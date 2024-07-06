package ai

import (
	"bytes"
	"context"
	"encoding/json"
	"fmt"
	"github.com/AEVKISELEV/symfony-hktn/internal/gpt"
	"github.com/AEVKISELEV/symfony-hktn/internal/proxyclient"
	"github.com/sashabaranov/go-openai"
	"go.uber.org/zap"
	"net/http"
)

type TextData struct {
	Post     TextPost
	Comments []TextComment
}

func (td TextData) String() string {
	post := fmt.Sprintf("%s || %d", td.Post.Text, td.Post.Likes)
	for _, comment := range td.Comments {
		post += fmt.Sprintf(" |||| %s: %s || %d", comment.Username, comment.Text, comment.Likes)
	}

	return post
}

type TextPost struct {
	Text  string
	Likes int
}

type TextComment struct {
	Username string
	Text     string
	Likes    int
}

type TextGeneratorConfig struct {
	OpenAIToken string
}

type TextGenerator struct {
	config   TextGeneratorConfig
	endpoint string

	logger *zap.Logger

	assistant *gpt.Assistant
}

func NewTextGenerator(config TextGeneratorConfig, endpoint string, logger *zap.Logger) (*TextGenerator, error) {
	generator := TextGenerator{
		config:   config,
		endpoint: endpoint,
		logger:   logger,
	}

	preprompt, err := loadTextPrepromt()
	if err != nil {
		return nil, fmt.Errorf("cannot load text preprompt: %w", err)
	}

	assistant, err := gpt.NewAssistant(config.OpenAIToken, preprompt, openai.GPT4o)
	if err != nil {
		return nil, fmt.Errorf("cannot create assistant: %w", err)
	}

	generator.assistant = assistant

	return &generator, nil
}

func loadTextPrepromt() (string, error) {
	return `
Ты - аналитик в компании, который занимается анализом постов в группе социальной сети, посвященной компании, в которой ты работаешь. Тебе нужно проанализировать пост, сделанный в группу, и комментарии, которые в этой группе были оставлены.

На основе этих данных тебе нужно:
1. Дать общую оценку посту и комментариям.
2. Выдать рекомендации по улучшению поста.

**Критерии оценки поста:**
- Текст: Ясность, грамотность, соответствие целевой аудитории.
- Стилистика: Тон, стиль, соответствие бренду.

**Критерии оценки комментариев:**
- Общее настроение: Позитивное, негативное, нейтральное.
- Основные темы: Что больше всего нравится и не нравится пользователям.

**Формат ответа:**
1. **Общая оценка поста:**
- Текст: [оценка]
- Стилистика: [оценка]

2. **Общая оценка комментариев:**
- Настроение: [описание настроения]
- Основные темы: [описание тем]

3. **Рекомендации по улучшению поста:**
- [рекомендация 1]
- [рекомендация 2]

**Пример ответа:**
1. **Общая оценка поста:**
- Текст: Ясный и грамотный, но можно улучшить структуру.
- Стилистика: Соответствует бренду, но слишком формальный тон.

2. **Общая оценка комментариев:**
- Настроение: В основном позитивное, но есть несколько негативных комментариев.
- Основные темы: Пользователям нравится визуальный контент, но они жалуются на сложность навигации.

3. **Рекомендации по улучшению поста:**
- Упростить структуру текста.
- Смягчить тон, сделать его более дружелюбным.

Используй в ответе просто текст. Не пиши в формате markdown.

Данные будут приходить в следующем формате:
*текст поста* || *количество лайков к посту* |||| *Имя пользователя, оставившего комментарий*: *текст комментария 1* || *количество лайков к комментарию 1* |||| ... |||| *Имя пользователя, оставившего комментарий*: *текст комментария N* || *количество лайков к комментарию N*
`, nil
}

func (tg *TextGenerator) GenerateText(id string, data TextData) error {
	rs, err := tg.assistant.Ask(context.TODO(), data.String())
	if err != nil {
		return fmt.Errorf("cannot generate text: %w", err)
	}

	tg.logger.Debug("generate new text", zap.String("id", id), zap.String("result", rs))

	return tg.sendCallback(id, rs)
}

func (tg *TextGenerator) sendCallback(id, text string) error {
	client, err := proxyclient.ProxyAwareHttpClient(true)
	if err != nil {
		return err
	}

	cr := CallbackResponse{
		ID:      id,
		Content: text,
	}

	b, err := json.Marshal(cr)
	if err != nil {
		return fmt.Errorf("cannot marshal callback response: %w", err)
	}

	resp, err := client.Post(tg.endpoint, "application/json", bytes.NewBuffer(b))
	if err != nil {
		return fmt.Errorf("cannot send request: %w", err)
	}

	tg.logger.Info("Send analytics", zap.String("id", id), zap.String("endpoint", tg.endpoint))

	if resp.StatusCode != http.StatusOK {
		b := bytes.Buffer{}
		n, err := b.ReadFrom(resp.Body)

		if err != nil {
			return fmt.Errorf("unexpected status code: %d; cannot read body: %w", resp.StatusCode, err)
		}
		if n == 0 {
			return fmt.Errorf("unexpected status code: %d; empty body", resp.StatusCode)
		}

		return fmt.Errorf("unexpected status code: %d; body: %s", resp.StatusCode, string(b.Bytes()))
	}

	tg.logger.Info("callback sent", zap.String("id", id))

	return nil
}

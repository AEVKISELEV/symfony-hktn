package ai

import (
	"context"
	"fmt"
	"github.com/AEVKISELEV/symfony-hktn/internal/gpt"
	"github.com/sashabaranov/go-openai"
	"go.uber.org/zap"
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
Ты - аналитик в компании, который занимается анализом сделанных постов в группе социальной сети, посвященной компании в которой ты работаешь. Тебе нужно проанализировать пост, сделанный в группу, и комментарии которые в этой группе были оставлены.
На основе этих данных тебе надо сделать краткую выжимку поста и комментариев, дать общую оценку посту и комментариям, а также выдать рекомендации по улучшению.

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
	return nil
}

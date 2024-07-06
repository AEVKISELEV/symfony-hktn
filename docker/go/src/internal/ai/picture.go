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

type PictureGenerator struct {
	generator *gpt.PictureGenerator
	logger    *zap.Logger
	endpoint  string
}

type PictureData struct {
	TextData
}

func NewPictureGenerator(token string, endpoint string, logger *zap.Logger) (*PictureGenerator, error) {
	g, err := gpt.NewPictureGenerator(token)
	if err != nil {
		return nil, fmt.Errorf("cannot generate picture")
	}

	return &PictureGenerator{
		generator: g,
		logger:    logger,
		endpoint:  endpoint,
	}, nil
}

func loadPicturePrepromt() string {
	return `
Создай изображение, которое визуально описывает состояние людей, оставивших комментарии к посту в социальной сети компании. Используй данные о тексте поста, количестве лайков к посту и комментариях пользователей с количеством лайков к каждому комментарию, чтобы передать настроение и эмоции комментаторов.

Формат данных:
*текст поста* || *количество лайков к посту* |||| *Имя пользователя, оставившего комментарий*: *текст комментария 1* || *количество лайков к комментарию 1* |||| ... |||| *Имя пользователя, оставившего комментарий*: *текст комментария N* || *количество лайков к комментарию N*

Сами данные:
`
}

func (tg *PictureGenerator) GenerateImage(id string, data PictureData) error {
	rs, err := tg.generator.GeneratePicture(context.Background(), loadPicturePrepromt()+data.String(), openai.CreateImageSize1024x1024)
	if err != nil {
		return fmt.Errorf("cannot generate picture: %w", err)
	}

	tg.logger.Debug("generate new picture", zap.String("id", id), zap.String("result", rs))

	return tg.sendCallback(id, rs)
}

func (tg *PictureGenerator) sendCallback(id, text string) error {
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
		return fmt.Errorf("cannot send request for pic: %w", err)
	}

	tg.logger.Info("Send analytics picture", zap.String("id", id), zap.String("endpoint", tg.endpoint))

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

	tg.logger.Info("callback picture sent", zap.String("id", id))

	return nil
}

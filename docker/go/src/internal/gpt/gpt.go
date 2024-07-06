package gpt

import (
	"context"
	"fmt"
	"github.com/AEVKISELEV/symfony-hktn/internal/proxyclient"
	"time"
)
import "github.com/sashabaranov/go-openai"

type Assistant struct {
	client    *openai.Client
	assistant *openai.Assistant

	thread *openai.Thread
}

func createOpenAIClient(token string) (*openai.Client, error) {
	clientConfig := openai.DefaultConfig(token)

	var err error
	clientConfig.HTTPClient, err = proxyclient.ProxyAwareHttpClient()
	if err != nil {
		return nil, fmt.Errorf("cannot create proxy client: %w", err)
	}

	return openai.NewClientWithConfig(clientConfig), nil
}
func NewAssistant(token, prepromt, model string) (*Assistant, error) {
	ctx := context.Background()
	ctx, cancel := context.WithTimeout(ctx, 10*time.Second)
	defer cancel()

	assistantName := "assistant"

	client, err := createOpenAIClient(token)
	if err != nil {
		return nil, fmt.Errorf("cannot create openai client: %w", err)
	}

	assistant, err := initAssistant(client, assistantName, prepromt, model)
	if err != nil {
		return nil, fmt.Errorf("cannot init assistant: %w", err)
	}

	gpt := Assistant{
		client:    client,
		assistant: assistant,
	}

	return &gpt, nil
}

func initAssistant(client *openai.Client, name string, preprompt string, model string) (*openai.Assistant, error) {
	ctx := context.Background()
	ctx, cancel := context.WithTimeout(ctx, 10*time.Second)
	defer cancel()

	assistant, err := client.CreateAssistant(ctx, openai.AssistantRequest{
		Model:        model,
		Name:         &name,
		Instructions: &preprompt,
	})
	if err != nil {
		return nil, fmt.Errorf("cannot create assistant: %w", err)
	}

	return &assistant, nil
}

func (a *Assistant) Ask(ctx context.Context, text string) (string, error) {
	thread, err := a.getThread(ctx)
	if err != nil {
		return "", fmt.Errorf("cannot load thread: %w", err)
	}

	_, err = a.client.CreateMessage(ctx, thread.ID, openai.MessageRequest{
		Role:    openai.ChatMessageRoleUser,
		Content: text,
	})

	if err != nil {
		return "", fmt.Errorf("cannot create message: %w", err)
	}

	run, err := a.client.CreateRun(ctx, thread.ID, openai.RunRequest{
		AssistantID: a.assistant.ID,
	})
	if err != nil {
		return "", fmt.Errorf("cannot create run: %w", err)
	}

	for {
		<-time.After(3 * time.Second)
		run, err = a.client.RetrieveRun(ctx, thread.ID, run.ID)
		if err != nil {
			return "", fmt.Errorf("cannot retrieve run: %w", err)
		}
		if run.Status == openai.RunStatusCompleted || run.Status == openai.RunStatusFailed {
			break
		}
	}

	if run.Status != openai.RunStatusCompleted {
		return "", fmt.Errorf("run status is not completed: %s", run.Status)
	}

	l, err := a.client.ListMessage(ctx, thread.ID, nil, nil, nil, nil)

	return l.Messages[0].Content[0].Text.Value, nil
}

func (a *Assistant) getThread(ctx context.Context) (openai.Thread, error) {
	if a.thread != nil {
		return *a.thread, nil
	}

	thread, err := a.client.CreateThread(ctx, openai.ThreadRequest{})
	if err != nil {
		return openai.Thread{}, fmt.Errorf("cannot create thread: %w", err)
	}

	a.thread = &thread
	return thread, nil
}

// PICTURE SECTION

type PictureGenerator struct {
	client *openai.Client
}

func NewPictureGenerator(token string) (*PictureGenerator, error) {
	client, err := createOpenAIClient(token)
	if err != nil {
		return nil, fmt.Errorf("cannot create openai client: %w", err)
	}

	return &PictureGenerator{client: client}, nil
}

func (pg *PictureGenerator) GeneratePicture(ctx context.Context, prompt string, imageSize string) (string, error) {
	reqUrl := openai.ImageRequest{
		Prompt:         prompt,
		Size:           imageSize,
		ResponseFormat: openai.CreateImageResponseFormatURL,
		Model:          openai.CreateImageModelDallE3,
		N:              1,
	}

	respUrl, err := pg.client.CreateImage(ctx, reqUrl)
	if err != nil {
		return "", fmt.Errorf("cannot create image: %w", err)
	}

	return respUrl.Data[0].URL, nil
}

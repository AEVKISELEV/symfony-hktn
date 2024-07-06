package ai

type TextData struct {
}

type TextGeneratorConfig struct {
	OpenAIToken string
}

type TextGenerator struct {
	config   TextGeneratorConfig
	endpoint string
}

func NewTextGenerator(config TextGeneratorConfig, endpoint string) (*TextGenerator, error) {
	generator := TextGenerator{
		config:   config,
		endpoint: endpoint,
	}

	return &generator, nil
}

func (tg *TextGenerator) GenerateText(id string, data TextData) error {
	// TODO: generate and send by HTTP
}

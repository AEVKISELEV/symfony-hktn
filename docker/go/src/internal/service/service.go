package service

import (
	"encoding/json"
	"fmt"
	"github.com/AEVKISELEV/symfony-hktn/internal/ai"
	"github.com/AEVKISELEV/symfony-hktn/internal/logger"
	"github.com/AEVKISELEV/symfony-hktn/internal/rabbit"
	"github.com/AEVKISELEV/symfony-hktn/internal/util"
	amqp "github.com/rabbitmq/amqp091-go"
	"go.uber.org/zap"
	"os"
	"strconv"
)

type Service struct {
	config Config

	logger *zap.Logger
	rabbit *rabbit.Rabbit

	// generators
	aiText *ai.TextGenerator
}

type Config struct {
	RabbitConfig RabbitConfig

	AITextConfig ai.TextGeneratorConfig
}

type RabbitConfig struct {
	Password  string
	Username  string
	Host      string
	Port      int
	AdminPort int
}

func (rc RabbitConfig) Address() string {
	return fmt.Sprintf("amqp://%s:%s@%s:%d", rc.Username, rc.Password, rc.Host, rc.Port)
}

func Start() error {
	s := Service{}
	var err error

	s.logger, err = logger.BuildLogger()
	if err != nil {
		return fmt.Errorf("cannot build logger: %w", err)
	}

	s.config, err = readConfig()
	if err != nil {
		return fmt.Errorf("cannot read config: %w", err)
	}

	s.rabbit, err = rabbit.New(s.config.RabbitConfig.Address())
	if err != nil {
		return fmt.Errorf("cannot create rabbit instance")
	}

	s.aiText, err = ai.NewTextGenerator(s.config.AITextConfig, "http://nginx/api/v1/ai/generate", s.logger)
	if err != nil {
		return fmt.Errorf("cannot create ai text generator: %w", err)
	}

	err = s.Listen()
	if err != nil {
		return fmt.Errorf("cannot start listening: %w", err)
	}

	return nil
}

func (s *Service) Listen() error {
	s.logger.Info("Service start listening")

	err := s.routeQueue("analysis", s.handleAnalysis)
	if err != nil {
		return fmt.Errorf("cannot route analysis queue: %w", err)
	}

	return nil
}

type QueueData struct {
	ID      string `json:"id"`
	Type    string `json:"type"`
	Content any    `json:"content"`
}

func (s *Service) handleAnalysis(d amqp.Delivery) error {
	s.logger.Info("handle analysis", zap.String("body", string(d.Body)))

	var data QueueData
	err := json.Unmarshal(d.Body, &data)
	if err != nil {
		return fmt.Errorf("cannot unmarshal body: %w", err)
	}

	switch data.Type {
	case "text":
		var textData ai.TextData
		err = util.ParseJSONData(data.Content, &textData)
		if err == nil {
			return s.aiText.GenerateText(data.ID, textData)
		}
	default:
		err = fmt.Errorf("unknown type '%s'", data.Type)
	}

	if err != nil {
		return fmt.Errorf("cannot parse data from queue: %w", err)
	}

	return nil
}

func (s *Service) routeQueue(queueName string, handler func(d amqp.Delivery) error) error {
	msgs, err := s.rabbit.CreateQueue(queueName)
	if err != nil {
		return fmt.Errorf("cannot create queue: %w", err)
	}

	go func() {
		for msg := range msgs {
			err := handler(msg)
			if err != nil {
				s.logger.Error("cannot handle message", zap.String("queue_name", queueName), zap.Error(err))
			}
		}
	}()

	return nil
}

func readConfig() (Config, error) {
	rabbitCfg, err := readRabbitConfig()
	if err != nil {
		return Config{}, fmt.Errorf("cannot read rabbit config: %w", err)
	}

	aiTextCfg, err := readAITextConfig()
	if err != nil {
		return Config{}, fmt.Errorf("cannot read ai text config: %w", err)
	}

	return Config{
		RabbitConfig: rabbitCfg,

		AITextConfig: aiTextCfg,
	}, nil
}

func readRabbitConfig() (RabbitConfig, error) {
	passwd, ok := os.LookupEnv("RABBIT_PASSWORD")
	if !ok {
		return RabbitConfig{}, fmt.Errorf("cannot read RABBIT_PASSWORD")
	}

	user, ok := os.LookupEnv("RABBIT_USERNAME")
	if !ok {
		return RabbitConfig{}, fmt.Errorf("cannot read RABBIT_USERNAME")
	}

	host, ok := os.LookupEnv("RABBIT_HOST")
	if !ok {
		return RabbitConfig{}, fmt.Errorf("cannot read RABBIT_HOST")
	}

	port, ok := os.LookupEnv("RABBIT_PORT")
	if !ok {
		return RabbitConfig{}, fmt.Errorf("cannot read RABBIT_PORT")
	}

	portInt, err := strconv.Atoi(port)
	if err != nil {
		return RabbitConfig{}, fmt.Errorf("cannot convert RABBIT_PORT to int: %w", err)
	}

	adminPort, ok := os.LookupEnv("RABBIT_ADMIN_PORT")
	if !ok {
		return RabbitConfig{}, fmt.Errorf("cannot read RABBIT_ADMIN_PORT")
	}

	adminPortInt, err := strconv.Atoi(adminPort)
	if err != nil {
		return RabbitConfig{}, fmt.Errorf("cannot convert RABBIT_ADMIN_PORT to int: %w", err)
	}

	return RabbitConfig{
		Password:  passwd,
		Username:  user,
		Host:      host,
		Port:      portInt,
		AdminPort: adminPortInt,
	}, nil
}

func readAITextConfig() (ai.TextGeneratorConfig, error) {
	token, ok := os.LookupEnv("OPENAI_TOKEN")
	if !ok {
		return ai.TextGeneratorConfig{}, fmt.Errorf("cannot read OPENAI_TOKEN")
	}

	return ai.TextGeneratorConfig{
		OpenAIToken: token,
	}, nil
}

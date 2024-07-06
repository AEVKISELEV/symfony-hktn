package service

import (
	"fmt"
	"github.com/AEVKISELEV/symfony-hktn/internal/logger"
	"go.uber.org/zap"
	"os"
)

type Service struct {
	logger *zap.Logger
	config Config
}

type Config struct {
	RabbitMQAddress string
}

func readConfig() (Config, error) {
	rabbit, ok := os.LookupEnv("RABBITMQ_ADDRESS")
	if !ok {
		return Config{}, fmt.Errorf("cannot read rabbitmq address from env")
	}

	return Config{
		RabbitMQAddress: rabbit,
	}, nil
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

	s.logger.Info("Service started")

	return nil
}

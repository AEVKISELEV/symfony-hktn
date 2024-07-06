package service

import (
	"fmt"
	"github.com/AEVKISELEV/symfony-hktn/internal/logger"
	"go.uber.org/zap"
)

type Service struct {
	logger *zap.Logger
}

func Start() error {

	s := Service{}
	var err error

	s.logger, err = logger.BuildLogger()
	if err != nil {
		return fmt.Errorf("cannot build logger: %w", err)
	}

	s.logger.Info("Service started")

	return nil
}

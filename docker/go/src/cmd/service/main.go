package main

import (
	"github.com/AEVKISELEV/symfony-hktn/internal/service"
	"log"
)

func main() {
	err := service.Start()
	if err != nil {
		log.Fatalf("Service was crashed: %s", err.Error())
	}
}

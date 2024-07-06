package main

import "github.com/AEVKISELEV/symfony-hktn/internal/service"

func main() {
	err := service.Start()
	if err != nil {
		panic("Error while start service")
	}
}

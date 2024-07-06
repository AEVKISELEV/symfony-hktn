package rabbit

import (
	"fmt"
	amqp "github.com/rabbitmq/amqp091-go"
)

type Rabbit struct {
	conn *amqp.Connection
}

func New(connAddr string) (*Rabbit, error) {
	conn, err := amqp.Dial(connAddr)
	if err != nil {
		return nil, fmt.Errorf("cannot connect to rabbit: %w", err)
	}

	return &Rabbit{conn: conn}, nil
}

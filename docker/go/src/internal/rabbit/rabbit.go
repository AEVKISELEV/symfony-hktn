package rabbit

import (
	"fmt"
	amqp "github.com/rabbitmq/amqp091-go"
)

type Rabbit struct {
	conn    *amqp.Connection
	channel *amqp.Channel
}

func New(connAddr string) (*Rabbit, error) {
	conn, err := amqp.Dial(connAddr)
	if err != nil {
		return nil, fmt.Errorf("cannot connect to rabbit: %w", err)
	}

	channel, err := conn.Channel()
	if err != nil {
		return nil, fmt.Errorf("cannot create channel: %w", err)
	}

	return &Rabbit{conn: conn, channel: channel}, nil
}

func (r *Rabbit) Destroy() error {
	err := r.channel.Close()
	if err != nil {
		return fmt.Errorf("cannot close channel: %w", err)
	}

	err = r.conn.Close()
	if err != nil {
		return fmt.Errorf("cannot close connection: %w", err)
	}

	return nil
}

func (r *Rabbit) CreateQueue(name string) (<-chan amqp.Delivery, error) {
	// Объявление очереди
	q, err := r.channel.QueueDeclare(
		name,  // имя очереди
		false, // durable
		false, // delete when unused
		false, // exclusive
		false, // no-wait
		nil,   // arguments
	)
	if err != nil {
		return nil, fmt.Errorf("failed to declare a queue: %w", err)
	}

	// Регистрация consumer
	msgs, err := r.channel.Consume(
		q.Name, // имя очереди
		"",     // consumer
		true,   // auto-ack
		false,  // exclusive
		false,  // no-local
		false,  // no-wait
		nil,    // arguments
	)
	if err != nil {
		return nil, fmt.Errorf("failed to register a consumer: %w", err)
	}

	return msgs, nil
}

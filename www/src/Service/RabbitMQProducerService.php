<?php

namespace App\Service;

use App\Contract\RabbitMQMessage;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQProducerService
{
    private readonly AMQPStreamConnection $connection;
    private readonly AMQPChannel $channel;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $host = getenv('RABBITMQ_HOST');
        $port = getenv('RABBITMQ_PORT');
        $user = getenv('RABBITMQ_USER_NAME');
        $password = getenv('RABBITMQ_PASSWORD');

        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
    }

    public function sendMessage(RabbitMQMessage $message, string $queueName): void
    {
        $this->channel->queue_declare($queueName, false, true, false, false);

        $msg = new AMQPMessage($message->toString(), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->basic_publish($msg, '', $queueName);

        $this->closeConnection();
    }

    private function closeConnection(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}
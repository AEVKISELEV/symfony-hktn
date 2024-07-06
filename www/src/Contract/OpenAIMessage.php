<?php

namespace App\Contract;

class OpenAIMessage implements RabbitMQMessage
{

    public function toString(): string
    {
        return 'Your message';
    }
}
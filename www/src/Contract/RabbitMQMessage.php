<?php

namespace App\Contract;

interface RabbitMQMessage
{
    public function toString(): string;
}
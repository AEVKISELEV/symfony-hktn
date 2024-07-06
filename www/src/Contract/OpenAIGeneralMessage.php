<?php

namespace App\Contract;

class OpenAIGeneralMessage implements RabbitMQMessage
{
	private string $messageText;

	public function toString(): string
	{
		return $this->messageText;
	}

	public function getMessageText(): string
	{
		return $this->messageText;
	}

	public function setMessageText(string $messageText): static
	{
		$this->messageText = $messageText;

		return $this;
	}
}
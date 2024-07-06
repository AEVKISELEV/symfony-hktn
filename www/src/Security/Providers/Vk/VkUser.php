<?php

namespace App\Security\Providers\Vk;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class VkUser implements ResourceOwnerInterface
{
	private array $response;

	public function __construct(array $response)
	{
		$this->response = $response;
	}

	public function getId()
	{
		return $this->response['id'];
	}

	public function getEmail(): ?string
	{
		return $this->response['email'] || null;
	}

	public function getName(): ?string
	{
		return $this->response['first_name'] . $this->response['last_name'];
	}



	public function toArray(): array
	{
		return $this->response;
	}
}
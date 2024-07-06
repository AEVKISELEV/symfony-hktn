<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use VK\Client\VKApiClient;

class VkApiConnector
{
	private VKApiClient $apiClient;
	private RequestStack $requestStack;
	private TokenStorageInterface $tokenStorage;

	public function __construct(
		RequestStack $requestStack,
		TokenStorageInterface $tokenStorage,
	)
	{
		$this->apiClient = new \VK\Client\VKApiClient();
		$this->tokenStorage = $tokenStorage;
		$this->requestStack = $requestStack;
	}

	public function getGroups(): array
	{
		return
			$this->apiClient->groups()->get(
				$this->requestStack->getSession()->get('access_token'),
				[
					'user_id' => $this->tokenStorage
						->getToken()
						->getUser()
						->getUserIdentifier(),
					'extended' => 1,
					'filter' => 'moder'
				],
			);
	}
}
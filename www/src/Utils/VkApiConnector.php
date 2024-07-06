<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\RequestStack;
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
					'user_id' => $this->requestStack->getSession()->get('user_id'),

					'extended' => 1,
					'filter' => 'moder',
				],
			);
	}

	public function getPosts(string $groupId, int $count = 100): array
	{
		return
			$this->apiClient->wall()->get(
				$this->requestStack->getSession()->get('access_token'),
				[
					'owner_id' => $groupId,
					'count' => $count,
					'extended' => 1,
				]
			);
	}

	public function getPost(string $groupId, string $postId): array
	{
		return
			$this->apiClient->wall()->getById(
				$this->requestStack->getSession()->get('access_token'),
				[
					'posts' => $groupId.'_'.$postId,
					'extended' => 1,
				]
			);
	}

	public function getComments(string $groupId, int $postId, int $count = 100): array
	{
		return
			$this->apiClient->wall()->getComments(
				$this->requestStack->getSession()->get('access_token'),
				[
					'owner_id' => $groupId,
					'post_id' => $postId,
					'count' => $count,
					'extended' => 1,
				]
			);
	}
}
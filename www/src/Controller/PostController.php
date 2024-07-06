<?php

namespace App\Controller;

use App\Utils\VkApiConnector;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends BaseController
{
	#[Route(path: "/api/v1/posts/{groupId}", name: "app_posts", methods: ["GET"])]
	public function postsList(string $groupId, VkApiConnector $vkApiConnector): Response
	{
		return $this->json($vkApiConnector->getPosts($groupId));
	}
	#[Route(path: "/api/v1/posts/{groupId}/{postId}", name: "app_post", methods: ["GET"])]
	public function getPost(string $groupId, int $postId, VkApiConnector $vkApiConnector): Response
	{
		return $this->json($vkApiConnector->getPost($groupId, $postId));
	}
}
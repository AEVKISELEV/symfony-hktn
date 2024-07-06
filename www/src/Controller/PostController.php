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
}
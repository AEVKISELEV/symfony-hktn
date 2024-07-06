<?php

namespace App\Controller;

use App\Utils\VkApiConnector;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GroupController extends BaseController
{
	#[Route(path: "/api/v1/groups", name: "app_groups_list", methods: "GET")]
	public function groupsList(VkApiConnector $vkApiConnector): Response
	{
		$groups = $vkApiConnector->getGroups();

		$result = [
			[
				'id' => 1,
				'title' => 'Музыка и клипы',
				'description' => 'Все самые новые и популярные музыкальные клипы в одном месте.',
			],
			[
				'id' => 2,
				'title' => 'Новости IT и технологий',
				'description' => 'Свежие новости из мира технологий, гаджетов и IT.',
			],
			[
				'id' => 3,
				'title' => 'Путешествия по миру',
				'description' => 'Лучшие советы и истории о путешествиях по всему миру.',
			],
			[
				'id' => 4,
				'title' => 'Рецепты и кулинария',
				'description' => 'Вкусные рецепты и советы по приготовлению блюд.',
			],
			[
				'id' => 5,
				'title' => 'Фильмы и сериалы',
				'description' => 'Обсуждение новых фильмов и сериалов, трейлеры и рецензии.',
			],
			[
				'id' => 6,
				'title' => 'Здоровье и фитнес',
				'description' => 'Полезные советы по здоровому образу жизни и фитнесу.',
			],
			[
				'id' => 7,
				'title' => 'Автомобили и мотоциклы',
				'description' => 'Все о современных автомобилях и мотоциклах, тест-драйвы и обзоры.',
			],
			[
				'id' => 8,
				'title' => 'Юмор и развлечения',
				'description' => 'Подборка лучших шуток и смешных видео.',
			],
			[
				'id' => 9,
				'title' => 'Фотография и искусство',
				'description' => 'Красивая фотография и современное искусство.',
			],
			[
				'id' => 10,
				'title' => 'Наука и образование',
				'description' => 'Интересные факты и статьи о науке и образовании.',
			],
		];

		return $this->json($result);
	}

	#[Route(path: "/api/v1/groups", name: "app_create_group", methods: ["POST"])]
	#[OA\RequestBody(
		description: "Create a new group",
		required: true,
		content: new OA\JsonContent(
			properties: [new OA\Property(property: "link", type: "string", description: "The link for the group")],
			type:       "object",
		)
	)]
	#[OA\Response(
		response: 200,
		description: "Returns the rewards of a user",
		content: new OA\JsonContent(
			properties: [
							new OA\Property(property: "status", type: "string", description: "Response status"),
							new  OA\Property(property: "data", type: "object", description: "Request data"),
						],
			type:       "object",
		)
	)]
	public function createGroup(Request $request): Response
	{
		$data = json_decode($request->getContent(), true);
		$link = $data['link'] ?? null;
		if ($link === null)
		{
			return $this->json(['success' => false, 'error' => 'has no link msf']);
		}

		return $this->json(
			[
				'status' => 'success',
				'data' => $data,
			],
		);
	}
}
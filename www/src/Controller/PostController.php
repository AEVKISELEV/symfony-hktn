<?php

namespace App\Controller;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
	#[Route(path: "/api/v1/posts/{groupId}", name: "app_posts", methods: ["GET"])]
	public function postsList(int $groupId): Response
	{
		$result = [
			[
				'id' => 1,
				'group_id' => $groupId,
				'title' => 'Приветственное сообщение',
				'author' => 'Иван Иванов',
				'content' => 'Добро пожаловать в нашу группу! Здесь мы будем делиться новыми музыкальными клипами и обсуждать их.',
				'dateCreate' => (new DateTimeImmutable('2023-07-01 12:00:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 120,
				'commentsAmount' => 15,
			],
			[
				'id' => 2,
				'group_id' => $groupId,
				'title' => 'Новый клип!',
				'author' => 'Мария Петрова',
				'content' => 'Новый клип от популярного артиста уже здесь! Смотрите и делитесь своими впечатлениями.',
				'dateCreate' => (new DateTimeImmutable('2023-07-02 14:30:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 200,
				'commentsAmount' => 25,
			],
			[
				'id' => 3,
				'group_id' => $groupId,
				'title' => 'Свежие новости',
				'author' => 'Анна Смирнова',
				'content' => 'Свежие новости из мира технологий: новые функции в последнем обновлении ОС.',
				'dateCreate' => (new DateTimeImmutable('2023-07-03 09:15:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 80,
				'commentsAmount' => 10,
			],
			[
				'id' => 4,
				'group_id' => $groupId,
				'title' => 'Обзор гаджета',
				'author' => 'Павел Сидоров',
				'content' => 'Новый гаджет на рынке: стоит ли покупать? Наш обзор и мнения экспертов.',
				'dateCreate' => (new DateTimeImmutable('2023-07-04 16:45:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 150,
				'commentsAmount' => 20,
			],
			[
				'id' => 5,
				'group_id' => $groupId,
				'title' => 'Лучшие места для отдыха',
				'author' => 'Екатерина Федорова',
				'content' => 'Лучшие места для летнего отдыха: наши рекомендации.',
				'dateCreate' => (new DateTimeImmutable('2023-07-05 11:00:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 95,
				'commentsAmount' => 5,
			],
			[
				'id' => 6,
				'group_id' => $groupId,
				'title' => 'Путешествие в Японию',
				'author' => 'Дмитрий Кузнецов',
				'content' => 'История моего путешествия в Японию: что стоит увидеть и как подготовиться.',
				'dateCreate' => (new DateTimeImmutable('2023-07-06 10:30:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 130,
				'commentsAmount' => 8,
			],
			[
				'id' => 7,
				'group_id' => $groupId,
				'title' => 'Рецепт недели',
				'author' => 'Светлана Орлова',
				'content' => 'Рецепт недели: восхитительный торт на все случаи жизни.',
				'dateCreate' => (new DateTimeImmutable('2023-07-07 13:15:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 110,
				'commentsAmount' => 12,
			],
			[
				'id' => 8,
				'group_id' => $groupId,
				'title' => 'Идеальный завтрак',
				'author' => 'Алексей Николаев',
				'content' => 'Как приготовить идеальный завтрак за 15 минут: советы и рецепты.',
				'dateCreate' => (new DateTimeImmutable('2023-07-08 08:45:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 90,
				'commentsAmount' => 10,
			],
			[
				'id' => 9,
				'group_id' => $groupId,
				'title' => 'Обзор нового фильма',
				'author' => 'Ольга Морозова',
				'content' => 'Обзор нового фильма: стоит ли идти в кинотеатр?',
				'dateCreate' => (new DateTimeImmutable('2023-07-09 17:30:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 140,
				'commentsAmount' => 18,
			],
			[
				'id' => 10,
				'group_id' => $groupId,
				'title' => 'Лучшие сериалы месяца',
				'author' => 'Владимир Васильев',
				'content' => 'Лучшие сериалы этого месяца: что посмотреть на выходных.',
				'dateCreate' => (new DateTimeImmutable('2023-07-10 20:00:00'))->format(DateTimeInterface::ATOM),
				'likesAmount' => 160,
				'commentsAmount' => 22,
			],
		];

		return $this->json($result);
	}
}
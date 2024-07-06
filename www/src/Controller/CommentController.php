<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentController extends BaseController
{
	#[Route(path: "/api/v1/comments/{postId}", name: "app_comments", methods: ["GET"])]
	public function commentsList(int $postId): Response
	{
		$comments = [
			[
				'id' => 1,
				'post_id' => $postId,
				'author' => 'Алексей Петров',
				'content' => 'Отличный пост, спасибо за информацию!',
				'dateCreate' => (new DateTimeImmutable('2023-07-11 09:30:00'))->format(\DateTime::ATOM),
				'likesAmount' => 5,
			],
			[
				'id' => 2,
				'post_id' => $postId,
				'author' => 'Елена Сидорова',
				'content' => 'Полностью согласна с автором. Хороший выбор темы для обсуждения.',
				'dateCreate' => (new DateTimeImmutable('2023-07-11 10:15:00'))->format(\DateTime::ATOM),
				'likesAmount' => 3,
			],
			[
				'id' => 3,
				'post_id' => $postId,
				'author' => 'Иван Иванов',
				'content' => 'Не согласен с предыдущим комментарием. Мне кажется, что...',
				'dateCreate' => (new DateTimeImmutable('2023-07-11 11:00:00'))->format(\DateTime::ATOM),
				'likesAmount' => 7,
			],
			[
				'id' => 4,
				'post_id' => $postId,
				'author' => 'Мария Николаева',
				'content' => 'Вопрос к автору: можно ли получить дополнительную информацию на эту тему?',
				'dateCreate' => (new DateTimeImmutable('2023-07-11 12:30:00'))->format(\DateTime::ATOM),
				'likesAmount' => 2,
			],
			[
				'id' => 5,
				'post_id' => $postId,
				'author' => 'Анна Павлова',
				'content' => 'Очень интересная статья. Буду ждать продолжения!',
				'dateCreate' => (new DateTimeImmutable('2023-07-11 13:45:00'))->format(\DateTime::ATOM),
				'likesAmount' => 4,
			],
		];

		return $this->json($comments);
	}
}
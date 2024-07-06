<?php

namespace App\Controller;

use App\Contract\OpenAIGeneralMessage;
use App\Entity\Ai\Post\Analytic\GenerateResult;
use App\Repository\Ai\Post\Analytic\GenerateResultRepository;
use App\Service\RabbitMQProducerService;
use App\Utils\VkApiConnector;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AiController extends BaseController
{
	#[Route(path: "/api/v1/ai/generate", name: "app_ai_generate", methods: ["POST"])]
	public function set(
		Request $request,
		GenerateResultRepository $generateResultRepository,
	): Response
	{
		$jsonka = json_decode($request->getContent());
		$id = (string)($jsonka['id'] ?? 0);
		$content = (string)($jsonka['content'] ?? '');
		if (!$id)
		{
			return $this->jsonResponseWithError('has no post by id');
		}
		if ($content === '')
		{
			return $this->json(['status' => 'error', 'message' => 'contentIsEmpty'], 400);
		}

		[$postId, $groupId] = explode('.', $id);
		$generateResult = new GenerateResult();
		$generateResult->content = $content;
		$generateResult->vkPostId = $postId;
		$generateResult->vkGroupId = $groupId;
		$generateResult->dateCreate = new \DateTimeImmutable();

		try
		{
			$generateResultRepository->save($generateResult);
		}
		catch (\Throwable $throwable)
		{
			return $this->json(['status' => 'error', 'message' => 'exception: ' . $throwable->getMessage()], 400);
		}

		return $this->json(['status' => 'ok']);
	}

	#[Route(path: "/api/v1/analytic/generate", name: "app_ai_analytic", methods: ["POST"])]
	#[OA\RequestBody(
		description: "Create a new group",
		required: true,
		content: new OA\JsonContent(
			properties: [
							new OA\Property(property: "postId", type: "string"),
							new OA\Property(property: "groupId", type: "string"),
						],
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
	public function send(
		Request $request,
		VkApiConnector $vkApiConnector,
		RabbitMQProducerService $producerService,
	): Response
	{
		$jsonka = json_decode($request->getContent(), true);
		$postId = (string)($jsonka['postId'] ?? 0);
		if (!$postId)
		{
			return $this->jsonResponseWithError('has no post by id');
		}
		$groupId = (string)($jsonka['groupId'] ?? 0);
		if (!$groupId)
		{
			return $this->jsonResponseWithError('has no group by id');
		}
		$comments = $vkApiConnector->getComments($groupId, $postId);
		$post = $vkApiConnector->getPost($groupId, $postId);
		$commentsMe = [];

		foreach ($comments['items'] as $comment)
		{
			$user_id = $comment['from_id'];
			$user_profile = array_values(
								array_filter(
									$comments['profiles'],
									function($profile) use ($user_id) {
										return $profile['id'] == $user_id;
									},
								),
							)[0];

			$commentsMe[] = [
				'username' => $user_profile['first_name'] . ' ' . $user_profile['last_name'],
				'text' => $comment['text'],
				'likes' => $comment['likes']['count'] ?? 0,
				'attachments' => [],
			];
		}

		$post = $post['items'][0];
		$message = [
			'type' => "TEXT",
			'id' => $postId . '.' . $groupId,
			'content' => [
				'post' => [
					'text' => $post['text'],
					'likes' => $post['likes']['count'] ?? 0,
					'attachments' => [],
				],
				'comments' => $commentsMe,
			],
		];

		$producerService->sendMessage(new OpenAIGeneralMessage(json_encode($message)), 'analysis');

		return $this->json(
			[
				'message' => $message,
				'comments' => $comments,
				'post' => $post,
			],
		);

	}

	#[Route(path: "/api/v1/analytic/{postId}/{groupId}", name: "app_ai_analytic_by_post", methods: ["get"])]
	public function get(
		string $postId,
		string $groupId,
		GenerateResultRepository $generateResultRepository,
	): Response
	{
		$generateResult = $generateResultRepository->findBy(['vkPostId' => $postId, 'vkGroupId' => $groupId]);
		$res = [];
		foreach ($generateResult as $item)
		{
			$res[] = ['content' => $item->content, 'dateCreate' => $item->dateCreate];
		}

		return $this->json(
			[
				'status' => 'ok',
				'data' => $res,
			],
		);
	}
}
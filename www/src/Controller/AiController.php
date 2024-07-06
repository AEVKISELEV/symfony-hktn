<?php

namespace App\Controller;

use App\Entity\Ai\Post\Analytic\GenerateResult;
use App\Repository\Ai\Post\Analytic\GenerateResultRepository;
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
		$postId = (int)($jsonka['id'] ?? 0);
		$content = (string)($jsonka['content'] ?? '');
		if (!$postId)
		{
			return $this->jsonResponseWithError('has no post by id');
		}
		if ($content === '')
		{
			return $this->json(['status' => 'error', 'message' => 'contentIsEmpty'], 400);
		}
		$generateResult = new GenerateResult();
		$generateResult->content = $content;
		$generateResult->vkPostId = $postId;
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
			properties: [new OA\Property(property: "postId", type: "string", description: "The link for the group")],
			type: "object",
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
			type: "object",
		)
	)]
	public function send(
		Request $request,
		GenerateResultRepository $generateResultRepository,
	): Response
	{
		$jsonka = json_decode($request->getContent());
		$postId = (string)($jsonka['id'] ?? 0);
		if (!$postId)
		{
			return $this->jsonResponseWithError('has no post by id');
		}

		return $this->json(['status' => 'ok']);
	}

	#[Route(path: "/api/v1/analytic/{postId}", name: "app_ai_analytic_by_post", methods: ["get"])]
	public function get(
		int $postId,
		GenerateResultRepository $generateResultRepository,
	): Response
	{
		$generateResult = $generateResultRepository->find($postId);

		return $this->json(['status' => 'ok', 'data' => ['content' => $generateResult?->content, 'dateCreate' => $generateResult?->dateCreate]]);
	}
}
<?php

namespace App\Controller;

use App\Entity\Ai\Post\Analytic\GenerateResult;
use App\Repository\Ai\Post\Analytic\GenerateResultRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
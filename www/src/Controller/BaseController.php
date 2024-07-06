<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
	protected function jsonResponseWithError(string $message): JsonResponse
	{
		return $this->json(['status' => 'error', 'message' => $message], 400);
	}
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AiController extends AbstractController
{
	#[Route(path: "/api/v1/ai/generate", name: "app_ai_generate", methods: ["POST"])]
	public function commentsList(Request $request): Response
	{

	}
}
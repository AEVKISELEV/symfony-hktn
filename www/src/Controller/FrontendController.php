<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FrontendController extends AbstractController
{
	#[Route("/", 'app_index', methods: ['GET'])]
	public function index(): Response
	{
		if (!$this->getUser())
		{
			return $this->redirectToRoute('app_login');
		}

		return $this->render('index.html.twig');
	}
}
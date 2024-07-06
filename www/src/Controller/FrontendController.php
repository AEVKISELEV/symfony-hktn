<?php

namespace App\Controller;

use App\Utils\VkApiConnector;
use GuzzleHttp\ClientInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class FrontendController extends BaseController
{
	#[Route("/", 'app_index', methods: ['GET'])]
	public function index(SessionInterface $session, ClientRegistry $clientRegistry): Response
	{
		if (!$session->get('access_token'))
		{
			return $this->redirect('/login');
		}

		return $this->render('index.html.twig');
	}
}
<?php

namespace App\Controller;

use App\Security\Providers\Vk\VkProvider;
use App\Security\Providers\Vk\VkUser;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// #[Route('/vkontakte', name: 'connect_vkontakte_')]
class VKController extends AbstractController
{
	private $clientRegistry;

	public function __construct(ClientRegistry $clientRegistry)
	{
		$this->clientRegistry = $clientRegistry;
	}

	#[\Symfony\Component\Routing\Attribute\Route("/connect/vk", 'connect_vk_start', methods: ['GET'])]
	public function connectLifeHackerAction(Request $request)
	{
		/**
		 * @var VkProvider $provider
		 */
		return $this
			->clientRegistry
			->getClient('vkontakte')
			->redirect()
		;
	}

	#[\Symfony\Component\Routing\Attribute\Route("/connect/vk/check", 'connect_vk_check', methods: ['GET'])]
	public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
	{
		// ** if you want to *authenticate* the user, then
		// leave this method blank and create a Guard authenticator
		// (read below)

		/** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
		$client = $clientRegistry->getClient('vkontakte');

		try {
			// the exact class depends on which provider you're using
			/** @var VkUser $user */
			$user = $client->fetchUser();

			// do something with all this new power!
			// e.g. $name = $user->getFirstName();
			var_dump($user); die;
			// ...
		} catch (IdentityProviderException $e) {
			// something went wrong!
			// probably you should return the reason to the user
			var_dump($e->getMessage(),1); die;
		}
	}
}
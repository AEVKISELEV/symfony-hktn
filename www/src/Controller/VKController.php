<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\Providers\Vk\VkProvider;
use App\Security\Providers\Vk\VkUser;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

// #[Route('/vkontakte', name: 'connect_vkontakte_')]
class VKController extends AbstractController
{
	private $clientRegistry;

	public function __construct(ClientRegistry $clientRegistry)
	{
		$this->clientRegistry = $clientRegistry;
	}

	#[\Symfony\Component\Routing\Attribute\Route('/connect/vk', 'connect_vk_start', methods: ['GET'])]
	public function connectAction(Request $request)
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

	#[\Symfony\Component\Routing\Attribute\Route('/connect/force', 'connect_vk_start_force', methods: ['GET'])]
	public function connectLifeHackerAction(
		EntityManagerInterface $entityManager,
		EventDispatcherInterface $eventDispatcher,
		TokenStorageInterface $tokenStorage,
		RequestStack $requestStack,
		Request $request,
	)
	{
		$existingUser = $entityManager->getRepository(User::class)->findOneBy(['vkId' => 265055656]);

		if ($existingUser)
		{
			$this->auth($existingUser, $eventDispatcher, $tokenStorage, $requestStack, $request);
			return $this->redirect("/");
		}

		$user = new User();

		$user->setVkId(265055656);
		$user->setPassword(hash('sha1', 265055656));
		$user->setFirstname('Пользователь');
		$user->setLastname('Продукта');

		// $user->setRefreshToken();
		$user->setAccessToken('vk1.a.7OS2r1v7zCyG8XbVoFv3R7at2XMRXdN3wFmuNzaio8m19FKdArOsKYRpGIhtF2JvEObVdM5pI91gJbpNq55KFLiIRNABPFFpy-DAs8C6WxD7L-KsGYsYJTm7UHxqXY0zXAPujK787AZ0vllDCEhxc11sWgZgbGdEhvfHOl8V4Lr-3IApW_A7Bg9NFLi_JOYj');

		$entityManager->persist($user);
		$entityManager>flush();

		$this->auth($user, $eventDispatcher, $tokenStorage, $requestStack, $request);
		return $this->redirect("/");
	}

	private function auth(
		User $user,
		EventDispatcherInterface $eventDispatcher,
		TokenStorageInterface $tokenStorage,
		RequestStack $requestStack,
		Request $request
	)
	{
		$token = new UsernamePasswordToken($user, 'main', $user->getRoles());
		$tokenStorage->setToken($token->getUserIdentifier(), $token);

		// If you want to make authentication persistent across requests:
		$requestStack->getSession()->set('_security_main', serialize($token));
		$requestStack->getSession()->set('access_token', $user->getAccessToken());

		// Fire the login event manually
		$event = new InteractiveLoginEvent($request, $token);

		$eventDispatcher->dispatch($event, 'security.interactive_login');
	}

	#[\Symfony\Component\Routing\Attribute\Route('/connect/vk/check', 'connect_vk_check', methods: ['GET'])]
	public function connectCheckAction()
	{
	}
}
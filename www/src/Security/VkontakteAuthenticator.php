<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Providers\Vk\VkUser;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class VkontakteAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
	private $clientRegistry;
	private $entityManager;
	private $router;
	private $requestStack;

	public function __construct(
		ClientRegistry $clientRegistry,
		EntityManagerInterface $entityManager,
		RouterInterface $router,
		RequestStack $requestStack
	)
	{
		$this->clientRegistry = $clientRegistry;
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->requestStack = $requestStack;
	}
	public function getSession()
	{
		$request = $this->requestStack->getCurrentRequest();

		// Check if the session has been started
		if ($request->hasPreviousSession()) {
			// Get and return the session
			return $request->getSession();
		}
	}
	public function supports(Request $request): ?bool
	{
		return $request->attributes->get('_route') === 'connect_vk_check';
	}

	public function authenticate(Request $request): Passport
	{
		$client = $this->clientRegistry->getClient('vkontakte');
		$accessToken = $this->fetchAccessToken($client);

		return new SelfValidatingPassport(
			new UserBadge(
				$accessToken->getToken(), function() use ($accessToken, $client) {
				/** @var VkUser $vkU */
				$vkU = $client->fetchUserFromToken($accessToken);

				$existingUser =
					$this->entityManager->getRepository(User::class)->findOneBy(['vkId' => $vkU->getId()]);

				if ($existingUser)
				{
					if ($accessToken->getToken() !== $existingUser->getAccessToken())
					{
						$existingUser->setRefreshToken($accessToken->getRefreshToken());
						$existingUser->setAccessToken($accessToken->getToken());

						$this->entityManager->persist($existingUser);
						$this->entityManager->flush();
					}
					return $existingUser;
				}

				$user = new User();

				// 3) Maybe you just want to "register" them by creating
				// a User object
				$user->setVkId($vkU->getId());
				$user->setPassword(hash('sha1', $vkU->getId()));
				$user->setFirstname($vkU->getFirstname());
				$user->getLastname($vkU->getLastName());

				$user->setRefreshToken($accessToken->getRefreshToken());
				$user->setAccessToken($accessToken->getToken());

				$this->entityManager->persist($user);
				$this->entityManager->flush();

				return $user;
			},
			),
		);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		// change "app_homepage" to some route in your app
		$targetUrl = $this->router->generate('app_index');
		$existingUser =
			$this->entityManager->getRepository(User::class)->findOneBy(['vkId' => $token->getUserIdentifier()]);

		$this->getSession()->set('access_token', $existingUser->getAccessToken());
		$this->getSession()->set('user_id', $existingUser->getId());

		return new RedirectResponse($targetUrl);
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
	{
		$message = strtr($exception->getMessageKey(), $exception->getMessageData());

		return new Response($message, Response::HTTP_FORBIDDEN);
	}

	/**
	 * Called when authentication is needed, but it's not sent.
	 * This redirects to the 'login'.
	 */
	public function start(Request $request, AuthenticationException $authException = null): Response
	{
		return new RedirectResponse(
			'/connect/vk', // might be the site, where users choose their oauth provider
			Response::HTTP_TEMPORARY_REDIRECT,
		);
	}
}
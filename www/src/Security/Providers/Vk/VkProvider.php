<?php

namespace App\Security\Providers\Vk;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class VkProvider extends AbstractProvider
{
	use BearerAuthorizationTrait;

	protected string $version = '5.199';
	protected ?string $lang = null;
	protected array $fields = ['id'];

	public function getBaseAuthorizationUrl()
	{
		return 'https://oauth.vk.com/authorize';
	}

	public function __construct(array $options = [], array $collaborators = [])
	{
		parent::__construct($options, $collaborators);
		$this->redirectUri = str_replace(
			'http://',
			'https://',
			$this->redirectUri,
		);
	}

	public function getBaseAccessTokenUrl(array $params)
	{
		return 'https://oauth.vk.com/access_token';
	}

	public function getResourceOwnerDetailsUrl(\League\OAuth2\Client\Token\AccessToken $token)
	{
		$fields = 'first_name,last_name,email,photo_max_orig,online,last_seen,sex,bdate,city,universities';
		$params = [
			'fields' => $fields,
			'v' => $this->version,
			'access_token' => $token->getToken(),
		];

		return 'https://api.vk.com/method/account.getProfileInfo?'.http_build_query($params);
	}

	protected function getDefaultScopes()
	{
		return ['email', 'groups'];
	}

	protected function checkResponse(ResponseInterface $response, $data)
	{
		if (empty($data['error']))
		{
			return;
		}
		var_dump($data['error']);die;
		throw new IdentityProviderException($data['error'], 0, $data);
	}

	protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
	{
		return new VkUser($response);
	}
}
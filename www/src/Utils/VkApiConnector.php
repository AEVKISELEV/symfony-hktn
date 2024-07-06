<?php

namespace App\Utils;

use VK\Client\VKApiClient;

class VkApiConnector
{
	private VKApiClient $apiClient;

	private function __construct()
	{
		$this->apiClient = new \VK\Client\VKApiClient();
	}

}
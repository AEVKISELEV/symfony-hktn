<?php

namespace App\Controller;

use App\Utils\VkApiConnector;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GroupController extends BaseController
{
	#[Route(path: "/api/v1/groups", name: "app_groups_list", methods: "GET")]
	public function groupsList(VkApiConnector $vkApiConnector): Response
	{
		return $this->json($vkApiConnector->getGroups());
	}

	#[Route(path: "/api/v1/groups", name: "app_create_group", methods: ["POST"])]
	#[OA\RequestBody(
		description: "Create a new group",
		required: true,
		content: new OA\JsonContent(
			properties: [new OA\Property(property: "link", type: "string", description: "The link for the group")],
			type:       "object",
		)
	)]
	#[OA\Response(
		response: 200,
		description: "Returns the rewards of a user",
		content: new OA\JsonContent(
			properties: [
							new OA\Property(property: "status", type: "string", description: "Response status"),
							new  OA\Property(property: "data", type: "object", description: "Request data"),
						],
			type:       "object",
		)
	)]
	public function createGroup(Request $request): Response
	{
		$data = json_decode($request->getContent(), true);
		$link = $data['link'] ?? null;
		if ($link === null)
		{
			return $this->json(['success' => false, 'error' => 'has no link msf']);
		}

		return $this->json(
			[
				'status' => 'success',
				'data' => $data,
			],
		);
	}
}
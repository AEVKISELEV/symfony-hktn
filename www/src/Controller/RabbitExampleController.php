<?php

namespace App\Controller;

use App\Contract\OpenAIGeneralMessage;
use App\Service\RabbitMQProducerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RabbitExampleController extends AbstractController
{
    private RabbitMQProducerService $rabbitMQProducerService;

    public function __construct(RabbitMQProducerService $rabbitMQProducerService)
    {
        $this->rabbitMQProducerService = $rabbitMQProducerService;
    }

    #[Route('/api/v1/test/publish', 'publish_message', methods: ['POST'])]
    public function publishMessage(): Response
    {
        $message = (new OpenAIGeneralMessage())->setMessageText("");
        $this->rabbitMQProducerService->sendMessage($message, 'analysis');

        return new JsonResponse(['message' => 'Message published'], Response::HTTP_OK);
    }
}
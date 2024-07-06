<?php

namespace App\Controller;

use App\Contract\OpenAIMessage;
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
        $message = new OpenAIMessage();
        $this->rabbitMQProducerService->sendMessage($message, 'testik');

        return new JsonResponse(['message' => 'Message published'], Response::HTTP_OK);
    }
}
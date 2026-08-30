<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/health', name: 'health')]
final class HealthController extends AbstractController
{
    #[Route('/live', methods: ['GET'], name: '_live')]
    public function live(): JsonResponse
    {
        return $this->json(['status' => 'UP']);
    }

    #[Route('/ready', methods: ['GET'], name: '_ready')]
    public function ready(Connection $connection): JsonResponse
    {
        try {
            $connection->executeQuery('SELECT 1');

            return $this->json(['status' => 'UP']);
        } catch (\Throwable) {
            return $this->json(['status' => 'DOWN'], 503);
        }
    }
}

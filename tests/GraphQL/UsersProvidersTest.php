<?php

namespace App\Tests\GraphQL;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UsersProvidersTest extends WebTestCase
{
    public function testUsersFieldExistsOnRootQuery(): void
    {
        $client = static::createClient();
        $client->request('POST', '/', [
            'body' => json_encode(['query' => '{ users { uuid } }'], JSON_THROW_ON_ERROR)
        ]);

        $this->assertResponseIsSuccessful();

        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayNotHasKey('errors', $payload);
        $this->assertSame(['users' => []], $payload['data']);
    }
}

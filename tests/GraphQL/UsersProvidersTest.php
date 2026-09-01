<?php

namespace App\Tests\GraphQL;

use App\Tests\Security\OAuth2ClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UsersProvidersTest extends WebTestCase
{
    use OAuth2ClientTrait;

    public function testUsersFieldExistsOnRootQuery(): void
    {
        $client = static::createClient();
        $this->registerOAuth2Client('test-consumer', 'test-secret');
        $accessToken = $this->requestAccessToken($client, 'test-consumer', 'test-secret');

        $client->jsonRequest('POST', '/', ['query' => '{ users { totalCount items { uuid } } }'], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$accessToken,
        ]);

        $this->assertResponseIsSuccessful();

        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayNotHasKey('errors', $payload);
        $this->assertSame(['users' => ['totalCount' => 0, 'items' => []]], $payload['data']);
    }
}

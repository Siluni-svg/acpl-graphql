<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OAuth2ApiAccessTest extends WebTestCase
{
    use OAuth2ClientTrait;

    private const QUERY = '{ __typename }';

    public function testGraphqlRejectsRequestsWithoutAToken(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/', ['query' => self::QUERY]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGraphqlRejectsAnInvalidToken(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/', ['query' => self::QUERY], [
            'HTTP_AUTHORIZATION' => 'Bearer not-a-real-token',
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testClientCredentialsTokenGrantsAccessToTheGraphqlApi(): void
    {
        $client = static::createClient();
        $this->registerOAuth2Client('consumer-app', 'consumer-secret');
        $accessToken = $this->requestAccessToken($client, 'consumer-app', 'consumer-secret');

        $client->jsonRequest('POST', '/', ['query' => self::QUERY], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$accessToken,
        ]);

        $this->assertResponseIsSuccessful();

        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayNotHasKey('errors', $payload);
        $this->assertSame(['__typename' => 'Query'], $payload['data']);
    }
}

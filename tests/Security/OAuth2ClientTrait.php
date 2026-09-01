<?php

namespace App\Tests\Security;

use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method static ContainerInterface getContainer()
 */
trait OAuth2ClientTrait
{
    /**
     * @param list<string> $scopes
     */
    private function registerOAuth2Client(string $identifier, string $secret, array $scopes = ['api']): void
    {
        $manager = static::getContainer()->get(ClientManagerInterface::class);

        $existing = $manager->find($identifier);
        if (null !== $existing) {
            $manager->remove($existing);
        }

        $client = new Client($identifier, $identifier, $secret);
        $client->setGrants(new Grant('client_credentials'));
        $client->setScopes(...array_map(static fn (string $scope): Scope => new Scope($scope), $scopes));

        $manager->save($client);
    }

    private function requestAccessToken(KernelBrowser $client, string $identifier, string $secret): string
    {
        $client->request('POST', '/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $identifier,
            'client_secret' => $secret,
        ]);

        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return $payload['access_token'];
    }
}

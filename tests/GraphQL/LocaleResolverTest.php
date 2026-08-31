<?php

namespace App\Tests\GraphQL;

use App\Entity\User;
use App\GraphQL\LocaleResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class LocaleResolverTest extends TestCase
{
    public function testDefaultsToEnglishWithoutUserOrRequest(): void
    {
        $resolver = new LocaleResolver($this->security(null), new RequestStack());

        $this->assertSame('en', $resolver->resolve());
    }

    public function testFallsBackToAcceptLanguageHeader(): void
    {
        $resolver = new LocaleResolver($this->security(null), $this->requestStack('fr-FR,fr;q=0.9,en;q=0.8'));

        $this->assertSame('fr', $resolver->resolve());
    }

    public function testAcceptLanguageRespectsQuality(): void
    {
        $resolver = new LocaleResolver($this->security(null), $this->requestStack('en;q=0.5,fr;q=0.9'));

        $this->assertSame('fr', $resolver->resolve());
    }

    public function testUserLocaleTakesPriorityOverHeader(): void
    {
        $user = new User()->setLocale('fr');

        $resolver = new LocaleResolver($this->security($user), $this->requestStack('en'));

        $this->assertSame('fr', $resolver->resolve());
    }

    public function testUnsupportedHeaderLocaleFallsBackToDefault(): void
    {
        $resolver = new LocaleResolver($this->security(null), $this->requestStack('de-DE'));

        $this->assertSame('en', $resolver->resolve());
    }

    private function security(?User $user): Security
    {
        $security = $this->createStub(Security::class);
        $security->method('getUser')->willReturn($user);

        return $security;
    }

    private function requestStack(string $acceptLanguage): RequestStack
    {
        $request = new Request();
        $request->headers->set('Accept-Language', $acceptLanguage);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}

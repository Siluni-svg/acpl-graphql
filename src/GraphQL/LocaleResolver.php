<?php

namespace App\GraphQL;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolver
{
    public const DEFAULT_LOCALE = 'en';

    private const SUPPORTED_LOCALES = ['fr', 'en'];

    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
    ) {}

    /**
     * Résout la locale courante avec la priorité : utilisateur connecté > header Accept-Language > défaut.
     */
    public function resolve(): string
    {
        $user = $this->security->getUser();
        if ($user instanceof User && null !== $user->getLocale()) {
            $userLocale = $this->normalize($user->getLocale());
            if (null !== $userLocale) {
                return $userLocale;
            }
        }

        $acceptLanguage = $this->requestStack->getCurrentRequest()?->headers->get('Accept-Language');
        if (null !== $acceptLanguage) {
            $headerLocale = $this->parseAcceptLanguage($acceptLanguage);
            if (null !== $headerLocale) {
                return $headerLocale;
            }
        }

        return self::DEFAULT_LOCALE;
    }

    private function normalize(string $locale): ?string
    {
        $locale = strtolower(substr($locale, 0, 2));

        return in_array($locale, self::SUPPORTED_LOCALES, true) ? $locale : null;
    }

    private function parseAcceptLanguage(string $header): ?string
    {
        $entries = [];

        foreach (explode(',', $header) as $part) {
            $segments = explode(';', trim($part));
            $quality = 1.0;

            foreach (array_slice($segments, 1) as $param) {
                if (str_starts_with(trim($param), 'q=')) {
                    $quality = (float) substr(trim($param), 2);
                }
            }

            $entries[] = [trim($segments[0]), $quality];
        }

        usort($entries, static fn (array $a, array $b): int => $b[1] <=> $a[1]);

        foreach ($entries as [$lang]) {
            $locale = $this->normalize($lang);
            if (null !== $locale) {
                return $locale;
            }
        }

        return null;
    }
}

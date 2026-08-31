<?php

namespace App\DependencyInjection\Compiler;

use Overblog\GraphQLBundle\Request\Executor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * overblog/graphql-bundle registers its schema builders once, at Executor
 * construction time, then tags the service `kernel.reset` so Symfony clears
 * it between requests. That combination only works when the container is
 * rebuilt per request (classic PHP-FPM). Under FrankenPHP worker mode the
 * container — and the Executor singleton — survive across requests, so the
 * reset empties `$schemas` without anything ever repopulating it: every
 * request after the first one fails with "At least one schema should be
 * declared." Dropping the tag keeps the schema builders registered for the
 * life of the worker, where they belong.
 */
class GraphQLExecutorPersistencePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(Executor::class)) {
            return;
        }

        $container->getDefinition(Executor::class)->clearTag('kernel.reset');
    }
}

<?php

declare(strict_types=1);

// use App\Domain\User\UserRepository;
// use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // The following remains from the original skeleton app

    // // Here we map our UserRepository interface to its in memory implementation
    // $containerBuilder->addDefinitions([
    //     UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
    // ]);
};

<?php
declare(strict_types=1);

use App\Domain\Login\LoginRepository;
use App\Domain\User\UserRepository;
use App\Domain\Usuario\UsuarioRepository;
use App\Domain\Sistema\SistemaRepository;
use App\Domain\Plantilla\PlantillaRepository;
use App\Domain\UsuarioRol\UsuarioRolRepository;
use App\Domain\Componente\ComponenteRepository;
use App\Domain\Archivo\ArchivoRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Usuario\InMemoryUsuarioRepository;
use App\Infrastructure\Persistence\Login\InMemoryLoginRepository;
use App\Infrastructure\Persistence\Sistema\InMemorySistemaRepository;
use App\Infrastructure\Persistence\Plantilla\InMemoryPlantillaRepository;
use App\Infrastructure\Persistence\UsuarioRol\InMemoryUsuarioRolRepository;
use App\Infrastructure\Persistence\Componente\InMemoryComponenteRepository;
use App\Infrastructure\Persistence\Archivo\InMemoryArchivoRepository;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        UsuarioRepository::class => \DI\autowire(InMemoryUsuarioRepository::class),
        LoginRepository::class => \DI\autowire(InMemoryLoginRepository::class),
        SistemaRepository::class => \DI\autowire(InMemorySistemaRepository::class),
        PlantillaRepository::class => \DI\autowire(InMemoryPlantillaRepository::class),
        UsuarioRolRepository::class => \DI\autowire(InMemoryUsuarioRolRepository::class),
        ComponenteRepository::class => \DI\autowire(InMemoryComponenteRepository::class),
        ArchivoRepository::class => \DI\autowire(InMemoryArchivoRepository::class),
    ]);


};

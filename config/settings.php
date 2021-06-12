<?php

declare(strict_types=1);

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder): void
{
    $containerBuilder->addDefinitions([
        'language'      => getenv('LANGUAGE'),
        'database.path' => getenv('DATABASE_PATH')
    ]);
};
<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
(require __DIR__ . '/../config/settings.php')($containerBuilder);

// Set up container
(require __DIR__ . '/../config/container.php')($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

// Register routes
(require __DIR__ . '/../config/routes.php')($app);

$app->run();
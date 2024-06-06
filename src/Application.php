<?php

namespace Vendic\Monitoring\OhDear;

require_once __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Vendic\Monitoring\OhDear\Config\Config;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Application
{
    private Container $container;

    public function __construct()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(paths: __DIR__ . "/..");
        if (file_exists(__DIR__ . "/../.env")) {
            $dotenv->load();
        }
        # If it doesn't exist, we're in production and using the system environment variables

        // Set up error handling
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();

        // Build the PHP-DI Container
        $containerBuilder = new ContainerBuilder();

        $coreCount = (
            isset($_ENV['CORE_COUNT']) && is_numeric($_ENV['CORE_COUNT']) ?
                $_ENV['CORE_COUNT'] :
                intval(shell_exec('nproc'))
        ) ?: 48;

        $containerBuilder->addDefinitions([
            Config::class => static fn(): \Vendic\Monitoring\OhDear\Config\Config => new Config(
                ohdearSecret: $_ENV['OH_DEAR_HEALTH_CHECK_SECRET'],
                cores: $coreCount
            ),
        ]);

        $this->container = $containerBuilder->build();
    }

    public function getContainer(): Container
    {
        return $this->container;
    }
}

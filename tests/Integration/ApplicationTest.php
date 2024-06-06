<?php

namespace Vendic\Monitoring\OhDear\Tests\Integration;

use DI\Container;
use Vendic\Monitoring\OhDear\Application;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->restoreErrorHandler();
        $this->restoreExceptionHandler();
    }

    protected function restoreErrorHandler(): void
    {
        $previousHandler = set_error_handler(static fn() => null);
        restore_error_handler();
        if ($previousHandler !== null && !($previousHandler instanceof \PHPUnit\Runner\ErrorHandler)) {
            restore_error_handler();
        }
    }

    protected function restoreExceptionHandler(): void
    {
        $previousHandler = set_exception_handler(static fn() => null);
        restore_exception_handler();
        if ($previousHandler !== null) {
            restore_exception_handler();
        }
    }

    public function testApplication(): void
    {
        $app = new Application();
        $container = $app->getContainer();
        $this->assertInstanceOf(Application::class, $app);
        $this->assertInstanceOf(Container::class, $container);
    }

}
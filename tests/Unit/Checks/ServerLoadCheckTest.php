<?php

namespace Vendic\Monitoring\OhDear\Tests\Unit\Checks;

use phpmock\prophecy\PHPProphet;
use PHPUnit;
use Prophecy\Prophet;
use Vendic\Monitoring\OhDear\Check;
use Vendic\Monitoring\OhDear\Checks\ServerLoadCheck;
use Vendic\Monitoring\OhDear\Config\Config;
use Vendic\Monitoring\OhDear\Constants\Status;

class ServerLoadCheckTest extends PHPUnit\Framework\TestCase
{
    private Prophet $prophet;
    private PHPProphet $phpprophet;
    private $checksNamespace;
    private $config;

    public static array | false $loadAvg;

    public function setUp(): void
    {
        $this->phpprophet = new PHPProphet();
        $this->prophet = new Prophet();

        // Set the sys_getloadavg function to the one in this namespace
        $this->checksNamespace = $this->phpprophet->prophesize('Vendic\Monitoring\OhDear\Checks');

        $this->config = $this->prophet->prophesize(Config::class);
    }

    public function tearDown(): void
    {
        $this->phpprophet->checkPredictions();
        $this->prophet->checkPredictions();
    }

    private function executeServerLoadCheck(): array
    {
        $serverLoadCheck = new ServerLoadCheck($this->config->reveal());

        // Act
        $result = $serverLoadCheck->execute();

        // Return the result as an array
        return $result->toArray();
    }

    public function testReturnsTrueWhenInsideRange(): void
    {
        // Arrange
        self::$loadAvg = [0.1, 0.1, 0.1];
        $this->checksNamespace->sys_getloadavg()->willReturn(self::$loadAvg);
        $this->checksNamespace->reveal();

        $this->config->getCores()->willReturn(1);

        $result = $this->executeServerLoadCheck();

        // Assert
        $this->assertEquals(Status::STATUS_OK, $result['status']);
    }

    public function testReturnsTrueWhenOutsideRange(): void
    {
        // Arrange
        self::$loadAvg = [2, 2, 2];
        $this->checksNamespace->sys_getloadavg()->willReturn(self::$loadAvg);
        $this->checksNamespace->reveal();

        $this->config->getCores()->willReturn(1);

        $result = $this->executeServerLoadCheck();

        // Assert
        $this->assertEquals(Status::STATUS_WARNING, $result['status']);
    }

    public function testReturnsFaultWhenSysGetLoadAvgFails(): void
    {
        // Arrange
        self::$loadAvg = false;
        $this->checksNamespace->sys_getloadavg()->willReturn(self::$loadAvg);
        $this->checksNamespace->reveal();

        $this->config->getCores()->willReturn(1);

        $result = $this->executeServerLoadCheck();

        // Assert
        $this->assertEquals(Status::STATUS_FAILED, $result['status']);
    }
}
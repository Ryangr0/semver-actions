<?php

namespace Vendic\Monitoring\OhDear\Checks;

use Vendic\Monitoring\OhDear\Config\Config;
use Vendic\Monitoring\OhDear\Constants\Status;
use Vendic\Monitoring\OhDear\Dtos\CheckResults;

class ServerLoadCheck
{
    private const CORES_USAGE_WARNING_THRESHOLD = 0.85;


    public function __construct(private Config $config)
    {
    }

    public function execute(): CheckResults
    {
        $loadAverage = sys_getloadavg();
        if (!$loadAverage) {
            return new CheckResults(
                "ServerLoad",
                "Server Load",
                Status::STATUS_FAILED,
                "Failed to retrieve server load.",
                "Failed to retrieve server load.",
                []
            );
        }

        list($oneMinuteLoad, $fiveMinuteLoad, $fifteenMinuteLoad) = $loadAverage;

        $status = Status::STATUS_OK;
        $notificationMessage = 'Server load is within normal parameters.';

        if ($fiveMinuteLoad > $this->config->getCores() * self::CORES_USAGE_WARNING_THRESHOLD) {
            $status = Status::STATUS_WARNING;
            $notificationMessage = "Sustained over-utilization detected.";
        }

        return new CheckResults(
            "ServerLoad",
            "Server Load",
            $status,
            $notificationMessage,
            sprintf(
                'Load: 1m-%s, 5m-%s, 15m-%s',
                $oneMinuteLoad,
                $fiveMinuteLoad,
                $fifteenMinuteLoad
            ),
            [
                'oneMinuteLoad' => $oneMinuteLoad,
                'fiveMinuteLoad' => $fiveMinuteLoad,
                'fifteenMinuteLoad' => $fifteenMinuteLoad,
            ]
        );
    }
}

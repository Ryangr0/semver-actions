<?php

namespace Vendic\Monitoring\OhDear\Checks;

use Vendic\Monitoring\OhDear\Constants\Status;
use Vendic\Monitoring\OhDear\Dtos\CheckResults;

class DiskSpaceCheck implements CheckInterface
{
    private const WARNING_THRESHOLD = 85;

    private const FAILED_THRESHOLD = 90;

    public function execute(): CheckResults
    {
        $diskUsagePercentage = $this->getDiskUsagePercentage();

        $status = Status::STATUS_OK;

        if ($diskUsagePercentage > self::WARNING_THRESHOLD) {
            $status = Status::STATUS_WARNING;
        }

        if ($diskUsagePercentage > self::FAILED_THRESHOLD) {
            $status = Status::STATUS_FAILED;
        }

        return new CheckResults(
            "UsedDiskSpace",
            "Used Disk Space",
            $status,
            sprintf('Disk usage is at %s%%', $diskUsagePercentage),
            $diskUsagePercentage . '% used',
            ["disk_space_used_percentage" => $diskUsagePercentage]
        );
    }

    private function getDiskUsagePercentage(): float|int
    {
        $totalSpace = disk_total_space("/");
        $freeSpace = disk_free_space("/");
        $usedSpace = $totalSpace - $freeSpace;
        return ($usedSpace / $totalSpace) * 100;
    }
}

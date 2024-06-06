<?php

namespace Vendic\Monitoring\OhDear\Checks;

use Vendic\Monitoring\OhDear\Dtos\CheckResults;

interface CheckInterface
{
    public function execute(): CheckResults;
}

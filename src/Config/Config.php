<?php

namespace Vendic\Monitoring\OhDear\Config;

class Config
{
    public function __construct(
        public string $ohdearSecret,
        public int $cores = 48
    ) {
    }

    public function getOhDearSecret(): string
    {
        return $this->ohdearSecret;
    }

    public function getCores(): int
    {
        return $this->cores;
    }
}

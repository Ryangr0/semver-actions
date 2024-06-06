<?php

namespace Vendic\Monitoring\OhDear\Dtos;

class CheckResults
{
    /**
     * @param array<string, mixed> $meta
     */
    public function __construct(
        private string $name,
        private string $label,
        private string $status,
        private string $notificationMessage,
        private string $shortSummary,
        private array $meta
    ) {
    }

    /**
     * @return array<string, string|array>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'status' => $this->status,
            'notificationMessage' => $this->notificationMessage,
            'shortSummary' => $this->shortSummary,
            'meta' => $this->meta,
        ];
    }
}

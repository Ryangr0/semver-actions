<?php

namespace Vendic\Monitoring\OhDear;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vendic\Monitoring\OhDear\Checks\CheckInterface;
use Vendic\Monitoring\OhDear\Checks\DiskSpaceCheck;
use Vendic\Monitoring\OhDear\Checks\ServerLoadCheck;
use Vendic\Monitoring\OhDear\Config\Config;

class RequestHandler
{
    public function __construct(
        private readonly Application $application,
        private Config $config
    ) {
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->hasHeader('Oh-Dear-Health-Check-Secret')) {
            $passedSecret = $request->getHeader('Oh-Dear-Health-Check-Secret')[0];
        }


        if (!isset($passedSecret) || $this->config->getOhDearSecret() !== $passedSecret) {
            return new Response(
                403,
                [],
                json_encode(
                    ['error' => 'Invalid health check secret'],
                    JSON_THROW_ON_ERROR
                )
            );
        }

        $checks = [
            DiskSpaceCheck::class,
            ServerLoadCheck::class
        ];

        $checkResults = [];
        foreach ($checks as $check) {
            /** @var CheckInterface $checkInstance */
            $checkInstance = $this->application->getContainer()->get($check);
            $checkResults[] = $checkInstance->execute()->toArray();
        }

        return new Response(
            200,
            [],
            json_encode(
                [
                    "finishedAt" => time(),
                    "checkResults" => $checkResults
                ],
                JSON_THROW_ON_ERROR
            )
        );
    }
}

<?php

require_once __DIR__ . '/../src/Application.php';

use GuzzleHttp\Psr7\ServerRequest;
use Vendic\Monitoring\OhDear\Application;
use Vendic\Monitoring\OhDear\RequestHandler;

$app = new Application();
$container = $app->getContainer();


// Get the RequestHandler from the container
/** @var RequestHandler $requestHandler */
$requestHandler = $container->get(RequestHandler::class);

// Create a server request and handle it
$request = ServerRequest::fromGlobals();

try {
    $response = $requestHandler->handle($request);
} catch (Throwable $throwable) {
    echo "Something went wrong.";
    throw $throwable;
//    echo $e->getMessage();
}


// Output the response
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

header('Content-Type: application/json');

http_response_code($response->getStatusCode());
echo $response->getBody();

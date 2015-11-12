<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../../bootstrap.php';

require_once __DIR__.'/../app/AppKernel.php';

$environment = 'dev';
if (isset($_ENV['SYMFONY_ENVIRONMENT'], $_ENV['SYMFONY__ENABLE_ADDITIONALS'])) {
    $environment = $_ENV['SYMFONY_ENVIRONMENT'];
    $_SERVER['SYMFONY__ENABLE_ADDITIONALS'] = $_ENV['SYMFONY__ENABLE_ADDITIONALS'];
}

$kernel = new AppKernel($environment, true);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../../bootstrap.php';

require_once __DIR__.'/../app/AppKernel.php';

$environment = 'dev';
if (isset($_SERVER['SYMFONY_ENVIRONMENT'])) {
    $environment = $_SERVER['SYMFONY_ENVIRONMENT'];
}

$kernel = new AppKernel($environment, true);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

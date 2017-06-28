<?php

use Stars\Core\Service\RequestService;
use Stars\Core\Service\ViewModelService;
use Stars\Core\Service\BootstrapService;

require_once __DIR__.'/../vendor/autoload.php';

$rootPath = __DIR__.'/..';

$request = RequestService::initialize();
$viewModel = ViewModelService::initialize($rootPath);
$bootstrap = new BootstrapService($request,$viewModel);
$response = $bootstrap->handle('Stars');

echo $response;
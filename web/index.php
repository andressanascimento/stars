<?php

use Symfony\Component\Yaml\Yaml;
use Stars\Core\Service\RequestService;
use Stars\Core\Service\ViewModelService;
use Stars\Core\Service\DatabaseService;
use Stars\Core\Service\BootstrapService;


require_once __DIR__.'/../vendor/autoload.php';

$rootPath = __DIR__.'/..';
$config = Yaml::parse(file_get_contents('../app/config/database.yml'));
$config_routes = Yaml::parse(file_get_contents('../app/config/routes.yml'));
$request = RequestService::initialize($config_routes);
$viewModel = ViewModelService::initialize($rootPath);
$database = DatabaseService::initialize($config);
$bootstrap = new BootstrapService($request, $viewModel, $database);
$response = $bootstrap->handle('Stars');

echo $response;
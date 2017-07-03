<?php

namespace Stars\Core\Service;

use Stars\Core\Service\RequestService;
use Stars\Core\Service\ViewModelService;
use Stars\Core\Service\DatabaseService;

class BootstrapService
{

    private $projectNamespace;

    /**
     * @param RequestService $request
     * @param ViewModelService $viewModel
     * 
     */
    public function __construct(RequestService $request, ViewModelService $viewModel, DatabaseService $db)
    {
        $this->request = $request;
        $this->viewModel = $viewModel;
        $this->db = $db;
    }

    /**
     * Match request to controller and returns a response
     * @param string $project_name
     * @return string $response
     */
    public function handle($project_namespace) 
    {
        $this->projectNamespace = $project_namespace;
        $route = $this->request->getRoute($this->request->server['REQUEST_URI']);
        if (!$route) {
            return "404 página não encontrada";
        }
        
        $class_name = $this->getControllerName($route);

        $action = $route['action'].'Action';
        try {
            $controller = new $class_name($this->request, $this->viewModel->getViewModel(), $this->db);
            $response = $controller->{$action}();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response;
    }

    /**
     * Create a controller name and check if class exists
     * @param array $route List of definition routes routes.yml
     * @return string Controller class name
     */
    private function getControllerName(array $route)
    {
        $module = $route['module'];
        $controller = $this->dashesToCamelCase($route['controller']);
        $action = $this->dashesToCamelCase($route['action']).'Action';

        $class_name = $this->projectNamespace."\\".$module."\\Controller\\".$controller.'Controller';
        if (!class_exists($class_name)) {
            throw new \Exception("Controller ".$class_name." not found");
        }
        return $class_name;
    }

    /**
     * Convert string with undercore to camelCase
     * @param string $string
     * @return string
     */
    private function dashesToCamelCase($string) 
    {

        $str = lcfirst(str_replace('-', '', ucwords($string, '-')));

        return $str;
    }
}
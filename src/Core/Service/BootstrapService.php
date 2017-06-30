<?php

namespace Stars\Core\Service;

use Stars\Core\Service\RequestService;
use Stars\Core\Service\ViewModelService;
use Stars\Core\Service\DatabaseService;

class BootstrapService
{
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
        $uri = urldecode($this->request->server['REQUEST_URI']);
        $url = explode ("/", $uri);

        $module = $url[1];
        $controller = $this->dashesToCamelCase($url[2]);
        $action = $this->dashesToCamelCase($url[3]).'Action';

        $class_name = $project_namespace."\\".$module."\\Controller\\".$controller.'Controller';
        try {
            $controller = new $class_name($this->request, $this->viewModel->getViewModel(), $this->db);
            $response = $controller->{$action}();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response;
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
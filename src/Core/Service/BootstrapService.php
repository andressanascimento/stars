<?php

namespace Stars\Core\Service;

use Stars\Core\Service\RequestService;
use Stars\Core\Service\ViewModelService;

class BootstrapService
{
    /**
     * @param RequestService $request
     * @param ViewModelService $viewModel
     * 
     */
    public function __construct(RequestService $request, ViewModelService $viewModel)
    {
        $this->request = $request;
        $this->viewModel = $viewModel;
    }

    /**
     * Match request to controller and returns a response
     * @param string $project_name
     * @return string $response
     */
    public function handle($project_namespace) 
    {
        $get = $this->request->get();
        $module = $get['module'];
        $controller = $this->dashesToCamelCase($get['controller']);
        $action = $this->dashesToCamelCase($get['action']).'Action';

        $class_name = $project_namespace."\\".$module."\\Controller\\".$controller.'Controller';
        try {
            $controller = new $class_name($this->request, $this->viewModel->getViewModel());
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
<?php

namespace Stars\Core\Service;

class ViewModelService
{
    /**
     * @var \Twig_Environment
     */
    private $viewModel;

    protected function __construct(\Twig_Environment $viewModel)
    {
        $this->viewModel = $viewModel;
    }

    public static function initialize($rootPath)
    {
        $directories = array(
            $rootPath.DIRECTORY_SEPARATOR.'web', 
            $rootPath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'layout'
        );
        //rid of the dots that scandir() picks up in Linux environments
        $modules = array_diff(scandir($rootPath.DIRECTORY_SEPARATOR.'src'), array('..', '.'));
        foreach ($modules as $module) {
            $directories[] = $rootPath.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'views';
        }

        $loader = new \Twig_Loader_Filesystem($directories);
        return new static(new \Twig_Environment($loader));
        
    }

	public function getViewModel()
    {
        return $this->viewModel;
    }
}
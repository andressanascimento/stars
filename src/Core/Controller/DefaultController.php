<?php

namespace Stars\Core\Controller;

use Stars\Core\Service\RequestService;

class DefaultController 
{
    /**
     *  @var RequestService 
     */
    protected $request;
    /**
     *  @var \Twig_Environment 
     */
    protected $view;


    /**
     * @param RequestService $request
     * @param \Twig_Environment $view
     */
    public function __construct(RequestService $request, \Twig_Environment $view) 
    {
        $this->request = $request;
        $this->view = $view;
    }
}
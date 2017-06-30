<?php

namespace Stars\Core\Controller;

use Stars\Core\Service\RequestService;
use Stars\Core\Service\DatabaseService;

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
     *  @var DatabaseService
     */
    protected $db;


    /**
     * @param RequestService $request
     * @param \Twig_Environment $view
     * @param DatabaseService $request
     */
    public function __construct(RequestService $request, \Twig_Environment $view, DatabaseService $db) 
    {
        $this->request = $request;
        $this->view = $view;
        $this->db = $db;
    }
}
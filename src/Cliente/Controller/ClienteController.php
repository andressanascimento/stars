<?php

namespace Stars\Cliente\Controller;

use Stars\Core\Controller\DefaultController;

class ClienteController extends DefaultController
{
    public function listAction()
    {
        return $this->view->render('cliente/list.html.twig', array('hello' => 'hello world'));
    }
}
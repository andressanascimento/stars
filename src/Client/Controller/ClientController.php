<?php

namespace Stars\Client\Controller;

use Stars\Core\Controller\DefaultController;

class ClientController extends DefaultController
{
    public function listAction()
    {
        $repo = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        //$list = $repo->insert(array('name'=>'Teste','age'=>28));
        //$list = $repo->update(array('name'=>'Cherry','age'=>28),array('id'=> 2));
        //$list = $repo->delete(array('id'=> 2));
        //$list = $repo->findBy(array('id' => 1));
        //$list = $repo->fetchAll();

        return $this->view->render('client/list.html.twig', array('hello' => 'hello world'));
    }
}
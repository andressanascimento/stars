<?php

namespace Stars\Store\Controller;

use Stars\Core\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $repo = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $list = $repo->storeReport();
        var_dump($list[2]['data']);
        return $this->view->render('index/index.html.twig', array('list' => $list));
    }
}
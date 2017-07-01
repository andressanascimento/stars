<?php

namespace Stars\Client\Controller;

use Stars\Core\Controller\Controller;
use Stars\Client\FormValidator\ClientFormValidator;

class ClientController extends Controller
{
    public function indexAction()
    {
        $repo = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        $list = $repo->fetchAll();

        return $this->view->render('client/index.html.twig', array('clients' => $list));
    }

    public function searchAction()
    {
        $list = array();
        if ($this->request->isPost()) {
            $name = $this->request->param('name','post');
            $repository = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
            $list = $repository->searchByName($name);
        }

        return $this->view->render('client/search.html.twig', array('clients' => $list));
    }

    public function formAction()
    {
        $id = $this->request->param('id','get');
        $repository = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');

        $message =  null;
        $input_errors = null;
        $model = null;

        if (!is_null($id)) {
            $model = $repository->findBy(array('id' => $id));
        }

        if ($this->request->isPost()) {
            $id = $this->request->param('id','post');
            if(!is_null($id)) {
                $model = $repository->findBy(array('id' => $id));
            }

            $post = $this->request->post();
            $validator = new ClientFormValidator();
            if ($validator->isValid($post)) {
                try {
                    if (is_null($id)) {
                        $repository->insert($post);
                        $message = array('status' => 'alert-success', 'message' => 'Cliente inserido com sucesso');
                    } else {
                        $repository->update($post, array('id' => $id));
                        $message = array('status' => 'alert-success', 'message' => 'Cliente atualizado com sucesso');
                    }
                    
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi possível inserir');
                }
            } else {
                $input_errors = $validator->getMessages();
            }
        }

        return $this->view->render('client/form.html.twig', array('message' => $message, 
                                                                'input_errors' => $input_errors, 
                                                                'model' => $model)
        );
    }
}
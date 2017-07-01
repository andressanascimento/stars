<?php

namespace Stars\Client\Controller;

use Stars\Core\Controller\Controller;
use Stars\Client\Model\ClientModel;
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

        return $this->view->render('client/list.html.twig', array('clients' => $list));
    }

    public function listAction()
    {
        $repository = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        $list = $repository->fetchAll();
        return $this->view->render('client/list.html.twig', array('clients' => $list));
    }

    public function newAction()
    {
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');

        if ($this->request->isPost()) {
            $post = $this->request->post();
            $validator = new ClientFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->insert($post);
                    $message = array('status' => 'alert-success', 'message' => 'Cliente inserido com sucesso');
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi possível inserir');
                }
            } else {
                //form invalid
                $model = (object) $post;
                $input_errors = $validator->getMessages();
            }
        }

        return $this->view->render('client/form.html.twig', 
            array('message' => $message,'input_errors' => $input_errors, 'model' => $model, 'action' => '/client/client/new')
        );
    }

    public function updateAction()
    {
        $id = $this->request->param('id','get');
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        //load form
        if ($this->request->isPost()) {
            $id = $this->request->param('id','post');

            $post = $this->request->post();
            $validator = new ClientFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->update($post, array('id' => $id));
                    $message = array('status' => 'alert-success', 'message' => 'Cliente atualizado com sucesso');
                    $model = (object) $post;
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi atualizar inserir');
                }
            } else {
                //form invalid
                $model = (object) $post;
                $input_errors = $validator->getMessages();
            }
        }

        if (is_null($model)) {
            $model = $repository->findByOne(array('id' => $id));
        }
        
        return $this->view->render('client/form.html.twig', 
            array('message' => $message, 
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/client/client/update'
            )
        );
    }

    public function deleteAction()
    {
        $message = null;

        $repository = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        if ($this->request->isPost()) {
            $ids = $this->request->param('ids','post');
            try {
                $repository->deleteList($ids);
                $message = array('status' => 'alert-success', 'message' => 'Registros apagados');
            } catch (\Exception $e) {
                $message = array('status' => 'alert-danger', 'message' => 'Não foi possível deletar os registros');
            }
        }

        $list = $repository->fetchAll();

        return $this->view->render('client/list.html.twig', array('clients' => $list, 'message' => $message));
    }
}
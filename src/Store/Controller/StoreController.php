<?php

namespace Stars\Store\Controller;

use Stars\Core\Controller\Controller;
use Stars\Store\Model\StoreModel;
use Stars\Store\FormValidator\StoreFormValidator;

class StoreController extends Controller
{
    public function indexAction()
    {
        $repo = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $list = $repo->fetchAll();

        return $this->view->render('store/index.html.twig', array('stores' => $list));
    }

    public function searchAction()
    {
        $list = array();
        if ($this->request->isPost()) {
            $name = $this->request->param('name','post');
            $repository = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
            $list = $repository->searchByName($name);
        }

        return $this->view->render('store/list.html.twig', array('stores' => $list));
    }

    public function listAction()
    {
        $repository = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $list = $repository->fetchAll();
        return $this->view->render('store/list.html.twig', array('stores' => $list));
    }

    public function newAction()
    {
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $repository_state = $this->db->getRepository('Stars\\Store\\Model\\StateModel');
        $states = $repository_state->fetchAll();

        if ($this->request->isPost()) {
            $post = $this->request->post();
            $validator = new StoreFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->insert($post);
                    $message = array('status' => 'alert-success', 'message' => 'Loja foi inserida com sucesso');
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi possível inserir');
                }
            } else {
                //form invalid
                $model = (object) $post;
                $input_errors = $validator->getMessages();
            }
        }

        return $this->view->render('store/form.html.twig', 
            array('message' => $message,
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/store/store/new',
                'states' => $states
            )
        );
    }

    public function updateAction()
    {
        $id = $this->request->param('id','get');
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $repository_state = $this->db->getRepository('Stars\\Store\\Model\\StateModel');
        $states = $repository_state->fetchAll();
        //load form
        if ($this->request->isPost()) {
            $id = $this->request->param('id','post');

            $post = $this->request->post();
            $validator = new StoreFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->update($post, array('id' => $id));
                    $message = array('status' => 'alert-success', 'message' => 'Loja atualizado com sucesso');
                    $model = new StoreModel($post);
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi atualizar');
                }
            } else {
                //form invalid
                $model = new StoreModel($post);
                $input_errors = $validator->getMessages();
            }
        }

        if (is_null($model)) {
            $model = $repository->findByOne(array('id' => $id));
        }

        return $this->view->render('store/form.html.twig', 
            array('message' => $message, 
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/store/store/update',
                'states' => $states
            )
        );
    }

    public function deleteAction()
    {
        $message = null;

        $repository = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        if ($this->request->isPost()) {
            $ids = $this->request->param('ids','post');
            try {
                $result = $repository->deleteList($ids);
                if (empty($result)) {
                    $message = array('status' => 'alert-success', 'message' => 'Registros apagados');
                } else {
                    $message = array('status' => 'alert-info', 'message' => $result);
                }
            } catch (\Exception $e) {
                $message = array('status' => 'alert-danger', 'message' => 'Não foi possível deletar os registros');
            }
        }

        $list = $repository->fetchAll();

        return $this->view->render('store/list.html.twig', array('stores' => $list, 'message' => $message));
    }
}
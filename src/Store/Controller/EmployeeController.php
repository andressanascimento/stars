<?php

namespace Stars\Store\Controller;

use Stars\Core\Controller\Controller;
use Stars\Store\Model\EmployeeModel;
use Stars\Store\FormValidator\EmployeeFormValidator;

class EmployeeController extends Controller
{
    public function indexAction()
    {
        $repo = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
        $list = $repo->fetchAll();

        return $this->view->render('employee/index.html.twig', array('employees' => $list));
    }

    public function searchAction()
    {
        $list = array();
        if ($this->request->isPost()) {
            $name = $this->request->param('name','post');
            $repository = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
            $list = $repository->searchByName($name);
        }

        return $this->view->render('employee/list.html.twig', array('employees' => $list));
    }

    public function listAction()
    {
        $repository = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
        $list = $repository->fetchAll();
        return $this->view->render('employee/list.html.twig', array('employees' => $list));
    }

    public function newAction()
    {
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
        $repository_store = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $stores = $repository_store->fetchAll();

        if ($this->request->isPost()) {
            $post = $this->request->post();
            $validator = new EmployeeFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->insert($post);
                    $message = array('status' => 'alert-success', 'message' => 'Funcionário foi inserida com sucesso');
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi possível inserir');
                }
            } else {
                //form invalid
                $model = (object) $post;
                $input_errors = $validator->getMessages();
            }
        }

        return $this->view->render('employee/form.html.twig', 
            array('message' => $message,
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/store/employee/new',
                'stores' => $stores
            )
        );
    }

    public function updateAction()
    {
        $id = $this->request->param('id','get');
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
        $repository_store = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $stores = $repository_store->fetchAll();
        //load form
        if ($this->request->isPost()) {
            $id = $this->request->param('id','post');

            $post = $this->request->post();
            $validator = new EmployeeFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->update($post, array('id' => $id));
                    $message = array('status' => 'alert-success', 'message' => 'Funcionário atualizado com sucesso');
                    $model = new EmployeeModel($post);
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi atualizar');
                }
            } else {
                //form invalid
                $model = new EmployeeModel($post);
                $input_errors = $validator->getMessages();
            }
        }

        if (is_null($model)) {
            $model = $repository->findByOne(array('id' => $id));
        }

        return $this->view->render('employee/form.html.twig', 
            array('message' => $message, 
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/store/employee/update',
                'stores' => $stores
            )
        );
    }

    public function deleteAction()
    {
        $message = null;

        $repository = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
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

        return $this->view->render('employee/list.html.twig', array('employees' => $list, 'message' => $message));
    }
}
<?php

namespace Stars\Client\Controller;

use Stars\Core\Controller\Controller;
use Stars\Client\Model\TransactionModel;
use Stars\Client\FormValidator\TransactionFormValidator;


class TransactionController extends Controller
{
    public function indexAction()
    {
        $repo = $this->db->getRepository('Stars\\Client\\Model\\TransactionModel');
        $list = $repo->fetchAll();
        return $this->view->render('transaction/index.html.twig', array('transactions' => $list));
    }

    public function searchAction()
    {
        $list = array();
        if ($this->request->isPost()) {
            $name = $this->request->param('name','post');
            $repository = $this->db->getRepository('Stars\\Client\\Model\\TransactionModel');
            $list = $repository->searchByName($name);
        }

        return $this->view->render('transaction/list.html.twig', array('transactions' => $list));
    }

    public function listAction()
    {
        $repository = $this->db->getRepository('Stars\\Client\\Model\\TransactionModel');
        $list = $repository->fetchAll();
        return $this->view->render('transaction/list.html.twig', array('transactions' => $list));
    }

    public function newAction()
    {
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Client\\Model\\TransactionModel');
        
        $repository_store = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $stores = $repository_store->fetchAll();

        $repository_employee = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
        $employees = $repository_employee->fetchAll();

        $repository_client = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        $clients = $repository_client->fetchAll();

        if ($this->request->isPost()) {
            $post = $this->request->post();
            $validator = new TransactionFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->insert($post);
                    $message = array('status' => 'alert-success', 'message' => 'Compra foi inserida com sucesso');
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi possível inserir');
                }
            } else {
                //form invalid
                $model = (object) $post;
                $input_errors = $validator->getMessages();
            }
        }

        return $this->view->render('transaction/form.html.twig', 
            array('message' => $message,
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/client/transaction/new',
                'stores' => $stores,
                'clients' => $clients,
                'employees' => $employees
            )
        );
    }

    public function updateAction()
    {
        $id = $this->request->param('id','get');
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Client\\Model\\TransactionModel');

        $repository_store = $this->db->getRepository('Stars\\Store\\Model\\StoreModel');
        $stores = $repository_store->fetchAll();

        $repository_client = $this->db->getRepository('Stars\\Client\\Model\\ClientModel');
        $clients = $repository_client->fetchAll();

        $repository_employee = $this->db->getRepository('Stars\\Store\\Model\\EmployeeModel');
        $employees = $repository_employee->fetchAll();
        //load form
        if ($this->request->isPost()) {
            $id = $this->request->param('id','post');

            $post = $this->request->post();
            $validator = new TransactionFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->update($post, array('id' => $id));
                    $message = array('status' => 'alert-success', 'message' => 'Compra atualizada com sucesso');
                    $model = new TransactionModel($post);
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi atualizar');
                }
            } else {
                //form invalid
                $model = new TransactionModel($post);
                $input_errors = $validator->getMessages();
            }
        }

        if (is_null($model)) {
            $model = $repository->findByOne(array('id' => $id));
        }

        return $this->view->render('transaction/form.html.twig', 
            array('message' => $message, 
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/client/transaction/update',
                'stores' => $stores,
                'clients' => $clients,
                'employees' => $employees
            )
        );
    }

    public function deleteAction()
    {
        $message = null;

        $repository = $this->db->getRepository('Stars\\Client\\Model\\TransactionModel');
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

        return $this->view->render('transaction/list.html.twig', array('transactions' => $list, 'message' => $message));
    }
}
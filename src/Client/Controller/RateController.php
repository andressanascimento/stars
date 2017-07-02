<?php

namespace Stars\Client\Controller;

use Stars\Core\Controller\Controller;
use Stars\Client\Model\RateModel;
use Stars\Client\FormValidator\RateFormValidator;


class RateController extends Controller
{

    public function newAction()
    {
        $model = null;
        $input_errors = null;
        $message = null;
        $transaction_id = $this->request->param('id','get');
        $repository = $this->db->getRepository('Stars\\Client\\Model\\RateModel');
        $action = '/client/rate/new';

        if ($repository->checkIfTransactionHasRate($transaction_id)) {
            $message = array('status' => 'alert-danger', 
                'message' => 'Essa transação já possui uma avaliação, você não pode inserir uma nova'
            );
            return $this->view->render('error.html.twig', array('message' => $message));
        }

        if ($this->request->isPost()) {
            $post = $this->request->post();
            $validator = new RateFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $id = $repository->insert($post);
                    
                    $message = array('status' => 'alert-success', 'message' => 'Avaliação foi inserida foi inserida com sucesso');
                    $action = '/client/rate/update';
                    $model = (object) ($post + array('id' => $id));
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi possível inserir');
                }
            } else {
                //form invalid
                $model = (object) $post;
                $input_errors = $validator->getMessages();
            }
        }

        return $this->view->render('rate/form.html.twig', 
            array('message' => $message,
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => $action,
                'transaction_id' => $transaction_id
            )
        );
    }

    public function updateAction()
    {
        $id = $this->request->param('id','get');
        $model = null;
        $input_errors = null;
        $message = null;
        $repository = $this->db->getRepository('Stars\\Client\\Model\\RateModel');

        //load form
        if ($this->request->isPost()) {
            $id = $this->request->param('id','post');

            $post = $this->request->post();
            $validator = new RateFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->update($post, array('id' => $id));
                    $message = array('status' => 'alert-success', 'message' => 'A avaliação foi atualiza com sucesso');
                    $model = new RateModel($post);
                } catch (\Exception $e) {
                    $message = array('status' => 'alert-danger', 'message' => 'Não foi atualizar');
                }
            } else {
                //form invalid
                $model = new RateModel($post);
                $input_errors = $validator->getMessages();
            }
        }

        if (empty($model)) {
            $model = $repository->findByOne(array('id' => $id));   
        }

        return $this->view->render('rate/form.html.twig', 
            array('message' => $message, 
                'input_errors' => $input_errors, 
                'model' => $model, 
                'action' => '/client/rate/update'
            )
        );
    }
}
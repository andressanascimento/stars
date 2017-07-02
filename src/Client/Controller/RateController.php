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

        if ($this->request->isPost()) {
            $post = $this->request->post();
            var_dump($post);
            $validator = new RateFormValidator();
            if ($validator->isValid($post)) {
                try {
                    $repository->insert($post);
                    $message = array('status' => 'alert-success', 'message' => 'Avaliação foi inserida foi inserida com sucesso');
                } catch (\Exception $e) {
                        var_dump($e->getMessages());
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
                'action' => '/client/rate/new',
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
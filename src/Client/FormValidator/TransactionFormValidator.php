<?php

namespace Stars\Client\FormValidator;

use Stars\Core\FormValidator\FormValidator;
use Stars\Core\Validator\CnpjValidator;

class TransactionFormValidator extends FormValidator
{
    protected $rules;

    public function __construct() {
        $this->rules = array (
            'client_id' => array(
                'required' => true
            ),
            'store_id' => array(
                'required' => true
            ),
            'employee_id' => array(
                'required' => true
            )
        );
    }
}
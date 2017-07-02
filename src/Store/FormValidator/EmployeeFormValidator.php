<?php

namespace Stars\Store\FormValidator;

use Stars\Core\FormValidator\FormValidator;
use Stars\Core\Validator\CnpjValidator;

class EmployeeFormValidator extends FormValidator
{
    protected $rules;

    public function __construct() {
        $this->rules = array (
            'name' => array(
                'required' => true
            ),
            'store_id' => array(
                'required' => true
            )
        );
    }
}
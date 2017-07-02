<?php

namespace Stars\Store\FormValidator;

use Stars\Core\FormValidator\FormValidator;
use Stars\Core\Validator\CnpjValidator;

class StoreFormValidator extends FormValidator
{
    protected $rules;

    public function __construct() {
        $this->rules = array (
            'name' => array(
                'required' => true
            ),
            'cnpj' => array (
                'validators' => array (
                    'cnpj' => new CnpjValidator()
                ),
                'required' => true
            ),
            'state_id' => array(
                'required' => true
            )
        );
    }
}
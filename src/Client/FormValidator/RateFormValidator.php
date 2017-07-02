<?php

namespace Stars\Client\FormValidator;

use Stars\Core\FormValidator\FormValidator;
use Stars\Core\Validator\CnpjValidator;

class RateFormValidator extends FormValidator
{
    protected $rules;

    public function __construct() {
        $this->rules = array (
            'transaction_id' => array(
                'required' => true
            ),
            'rate' => array(
                'required' => true
            )
        );
    }
}
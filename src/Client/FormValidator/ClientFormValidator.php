<?php

namespace Stars\Client\FormValidator;

use Stars\Core\FormValidator\FormValidator;

class ClientFormValidator extends FormValidator
{
    protected $rules;

    public function __construct() {
        $this->rules = array (
            'name' => array(
                'required' => true
            ),
            'age' => array (
                'validators' => array (
                    'is_int' => function ($value, &$message) {
                        if (!ctype_digit(trim($value))) {
                            $message = "Idade não é um valor inteiro";
                            return false;
                        }
                        return true;
                    }
                ),
                'required' => true
            )
        );
    }
}
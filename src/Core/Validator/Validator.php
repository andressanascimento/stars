<?php

namespace Stars\Core\Validator;

interface Validator
{
    public function isValid($value);

    public function getMessage();
}
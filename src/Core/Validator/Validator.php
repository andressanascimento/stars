<?php

namespace Stars\Core\Validator;

interface Validator
{
	public function isValid();

	public function getMessage();
}
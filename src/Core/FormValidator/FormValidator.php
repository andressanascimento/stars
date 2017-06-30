<?php

namespace Stars\Core\ValidateForm;

class ValidateForm
{
	protected $rules;

	protected $messages;

	public function valid(array $form_values)
	{
		$this->messages = null;
		$messages = array();
		$invalid = 0;
		$rules = $this->rules;
		if ($this->validateRules($this->rules)) {
			foreach ($form_values as $input => $input_value) {
				if (array_key_exists($input, $rules)) {
					foreach ($rules[$input]['validators'] as $validator) {
						$messages = null;
						if ($validator instanceof \Closure) {
							if (!$validator($value,&$message)) {
								$messages[$input] = $message;
								$invalid++;
							}
						}
					}
				}
			}
		}

		return $invalid > 0 ? false : true;
	}

	protected function validateRules()
	{
		$rules = $this->rules;
		if (is_null($rules)) {
			throw new \Exception("Rules not defined");
		}

		foreach ($rules as $field => $definition) {
			if (!array_key_exists('validators', $definition)) {
				throw new \Exception("Validators not defined for field ".$field);
			}

			foreach ($definition['validators'] as $validator) {
				if (!($validator instanceof \Closure) || !($validator instanceof Stars\Core\Validator\Validator)) {
					throw new \Exception("Your validator should be a closure or a implementation of Stars\/Core\/Validator\/Validator".$field);
				}
			}
		}

		return true;
	}

	public function getMessages()
	{
		return $this->getMessages;
	}
}
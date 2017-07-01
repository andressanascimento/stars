<?php

namespace Stars\Core\FormValidator;

class FormValidator
{
    protected $rules;

    protected $messages;

    public function isValid(array $form_values)
    {
        $this->messages = null;
        $invalid = true;
        $rules = $this->rules;
        if ($this->validateRules($this->rules)) {
            foreach ($form_values as $input => $input_value) {
                if (array_key_exists($input, $rules)) {
                    if (array_key_exists('validators', $rules[$input])) {
                        foreach ($rules[$input]['validators'] as $validator) {
                            $messages = null;
                            if ($validator instanceof \Closure) {
                                if (!$validator($input_value, $message)) {
                                    $this->messages[$input][] = $message;
                                    $invalid = false;
                                }
                            }

                            if ($validator instanceof Stars\Core\Validator\Validator) {
                                if (!$validator->isValid($input_value)) {
                                    $this->messages[$input][] = $validator->getMessages();
                                    $invalid = false;
                                }
                            }
                        }
                    }
                }
            }

            if(!$this->checkRequired($form_values)) {
                $invalid = false;
            }
        }

        return $invalid;
    }

    protected function checkRequired(array $form_values)
    {
        $invalid = true;
        foreach ($this->rules as $input_name => $rule) {
            if (array_key_exists('required', $rule) && empty($form_values[$input_name])) {
                $this->messages[$input_name][] = "É necessário preencher este campo";
                $invalid = false;
            }
        }
        return $invalid;
    }

    protected function validateRules()
    {
        $rules = $this->rules;
        if (is_null($rules)) {
            throw new \Exception("Rules not defined");
        }

        foreach ($rules as $field => $definition) {
            if (array_key_exists('validators', $definition)) {
                foreach ($definition['validators'] as $validator) {
                    if (!($validator instanceof \Closure || $validator instanceof Stars\Core\Validator\Validator)) {
                        throw new \Exception("Your validator should be a closure or a implementation of Stars\/Core\/Validator\/Validator");
                    }
                }
            }
        }

        return true;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
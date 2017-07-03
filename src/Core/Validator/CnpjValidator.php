<?php

namespace Stars\Core\Validator;

use Stars\Core\Validator\Validator;

class CnpjValidator implements Validator
{
    private $message;

    /**
     * Check if is a valid CNPJ
     * @param string $value Value from post
     * @return boolean
     */
    public function isValid($value)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $value);

        $invalidos = array(
            '00000000000000',
            '11111111111111',
            '22222222222222',
            '33333333333333',
            '44444444444444',
            '55555555555555',
            '66666666666666',
            '77777777777777',
            '88888888888888',
            '99999999999999'
        );

        // Verifica se o CNPJ está na lista de inválidos
        if (in_array($cnpj, $invalidos)) {
            $this->message = "Não é um CNPJ válido";
            return false;
        }

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            $this->message = "Não é um CNPJ válido";
            return false;
        }
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)) {
            $this->message = "Não é um CNPJ válido";
            return false;
        }
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        if (!($cnpj{13} == ($resto < 2 ? 0 : 11 - $resto))) {
            $this->message = "Não é um CNPJ válido";
            return false;
        }
        return true;
    }

    /**
     * Return error message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
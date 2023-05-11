<?php

namespace App\Utils;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CpfUtil
{
    public static function cpfGenerate() {
        $cpf = '';
    
        // Gerar nove primeiros dígitos aleatoriamente
        for ($i = 0; $i < 9; $i++) {
            $cpf .= rand(0, 9);
        }
    
        // Calcular primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        if ($resto < 2) {
            $digito1 = 0;
        } else {
            $digito1 = 11 - $resto;
        }
    
        // Acrescentar primeiro dígito verificador ao CPF
        $cpf .= $digito1;
    
        // Calcular segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        if ($resto < 2) {
            $digito2 = 0;
        } else {
            $digito2 = 11 - $resto;
        }
    
        // Acrescentar segundo dígito verificador ao CPF
        $cpf .= $digito2;
    
        // Retornar CPF gerado
        return substr_replace(substr_replace(substr_replace($cpf, '.', 3, 0), '.', 7, 0), '-', 11, 0);
    }

    public static function cpfValidation($number)
    {
        // Passo 2
        $cpf = preg_replace('/[^0-9]/', "", $number);
    
        // Passo 3
        if (strlen($cpf) != 11) {
            return false;
        }
    
        // Passo 4
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // Passo 5
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (10 - $i);
        }
        $digit = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
        if ($digit != intval($cpf[9])) {
            return false;
        }
    
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($cpf[$i]) * (11 - $i);
        }
        $digit = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
        if ($digit != intval($cpf[10])) {
            return false;
        }
    
        // Passo 6
        return true;
    }
}
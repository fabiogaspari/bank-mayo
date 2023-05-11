<?php

namespace App\Utils;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CnpjUtil
{
  public static function cnpjGenerate(): string
  {
    $n1 = rand(0, 9);
    $n2 = rand(0, 9);
    $n3 = rand(0, 9);
    $n4 = rand(0, 9);
    $n5 = rand(0, 9);
    $n6 = rand(0, 9);
    $n7 = rand(0, 9);
    $n8 = rand(0, 9);
    $n9 = 0;
    $n10 = 0;
    $n11 = 0;
    $n12 = 1;

    $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
    $d1 = 11 - ($d1 % 11);
    if ($d1 >= 10) {
      $d1 = 0;
    }

    $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
    $d2 = 11 - ($d2 % 11);
    if ($d2 >= 10) {
      $d2 = 0;
    }

    $cnpj = "$n1$n2.$n3$n4$n5.$n6$n7$n8/$n9$n10$n11$n12-$d1$d2";

    return $cnpj;
  }

  public static function cnpjValidation($cnpj)
  {
    $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

    // Valida tamanho
    if (strlen($cnpj) != 14)
      return false;

    // Verifica se todos os digitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj))
      return false;

    // Valida primeiro dígito verificador
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
      $soma += $cnpj[$i] * $j;
      $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
      return false;

    // Valida segundo dígito verificador
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
      $soma += $cnpj[$i] * $j;
      $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
  }
}

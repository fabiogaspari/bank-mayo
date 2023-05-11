<?php

namespace App\Rules;

use App\Utils\CnpjUtil;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyValidCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( !CnpjUtil::cnpjValidation($value) ) {
            $fail('O :attribute deve ser um CNPJ válido.');
        }
    }
}

<?php

namespace App\Rules;

use App\Utils\CnpjUtil;
use App\Utils\CpfUtil;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyValidCpfCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!CpfUtil::cpfValidation($value)) {
            if (!CnpjUtil::cnpjValidation($value)) {
                $fail('O :attribute deve ser um CPF/CNPJ válido.');
            }
        }
    }
}

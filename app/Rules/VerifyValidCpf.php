<?php

namespace App\Rules;

use App\Utils\CpfUtil;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyValidCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( !CpfUtil::cpfValidation($value) ) {
            $fail('O :attribute deve ser um CPF válido.');
        }
    }
}

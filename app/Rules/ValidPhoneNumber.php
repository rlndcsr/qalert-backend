<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Validate the phone number.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = str_replace([' ', '-'], '', $value);

        if (!preg_match('/^\d+$/', $value)) {
            $fail('The phone number must contain only digits.');
            return;
        }

        if (strlen($value) !== 11) {
            $fail('The phone number must be exactly 11 digits.');
            return;
        }

        if (!str_starts_with($value, '09')) {
            $fail('The phone number must start with 09.');
            return;
        }
    }
}

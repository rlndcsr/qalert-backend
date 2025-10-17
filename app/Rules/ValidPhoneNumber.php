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
        // Remove spaces or dashes just in case
        $value = str_replace([' ', '-'], '', $value);

        // 1️⃣ Must be all digits
        if (!preg_match('/^\d+$/', $value)) {
            $fail('The phone number must contain only digits.');
            return;
        }

        // 2️⃣ Must be exactly 11 digits
        if (strlen($value) !== 11) {
            $fail('The phone number must be exactly 11 digits.');
            return;
        }

        // 3️⃣ Must start with 09
        if (!str_starts_with($value, '09')) {
            $fail('The phone number must start with 09.');
            return;
        }
    }
}

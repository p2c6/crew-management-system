<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ValidSlug implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $val = strtolower($value);
        $count = explode(" ", $val);
        
        if (count($count) > 1 && !str_contains($val, '_')) {
            $fail('The :attribute must be contain with underscore/s');
        }
    }
}
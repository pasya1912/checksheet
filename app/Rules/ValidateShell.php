<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateShell implements Rule
{


    public function passes($attribute, $value)
    {
        if ($value == 'm1' || $value == 'm2') {
            return true;
        }

    }

    public function message()
    {
        return "Shell tidak valid.";
    }
}

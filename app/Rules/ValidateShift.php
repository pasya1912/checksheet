<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateShift implements Rule
{


    public function passes($attribute, $value)
    {
        if ($value == '1' || $value == '3' || $value == '2' || $value == '1-long' ||$value == '3-long') {
            return true;
        }

    }

    public function message()
    {
        return "Shift tidak valid.";
    }
}

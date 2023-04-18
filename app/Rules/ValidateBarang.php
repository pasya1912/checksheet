<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateBarang implements Rule
{


    public function passes($attribute, $value)
    {
        if ($value == 'first' || $value == 'last') {
            return true;
        }

    }

    public function message()
    {
        return "Urutan barang harus first atau last.";
    }
}

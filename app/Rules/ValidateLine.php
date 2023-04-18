<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateLine implements Rule
{


    public function passes($attribute, $value)
    {
        $result = DB::table('tm_checksheet')
            ->select('line')
            ->where('line', $value)
            ->count();

        return $result > 0;
    }

    public function message()
    {
        return "Line tidak ada dalam database.";
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateCode implements Rule
{
    protected $line;

    public function __construct($line)
    {
        $this->line = $line;
    }

    public function passes($attribute, $value)
    {
        $result = DB::table('tm_checksheet')
            ->select('code')
            ->where('line', $this->line)
            ->where('code', $value)
            ->count();

        return $result > 0;
    }

    public function message()
    {
        return "Code tidak ada dalam database.";
    }
}

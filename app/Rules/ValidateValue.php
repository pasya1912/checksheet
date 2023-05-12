<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateValue implements Rule
{
    protected $id;
    function __construct($id)
    {
        $this->id = $id;
    }


    public function passes($attribute, $value)
    {
        $db = DB::table('tm_checkarea')
            ->select('tipe')
            ->where('id', $this->id)->first();

        if ($db->tipe == 1) {
            if ($value == 'ok' || $value == 'ng') {
                return true;
            }
        } else if ($db->tipe == 2) {
            //if value number float big any number then true

            if (is_numeric($value)) {
                return true;
            }

        } else if ($db->tipe == 3) {
            return true;
        }


    }

    public function message()
    {
        return "Format Value tidak valid.";
    }
}

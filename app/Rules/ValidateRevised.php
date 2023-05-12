<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use Illuminate\Support\Facades\DB;
class ValidateRevised implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $id;
    protected $mark;
    function __construct($id,$mark)
    {
        $this->id = $id;
        $this->mark = $mark;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->mark == ""){
            return true;
        }
        $db = DB::table('tm_checkarea')
            ->select('tipe','min','max')
            ->where('id', $this->id)->first();
        if ($db->tipe == 1) {
            if ($value == 'ok') {

                return true;
            }
            else{
                return false;
            }
        } else if ($db->tipe == 2) {
            //if value number float big any number then true

            if (is_numeric($value)) {
                if ($value >= $db->min && $value <= $db->max) {
                    return true;
                } else {
                    return false;
                }
            }

        } else if ($db->tipe == 3) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nilai revisi tidak boleh NG.';
    }
}

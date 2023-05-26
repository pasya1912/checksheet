<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;
class FormCheckData
{
    public function getData($id)
    {
        return DB::table('tm_checksheet')
            ->select('line','code','nama as nama_checksheet')
            ->where('id', $id)
            ->first();
    }
}

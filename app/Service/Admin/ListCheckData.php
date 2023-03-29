<?php

namespace App\Service\Admin;

use Illuminate\Support\Facades\DB;

class ListCheckData {

    public function getList($request)
    {
        $data = DB::table('tm_checkdata')
        ->select('tm_checkdata.*')
        ->orderBy('tm_checkdata.id', 'desc')
        ->paginate(10);

    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckdataController extends Controller
{
    public function list(Request $request,\App\Service\Admin\ListCheckData $listCheckData)
    {
        return $listCheckData->getList($request);
    }

}

<?php

namespace App\Http\Controllers;

use App\Service\ChecksheetData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ChecksheetData $checksheetData)
    {
        //class object empty
        $arr = [];
        $line = $checksheetData->getLine();

        foreach ($line as $key => $value) {
            $arr[$key]['line'] = $value->line;
            $arr[$key]['model'] = array_map(function ($object) use ($checksheetData,$value) {
                $ya = [];
                $ya['model'] = $object->code;
                $ya['status'] = $checksheetData->getStatus($value->line,$object->code);
                return $ya;
            }, $checksheetData->getCode($value->line)->values()->toArray());
        }
        return view('dashboard',['datas' => $arr,'lines' => $line]);
    }
    public function getStatus(Request $request, ChecksheetData $checksheetData)
    {
        $arr = [];
        $model = $checksheetData->getCode($request->line);
        foreach($model as $key => $value){
            $arr[$key]['model'] = $value->code;
            $arr[$key]['status'] = $checksheetData->getStatus($request->line,$value->code);
        }


        return $arr;
    }
}

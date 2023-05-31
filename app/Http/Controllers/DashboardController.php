<?php

namespace App\Http\Controllers;

use App\Service\ChecksheetData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ChecksheetData $checksheetData)
    {
        //class object empty
        $datas = [];
        $line = $checksheetData->getLine();

        foreach ($line as $key => $value) {
            $datas[$key]['line'] = $value->line;
            $datas[$key]['model'] = array_map(function ($object) use ($checksheetData,$value) {
                $ya = [];
                $ya['model'] = $object->code;
                $ya['status'] = $checksheetData->getStatus($value->line,$object->code);
                return $ya;
            }, $checksheetData->getCode($value->line)->values()->toArray());
        }
        return view('dashboard',compact('datas'));
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

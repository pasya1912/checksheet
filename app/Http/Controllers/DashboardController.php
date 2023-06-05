<?php

namespace App\Http\Controllers;

use App\Service\ChecksheetData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ChecksheetData $checksheetData)
    {
        //class object empty
        $daget = [];
        $line = $checksheetData->getLine();

        foreach ($line as $key => $value) {
            foreach($checksheetData->getCode($value->line) as $key2 => $value2){
                $daget[$key]['line'] = $value->line;
                $daget[$key]['model'] = array_map(function ($item) {
                    return $item->code;
                }, $checksheetData->getCode($value->line)->toArray());
                $daget[$key]['status']['ok'][] = $checksheetData->getGood($value->line,$value2->code);
                $daget[$key]['status']['ng'][] = $checksheetData->getBad($value->line,$value2->code);
                $daget[$key]['status']['revised'][] = $checksheetData->getRevised($value->line,$value2->code);

            }
        }

        $allArray = [];

        $allArray['line'] = array_map(function ($item) {
            return $item->line;
        }, $line->toArray());
        foreach($allArray['line'] as $key => $value){
            $allArray['status']['ok'][] = $checksheetData->getGood($value);
            $allArray['status']['ng'][] = $checksheetData->getBad($value);
            $allArray['status']['revised'][] = $checksheetData->getRevised($value);
        }





        return view('dashboard',compact('daget','allArray'));
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

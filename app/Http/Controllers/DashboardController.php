<?php

namespace App\Http\Controllers;

use App\Service\ChecksheetData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ChecksheetData $checksheetData)
    {
        //class object empty
        $daget = [];
        $line = $checksheetData->getLine();

        foreach ($line as $key => $value) {
            $daget[$key]['status']['ok'] = 0;
            $daget[$key]['status']['ng'] = 0;
            $daget[$key]['status']['revised'] = 0;
            foreach($checksheetData->getCode($value->line) as $key2 => $value2){
                $daget[$key]['line'] = $value->line;

                $daget[$key]['status']['ok']     += $checksheetData->getGood($value->line,$value2->code);
                $daget[$key]['status']['ng'] += $checksheetData->getBad($value->line,$value2->code);
                if($checksheetData->getRevised($value->line,$value2->code) > 0)
                {
                    $daget[$key]['status']['ng'] -= $checksheetData->getRevised($value->line,$value2->code);
                }
                $daget[$key]['status']['revised'] += $checksheetData->getRevised($value->line,$value2->code);

            }
        }

        $allArray = [];

        $line = array_map(function ($item) {
            return $item->line;
        }, $line->toArray());
        $allArray['status']['ok'] = 0;
        $allArray['status']['ng'] = 0;
        $allArray['status']['revised'] = 0;
        foreach($line as $key => $value){
            $allArray['status']['ok'] += $checksheetData->getGood($value);
            $allArray['status']['ng'] += $checksheetData->getBad($value);
            if($checksheetData->getRevised($value) > 0)
            {
                $allArray['status']['ng'] -= $checksheetData->getRevised($value);
            }
            $allArray['status']['revised'] += $checksheetData->getRevised($value);
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

<?php

namespace App\Http\Controllers;

use App\Service\ChecksheetData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ChecksheetData $checksheetData)
    {

        //tanggal from get query
        $tanggal = request()->get('tanggal') ?? date('Y-m-d');
        $daget = [];
        $line = $checksheetData->getLine();

        foreach ($line as $key => $value) {
            $daget[$key]['status']['ok'] = 0;
            $daget[$key]['status']['ng'] = 0;
            $daget[$key]['status']['revised'] = 0;
            foreach($checksheetData->getCode($value->line) as $key2 => $value2){
                $daget[$key]['line'] = $value->line;

                $daget[$key]['status']['ok']     += $checksheetData->getGood($value->line,$value2->code,$tanggal);
                $daget[$key]['status']['ng'] += $checksheetData->getBad($value->line,$value2->code,$tanggal);
                if($checksheetData->getRevised($value->line,$value2->code,$tanggal) > 0)
                {
                    $daget[$key]['status']['ng'] -= $checksheetData->getRevised($value->line,$value2->code,$tanggal);
                }
                $daget[$key]['status']['revised'] += $checksheetData->getRevised($value->line,$value2->code,$tanggal);

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
            $allArray['status']['ok'] += $checksheetData->getGood($value,null,$tanggal);
            $allArray['status']['ng'] += $checksheetData->getBad($value,null,$tanggal);
            if($checksheetData->getRevised($value,null,$tanggal) > 0)
            {
                $allArray['status']['ng'] -= $checksheetData->getRevised($value,null,$tanggal);
            }
            $allArray['status']['revised'] += $checksheetData->getRevised($value,null,$tanggal);
        }
        return view('dashboard',compact('daget','allArray'));
    }
}

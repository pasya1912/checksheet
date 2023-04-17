<?php

namespace App\Http\Controllers;
//use db
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ChecksheetController extends Controller
{
    public function list(Request $request, \App\Service\ChecksheetData $checksheet)
    {
        $line = !$request->get('line') ? '': $request->get('line');
        $code = !$request->get('code') ? '' : $request->get('code');
        if($line == '' && $code == ''){
            $checkList = 500;
        }
        else if($line != '' && $code == ''){
            $checkList = 400;
        }else{


        //get checksheet from db based on search parameter if exist with like paginate every 10
        $checkList = DB::table('tm_checksheet')
            //cek apakah terdapat tt_checkdata yang tidak sesuai min max
            ->select('tm_checksheet.*',DB::raw('sum((CASE WHEN tm_checkarea.tipe = "1" THEN
            (CASE WHEN tt_checkdata.value = "ok" THEN 0
            WHEN tt_checkdata.value = "ng" THEN 1
            ELSE 0 END)
        WHEN tm_checkarea.tipe = "2" THEN
            (CASE WHEN tt_checkdata.value BETWEEN IFNULL(tm_checkarea.min,-99999999) AND IFNULL(tm_checkarea.max,99999999) THEN 0 ELSE 1 END)
        ELSE 0 END)) as notgood'))
            ->leftJoin('tm_checkarea', 'tm_checksheet.id', '=', 'tm_checkarea.id_checksheet')
            ->leftJoin('tt_checkdata', 'tm_checkarea.id', '=', 'tt_checkdata.id_checkarea')
            ->whereDate('tt_checkdata.tanggal', '=', date('Y-m-d'))
            //search in all columns



            ->where(function ($query) use ($request,$line,$code) {

                    $query->where('line', 'LIKE', '%' . $line . '%')
                        ->where('code', 'LIKE', '%' . $code . '%');

            })
            ->groupBy('tm_checksheet.id')
            ->paginate(20)->appends(request()->query())->toArray();
        }

        $lineList = $checksheet->getLine();
        $codeList = $checksheet->getCode($request->get('line'));






        //return view checklist with checklist
        return view('checksheet.list', compact('checkList','lineList','codeList'));
    }
}

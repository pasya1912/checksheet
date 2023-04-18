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
                ->select('tm_checksheet.*')


            ->where(function ($query) use ($line,$code) {

                    $query->where('line', 'LIKE', '%' . $line . '%')
                        ->where('code', 'LIKE', '%' . $code . '%');

            })

            ->paginate(20)->appends(request()->query())->toArray();
            foreach($checkList['data'] as $key => $value){
                $checkList['data'][$key]->notgood = DB::table('tt_checkdata')
                ->select('tt_checkdata.*','tm_checkarea.min','tm_checkarea.max')
                ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                ->where('tm_checksheet.id',$value->id)
                ->whereDate('tanggal',date('Y-m-d'))
                ->where('tt_checkdata.value',DB::raw('tt_checkdata.value'))
                ->whereRaw('
                (CASE
                    WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ng"
                    WHEN tm_checkarea.tipe = "2" THEN tt_checkdata.value NOT BETWEEN IFNULL(tm_checkarea.min,-99999999) AND IFNULL(tm_checkarea.max,99999999)
                    WHEN tm_checkarea.tipe = "3" THEN tt_checkdata.value = "xxxxxx"
                    END
                    )')


                ->count();
            }

        }


        $lineList = $checksheet->getLine();
        $codeList = $checksheet->getCode($request->get('line'));







        //return view checklist with checklist
        return view('checksheet.list', compact('checkList','lineList','codeList'));
    }
}

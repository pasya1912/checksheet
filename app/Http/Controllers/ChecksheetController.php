<?php

namespace App\Http\Controllers;

//use db
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ChecksheetController extends Controller
{
    public function list(Request $request, \App\Service\ChecksheetData $checksheet)
    {
        $line = !$request->get('line') ? '' : $request->get('line');
        $code = !$request->get('code') ? '' : $request->get('code');
        $cell = !$request->get('cell') ? '' : $request->get('cell');
        $shift = !$request->get('shift') ? '' : $request->get('shift');
        $barang = !$request->get('barang') ? '' : $request->get('barang');

        $query = new \stdClass();
        $query->cell = $cell;
        $query->shift = $shift;
        $query->barang = $barang;
        $query->line = $line;
        $query->code = $code;
        if ($line == '' && $code == '') {
            $checkList = 500;
        } else if ($line != '' && $code == '') {
            $checkList = 400;
        } else {


            //get checksheet from db based on search parameter if exist with like paginate every 10
            $checkList = DB::table('tm_checksheet')
                ->select('tm_checksheet.*')


                ->where(function ($query) use ($line, $code) {

                    $query->where('line', 'LIKE', '%' . $line . '%')
                        ->where('code', 'LIKE', '%' . $code . '%');

                })

                ->paginate(20)->appends(request()->query())->toArray();
            foreach ($checkList['data'] as $key => $value) {
                $checkList['data'][$key]->all = DB::table('tm_checkarea')
                    ->select('id')->where('tm_checkarea.id_checksheet', $value->id)->count();

                $checkList['data'][$key]->notgood = DB::table('tt_checkdata')

                    ->select('tm_checkarea.id', 'tm_checkarea.tipe', 'tt_checkdata.value')
                    ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                    ->where('tm_checksheet.id', $value->id)
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.shift', $shift)
                    ->whereRaw('
                (CASE
                    WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ng"
                    WHEN tm_checkarea.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) < IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-Infinity" AS DECIMAL(10,4)))) OR (CAST(tt_checkdata.value AS DECIMAL(10,4)) > IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("Infinity" AS DECIMAL(10,4))))
                    WHEN tm_checkarea.tipe = "3" THEN tt_checkdata.value = "xxxxxx"
                    END
                    )')
                    ->count();
                $checkList['data'][$key]->good = DB::table('tm_checkarea')
                    ->select('tm_checkarea.id', 'tm_checkarea.tipe', 'tt_checkdata.value')->where('tm_checkarea.id_checksheet', $value->id)
                    ->leftJoin('tt_checkdata', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'))
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.shift', $shift)
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->whereRaw('
                (CASE
                    WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ok"
                    WHEN tm_checkarea.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) >= IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-Infinity" AS DECIMAL(10,4)))) AND (CAST(tt_checkdata.value AS DECIMAL(10,4)) <= IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("Infinity" AS DECIMAL(10,4))))
                    WHEN tm_checkarea.tipe = "3" THEN tt_checkdata.value = tt_checkdata.value
                    END
                    )')
                    ->count();
                $checkList['data'][$key]->approval = DB::table('tt_checkdata')
                    ->select('tt_checkdata.*')
                    ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                    ->where('id_checksheet', $value->id)
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.shift', $shift)
                    ->whereDate('tt_checkdata.tanggal', date('Y-m-d'))
                    ->where('tt_checkdata.approval', 'approved')
                    ->count();

                $checkList['data'][$key]->status =
                ($checkList['data'][$key]->all == $checkList['data'][$key]->good ? 'DONE-OK' : ($checkList['data'][$key]->notgood && $checkList['data'][$key]->good + $checkList['data'][$key]->notgood == $checkList['data'][$key]->all ? 'DONE-NG' : ($checkList['data'][$key]->notgood > 0 ? 'PROGRESS-NG' : (($checkList['data'][$key]->notgood == 0 && $checkList['data'][$key]->good >0) ?'PROGRESS-OK':'NOT-STARTED'))));


            }

        }
        $lineList = $checksheet->getLine();
        $codeList = $checksheet->getCode($request->get('line'));
        //return view checklist with checklist
        return view('checksheet.list', compact('checkList', 'lineList', 'codeList','query'));
    }
}

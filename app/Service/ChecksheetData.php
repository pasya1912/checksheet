<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ChecksheetData
{
    public function getLine()
    {

        $line = Cache::remember('getLine', 300, function () {
            return DB::table('tm_checksheet')
                ->select('line')
                ->distinct()
                ->orderBy('line', 'ASC')
                ->get();
        });
        return $line;
    }
    public function getCode($line)
    {
        $model = Cache::remember('getCode' . $line, 300, function () use ($line) {
            return DB::table('tm_checksheet')
                ->select('code')
                ->distinct()
                ->where('line', $line)
                ->orderBy('code', 'ASC')
                ->get();
        });
        return $model;
    }
    public function getChecksheet($line, $code)
    {
        $sheet = Cache::remember('getChecksheet' . $line . $code, 300, function () use ($line, $code) {
            return DB::table('tm_checksheet')
                ->select('id', 'nama')
                ->distinct()
                ->where('line', $line)
                ->where('code', $code)
                ->orderBy('id', 'ASC')
                ->get();
        });
        return $sheet;
    }
    public function getArea($line, $code, $idchecksheet)
    {
        $area = Cache::remember('getArea' . $line . $code . $idchecksheet, 300, function () use ($line, $code, $idchecksheet) {
            return DB::table('tm_checkarea')
                ->select('id', 'nama')
                ->distinct()
                ->where('id_checksheet', $idchecksheet)
                ->orderBy('id', 'ASC')
                ->get();
        });
        return $area;
    }
    public function getStatus($line,$model)
    {

        $all = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'))
        ->where('tm_checksheet.line',$line)
        ->where('tm_checksheet.code',$model)
        ->whereDate('tanggal', date('Y-m-d'))->count();

        $good = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'))
        ->where('tm_checksheet.line',$line)
        ->where('tm_checksheet.code',$model)
        ->whereDate('tanggal', date('Y-m-d'))
        ->whereRaw('
        (CASE
            WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ok"
            WHEN tm_checkarea.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) >= IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-999999" AS DECIMAL(10,4)))) AND (CAST(tt_checkdata.value AS DECIMAL(10,4)) <= IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("999999" AS DECIMAL(10,4))))
            WHEN tm_checkarea.tipe = "3" THEN tt_checkdata.value = tt_checkdata.value
            END
            )')
        ->count();
        $notgood = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'))
        ->where('tm_checksheet.line',$line)
        ->where('tm_checksheet.code',$model)
        ->whereDate('tanggal', date('Y-m-d'))
        ->whereRaw('
        (CASE
            WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ng"
            WHEN tm_checkarea.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) < IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-999999" AS DECIMAL(10,4)))) OR (CAST(tt_checkdata.value AS DECIMAL(10,4)) > IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("999999" AS DECIMAL(10,4))))
            WHEN tm_checkarea.tipe = "3" THEN 1 = 2
            END
            )')
        ->count();
        $revisi = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'))
        ->where('tm_checksheet.line',$line)
        ->where('tm_checksheet.code',$model)
        ->whereDate('tanggal', date('Y-m-d'))
        ->whereNotNull('tt_checkdata.revised_value')
        ->where('tt_checkdata.mark', '1')->count();
        $arr = [
            'good' => $good,
            'notgood' => $notgood,
            'revisi' => $revisi
        ];
        return $arr;

    }
}

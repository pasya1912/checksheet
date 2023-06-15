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
    public function getAll($line = null,$model = null,$tanggal = null)
    {
        $all = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'));
        if($line != null){
            $all->where('tm_checksheet.line',$line);
        }
        if($model != null){
            $all->where('tm_checksheet.code',$model);
        }
        if($tanggal != null){
            $all->where('tanggal', '>=', startOfDay($tanggal))
            ->where('tanggal', '<', endOfDay($tanggal));
        }else
        {
            $all->where('tanggal', '>=', startOfDay())
            ->where('tanggal', '<', endOfDay());
        }
        return $all->count();

    }
    public function getGood($line = null,$model = null,$tanggal = null)
    {

        $good = DB::table('tt_checkdata')
        ->select('tm_checkdata.id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'));
        if($line != null){
            $good->where('tm_checksheet.line',$line);
        }
        if($model != null){
            $good->where('tm_checksheet.code',$model);
        }
        if($tanggal != null){
            $good->where('tanggal', '>=', startOfDay($tanggal))
            ->where('tanggal', '<', endOfDay($tanggal));
        }else
        {
            $good->where('tanggal', '>=', startOfDay())
            ->where('tanggal', '<', endOfDay());
        }
        $good->whereRaw('
        (CASE
            WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ok"
            WHEN tm_checkarea.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) >= IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-999999" AS DECIMAL(10,4)))) AND (CAST(tt_checkdata.value AS DECIMAL(10,4)) <= IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("999999" AS DECIMAL(10,4))))
            WHEN tm_checkarea.tipe = "3" THEN tt_checkdata.value = tt_checkdata.value
            END
            )');

        return $good->count();
    }

    public function getBad($line = null,$model = null,$tanggal = null)
    {

        $notgood = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'));
        if($line != null){
            $notgood->where('tm_checksheet.line',$line);
        }
        if($model != null){
            $notgood->where('tm_checksheet.code',$model);
        }
        if($tanggal != null){
            $notgood->where('tanggal', '>=', startOfDay($tanggal))
            ->where('tanggal', '<', endOfDay($tanggal));
        }else
        {
            $notgood->where('tanggal', '>=', startOfDay())
            ->where('tanggal', '<', endOfDay());
        }
        $notgood->whereRaw('
        (CASE
            WHEN tm_checkarea.tipe = "1" THEN tt_checkdata.value = "ng"
            WHEN tm_checkarea.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) < IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-999999" AS DECIMAL(10,4)))) OR (CAST(tt_checkdata.value AS DECIMAL(10,4)) > IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("999999" AS DECIMAL(10,4))))
            WHEN tm_checkarea.tipe = "3" THEN 1 = 2
            END
            )');

        return $notgood->count();

    }
    public function getRevised($line = null,$model = null,$tanggal = null)
    {

        $revisi = DB::table('tt_checkdata')
        ->select('id')
        ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
        ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
        ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'));
        if($line != null){
            $revisi->where('tm_checksheet.line',$line);
        }
        if($model != null){
            $revisi->where('tm_checksheet.code',$model);
        }
        if($tanggal != null){
            $revisi->where('tanggal', '>=', startOfDay($tanggal))
            ->where('tanggal', '<', endOfDay($tanggal));

        }else
        {
            $revisi->where('tanggal', '>=', startOfDay())
            ->where('tanggal', '<', endOfDay());
        }
        $revisi->whereNotNull('tt_checkdata.revised_value')
        ->where('tt_checkdata.mark', '1');
        return $revisi->count();

    }
}

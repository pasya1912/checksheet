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
}

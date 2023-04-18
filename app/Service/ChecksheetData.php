<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;
class ChecksheetData
{
    public function getLine()
    {
        $line = DB::table('tm_checksheet')
        ->select('line')
        ->distinct()
        ->orderBy('line','ASC')
        ->get();
        return $line;
    }
    public function getCode($line)
    {
        $line = DB::table('tm_checksheet')
        ->select('code')
        ->distinct()
        ->where('line',$line)
        ->orderBy('code','ASC')
        ->get();
        return $line;
    }
    public function getChecksheet($line,$code)
    {
        $sheet = DB::table('tm_checksheet')
        ->select('id','nama')
        ->distinct()
        ->where('line',$line)
        ->where('code',$code)
        ->orderBy('id','ASC')
        ->get();
        return $sheet;
    }
    public function getArea($line,$code,$idchecksheet)
    {
        $area = DB::table('tm_checkarea')
        ->select('id','nama')
        ->distinct()
        ->where('id_checksheet',$idchecksheet)
        ->orderBy('id','ASC')
        ->get();
        return $area;
    }
}

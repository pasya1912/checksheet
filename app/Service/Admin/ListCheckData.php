<?php

namespace App\Service\Admin;

use Illuminate\Support\Facades\DB;

class ListCheckData
{

    public function get($request)
    {
        $min_tanggal = ($request->get('min_tanggal') == null || $request->get('min_tanggal') == '') ? '' : $request->get('min_tanggal');
        $max_tanggal = ($request->get('max_tanggal') == null || $request->get('max_tanggal') == '') ? '' : $request->get('max_tanggal');
        $barang = ($request->get('barang') == null || $request->get('barang') == '') ? '' : $request->get('barang');
        $shift = ($request->get('shift') == null || $request->get('shift') == '') ? '' : $request->get('shift');
        $cell = ($request->get('cell') == null || $request->get('cell') == '') ? '' : $request->get('cell');
        $area = ($request->get('area') == null || $request->get('area') == '') ? '' : $request->get('area');
        $checksheet = ($request->get('checksheet') == null || $request->get('checksheet') == '') ? '' : $request->get('checksheet');
        $code = ($request->get('code') == null || $request->get('code') == '') ? '' : $request->get('code');
        $line = ($request->get('line') == null || $request->get('line') == '') ? '' : $request->get('line');




        //get corresponding checkdata with id_checkarea from $checkarea
        $isStandar = DB::raw('
        (CASE WHEN tm_checkarea.tipe = "1" THEN
            (CASE
            WHEN tt_checkdata.value = "ok" THEN "good"
            WHEN tt_checkdata.value = "ng" THEN "notgood"
             END)
        WHEN tm_checkarea.tipe = "2" THEN
        (CASE
        WHEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) < IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-Infinity" AS DECIMAL(10,4)))) OR (CAST(tt_checkdata.value AS DECIMAL(10,4)) > IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("Infinity" AS DECIMAL(10,4)))) THEN "notgood"
        ELSE "good"
        END)
        WHEN tm_checkarea.tipe = "3" THEN
            "general"
        END) as status');
        $revisedStatus = DB::raw('
        (CASE WHEN tm_checkarea.tipe = "1" THEN
            (CASE
            WHEN tt_checkdata.revised_value = "ok" THEN "good"
            WHEN tt_checkdata.revised_value = "ng" THEN "notgood"
             END)
        WHEN tm_checkarea.tipe = "2" THEN
            (CASE
            WHEN (CAST(tt_checkdata.revised_value AS DECIMAL(10,4)) < IFNULL(CAST(tm_checkarea.min AS DECIMAL(10,4)), CAST("-Infinity" AS DECIMAL(10,4)))) OR (CAST(tt_checkdata.revised_value AS DECIMAL(10,4)) > IFNULL(CAST(tm_checkarea.max AS DECIMAL(10,4)), CAST("Infinity" AS DECIMAL(10,4)))) THEN "notgood"
            ELSE "good"
            END)
        WHEN tm_checkarea.tipe = "3" THEN
            "general"
        END) as revised_status');

        $checkdata = DB::table('tt_checkdata')
            ->select('tt_checkdata.*', 'tm_checkarea.min', 'tm_checkarea.max', 'tm_checkarea.tipe', 'tm_checksheet.nama as nama_checksheet', 'tm_checkarea.nama as nama_checkarea', 'tm_checksheet.line', 'tm_checksheet.code', 'tm_checksheet.jenis', 'users.name as name', 'users.npk', $isStandar,$revisedStatus)
            ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            ->leftJoin('users', 'tt_checkdata.user', '=', 'users.npk')

            ->where(function ($query) use ($min_tanggal, $max_tanggal, $barang, $shift, $cell, $area, $checksheet, $code, $line) {
                if ($min_tanggal != '') {
                    $query->whereDate('tt_checkdata.tanggal', '>=', $min_tanggal);
                }
                if ($max_tanggal != '') {
                    $query->whereDate('tt_checkdata.tanggal', '<=', $max_tanggal);
                }
                if ($barang != '') {
                    $query->where('tt_checkdata.barang', '=', $barang);
                }
                if ($shift != '') {
                    $query->where('tt_checkdata.shift', 'like', '%' . $shift . '%');
                }
                if ($cell != '') {
                    $query->where('tt_checkdata.nama', 'like', '%' . $cell . '%');
                }
                if ($area != '') {
                    $query->where('tm_checkarea.id', 'like', '%' . $area . '%');
                }
                if ($checksheet != '') {
                    $query->where('tm_checksheet.id', 'like', '%' . $checksheet . '%');
                }
                if ($code != '') {
                    $query->where('tm_checksheet.code', 'like', '%' . $code . '%');
                }
                if ($line != '') {
                    $query->where('tm_checksheet.line', 'like', '%' . $line . '%');
                }
            })
            ->orderBy('tt_checkdata.id','DESC')
            ->paginate(20)->appends(request()->query())->toArray();
        return $checkdata;

    }
}

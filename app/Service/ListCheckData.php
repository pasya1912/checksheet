<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class ListCheckData {

    public function getList($checkarea,$request)
    {
        $checksheet = DB::table('tm_checksheet')
            ->select('line','code','nama as nama_checksheet')
            ->where('id', $checkarea->id_checksheet)
            ->first();
        //merg
        $checksheetarea = (object) array_merge((array) $checksheet, (array) $checkarea);

        //get corresponding checkdata with id_checkarea from $checkarea
        $columns = ['tm_checkarea.nama','tm_checksheet.nama','tt_checkdata.nama','tt_checkdata.barang','tt_checkdata.tanggal','tt_checkdata.user','tt_checkdata.value','tt_checkdata.revised_value'];
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
            ->select('tt_checkdata.*','tm_checkarea.min','tm_checkarea.max','tm_checkarea.tipe',$isStandar,$revisedStatus)
            ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            ->where('tm_checkarea.id_checksheet', $checkarea->id_checksheet)
            ->where('tt_checkdata.id_checkarea', $checkarea->id)
            //search in all columns
            ->where(function ($query) use ($request,$columns) {
                if (($term = $request->get('search'))) {
                    foreach ($columns as $column) {
                        $subQuery = $query->orWhere($column, 'LIKE', "%".$term."%");
                    }
                }
            })
            ->orderBy('tt_checkdata.id','DESC')
            ->paginate(20)->appends(request()->query())->toArray();
            switch($checksheetarea->tipe){
                case 1:
                    return view('checksheet.checkdata.list.satu',compact('checkdata','checksheetarea'));
                    break;
                case 2:
                    return view('checksheet.checkdata.list.dua',compact('checkdata','checksheetarea'));
                    break;
                case 3:
                    return view('checksheet.checkdata.list.tiga',compact('checkdata','checksheetarea'));
                    break;
                default:
                    throw new \Exception('Checksheet error');
                    break;

            }

    }

}

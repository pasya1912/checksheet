<?php

namespace App\Service\Admin;

use Illuminate\Support\Facades\DB;

class ListCheckData {

    public   function getList($request)
    {


        //get corresponding checkdata with id_checkarea from $checkarea
        $columns = ['tm_checkarea.nama','tm_checksheet.nama','tt_checkdata.nama','tt_checkdata.barang','tt_checkdata.tanggal','tt_checkdata.user','tt_checkdata.value'];
        $isStandar = DB::raw('
        (CASE WHEN tm_checkarea.tipe = "1" THEN
            (CASE
            WHEN tt_checkdata.value = "ok" THEN "good"
            WHEN tt_checkdata.value = "ng" THEN "notgood"
             END)
        WHEN tm_checkarea.tipe = "2" THEN
            (CASE WHEN tt_checkdata.value BETWEEN IFNULL(tm_checkarea.min,-99999999) AND IFNULL(tm_checkarea.max,99999999) THEN "good" ELSE "notgood" END)
        WHEN tm_checkarea.tipe = "3" THEN
            "netral"
        END) as status');

        $checkdata = DB::table('tt_checkdata')
            ->select('tt_checkdata.*','tm_checkarea.min','tm_checkarea.max','tm_checkarea.tipe','tm_checksheet.nama as nama_checksheet','tm_checkarea.nama as nama_checkarea','tm_checksheet.line','tm_checksheet.code','tm_checksheet.jenis',$isStandar)
            ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            //search in all columns
            ->where(function ($query) use ($request,$columns) {
                if (($term = $request->get('search'))) {
                    foreach ($columns as $column) {
                        $subQuery = $query->orWhere($column, 'LIKE', "%".$term."%");
                    }
                }
            })
            ->orderBy('tt_checkdata.nama','ASC')
            ->paginate(20)->appends(request()->query())->toArray();
            return view('checksheet.checkdata.list.data',compact('checkdata'));

    }
}

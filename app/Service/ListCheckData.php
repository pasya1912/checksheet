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
            ->select('tt_checkdata.*','tm_checkarea.min','tm_checkarea.max','tm_checkarea.tipe',$isStandar)
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
            ->orderBy('tt_checkdata.nama','ASC')
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

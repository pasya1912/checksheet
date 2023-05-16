<?php

namespace App\Service\Admin;

use Illuminate\Support\Facades\DB;

class ListCheckData
{

    public function get($request, $approval = null)
    {


        $query = $this->getData($request, $approval);
        //update approval


        $checkdata = $query->orderBy('tt_checkdata.id', 'DESC')->paginate(20)->appends(request()->query())->toArray();


        return $checkdata;

    }
    function approve($request, $approval = null): bool
    {
        $query = $this->getData($request, $approval);
        //update approval
        $newApproval = "".($approval+1)."";
        $data = $query->get();
        DB::beginTransaction();
        $update = $query->update([
            'approval' => $newApproval
        ]);

        if($update){

            foreach($data as $d){

                $update = DB::table('approval_history')->insert([
                    'id_checkdata' => $d->id,
                    'approval' => $newApproval,
                    'approval_history.user' => auth()->user()->npk,
                ]);
                if($update <= 0){
                    DB::rollBack();
                    return false;
                }

            }
            DB::commit();
            return true;
        }else{
            DB::rollBack();
            return false;
        }
    }

    function getData($request, $approval = null) : \Illuminate\Database\Query\Builder
    {
        $filter = ($request->get('filter') == null || $request->get('filter') == '') ? '' : $request->get('filter');
        $min_tanggal = ($request->get('min_tanggal') == null || $request->get('min_tanggal') == '') ? '' : $request->get('min_tanggal');
        $max_tanggal = ($request->get('max_tanggal') == null || $request->get('max_tanggal') == '') ? '' : $request->get('max_tanggal');
        $barang = ($request->get('barang') == null || $request->get('barang') == '') ? '' : $request->get('barang');
        $cell = ($request->get('cell') == null || $request->get('cell') == '') ? '' : $request->get('cell');
        $shift = ($request->get('shift') == null || $request->get('shift') == '') ? '' : $request->get('shift');
        $area = ($request->get('area') == null || $request->get('area') == '') ? '' : $request->get('area');
        $checksheet = ($request->get('checksheet') == null || $request->get('checksheet') == '') ? '' : $request->get('checksheet');
        $code = ($request->get('code') == null || $request->get('code') == '') ? '' : $request->get('code');
        $line = ($request->get('line') == null || $request->get('line') == '') ? '' : $request->get('line');

        //get corresponding checkdata with id_checkarea from $checkarea
        $statusCheck = '
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
        END)';
        $isStandar = DB::raw($statusCheck . ' as status');
        $revisedCheck = '
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
        END)';
        $revisedStatus = DB::raw($revisedCheck . ' as revised_status');
        return DB::table('tt_checkdata')
            ->select('tt_checkdata.*', 'tm_checkarea.min', 'tm_checkarea.max', 'tm_checkarea.tipe', 'tm_checksheet.nama as nama_checksheet', 'tm_checkarea.nama as nama_checkarea', 'tm_checksheet.line', 'tm_checksheet.code', 'tm_checksheet.jenis', 'users.name as name', 'users.npk', $isStandar, $revisedStatus)
            ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            ->leftJoin('users', 'tt_checkdata.user', '=', 'users.npk')

            ->where(function ($query) use ($approval, $statusCheck, $revisedCheck, $min_tanggal, $max_tanggal, $barang, $shift, $cell, $area, $checksheet, $code, $line, $filter) {
                if (auth()->user()->role != 'admin') {
                    $query->where('tt_checkdata.user', '=', auth()->user()->npk);
                }
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
                    $query->where('tt_checkdata.shift', '=', $shift);
                }
                if ($cell != '') {
                    $query->where('tt_checkdata.nama', 'like', '%' . $cell . '%');
                }
                if ($area != '') {
                    $query->where('tm_checkarea.id', '=', $area);
                }
                if ($checksheet != '') {
                    $query->where('tm_checksheet.id', '=', $checksheet);
                }
                if ($code != '') {
                    $query->where('tm_checksheet.code', '=', $code);
                }
                if ($line != '') {
                    $query->where('tm_checksheet.line', '=', $line);
                }
                if ($approval != null || $approval != '') {
                    $query->where('tt_checkdata.approval', '=', $approval);
                }
                if ($filter != '') {
                    if ($filter == 'good') {
                        $query->whereRaw($statusCheck . " in ('good','general')");
                    } else if ($filter == 'notgood') {
                        $query->whereRaw($statusCheck . " = 'notgood'");
                    } else if ($filter == 'need_check') {
                        $query->where(function ($query) use ($revisedCheck) {
                            $query->whereIn(DB::raw($revisedCheck), ['good', 'general'])
                                ->orWhere(function ($query) {
                                        $query->where('tt_checkdata.mark', '=', '1')
                                            ->whereNotNull('tt_checkdata.revised_value');
                                    });
                        });
                    } else if ($filter = 'revised') {
                        $query->where('mark', '=', '1')
                            //where revised_value not null
                            ->whereNotNull('revised_value');
                    }
                }
            });
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckareaController extends Controller
{
    public function list($id, Request $request)
    {

        try {
            //if query cell, shift, code,line,barang not exist return error
            if (!$request->query('cell') || !$request->query('shift') || !$request->query('code') || !$request->query('line') || !$request->query('barang')) {
                throw new \Exception('Missing Reqired Parameter');
            }

            //get checksheet from db based on search parameter if exist with like paginate every 10

            $checksheet = DB::table('tm_checksheet')
                ->where('id', $id)
                ->first();
            if (!$checksheet) {
                throw new \Exception('Checksheet not found');
            }
            $areaList = DB::table('tm_checkarea')
                ->select('tm_checkarea.deskripsi', 'tm_checkarea.min', 'tm_checkarea.max', 'tm_checkarea.tipe', 'tm_checkarea.id as id', DB::raw('COALESCE(tm_checkarea.nama,tm_checksheet.nama) as nama'))
                ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                ->where('tm_checkarea.id_checksheet', $id)
                //search in all columns
                ->where(function ($query) use ($request) {
                    if (($term = $request->get('search'))) {
                        $query->orWhere('tm_checkarea.nama', 'LIKE', '%' . $term . '%')
                            ->orWhere('tm_checkarea.deskripsi', 'LIKE', '%' . $term . '%');
                    }
                })
                ->orderBy('tm_checkarea.nama', 'ASC')
                ->paginate(100)->appends(request()->query())->toArray();

            foreach ($areaList['data'] as $key => $area) {
                $area->checkdata = DB::table('tt_checkdata')
                    ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')

                    ->select('tt_checkdata.*','tm_checkarea.id as area_id','tm_checkarea.tipe','tt_checkdata.notes')
                    ->where('tt_checkdata.id_checkarea', $area->id)
                    ->where('tt_checkdata.nama', $request->query('cell'))
                    ->where('tt_checkdata.shift', $request->query('shift'))
                    //where day today
                    ->whereDate('tt_checkdata.tanggal', $request->query('tanggal') ?? date('Y-m-d'))
                    ->first();

                if ($area->checkdata) {
                    if ($area->checkdata->tipe == 2) {
                        $area->checkdata->is_good = $area->checkdata->value >= $area->min && $area->checkdata->value <= $area->max;
                    }
                    elseif($area->checkdata->tipe == 1){
                        $area->checkdata->is_good = $area->checkdata->value == "ok";
                    }
                    elseif($area->checkdata->tipe == 3){
                        $area->checkdata->is_good = true;
                    }
                }
            }
            return view('checksheet.area', compact('areaList', 'checksheet'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }


    }
}

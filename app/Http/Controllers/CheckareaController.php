<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckareaController extends Controller
{
    public function list($id,Request $request)
    {

        try{
        //if query shell, shift, code,line,barang not exist return error
        if(!$request->query('shell') || !$request->query('shift') || !$request->query('code') || !$request->query('line') || !$request->query('barang')){
            throw new \Exception('Missing Reqired Parameter');
        }

        //get checksheet from db based on search parameter if exist with like paginate every 10

        $checksheet = DB::table('tm_checksheet')
            ->where('id', $id)
            ->first();
        if(!$checksheet){
            throw new \Exception('Checksheet not found');
        }
        $areaList = DB::table('tm_checkarea')
            ->select('tm_checkarea.deskripsi','tm_checkarea.min','tm_checkarea.max','tm_checkarea.tipe','tm_checkarea.id as id',DB::raw('COALESCE(tm_checkarea.nama,tm_checksheet.nama) as nama'))
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            ->where('tm_checkarea.id_checksheet', $id)
            //search in all columns
            ->where(function ($query) use ($request) {
                if (($term = $request->get('search'))) {
                    $query->orWhere('tm_checkarea.nama', 'LIKE', '%' . $term . '%')
                        ->orWhere('tm_checkarea.deskripsi', 'LIKE', '%' . $term . '%');
                }
            })
            ->orderBy('tm_checkarea.nama','ASC')
            ->paginate(100)->appends(request()->query())->toArray();

            foreach($areaList['data'] as $key=>$area){
                $area->checkdata = DB::table('tt_checkdata')
                ->select('*')
                ->where('tt_checkdata.id_checkarea', $area->id)
                ->where('nama',$request->query('shell'))
                ->where('shift',$request->query('shift'))
                //where day today
                ->whereDate('tanggal', $request->query('tanggal') ?? date('Y-m-d'))
                ->first();
                if($area->checkdata){
                    $area->checkdata->is_good = $area->checkdata->value >= $area->min && $area->checkdata->value <= $area->max;
                }
            }

            return view('checksheet.area', compact('areaList','checksheet'));
        }
        catch(\Exception $e){
            return redirect()->route('checksheet.list')->with('error', $e->getMessage());
        }

        //return view checklist with checklist

    }
}

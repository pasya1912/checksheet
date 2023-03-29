<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckareaController extends Controller
{
    public function list($id,Request $request)
    {
        try{

        //get checksheet from db based on search parameter if exist with like paginate every 10

        $checksheet = DB::table('tm_checksheet')
            ->where('id', $id)
            ->first();
        if(!$checksheet){
            throw new \Exception('Checksheet not found');
        }
        $areaList = DB::table('tm_checkarea')
            ->select('tm_checkarea.nama','tm_checkarea.id','tm_checkarea.deskripsi',DB::raw('COALESCE(tm_checkarea.nama,tm_checksheet.nama) as nama'))
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
            ->paginate(20)->appends(request()->query())->toArray();
            return view('checksheet.area', compact('areaList','checksheet'));
        }
        catch(\Exception $e){
            return redirect()->route('checksheet.list')->with('error', $e->getMessage());
        }

        //return view checklist with checklist

    }
}

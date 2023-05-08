<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkdata;

class CheckdataController extends Controller
{
    public function list(Request $request,\App\Service\Admin\ListCheckData $listCheckData, \App\Service\ChecksheetData $checksheetData)
    {

        $checkdata = $listCheckData->get($request);
        $lineList = $checksheetData->getLine();
        $codeList = $checksheetData->getCode($request->get('line'));
        $checkList = $checksheetData->getChecksheet($request->get('line'),$request->get('code'));
        $areaList = $checksheetData->getArea($request->get('line'),$request->get('code'),$request->get('checksheet'));
        //dd($checkdata);
        return view('checksheet.checkdata.data',compact('checkdata','lineList','codeList','checkList','areaList'));
    }
    public function list_new(Request $request,\App\Service\Admin\ListCheckData $listCheckData, \App\Service\ChecksheetData $checksheetData)
    {

        $checkdata = $listCheckData->get($request);
        $lineList = $checksheetData->getLine();
        $codeList = $checksheetData->getCode($request->get('line'));
        $checkList = $checksheetData->getChecksheet($request->get('line'),$request->get('code'));
        $areaList = $checksheetData->getArea($request->get('line'),$request->get('code'),$request->get('checksheet'));
        //dd($checkdata);
        return view('checksheet.checkdata.data-new',compact('checkdata','lineList','codeList','checkList','areaList'));
    }
    public function updateStatus($id, Request $request)
    {
        //validate request->status only allow approved rejected or wait
        $request->validate([
            'status' => 'required|in:approved,rejected,wait'
        ]);

        try{
        $checkdata = Checkdata::find($id);
        $checkdata->approval = $request->status;
        $checkdata->save();
        }catch(\Exception $e){
            return response()->json(['status'=>'gagal','message' => $e->getMessage()], 500);
        }

        return response()->json(['status'=>'success','message' => 'Status berhasil diubah'], 200);
    }



}

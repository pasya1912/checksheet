<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkdata;

class CheckdataController extends Controller
{
    public function updateStatus($id, Request $request)
    {
        //validate request->status only allow approved rejected or wait
        $request->validate([
            'status' => 'required|in:1,2,3,4'
        ]);
        $user_jabatan = auth()->user()->jabatan;
        if($user_jabatan == '1'){
            $approval = '1';
        }
        elseif($user_jabatan == '2')
        {
            $approval = '2';

        }
        elseif($user_jabatan == '3')
        {
            $approval = '3';

        }
        elseif($user_jabatan == '4')
        {
            $approval = '4';

        }
        try{
        $checkdata = Checkdata::find($id);
        $checkdata->approval = $approval;
        $checkdata->save();
        }catch(\Exception $e){
            return response()->json(['status'=>'gagal','message' => $e->getMessage()], 500);
        }

        return response()->json(['status'=>'success','message' => 'Status berhasil diubah'], 200);
    }



}

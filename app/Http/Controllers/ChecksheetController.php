<?php

namespace App\Http\Controllers;

//use db
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ChecksheetController extends Controller
{
    public function getCode(Request $request, \App\Service\ChecksheetData $checksheetData)
    {

        $codeList = $checksheetData->getCode($request->line);
        //return json with status success if not empty
        if (count($codeList)) {
            return response()->json([
                'status' => 'success',
                'data' => $codeList
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => []
            ], 404);
        }
    }
    public function list(Request $request, \App\Service\ChecksheetData $checksheet)
    {
        $line = !$request->get('line') ? '' : $request->get('line');
        $code = !$request->get('code') ? '' : $request->get('code');
        $cell = !$request->get('cell') ? '' : $request->get('cell');
        $shift = !$request->get('shift') ? '' : $request->get('shift');
        $barang = !$request->get('barang') ? '' : $request->get('barang');
        $query = new \stdClass();
        $query->cell = $cell;
        $query->shift = $shift;
        $query->barang = $barang;
        $query->line = $line;
        $query->code = $code;
        if ($line == '' && $code == '') {
            $checkList = 500;
        } else if ($line != '' && $code == '') {
            $checkList = 400;
        }
         else {
            //get checksheet from db based on search parameter if exist with like paginate every 10
            $checkList = DB::table('tm_checksheet')
                ->select('tm_checksheet.*')


                ->where(function ($query) use ($line, $code) {

                    $query->where('line', 'LIKE', '%' . $line . '%')
                        ->where('code', 'LIKE', '%' . $code . '%');

                })

                ->paginate(20)->appends(request()->query())->toArray();
            foreach ($checkList['data'] as $key => $value) {
                $checkList['data'][$key]->all = DB::table('tm_checkarea')
                    ->select('id')->where('tm_checkarea.id_checksheet', $value->id)->count();

                $checkList['data'][$key]->jml_revised = DB::table('tt_checkdata')
                    ->select('tm_checkarea.id', 'tt_checkdata.tipe', 'tt_checkdata.value')
                    ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                    ->where('tm_checksheet.id', $value->id)
                    ->where('tanggal', '>=', startOfDay())
                    ->where('tanggal', '<', endOfDay())
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.barang', $barang)
                    ->where('tt_checkdata.shift', $shift)
                    ->whereNotNull('tt_checkdata.revised_value')
                    ->where('tt_checkdata.mark', '1')->count();

                $checkList['data'][$key]->notgood = DB::table('tt_checkdata')

                    ->select('tm_checkarea.id', 'tt_checkdata.tipe', 'tt_checkdata.value')
                    ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                    ->where('tm_checksheet.id', $value->id)
                    ->where('tanggal', '>=', startOfDay())
                    ->where('tanggal', '<', endOfDay())
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.barang', $barang)
                    ->where('tt_checkdata.shift', $shift)
                    ->whereRaw('
                (CASE
                    WHEN tt_checkdata.tipe = "1" THEN tt_checkdata.value = "ng"
                    WHEN tt_checkdata.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) < IFNULL(CAST(tt_checkdata.min AS DECIMAL(10,4)), CAST("-999999" AS DECIMAL(10,4)))) OR (CAST(tt_checkdata.value AS DECIMAL(10,4)) > IFNULL(CAST(tt_checkdata.max AS DECIMAL(10,4)), CAST("999999" AS DECIMAL(10,4))))
                    WHEN tt_checkdata.tipe = "3" THEN 1 = 2
                    END
                    )')
                    ->count();
                $checkList['data'][$key]->good = DB::table('tm_checkarea')
                    ->select('tm_checkarea.id', 'tt_checkdata.tipe', 'tt_checkdata.value')->where('tm_checkarea.id_checksheet', $value->id)
                    ->leftJoin('tt_checkdata', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->where('tt_checkdata.value', DB::raw('tt_checkdata.value'))
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.shift', $shift)
                    ->where('tt_checkdata.barang', $barang)
                    ->where('tt_checkdata.tanggal', '>=', startOfDay())
                    ->where('tt_checkdata.tanggal', '<', endOfDay())
                    ->whereRaw('
                (CASE
                    WHEN tt_checkdata.tipe = "1" THEN tt_checkdata.value = "ok"
                    WHEN tt_checkdata.tipe = "2" THEN (CAST(tt_checkdata.value AS DECIMAL(10,4)) >= IFNULL(CAST(tt_checkdata.min AS DECIMAL(10,4)), CAST("-999999" AS DECIMAL(10,4)))) AND (CAST(tt_checkdata.value AS DECIMAL(10,4)) <= IFNULL(CAST(tt_checkdata.max AS DECIMAL(10,4)), CAST("999999" AS DECIMAL(10,4))))
                    WHEN tt_checkdata.tipe = "3" THEN tt_checkdata.value = tt_checkdata.value
                    END
                    )')
                    ->count();
                $checkList['data'][$key]->approval = DB::table('tt_checkdata')
                    ->select('tt_checkdata.*')
                    ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
                    ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                    ->where('id_checksheet', $value->id)
                    ->where('tt_checkdata.nama', $cell)
                    ->where('tt_checkdata.shift', $shift)
                    ->where('tt_checkdata.barang', $barang)
                    ->where('tt_checkdata.tanggal', '>=', startOfDay())
                    ->where('tt_checkdata.tanggal', '<', endOfDay())
                    ->where('tt_checkdata.approval', '4')
                    ->count();
                $checkList['data'][$key]->status =
                    ($checkList['data'][$key]->all == $checkList['data'][$key]->good || $checkList['data'][$key]->all == $checkList['data'][$key]->good + $checkList['data'][$key]->jml_revised ? 'DONE-OK' : ($checkList['data'][$key]->notgood && $checkList['data'][$key]->good + $checkList['data'][$key]->notgood == $checkList['data'][$key]->all ? 'DONE-NG' : ($checkList['data'][$key]->notgood > 0 && $checkList['data'][$key]->jml_revised - $checkList['data'][$key]->notgood != 0 ? 'PROGRESS-NG' : ((($checkList['data'][$key]->notgood == 0 || $checkList['data'][$key]->jml_revised - $checkList['data'][$key]->notgood == 0) && ($checkList['data'][$key]->good > 0 || $checkList['data'][$key]->jml_revised > 0 )) ? 'PROGRESS-OK' : 'NOT-STARTED'))));


            }

        }

        if ($cell == '' || $shift == '' || $barang == '' || $line == '' || $code == '') {
            $checkList = 300;
        }
        if(session('jp') == null){

            $checkList = 700;

        }

        $user = User::where('npk', session('jp'))->where('role', 'admin')->where('jabatan', '1')->first();
        if(!$user)
        {
            $checkList = 705;

        }


        $lineList = $checksheet->getLine();
        $codeList = $checksheet->getCode($request->get('line'));
        $jpies = User::where('role','admin')->where('jabatan', '1')->get();
        //return view checklist with checklist
        return view('checksheet.list', compact('checkList', 'lineList', 'codeList', 'query','jpies'));
    }
    public function setJP(Request $request)
    {
       $request->validate([
        'jp' => 'required|numeric|exists:users,npk',
       ]);
       $user = User::where('npk',$request->jp)->first();
        //if user has role admin and jabatan 1 then set jp in session
         if ($user->role == 'admin' && $user->jabatan == '1') {
            session(['jp' => $request->jp]);
            return redirect()->back()->with('success', 'JP berhasil di set');
        }else{
            return redirect()->back()->with('error', 'JP gagal di set');
        }
    }
}

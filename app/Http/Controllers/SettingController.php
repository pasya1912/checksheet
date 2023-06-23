<?php

namespace App\Http\Controllers;

use App\Service\ChecksheetData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index(Request $request, ChecksheetData $checksheet)
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
        } else {


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
                }
        }
        $lineList = $checksheet->getLine();
        $codeList = $checksheet->getCode($request->get('line'));
        //return view checklist with checklist
        return view('checksheet.setting.list', compact('checkList', 'lineList', 'codeList', 'query'));
    }
    public function area($id, Request $request)
    {
        try {


            //get checksheet from db based on search parameter if exist with like paginate every 10

            $checksheet = DB::table('tm_checksheet')
                ->where('id', $id)
                ->first();
            if (!$checksheet) {
                throw new \Exception('Checksheet not found');
            }
            $areaList = DB::table('tm_checkarea')
                ->select('tm_checkarea.deskripsi', 'tm_checkarea.min', 'tm_checkarea.max', 'tm_checkarea.tipe', 'tm_checkarea.id as id','tm_checkarea.tengah', DB::raw('COALESCE(tm_checkarea.nama,tm_checksheet.nama) as nama'))
                ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
                ->where('tm_checkarea.id_checksheet', $id)
                //search in all columns
                ->where(function ($query) use ($request) {
                    if (($term = $request->get('search'))) {
                        $query->orWhere('tm_checkarea.nama', 'LIKE', '%' . $term . '%')
                            ->orWhere('tm_checkarea.deskripsi', 'LIKE', '%' . $term . '%');
                    }
                })
                ->orderBy('tm_checkarea.id', 'ASC')
                ->orderBy('tm_checkarea.nama', 'ASC')
                ->paginate(100)->appends(request()->query())->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('checksheet.setting.area', compact('areaList', 'checksheet'));
    }
    public function areaEdit($idchecksheet,$idcheckarea, Request $request)
    {
        try{
            $checksheet = DB::table('tm_checksheet')
            ->where('id', $idchecksheet)
            ->first();
        if (!$checksheet) {
            throw new \Exception('Checksheet not found');
        }
        $area = DB::table('tm_checkarea')
        ->where('id', $idcheckarea)
        ->first();

        return view('checksheet.setting.areaEdit', compact('checksheet','area'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function areaEditAction($idchecksheet,$idcheckarea, Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tipe' => 'required',
            'min' => 'required_if:tipe,2',
            'max' => 'required_if:tipe,2',
            'tengah' => 'required_if:tipe,2',
        ]);
        try{
            //update data
            $update = DB::table('tm_checkarea')
            ->where('id', $idcheckarea)
            ->update([
                'nama' => $request->get('nama'),
                'deskripsi' => $request->get('deskripsi'),
                'tipe' => $request->get('tipe'),
                'min' => $request->get('min'),
                'max' => $request->get('max'),
                'tengah' => $request->get('tengah'),
            ]);

            if(!$update){
                throw new \Exception('Tidak ada data yang diperbaharui');
            }
            return redirect()->route('checksheet.setting.area',[$idchecksheet])->with('success','Data updated successfully');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
}

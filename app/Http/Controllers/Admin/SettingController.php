<?php

namespace App\Http\Controllers\Admin;

use App\Models\UpdateCheckarea;
use App\Service\ChecksheetData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
                ->select('tm_checkarea.deskripsi', 'tm_checkarea.min', 'tm_checkarea.max', 'tm_checkarea.tipe', 'tm_checkarea.id as id', 'tm_checkarea.tengah', DB::raw('COALESCE(tm_checkarea.nama,tm_checksheet.nama) as nama'))
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
    public function areaEdit($idchecksheet, $idcheckarea, Request $request)
    {
        try {
            $checksheet = DB::table('tm_checksheet')
                ->where('id', $idchecksheet)
                ->first();
            if (!$checksheet) {
                throw new \Exception('Checksheet not found');
            }
            $area = DB::table('tm_checkarea')
                ->where('id', $idcheckarea)
                ->first();

            return view('checksheet.setting.areaEdit', compact('checksheet', 'area'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function areaEditAction($idchecksheet, $idcheckarea, Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tipe' => 'required',
            'min' => 'required_if:tipe,2',
            'max' => 'required_if:tipe,2',
            'tengah' => 'required_if:tipe,2',
        ]);
        $checkarea = DB::table('tm_checkarea')->where('id', $idcheckarea)->first();
        //if old data and new data same dont update
        if ($checkarea->nama == $request->get('nama') && $checkarea->deskripsi == $request->get('deskripsi') && $checkarea->tipe == $request->get('tipe') && $checkarea->min == $request->get('min') && $checkarea->max == $request->get('max') && $checkarea->tengah == $request->get('tengah')) {
            return redirect()->back()->with('success', 'Data berhasil diupdate');
        }
        try {
            $arr = [
                'id_checkarea' => $idcheckarea,
                'user' => auth()->user()->npk,
                'old_nama' => $checkarea->nama,
                'old_deskripsi' => $checkarea->deskripsi,
                'old_tipe' => $checkarea->tipe,
                'old_min' => $checkarea->min,
                'old_max' => $checkarea->max,
                'old_tengah' => $checkarea->tengah,
                'new_nama' => $request->get('nama'),
                'new_deskripsi' => $request->get('deskripsi'),
                'new_tipe' => $request->get('tipe'),
                'new_min' => $request->get('min'),
                'new_max' => $request->get('max'),
                'new_tengah' => $request->get('tengah'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if (auth()->user()->jabatan >= 3) {
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
                $arr['status'] = 'approved';
                $arr['approver'] = auth()->user()->npk;
            }
                //insert into update_checkarea
                $update = DB::table('update_checkarea')
                    ->insert($arr);



            if (!$update) {
                throw new \Exception('Tidak ada data yang diperbaharui');
            }
            //return to setting area
            $redirect = redirect()->route('checksheet.setting.area', ['id' => $idchecksheet]);
            if (auth()->user()->jabatan >= 3) {
                return $redirect->with('success', 'Data berhasil diperbaharui');
            } else {
                return $redirect->with('success', 'Perubahan Data berhasil diajukan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    public function approvalList(Request $request, ChecksheetData $checksheetData)
    {
        $line = !$request->get('line') ? null : $request->get('line');
        $code = !$request->get('code') ? null : $request->get('code');
        //get UpdateCheckarea paginate 10 using UpdateCheckarea model
        $list = DB::table('update_checkarea')->select('tm_checksheet.line', 'tm_checksheet.code', 'tm_checksheet.nama as nama_checksheet', 'tm_checkarea.nama as nama_checkarea', 'update_checkarea.*')
            ->leftJoin('tm_checkarea', 'update_checkarea.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id');
        if ($line)
            $list->where('tm_checksheet.line', '=', '%' . $line . '%');
        if ($code)
            $list->where('tm_checksheet.code', '=', '%' . $code . '%');

        $list = $list->orderBy('id', 'DESC')->orderBy('id')->paginate(15);
        $lineList = $checksheetData->getLine();
        $codeList = $checksheetData->getCode($request->get('line'));
        $list = $list->appends(request()->query())->toArray();
        foreach($list['data'] as $key => $value){
            $list['data'][$key]->nama_user = DB::table('users')->where('npk', $value->user)->first()->name;
            $list['data'][$key]->nama_approver = DB::table('users')->where('npk', $value->approver)->first()?->name;

        }

        return view('checksheet.setting.approvalList', compact('list', 'lineList', 'codeList'));


    }
    public function approvalDetail($id, Request $request)
    {
        $data = DB::table('update_checkarea')->select('tm_checksheet.line', 'tm_checksheet.code', 'tm_checksheet.nama as nama_checksheet', 'tm_checkarea.nama as nama_checkarea', 'update_checkarea.*')
            ->leftJoin('tm_checkarea', 'update_checkarea.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            ->where('update_checkarea.id', $id)->first();

        return view('checksheet.setting.approval.detail', compact('data'));


    }
    public function approvalAction($id, Request $request)
    {
        $request->validate(
            [
                'status' => 'required|in:0,1'
            ]
        );
        $status = (int) $request->status;
        try{
        DB::beginTransaction();
        $dataUpdate = UpdateCheckarea::find($id);
        if (!$dataUpdate)
            return redirect()->route('checksheet.setting.approval')->with('error', 'Data tidak ditemukan');
        if ($dataUpdate->status != 'pending')
            return redirect()->route('checksheet.setting.approval')->with('error', 'Data tidak dapat diupdate');
        if ($status) {
            $dataUpdate->status = 'approved';
        } else {
            $dataUpdate->status = 'rejected';
        }
        $dataUpdate->approver = auth()->user()->npk;
        if ($dataUpdate->save()) {
            //update tm_checkarea
            $update = DB::table('tm_checkarea')
                ->where('id', $dataUpdate->id_checkarea)
                ->update([
                    'nama' => $dataUpdate->new_nama,
                    'deskripsi' => $dataUpdate->new_deskripsi,
                    'tipe' => $dataUpdate->new_tipe,
                    'min' => $dataUpdate->new_min,
                    'max' => $dataUpdate->new_max,
                    'tengah' => $dataUpdate->new_tengah,
                ]);
            if ($update) {
                DB::commit();
                return redirect()->route('checksheet.setting.approval')->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->route('checksheet.setting.approval')->with('error', 'Data gagal diupdate');
            }
        }
        return redirect()->route('checksheet.setting.approval')->with('error', 'Data gagal diupdate');
        }
        catch(\Exception $e){
            DB::rollback();
            return redirect()->route('checksheet.setting.approval')->with('error', $e->getMessage());
        }
    }
}

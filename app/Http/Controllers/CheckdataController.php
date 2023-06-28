<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Checkdata;
use Illuminate\Validation\ValidationException;

use App\Rules\ValidateLine;
use App\Rules\ValidateCode;
use App\Rules\ValidateBarang;
use App\Rules\ValidateCell;
use App\Rules\ValidateShift;
use App\Rules\ValidateValue;
use App\Rules\ValidateRevised;

use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;


class CheckdataController extends Controller
{

    public function list(Request $request,\App\Service\Admin\ListCheckData $listCheckData, \App\Service\ChecksheetData $checksheetData)
    {

        $checkdata = $listCheckData->get($request);
        $lineList = $checksheetData->getLine();
        $codeList = $checksheetData->getCode($request->get('line'));
        $checkList = $checksheetData->getChecksheet($request->get('line'),$request->get('code'));
        $areaList = $checksheetData->getArea($request->get('line'),$request->get('code'),$request->get('checksheet'));


        return view('checksheet.checkdata.data',compact('checkdata','lineList','codeList','checkList','areaList'));
    }
    public function store($idchecksheet, $idcheckarea, Request $request, \App\Service\StoreCheckData $storeCheckData)
    {
        try {
            $request->validate([
                'value' => 'required',
                new ValidateValue($idcheckarea),
                'barang' => ['required', new ValidateBarang],
                'code' => ['required', new ValidateCode($request->line)],
                'line' => ['required', new ValidateLine],
                'cell' => ['required', new ValidateCell],
                'shift' => ['required', new ValidateShift],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
        }



        //check if data already exist
        $check = DB::table('tt_checkdata')
            ->leftJoin('tm_checkarea', 'tt_checkdata.id_checkarea', '=', 'tm_checkarea.id')
            ->leftJoin('tm_checksheet', 'tm_checkarea.id_checksheet', '=', 'tm_checksheet.id')
            ->where('tm_checksheet.id', $idchecksheet)
            ->where('tm_checkarea.id', $idcheckarea)
            ->where('tm_checksheet.code', $request->code)
            ->where('tm_checksheet.line', $request->line)
            ->where('tt_checkdata.shift', $request->shift)
            ->where('tt_checkdata.nama', $request->cell)
            ->where('tt_checkdata.tanggal', '>=', startOfDay())
            ->where('tt_checkdata.tanggal', '<', endOfDay())
            ->where('tt_checkdata.barang', $request->barang);
        $check = $check->count();

        //if data already exist return status error

        if ($check > 0) {
            return response()->json(['status' => 'error', 'message' => 'Data sudah ada'], 422);
        }


        $db = DB::table('tt_checkdata')->insert(
            [
                'id_checkarea' => $idcheckarea,
                'nama' => $request->cell,
                'barang' => $request->barang,
                'tanggal' => date('Y-m-d H:i:s'),
                'user' => $request->user()->npk,
                'value' => $request->value,
                'approval' => '0',
                'mark' => '0',
                'shift' => $request->shift,
                'jp' => session('jp')
            ]
        );


        //get tipe of $db


        //if success return status success
        if ($db) {

            $tipe = DB::table('tm_checkarea')
            ->where('id', $idcheckarea)
            ->first();
            $isStandar = $this->cekStandar($tipe, $request->value);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'is_good' => $isStandar], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data gagal disimpan'], 500);
        }
    }
    public function cekStandar($checkData, $value)
    {
        switch ($checkData->tipe) {
            case 1:
                if ($value == "ok") {
                    return true;
                } else {
                    return false;
                }
                break;
            case 2:
                if ($value >= $checkData->min && $value <= $checkData->max) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 3:
                return true;
                break;
            default:
                return false;
                break;
        }
    }
    public function updateNotes($idchecksheet, $idcheckarea, Request $request)
    {

        $request->validate([
            'id_checkdata' => 'required|numeric',
            'notes' => 'required',
            'marked' => 'sometimes|numeric',
            'revised_value' => [ 'sometimes',new  ValidateRevised($idcheckarea,$request->marked ?? "")]
        ]);
        $request->notes = htmlspecialchars($request->notes);

        $find = Checkdata::where('id', $request->id_checkdata);
        if (auth()->user()->role != 'admin') {
            $find->where('user', auth()->user()->npk);

        }
        $find = $find->first();
        $notes = $request->notes . "<br>- " . auth()->user()->name . " (" . date('Y-m-d H:i:s') . ")";

        if ($find && $find->mark != 1) {
            $find->notes = $notes;
            if($request->marked == "1"){
                if($request->revised_value != null && $request->revised_value != ""){
                    $find->revised_value = $request->revised_value;
                }
                $find->mark = "1";

            }
            $find->save();
            return redirect()->back()->with('success', 'Revisi/Notes berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Revisi/Notes gagal ditambahkan');
        }
    }

    public function get($idchecksheet, $idcheckarea, Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric'
            ]);

            $find = Checkdata::where('id', $request->id)->first();

            if ($find) {
                return response()->json(['status' => 'success', 'message' => 'Data berhasil ditemukan', 'data' => $find], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Data gagal ditemukan'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditemukan'], 500);
        }
    }
    public function export(Request $request,\App\Service\Admin\ListCheckData $listCheckData)
    {
            $checkdata = $listCheckData->getData($request)->get();


            foreach($checkdata as $key => $value){


                $approvalHistory = Checkdata::find($value->id)->approvalHistory;

                $checkdata[$key]->jp = false;
                $checkdata[$key]->leader = false;
                $checkdata[$key]->spv = false;
                $checkdata[$key]->manager = false;
                foreach($approvalHistory as $approval){

                    if($approval->approval == 1){

                        $checkdata[$key]->jp = $approval->owner->name;
                    }else if($approval->approval == 2){
                        $checkdata[$key]->leader = $approval->owner->name;
                    }else if($approval->approval == 3){
                        $checkdata[$key]->spv = $approval->owner->name;

                    }
                    else if($approval->approval == 4){
                        $checkdata[$key]->manager = $approval->owner->name;
                    }

                }

            }


            //map the data collection to array only id and nama
            $checkdata = $checkdata->map(function ($item, $key) {
                $status = "";
                $replaceNotes = $item->notes ? preg_replace("/<br>(.*?)\)/","",$item->notes) : null;
                if($item->status == "notgood" && $item->revised_status == 'good'){
                    $status = "OK (Revised)";
                }
                if($item->status == "good" && $item->revised_value == null){
                    $status =  "OK";
                }else if($item->status == "notgood" && $item->revised_value == null){
                    $status ="NG (Belum Drevisi)";
                }

                return [
                    'jenis'=> $item->jenis,
                    'line' => $item->line,
                    'model' => $item->code,
                    'nama_checksheet' => $item->nama_checksheet,
                    'nama_checkarea' => $item->nama_checkarea,
                    'cell' => $item->nama,
                    'urutan' => $item->barang,
                    'shift' => $item->shift,
                    'tanggal'=> date('d-m-Y',strtotime($item->tanggal)),
                    'jam'=> date('H:i:s',strtotime($item->tanggal)),
                    'min' => $item->min,
                    'max' => $item->max,
                    'value' => $item->value,
                    'status' => $status,
                    'revised_value' => $item->revised_value,
                    'notes' => $replaceNotes,
                    'checker' => $item->name,
                    'jp_approve' => $item->jp,
                    'leader_approve' => $item->leader,
                    'spv_approve' => $item->spv,
                    'manager_approve' => $item->manager

                ];
            });
            //return Checkdata::find(27)->approvalHistory;

            return Excel::download(new DataExport($checkdata), 'request'.date('Y-m-d-h-i-s').'.xlsx');
    }
}

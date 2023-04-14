<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Rules\ValidateLine;
use App\Rules\ValidateCode;
use App\Rules\ValidateBarang;
use App\Rules\ValidateShell;
use App\Rules\ValidateShift;
use App\Rules\ValidateValue;


class CheckdataController extends Controller
{

    public function store($idchecksheet, $idcheckarea, Request $request, \App\Service\StoreCheckData $storeCheckData)
    {
        try {
            $request->validate([
                'value' => 'required', new ValidateValue($idcheckarea),
                'barang' => ['required', new ValidateBarang],
                'code' => ['required', new ValidateCode($request->line)],
                'line' => ['required', new ValidateLine],
                'shell' => ['required', new ValidateShell],
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
            ->where('tt_checkdata.nama', $request->shell)
            ->whereDate('tt_checkdata.tanggal', date('Y-m-d'))
            ->where('tt_checkdata.barang', $request->barang);



        $check = $check->count();

        //if data already exist return status error

        if ($check > 0) {
            return response()->json(['status' => 'error', 'message' => 'Data sudah ada'], 422);
        }

        $db = DB::table('tt_checkdata')->insert(
            [
                'id_checkarea' => $idcheckarea,
                'nama' => $request->shell,
                'barang' => $request->barang,
                'tanggal' => date('Y-m-d H:i:s'),
                'user' => $request->user()->npk,
                'value' => $request->value,
                'approval' => 'wait',
                'mark' => '0',
                'shift' => $request->shift
            ]
        );
        //get tipe of $db
        $tipe = DB::table('tm_checkarea')
            ->where('id', $idcheckarea)
            ->first();

        //if success return status success
        if ($db) {
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
                if ($value == "good") {
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
}

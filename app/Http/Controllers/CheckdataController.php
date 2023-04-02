<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CheckdataController extends Controller
{

    public function store($idchecksheet, $idcheckarea, Request $request, \App\Service\StoreCheckData $storeCheckData)
    {

        dd($request);
        dd();

        try {
            $checkarea = $this->getCheckArea($idchecksheet, $idcheckarea, '*');
            if (!$checkarea) {
                throw new \Exception('Checksheet not found');
            }



            return $storeCheckData->store($idchecksheet, $idcheckarea, $request, $checkarea->tipe);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function getCheckArea($idchecksheet,$idcheckarea,...$select)
    {
        return DB::table('tm_checkarea')
        ->select($select)
        ->where('tm_checkarea.id_checksheet', $idchecksheet)
        ->where('tm_checkarea.id', $idcheckarea)
        ->first();
    }
    public function list($idchecksheet,$idcheckarea,Request $request,\App\Service\ListCheckData $listCheckData)
    {
        try {
            $checkarea = $this->getCheckArea($idchecksheet, $idcheckarea, '*', DB::raw('COALESCE(tm_checkarea.nama,"Area") as nama'));
            if (!$checkarea) {
                throw new \Exception('Checksheet not found');
            }

            return $listCheckData->getList($checkarea,$request);
        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

    }
}

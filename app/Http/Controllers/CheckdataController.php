<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CheckdataController extends Controller
{
    public function form($idchecksheet, $idcheckarea, Request $request, \App\Service\FormCheckData $formCheckData)
    {
        //get type checkarea
        try {
            $type = DB::table('tm_checkarea')
                ->select('tm_checkarea.tipe')
                ->where('tm_checkarea.id_checksheet', $idchecksheet)
                ->where('tm_checkarea.id', $idcheckarea)
                ->first();
            if(!$type)
            {
                throw new \Exception('Checksheet not found');
            }

            return $formCheckData->getForm($type->tipe);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Checksheet not found');
        }
    }
}

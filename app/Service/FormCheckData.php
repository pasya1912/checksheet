<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;
class FormCheckData
{
    public function getData($id)
    {
        return DB::table('tm_checksheet')
            ->select('line','code','nama as nama_checksheet')
            ->where('id', $id)
            ->first();
    }
    public function getForm($checkarea)
    {
        $checksheet = $this->getData($checkarea->id_checksheet);
        //merge
        $data = (object) array_merge((array) $checksheet, (array) $checkarea);

        switch($checkarea->tipe){
            case 1:
                return view('checksheet.form.satu',compact('data'));
                break;
            case 2:
                return view('checksheet.form.dua',compact('data'));
                break;
            case 3:
                return view('checksheet.form.tiga',compact('data'));
                break;
            default:
                throw new \Exception('Checksheet error');
                break;

        }
    }
}

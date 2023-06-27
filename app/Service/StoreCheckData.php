<?php

namespace App\Service;

//use db
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class StoreCheckData
{
    public function store($idchecksheet, $idcheckarea, $request,$type)
    {

        $request = $this->validate($request,$type);

        $data = [
            'id_checkarea' => (int)$idcheckarea,
            'nama' => $request->nama,
            'barang' => $request->barang,
            'tanggal' => date('Y-m-d H:i:s'),
            'user' => Auth::user()->name,
            'value' => $request->value,
        ];

        //insert
        if(DB::table('tt_checkdata')->insert($data)){
            return redirect()->back()->with('success', 'Checksheet success');
        }
        else{
            throw new \Exception('Checksheet error');
        }


    }

    public function validate($request,$type)
    {
        $array = [];
        $array['nama'] = 'required';
        $array['barang'] = 'required|in:first,middle,last';

        switch($type){
            case 1:
                $array['value'] = 'required|in:ok,ng';
                break;
            case 2:
                $request->value = $request->value;
                $array['value'] = 'required';
                break;
            case 3:
                $array['value'] = 'required';
                break;
            default:
                throw new \Exception('Checksheet error');
                break;
        }
        $request->validate($array);
        return $request;

    }
}

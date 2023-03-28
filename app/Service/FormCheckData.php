<?php

namespace App\Service;


class FormCheckData
{
    public function getForm($type)
    {
        switch($type){
            case 1:
                return "Tipe 1";
                break;
            case 2:
                return view('checksheet.form.dua');
                break;
            case 3:
                return "Tipe 3";
                break;
            default:
                throw new \Exception('Checksheet error');
                break;

        }
    }
}

<?php

if(!function_exists('startOfDay')){
    function startOfDay($tanggal = null)
    {
        if($tanggal != null){
            return date('Y-m-d 06:00:00', strtotime($tanggal));
        }else{
            return date('Y-m-d 06:00:00', strtotime('today'));
        }
    }
}

if(!function_exists('endOfDay')){
    function endOfDay($tanggal = null)
    {
        //if tanggal empty then tomorrow 6am if not empty then tanggal 6 am
        if($tanggal != null){
            return date('Y-m-d 06:00:00', strtotime($tanggal . ' +1 day'));
        }else{
            return date('Y-m-d 06:00:00', strtotime('tomorrow'));
        }
    }

}

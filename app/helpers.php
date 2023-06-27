<?php

if(!function_exists('startOfDay')){
    function startOfDay($tanggal = null)
    {
        if($tanggal != null){

            //if today less than 6am then return yesterday 6am else return today 6am
            if(date('H') < 6){
                return date('Y-m-d 06:00:00', strtotime($tanggal . ' -1 day'));
            }
            return date('Y-m-d 06:00:00', strtotime($tanggal));
        }else{
            //get today, if today less than 6am then return yesterday 6am else return today 6am
            if(date('H') < 6){
                return date('Y-m-d 06:00:00', strtotime('yesterday'));
            }

            return date('Y-m-d 06:00:00', strtotime('today'));
        }
    }
}

if(!function_exists('endOfDay')){
    function endOfDay($tanggal = null)
    {


        if($tanggal != null){

            //if today less than 6am then return yesterday 6am else return today 6am
            if(date('H') < 6){
                return date('Y-m-d 06:00:00', strtotime($tanggal));
            }
            return date('Y-m-d 06:00:00', strtotime($tanggal . ' +1 day'));
        }else{
            //get today, if today less than 6am then return yesterday 6am else return today 6am
            if(date('H') < 6){
                return date('Y-m-d 06:00:00', strtotime('today'));
            }
            return date('Y-m-d 06:00:00', strtotime('tomorrow'));
        }
    }

}

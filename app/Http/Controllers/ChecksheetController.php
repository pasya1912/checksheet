<?php

namespace App\Http\Controllers;
//use db
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ChecksheetController extends Controller
{
    public function list(Request $request)
    {
        $line = !$request->get('line') ? '': $request->get('line');
        $code = !$request->get('code') ? '' : $request->get('code');
        if($line == '' && $code == ''){
            $checkList = 500;
        }
        else if($line != '' && $code == ''){
            $checkList = 400;
        }else{

        //get checksheet from db based on search parameter if exist with like paginate every 10
        $checkList = DB::table('tm_checksheet')
            //cek apakah terdapat tt_checkdata yang tidak sesuai min max
            ->select('tm_checksheet.*')

            ->where(function ($query) use ($request,$line,$code) {

                    $query->where('line', 'LIKE', '%' . $line . '%')
                        ->where('code', 'LIKE', '%' . $code . '%');

            })
            ->paginate(20)->appends(request()->query())->toArray();
        }
        $lineList = $this->getLine();
        $codeList = $this->getCode($request->get('line'));






        //return view checklist with checklist
        return view('checksheet.list', compact('checkList','lineList','codeList'));
    }
    public function getLine()
    {
        $line = DB::table('tm_checksheet')->select('line')->distinct()->orderBy('line','ASC')->get();
        return $line;
    }
    public function getCode($line)
    {
        $line = DB::table('tm_checksheet')->select('code')->distinct()->where('line',$line)->orderBy('code','ASC')->get();
        return $line;
    }
}

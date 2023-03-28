<?php

namespace App\Http\Controllers;
//use db
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ChecksheetController extends Controller
{
    public function list(Request $request)
    {
        //get checksheet from db based on search parameter if exist with like paginate every 10
        $checkList = DB::table('tm_checksheet')
            //search in all columns
            ->where(function ($query) use ($request) {
                if (($term = $request->get('search'))) {
                    $query->orWhere('line', 'LIKE', '%' . $term . '%')
                        ->orWhere('code', 'LIKE', '%' . $term . '%')
                        ->orWhere('nama', 'LIKE', '%' . $term . '%')
                        ->orWhere('jenis', 'LIKE', '%' . $term . '%');
                }
            })
            ->paginate(20)->appends(request()->query())->toArray();



        //return view checklist with checklist
        return view('checksheet.list', compact('checkList'));
    }
}

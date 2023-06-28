<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\ValidateLine;
use App\Rules\ValidateCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Checkdata;
class CheckdataController extends Controller
{
    function merging($jabatan, $request): array
    {


        if ($jabatan == '1') {

            if ($request->tanggal != null || $request->tanggal != '') {
                $min_tanggal = $request->tanggal;
                $max_tanggal = $request->tanggal;
            } else {
                $min_tanggal = date("Y-m-d");
                $max_tanggal = date("Y-m-d");
            }

            $approval = '0';
            $timpa = [
                'area'=>'',
                'barang' => '',
                'cell' => ''
            ];
        } elseif ($jabatan == '2') //leader
        {

            if ($request->tanggal != null || $request->tanggal != '') {
                $min_tanggal = $request->tanggal;
                $max_tanggal = $request->tanggal;
            } else {
                $min_tanggal = date("Y-m-d", strtotime("-1 day"));
                $max_tanggal = date("Y-m-d");
            }

            $approval = '1';
            $timpa = [
                'area'=>'',
                'barang' => '',
                'cell' => ''
            ];
        } elseif ($jabatan == '3') //supervisor
        {

            if ($request->tanggal != null || $request->tanggal != '') {
                $date = $request->tanggal;
                $carbonDate = Carbon::parse($date);

                // Set the Carbon instance to the start of the week
                $carbonDate->startOfWeek();

                // Get the first day of the week
                $min_tanggal = $carbonDate->format('Y-m-d');

                // Set the Carbon instance to the end of the week
                $carbonDate->endOfWeek();

                // Get the last day of the week
                $max_tanggal = $carbonDate->format('Y-m-d');

            } else {

                $date = date("Y-m-d");
                $carbonDate = Carbon::parse($date);

                // Set the Carbon instance to the start of the week
                $carbonDate->startOfWeek();

                // Get the first day of the week
                $min_tanggal = $carbonDate->format('Y-m-d');

                // Set the Carbon instance to the end of the week
                $carbonDate->endOfWeek();

                // Get the last day of the week
                $max_tanggal = $carbonDate->format('Y-m-d');
            }


            $approval = '2';
            $timpa = [
                'area' => '',
                'checksheet' => '',
                'shift' => '',
                'barang' => '',
                'cell' => ''
            ];
        } elseif ($jabatan == '4') //manager

        {
            if ($request->tanggal != null || $request->tanggal != '') {
                //get first day of the month using carbon
                $date = $request->tanggal;
                $carbonDate = Carbon::parse($date);

                // Set the Carbon instance to the start of the month
                $carbonDate->startOfMonth();

                // Get the first day of the month
                $min_tanggal = $carbonDate->format('Y-m-d');

                // Set the Carbon instance to the end of the month
                $carbonDate->endOfMonth();

                // Get the last day of the month
                $max_tanggal = $carbonDate->format('Y-m-d');
            } else {
                //get first day of the month using carbon
                $date = date("Y-m-d");
                $carbonDate = Carbon::parse($date);

                // Set the Carbon instance to the start of the month
                $carbonDate->startOfMonth();

                // Get the first day of the month
                $min_tanggal = $carbonDate->format('Y-m-d');

                // Set the Carbon instance to the end of the month
                $carbonDate->endOfMonth();

                // Get the last day of the month
                $max_tanggal = $carbonDate->format('Y-m-d');
            }


            $approval = '3';
            $timpa = [
                'area' => '',
                'code' => '',
                'checksheet' => '',
                'shift' => '',
                'barang' => '',
                'cell' => ''
            ];


        } else {
            return [];
        }
        $merge = array_merge($timpa,[
            'filter' => 'need_check',
            'min_tanggal' => $min_tanggal ?? '',
            'max_tanggal' => $max_tanggal ?? '',
        ]);
        $mergeArray = [
            'approval' => $approval,
            'data' => $merge
        ];

        return $mergeArray;
    }
    public function approval_page(Request $request, \App\Service\Admin\ListCheckData $listCheckData, \App\Service\ChecksheetData $checksheetData)
    {
        $mergeArray = $this->merging(auth()->user()->jabatan, $request);
        if (empty($mergeArray)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        //validate request to have leader if jabatan is jp / 1
        $approval = $mergeArray['approval'];

        //merge with request
        $request->merge($mergeArray['data']);

        $lineList = $checksheetData->getLine();
        $codeList = $checksheetData->getCode($request->get('line'));
        $checkList = $checksheetData->getChecksheet($request->get('line'), $request->get('code'));
        $areaList = $checksheetData->getArea($request->get('line'), $request->get('code'), $request->get('checksheet'));

        $checkdata = $listCheckData->get($request, $approval);
        $good = 0;
        $revised = 0;

        foreach ($checkdata['data'] as $key => $value) {
            if ($value->status == "good" || $value->status == "general") {
                $good++;
            } elseif (($value->status == "notgood" || $value->status == "general") && $value->revised_status == "good") {
                $revised++;
            }
        }

        //get leader
        $leader = User::where('role','admin')->where('jabatan', '2')->get();


        //return approval blade
        return view('checksheet.checkdata.approval', compact('checkdata', 'lineList', 'codeList', 'checkList', 'areaList', 'good', 'revised','leader'));
    }
    public function approval(Request $request, \App\Service\Admin\ListCheckData $listCheckData)
    {
        if (auth()->user()->jabatan == '1') {
            $request->validate([
                'leader' => 'numeric|exists:users,npk'
            ]);
            $user = User::where('npk',$request->leader)->first();
            if ($user->jabatan != '2') {
                return redirect()->back()->with('error', 'JP TIDAK VALID');
            }
        }

        $user_jabatan = auth()->user()->jabatan;

        $mergeArray = $this->merging($user_jabatan, $request);
        if (empty($mergeArray)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }
        $approval = $mergeArray['approval'];
        //merge with request
        $request->merge($mergeArray['data']);

        $checkdata = $listCheckData->approve($request, $approval);
        if ($checkdata) {
            //array without csrf token from $request and ignore empty
            $arr = array_filter($request->except('_token','filter','tanggal'));
            $arr['filter'] = 'approved';
            return redirect()->route('checksheet.data', $arr)->with('success', 'Berhasil Approve');
        } else {
            return redirect()->back()->with('error', 'Gagal Approve');
        }
    }



}

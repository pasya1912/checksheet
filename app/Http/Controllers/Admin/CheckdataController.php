<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

            if ($request->tanggal == null || $request->tanggal == '') {
                $min_tanggal = $request->tanggal;
                $max_tanggal = $request->tanggal;
            } else {
                $min_tanggal = date("Y-m-d");
                $max_tanggal = date("Y-m-d");
            }

            $approval = '0';
            $timpa = [
                'shift' => '',
                'barang' => '',
                'cell' => ''
            ];
        } elseif ($jabatan == '2') //leader
        {

            if ($request->tanggal == null || $request->tanggal == '') {
                $min_tanggal = $request->tanggal;
                $max_tanggal = $request->tanggal;
            } else {
                $min_tanggal = date("Y-m-d", strtotime("-1 day"));
                $max_tanggal = date("Y-m-d");
            }

            $approval = '1';
            $timpa = [
                'shift' => '',
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

        $mergeArray = [
            'approval' => $approval,
            'data' => [
                ...$timpa,
                'filter' => 'need_check',
                'min_tanggal' => $min_tanggal ?? '',
                'max_tanggal' => $max_tanggal ?? '',
            ]
        ];

        return $mergeArray;
    }
    public function approval_page(Request $request, \App\Service\Admin\ListCheckData $listCheckData, \App\Service\ChecksheetData $checksheetData)
    {
        $mergeArray = $this->merging(auth()->user()->jabatan, $request);
        if (empty($mergeArray)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }
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
            if ($value->status == "good") {
                $good++;
            } elseif ($value->status == "notgood" && $value->revised_status == "good") {
                $revised++;
            }
        }

        //return approval blade
        return view('checksheet.checkdata.approval', compact('checkdata', 'lineList', 'codeList', 'checkList', 'areaList', 'good', 'revised'));
    }
    public function approval(Request $request, \App\Service\Admin\ListCheckData $listCheckData)
    {


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
            return redirect()->route('checksheet.data')->with('success', 'Berhasil Approve');
        } else {
            return redirect()->back()->with('error', 'Gagal Approve');
        }
    }
    public function updateStatus($id, Request $request)
    {
        //validate request->status only allow approved rejected or wait
        $request->validate([
            'status' => 'required|in:1,2,3,4'
        ]);
        $user_jabatan = auth()->user()->jabatan;
        if ($user_jabatan == '1') {
            $approval = '1';
        } elseif ($user_jabatan == '2') {
            $approval = '2';

        } elseif ($user_jabatan == '3') {
            $approval = '3';

        } elseif ($user_jabatan == '4') {
            $approval = '4';

        }
        try {
            $checkdata = Checkdata::find($id);
            $checkdata->approval = $approval;
            $checkdata->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'gagal', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Status berhasil diubah'], 200);
    }



}

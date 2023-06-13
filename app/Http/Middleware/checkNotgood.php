<?php

namespace App\Http\Middleware;

use App\Service\ChecksheetData;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class checkNotgood
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $checksheetData = new ChecksheetData();
        //check NG & Revised from checksheetData
        if($checksheetData->getBad() - $checksheetData->getRevised() > 0)
        {
            //redirect next request with session flash
            Session::flash('error', 'Terdapat NG yang belum direvisi!');
        }
        return $next($request);
    }
}

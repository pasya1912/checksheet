<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class checkJP
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
        //check in session if jp session is exist and valid
        if(!$request->session()->has('jp') || $request->session()->get('jp') == null)
        {
            $user = User::where('npk',$request->jp)->first();
            if($user == null || $user->role != '1')
            {
                return redirect()->route('checksheet.list')->with('error', 'Anda belum memilih JP!');
            }
        }
        return $next($request);
    }
}

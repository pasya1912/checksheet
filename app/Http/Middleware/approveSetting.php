<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class approveSetting
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
        $jabatan = auth()->user()->jabatan;
        if($jabatan < 3 || $jabatan > 4)
        {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;
use DB;
use Closure;

class MaintenanceMode
{
    public function handle($request, Closure $next)

    {
        $ms = DB::table('settings')->where('slug','=','maintainence_mode')->first();;
        if($ms->value == 1) {
           return redirect()->route('front-maintenance');
        }
        return $next($request);
    }
}

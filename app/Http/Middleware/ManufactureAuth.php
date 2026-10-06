<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



  
class ManufactureAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'backend') {
            $user = Auth::guard('backend')->user();
            if ($user && $user->role_id == 5) {
                return redirect('manufacture/dashboard');
            }
            return $next($request);
    }
        

}
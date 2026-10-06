<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\User;

class UserCheck {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'frontend') {
        $user_id = Auth::guard('frontend')->user()->id;
        $user = DB::table('users')->where('id',$user_id)->first();
        // dd($user);
        if ($user->email !='' && $user->phone !='') {
            return $next($request);
        }else{
            return redirect()->route('my-profile')->with('error_msg', 'Please Complete your profile!');
        }
        
    }

}

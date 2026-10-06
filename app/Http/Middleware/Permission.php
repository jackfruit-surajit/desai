<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Admin;
use App\Models\Role;

class Permission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $api="")
    {   
        $user = Auth::guard('backend')->user();
        if($user->type_id == 1){
            return $next($request);
        }else{
            $role = Role::find($user->role_id);
            $routeName = $request->route()->getName();
            $routeAction = $request->route()->getAction();
            $actionLabel = $routeAction['label'] ?? null;
            if($actionLabel == NULL){
                return $next($request);
            }else{
                if ($role->hasPermission($routeName)) {
                    return $next($request);
                }else{
                    return redirect()->route('admin-dashboard')->with('error_msg', 'Warning! Not enough permissions. Please contact Site Admin for more.');
                }
            }
            

        }

        if (!empty($api)) {
            return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
        } 
        else {
            Session::flash('message', 'Warning! Not enough permissions. Please contact Site Admin for more.');
            Session::flash('status', 'warning');
            return redirect()->back();
        }
    }

}

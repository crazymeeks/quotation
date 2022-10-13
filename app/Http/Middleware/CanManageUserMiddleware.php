<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;

class CanManageUserMiddleware
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
        $loggedInUser = $request->session()->get('auth');
        
        $role = $loggedInUser->role->title;
        
        if (in_array($role, [Role::ADMIN_ROLE])) {
            $request->merge([
                'user' => $loggedInUser,
                'current_role' => $role,
            ]);
            return $next($request);
        }

        abort(403);
    }
}

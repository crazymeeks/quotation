<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActionAbilityMiddleware
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
        
        
        $allowedMethods = $this->getAllowedAction($loggedInUser->role->permission->title);
        $requestMethod = strtolower($request->method());
        if (in_array($requestMethod, $allowedMethods)) {
            return $next($request);
        }

        if (is_testing()) {
            return response()->json(['message' => 'You are not allowed to access this resource.'], 403);
        }
        
        abort(403);
    }

    /**
     * Get allowed action
     *
     * @param string $permission
     * 
     * @return array
     */
    protected function getAllowedAction(string $permission)
    {
        $actions = [
            'read' => [
                'get'
            ],
            'write' => [
                'get',
                'post',
                'put',
                'patch'
            ],
            'admin' => [
                'get',
                'post',
                'put',
                'delete',
                'patch'
            ]
        ];

        $permission = strtolower($permission);

        if (isset($actions[$permission])) {
            return $actions[$permission];
        }

        return [];
    }
}
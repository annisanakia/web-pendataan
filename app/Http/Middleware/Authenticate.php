<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards){
        if (!Auth::guard($guards)->check()) {
            if ($request->ajax()) {
                return '<script>window.location.href = "'.url('login').'";</script>';
            }
            Auth::logout();
            return redirect()->guest('login');
        }else{
            $prefix = request()->route()->getPrefix() != ''? request()->route()->getPrefix() : 'home';
            $prefix = in_array($prefix,['holland_question','ist_question'])? 'bank_question' : $prefix;
            if (!array_key_exists($prefix,menuSideBar()) && $prefix != 'account_setting') {
                return response()->view('errors.unauthorized');
            }
        }

        return $next($request);
    }
}

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
            $user_id = \Auth::user()->id ?? null;

            $subdistrict_ids = \Models\users_subdistrict::select(['subdistrict_id'])
                    ->where('user_id',$user_id)
                    ->pluck('subdistrict_id')
                    ->all();
            $request->session()->put('subdistrict_ids', $subdistrict_ids);

            $prefix = request()->route()->getPrefix() != ''? request()->route()->getPrefix() : 'home';
            $prefix = in_array($prefix,['city','district','subdistrict','job_type','volunteer_data'])? 'setting' : $prefix;
            if (!array_key_exists($prefix,menuSideBar()) && $prefix != 'account_setting') {
                return response()->view('errors.unauthorized');
            }
        }

        return $next($request);
    }
}

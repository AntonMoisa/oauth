<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->get('user_id')) {
            return $next($request);
        }

        $request->session()->forget('user_id');
        $request->session()->forget('user_email');
        $request->session()->forget('user_token');

        return redirect()->route('index')->with('message', 'Logout');
    }
}

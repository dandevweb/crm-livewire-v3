<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleImpersonation
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($id = session()->get('impersonate')) {
            auth()->onceUsingId($id);
        }

        return $next($request);
    }
}

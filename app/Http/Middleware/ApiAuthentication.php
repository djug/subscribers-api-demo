<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userId = Helpers::getUserIdFromApiKey($request);
        if (! $userId) {
                   return response()->json(['error' => ['code' => 302, 'message' => "API-Key Unauthorized"]], 302);
        }

        return $next($request);
    }
}

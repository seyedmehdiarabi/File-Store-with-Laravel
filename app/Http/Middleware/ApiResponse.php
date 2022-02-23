<?php


namespace App\Http\Middleware;

use Closure;

class ApiResponse
{
    public function handle($request, Closure $next)
    {
        $data = $next($request);
        return response()->json($data,200,
            ['Content-Type'=>'application/json;charset=UTF-8','Charset'=>'utf-8'],JSON_NUMERIC_CHECK);
    }
}

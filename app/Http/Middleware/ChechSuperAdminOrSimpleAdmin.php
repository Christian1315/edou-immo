<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChechSuperAdminOrSimpleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Is_User_A_SimpleAdmin_Or_SuperAdmin(request()->user()->id)) {
            return response()->json([
                'status'=>false,
                "message"=>"Seuls les Super Admins et les simples admins sont autorisés à effectuer cette opération"
            ],404);
        }
        return $next($request);
    }
}

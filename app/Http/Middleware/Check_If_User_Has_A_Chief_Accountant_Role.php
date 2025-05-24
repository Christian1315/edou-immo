<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Check_If_User_Has_A_Chief_Accountant_Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Is_User_Has_A_Chief_Accountant_Role(request()->user()->id)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Vous n'avez pas le rôle d'un Chef Comptable! Vous ne pouvez donc pas éffectuer cette opération",
                ],
                505
            );
        }
        return $next($request);
    }
}

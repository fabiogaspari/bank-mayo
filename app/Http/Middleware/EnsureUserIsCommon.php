<?php

namespace App\Http\Middleware;

use App\Utils\CpfUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCommon
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!CpfUtil::cpfValidation((Auth::user()->cpf_cnpj))) {
            return abort(401);
        }
 
        return $next($request);
    }
}

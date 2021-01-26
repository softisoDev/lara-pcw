<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuth
{

    private $secretCode = '3W3jSYYEs_pMbJrxACKWtWshAhXuHz_9WH7Su6ksgVGNSUQjmpE2Ld2LYbnhrKJN4Sv_Vtn9vEE99fa5r2wrTR28M3Sd8_nH9WRvWQsFWCnfvEsJrqX2DBBR6s_Jnq';

    public function handle(Request $request, Closure $next)
    {

        $headerToken = $request->header('authorization');

        if ( is_null($headerToken) || !strcmp($headerToken, $this->secretCode) ) {
            return $this->unauthorized();
        }

        return $next($request);
    }

    public function unauthorized()
    {
        return api()->fail('Unauthorized')->status(401)->toJson();
    }
}

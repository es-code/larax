<?php

namespace Escode\Larax\App\Http\Middleware;

use Closure;
use Escode\Larax\App\Models\LaraxException;
use Escode\Larax\App\Models\LaraxUser;
use Illuminate\Support\Facades\Log;

class AuthLaraxApi
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

        if(LaraxUser::where('user_key',$request->bearerToken())->exists()){
            return $next($request);
        }
       return response()->json(['status'=>false,'message'=>'you can\'t access'],401);
    }
}

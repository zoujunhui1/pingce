<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnableCrossRequestMiddleware
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
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = [
            '*',
            'http://localhost:8090'
        ];
        if (in_array($origin, $allow_origin)) {
            $data = [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN',
                'Access-Control-Expose-Headers'    => 'Authorization, authenticated',
                'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, OPTIONS',
                'Access-Control-Allow-Credentials' => 'true'
            ];
            $response->headers->add($data);
//            $response->header('Access-Control-Allow-Origin', $origin);
//            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
//            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
//            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
//            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }

}

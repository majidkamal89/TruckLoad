<?php

namespace App\Http\Middleware;

use Closure;
use App\LoadController;
class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {/*
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQ2LCJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvZG9sb2dpbiIsImlhdCI6MTQ5NTA5NjQ5MSwiZXhwIjoxNDk1MTAwMDkxLCJuYmYiOjE0OTUwOTY0OTEsImp0aSI6IkVWUnZ0MlNHS0I0Qjk1WHgifQ.T9erL4cj_d5_3D6OC6rajI2w5GHUxKwbgWrEJ0WbOBY';*/

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin' , '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        return $response;

        /*return $next($request)
            ->header('Access-Control-Allow-Origin','http://localhost:4200')
            ->header('Access-Control-Allow-Methods','GET, POST, PUT, PATCH, DELETE, OPTION')
            ->header('Access-Control-Allow-Headers','Content-Type,Authorization');*/
        // ->header('Authorization', 'Bearer ' . $token);

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * add Log System
     * 
     * Last Update 2021-03-19 09:09:09
     * By Prasong putichanchai
     * 
     *  @return array
     */

    public function addLogSys($request, $data = [], $req = [])
    {

        Log::channel('appsyslog')->info(
            '#log#',
            [
                'username' => @Auth::user()->username,
                'ip' => $request->ip(),
                'date' =>  date("Y-m-d H:i:s"),
                'uri' => Route::current()->uri,
                'action' => Route::current()->action['as'],
                'parameters' => Route::current()->parameters(),
                'route' => Route::currentRouteName(),
                'methods' => Route::current()->methods,
                'request' => $req,
                'response_code' => http_response_code(),
                'data' => $data,
            ]
        );
    }
}

<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use App\Services\BackEndService;

class LogsService
{
    public function __construct()
    {
        $this->dateTime = date("Y-m-d H:i:s");
        $this->ip = $this->getUserIP();
        $this->userName = '';
        $this->BackEndService = new BackEndService();

    }

    public function logsBackEnd($data=[])
    {

        if(!Route::currentRouteName()){
            $route = explode('Controllers', Route::current()->action['controller'])[1];
        }else{
            $route = Route::currentRouteName();
        }

        $data = (object)$data;

        $username = "";
        if(!@$data->username){
            $username = @Auth::user()->username;
        }else{
            $username = $data->username;
        }

        $action = "";
        // if(@Route::current()->action['as']){
        //     $action = Route::current()->action['as'];
        // }else{

        // }
        if (@$data->action) {
            $action = $data->action;
        } elseif(@Route::current()->action['as']) {
            $action = Route::current()->action['as'];
        }

        $res = "";
        if (@$data->res) {
            $res = $data->res;
        }

        if (@$data->type) {
            $type = $data->type;
        } else {
            $type = 'info';
        }

        //---view A= all , S=Admin only
        if (@$data->view) {
            $view = $data->view;
        } else {
            $view = 'A';
        }
        $data->view = $view;
        $data->type = $type;
        $data->username = $username;
        $data->dateTime = $this->dateTime;
        $data->ip = $this->ip;
        $data->date = $this->dateTime;
        $data->uri = Route::current()->uri;
        $data->action = $action;
        $data->parameters = Route::current()->parameters();
        $data->route = $route;
        $data->request = $data->req;
        $data->response_code = $data->responseCode;
        $data->methods = Route::current()->methods;
        $data->response = @$data->response;

            Log::channel('app_sys_log')->info(
                '#Front End#',
                [
                    $data
                ]
            );
        $this->BackEndService->logs($data);
        
    }

    protected function getUserIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

?>
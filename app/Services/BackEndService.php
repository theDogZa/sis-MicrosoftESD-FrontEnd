<?php
namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\GuzzleHttp;

class BackEndService
{
    public function __construct()
    {
        $this->dateTime = date("Y-m-d H:i:s");
        $this->userName = '';
        $this->uuid = Str::uuid()->toString();
        $this->url = config('api.backend.url');
        $this->token = '';
    }

    public function logs($data=[])
    {
        $data = (object)$data;
        $arrReq = (object)array();

        $arrHeader = array();
        $arrHeader['requestUId'] = $this->uuid;
        $arrHeader['authorization'] = 'Bearer ' . $this->token;
        $arrHeader['content-type'] = 'application/json';

        $arrReq->url = $this->url . config('api.backend.service.logs');
        $arrReq->headers = $arrHeader;
        $arrReq->body = $data;

        $api = new GuzzleHttp;
        $apiResponse = (object)$api->post($arrReq);
        Log::info('info: BackEndService:logs : ', ['res' => $apiResponse]);
       // dd($arrReq,$apiResponse, 'apiResponse');
    }

    public function getLicense($data = [])
    {
        $data = (object)$data;
        $arrReq = (object)array();

        $response = (object) array();
        $response->status = (object) array();

        $arrHeader = array();
        $arrHeader['requestUId'] = $this->uuid;
        $arrHeader['authorization'] = 'Bearer ' . $this->token;
        $arrHeader['content-type'] = 'application/json';

        

        $arrReq->url = $this->url . config('api.backend.service.getLicense');
        $arrReq->headers = $arrHeader;
        $arrReq->body = $data;

        $api = new GuzzleHttp;
        $apiResponse = (object)$api->post($arrReq);
        Log::info('info: BackEndService:getLicense : ', ['res' => $apiResponse]);
        if ($apiResponse->code == 200) {

            $response->status->code = 200;
            $response->status->message = 'Success';
            $response->data = $apiResponse->data->data;
        } else {
            $response->status->code = $apiResponse->code;
            $response->status->message = 'error';
            $response->data = $apiResponse->data->data;
        }

        return $response;
    }

}

?>
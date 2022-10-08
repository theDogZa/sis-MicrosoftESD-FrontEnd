<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Services\sapService;
use App\Services\soapService;

class testController extends Controller
{
  /**
   * Instantiate a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //$this->middleware('auth');
    //$this->middleware('RolePermission');
    Cache::flush();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/html');

    // $this->SAP = new sapService();
    // $this->SOAP = new soapService();

  }

  function testsap(){

    $response = (object) array();
    $customerCode = "";

    $results = $this->SAP->Z_SD0003($customerCode);
    if($results->status->code == 200){
      
      $response->status = $results->status;
      $response->data = $results->data;
    }else{
      $response->status = $results->status;
      $response->data = [];
    }
    
    dd($response);
    
  
  }

  function testsoap(){

    $data = [];
    $response = $this->SOAP->createOrderSAP($data);

    dd($response);

  }
}


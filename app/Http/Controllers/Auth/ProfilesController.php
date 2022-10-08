<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Services\LogsService;

class ProfilesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profiles Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
          //$this->addLogSys($request);
          return $next($request);
        });

      $this->logs = new LogsService();

        Cache::flush();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: text/html');
        $this->arrShowFieldProfile = [
		    'username' => 1,  'first_name' => 1,  'last_name' => 1,  'email' => 1,  'email_verified_at' => 0,  'password' => 1, 'active' => 0,  'activated' => 0,  'remember_token' => 0,  'last_login' => 1, 		];
    }

  /**
   * Show profile the form for editing the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $id = Auth::id();
    $select = '*';

    $compact = (object) array();
    $user = User::select($select)->findOrFail($id);
    $compact->arrShowField = $this->arrShowFieldProfile;

    $compact->user = $user;

    $arrLog = [];
    $arrLog['type'] = 'info';
    $arrLog['view'] = 'A';
    $arrLog['action'] = 'Profiles.edit';
    $arrLog['req'] = array('sale_uid' => Auth::id());
    $arrLog['response'] = $user->toArray();
    $arrLog['responseCode'] = 200;
    $this->logs->logsBackEnd($arrLog);

    return view('_profile.form',$user, (array) $compact);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request) {
  
    $input = (object) $request->except(['_token', '_method']);
  
    try {
      DB::beginTransaction();
    
      $user = User::find(Auth::id());   

      $user->email = $input->email;
      $user->first_name = $input->first_name;
      $user->last_name = $input->last_name;
      
      $user->save();

      DB::commit();
      Log::info('Successful: User:Profiles : ', ['id' => $user->id, 'data' => $user]);

      $message = trans('profiles.message_change_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: User:update :' . $e->getMessage());

      $message = trans('profiles.message_change_error');
      $status = 'error';
      $title = 'Error';
    }
    $compact = (object) array();
    $compact->arrShowField = $this->arrShowFieldProfile;

    $arrLog = [];
    $arrLog['type'] = 'info';
    $arrLog['view'] = 'A';
    $arrLog['action'] = 'Profiles.update';
    $arrLog['req'] = array('sale_uid' => Auth::id());
    $arrLog['response'] = $user->toArray();
    $arrLog['responseCode'] = 200;
    $this->logs->logsBackEnd($arrLog);

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return view('_profile.form', $user, (array) $compact);
  }

  /**
   * This function checks if the username is already taken
   * 
   * @param Request request The request object.
   * 
   * @return The response is being returned as a base64 encoded string.
   */
  public function checkUsername(Request $request) {
    $response = (object)[];
    $chk = false;
    $user = User::select('id')->where('username',$request->username)->first();

    if(!isset($user->id)){
        $chk = true;
    }
    $response->code = 200;
    $response->message = $chk;

    Log::info('Successful: ProfilesController:checkUsername : ', ['req' => $request->username, 'res' => $response]);

    return base64_encode(json_encode((array)$response));
  }

  /**
   * This function checks if the email is already in use
   * 
   * @param Request request The request object.
   * 
   * @return The response is being returned as a base64 encoded string.
   */
  public function checkEmail(Request $request) {
    $response = (object)[];
    $chk = false;
    $user = User::select('id')->where('email',$request->email)->first();
    if(!isset($user->id)){
        $chk = true;
    }
    $response->code = 200;
    $response->message = $chk;

    Log::info('Successful: ProfilesController:checkEmail : ', ['req' => $request->email, 'res' => $response]);

    return base64_encode(json_encode((array)$response));
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\Billing;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

use App\Services\LogsService;
use App\Services\BackEndService;

class OrdersController extends Controller
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

    $this->arrShowFieldIndex = [
		 'customer_name' => 1,  'email' => 1,  'tel' => 1,  'path_no' => 1,  'receipt_no' => 1,  'sale_uid' => 1,  'sale_at' => 1, 		];
		$this->arrShowFieldFrom = [
		 'customer_name' => 1,  'email' => 1,  'tel' => 1,  'path_no' => 1,  'receipt_no' => 1,  'sale_uid' => 0,  'sale_at' => 0, 		];
		$this->arrShowFieldView = [
		 'customer_name' => 1,  'email' => 1,  'tel' => 1,  'path_no' => 1,  'receipt_no' => 1,  'sale_uid' => 1,  'sale_at' => 1, 		];

    $this->logs = new LogsService();
  }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		$rules = [
			'customer_name' => 'required|string|max:255',
			'email' => 'required|string|max:255',
			'tel' => 'required|string|max:255',
			'path_no' => 'required|string|max:255',
			'receipt_no' => 'required|string|max:255',
			'sale_uid' => 'required|string|max:255',
			'sale_at' => 'required|string|max:255',
			//#Ex
			//'username' => 'required|string|max:20|unique:users,username,' . $data ['id'],
			//'email' => 'required|string|email|max:255|unique:users,email,' . $data ['id'],
			// 'password' => 'required|string|min:6|confirmed',
			//'password' => 'required|string|min:6',
		];
		
		$messages = [
			'customer_name.required' => trans('Order.customer_name_required'),
			'email.required' => trans('Order.email_required'),
			'tel.required' => trans('Order.tel_required'),
			'path_no.required' => trans('Order.path_no_required'),
			'receipt_no.required' => trans('Order.receipt_no_required'),
			'sale_uid.required' => trans('Order.sale_uid_required'),
			'sale_at.required' => trans('Order.sale_at_required'),
			//#Ex
			//'email.unique'  => 'Email already taken' ,
			//'username.unique'  => 'Username "' . $data['username'] . '" already taken',
			//'email.email' =>'Email type',
		];

		return Validator::make($data,$rules,$messages);
	}

  public function list(Request $request)
  {
    $compact = (object) array();

    $select = $this->_listToSelect($this->arrShowFieldIndex);

    $results = Order::select($select);

    $results = $results->where('sale_uid',Auth::id());

    $results = $results->orderBy('sale_at','DESC');

    $orders = $results->get();

    foreach($orders as $order){
      $orderItems = OrderItem::where('order_id',$order->id)->get();
      foreach($orderItems as $orderItem){
        $showLicense = '';
        $arrDataLicense = explode("-", $orderItem->license_key);
        if(isset($arrDataLicense)){
          foreach($arrDataLicense AS $k => $v){
            if($k != 0 && $k != count($arrDataLicense)-1){
              $nv = '-xxxxx';
            }elseif($k == count($arrDataLicense)-1){
              $nv ='-'.$v;
            }else{
              $nv = $v;
            }
            $showLicense .= $nv;
          }
          //$orderItem->license_key = $showLicense;
          $arrOrderItems[$order->id] = $showLicense;
        }
      }
    }

    $compact->collection = $orders;
    if(count($orders)){
      $compact->orderItems = $arrOrderItems;
    }

    $log['req'] = array('sale_uid'=> Auth::id());
    $log['responseCode'] = 200;
    $log['response'] = $orders;
    $this->logs->logsBackEnd($log);

    $compact->arrShowField = $this->arrShowFieldIndex;

    return view('_orders.list', (array) $compact);
  }


  public function index(Request $request)
  {

    $compact = (object) array();

    $log['req'] = [];
    $log['action'] = 'home.';
    $log['responseCode'] = 200;
    $log['response'] = [];
    $this->logs->logsBackEnd($log);

    return view('_orders.index', (array) $compact);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
      $compact = (object) array();
      $compact->arrShowField = $this->arrShowFieldFrom;

      $this->_getDataBelongs($compact);

      $log['req'] = [];
      $log['responseCode'] = 200;
      $log['response'] = [];
      $this->logs->logsBackEnd($log);

      return view('_orders.form', (array) $compact);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {


    $input = (object) $request->except(['_token', '_method']);

      $billings = Billing::select('id', 'sale_count')->where('billings.active', 1)->where('vendor_article', $input->path_no)->whereColumn('sale_count','<', 'qty')->count();
      if($billings==0){
        return redirect()->route('order.from');
      }

    try {
      DB::beginTransaction();

      //---items --------------------------------
      $orderId = null;
      foreach($input->serial as $serial){

        $inv = Inventory::select('inventory.id AS invId', 'billings.id AS billingId','billings.sale_count AS saleCount', 'billings.qty AS billingQty')
        ->leftJoin('billings', 'billings.id', '=', 'inventory.billing_id')
        ->where('billings.vendor_article', $input->path_no)
        ->where('billings.active', 1)
        ->where('inventory.active', 1)
        ->whereColumn('billings.sale_count', '<', 'billings.qty')
        ->where('serial', $serial)
        ->where('sale_status', 0)
        ->first();
        
        if(@$inv->invId){

          if(!$orderId){

            $order = new Order;
            $order->customer_name = $input->customer_name;
            $order->email = $input->email;
            $order->tel = $input->tel;
            $order->path_no = $input->path_no;
            $order->receipt_no = $input->receipt_no;
            $order->sale_uid = Auth::id();
            $order->sale_at = date("Y-m-d H:i:s");
            $order->created_uid = Auth::id();
            $order->created_at = date("Y-m-d H:i:s");
            $order->save();

            $arrLog = [];
            $arrLog['type'] = 'info';
            $arrLog['view'] = 'S';
            $arrLog['action'] = 'orders.Order Created';
            $arrLog['req'] = (array)$input;
            $arrLog['response'] = $order->toArray();
            $arrLog['responseCode'] = 200;
            $this->logs->logsBackEnd($arrLog);

            $orderId = $order->id;
          }

          $orderItem = new OrderItem;
          $orderItem->order_id = $orderId;
          $orderItem->inventory_id = $inv->invId;
          $orderItem->created_uid = Auth::id();
          $orderItem->created_at = date("Y-m-d H:i:s");
          $orderItem->save();

          $arrLog = [];
          $arrLog['type'] = 'info';
          $arrLog['view'] = 'S';
          $arrLog['action'] = 'orders.OrderItem Created';
          $arrLog['req'] = array('id' => $inv->invId);
          $arrLog['response'] = $orderItem->toArray();
          $arrLog['responseCode'] = 200;
          $this->logs->logsBackEnd($arrLog);

          $inventory = Inventory::find($inv->invId);
          $inventory->sale_status = 1;
          $inventory->updated_uid = Auth::id();
          $inventory->updated_at = date("Y-m-d H:i:s");
          $inventory->save();

          $arrLog = [];
          $arrLog['type'] = 'info';
          $arrLog['view'] = 'S';
          $arrLog['action'] = 'orders.Inventory Update';
          $arrLog['req'] = array('id' => $inv->invId);
          $arrLog['response'] = $inventory->toArray();
          $arrLog['responseCode'] = 200;
          $this->logs->logsBackEnd($arrLog);

          $billing = Billing::find($inv->billingId);
          $billing->sale_count = $inv->saleCount+1;
          // $billing->remaining_amount = $inv->billingQty - ($inv->saleCount + 1);
          $billing->remaining_amount = $billing->remaining_amount -1;
          $billing->updated_uid = Auth::id();
          $billing->updated_at = date("Y-m-d H:i:s");
          $billing->save();

          $arrLog = [];
          $arrLog['type'] = 'info';
          $arrLog['view'] = 'S';
          $arrLog['action'] = 'orders.billing Update';
          $arrLog['req'] = array('id' => $inv->billingId);
          $arrLog['response'] = $billing->toArray();
          $arrLog['responseCode'] = 200;
          $this->logs->logsBackEnd($arrLog);

        }else{

          $message = trans('order.message_insert_serial_used');
          $status = 'error';
          $title = 'Error';

        }
      }

      DB::commit();
      Log::info('Successful: Order:store : ', ['data' => $order->toArray()]);

      $log = [];
      $log['req'] = $input;
      $log['view'] = 'A';
      $log['action'] = 'orders.store';
      $log['responseCode'] = 200;
      $log['response'] = $order->toArray();
      $this->logs->logsBackEnd($log);

      $data['orderId'] = $orderId;

      //------ get License key
      $BackEnd = new BackEndService;
      $responseApi = $BackEnd->getLicense($data);

      // dd($responseApi, $responseApi->data, $responseApi->data->logs);

      foreach ($responseApi->data->logs as $k => $log){
        $log->responseCode = 200;
        //$log->action = $k;
        $log->req = $log->request;
        if(!@$log->view){
          $log->view = 'A';
        }
        
        $this->logs->logsBackEnd($log);
      }


      Log::info('Successful: Order:store:getLicense : ', ['response' => $responseApi]);

      // $log['req'] = $data;
      // $log['responseCode'] = 200;
      // $this->logs->logsBackEnd($log);

      //-------

      $message = trans('core.message_insert_success');
      $status = 'success';
      $title = 'Success';

    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: Order:store :' . $e->getMessage());

      $dataLog = array();
      $dataLog['action'] = 'Order:store';
      $dataLog['view'] = 'A';
      $dataLog['responseCode'] = 200;
      $dataLog['req'] = (array)$input;
      $dataLog['response'] = array('ErrorMessage' => $e->getMessage());
      $this->logs->logsBackEnd($dataLog);
      //$this->_cLog($request, $dataLog);

      $message = trans('core.message_insert_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('order.index');
  }

  /**
   * Field list To Select data form db 
   *
   * @param  array  $arrField
   * @return array select data
   */
  protected function _listToSelect($arrField)
  {
    $select[] = 'id';
    foreach ($arrField as $key => $val) {
      if ($val == 1) {
        $select[] = $key;
      }
    }
    return $select;
  }

  /**
   * This function is used to get the data that belongs to the user
   * 
   * @param compact The name of the variable that will be used to store the data.
   */
  protected function _getDataBelongs($compact)
  {
    $compact->arrSaleu = User::where('id', '!=', null)
    ->orderBy('id')
    ->pluck('username', 'id')
    ->toArray();
  }

  /**
   * This function checks if the path_no is valid and if it is, it checks if the path_no is out of
   * stock
   * 
   * @param Request request The request object.
   * 
   * @return The response object contains the code, message, and data.
   */
  public function checkPathNo(Request $request){
    
    $response = (object) array();

    $response->code = 200;
    $response->data = [];

    $input = (object) $request->except(['_token', '_method']);
    
    $log = [];
    $log['req'] = (array)$input;

    $billings = Billing::select('id', 'sale_count', 'material_desc')->where('billings.active', 1)->where('vendor_article', $input->path_no);
    $cBillings = $billings->count();
    if ($cBillings == 0) {
      $response->code = 400;
      $response->message = trans('orders.message_path_not_found');
      $log['responseCode'] = 400;
      $log['response'] = (array)$response;
    }else{
      $qBillings = $billings->whereColumn('sale_count', '<', 'qty')->count();
      if ($qBillings == 0) {
        $response->code = 400;
        $response->message = trans('orders.message_path_out_of_stock');

        $log['responseCode'] = 400;
        $log['response'] = (array)$response;
      }else{
        $data = $billings->first();
        $response->data = $data->material_desc;

        $log['responseCode'] = 200;
        $log['response'] = $billings->get()->toArray();
      }
    }

    $this->logs->logsBackEnd($log);

    return $response;
    
  }

  /**
   * This function checks if the serial number is valid or not
   * 
   * @param Request request The request object.
   * 
   * @return The response object is being returned.
   */
  public function checkSerial(Request $request)
  {

    $response = (object) array();

    $response->code = 200;
    $response->data = [];

    $input = (object) $request->except(['_token', '_method']);

    foreach ($input->serial as $serial) {

      $log = [];
      $log['req'] = array('path_no' => $input->path_no, 'serial' => $serial);

      $Inventory = Inventory::select('inventory.id AS invId', 'billings.id AS billingId', 'billings.sale_count AS saleCount')
        ->leftJoin('billings', 'billings.id', '=', 'inventory.billing_id')
        ->where('billings.active', 1)
        ->where('inventory.active', 1)
        ->where('billings.vendor_article', $input->path_no)
        ->whereColumn('billings.sale_count', '<', 'billings.qty')
        ->where('serial', $serial);
      //->where('sale_status', 0)
      //->count();
      $cInv = $Inventory->count();
  
      if ($cInv == 0) {
        $response->code = 400;
        $response->message = trans('orders.message_serial_not_found');
        $log['responseCode'] = 400;
        $log['response'] = (array)$response;
      }else{
        $qInv = $Inventory->where('sale_status', 0)->count();
        if($qInv == 0) {
          $response->code = 400;
          $response->message = trans('orders.message_insert_serial_used');
          $log['responseCode'] = 400;
          $log['response'] = (array)$response;
        }
        
        $log['responseCode'] = 200;
        $log['response'] = $Inventory->get()->toArray();
      }

      $this->logs->logsBackEnd($log);
    }

    return $response;
  }
}
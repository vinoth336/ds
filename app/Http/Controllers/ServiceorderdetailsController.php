<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Serviceorderdetails;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class ServiceorderdetailsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'serviceorderdetails';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Serviceorderdetails();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'serviceorderdetails',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{
        $url_region = $request->region;
		 
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

		
		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query 
		$results = $this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('serviceorderdetails');
		
		$this->data['rowData']		= $results['rows'];
		// Build Pagination 
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();	
		// Row grid Number 
		$this->data['i']			= ($page * $params['limit'])- $params['limit']; 
		// Grid Configuration 
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);		
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any
		
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
		// Render into template
		
		
		$user_id = \Auth::user()->id;
		$region_id = session()->get('rid');
		
		if($url_region != ''){
			
			$results = \DB::select("SELECT `sf`.*,`lbc`.`first_name`,`lbc`.`region` FROM `lunch_box_customers` as `lbc` JOIN `service_form` as `sf` ON `sf`.`cust_id` = `lbc`.`id` and `sf`.`service_status` NOT IN (2,4) and `sf`.`subscription_status` NOT IN (2,3,4) and `lbc`.`region`='".$url_region."' ORDER BY `sf`.`id` DESC");
			
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$url_region)->get();
			
		}elseif(\Auth::user()->group_id == 7){
			
			$results = \DB::select("SELECT `sf`.*,`lbc`.`first_name`,`lbc`.`region` FROM `lunch_box_customers` as `lbc` JOIN `service_form` as `sf` ON `sf`.`cust_id` = `lbc`.`id` and `sf`.`service_status` NOT IN (2,4) and `sf`.`subscription_status` NOT IN (2,3,4) and `lbc`.`region`='".$region_id."' ORDER BY `sf`.`id` DESC");
			
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$region_id)->get();
			
		}else{
			$results = \DB::select("SELECT `sf`.*,`lbc`.`first_name` FROM `lunch_box_customers` as `lbc` JOIN `service_form` as `sf` ON `sf`.`cust_id` = `lbc`.`id` and `sf`.`service_status` NOT IN (2,4) and `sf`.`subscription_status` NOT IN (2,3,4) ORDER BY `sf`.`id` DESC");
			
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->get();
			
		}		
		
	
		//$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->get();
		// $delivery_boys_form = \DB::table('service_deliveryboy')->select('id','username')->get();
		
		$deliveryboys .= '<option value=""> </option>';
		foreach ($delivery_boys as $key => $delivery_boy){
			$deliveryboys .= '<option value="'.$delivery_boy->id.'">'.$delivery_boy->username.'</option>';
		}
		
		/*$deliveryboysform .= '<option value=""> </option>';
		foreach ($delivery_boys_form as $key => $delivery_boys_forms){
			$deliveryboysform .= '<option value="'.$delivery_boys_forms->id.'">'.$delivery_boys_forms->username.'</option>';
		}*/
		
		
		//$this->data['deliveryboysform'] = $deliveryboysform;
		$this->data['deliveryboys'] = $deliveryboys;
		$this->data['results']	= $results;
		return view('serviceorderdetails.index',$this->data);
	}	



	function getUpdate(Request $request, $id = null)
	{
	
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('service_form'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('serviceorderdetails.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('service_form'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('serviceorderdetails.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_serviceorderdetails');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'serviceorderdetails/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'serviceorderdetails?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('id') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('serviceorderdetails/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}	

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('serviceorderdetails')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('serviceorderdetails')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	
	
	
	public function getServiceaccept( Request $request){
		//echo $request->id;
	
	    $result = \DB::table('service_form')->where('id','=',$request->id)->update(['service_status'=>1]);
		//print_r($response);exit;
	
		echo "1";
		
	}		

    public function getServicereject( Request $request){
		//echo $request->id;
	
	    $result = \DB::table('service_form')->where('id','=',$request->id)->update(['service_status'=>2]);
		//print_r($response);exit;
	
		echo "2";
		
	}
	
	public function postManualserviceassigntoboy( Request $request){
		$id = $request->id;
		$boy_id = $request->boy_id;
	    $type = $request->type;
		
		if($type == "Service"){
		$result = \DB::table('service_deliveryboy')->where('id','=',$request->boy_id)->first();		
		}else{
		$result = \DB::table('abserve_deliveryboys')->where('id','=',$request->boy_id)->first();		
		}
		
		$update = \DB::table('service_form')->where('id','=',$request->id)->update(['service_status'=>3,'dboy_id'=>$boy_id,'dboy_name'=>$result->username]);
		echo "3";	
		
	}	
	
	public function sendSms1($mobilenumber, $message, $user_id) {
    	
		$authkey = "210837APHBz36Wwmx5ad87e64";
		
	  	$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.msg91.com/api/v2/sendsms",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{ \"sender\": \"DSRVLR\", \"route\": \"4\", \"country\": \"91\", \"sms\": [ { \"message\": \"$message\", \"to\": [ \"$mobilenumber\" ] } ] }",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			  "authkey: $authkey",
			  "content-type: application/json"
			),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		$res = array('user_id'=>$user_id, 'response'=>json_decode($response));
		
		
		
		if ($err) {
		  	$error['sms_error'] = "cURL Error #:" . $err;
			
		} else {
		  	//print_r $result;
			json_encode($res);
		}
  	}
	public function sendSms2($mobilenumber, $message, $res_id) {
    	
		$authkey = "210837APHBz36Wwmx5ad87e64";
		
	  	$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.msg91.com/api/v2/sendsms",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{ \"sender\": \"DSRVLR\", \"route\": \"4\", \"country\": \"91\", \"sms\": [ { \"message\": \"$message\", \"to\": [ \"$mobilenumber\" ] } ] }",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			  "authkey: $authkey",
			  "content-type: application/json"
			),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		$res = array('user_id'=>$user_id, 'response'=>json_decode($response));
		
		
		
		if ($err) {
		  	$error['sms_error'] = "cURL Error #:" . $err;
			
		} else {
		  	//print_r $result;
			json_encode($res);
		}
  	}
	
	public function sendWhatsappMessage($instanceId, $destNumber, $message) {
		$CLIENT_ID = "deliverystar2018@gmail.com";
		$CLIENT_SECRET = "023c969bf35941a78afa151216a0a23f";
	
		$postData = array(
		  'number' => $destNumber,
		  'message' => $message
		);
	
		$headers = array(
		  'Content-Type: application/json',
		  'X-WM-CLIENT-ID: '.$CLIENT_ID,
		  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
		);
	
		$url = 'http://api.whatsmate.net/v3/whatsapp/single/text/message/' . $instanceId;
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		$response = curl_exec($ch);
		curl_close($ch);
	
		return $response;
	}
	
	public function sendWhatsappImage($instanceId, $destNumber, $pathToImage) {
		$CLIENT_ID = "deliverystar2018@gmail.com";
		$CLIENT_SECRET = "023c969bf35941a78afa151216a0a23f";
	
		//$pathToImage = "/tmp/your_image.jpg";    // TODO: Replace it with the path to your image
		$imageData = file_get_contents($pathToImage);
		$base64Image = base64_encode($imageData);
		
		$postData = array(
			'number' 	=> $destNumber,  // TODO: Specify the recipient's number (NOT the gateway number) here.
			'image' 	=> $base64Image,
			'caption' 	=> 'Delivery Star'
		);
	
		$headers = array(
		  'Content-Type: application/json',
		  'X-WM-CLIENT-ID: '.$CLIENT_ID,
		  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
		);
	
		$url = 'http://api.whatsmate.net/v3/whatsapp/single/image/message/' . $instanceId;
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		$response = curl_exec($ch);
		curl_close($ch);
	
		return $response;
	}
	
	public function sendWhatsappPdf($instanceId, $destNumber, $pathToDocument, $pdf) {
		$CLIENT_ID = "deliverystar2018@gmail.com";
		$CLIENT_SECRET = "023c969bf35941a78afa151216a0a23f";
	
		//$pathToImage = "/tmp/your_image.jpg";    // TODO: Replace it with the path to your image
		$docData = file_get_contents($pathToDocument);
  		$base64Doc = base64_encode($docData);
		//$fn = $pdf;
		
		$postData = array(
			'number' 	=> $destNumber,  // TODO: Specify the recipient's number (NOT the gateway number) here.
			'document' 	=> $base64Doc,
			'filename' 	=> $pdf
		);
	
		$headers = array(
		  'Content-Type: application/json',
		  'X-WM-CLIENT-ID: '.$CLIENT_ID,
		  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
		);
	
		$url = 'http://api.whatsmate.net/v3/whatsapp/single/document/message/' . $instanceId;
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		$response = curl_exec($ch);
		curl_close($ch);
	
		return $response;
	}
	
	public function postSendmail( Request $request){
		
		//print_r($request->all());   exit;	
		$id = $request->id;
		$to_location = $request->to_location;
		$to_pin_code = $request->to_pin_code;
		$from_location = $request->location;
		$instruction = $request->instruction;
		$c_phone_number = $request->phone;
		$from_pincode = $request->pin_code;
		$cat_name = $request->cat_name;
		$subcat_name = $request->subcat_name;
		$subnodecat_name = $request->subnodecat_name;
		$boy_id = $request->boy_id;
		
		/*insert instruction to table*/
	 	$ins= \DB::table('service_dboy_instruction')->insert(['orderid'=>$id,'instruction'=>$instruction]);
	
	 	/****for delivery boy with phone number location and instruction****/
	 
		$delivery_boys = \DB::table('service_form')->select('dboy_id','type','file','date','comments','vendor')->where('id','=',$id)->first();
		
	   	if($delivery_boys->type != "Service"){
			//$delivery = \DB::table('abserve_deliveryboys')->select('username')->where('id','=',$boy_id)->first();
			$details = \DB::table('abserve_deliveryboys')->where('id','=',$boy_id)->first();
		}else{
			$details = \DB::table('service_deliveryboy')->where('id','=',$boy_id)->first();
			//$delivery = \DB::table('service_deliveryboy')->select('username')->where('id','=',$boy_id)->first();
		}
		//print_r($details);  exit;
		$update = \DB::table('service_form')->where('id','=',$id)->update(['service_status'=>3,'dboy_id'=>$boy_id,'dboy_name'=>$details->username]);
		
		
		
		
	    /*if($delivery_boys->type != "Service"){
			$delivery_num = \DB::table('abserve_deliveryboys')->select('phone_number')->where('id','=',$delivery_boys->dboy_id)->first();
		}else{
			$delivery_num = \DB::table('service_deliveryboy')->select('phone_number')->where('id','=',$delivery_boys->dboy_id)->first();
		}*/
		
		$date_time = $delivery_boys->date;
		$cust_ins = $delivery_boys->comments;
		
	 	$phone = "+91 ".$details->phone_number; 
	 	if($request->type == "Relocation"){
	 		//$message = "Orderid #SE".$id.",\r\n Customer number: ".$c_phone_number.",\r\n Relocate from ". $from_location.',\r\n '.$from_pincode.",\r\n Relocate to ". $to_location.' - '.$to_pin_code." with following instruction ".$instruction."";			
			$message = "Order Id: #SE".$id."\r\nCustomer No: ".$c_phone_number."\r\nVendor Address: ". $from_location.' - '.$from_pincode."\r\nCustomer Address: ". $to_location.' - '.$to_pin_code."\r\nDate / Time: ".$date_time."\r\nCategory 1: ".$cat_name."\r\nCategory 2: ".$subcat_name."\r\nCategory 3: ".$subnodecat_name."\r\nCustomer Comments: ".$cust_ins."";
			
		} else {
			if($request->type == "Delivery"){
			
				$vendor = \DB::table('vendor')->where('id','=',$delivery_boys->vendor)->first();
				$message = "Order Id: #SE".$id."\r\nVendor Store Name: ".$vendor->store_name."\r\nStore Address: ".$vendor->address."\r\nCustomer Number: ".$c_phone_number."\r\nCustomer Address: ". $to_location.' - '.$to_pin_code."\r\nDate / Time: ".$date_time."\r\nCategory 1: ".$cat_name."\r\nCategory 2: ".$subcat_name."\r\nCategory 3: ".$subnodecat_name."\r\nCustomer Comments: ".$cust_ins."";
				
			}else{
				
				//$message = "Orderid #SE".$id.",\r\n Customer number: ".$c_phone_number.",\r\n located in ". $to_location.' - '.$to_pin_code." with following instruction ".$instruction."";
				$message = "Order Id: #SE".$id."\r\nCustomer No: ".$c_phone_number."\r\nCustomer Address: ". $to_location.' - '.$to_pin_code."\r\nDate / Time: ".$date_time."\r\nCategory 1: ".$cat_name."\r\nCategory 2: ".$subcat_name."\r\nCategory 3: ".$subnodecat_name."\r\nCustomer Comments: ".$cust_ins."";
			}
		}
		//echo $message;  exit;
		//$this->sendSms1($phone, $message, $id);
		$instanceId = 18;
		//echo $instanceId . ' - ' .$phone. ' - ' .$message;  exit;
		$this->sendWhatsappMessage($instanceId, $phone, $message);
	 	/****for customer with phone number and buffer time****/
		
		$file = $delivery_boys->file;
		if($file != ""){			
			$pathToImage = \URL::to('').$file;
			$extension = explode(".",$file);
			//echo $extension[1]; exit;
			if((($extension[1] == "jpg") || ($extension[1] == "jpeg")) || ($extension[1] == "png")){
				$this->sendWhatsappImage($instanceId, $phone, $pathToImage);
			} else if(((($extension[1] == "pdf") || ($extension[1] == "docx")) || ($extension[1] == "xlsx"))|| ($extension[1] == "txt")){
				$pdf = explode("/",$file);
				$this->sendWhatsappPdf($instanceId, $phone, $pathToImage, $pdf[3]);				
			}
		}		
	
	 	/*$subcat = $request->subcategory;
	 	$subnode = $request->subnode;
	
		if($subnode != '' && $subcat != ''){
			$b_time = \DB::table('service_categories')->select('buffer_time')->where('id','=',$subnode)->first();
		}else{
			$b_time = \DB::table('service_categories')->select('buffer_time')->where('id','=',$subcat)->first();
		}
	
		//echo $b_time->buffer_time;
		if($b_time->buffer_time != ''){
			$buffer = $b_time->buffer_time;
		}else{
			$buffer = "1";
		}*/
		$c_phone_number = $request->phone;  // exit;
		// $message1 = "Delivery boy number : ".$phone ." and orderid is #SE".$id.""; 
		//$message1 = "Order #SE".$id." is assigned to Delivery boy ".$phone." and he will contact you within ".$buffer."hour";
		$message1 = "Orderid #SE".$id." is accepted. Our executive will contact you shortly. Thanks for ordering with us.";
		$this->sendSms2($c_phone_number, $message1, $id);
	
		return Redirect::to('serviceorderdetails')->with('messagetext', \Lang::get('Message sent'))->with('msgstatus','success'); 
	}	
	
	
	public function postOrderupdate( Request $request){
		
		//print_r($request->all());   //exit;	
	 	$id = $request->id;
	 	$order_amount = $request->order_amount;
	 	$c_phone_number = $request->phone;	 
	
	 	/****for delivery boy with phone number location and instruction****/
	 
	 	$delivery_boys = \DB::table('service_form')->select('dboy_id','type')->where('id','=',$id)->first();
	    if($delivery_boys->type != "Service"){
			$delivery_num = \DB::table('abserve_deliveryboys')->select('phone_number')->where('id','=',$delivery_boys->dboy_id)->first();
		}else{
			$delivery_num = \DB::table('service_deliveryboy')->select('phone_number')->where('id','=',$delivery_boys->dboy_id)->first();
		}
			
    	$update = \DB::table('service_form')->where('id','=',$request->id)->update(['service_status'=>4,'order_amount'=>$order_amount,'payment_status'=>1]);
		
		if($update){			
			$phone = "+91 ".$delivery_num->phone_number; 
			$message = "Thanks for completing the order #SE".$id.". Kindly collect the invoice amount of Rs.".$order_amount." from customer. Thanks!";
			
			//echo $message;  exit;
			//$this->sendSms1($phone, $message, $id);
			$instanceId = 18;
			$this->sendWhatsappMessage($instanceId, $phone, $message);
			/****for customer with phone number and buffer time****/ 
			
			$c_phone_number = $request->phone;  // exit;
			$message1 = "Orderid #SE".$id." has successfully completed. Invoice amount Rs.".$order_amount." Thanks for being an amazing customer.";   
			$this->sendSms2($c_phone_number, $message1, $id);
			
			return Redirect::to('serviceorderdetails')->with('messagetext', \Lang::get('Message sent with order amount'))->with('msgstatus','success'); 
		}
		
	}	
	
	
	public function postOrderinstruction( Request $request){
		
		//print_r($request->all());   exit;	
		
		$id = $request->id;
		$order_instruction = $request->order_inst;
		
		
		$update = \DB::table('service_form')->where('id','=',$request->id)->update(['order_instruction'=>$order_instruction]);
		
		return Redirect::to('serviceorderdetails')
				->with('messagetext', \Lang::get('Saved successfully'))->with('msgstatus','success'); 
	
	}
	
	public function postVendorassign( Request $request){
		
		//print_r($request->all());   exit;	
		
		$id = $request->orderid;
		$vendorid = $request->vendorid;
		$cat_name = $request->cat_name;
		$subcat_name = $request->subcat_name;
		$subnodecat_name = $request->subnodecat_name;
		$phone = $services->c_phone_number;
		
		$vendor = \DB::table('vendor')->where('id','=',$vendorid)->first();
		$services = \DB::table('service_form')->where('id','=',$id)->first();
		
		$update = \DB::table('service_form')->where('id','=',$id)->update(['vendor'=>$vendorid,'vendor_name'=>$vendor->store_name]);
		
		$phone = "+91 ".$vendor->primary_number;
		
		$message = "Order Id: #SE".$id."\r\nVendor Store Name: ".$vendor->store_name."\r\nStore Address: ".$vendor->address."\r\nCustomer Number: ".$services->c_phone_number."\r\nCustomer Address: ". $services->to_location.' - '.$services->to_pin_code."\r\nDate / Time: ".$services->date."\r\nCategory 1: ".$cat_name."\r\nCategory 2: ".$subcat_name."\r\nCategory 3: ".$subnodecat_name."\r\nCustomer Comments: ".$services->comments."";	
		
		
		//$this->sendSms1($phone, $message, $id);
		$instanceId = 18;
		//echo $instanceId . ' - ' .$phone. ' - ' .$message;  exit;
		$this->sendWhatsappMessage($instanceId, $phone, $message);
	 	/****for customer with phone number and buffer time****/
		
		$file = $services->file;
		if($file != ""){			
			$pathToImage = \URL::to('').$file;
			$extension = explode(".",$file);
			//echo $extension[1]; exit;
			if((($extension[1] == "jpg") || ($extension[1] == "jpeg")) || ($extension[1] == "png")){
				$this->sendWhatsappImage($instanceId, $phone, $pathToImage);
			} else if(((($extension[1] == "pdf") || ($extension[1] == "docx")) || ($extension[1] == "xlsx"))|| ($extension[1] == "txt")){
				$pdf = explode("/",$file);
				$this->sendWhatsappPdf($instanceId, $phone, $pathToImage, $pdf[3]);				
			}
		}
		
		//return Redirect::to('serviceorderdetails')->with('messagetext', \Lang::get('Saved successfully'))->with('msgstatus','success'); 
		echo "1"; //success
	
	}	
	
	
	public function getAjaxload( Request $request){
		$url_region = $request->regionselect;   
		$user_id = \Auth::user()->id;
		$region_id = session()->get('rid');
		$current_time = date("H:i:s");
		
		if($url_region != ''){	
			$results = \DB::select("SELECT `sf`.*,`lbc`.`first_name`,`lbc`.`region` FROM `lunch_box_customers` as `lbc` JOIN `service_form` as `sf` ON `sf`.`cust_id` = `lbc`.`id` and `sf`.`service_status` NOT IN (2,4) and `sf`.`subscription_status` NOT IN (2,3,4) and `lbc`.`region`='".$url_region."' ORDER BY `sf`.`id` DESC");
		}elseif(\Auth::user()->group_id == 7){
			$results = \DB::select("SELECT `sf`.*,`lbc`.`first_name`,`lbc`.`region` FROM `lunch_box_customers` as `lbc` JOIN `service_form` as `sf` ON `sf`.`cust_id` = `lbc`.`id` and `sf`.`service_status` NOT IN (2,4) and `sf`.`subscription_status` NOT IN (2,3,4) and `lbc`.`region`='".$region_id."' ORDER BY `sf`.`id` DESC");	
		}else{
			$results = \DB::select("SELECT `sf`.*,`lbc`.`first_name` FROM `lunch_box_customers` as `lbc` JOIN `service_form` as `sf` ON `sf`.`cust_id` = `lbc`.`id` and `sf`.`service_status` NOT IN (2,4) and `sf`.`subscription_status` NOT IN (2,3,4) ORDER BY `sf`.`id` DESC");	
		}
		//  print_r($results);
		
		if(!empty($results)){
		  foreach($results as $order){
			$action = "";
			$deliveryboys = "";
			$order_in = "";
			$_vendor = "";
			$_vendors = "";
			
			if($order->subcategory != '' && $order->subnode != ''){
				$subcat_id = $order->subnode;
			} else {
				$subcat_id = $order->subcategory;
			}
			$type = strtolower($order->type);	
	      	if($type != "service"){
				if($url_region !=''){
					$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$url_region)->get();
				} else {
					$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$order->region)->get();	
				}
				if($type == "delivery") {
					if($url_region !=''){
						$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Delivery')->where('region','=',$url_region)->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
					} else {
						$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Delivery')->where('region','=',$order->region)->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
					}
				}elseif($type == "relocation") {
					if($url_region !=''){
						$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Relocation')->where('region','=',$url_region)->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
					} else {
						$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Relocation')->where('region','=',$order->region)->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
					}
				}
				
			}else{
			    $vendors = array();                  
				if($order->subcategory != '' && $order->subnode != ''){
					if($url_region !=''){
						$delivery_boys = \DB::table('service_deliveryboy')->select('id','username')->where('region','=',$url_region)->where('status','=',1)->whereRaw("find_in_set(".$order->subnode.",subcat_id)")->get();
					} else {
						$delivery_boys = \DB::table('service_deliveryboy')->select('id','username')->where('region','=',$order->region)->where('status','=',1)->whereRaw("find_in_set(".$order->subnode.",subcat_id)")->get();
					}
				}else{
					if($url_region !=''){
						$delivery_boys = \DB::table('service_deliveryboy')->select('id','username')->where('status','=',1)->where('region','=',$url_region)->whereRaw("find_in_set(".$order->subcategory.",subcat_id)")->get();
					} else {
						$delivery_boys = \DB::table('service_deliveryboy')->select('id','username')->where('status','=',1)->where('region','=',$order->region)->whereRaw("find_in_set(".$order->subcategory.",subcat_id)")->get();
					}
				}				
		  		//$delivery_boys = \DB::table('service_deliveryboy')->select('id','username')->get();
			}
			
			if(count($vendors)>0){
				$_vendors .= '<option value=""> </option>';
				foreach ($vendors as $key => $vendor){
					$_vendors .= '<option value="'.$vendor->id.'">'.$vendor->store_name.'</option>';
				}
				$_vendor .='<select name="vendors" rows="5" id="vendors" class="select1 ">'.$_vendors.'</select>';
			} else{
				$_vendor .='';
			}
		
			$deliveryboys .= '<option value=""> </option>';
			foreach ($delivery_boys as $key => $delivery_boy){
				$deliveryboys .= '<option value="'.$delivery_boy->id.'">'.$delivery_boy->username.'</option>';
			}
			
			if($type != "service"){
				if($order->vendor ==''){
				  	$disabled ='disabled=""';
				} else {
				   	$disabled ='';
				}			
		
				if($order->service_status == 0){
						$action .='<i data-toggle="tooltip" title="Accept your order" class="icon-checkmark-circle2 fn_accept" aria-hidden="true" style="cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->id.'" class="id" /><select name="delivery_boy" rows="5" id="delivery_boy" class="select1 "'.$disabled.'>'.$deliveryboys.'</select>';
				} elseif($order->service_status == 1){
					$action .='<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->id.'" class="id" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 "'.$disabled.'>'.$deliveryboys.'</select>';
				}elseif($order->service_status == 3){
					$action .='<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->id.'" class="id" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 "'.$disabled.'>'.$deliveryboys.'</select>';
				}
			
			} else {
				if($order->service_status == 0){
					$action .='<i data-toggle="tooltip" title="Accept your order" class="icon-checkmark-circle2 fn_accept" aria-hidden="true" style="cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->id.'" class="id" /><select name="delivery_boy" rows="5" id="delivery_boy" class="select1 ">'.$deliveryboys.'</select>';
				} elseif($order->service_status == 1){
					$action .='<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->id.'" class="id" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 ">'.$deliveryboys.'</select>';
				}elseif($order->service_status == 3){
					$action .='<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->id.'" class="id" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 ">'.$deliveryboys.'</select>';
				}	
			}
			if($order->dboy_id != ""){
				$action .='<a href="" id="orderamt" data-toggle="modal"  data-dismiss="modal" data-target="#order-update" ><span class="txt-primary">Order Finished</span></a>';
			}
				
			$catname = \DB::table('service_categories')->select('cat_name')->where('id','=',$order->category)->first();  
			$subcatname = \DB::table('service_categories')->select('cat_name')->where('id','=',$order->subcategory)->first();       $subnode = \DB::table('service_categories')->select('cat_name')->where('id','=',$order->subnode)->first();
				
				
			$txt1 = $url;
			$file = $order->file;
			$folder_path = $txt1.$file;
			if($order->file !=''){
				$file = '<a href="' . $folder_path . '" download>Download</a>';
			}

			
			if($order->service_status == 1 ){
				$status ='<span class="label status label-success">Accepted Service</span>';
			} elseif($order->service_status == 2){
				$status ='<span class="label status label-primary">Rejected Service</span>';
			} elseif($order->service_status == 3){
				$status ='<span class="label status label-info">Assigned</span>';
			} else {
				$status ='<span class="label label-warning status">'. trans("core.pending") .'</span>';
			}
			
			
			if($order->subscription_status == 1 ){
				$sub_status  = "Paid";
			}else{
				$sub_status  = "Unpaid";
			}
			
			$order_in .=$order->order_instruction;
			$order_in .='<br><a href="" id="orderinst" data-toggle="modal"  data-dismiss="modal" data-target="#order-inst" ><span class="txt-primary">Order Innstruction</span></a>';
				
			
			$html .= '<tr><td width="50"><input type="checkbox" class="ids" name="ids[]" value="'.$order->id.'" /></td><td width="50">#'.$order->orderid.'</td><td width="50">'.$order->cust_id.'</td><td width="50">'.$order->first_name.'</td><td width="50">'.$order->c_phone_number.'</td><td width="50" style="width="50" style="width:100px; white-space:pre-line;">'.$order->location.'</td><td width="50" style="width="50" style="width:100px; white-space:pre-line;">'.$order->to_location.'</td><td width="50">'.$order->comments.'</td><td width="50">'.$file.'</td><td width="50">'.$order->date.'</td><td width="50">'.$subcatname->cat_name.'</td><td width="50">'.$subnode->cat_name.'</td><td width="50">'.$_vendor.'</td><td width="50">'.$order->vendor_name.'</td><td width="50">'.$action.'</td><td width="50">'.$status.'</td><td width="50">'.$order->dboy_name.'</td><td width="50">'.$catname->cat_name.'</td><td width="50">'.$order_in.'</td><td width="50">'.$order->service_charge.'</td><td width="50">'.$order->delivery_type.'</td><td width="50">'.$order->type.'</td><td width="50">'.$order->email.'</td><td width="50">'.$order->description.'</td><td width="50">'.$sub_status.'</td><input type="hidden" class="phone" value="'.$order->c_phone_number.'" /><input type="hidden" class="location" value="'.$order->location.'" /><input type="hidden" class="type" value="'.$order->type.'"/><input type="hidden" class="subcategory" value="'.$order->subcategory.'" /><input type="hidden" class="subnode" value="'.$order->subnode.'"/><input type="hidden" class="to_location" value="'.$order->to_location.'"/><input type="hidden" class="type" value="'.$order->type.'"/><input type="hidden" class="pin_code" value="'.$order->pin_code.'"/><input type="hidden" class="to_pin_code" value="'.$order->to_pin_code.'"/><input type="hidden" class="cat_name" value="'.$catname->cat_name.'"/><input type="hidden" class="subcat_name" value="'.$subcatname->cat_name.'" /><input type="hidden" class="subnodecat_name" value="'.$subnode->cat_name.'" /></tr>';
		  }
		  return $html;
		} else {
			$html .= '<tr class="odd"><td valign="top" colspan="21" class="dataTables_empty">No data available in table</td></tr>';
			return $html;
		}
	}			

}
  
<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Pushnotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class PushnotificationController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'pushnotification';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Pushnotification();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'pushnotification',
			'return'	=> self::returnUrl()
			
		);
		
	}
	

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
       
	   $region = \DB::table('region')->select('*')->get();
	  // print_r($region);   exit;
	   $this->data['region'] 	=	$region;
		
		return view('pushnotification.index',$this->data);
	}	


	public function iospushnotification($app_api,$mobile_token,$message,$app_name,$file_name)
	{	 
		 
	//	define( 'API_ACCESS_KEY3', $app_api );
		
        if($file_name != ''){
		$image =  url('/uploads/notification/'.$file_name.'');  
		}else {
		$image =  ''; 	
		}		
		
		$registrationIds = $mobile_token;
		//print_r($mobile_token);  exit;

		// prep the bundle
		$msg = array
		(
			'message' 	=> 'Image',
			'title'		=> 'Delivery Star',			
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon',
			'mediaUrl' =>   $image,
			
		);
		
		$notification = array
		(
			'body' 		=> $message,
			'title'		=> 'Delivery Star',			
		);

		$fields = array
		(
			'registration_ids' 	=> $mobile_token,
			'notification'		=> $notification,
			"content_available" => true,
            "mutable_content"   => true,
			'data'				=> $msg,
			"apns" => array( 
				 "alert" => array( 
					 "title" => "Delivery Star"
				 )
			)
		);
		 
		$headers = array
		(
			'Authorization: key=' . $app_api,
			'Content-Type: application/json'
		);
	
		//print_r(json_encode($fields));   exit;
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );

		//Decoding json from result 
		$res = json_decode($result);
		
		/*echo "<pre>";
		echo($app_api)."<br>";
		echo($app_name)."<br>";
		echo($message)."<br>";
		echo($mobile_token)."<br>";
		print_r($result);
		print_r($res);*/
		
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;		
		
	}	
	
	public function pushnotification($app_api,$mobile_token,$message,$app_name,$file_name)
	{
		
		if($file_name != ''){
		$image =  url('/uploads/notification/'.$file_name.'');  
		}else {
		$image =  url('/abserve/images/logo456.png'); 	
		}
		//define( 'API_ACCESS_KEY', $app_api );
		
		$registrationIds = $mobile_token;
		//print_r($registrationIds);   exit;
	
		// prep the bundle
		$msg = array
		(
			'message' 	=> 'dstar',
			'title'		=> 'Message from '.$app_name,
			/*'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'=> 'Ticker text here...Ticker text here...Ticker text here',*/
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon',
			'mediaUrl' =>   $image,
			'body' 		=> $message,
		);


         $notification = array
		(
			'body' 		=> $message,
			'title'		=> 'Delivery Star'
		);
		
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			//'notification'		=> $notification,
			'data'				=> $msg,
		);

		
		$headers = array
		(
			'Authorization: key=' . $app_api,
			'Content-Type: application/json'
		);
		
		//print_r(json_encode($fields));   exit;
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS,json_encode($fields) );
		$result = curl_exec($ch );
		curl_close( $ch );

		//Decoding json from result 
		$res = json_decode($result);
	
		//print_r($result);   exit;
		
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;		
		//echo $flag;  exit;
	}
	
	public function appapimethod( $value = ''){   

		$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();
		return $appapi[0];
	}


	function postMessage( Request $request)
	{
		
	//print_r($request->all());  //exit;
		
		if(!is_null(Input::file('image')))
		{
		
			$dir	= 'uploads/notification/';
			$directory	= base_path().'/uploads/notification/';
			if (!(\File::exists($directory))) {
				$destinationPath = \File::makeDirectory($directory, 0777, true);
			}
			$destinationPath = $directory;
		
				$org_name	= $_FILES['image']['name']/*[$key]*/;
				$ext		= pathinfo($org_name, PATHINFO_EXTENSION);
				$file_name	= time()."-".rand(10,100)./*.$key.*/'.'.$ext;  
				$file_tmp	= $_FILES['image']['tmp_name']/*[$key]*/;
				if($file_name !=''){
					$upload = move_uploaded_file($file_tmp,$destinationPath.$file_name);
					$files .= /*$dir.*/$file_name;
				}		
		}
				
		$file_name =  $files;  //exit;
		
		
		$val['device'] = $request->status;
		$val['image'] =  $dir.$file_name;
		$val['message'] = $request->message;
		$val['region'] = $request->region;
		$val['date'] = date("Y-m-d");
		$val['time'] =  date("H:i:s");
		
		//print_r($val);   
		
		$ins=\DB::table('push_notification')->insert($val);
		
		if($request->region == "all"){
		$customers = \DB::table('tb_users')->select('*')->where('group_id','=',4)->where('mobile_token','!=','')->where('device','=',$request->status)->get();
		}else{
		$customers = \DB::table('tb_users')->select('*')->where('group_id','=',4)->where('mobile_token','!=','')->where('device','=',$request->status)->where('region','=',$request->region)->get();
		}
	//print_r($customers); // exit;
	//exit;
	//$customers = \DB::table('tb_users')->select('*')->where('group_id','=',4)->where('mobile_token','!=','')->where('device','=',$request->status)->where('region','=',$request->region)->get();
	//$device = $request->status;
		
	//$customers =	\DB::select("SELECT * FROM `tb_users` WHERE `group_id`='4' AND `mobile_token` !='' AND `device`= '".$device."'  AND (`phone_number`='9894764838' OR `phone_number`='9094621042' OR `phone_number`='8074002859' OR `phone_number`='9487410636')");
		
		//print_r($customers);  exit;
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {   
		   
		if($request->status == 'ios'){
		   
		   $mobile_token_value = array();
		   foreach($customers as $customer){
	 
				$mobile_token_value[] = $customer->mobile_token;
				$message = $request->message;
				$appapi_details	= $this->appapimethod(4);
				$app_name		= $appapi_details->app_name;
				$app_api 		= $appapi_details->api;
			}
				
			//$mobile_token = '"\"'.implode('","', $mobile_token_value).'"\"';
			
			$mobile_tokens = array();
			  $k = count($mobile_token_value);
			
			if($k > 1000){
				$mobile_tokens = array_chunk($mobile_token_value, 1000);
				$j = count($mobile_tokens);
				
				for($i = 0; $i < $j; $i++){
					
					$this->iospushnotification($app_api,$mobile_tokens[$i],$message,$app_name,$file_name);
				}
				
			} else {
				
				$this->iospushnotification($app_api,$mobile_token_value,$message,$app_name,$file_name);
			}
			
		} else {
			
		    $mobile_token_value = array();
		    foreach($customers as $customer){
			
			 	$mobile_token_value[] = $customer->mobile_token;  
			    $message = $request->message;   
				$appapi_details	= $this->appapimethod(1);
			 	$app_name		= $appapi_details->app_name; 
				$app_api 		= $appapi_details->api;    
				
		  	}
			
			//echo '<pre>';
			//$mobile_token = '"\"'.implode('","', $mobile_token_value).'"\"';
			//$mobile_token_value = implode('","',$mobile_token);
			 $mobile_tokens = array();
			 $k = count($mobile_token_value);
			 //print_r($k);
			if($k > 1000){
				$mobile_tokens = array_chunk($mobile_token_value, 1000);
				$j = count($mobile_tokens);
				//print_r($j);
				for($i = 0; $i < $j; $i++){
					//print_r($mobile_tokens[$i]);
					$this->pushnotification($app_api,$mobile_tokens[$i],$message,$app_name,$file_name);
				}
				
			} else {
				//$j = count($mobile_token_value);
				//print_r($j);
				$this->pushnotification($app_api,$mobile_token_value,$message,$app_name,$file_name);
			}
			
			//exit;
			//$this->pushnotification($app_api,$mobile_token_value,$message,$app_name,$file_name);	
	  	}
		return Redirect::to('pushnotification/index')->with('messagetext','Successfully sent notification')->with('msgstatus','success');
		
		}else{			
			return Redirect::to('pushnotification/index')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
				->withErrors($validator)->withInput();
		}
	}
	
	
}
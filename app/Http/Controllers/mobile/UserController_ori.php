<?php namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use App\Models\Partners;
use App\Models\Deliveryboy;
use App\Models\Customers;
use App\Models\Restaurant;
use Socialize;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;

use Auth, DB, Crypt, DateTime, Session, Authorizer,File; 

class UserController extends Controller {

	
	protected $layout = "layouts.main";

	public function __construct() {
		parent::__construct();
		$free_boys = [];
	} 
	public function pushnotification2($app_api,$mobile_token,$message,$app_name)
	{	 
		define( 'API_ACCESS_KEY2', $app_api );
		/*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

		$registrationIds = [$mobile_token];

		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> 'Message from'.$app_name,
			/*'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'=> 'Ticker text here...Ticker text here...Ticker text here',*/
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);

		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY2,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
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
		echo($mobile_token)."<br>";
		print_r($result);
		print_r($res);*/
		
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;
		
		//if success is 1 means message is sent 
		/*if($flag == 1){
			switch($type)
				{
					case 'order insert':	
					echo "Food app insert";
					$sUpdate = \DB::update("UPDATE `tracstra_chats` SET `status`= '1' WHERE `status`= '0' AND `id`=".$id);
					break;
				}
		}else{
			
		}*/
	}
	public function pushnotification($app_api,$mobile_token,$message,$app_name)
	{	 
		 
		define( 'API_ACCESS_KEY', $app_api );
		/*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

		

		$registrationIds = [$mobile_token];

		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> 'Message from'.$app_name,
			/*'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'=> 'Ticker text here...Ticker text here...Ticker text here',*/
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);

		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
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
		echo($mobile_token)."<br>";
		print_r($result);
		print_r($res);
		*/
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;
		
		//if success is 1 means message is sent 
		/*if($flag == 1){
			switch($type)
				{
					case 'order insert':	
					echo "Food app insert";
					$sUpdate = \DB::update("UPDATE `tracstra_chats` SET `status`= '1' WHERE `status`= '0' AND `id`=".$id);
					break;
				}
		}else{
			
		}*/
	}

	public function appapimethod( $value = '')
	{   

		$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();

		return $appapi[0];
	}

	public function userapimethod($userid = '',$table)
	{

		$userapi = \DB::table($table)->select('mobile_token')->where('id','=',$userid)->get();

		return $userapi[0]->mobile_token;
	}

	public function assign_rand_value($num) {
	    // accepts 1 - 36
	    switch($num) {
	        case "1"  : $rand_value = "a"; break;
	        case "2"  : $rand_value = "b"; break;
	        case "3"  : $rand_value = "c"; break;
	        case "4"  : $rand_value = "d"; break;
	        case "5"  : $rand_value = "e"; break;
	        case "6"  : $rand_value = "f"; break;
	        case "7"  : $rand_value = "g"; break;
	        case "8"  : $rand_value = "h"; break;
	        case "9"  : $rand_value = "i"; break;
	        case "10" : $rand_value = "j"; break;
	        case "11" : $rand_value = "k"; break;
	        case "12" : $rand_value = "l"; break;
	        case "13" : $rand_value = "m"; break;
	        case "14" : $rand_value = "n"; break;
	        case "15" : $rand_value = "o"; break;
	        case "16" : $rand_value = "p"; break;
	        case "17" : $rand_value = "q"; break;
	        case "18" : $rand_value = "r"; break;
	        case "19" : $rand_value = "s"; break;
	        case "20" : $rand_value = "t"; break;
	        case "21" : $rand_value = "u"; break;
	        case "22" : $rand_value = "v"; break;
	        case "23" : $rand_value = "w"; break;
	        case "24" : $rand_value = "x"; break;
	        case "25" : $rand_value = "y"; break;
	        case "26" : $rand_value = "z"; break;
	        case "27" : $rand_value = "0"; break;
	        case "28" : $rand_value = "1"; break;
	        case "29" : $rand_value = "2"; break;
	        case "30" : $rand_value = "3"; break;
	        case "31" : $rand_value = "4"; break;
	        case "32" : $rand_value = "5"; break;
	        case "33" : $rand_value = "6"; break;
	        case "34" : $rand_value = "7"; break;
	        case "35" : $rand_value = "8"; break;
	        case "36" : $rand_value = "9"; break;
	    }
	    return $rand_value;
	}

	public function get_rand_alphanumeric($length) {
	    if ($length>0) {
	        $rand_id="";
	        for ($i=1; $i<=$length; $i++) {
	            mt_srand((double)microtime() * 1000000);
	            $num = mt_rand(1,36);
	            $rand_id .= $this->assign_rand_value($num);
	        }
	    }
	    return $rand_id;
	}

/*	public function getDistance($addressFrom, $addressTo, $unit){
		//Change address format
		$formattedAddrFrom 	= str_replace(' ','+',$addressFrom);
		$formattedAddrTo 	= str_replace(' ','+',$addressTo);

		//Send request and receive json data
		$geocodeFrom 	= file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false');
		$outputFrom 	= json_decode($geocodeFrom);
		$geocodeTo 		= file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false');
		$outputTo 		= json_decode($geocodeTo);

		//Get latitude and longitude from geo data
		$latitudeFrom 	= $outputFrom->results[0]->geometry->location->lat;
		$longitudeFrom 	= $outputFrom->results[0]->geometry->location->lng;
		$latitudeTo 	= $outputTo->results[0]->geometry->location->lat;
		$longitudeTo 	= $outputTo->results[0]->geometry->location->lng;

		//Calculate distance from latitude and longitude
		$theta 	= $longitudeFrom - $longitudeTo;
		$dist 	= sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
		$dist 	= acos($dist);
		$dist 	= rad2deg($dist);
		$miles 	= $dist * 60 * 1.1515;
		$unit 	= strtoupper($unit);
		if ($unit == "K") {
			return ($miles * 1.609344).' km';
		} else if ($unit == "N") {
			return ($miles * 0.8684).' nm';
		} else {
			return $miles.' mi';
		}
	}
*/
	public function getDistance($lat1, $lon1, $lat2, $lon2) {

    $theta = $lon1 - $lon2;

    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));

    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $kilometers = $miles * 1.609344;


    return $kilometers;
}

	public function postAddresscheck(Request $request){

		$address = \DB::table('abserve_restaurants')->select('*')->where('id','=',$_REQUEST['res_id'])->get('id');
		//$delivery = \DB::table('abserve_admin_settings')->select('*')->first();
		$data = $this->getDistance($address[0]->latitude,$address[0]->longitude,$_REQUEST['lat'],$_REQUEST['lang'],"K");

		$delivery_kmv =2;
		$delivery_charge =10;
		//echo '<pre>';var_dump($data);
		if($data > 5){
			$response['delivery_charge']=0;
			$response['message'] = "Your address is far away from this Restaurant";
			echo json_encode($response);exit;
		}else{
			if($data <= $delivery_kmv){
				$response['delivery_charge']=$delivery_charge;
			}else{ 
				$perkm =$delivery_charge/$delivery_kmv;
				$kilom=substr($data, 0, 1);
				$del_charge =$kilom * $perkm;
				$response['delivery_charge']=round($del_charge); 
			}
			$response['message'] = "Success";
			echo json_encode($response); exit;
		}
	}	
	public function postPartners(){
		$response = array();
		$partners =	\DB::table('abserve_partners')->select('id', 'username as name')->get();

		$response['partners'] = $partners;
		echo json_encode($response);exit;
	}

	public function postPartnerverify(){
		$_REQUEST['user_id'] 	= str_replace('"','', $_REQUEST['user_id']);
		$_REQUEST['status'] 	= str_replace('"','', $_REQUEST['status']);

		$user =	\DB::table('abserve_partners')->where('id', $_REQUEST['user_id'] )->update(array('phone_verified' => $_REQUEST['status'],'active'=>1));
	}

	public function postDeliveryverify(){
		$_REQUEST['user_id'] 	= str_replace('"','', $_REQUEST['user_id']);
		$_REQUEST['status'] 	= str_replace('"','', $_REQUEST['status']);

		$user =	\DB::table('abserve_deliveryboys')->where('id', $_REQUEST['user_id'] )->update(array('phone_verified' => $_REQUEST['status'],'active'=>1));
	}

	public function postCustomerverify(){
		$_REQUEST['user_id'] 	= str_replace('"','', $_REQUEST['user_id']);
		$_REQUEST['status'] 	= str_replace('"','', $_REQUEST['status']);

		$user =	\DB::table('tb_users')->where('id', $_REQUEST['user_id'] )->update(array('phone_verified' => $_REQUEST['status'],'active'=>1));
	}


	public function postCreate( Request $request){
	
		$rules = array(
			'group_id'       =>'required',
			//'username'       =>'required',
			'firstname'      =>'required|alpha_num|min:2',
			'lastname'       =>'required|alpha_num|min:2',
			'email'          =>'required|email|unique:tb_users',
			'password'       =>'required',
			'phone_number'	 =>'required|numeric|unique:tb_users',
			//'phone_code'	 =>'required|numeric',
			//'phone_otp'      =>'required|numeric',
			);	
         
		
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->username   = $request->firstname;
			$authen->first_name = $request->firstname;
			$authen->last_name  = $request->lastname;
			$authen->phone_number = $request->phone_number;
			//$authen->phone_code  = $request->phone_code;
			//$authen->phone_otp   = $request->phone_otp;
			$authen->phone_verified   = 1;
			$authen->email = trim($request->email);
			$authen->activation = $code;
			$authen->group_id = $request->group_id;
			if($request->address!=''){
			$authen->address  =$request->address;
		    }else{
		    $authen->address  ='';	
		    }
			$authen->password = \Hash::make($request->password);
			if($request->group_id == 4  || $request->group_id == 0){
				if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			} else if($request->group_id == 3){
				$authen->active = '0';
			}
			$authen->save();
			
			$data = array(
				'username'  => $request->firstname ,
				'firstname'	=> $request->firstname ,
				'lastname'	=> $request->lastname,
				'email'		=> $request->email ,
				'phonenumber'=> $request->phone_number ,
				'password'	=> $request->password,
				'code'		=> $code,
				//'phone_code'=>$request->phone_code ,
				'address'   =>$request->address,
 				
			);
			if($request->address!=""){
				$val['address']=$request->address;
				$val['user_id']=$authen->id;
				$val['lat']=$request->lat;
				$val['address_type']='home';
				$val['lang']=$request->lang;
				$val['building']='';
				$val['landmark']='';

			}
			$ins=\DB::table('abserve_user_address')->insert($val);
			if($request->group_id == '4'|| $request->group_id == 0 ) {

				if(CNF_ACTIVATION == 'confirmation') { 
					$to = $request->email;
					$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
					$message = view('user.emails.registration', $data);
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
						mail($to, $subject, $message, $headers);	
					
					$response["id"] 		= "1";
					$response['message'] = "Thanks for registering! . Please check your inbox and follow activation link";
					echo json_encode($response);exit;

									
				} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response['message'] = "Thanks for registering! . We will validate you account before your account active";
					echo json_encode($response);exit;
				} else {
					$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
	   			 	$response["id"] 		  = "3";
	   			 	$response["user_details"] = $user;
					$response['message'] = "Thanks for registering! . Your account is active now ";
					echo json_encode($response);exit;
	   			}
			} else if($request->group_id == 3)	 {
				/*$notify = new Notification;
				$notify->userid = 1;
				$notify->url = \URL::to('/partneractivation/update/'.$authen->id);
				$notify->title = $request->firstname.' '.$request->lastname. " was signedup as partner.Waiting for Admin activation";
				$notify->created = date('Y-m-d H:i:s');
				
				$notify->is_read = 0;
				$notify->save();
*/
				$response["id"] 		= "3";
				$response['message'] = "Thanks for registering! . site administrator will contact you soon";
				echo json_encode($response);exit;
			}
			$user = \DB::table('tb_users')->select('id')->where('phone_number','=',$_REQUEST['phonenumber'])->get();

			foreach ($user as $key) {
				$userid = $key->id;
			}
			$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
			\DB::table('tb_users')->where('id','=',$userid)->update(['mobile_token'=>$_REQUEST['mobile_token'],'device'=>$_REQUEST['device']]);
			$response["id"] 		= "3";
			$response["message"] 	= "Your account is active now";
			$response['user_details']	= $user;
			echo json_encode($response);exit;    
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			//$response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['firstname'])){
					$response['message'] = $error['firstname'][0];
				} else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				}
			}

			
	  }
	   echo json_encode($response); exit;
	}

	public function postCreateold( Request $request){
	  if($request->group_id==4){
		$rules = array(
			'group_id'       =>'required',
			//'username'       =>'required',
			'firstname'      =>'required|alpha_num|min:2',
			'lastname'       =>'required|alpha_num|min:2',
			'email'          =>'required|email|unique:tb_users',
			'password'       =>'required',
			'phone_number'	 =>'required|numeric|unique:tb_users',
			//'phone_code'	 =>'required|numeric',
			//'phone_otp'      =>'required|numeric',
			);	
         
		
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->username   = $request->firstname;
			$authen->first_name = $request->firstname;
			$authen->last_name  = $request->lastname;
			$authen->phone_number = $request->phone_number;
			//$authen->phone_code  = $request->phone_code;
			//$authen->phone_otp   = $request->phone_otp;
			$authen->phone_verified   = 1;
			$authen->email = trim($request->email);
			$authen->activation = $code;
			$authen->group_id = $request->group_id;
			if($request->address!=''){
			$authen->address  =$request->address;
		    }else{
		    $authen->address  ='';	
		    }
			$authen->password = \Hash::make($request->password);
			if($request->group_id == 4  || $request->group_id == 0){
				if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			} else if($request->group_id == 3){
				$authen->active = '0';
			}
			$authen->save();
			
			$data = array(
				'username'  => $request->firstname ,
				'firstname'	=> $request->firstname ,
				'lastname'	=> $request->lastname,
				'email'		=> $request->email ,
				'phonenumber'=> $request->phone_number ,
				'password'	=> $request->password,
				'code'		=> $code,
				//'phone_code'=>$request->phone_code ,
				'address'   =>$request->address,
 				
			);
			if($request->address!=""){
				$val['address']=$request->address;
				$val['user_id']=$authen->id;
				$val['lat']='';
				$val['address_type']='home';
				$val['lang']='';
				$val['building']='';
				$val['landmark']='';

			}
			$ins=\DB::table('abserve_user_address')->insert($val);
			if($request->group_id == '4'|| $request->group_id == 0 ) {

				if(CNF_ACTIVATION == 'confirmation') { 
					$to = $request->email;
					$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
					$message = view('user.emails.registration', $data);
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
						mail($to, $subject, $message, $headers);	
					
					$response["id"] 		= "1";
					$response['message'] = "Thanks for registering! . Please check your inbox and follow activation link";
					echo json_encode($response);exit;

									
				} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response['message'] = "Thanks for registering! . We will validate you account before your account active";
					echo json_encode($response);exit;
				} else {
					$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
	   			 	$response["id"] 		  = "3";
	   			 	$response["user_details"] = $user;
					$response['message'] = "Thanks for registering! . Your account is active now ";
					echo json_encode($response);exit;
	   			}
			} else if($request->group_id == 3)	 {
				/*$notify = new Notification;
				$notify->userid = 1;
				$notify->url = \URL::to('/partneractivation/update/'.$authen->id);
				$notify->title = $request->firstname.' '.$request->lastname. " was signedup as partner.Waiting for Admin activation";
				$notify->created = date('Y-m-d H:i:s');
				
				$notify->is_read = 0;
				$notify->save();*/

				$response["id"] 		= "3";
				$response['message'] = "Thanks for registering! . site administrator will contact you soon";
				echo json_encode($response);exit;
			}
			$user = \DB::table('tb_users')->select('id')->where('phone_number','=',$_REQUEST['phonenumber'])->get();

			foreach ($user as $key) {
				$userid = $key->id;
			}
			$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
			\DB::table('tb_users')->where('id','=',$userid)->update(['mobile_token'=>$_REQUEST['mobile_token']]);
			$response["id"] 		= "3";
			$response["message"] 	= "Your account is active now";
			$response['user_details']	= $user;
			echo json_encode($response);exit;    
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			//$response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['firstname'])){
					$response['message'] = $error['firstname'][0];
				} else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				}
			}

			
	  }
	   echo json_encode($response); exit;

	}else{
		$rules = array(
			'group_id'       =>'required',
			//'username'       =>'required',
			'firstname'      =>'required|alpha_num|min:2',
			'lastname'       =>'required|alpha_num|min:2',
			'email'          =>'required|email|unique:tb_users',
			'password'       =>'required',
			'phone_number'	 =>'required|numeric|unique:tb_users',
			//'phone_code'	 =>'required|numeric',
			//'phone_otp'      =>'required|numeric',
			);	
         
		
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->username   = $request->firstname;
			$authen->first_name = $request->firstname;
			$authen->last_name  = $request->lastname;
			$authen->phone_number = $request->phone_number;
			//$authen->phone_code  = $request->phone_code;
			//$authen->phone_otp   = $request->phone_otp;
			$authen->phone_verified   = 1;
			$authen->email = trim($request->email);
			$authen->activation = $code;
			$authen->group_id = $request->group_id;
			if($request->address!=''){
			$authen->address  =$request->address;
		    }else{
		    $authen->address  ='';	
		    }
			$authen->password = \Hash::make($request->password);
			if($request->group_id == 4  || $request->group_id == 0){
				if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			} else if($request->group_id == 3){
				$authen->active = '0';
			}
			$authen->save();
			
			$data = array(
				'username'  => $request->firstname ,
				'firstname'	=> $request->firstname ,
				'lastname'	=> $request->lastname,
				'email'		=> $request->email ,
				'phonenumber'=> $request->phone_number ,
				'password'	=> $request->password,
				'code'		=> $code,
				//'phone_code'=>$request->phone_code ,
				'address'   =>$request->address,
 				
			);
			if($request->address!=""){
				$val['address']=$request->address;
				$val['user_id']=$authen->id;
				$val['lat']='';
				$val['address_type']='home';
				$val['lang']='';
				$val['building']='';
				$val['landmark']='';

			}
			$ins=\DB::table('abserve_user_address')->insert($val);
			if($request->group_id == '4'|| $request->group_id == 0 ) {

				if(CNF_ACTIVATION == 'confirmation') { 
					$to = $request->email;
					$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
					$message = view('user.emails.registration', $data);
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
						mail($to, $subject, $message, $headers);	
					
					$response["id"] 		= "1";
					$response['message'] = "Thanks for registering! . Please check your inbox and follow activation link";
					echo json_encode($response);exit;

									
				} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response['message'] = "Thanks for registering! . We will validate you account before your account active";
					echo json_encode($response);exit;
				} else {
					$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
	   			 	$response["id"] 		  = "3";
	   			 	$response["user_details"] = $user;
					$response['message'] = "Thanks for registering! . Your account is active now ";
					echo json_encode($response);exit;
	   			}
			} else if($request->group_id == 3)	 {
				/*$notify = new Notification;
				$notify->userid = 1;
				$notify->url = \URL::to('/partneractivation/update/'.$authen->id);
				$notify->title = $request->firstname.' '.$request->lastname. " was signedup as partner.Waiting for Admin activation";
				$notify->created = date('Y-m-d H:i:s');
				
				$notify->is_read = 0;
				$notify->save();*/

				$response["id"] 		= "3";
				$response['message'] = "Thanks for registering! . site administrator will contact you soon";
				echo json_encode($response);exit;
			}
			$user = \DB::table('tb_users')->select('id')->where('phone_number','=',$_REQUEST['phonenumber'])->get();

			foreach ($user as $key) {
				$userid = $key->id;
			}
			$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
			\DB::table('tb_users')->where('id','=',$userid)->update(['mobile_token'=>$_REQUEST['mobile_token']]);
			$response["id"] 		= "3";
			$response["message"] 	= "Your account is active now";
			$response['user_details']	= $user;
			echo json_encode($response);exit;    
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			//$response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['firstname'])){
					$response['message'] = $error['firstname'][0];
				} else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				}
			}

			
	  }
	   echo json_encode($response); exit;


	}
	}


	public function postCreate1( Request $request){
	
		$rules = array(
			'group_id'       =>'required',
			//'username'       =>'required',
			'firstname'      =>'required|alpha_num|min:2',
			'lastname'       =>'required|alpha_num|min:2',
			'email'          =>'required',
			'password'       =>'required',
			'phone_number'	 =>'required',
			//'phone_code'	 =>'required|numeric',
			//'phone_otp'      =>'required|numeric',
			);	
         
		
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->username   = $request->firstname;
			$authen->first_name = $request->firstname;
			$authen->last_name  = $request->lastname;
			$authen->phone_number = $request->phone_number;
			//$authen->phone_code  = $request->phone_code;
			//$authen->phone_otp   = $request->phone_otp;
			$authen->phone_verified   = 1;
			$authen->email = trim($request->email);
			$authen->activation = $code;
			$authen->group_id = $request->group_id;
			if($request->address!=''){
			$authen->address  =$request->address;
		    }else{
		    $authen->address  ='';	
		    }
			$authen->password = \Hash::make($request->password);
			if($request->group_id == 4  || $request->group_id == 0){
				$authen->save();
				$data = array(
				'username'  => $request->firstname ,
				'firstname'	=> $request->firstname ,
				'lastname'	=> $request->lastname,
				'email'		=> $request->email ,
				'phonenumber'=> $request->phone_number ,
				'password'	=> $request->password,
				'code'		=> $code,
				//'phone_code'=>$request->phone_code ,
				'address'   =>$request->address,
 				
			);
				if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			} else if($request->group_id == 3){
				$authen->active = '0';
				$authen->save();
				$data = array(
				'username'  => $request->firstname ,
				'firstname'	=> $request->firstname ,
				'lastname'	=> $request->lastname,
				'email'		=> $request->email ,
				'phonenumber'=> $request->phone_number ,
				'password'	=> $request->password,
				'code'		=> $code,
				//'phone_code'=>$request->phone_code ,
				'address'   =>$request->address,
 				
			);

			}
			
			
			
			if($request->address!=""){
				$val['address']=$request->address;
				$val['user_id']=$authen->id;
				$val['lat']='';
				$val['address_type']='home';
				$val['lang']='';
				$val['building']='';
				$val['landmark']='';

			}
			$ins=\DB::table('abserve_user_address')->insert($val);
			if($request->group_id == '4'|| $request->group_id == 0 ) {

				if(CNF_ACTIVATION == 'confirmation') { 
					$to = $request->email;
					$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
					$message = view('user.emails.registration', $data);
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
						mail($to, $subject, $message, $headers);	
					
					$response["id"] 		= "1";
					$response['message'] = "Thanks for registering! . Please check your inbox and follow activation link";
					echo json_encode($response);exit;

									
				} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response['message'] = "Thanks for registering! . We will validate you account before your account active";
					echo json_encode($response);exit;
				} else {
					$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
	   			 	$response["id"] 		  = "3";
	   			 	$response["user_details"] = $user;
					$response['message'] = "Thanks for registering! . Your account is active now ";
					echo json_encode($response);exit;
	   			}
			} else if($request->group_id == 3)	 {
				//$notify = new Notification;
				/*$notify->userid = 1;
				$notify->url = \URL::to('/partneractivation/update/'.$authen->id);
				$notify->title = $request->firstname.' '.$request->lastname. " was signedup as partner.Waiting for Admin activation";
				$notify->created = date('Y-m-d H:i:s');
				
				$notify->is_read = 0;
				$notify->save();*/

				$response["id"] 		= "3";
				$response['message'] = "Thanks for registering! . site administrator will contact you soon";
				echo json_encode($response);exit;
			}
			$user = \DB::table('tb_users')->select('id')->where('phone_number','=',$_REQUEST['phonenumber'])->get();

			foreach ($user as $key) {
				$userid = $key->id;
			}
			$user = \DB::table('tb_users')->select('id','username','phone_number','email')->where('id','=',$authen->id)->get();
			\DB::table('tb_users')->where('id','=',$userid)->update(['mobile_token'=>$_REQUEST['mobile_token']]);
			$response["id"] 		= "3";
			$response["message"] 	= "Your account is active now";
			$response['user_details']	= $user;
			echo json_encode($response);exit;    
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			//$response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['firstname'])){
					$response['message'] = $error['firstname'][0];
				} else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				}
			}

			
	  }
	   echo json_encode($response); exit;
	}


	public function postSocialcreate(){
		$_REQUEST	= str_replace('"','', $_REQUEST);
		$rules		= array(
			'first_name'		=>'required',
			'email'			=>'required|email|unique:tb_users',
			//'phone_number'	=>'required|unique:tb_users',
			//'password'		=>'required',
		);	

		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$code = rand(10000,10000000);
			$authen 				= new User;
			$authen->first_name 	= $_REQUEST['first_name'];
			$authen->email 			= trim($_REQUEST['email']);
			//$authen->avatar         = ($_REQUEST['avatar']);               
			//$authen->phone_number 	= trim($_REQUEST['phone_number']);
			$authen->activation 	= $code;
			$authen->group_id 		= 4;
			$authen->active			= '1';
			//$authen->password 		= \Hash::make($_REQUEST['password']);
			$authen->save();

			if($_REQUEST['device']== "android"){
					$value=array('device' =>"android",'mobile_token'=>$_REQUEST['token-']);
						$query=\DB::table('tb_users')->where('email','=', $_REQUEST['email'])->update($value);
				}
				if($_REQUEST['device']== "ios"){
					$value=array('device' =>"ios",'mobile_token'=>$_REQUEST['token']);
						$query=\DB::table('tb_users')->where('email','=',$_REQUEST['email'])->update($value);
				}

			if(isset($_REQUEST['image_url'])){
				$url	= $_REQUEST['image_url'];
				
                $name   =  $authen->id."-".time().".jpg";
				$img	= base_path()."/uploads/users/".$name;
				//print_r($img);exit;
				$ch = curl_init ($url);
			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			    $raw=curl_exec($ch);
			    curl_close ($ch);
			    if(file_exists($img)){
			        unlink($img);
			    }
			    $fp = fopen($img,'x');
			    fwrite($fp, $raw);
			    fclose($fp);

                //$suc	= file_put_contents($img, file_get_contents($url));
			    //if($suc){
					$image	= ['avatar'=>$name];
					$query1	= \DB::table('tb_users')->where('id','=', $authen->id)->update($image);
				//}
			} 
		    $user_values  = \DB::select("SELECT id,group_id,first_name,email,avatar FROM `tb_users` where `id` = '".$authen->id."'" );

		   foreach ($user_values as $key => $value) {
			   	$value->user_id = $value->id;
				if($value->avatar != ""){
					$image_url		= $this->image_url($value->avatar,'uploads/users');
					$value->avatar 	= $image_url;									
				} else {
					$value->avatar 	= \URL::to('').'/300x300.png';
				}
			}
			$response["status"]    = '1';
			$response["user_id"]    = $authen->id;
			$response["user_data"] 	= $user_values;
		} else {
			$messages	= $validator->messages();
			$error 		= $messages->getMessages();
            if(!empty($error['email'])){		
				if($error['email'][0] === "The email has already been taken."){
					if(isset($_REQUEST['image_url']) && $_REQUEST['image_url'] != ''){
						$avata = \DB::select("SELECT `id`,`avatar` FROM `tb_users` where `email` = '".$_REQUEST['email']."'") ;
						if($avata[0]->avatar != ""){
							$img	= base_path()."/uploads/users/".$avata[0]->avatar;
							if (File::exists($img)) {
								$image_del = File::delete($img);
							} 
						}
						$url	= $_REQUEST['image_url'];
						$name   = $avata[0]->id."-".time().".jpg";
						$img	= base_path()."/uploads/users/".$name;

						$ch = curl_init ($url);
					    curl_setopt($ch, CURLOPT_HEADER, 0);
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
					    $raw=curl_exec($ch);
					    curl_close ($ch);
					    if(file_exists($img)){
					        unlink($img);
					    }
					    $fp = fopen($img,'x');
					    fwrite($fp, $raw);
					    fclose($fp);
						
						//$suc	= file_put_contents($img, file_get_contents($url));
						$image	= ['avatar'=>$name];
						$query1	= \DB::table('tb_users')->where('id','=', $avata[0]->id)->update($image);
				    }
					$user_values= \DB::select("SELECT id, group_id,first_name,email,avatar FROM `tb_users` where email = '".$_REQUEST['email']."'" );

					if($_REQUEST['device']== "android"){
					$value=array('device' =>"android",'mobile_token'=>$_REQUEST['token']);
						$query=\DB::table('tb_users')->where('email','=', $_REQUEST['email'])->update($value);
				}
				if($_REQUEST['device']== "ios"){
					$value=array('device' =>"ios",'mobile_token'=>$_REQUEST['token']);
						$query=\DB::table('tb_users')->where('email','=',$_REQUEST['email'])->update($value);
				}


				   foreach ($user_values as $key => $value) {
						$value->user_id = $value->id;
					    if($value->avatar != ""){
							$image_url		= $this->image_url($value->avatar,'uploads/users');
							$value->avatar 	= $image_url;
						} else {
							$value->avatar 	= \URL::to('').'/300x300.png';
						}
					}
					$response["id"] 		= "3";
					$response["status"] 	= "3";
					$response["user_id"]    = $value->id;
					$response["user_data"] 	= $user_values;
					
				} else {
					$response["id"] 		= "4";
					$response["status"] 		= "5";
					$response["message"] 	= $error;
				}
			} else {
				$response["id"] 		= "5";
				$response["status"] 	= "5";
				$response["message"] 	= $error;
			}
		}
		echo json_encode($response); exit;
	}

	public function getPartnercreate( Request $request) {

		$_REQUEST 				= str_replace('"','', $_REQUEST);

		$response = array();
	
		$rules = array(
			'username'		=>'required|alpha_num|min:2',
			'email'			=>'required|email|unique:tb_users',
			'phone_number'	=>'required|numeric|unique:tb_users',
			'password'		=>'required|between:6,12',
			);	

		// if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$code = rand(10000,10000000);
			$code = substr(sha1(mt_rand()),17,6);
			// $code = $this->get_rand_alphanumeric(6);
		
			$authen 				= new User;
			$authen->username 		= $_REQUEST['username'];
			$authen->phone_number 	= $_REQUEST['phone_number'];
			$authen->email 			= trim($_REQUEST['email']);
			$authen->activation 	= $code;
			$authen->group_id 		= 3;
			$authen->password 		= \Hash::make($_REQUEST['password']);
			$authen->active 		= '0';
			// if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			$authen->save();
			
			/*$data = array(
				'username'		=> $_REQUEST['username'] ,
				'phone_number'	=> $_REQUEST['phone_number'] ,
				'email'			=> $_REQUEST['email'] ,
				'password'		=> $_REQUEST['password'] ,
				'code'			=> $code
				
			);*/
			if(CNF_ACTIVATION == 'confirmation')
			{ 
			
				/*$to = $request->input('email');
				$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
				$message = view('user.emails.registration', $data);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);*/	
				
					$response["id"] 		= "1";
					$response["message"] 	= "Please check your inbox and follow activation link";
					echo json_encode($response);exit;
			} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response["message"] 	= "We will validate you account before your account active";
					echo json_encode($response);exit;
			} else {
				$user = \DB::table('tb_users')->select('id')->where('phone_number','=',$_REQUEST['phone_number'])->get();

				foreach ($user as $key) {
					$userid = $key->id;
				}
				\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
					$response["id"] 		= "3";
					$response["message"] 	= "Your account is active now";
					$response['user_id']	= $userid;
					echo json_encode($response);exit;    
			}

		} else {
			
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			// $response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['username'])){
					$response['message'] = $error['username'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				}
			}
            echo json_encode($response); exit;
		}
	}

	public function postPartnercreate( Request $request) {

		$_REQUEST 				= str_replace('"','', $_REQUEST);

		$response = array();
	
		$rules = array(
			'username'		=>'required|alpha_num|min:2',
			'email'			=>'required|email|unique:tb_users',
			'phone_number'	=>'required|numeric|unique:tb_users',
			'password'		=>'required|between:6,12',
			);	

		// if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$code = rand(10000,10000000);
			$code = substr(sha1(mt_rand()),17,6);
			// $code = $this->get_rand_alphanumeric(6);
		
			$authen 				= new User;
			$authen->username 		= $_REQUEST['username'];
			$authen->phone_number 	= $_REQUEST['phone_number'];
			$authen->email 			= trim($_REQUEST['email']);
			$authen->activation 	= $code;
			$authen->group_id 		= 3;
			$authen->password 		= \Hash::make($_REQUEST['password']);
			$authen->active 		= '0';
			// if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			$authen->save();
			
			/*$data = array(
				'username'		=> $_REQUEST['username'] ,
				'phone_number'	=> $_REQUEST['phone_number'] ,
				'email'			=> $_REQUEST['email'] ,
				'password'		=> $_REQUEST['password'] ,
				'code'			=> $code
				
			);*/
			if(CNF_ACTIVATION == 'confirmation')
			{ 
			
				/*$to = $request->input('email');
				$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
				$message = view('user.emails.registration', $data);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);*/	
				
					$response["id"] 		= "1";
					$response["message"] 	= "Please check your inbox and follow activation link";
					echo json_encode($response);exit;
			} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response["message"] 	= "We will validate you account before your account active";
					echo json_encode($response);exit;
			} else {
				$user = \DB::table('tb_users')->select('id')->where('phone_number','=',$_REQUEST['phone_number'])->get();

				foreach ($user as $key) {
					$userid = $key->id;
				}
				\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
					$response["id"] 		= "3";
					$response["message"] 	= "Your account is active now";
					$response['user_id']	= $userid;
					echo json_encode($response);exit;    
			}

		} else {
			
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			// $response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['username'])){
					$response['message'] = $error['username'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				}
			}
            echo json_encode($response); exit;
		}
	}

	public function postDeliverycreate( Request $request) {

		$_REQUEST 				= str_replace('"','', $_REQUEST);

		$response = array();
	
		$rules = array(
			'username'=>'required|alpha_num|min:2',
			'email'=>'required|email|unique:abserve_deliveryboys',
			'phone_number'=>'required_with:phonefield|unique:abserve_deliveryboys|numeric',
			'password'=>'required|between:6,12',
			// 'partner_id'=>'required|numeric'
			);	
		// if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen 				= new Deliveryboy;
			$authen->username 		= $_REQUEST['username'];
			$authen->phone_number 	= $_REQUEST['phone_number'];
			$authen->email 			= trim($_REQUEST['email']);
			$authen->partner_id 	= $_REQUEST['partner_id'];
			$authen->activation 	= $code;
			$authen->group_id 		= 3;
			$authen->password 		= \Hash::make($_REQUEST['password']);
			$authen->active 		= '0';
			// if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			$authen->save();

			$user = \DB::table('abserve_deliveryboys')->select('id')->where('phone_number','=',$_REQUEST['phone_number'])->get();

			/*$user = \DB::select("SELECT `id` FROM `abserve_deliveryboys` WHERE `phone_number` = ".$_REQUEST['phone_number']);*/
			foreach ($user as $key) {
				$userid = $key->id;
			}
			
			/*$data = array(
				'username'		=> $_REQUEST['username'] ,
				'phone_number'	=> $_REQUEST['phone_number'] ,
				'email'			=> $_REQUEST['email'] ,
				'password'		=> $_REQUEST['password'] ,
				'code'			=> $code
				
			);*/
			if(CNF_ACTIVATION == 'confirmation')
			{ 
			
				/*$to = $request->input('email');
				$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
				$message = view('user.emails.registration', $data);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);*/	
				
					$response["id"] 		= "1";
					$response["message"] 	= "Please check your inbox and follow activation link";
					$response["user_id"]    = $userid;
					\DB::table('abserve_deliveryboys')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
					echo json_encode($response);exit;
			} elseif(CNF_ACTIVATION=='manual') {
					$response["id"] 		= "2";
					$response["message"] 	= "We will validate you account before your account active";
					echo json_encode($response);exit;
			} else {
				\DB::table('abserve_deliveryboys')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
					$response["id"] 		= "3";
					$response["message"] 	= "Your account is active now ";
					$response["user_id"]    = $userid;
					echo json_encode($response);exit;    
			}

		} else {
			
			$messages 				= $validator->messages();
			// print_r($messages);exit;
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			// $response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['username'])){
					$response['message'] = $error['username'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				}
			}
            echo json_encode($response); exit;
		}
	}

	public function postCustomercreate( Request $request) {

		$_REQUEST 				= str_replace('"','', $_REQUEST);
		// print_r($_REQUEST);exit;

		$response = array();
	
		$rules = array(
			'username'=>'required|alpha_num|min:2',
			'email'=>'required|email|unique:tb_users',
			'phone_number'=>'required|unique:tb_users|numeric',
			'password'=>'required|between:6,12',
			);	
		// if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			if(isset($_REQUEST['invite_code']) && $_REQUEST['invite_code'] != ''){
				$invite_code_valid = \DB::table('tb_users')
				->where('activation' ,'=' ,$_REQUEST['invite_code'])
				->exists();
				if($invite_code_valid){
					// $code = rand(10000,10000000);
					$code = $this->get_rand_alphanumeric(4);
					
					$authen 				= new Customers;
					$authen->username 		= $_REQUEST['username'];
					$authen->phone_number 	= $_REQUEST['phone_number'];
					$authen->email 			= trim($_REQUEST['email']);
					$authen->activation 	= $code;
					$authen->group_id 		= 4;
					$authen->password 		= \Hash::make($_REQUEST['password']);
					$authen->active 		= '0';
					
					$authen->save();

					$user_id 	= \DB::select("SELECT `id`,`activation` FROM `tb_users` WHERE `phone_number` = '".$_REQUEST['phone_number']."'");
					$userid 	= $user_id[0]->id;
					$code 		= $user_id[0]->activation;
					$code 		= $code.$userid;

					\DB::table('tb_users')->where('id','=',$userid)->update(array('activation'=>$code));

					$refer_id = \DB::table('tb_users')->select('*')->where("activation",'=',$_REQUEST['invite_code'])->get();

					$credit_code = $this->get_rand_alphanumeric(4);
					$credit_code = $userid.$credit_code.$refer_id[0]->id;

					$referal_earning = array("from_id"=>$userid,"to_id"=>$refer_id[0]->id,"credit_code"=>$credit_code,"credit_amt"=>50);

					\DB::table('abserve_user_credit')->insert($referal_earning);
					
					/*$data = array(
						'username'		=> $_REQUEST['username'] ,
						'phone_number'	=> $_REQUEST['phone_number'] ,
						'email'			=> $_REQUEST['email'] ,
						'password'		=> $_REQUEST['password'] ,
						'code'			=> $code
						
					);*/
					if(CNF_ACTIVATION == 'confirmation')
					{	
					
						/*$to = $request->input('email');
						$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
						$message = view('user.emails.registration', $data);
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
							mail($to, $subject, $message, $headers);*/	

							\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
						
							$response["id"] 		= "1";
							$response["message"] 	= "Please check your inbox and follow activation link";
							$response["user_id"]    = $userid;
							echo json_encode($response);exit;
					} elseif(CNF_ACTIVATION=='manual') {
						\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
							$response["id"] 		= "2";
							$response["message"] 	= "We will validate you account before your account active";
							$response["user_id"]    = $userid;
							$response["invite_code"]= $code;
							echo json_encode($response);exit;
					} else {
						\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
							$response["id"]				= "3";
							$response["message"]		= "Your account is active now";
							$response["user_id"]		= $userid;
							$response["invite_code"]	= $code;
							echo json_encode($response);exit;    
					}
				}else{
					$response["id"]		 = "6";
					$response['message'] = "Invalid Invite code";
					echo json_encode($response);exit;    
				}
			}else{

				// $code = rand(10000,10000000);
				$code = $this->get_rand_alphanumeric(4);
				
				$authen 				= new Customers;
				$authen->username 		= $_REQUEST['username'];
				$authen->phone_number 	= $_REQUEST['phone_number'];
				$authen->email 			= trim($_REQUEST['email']);
				$authen->activation 	= $code;
				$authen->group_id 		= 4;
				$authen->password 		= \Hash::make($_REQUEST['password']);
				$authen->active 		= '0';
				// if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
				$authen->save();

				$user_id 	= \DB::select("SELECT `id`,`activation` FROM `tb_users` WHERE `phone_number` = '".$_REQUEST['phone_number']."'");
				$userid 	= $user_id[0]->id;
				$code 		= $user_id[0]->activation;
				$code 		= $code.$userid;

				\DB::table('tb_users')->where('id','=',$userid)->update(array('activation'=>$code));
				
				/*$data = array(
					'username'		=> $_REQUEST['username'] ,
					'phone_number'	=> $_REQUEST['phone_number'] ,
					'email'			=> $_REQUEST['email'] ,
					'password'		=> $_REQUEST['password'] ,
					'code'			=> $code
					
				);*/
				if(CNF_ACTIVATION == 'confirmation')
				{	
				
					/*$to = $request->input('email');
					$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
					$message = view('user.emails.registration', $data);
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
						mail($to, $subject, $message, $headers);*/	
						\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
					
						$response["id"] 		= "1";
						$response["message"] 	= "Please check your inbox and follow activation link";
						$response["user_id"]    = $userid;
						echo json_encode($response);exit;
				} elseif(CNF_ACTIVATION=='manual') {
					\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
						$response["id"] 		= "2";
						$response["message"] 	= "We will validate you account before your account active";
						$response["user_id"]    = $userid;
						$response["invite_code"]= $code;
						echo json_encode($response);exit;
				} else {
					\DB::table('tb_users')->where('id','=',$userid)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
						$response["id"]				= "3";
						$response["message"]		= "Your account is active now";
						$response["user_id"]		= $userid;
						$response["invite_code"]	= $code;
						echo json_encode($response);exit;    
				}
			}

		} else {
			
			$messages 				= $validator->messages();
			// print_r($messages);exit;
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			// $response["error"] 		= $error;
			if(!empty($error)){
				if(isset($error['username'])){
					$response['message'] = $error['username'][0];
				} else if(isset($error['email'])){
					$response['message'] = $error['email'][0];
				} else if(isset($error['phone_number'])){
					$response['message'] = $error['phone_number'][0];
				} else if(isset($error['password'])){
					$response['message'] = $error['password'][0];
				}
			}
            echo json_encode($response); exit;
		}
	}
	public function postPartnersignin3( Request $request) {
		
		$_REQUEST 				= str_replace('"','', $_REQUEST);
		
		$response = array();

		$rules = array(
			'phone_number'=>'required',
			'password'=>'required',
			
		);		
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			
			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );
			
			if (\Auth::attempt(array('phone_number'=>$_REQUEST['phone_number'], 'password'=> $_REQUEST['password'] ), $remember )) {
			
					$row = User::find(\Auth::user()->id); 
	
					if($row->active =='0')
					{
						// inactive 
							$response["id"] 			= "1";
							$response["message"] 		= "Your Account is not active";
				            echo json_encode($response);exit;
					} 
					else if($row->active=='2')
					{
						// BLocked users
							$response["id"] 			= "2";
							$response["message"] 		= "Your Account is BLocked";
				            echo json_encode($response); exit;
					} else {
						$userid[0]["id"]				= 	$row->id;

						if($_REQUEST['device']) {

							
						
							
							\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
						} else {
							
							\DB::table("UPDATE `tb_users` set `device` = 'android' where `id` = ".$row->id);
						}
						\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						$response["id"] 				= "3";
						$response["message"] 			= "success";
						$response["user_id"]      		= $userid;

						//generating access token
						$sToken = Authorizer::issueAccessToken();
						$response["access_token"] = $sToken['access_token'];
						echo json_encode($response); exit;
					}							
			} 
			else {
				$response["id"] 		= "4";
				$response["message"] 	= "Your phonenumber,password combination was incorrect";
	            echo json_encode($response); exit;
			}

		
		} 
		else {
				$messages 				= $validator->messages();
				$error 					= $messages->getMessages();
				$response["id"] 		= "5";
				// $response["error"] 		= $error;
				if(!empty($error)){
					if(isset($error['phone_number'])){
						$response['message'] = $error['phone_number'][0];
					} else if(isset($error['password'])){
						$response['message'] = $error['password'][0];
					}
				}
	            echo json_encode($response); exit;
		}
	}

	public function postPartnersignin( Request $request) {
		
		$_REQUEST 				= str_replace('"','', $_REQUEST);
		$_REQUEST['group_id'] =3;
		$response = array();

		$rules = array(
			'phone_number'=>'required',
			'password'=>'required',
			'group_id'=>'required',
		);		
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			 if($_REQUEST['group_id'] ==3){
			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );
			
			if (\Auth::attempt(array('phone_number'=>$_REQUEST['phone_number'], 'password'=> $_REQUEST['password'],'group_id'=>3 ), $remember )) {
			
					$row = User::find(\Auth::user()->id); 
	
					if($row->active =='0')
					{
						// inactive 
							$response["id"] 			= "1";
							$response["message"] 		= "Your Account is not active";
				            echo json_encode($response);exit;
					} 
					else if($row->active=='2')
					{
						// BLocked users
							$response["id"] 			= "2";
							$response["message"] 		= "Your Account is BLocked";
				            echo json_encode($response); exit;
					} else {
						$userid[0]["id"]				= 	$row->id;

						if($_REQUEST['device']) {

							
						
							
							\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
						} else {
							
							\DB::table("UPDATE `tb_users` set `device` = 'android' where `id` = ".$row->id);
						}
						\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						$response["id"] 				= "3";
						$response["message"] 			= "success";
						$response["user_id"]      		= $userid;

						//generating access token
						$sToken = Authorizer::issueAccessToken();
						$response["access_token"] = $sToken['access_token'];
						echo json_encode($response); exit;
					}							
			} 
			else {
				$response["id"] 		= "4";
				$response["message"] 	= "Your phonenumber,password combination was incorrect";
	            echo json_encode($response); exit;
			}

		}else{
			$response["id"] 		= "6";
				$response["message"] 	= "gffhfh";
	            echo json_encode($response); exit;
		}
		} 
		else {
				$messages 				= $validator->messages();
				$error 					= $messages->getMessages();
				$response["id"] 		= "5";
				// $response["error"] 		= $error;
				if(!empty($error)){
					if(isset($error['phone_number'])){
						$response['message'] = $error['phone_number'][0];
					} else if(isset($error['password'])){
						$response['message'] = $error['password'][0];
					}
				}
	            echo json_encode($response); exit;
		}
	}
	
	public function postPartnersignin_old( Request $request) {
		
		$_REQUEST 				= str_replace('"','', $_REQUEST);
		
		$response = array();

		$rules = array(
			'phone_number'=>'required',
			'password'=>'required',
		);		

		// if(CNF_RECAPTCHA =='true') $rules['captcha'] = 'required|captcha';
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );
			
			if (\Auth::attempt(array('phone_number'=>$_REQUEST['phone_number'], 'password'=> $_REQUEST['password'] ), $remember )) {

					$row = User::find(\Auth::user()->id); 
	
					if($row->active =='0')
					{
						// inactive 
							$response["id"] 			= "1";
							$response["message"] 		= "Your Account is not active";
				            echo json_encode($response);exit;
					} 
					else if($row->active=='2')
					{
						// BLocked users
							$response["id"] 			= "2";
							$response["message"] 		= "Your Account is BLocked";
				            echo json_encode($response); exit;
					} else {
						$userid[0]["id"]				= 	$row->id;

						\DB::table('tb_users')->where('id','=',$row->id)->update(['mobile_token'=>$_REQUEST['mobile_token']]);

						$response["id"] 				= "3";
						$response["message"] 			= "success";
						$response["user_id"]      		= $userid;
						echo json_encode($response); exit;
					}							
			} 
			else {
				$response["id"] 		= "4";
				$response["message"] 	= "Your phonenumber,password combination was incorrect";
	            echo json_encode($response); exit;
			}
		} 
		else {
				$messages 				= $validator->messages();
				$error 					= $messages->getMessages();
				$response["id"] 		= "5";
				// $response["error"] 		= $error;
				if(!empty($error)){
					if(isset($error['phone_number'])){
						$response['message'] = $error['phone_number'][0];
					} else if(isset($error['password'])){
						$response['message'] = $error['password'][0];
					}
				}
	            echo json_encode($response); exit;
		}
	}

	public function postDeliverysignin( Request $request) {

		$_REQUEST 	= str_replace('"','', $_REQUEST);

		$response = array();
		
		$rules = array(
			'phone_number'	=>'required|numeric',
			'password'		=>'required',
		);		

		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {	

			$row = \DB::table('abserve_deliveryboys')->select('*')->where('phone_number','=',$_REQUEST['phone_number'])->get();

			/*echo "<pre>";
			print_r($row[0]->active);
			echo "<br>";exit;*/

			$password_match = Hash::check($_REQUEST['password'], $row[0]->password);

			if ($password_match == 1 && $row[0]->phone_number == $_REQUEST['phone_number']) {	

					if($row[0]->active =='0')
					{
						// inactive 
							$response["id"] 			= "1";
							$response["message"] 		= "Your Account is not active";
							echo json_encode($response);exit;
	
					} else if($row[0]->active=='2')
					{
						// BLocked users
							$response["id"]				= "2";
							$response["message"] 		= "Your Account is BLocked";
							echo json_encode($response); exit;
					} else {
						$userid[0]["id"]		= 	$row[0]->id;
						// $userid[0]["group_[0]id"]	= 	$row[0]->group_id;

						$profile	=	\DB::select("SELECT `group_id` FROM `abserve_deliveryboys` WHERE `id`=".$row[0]->id);
						\DB::table('abserve_deliveryboys')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						$response["id"] 				= "3";
						$response["message"] 			= "success";
						$response["user_id"]      		= $userid;

						//generating access token
						$sToken = Authorizer::issueAccessToken();
						$response["access_token"] = $sToken['access_token'];
						echo json_encode($response); exit;
						
					}					
				
			} else {
				$response["id"] 		= "4";
				$response["message"] 	= "Your phonenumber,password combination was incorrect";
				echo json_encode($response); exit;
			}
		} else {
				$messages 				= $validator->messages();
				$error 					= (array)$messages->getMessages();
				$response["id"] 		= "5";
				// $response["error"] 		= $error;
				if(!empty($error)){
					if(isset($error['phone_number'])){
						$response['message'] = $error['phone_number'][0];
					} else if(isset($error['password'])){
						$response['message'] = $error['password'][0];
					}
				}
				echo json_encode($response); exit;
		}	
	}

	public function postCustomersignin3( Request $request) {

		$_REQUEST 	= str_replace('"','', $_REQUEST);

		$response = array();
		
		$rules = array(
			'phone_number'	=>'required|numeric',
			'password'		=>'required',
		);		

		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {	

			$row = \DB::table('tb_users')->select('*')->where('phone_number','=',$_REQUEST['phone_number'])->get();

			$password_match = Hash::check($_REQUEST['password'], $row[0]->password);

			if ($password_match == 1 && $row[0]->phone_number == $_REQUEST['phone_number']) {	

					if($row[0]->active =='0')
					{
						// inactive 
							$response["id"] 			= "1";
							$response["message"] 		= "Your Account is not active";
							echo json_encode($response);exit;
	
					} else if($row[0]->active=='2')
					{
						// BLocked users
							$response["id"]				= "2";
							$response["message"] 		= "Your Account is BLocked";
							echo json_encode($response); exit;
					} else {
						$userid[0]["id"]			= $row[0]->id;
						$invite_code[0]['code'] 	= $row[0]->activation;
						// $userid[0]["group_id"]	= 	$row[0]->group_id;

						$profile	=	\DB::select("SELECT `group_id` FROM `tb_users` WHERE `id`=".$row[0]->id);
						\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						$response["id"] 				= "3";
						$response["message"] 			= "success";
						$response["user_id"]      		= $userid;
						$response["invite_code"]      	= $invite_code;

						//generating access token
						$sToken = Authorizer::issueAccessToken();
						$response["access_token"] = $sToken['access_token'];
						echo json_encode($response); exit;
					}					
				
			} else {
				$response["id"] 		= "4";
				$response["message"] 	= "Your phonenumber,password combination was incorrect";
				echo json_encode($response); exit;
			}
		} else {
				$messages 				= $validator->messages();
				$error 					= (array)$messages->getMessages();
				$response["id"] 		= "5";
				// $response["error"] 		= $error;
				if(!empty($error)){
					if(isset($error['phone_number'])){
						$response['message'] = $error['phone_number'][0];
					} else if(isset($error['password'])){
						$response['message'] = $error['password'][0];
					}
				}
				echo json_encode($response); exit;
		}	
	}

	public function postCustomersignin( Request $request) {

		$_REQUEST 	= str_replace('"','', $_REQUEST);
		$_REQUEST['group_id'] =4;
		$response = array();
		
		$rules = array(
			'phone_number'	=>'required|numeric',
			'password'		=>'required',
			'group_id'      =>'required'
		);		

		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {	
           if($_REQUEST['group_id'] ==4){
			$row = \DB::table('tb_users')->select('*')->where('phone_number','=',$_REQUEST['phone_number'])->where('group_id','=',4)->get();

			$password_match = Hash::check($_REQUEST['password'], $row[0]->password);

			if ($password_match == 1 && $row[0]->phone_number == $_REQUEST['phone_number']) {	

					if($row[0]->active =='0')
					{
						// inactive 
							$response["id"] 			= "1";
							$response["message"] 		= "Your Account is not active";
							echo json_encode($response);exit;
	
					} else if($row[0]->active=='2')
					{
						// BLocked users
							$response["id"]				= "2";
							$response["message"] 		= "Your Account is BLocked";
							echo json_encode($response); exit;
					} else {
						$userid[0]["id"]			= $row[0]->id;
						$invite_code[0]['code'] 	= $row[0]->activation;
						// $userid[0]["group_id"]	= 	$row[0]->group_id;
						$users = \DB::table('tb_users')->select('id','first_name','avatar')->where('phone_number','=',$_REQUEST['phone_number'])->get();

						foreach ($users as $key => $valu) {
					if($valu->avatar != ''){
						$valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
					}else{
						$valu->avatar = \URL::to('').'/uploads/images/no-image.png';
					}
				}

						//$profile	=	\DB::select("SELECT `group_id` FROM `tb_users` WHERE `id`=".$row[0]->id);
						//$user_details=	\DB::select("SELECT `id,first_name,last_name,avatar` FROM `tb_users` WHERE `id`=".$row[0]->id);
						\DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);

						$response["id"] 				= "3";
						$response["message"] 			= "success";
						$response["user_id"]      		= $users;
						$response["invite_code"]      	= $invite_code;

						//generating access token
						$sToken = Authorizer::issueAccessToken();
						$response["access_token"] = $sToken['access_token'];
						echo json_encode($response); exit;
					}					
				
			} else {
				$response["id"] 		= "4";
				$response["message"] 	= "Your phonenumber,password combination was incorrect";
				echo json_encode($response); exit;
			}
		}else{
			    $response["id"] 		= "4";
				$response["message"] 	= "your not";
				echo json_encode($response); exit;
		}
		} else {
				$messages 				= $validator->messages();
				$error 					= (array)$messages->getMessages();
				$response["id"] 		= "5";
				// $response["error"] 		= $error;
				if(!empty($error)){
					if(isset($error['phone_number'])){
						$response['message'] = $error['phone_number'][0];
					} else if(isset($error['password'])){
						$response['message'] = $error['password'][0];
					}
				}
				echo json_encode($response); exit;
		}	
	}

	public function postProfileedit_old(Request $request)
	{
		$rules = array(
			'user_id'=>'required|numeric',
		);
		$val  = array();
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$check = $this->exists('tb_users','id',$request->user_id);
			if($check){
				if($request->first_name!=""){
					$val['first_name'] = $request->first_name;
				}
				if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}
				if($request->phone_number!=""){
					$val['phone_number'] = $request->phone_number;
				}

				if($request->email!=""){
					$val['email'] = $request->email;
				}
				

				if(isset($_REQUEST['avatar']) && $_REQUEST['avatar']!=""){
					$avata = \DB::select("SELECT `avatar` FROM `tb_users` where `id` = '".$_REQUEST['user_id']."'") ;
					if($avata[0]->avatar !=""){
						$path=base_path()."/uploads/customers/".$avata[0]->avatar;
						
						if (File::exists($path)) {
					    	File::delete($path);
						} 
					}
					$uid   	= $_REQUEST['user_id']."-".rand(3,10)."-".time().".jpg";
				
					$image 	= $_REQUEST['avatar'];
					$path 	= base_path()."/uploads/customers/$uid";

					file_put_contents($path,base64_decode($image));
					$val['avatar'] = $uid;

				}
				
				if(!empty($val)){
		            $up = \DB::table('tb_users')->where('id', $request->user_id)->update($val); 
					if($up){
						$response['message'] = "Updated successfully";
					} else {
						$response['message'] = "Doesn't updated";
					}
				} else {
					$response['message'] = "Doesn't updated";
				}
			} else {
				$response['message'] = "User ID Doesn't exists";
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;

	}


	public function postProfileedit(Request $request)
	{
		$rules = array(
			'user_id'=>'required|numeric',
		);
		$val  = array();
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$check = $this->exists('tb_users','id',$request->user_id);
			if($check){
				if($request->first_name!=""){
					$val['first_name'] = $request->first_name;
				}
				if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}
				if($request->phone_number!=""){
					$val['phone_number'] = $request->phone_number;
				}

				if($request->email!=""){
					$val['email'] = $request->email;
				}
				

				if(isset($_REQUEST['avatar']) && $_REQUEST['avatar']!=""){
					$avata = \DB::select("SELECT `avatar` FROM `tb_users` where `id` = '".$_REQUEST['user_id']."'") ;
					if($avata[0]->avatar !=""){
						$path=base_path()."/uploads/customers/".$avata[0]->avatar;
						
						if (File::exists($path)) {
					    	File::delete($path);
						} 
					}
					$uid   	= $_REQUEST['user_id']."-".rand(3,10)."-".time().".jpg";
				
					$image 	= $_REQUEST['avatar'];
					$path 	= base_path()."/uploads/customers/$uid";

					file_put_contents($path,base64_decode($image));
					$val['avatar'] = $uid;

				}
				
				if(!empty($val)){
		            $up = \DB::table('tb_users')->where('id', $request->user_id)->update($val); 
					if($up){
						$userdetails = \DB::select("SELECT `avatar`,`id`,`first_name`,`last_name` FROM `tb_users` where `id` = '".$_REQUEST['user_id']."'") ;
						
						foreach ($userdetails as $key => $valu) {
					if($valu->avatar != ''){
						$valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
					}else{
						$valu->avatar = \URL::to('').'/uploads/images/no-image.png';
					}
				}

						$response['userdetails'] = $userdetails;
						$response['message'] = "Updated successfully";
					} else {
						$response['message'] = "Doesn't updated";
					}
				} else {
					$response['message'] = "Doesn't updated";
				}
			} else {
				$response['message'] = "User ID Doesn't exists";
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;
	}


	public function postCprofile( Request $request) {

		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){
				$profile = \DB::table('tb_users')->select('id','username','first_name','last_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();
				// foreach ($profile as $key => $value) {
					$prof[] = get_object_vars($profile[0]);
				// }

				foreach ($prof as $key => &$valu) {
					if($valu['avatar'] != ''){
						$valu['avatar'] = \URL::to('').'/uploads/customers/'.$valu['avatar'];
					}else{
						$valu['avatar'] = \URL::to('').'/uploads/images/no-image.png';
					}
				}

				$response['Customer profile'] = ($prof);
				echo json_encode($response);exit;
			}else{
				$response['message'] = "UserID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCusprofile( Request $request) {

		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){
				$profile = \DB::table('tb_users')->select('id','username','first_name','last_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();
				// foreach ($profile as $key => $value) {
					$prof[] = get_object_vars($profile[0]);
				// }

				foreach ($prof as $key => &$valu) {
					if($valu['avatar'] != ''){
						$valu['avatar'] = \URL::to('').'/uploads/users/'.$valu['avatar'];
					}else{
						$valu['avatar'] = \URL::to('').'/uploads/images/no-image.png';
					}
				}

				$response['Customerprofile'] = ($prof);
				echo json_encode($response);exit;
			}else{
				$response['message'] = "UserID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCedit(Request $request)
	{
		$rules = array(
			'user_id'=>'required|numeric',
		);
		$val  = array();
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$check = $this->exists('tb_users','id',$request->user_id);
			if($check){
				if($request->first_name!=""){
					$val['first_name'] = $request->first_name;
				}
				if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}
				if($request->phone_number!=""){
					$val['phone_number'] = $request->phone_number;
				}

				if($request->email!=""){
					$val['email'] = $request->email;
				}
				

				if(isset($_FILES['avatar'])) {
					$dir = base_path() . '/uploads/customers';
					if (!file_exists($dir)) {
						$ret_dir = mkdir($dir, 0777); 
					}

					$filename	= '';
					$result		= basename($_FILES["avatar"]["name"]);
					$exp		= explode(".",$result);
					$filename	= $_REQUEST['user_id'].'_'.time().'.'.$exp[1];
					$file_path	= $dir."/".$filename;
					$file_up	= move_uploaded_file($_FILES["avatar"]["tmp_name"], $file_path);
					if($file_up){
						$val['avatar'] = $filename;
					}
				}
				
				if(!empty($val)){
		            $up = \DB::table('tb_users')->where('id', $request->user_id)->update($val); 
					if($up){
						$response['message'] = "Updated successfully";
					} else {
						$response['message'] = "Doesn't updated";
					}
				} else {
					$response['message'] = "Doesn't updated";
				}
			} else {
				$response['message'] = "User ID Doesn't exists";
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;

	}

	

	public function postPprofile( Request $request){
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){
				$profile = \DB::table('tb_users')->select('id','username','first_name','last_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();
				// foreach ($profile as $key => $value) {
					$prof[] = get_object_vars($profile[0]);
				// }

				foreach ($prof as $key => &$valu) {
					if($valu['avatar'] != ''){
						$valu['avatar'] = \URL::to('').'/uploads/users/'.$valu['avatar'];
					}else{
						$valu['avatar'] = \URL::to('').'/uploads/images/no-image.png';
					}
				}

				$response['Partner profile'] = ($prof);
				echo json_encode($response);exit;
			}else{
				$response['message'] = "UserID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postPedit( Request $request) {

		$response 	= array();
		$rules		= array();
	
		$userid 	= array('user_id'		=>'required');	
		$user 		= array('username'		=>'required|min:2');
		$email 		= array('email'			=>'required|email|unique:tb_users');
		$phno 		= array('phone_number'	=>'required|unique:tb_users|numeric');

		array_unshift($rules, $userid);
		
		if($_REQUEST['username']){
			array_unshift($rules, $user);
		}
		if($_REQUEST['email']){
			array_unshift($rules, $email);
		}
		if($_REQUEST['phone_number']){
			array_unshift($rules, $phno);
		}
		$result = call_user_func_array('array_merge_recursive', $rules);
				
		$validator = Validator::make($_REQUEST, $result);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$user_id 	= $_REQUEST['user_id'];
				$image 		= $_REQUEST['avatar'];
				$file = public_path()."/uploads/customers/$user_id";

				$data 	=	$_REQUEST;
				unset($data['user_id']);

				/*if($image != ''){
					$path = public_path()."/uploads/customers/$user_id.jpg";
					file_put_contents($path,base64_decode($image));
				}*/

				$i=1;
				foreach ($data as $key => $name_value) {
					// if(in_array($key, $aFields)){
						$keys[] = $key;
						/*if($key == 'avatar'){
							\File::Delete($file);
							$vals[] = $user_id.".jpg";
						}
						else{*/
							$vals[] = $name_value;
						// }
					// }
					$i++;
				}

				$values = (array_combine($keys, $vals));

				$update = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->update($values);
				if($update){
					$response['message'] = "Updated Successfully";
					echo json_encode($response);exit;
				}else{
					$response['message'] = "Not Updated";
					echo json_encode($response);exit;					
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBprofile( Request $request){
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$cexists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){
				$profile = \DB::table('abserve_deliveryboys')->select('id','username','email','phone_number','address','city','state','avatar','online_sts')->where('id','=',$_REQUEST['user_id'])->get();
				foreach ($profile as $key => $value) {
					if($value->username!=''){$value->username=$value->username;}else{ $value->username='';}
					if($value->email!=''){$value->email=$value->email;}else{ $value->email='';}
					if($value->phone_number!=''){$value->phone_number=$value->phone_number;}else{ $value->phone_number='';}
					if($value->address!=''){$value->address=$value->address;}else{ $value->address='';}
					if($value->city!=''){$value->city=$value->city;}else{ $value->city='';}
					if($value->state!=''){$value->state=$value->state;}else{ $value->state='';}
					if($value->online_sts!=''){$value->online_sts=$value->online_sts;}else{ $value->online_sts='';}
					if($value->avatar != ''){
						$value->avatar = \URL::to('').'/uploads/deliveryboys/'.$value['avatar'];
					}else{
						$value->avatar = \URL::to('').'/uploads/images/no-image.png';
					}
				}

				$response['Boy profile'] = ($value);
				echo json_encode($response);exit;
			}else{
				$response['message'] = "UserID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyedit(Request $request)
	{
		$rules = array(
			'user_id'=>'required|numeric',
		);
		$val  = array();
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$check = $this->exists('abserve_deliveryboys','id',$request->user_id);
			if($check){
				if($request->username!=""){
					$val['username'] = $request->username;
				}
				if($request->email!=""){
					$val['email'] = $request->email;
				}
				if($request->phone_number!=""){
					$val['phone_number'] = $request->phone_number;
				}

				if(isset($_FILES['avatar'])) {
					$dir = base_path() . '/uploads/deliveryboys';
					if (!file_exists($dir)) {
						$ret_dir = mkdir($dir, 0777); 
					}

					$filename	= '';
					$result		= basename($_FILES["avatar"]["name"]);
					$exp		= explode(".",$result);
					$filename	= $_REQUEST['user_id'].'_'.time().'.'.$exp[1];
					$file_path	= $dir."/".$filename;
					$file_up	= move_uploaded_file($_FILES["avatar"]["tmp_name"], $file_path);
					if($file_up){
						$val['avatar'] = $filename;
					}
				}
				
				if(!empty($val)){
		            $up = \DB::table('abserve_deliveryboys')->where('id', $request->user_id)->update($val); 
					if($up){
						$response['message'] = "Updated successfully";
					} else {
						$response['message'] = "Doesn't updated";
					}
				} else {
					$response['message'] = "Doesn't updated";
				}
			} else {
				$response['message'] = "User ID Doesn't exists";
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;

	}

	public function postBedit( Request $request) {

		$response 	= array();
		$rules		= array();
	
		$userid 	= array('user_id'		=>'required');	
		$user 		= array('username'		=>'required|min:2');
		$email 		= array('email'			=>'required|email|unique:tb_users');
		$phno 		= array('phone_number'	=>'required|unique:tb_users|numeric');

		array_unshift($rules, $userid);
		
		if($_REQUEST['username']){
			array_unshift($rules, $user);
		}
		if($_REQUEST['email']){
			array_unshift($rules, $email);
		}
		if($_REQUEST['phone_number']){
			array_unshift($rules, $phno);
		}
		$result = call_user_func_array('array_merge_recursive', $rules);
				
		$validator = Validator::make($_REQUEST, $result);

		if ($validator->passes()) {
			$cexists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$user_id 	= $_REQUEST['user_id'];
				$image 		= $_REQUEST['avatar'];
				$file = public_path()."/uploads/deliveryboys/$user_id";

				$data 	=	$_REQUEST;
				unset($data['user_id']);

				/*if($image != ''){
					$path = public_path()."/uploads/customers/$user_id.jpg";
					file_put_contents($path,base64_decode($image));
				}*/

				$i=1;
				foreach ($data as $key => $name_value) {
					// if(in_array($key, $aFields)){
						$keys[] = $key;
						/*if($key == 'avatar'){
							\File::Delete($file);
							$vals[] = $user_id.".jpg";
						}
						else{*/
							$vals[] = $name_value;
						// }
					// }
					$i++;
				}

				$values = (array_combine($keys, $vals));

				$update = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['user_id'])->update($values);
				if($update){
					$response['message'] = "Updated Successfully";
					echo json_encode($response);exit;
				}else{
					$response['message'] = "Not Updated";
					echo json_encode($response);exit;					
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCaddressadd( Request $request) {
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$ins = array("user_id"=>$_REQUEST['user_id'],"address"=>$_REQUEST['address'],"building"=>$_REQUEST['building'],"landmark"=>$_REQUEST['landmark'],"address_type"=>$_REQUEST['type'],"lat"=>$_REQUEST['lat'],'lang'=>$_REQUEST['lang']);

				/*$intable = \DB::table('abserve_user_address')
				->where('user_id','=',$_REQUEST['user_id'])
				->where('address_type','=',$_REQUEST['type'])
				->exists();*/
				$if_ins = \DB::table('abserve_user_address')->insert($ins);

					if($if_ins){
						// $if_ins = \DB::table('abserve_user_address')->insert($ins);
						$response['message']	= "Address added Successfully";
					}else{
						$response['message']	= "Address doesn't added";
					}

				echo json_encode($response);exit;				
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCaddressshow( Request $request){
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$addr = \DB::table('abserve_user_address')->select('*')->where('user_id','=',$_REQUEST['user_id'])->get();

				$response['user_address']	= $addr;
				echo json_encode($response);exit;
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCaddressedit( Request $request){
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$values = array("address_type"=>$_REQUEST['type'],"building"=>$_REQUEST['building'],"landmark"=>$_REQUEST['landmark'],"address"=>$_REQUEST['address'],"lat"=>$_REQUEST['lat'],'lang'=>$_REQUEST['lang']);
				\DB::table('abserve_user_address')->where('user_id','=',$_REQUEST['user_id'])->where('id','=',$_REQUEST['address_id'])->update($values);

				$response['user_address']	= "Address Saved";
				echo json_encode($response);exit;
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCaddressdel( Request $request){
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			'address_id'	=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){
				$adrrexist = \DB::table('abserve_user_address')->where('user_id','=',$_REQUEST['user_id'])->where('id', '=', $_REQUEST['address_id'])->exists();

				if($adrrexist){
					\DB::table('abserve_user_address')->where("user_id",'=',$_REQUEST['user_id'])->where('id', '=', $_REQUEST['address_id'])->delete();

					$response['message']	= "Address Deleted Successfully";
					echo json_encode($response);exit;
				}else{
					$response['message']	= "Address Doesn't Exists";
					echo json_encode($response);exit;
				}
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postOrderinsert( Request $request){
		
		$data 	=	$_REQUEST;
		unset($data['user_id']);
		$aFields = array('cust_id','total_price','s_tax','coupon_id','coupon_price','grand_total','res_id','address','building','landmark','lat','lang','delivery','delivery_type');

 		$cust_id=$_REQUEST['cust_id'];
		$removepost=DB::table('abserve_user_cart')->where('user_id', '=', $cust_id)->delete();
	
		$i=1;
		foreach ($data as $key => $name_value) {
			if(in_array($key, $aFields)){
					$keys[] = $key;
					$vals[] = $name_value;
					if($key != 'time'){
						$keys[] = 'time';
						$vals[] = time();
					}
					if($key != 'date'){
						$keys[] = 'date';
						$vals[] = date('Y-m-d');
					}
			}
			$i++;
		}

			$details_ins = (array_combine($keys, $vals));
			\DB::table('abserve_order_details')->insert($details_ins);
			$oid = \DB::getPdo()->lastInsertId();

			/*$order_details = \DB::table('abserve_order_details')
			->select('*')
			->where("cust_id",'=',$_REQUEST['cust_id'])
			->where("res_id",'=',$_REQUEST['res_id'])
			->where("status",'=',0)
			->get();*/


			$food_items = array_intersect_key($data, array_flip(array('food_item','food_id','quantity','price')));

			foreach ($food_items as $key => $value) {
				$items[$key]=(explode(',', $value));
				$coutn = count($items[$key]);
			}

			
			for ($i=0; $i <$coutn ; $i++) { 
				foreach ($items as $key => $value) {
					$rt[] = $key."=> ".$value[$i];
				}
				$final = implode(',', $rt);
				$first_array = explode(',',$final);
				$final_array = array();


				$pass = array("orderid"=>$oid);

				array_unshift($final_array, $pass);
				$rest = call_user_func_array('array_merge_recursive', $final_array);

				foreach($first_array as $arr){
				    $data = explode('=>',$arr);
				    $rest[$data[0]] = $data[1];
				}

				\DB::table('abserve_order_items')->insert($rest);
			}

			$ins_val = $_REQUEST;
			if($ins_val['price'] != ''){
				$ins_val['order_value'] = str_replace(",","+",$ins_val['price']);
				if($ins_val['s_tax'] != ''){
					$ins_val['order_value'] = $ins_val['order_value']."+".$ins_val['s_tax'];
				}if($ins_val['coupon_price'] != ''){
					$ins_val['order_value'] = $ins_val['order_value']."-".$ins_val['coupon_price'];
				}
			}
			for ($i=0; $i <$coutn ; $i++) { 
				foreach ($items as $ky => $vle) {
					$ins_val['order_details'][] = $items['quantity'][$i]."x".$items['food_item'][$i]."-".$items['price'][$i];
					$ins_val['orderid'] = $oid;
					$ins_val['order_status'] = 0;
				}
			}

			$ins_val['order_details'] = implode(',', array_unique($ins_val['order_details']));
			$ins = array_intersect_key($ins_val, array_flip(array('cust_id','res_id','order_value','order_details','orderid','order_status')));

			$pre = array_intersect_key($ins_val, array_flip(array('partner_id','order_value','order_details','orderid','order_status')));

			$var 	= $_REQUEST['res_id'];
			$sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$var;
			$ab_cu 	= \DB::select($sql2);

			\DB::table('abserve_orders_customer')->insert($ins);
			\DB::table('abserve_orders_partner')->insert($pre);

			\DB::table('abserve_orders_partner')
			->where('orderid', $oid)
			->update(['partner_id' => $ab_cu[0]->partner_id]);

			$appapi_details	= $this->appapimethod(2);
			$mobile_token 	= $this->userapimethod($ab_cu[0]->partner_id,'tb_users');
			$message 		= "New orders found in your restaurant";
			$app_name		= $appapi_details->app_name;
			$app_api 		= $appapi_details->api;

			 
			

			$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();
			
			if($note_id[0]->device == 'ios'){
			$this->iospushnotification($mobile_token,$message,'2');
		    }else{
		    $this->pushnotification($app_api,$mobile_token,$message,$app_name);	
		    }

            $response['message'] 		= "Success";
			echo json_encode($response);exit;
	}

	// cust_id=39&res_id=3&food_id=24&food_item=Mushroom Cppucino&price=240&quantity=2&total_price=240& coupon_price&coupon_id&s_tax=28&grand_total=268&address=Mahatma Gandhi Nagar Main Rd, Krishnapuram Colony, Madurai, Tamil Nadu 625014, India&building=22&landmark=
	public function getOrderinsert( Request $request){
		
		$data 	=	$_REQUEST;
		unset($data['user_id']);
		$aFields = array('cust_id','total_price','s_tax','coupon_id','coupon_price','grand_total','res_id','address','building','landmark');

 		$cust_id=$_REQUEST['cust_id'];
		$removepost=DB::table('abserve_user_cart')->where('user_id', '=', $cust_id)->delete();
	
			$i=1;
			foreach ($data as $key => $name_value) {
				if(in_array($key, $aFields)){
						$keys[] = $key;
						$vals[] = $name_value;
						if($key != 'time'){
							$keys[] = 'time';
							$vals[] = time();
						}
						if($key != 'date'){
							$keys[] = 'date';
							$vals[] = date('Y-m-d');
						}
				}
				$i++;
			}

			$details_ins = (array_combine($keys, $vals));
			\DB::table('abserve_order_details')->insert($details_ins);
			$oid = \DB::getPdo()->lastInsertId();

			/*$order_details = \DB::table('abserve_order_details')
			->select('*')
			->where("cust_id",'=',$_REQUEST['cust_id'])
			->where("res_id",'=',$_REQUEST['res_id'])
			->where("status",'=',0)
			->get();*/


			$food_items = array_intersect_key($data, array_flip(array('food_item','food_id','quantity','price')));

			foreach ($food_items as $key => $value) {
				$items[$key]=(explode(',', $value));
				$coutn = count($items[$key]);
			}

			
			for ($i=0; $i <$coutn ; $i++) { 
				foreach ($items as $key => $value) {
					$rt[] = $key."=> ".$value[$i];
				}
				$final = implode(',', $rt);
				$first_array = explode(',',$final);
				$final_array = array();


				$pass = array("orderid"=>$oid);

				array_unshift($final_array, $pass);
				$rest = call_user_func_array('array_merge_recursive', $final_array);

				foreach($first_array as $arr){
				    $data = explode('=>',$arr);
				    $rest[$data[0]] = $data[1];
				}

				\DB::table('abserve_order_items')->insert($rest);
			}

			$ins_val = $_REQUEST;
			if($ins_val['price'] != ''){
				$ins_val['order_value'] = str_replace(",","+",$ins_val['price']);
				if($ins_val['s_tax'] != ''){
					$ins_val['order_value'] = $ins_val['order_value']."+".$ins_val['s_tax'];
				}if($ins_val['coupon_price'] != ''){
					$ins_val['order_value'] = $ins_val['order_value']."-".$ins_val['coupon_price'];
				}
			}
			for ($i=0; $i <$coutn ; $i++) { 
				foreach ($items as $ky => $vle) {
					$ins_val['order_details'][] = $items['quantity'][$i]."x".$items['food_item'][$i]."-".$items['price'][$i];
					$ins_val['orderid'] = $oid;
					$ins_val['order_status'] = 0;
				}
			}

			$ins_val['order_details'] = implode(',', array_unique($ins_val['order_details']));
			$ins = array_intersect_key($ins_val, array_flip(array('cust_id','res_id','order_value','order_details','orderid','order_status')));

			$pre = array_intersect_key($ins_val, array_flip(array('partner_id','order_value','order_details','orderid','order_status')));

			$var 	= $_REQUEST['res_id'];
			$sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$var;
			$ab_cu 	= \DB::select($sql2);

			\DB::table('abserve_orders_customer')->insert($ins);
			\DB::table('abserve_orders_partner')->insert($pre);

			\DB::table('abserve_orders_partner')
			->where('orderid', $oid)
			->update(['partner_id' => $ab_cu[0]->partner_id]);

			$appapi_details	= $this->appapimethod(2);
			$mobile_token 	= $this->userapimethod($ab_cu[0]->partner_id,'tb_users');
			$message 		= "New orders found in your restaurant";
			$app_name		= $appapi_details->app_name;
			$app_api 		= $appapi_details->api;
			// print_r($appapi_details->api);exit;

			$this->pushnotification($app_api,$mobile_token,$message,$app_name);
			//$this->pushnotificationios($mobile_token,$message,$app_name);

            $response['message'] 		= "Success";
			echo json_encode($response);exit;
	}

	public function postOrdershow( Request $request){
			
		$order_details = "SELECT `ao`.`name`,`ao`.`logo`,`ao`.`location`,`ah`.`id`,`ah`.`res_id`,`ah`.`total_price`,`ah`.`s_tax`,`ah`.`coupon_price`,`ah`.`grand_total`,`ah`.`address`,`ah`.`building`,`ah`.`landmark`,`ah`.`coupon_id`,`ah`.`time`,`ah`.`date`,`aoc`.`order_status` as status from `abserve_order_details` as `ah` JOIN `abserve_restaurants` as `ao`  ON `ah`.`res_id`=`ao`.`id` LEFT JOIN `abserve_orders_customer` as `aoc` ON `ah`.`id`=`aoc`.`orderid` WHERE `ah`.`cust_id`=".$_REQUEST['user_id']." ORDER BY `ah`.`id` DESC";
		$det=\DB::select($order_details);

		$orde_det_item=array();
		foreach ($det as $key => $value) {
			$orde_det_item[]=(get_object_vars($value));
		}

		foreach ($orde_det_item as $key => &$value){
			if($value['logo'] != ''){
				$value['logo']=\URL::to('').'/uploads/restaurants/'.$value['logo'];
			}else{
				$value['logo']=\URL::to('').'/uploads/restaurants/Default_food.jpg';
			}
			$value['time']	 = date('H:m:s A',$value['time']);
			$iOrderId = $value['id'];
			$iBoyId = 0;
			$aBoyInfo = \DB::table('abserve_orders_boy')->select('*')->where('orderid',$iOrderId)->first();
			if(count($aBoyInfo) > 0){
				$iBoyId = $aBoyInfo->boy_id;
			}
			$value['boy_id']	 = $iBoyId;
		}
		$response['message']		= "Success";
		$response['order_details'] 	= $orde_det_item;
		echo json_encode($response);exit;
	}

	public function postNewordershow( Request $request){
			
		/*$order_details = "SELECT `ao`.`name`,`ao`.`logo`,`ao`.`location`,`ah`.`id`,`ah`.`res_id`,`ah`.`total_price`,`ah`.`s_tax`,`ah`.`coupon_price`,`ah`.`grand_total`,`ah`.`address`,`ah`.`building`,`ah`.`landmark`,`ah`.`coupon_id`,`ah`.`time`,`ah`.`date`,`aoc`.`order_status` as status from `abserve_order_details` as `ah` JOIN `abserve_restaurants` as `ao`  ON `ah`.`res_id`=`ao`.`id` LEFT JOIN `abserve_orders_customer` as `aoc` ON `ah`.`id`=`aoc`.`orderid` WHERE `ah`.`cust_id`=".$_REQUEST['user_id']." ORDER BY `ah`.`id` DESC";
		$det=\DB::select($order_details);*/

			$order_details = "SELECT `ao`.`name`,`ao`.`logo`,`ao`.`location`,`ah`.`id`,`ah`.`res_id`,`ah`.`total_price`,`ah`.`s_tax`,`ah`.`coupon_price`,`ah`.`grand_total`,`ah`.`address`,`ah`.`building`,`ah`.`landmark`,`ah`.`coupon_id`,`ah`.`time`,`ah`.`date`,`ah`.`lat`,`ah`.`lang`,`aoc`.`order_status`from `abserve_order_details` as `ah` JOIN `abserve_restaurants` as `ao`  ON `ah`.`res_id`=`ao`.`id` LEFT JOIN `abserve_orders_customer` as `aoc` ON `ah`.`id`=`aoc`.`orderid` WHERE `ah`.`cust_id`=".$_REQUEST['user_id']." ORDER BY `ah`.`id` DESC";
		$det=\DB::select($order_details);

		 

		$orde_det_item=array();
		foreach ($det as $key => $value) {
			$orde_det_item[]=(get_object_vars($value));
		}

		
		foreach ($orde_det_item as $key => & $value){

			if($value['id']!=''){
				$val_food=\DB::table('abserve_order_items')->select('food_id')->where('orderid',$value['id'])->first();
				$value['food_id'] = $val_food->food_id;

			}
			if($value['logo'] != ''){
				$value['logo']=\URL::to('').'/uploads/restaurants/'.$value['logo'];
			}else{
				$value['logo']=\URL::to('').'/uploads/restaurants/Default_food.jpg';
			}
			$value['time']	 = date('H:m:s A',$value['time']);
			$iOrderId = $value['id'];
			$iBoyId = 0;
			$aBoyInfo = \DB::table('abserve_orders_boy')->select('*')->where('orderid',$iOrderId)->first();
			if(count($aBoyInfo) > 0){
				$iBoyId = $aBoyInfo->boy_id;
				$iBoyordersts = $aBoyInfo->order_status;
			}
			$value['boy_id']	 = $iBoyId;
			if($iBoyordersts=='1' || $iBoyordersts=='2' || $iBoyordersts=='3' || $iBoyordersts=='4'){
			$value['boy_order_sts']	 = '1';
		    }else{
		    $value['boy_order_sts']	 = '0';
		    }
		}
		
		$response['message']		= "Success";
		$response['order_details'] 	= $orde_det_item;
		echo json_encode($response);exit;
	}

	public function postFooddetailes(Request $request){

		$foots_resid="SELECT `ar`.`orderid`,`ar`.`food_id`,`ar`.`food_item`,`ar`.`quantity`,`ar`.`price` from `abserve_order_items` as `ar`  JOIN `abserve_order_details` as `ah`   ON  `ah`.`id`=`ar`.`orderid` WHERE `ah`.`cust_id`='".$_REQUEST['user_id']."'  AND `ar`.`orderid` =".$_REQUEST['orderid'];

		$fot=\DB::select($foots_resid);

		$foot_logo_name=array();
		foreach ($fot as $key => $value) {
		$foot_logo_name[]=(get_object_vars($value));
		}
		$response['message']		= "Success";
		$response['foot_details'] 	= $foot_logo_name;
		echo json_encode($response);exit;
	}

	public function postRating( Request $request){

		$cust_id 						=$_REQUEST['cust_id'];
		$res_id 						=$_REQUEST['res_id'];
		$rating 						= $_REQUEST['rating'];

		$value= array("cust_id" => $cust_id,"res_id" => $res_id,"rating"=>$rating);
		$insert=\DB::table('abserve_rating')->insert($value);
	
		if($insert){

		$response['message'][]		= "Success";
		echo json_encode($response); exit;

		}
		else { 

		$response['message'][]		= "Failed";
		echo json_encode($response); exit;
		}	
	}

	public function getPartnernerorders( Request $request){
		
		$response = $whole_orders = array();
	
		$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($id_exists){
				$order_ids = \DB::table('abserve_orders_partner')
				->select('orderid')
				->where('partner_id','=',$_REQUEST['partner_id'])
				->whereNotIn('order_status',[5])
				->get();

				if($order_ids != '' && !empty($order_ids)){

					foreach ($order_ids as $key => $value) {
						$ids[] = get_object_vars($value);
					}

					foreach ($ids as $sub) {
						foreach ($sub as $k => $v) {
							$result[$k][] = '"'.$v.'"';
						}
					}

					$query = "SELECT `oi`.* FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders = \DB::select($query);

					if(!empty($orders)){
						foreach ($orders as $ke => $vals) {
							$ods[] = get_object_vars($vals);
						}

						foreach ($ods as $od => &$odval) {
							// $odval['time'] = date('Y-m-d H:i:s A', $odval['time']);
							foreach ($odval as $key_in => $value_in) {
								if($key_in == 'orderid'){
									$whole_orders[$value_in][] = $ods[$od];
								}
							}
						}
					}
					$pquery = "SELECT * FROM `abserve_orders_partner` AS `po` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `po`.`orderid` WHERE `po`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$porders = \DB::select($pquery);
					// echo $pquery."<pre>";print_r($porders);exit();

					$query1 = "SELECT `od`.`id`,`time`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price`,`op`.`order_status` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_partner` AS `op` ON `op`.`orderid` = `od`.`id` WHERE `oi`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders1 = \DB::select($query1);

					if(!empty($orders1)){
						foreach ($orders1 as $ke => $vals) {
							$ods1[] = get_object_vars($vals);
						}

						foreach ($ods1 as $od1 => &$odval1) {
							$odval1['time'] = date('H:i:s A', $odval1['time']);
							foreach ($odval1 as $key_in1 => $value_in1) {
								if($key_in1 == 'id'){
									$whole_orders1[$value_in1][] = $ods1[$od1];
								}
							}
						}

						foreach($whole_orders1 as $cnt => $cntval) {
							$array[$cnt][0]['count'] = sizeof($cntval); 
						}

						$first_elements = array_map(function($i) {
							return $i[0];
						}, $whole_orders1);

						foreach($array as $ey => $vaue){
							foreach($first_elements as $value2 => $vl){
								if($ey === $value2){
									$array[$ey][0]['id'] 		= $vl['id'];
									$array[$ey][0]['time'] 		= $vl['time'];
									$array[$ey][0]['status'] 	= $vl['status'];
									$array[$ey][0]['order_status'] = $vl['order_status'];
									$array[$ey][0]['total'] 	= $vl['total_price'];
									$array[$ey][0]['subtotal'] 	= $vl['grand_total'];
									$array[$ey][0]['tax'] 		= $vl['s_tax'];
									$array[$ey][0]['coupon'] 	= $vl['coupon_price'];
								}
							}
						}

						foreach($array as $kys_in => $vls_in){
							$whole_orders[$kys_in][] = $vls_in[0]; 
						}
					}

					$response['message'] 		= "New orders found";
					$response['orders_values'] 	= $whole_orders;
					// echo "<pre>";print_r($response);exit;
					echo json_encode($response);exit;

				}else{
					$response['message'] = "No orders found";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}		
	}

	public function postPartnernerorders( Request $request){
		
		$response = $whole_orders = array();
	
		$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($id_exists){
				$order_ids = \DB::table('abserve_orders_partner')
				->select('orderid')
				->where('partner_id','=',$_REQUEST['partner_id'])
				->whereNotIn('order_status',[5])
				->get();

				if($order_ids != '' && !empty($order_ids)){

					foreach ($order_ids as $key => $value) {
						$ids[] = get_object_vars($value);
					}

					foreach ($ids as $sub) {
						foreach ($sub as $k => $v) {
							$result[$k][] = '"'.$v.'"';
						}
					}

					$query = "SELECT `oi`.* FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders = \DB::select($query);

					if(!empty($orders)){
						foreach ($orders as $ke => $vals) {
							$ods[] = get_object_vars($vals);
						}

						foreach ($ods as $od => &$odval) {
							// $odval['time'] = date('Y-m-d H:i:s A', $odval['time']);
							foreach ($odval as $key_in => $value_in) {
								if($key_in == 'orderid'){
									$whole_orders[$value_in][] = $ods[$od];
								}
							}
						}
					}
					$pquery = "SELECT * FROM `abserve_orders_partner` AS `po` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `po`.`orderid` WHERE `po`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$porders = \DB::select($pquery);
					// echo $pquery."<pre>";print_r($porders);exit();

					$query1 = "SELECT `od`.`id`,`time`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price`,`op`.`order_status` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_partner` AS `op` ON `op`.`orderid` = `od`.`id` WHERE `oi`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders1 = \DB::select($query1);

					if(!empty($orders1)){
						foreach ($orders1 as $ke => $vals) {
							$ods1[] = get_object_vars($vals);
						}

						foreach ($ods1 as $od1 => &$odval1) {
							$odval1['time'] = date('H:i:s A', $odval1['time']);
							foreach ($odval1 as $key_in1 => $value_in1) {
								if($key_in1 == 'id'){
									$whole_orders1[$value_in1][] = $ods1[$od1];
								}
							}
						}

						foreach($whole_orders1 as $cnt => $cntval) {
							$array[$cnt][0]['count'] = sizeof($cntval); 
						}

						$first_elements = array_map(function($i) {
							return $i[0];
						}, $whole_orders1);

						foreach($array as $ey => $vaue){
							foreach($first_elements as $value2 => $vl){
								if($ey === $value2){
									$array[$ey][0]['id'] 		= $vl['id'];
									$array[$ey][0]['time'] 		= $vl['time'];
									$array[$ey][0]['status'] 	= $vl['status'];
									$array[$ey][0]['order_status'] = $vl['order_status'];
									$array[$ey][0]['total'] 	= $vl['total_price'];
									$array[$ey][0]['subtotal'] 	= $vl['grand_total'];
									$array[$ey][0]['tax'] 		= $vl['s_tax'];
									$array[$ey][0]['coupon'] 	= $vl['coupon_price'];
								}
							}
						}

						foreach($array as $kys_in => $vls_in){
							$whole_orders[$kys_in][] = $vls_in[0]; 
						}
					}

					$response['message'] 		= "New orders found";
					$response['orders_values'] 	= $whole_orders;
					// echo "<pre>";print_r($response);exit;
					echo json_encode($response);exit;

				}else{
					$response['message'] = "No orders found";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}		
	}

	public function postPartnernerorders_new( Request $request){
		
		$response = $whole_orders = array();
	
		$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($id_exists){
				$order_ids = \DB::table('abserve_orders_partner')
				->select('orderid')
				->where('partner_id','=',$_REQUEST['partner_id'])
				->where('order_status','=',0)
				->get();

				if($order_ids != '' && !empty($order_ids)){

					foreach ($order_ids as $key => $value) {
						$ids[] = get_object_vars($value);
					}

					foreach ($ids as $sub) {
						foreach ($sub as $k => $v) {
							$result[$k][] = $v;
						}
					}

					$query = "SELECT `oi`.* FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders = \DB::select($query);

					if(!empty($orders)){
						foreach ($orders as $ke => $vals) {
							$ods[] = get_object_vars($vals);
						}

						foreach ($ods as $od => &$odval) {
							// $odval['time'] = date('Y-m-d H:i:s A', $odval['time']);
							foreach ($odval as $key_in => $value_in) {
								if($key_in == 'orderid'){
									$whole_orders[$value_in][] = $ods[$od];
								}
							}
						}
					}
					$pquery = "SELECT * FROM `abserve_orders_partner` AS `po` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `po`.`orderid` WHERE `po`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$porders = \DB::select($pquery);
					// echo $pquery."<pre>";
					// print_r($porders);exit();

					$query1 = "SELECT `od`.`id`,`time`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders1 = \DB::select($query1);

					if(!empty($orders1)){
						foreach ($orders1 as $ke => $vals) {
							$ods1[] = get_object_vars($vals);
						}

						foreach ($ods1 as $od1 => &$odval1) {
							$odval1['time'] = date('H:i:s A', $odval1['time']);
							foreach ($odval1 as $key_in1 => $value_in1) {
								if($key_in1 == 'id'){
									$whole_orders1[$value_in1][] = $ods1[$od1];
								}
							}
						}

						foreach($whole_orders1 as $cnt => $cntval) {
							$array[$cnt][0]['count'] = sizeof($cntval); 
						}

						$first_elements = array_map(function($i) {
							return $i[0];
						}, $whole_orders1);

						foreach($array as $ey => $vaue){
							foreach($first_elements as $value2 => $vl){
								if($ey === $value2){
									$array[$ey][0]['id'] 		= $vl['id'];
									$array[$ey][0]['time'] 		= $vl['time'];
									$array[$ey][0]['status'] 	= $vl['status'];
									$array[$ey][0]['total'] 	= $vl['total_price'];
									$array[$ey][0]['subtotal'] 	= $vl['grand_total'];
									$array[$ey][0]['tax'] 		= $vl['s_tax'];
									$array[$ey][0]['coupon'] 	= $vl['coupon_price'];
								}
							}
						}

						foreach($array as $kys_in => $vls_in){
							$whole_orders[$kys_in][] = $vls_in[0]; 
						}
					}

					$response['message'] 		= "New orders found";
					$response['orders_values'] 	= $whole_orders;
					/*echo "<pre>";
					print_r($response);exit;*/
					echo json_encode($response);exit;

				}else{
					$response['message'] = "No orders found";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}		
	}

	public function postPartnernerorders_old( Request $request){
		
		$order_ids = \DB::table('abserve_orders_partner')
		->select('orderid')
		->where('partner_id','=',$_REQUEST['partner_id'])
		->where('order_status','=',0)
		->get();

		if($order_ids != '' && !empty($order_ids)){

			foreach ($order_ids as $key => $value) {
				$ids[] = get_object_vars($value);
			}

			foreach ($ids as $sub) {
				foreach ($sub as $k => $v) {
					$result[$k][] = $v;
				}
			}

			$query = "SELECT `oi`.* FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
			$orders = \DB::select($query);

			foreach ($orders as $ke => $vals) {
				$ods[] = get_object_vars($vals);
			}

			foreach ($ods as $od => &$odval) {
				// $odval['time'] = date('Y-m-d H:i:s A', $odval['time']);
				foreach ($odval as $key_in => $value_in) {
					if($key_in == 'orderid'){
						$whole_orders[$value_in][] = $ods[$od];
					}
				}
			}

			$query1 = "SELECT `od`.`id`,`time`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
			$orders1 = \DB::select($query1);

			foreach ($orders1 as $ke => $vals) {
				$ods1[] = get_object_vars($vals);
			}

			foreach ($ods1 as $od1 => &$odval1) {
				$odval1['time'] = date('H:i:s A', $odval1['time']);
				foreach ($odval1 as $key_in1 => $value_in1) {
					if($key_in1 == 'id'){
						$whole_orders1[$value_in1][] = $ods1[$od1];
					}
				}
			}

			foreach($whole_orders1 as $cnt => $cntval) {
				$array[$cnt][0]['count'] = sizeof($cntval); 
			}

			$first_elements = array_map(function($i) {
				return $i[0];
			}, $whole_orders1);

			foreach($array as $ey => $vaue){
				foreach($first_elements as $value2 => $vl){
					if($ey === $value2){
						$array[$ey][0]['id'] 		= $vl['id'];
						$array[$ey][0]['time'] 		= $vl['time'];
						$array[$ey][0]['status'] 	= $vl['status'];
						$array[$ey][0]['total'] 	= $vl['total_price'];
						$array[$ey][0]['subtotal'] 	= $vl['grand_total'];
						$array[$ey][0]['tax'] 		= $vl['s_tax'];
						$array[$ey][0]['coupon'] 	= $vl['coupon_price'];
					}
				}
			}

			foreach($array as $kys_in => $vls_in){
				$whole_orders [$kys_in][]=$vls_in[0]; 
			}

			$response['message'] 		= "New orders found";
			$response['orders_values'] 	= $whole_orders;
			// echo "<pre>";
			// print_r($response);exit;
			echo json_encode($response);exit;

		}else{
			$response['message'] = "No orders found";
			echo json_encode($response);exit;
		}
	}

	public function postPorderhistory( Request $request){
		// print_r($_REQUEST);exit;
		/*$orders = \DB::table('abserve_orders_partner')
		->where('partner_id','=',$_REQUEST['partner_id'])
		->where('order_status','!=',0)
		->get();*/

		$order_ids = \DB::table('abserve_orders_partner')
		->select('orderid')
		->where('partner_id','=',$_REQUEST['partner_id'])
		->where('order_status','=',0)
		->get();

		// if($order_ids != '' && !empty($order_ids)){

			foreach ($order_ids as $key => $value) {
				$ids[] = get_object_vars($value);
			}

			foreach ($ids as $sub) {
				foreach ($sub as $k => $v) {
					$result[$k][] = $v;
				}
			}

		$query1 = "SELECT `od`.`id`,`time`,`status`,`oi`.* FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
			$orders = \DB::select($query1);

		$response['message'] = "Success";
		$response['confirmed_orders'] = $orders;
		echo json_encode($response);exit;
	}

	public function postOffers( Request $request){

		$credit_codes = \DB::table('abserve_user_credit')->select('*')->where('to_id','=',$_REQUEST['user_id'])->get();

		if(!empty($credit_codes)){
			$response['message'] = "Success";
		}else{
			$response['message'] = "Null";
		}
		$response['credit_codes'] = $credit_codes;
		echo json_encode($response);exit;
	}

	//order_status = 1(Partner,Customer) status = 1(Order Detail)Push_notification for customer
	public function postPorderaccept1( Request $request){
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>1]);
						$abc = \DB::table('abserve_orders_customer')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>1]);
						$abc1 = \DB::table('abserve_order_details')
						->where('id','=',$_REQUEST['order_id'])
						->update(['status'=>1]);
						if($abp && $abc){
							$cust_id = \DB::table('abserve_order_details')->select('cust_id')->where('id','=',$_REQUEST['order_id'])->get();
							$appapi_details	= $this->appapimethod(1);
							$mobile_token 	= $this->userapimethod($cust_id[0]->cust_id,'tb_users');
							$message 		= "Your order Hasbeen Accepted";
							$app_name		= $appapi_details->app_name;
							$app_api 		= $appapi_details->api;
							// print_r($appapi_details->api);exit;
							$this->pushnotification($app_api,$mobile_token,$message,$app_name);
							//$this->pushnotificationios($mobile_token,$message,$app_name);
							$response['message'] = "Order Accepted";
							echo json_encode($response);exit;
						}else{
							$response['message'] = "Order Doesn't Accepted";
							echo json_encode($response);exit;
						}
					}else{
						$response['message'] = "It's Not your Order";
						echo json_encode($response);exit;		
					}
				}else{
					$response['message'] = "No Such Order found";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postPorderaccept( Request $request){
		// print_r($request->all());exit;
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
		);	
		
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){

					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
						$abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
						$order_data = \DB::table('abserve_order_details')->select('cust_id','res_id')->where('id','=',$_REQUEST['order_id'])->get();
						$boy_assign_id = $this->getPassignorder($_REQUEST['partner_id'],$_REQUEST['order_id'],$order_data[0]->cust_id,$order_data[0]->res_id);

						//order assign id
						$oassignexists = \DB::table('abserve_order_assign')->where('order_id',$_REQUEST['order_id'])->first();
						if($oassignexists === null){
							\DB::table('abserve_order_assign')->insert(['assign_id'=>$boy_assign_id['inserted_id'],'order_id'=>$_REQUEST['order_id']]);
						}

						$order_datas = $this->Order_data($_REQUEST['order_id'],'');

						// Customer notification
						$appapi_details	= $this->appapimethod(1);
						$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
						$message 		= $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						
						$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
						if($note_id[0]->device == 'ios'){
						$this->iospushnotification($mobile_token,$message,'1');
				    	}else{
				    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
				    	}


						//DeliveryBoy Notification
						$appapi_details	= $this->appapimethod(3);
						$mobile_token 	= $this->userapimethod($boy_assign_id['boy_id'],'abserve_deliveryboys');
						$message 		= "Your have new Order";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						//$this->pushnotification2($app_api,$mobile_token,$message,$app_name);
						$note_id = \DB::table('abserve_deliveryboys')->select('device')->where('id',$boy_assign_id['boy_id'])->get();
						if($note_id[0]->device == 'ios'){
						$this->iospushnotification($mobile_token,$message,'3');
					    }else{
					    $this->pushnotification2($app_api,$mobile_token,$message,$app_name);	
					    }

						$boy_data = \DB::table('abserve_deliveryboys')->select('username','phone_number')->where('id',$boy_assign_id['boy_id'])->first();

						$response['id'] 	 = '1';
						$response['message'] = "Order accepted and assigned to ".$boy_data->username;
						$response['order_status'] = "1";
					}else{
						$response['id'] 	 = '2';
						$response['message'] = "It's Not your Order";	
					}
				}else{
					$response['id'] 	 = '2';
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['id'] 	 = '2';
				$response['message'] = "UserID Doesn't exists";		
			}
		}else {	
			$response['id'] 	 	= '5';	
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		//print_r($response);exit;
		echo json_encode($response); exit;
	}

	public function getPorderaccept( Request $request){
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
						$abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);

						$order_data = \DB::table('abserve_order_details')->select('cust_id','res_id')->where('id','=',$_REQUEST['order_id'])->get();
						$boy_assign_id = $this->getPassignorder($_REQUEST['partner_id'],$_REQUEST['order_id'],$order_data[0]->cust_id,$order_data[0]->res_id);

						//order assign id
						$oassignexists = \DB::table('abserve_order_assign')->where('order_id',$_REQUEST['order_id'])->first();
						if($oassignexists === null){
							\DB::table('abserve_order_assign')->insert(['assign_id'=>$boy_assign_id['inserted_id'],'order_id'=>$_REQUEST['order_id']]);
						}

						$order_datas = $this->Order_data($_REQUEST['order_id'],'');
						//echo "<pre>";print_r($order_datas);exit();

						// Customer notification
						$appapi_details	= $this->appapimethod(1);
						$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
						$message 		= $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						
						$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
						if($note_id[0]->device == 'ios'){
						$this->iospushnotification($mobile_token,$message,'1');
					    }else{
					    $this->pushnotification($app_api,$mobile_token,$message,$app_name);	
				    
}

						//DeliveryBoy Notification
						$appapi_details	= $this->appapimethod(3);
						$mobile_token 	= $this->userapimethod($boy_assign_id['boy_id'],'abserve_deliveryboys');
						$message 		= "Your have new Order";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						
						$note_id = \DB::table('abserve_deliveryboys')->select('device')->where('id',$boy_assign_id['boy_id'])->get();
						if($note_id[0]->device == 'ios'){
						$this->iospushnotification($mobile_token,$message,'3');
					    }else{
					    $this->pushnotification($app_api,$mobile_token,$message,$app_name);	
					    }

						$boy_data = \DB::table('abserve_deliveryboys')->select('username','phone_number')->where('id',$boy_assign_id['boy_id'])->first();

						$response['id'] 	 = '1';
						$response['message'] = "Order accepted and assigned to ".$boy_data->username;
						$response['order_status'] = "1";
					}else{
						$response['id'] 	 = '2';
						$response['message'] = "It's Not your Order";	
					}
				}else{
					$response['id'] 	 = '2';
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['id'] 	 = '2';
				$response['message'] = "UserID Doesn't exists";		
			}
		}else {	
			$response['id'] 	 	= '5';	
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	public function getPorderaccept_old( Request $request){
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
		);

				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);

						$order_data = \DB::table('abserve_order_details')->select('cust_id','res_id')->where('id','=',$_REQUEST['order_id'])->get();
						$boy_assign_id = $this->getPassignorder($_REQUEST['partner_id'],$_REQUEST['order_id'],$order_data[0]->cust_id,$order_data[0]->res_id);

						//order assign id
						$oassignexists = \DB::table('abserve_order_assign')->where('order_id',$_REQUEST['order_id'])->first();
						if($oassignexists === null){
							\DB::table('abserve_order_assign')->insert(['assign_id'=>$boy_assign_id['inserted_id'],'order_id'=>$_REQUEST['order_id']]);
						}

						//DeliveryBoy Notification
						$appapi_details	= $this->appapimethod(3);
						$mobile_token 	= $this->userapimethod($boy_assign_id['boy_id'],'abserve_deliveryboys');
						$message 		= "Your heve new Order";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						
						$note_id = \DB::table('abserve_deliveryboys')->select('device')->where('id',$boy_assign_id['boy_id'])->get();
						if($note_id[0]->device == 'ios'){
						$this->iospushnotification($mobile_token,$message,'3');
					    }else{
					    $this->pushnotification($app_api,$mobile_token,$message,$app_name);	
					    }

						$boy_data = \DB::table('abserve_deliveryboys')->select('username','phone_number')->where('id',$boy_assign_id['boy_id'])->first();

						$response['id'] 	 = '1';
						$response['message'] = "Order accepted and assigned to ".$boy_data->username;
						$response['order_status'] = "1";
					}else{
						$response['id'] 	 = '2';
						$response['message'] = "It's Not your Order";	
					}
				}else{
					$response['id'] 	 = '2';
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['id'] 	 = '2';
				$response['message'] = "UserID Doesn't exists";		
			}
		}else {	
			$response['id'] 	 	= '5';	
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	//order_status = 5(Partner,Customer)Push_notification for customer
	public function postPorderreject( Request $request){
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>5]);
						$abc = \DB::table('abserve_orders_customer')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>5]);
						$abc1 = \DB::table('abserve_order_details')
						->where('id','=',$_REQUEST['order_id'])
						->update(['status'=>5]);
						if($abp && $abc){
							$response['order_status'] 	= "5";
							$response['message'] 		= "Order Rejected";
							$response['id'] 			= "5";

							//Get Order data
								$order_datas = $this->Order_data($_REQUEST['order_id'],'');

								// Customer notification
								$appapi_details	= $this->appapimethod(1);
								$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
								$message 		= $order_datas->name." rejected  your order ";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;

								$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
								if($note_id[0]->device == 'ios'){
								$this->iospushnotification($mobile_token,$message,'1');
							    }else{
							    $this->pushnotification($app_api,$mobile_token,$message,$app_name);	
							    }
								
						}else{
							$response['id'] 	 = "2";
							$response['message'] = "Order Doesn't Rejected";
						}
					}else{
						$response['id'] 	 = "3";
						$response['message'] = "It's Not your Order";	
					}
				}else{
					$response['id'] 	 = "3";
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['id'] 	 = "3";
				$response['message'] = "UserID Doesn't exists";		
			}
		}else {
			$response['id']			= "5";
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	public function getPassignorder($partner_id,$order_id,$cust_id,$res_id){
		$return = array();
		$radius	= 5;
		$res_id	= \DB::table('abserve_order_details')->select('res_id')->where('id',$order_id)->first();
		$res_details = \DB::table('abserve_restaurants')->select('id','latitude','longitude')->where('id',$res_id->res_id)->first();
		$order_datas = \DB::table('abserve_orders_customer')->select('*')->where('orderid',$order_id)->first();

		//get nearby delivery boys
		$boys	= $this->nearbyDeliveryBoys($radius,$res_details->latitude,$res_details->longitude,$order_id);
		$random_id	= array_rand($boys,1);

		$return['boy_id']	= $boys[0]->id;//$boys[$random_id]->id;
		$orderAlredy 		= \DB::table('abserve_boyorderstatus')->where('oid',$order_id)->exists();

		if($orderAlredy){
			$orderData 	= \DB::table('abserve_boyorderstatus')->select('*')->where('oid',$order_id)->first();
			\DB::table('abserve_boyorderstatus')->where('id',$orderData->id)->update(['bid'=>$boys[$random_id]->id,'status'=>0]);
			$return['inserted_id']	= $orderData->id;
		} else {
			$return['inserted_id']	= \DB::table('abserve_boyorderstatus')->insertGetId(['bid'=>$boys[$random_id]->id,'oid'=>$order_id,'pid'=>$partner_id,'cid'=>$cust_id,'rid'=>$res_id->res_id,'status'=>0]);
		}
		return $return;
	}

	public function nearbyDeliveryBoys($radius,$latitude,$longitude,$order_id){
		$hav = $lat_lng = $bids_check = '';$orderDeclinedBoys = [];

		$orderAlredyDeclined = \DB::table('abserve_boyorderstatus')->where('status','2')->where('oid',$order_id)->exists();
		$orderAlredyAccepted = \DB::table('abserve_boyorderstatus')->where('status','1')->where('oid',$order_id)->exists();
		$orderAlredyAssigned = \DB::table('abserve_boyorderstatus')->where('status','0')->where('oid',$order_id)->exists();

		//extra query to update the main table
		\DB::table('abserve_order_details')->where('id',$order_id)->update(['status'=>2]);
		$assignedBoyData = \DB::table('abserve_deliveryboys')
						->select('abserve_deliveryboys.username')
						->leftjoin('abserve_boyorderstatus','abserve_boyorderstatus.bid','=','abserve_deliveryboys.id')
						->where('abserve_boyorderstatus.oid',$order_id)->first();

		if($orderAlredyAccepted){
			$response['message'] = "Order already accepted by delivery executive ".$assignedBoyData->username;
			$response['id'] = '2';
			echo json_encode($response);exit;
		} else if($orderAlredyDeclined) {
			$orderDeclinedBoys = \DB::table('abserve_boyorderstatus')->select(\DB::raw('GROUP_CONCAT(QUOTE( `bid` )) as bids'))->where('status','2')->where('oid',$order_id)->first();
		} else if($orderAlredyAssigned) {
			$response['message'] = "Order already assigned to delivery executive ".$assignedBoyData->username;
			$response['id'] = '2';
			echo json_encode($response);exit;			
		}

		if(!empty($orderDeclinedBoys)){
			if($orderDeclinedBoys->bids){
				$bids_check = " AND `abserve_deliveryboys`.id NOT IN (".$orderDeclinedBoys->bids.")";
			}
		}

		if($radius != ''){
			$lat_lng= ", ( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= " HAVING distance <= ".$radius." ORDER BY distance ";
		}

		$select	= '`abserve_deliveryboys`.id,email,username,boy_status,phone_number,mobile_token,latitude,longitude';
		$sql = "SELECT ".$select.$lat_lng." FROM `abserve_deliveryboys` WHERE `boy_status` = '0' ".$bids_check.$hav;
		$this->free_boys= \DB::select($sql);
		if(empty($this->free_boys)){
			$this->nearbyDeliveryBoys('',$latitude,$longitude,$order_id);
			return $this->free_boys;
		} else {
			return $this->free_boys;
		}
	}

	public function getPartnerconfirmedorders( Request $request){
		
		$response = array();
	
		$rules = array(
			'partner_id'	=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0'";
				$confirmed_orders_count = \DB::select($query);
				if(!empty($confirmed_orders_count) && $confirmed_orders_count != ''){
					foreach ($confirmed_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value); 
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`oi`.`orderid`,`od`.`time`,`grand_total` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0' GROUP BY `oi`.`orderid`";
					$confirmed_orders = \DB::select($query1);

					foreach ($confirmed_orders as $key => $value) {
						$value->time = date('H:m:s A',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] = "Success";
					$response['confirmed_orders'] = $confirmed_orders;
					/*echo "<pre>";
					print_r($response);exit;*/
					echo json_encode($response);exit;
				}else{
					$response['message'] = "Confirmed Orders Doesn't exists";
					$response['confirmed_orders'] = '';
					echo json_encode($response);exit;	
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postPartnerconfirmedorders( Request $request){
		
		$response = array();
	
		$rules = array(
			'partner_id'	=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0'";
				$confirmed_orders_count = \DB::select($query);
				if(!empty($confirmed_orders_count) && $confirmed_orders_count != ''){
					foreach ($confirmed_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value); 
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`oi`.`orderid`,`od`.`time`,`grand_total` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0' GROUP BY `oi`.`orderid`";
					$confirmed_orders = \DB::select($query1);

					foreach ($confirmed_orders as $key => $value) {
						$value->time = date('H:m:s A',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] = "Success";
					$response['confirmed_orders'] = $confirmed_orders;
					/*echo "<pre>";
					print_r($response);exit;*/
					echo json_encode($response);exit;
				}else{
					$response['message'] = "Confirmed Orders Doesn't exists";
					$response['confirmed_orders'] = '';
					echo json_encode($response);exit;	
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postNewpartnerconfirmedorders( Request $request){
		
		$response = array();
	
		$rules = array(
			'partner_id'	=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0'";
				$confirmed_orders_count = \DB::select($query);
				if(!empty($confirmed_orders_count) && $confirmed_orders_count != ''){
					foreach ($confirmed_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value); 
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`oi`.`orderid`,`od`.`time`,`grand_total` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0' GROUP BY `oi`.`orderid` ORDER BY `oi`.`orderid` DESC";
					$confirmed_orders = \DB::select($query1);

					foreach ($confirmed_orders as $key => $value) {
						$value->time = date('H:m:s A',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] = "Success";
					$response['confirmed_orders'] = $confirmed_orders;
					/*echo "<pre>";
					print_r($response);exit;*/
					echo json_encode($response);exit;
				}else{
					$response['message'] = "Confirmed Orders Doesn't exists";
					$response['confirmed_orders'] = [];
					echo json_encode($response);exit;	
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoystatus( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$boy_status = \DB::table('abserve_deliveryboys')
			->select('boy_status')
			->where('id','=',$_REQUEST['boy_id'])
			->get();

			$response['message'] 	= "Success";
			$response['boy_status'] = $boy_status;
			echo json_encode($response);exit;
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function getAllboystatus( Request $request){
		$boy_status = \DB::statement("SELECT `abserve_deliveryboys`.`email`,`username`,`boy_status`,`phone_number`,`mobile_token`,`latitude`,`longitude` FROM abserve_deliveryboys WHERE `abserve_deliveryboys`.`boy_status` = 0 ");
		/*$boy_status = \DB::table('abserve_deliveryboys')
		->select('email','username','boy_status','phone_number','mobile_token','latitude','longitude')
		->where('boy_status','0')
		->get();*/

		$response['message'] 	= "Success";
		$response['boy_status'] = $boy_status;
		echo "<pre>";print_r($response);exit();
		echo json_encode($response);exit;
	}

	public function getBoystatus( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$boy_status = \DB::table('abserve_deliveryboys')
			->select('boy_status')
			->where('id','=',$_REQUEST['boy_id'])
			->get();

			$response['message'] 	= "Success";
			$response['boy_status'] = $boy_status;
			echo json_encode($response);exit;
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyneworders1( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$orders_ids = \DB::table('abserve_orders_boy')
			->select('*')
			->where('boy_id','=',$_REQUEST['boy_id'])
			->where('order_status','=',0)
			->get();

			if($orders_ids != '' && !empty($orders_ids)){

				foreach ($orders_ids as $key => $value) {
					$ids[] = $value->orderid;
				}

				$query = "SELECT `oi`.`orderid`,`res_id`,`food_id`,`food_item`,`od`.`address`,`building`,`landmark`,`time` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_boy` AS `bo` ON `od`.`id` = `bo`.`orderid` WHERE `od`.`id` IN (".implode(',', $ids).")  AND `bo`.`order_status` = '0' ORDER BY `od`.`time` DESC";
				$orders = \DB::select($query);

				foreach ($orders as $key => $value) {
					foreach ($value as $key_in => $value_in) {
						if($key_in == 'orderid'){
							$restaurants[$value_in][] = $orders[$key];
						}
					}
				}

				foreach ($orders as $key => $value) {
					$value->cust_address = $value->building.",".$value->landmark.",".$value->address;
					$value->time = date('H:i:s A', $value->time);
				}

				$orders = array_reduce($orders, function ($carry, $item) {
				if (!isset($carry[$item->orderid])) {
					$carry[$item->orderid] = $item;
				} else {
					$carry[$item->orderid]->food_item .= ',' . $item->food_item;
				}
				return $carry;
				}, array());

				foreach ($restaurants as $key => $value) {
					$orders[$key]->count = sizeof($value);
				}

				$query1 = "SELECT DISTINCT(`od`.`res_id`) FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_boy` AS `bo` ON `od`.`id` = `bo`.`orderid` WHERE `od`.`id` IN (".implode(',', $ids).") AND `bo`.`order_status` = '0' ORDER BY `od`.`time` DESC";
				$orders1 = \DB::select($query1);

				foreach ($orders1 as $key => $value) {
					$res_ids[] = $value->res_id;
				}

				$qry = "SELECT `ar`.`name`,`location`,`ao`.`id` FROM `abserve_restaurants` AS `ar` JOIN `abserve_order_details` AS `ao` ON `ar`.`id` = `ao`.`res_id` WHERE `ar`.`id` IN (".implode(',', $res_ids).") AND `ao`.`id` IN (".implode(',', $ids).")";
				$res_names = \DB::select($qry);

				foreach ($orders as $key => $value) {
					foreach ($res_names as $key_in => $value_in) {
						if($value_in->id === $value->res_id){
							$value->res_name = $value_in->name;
							$value->location = $value_in->location;
						}
					}
					if($value->orderid === $value->orderid)
						$orders_final[$value->orderid][] = $value;
				}

				$response['message'] 	= "New orders found";
				$response['new_orders']	= $orders_final;
				// echo "<pre>";
				// print_r($response);exit;
				echo json_encode($response);exit;

			}else{
				$response['message'] = "No orders found";
				$response['new_orders']	= '';
				echo json_encode($response);exit;
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyneworders( Request $request){

		$rules = array(
			'boy_id'		=>'required',
		);

		$boy_id = $_REQUEST['boy_id'];
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$boy_exists = \DB::table('abserve_deliveryboys')->where('id',$boy_id)->exists();
			if($boy_exists){
				$orders_final =[];
				$orders_ids = \DB::table('abserve_boyorderstatus')->select(\DB::raw('GROUP_CONCAT(QUOTE( `oid` )) as oids'))->where('status','0')->where('bid',$boy_id)->first();

				if(!empty($orders_ids)){
					$cond = '';
					if(!empty($orders_ids->oids)){
						$cond = "AND `od`.`id` IN (".$orders_ids->oids.")";
						$cond1 = "AND `ao`.`id` IN (".$orders_ids->oids.")";
						$query = "SELECT `oi`.`orderid`,`res_id`,`food_id`,`food_item`,`od`.`address`,`building`,`landmark`,`time` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `bo`.`status` = '0' ".$cond." ORDER BY `od`.`time` DESC";
						$orders = \DB::select($query);
						
						foreach ($orders as $key => $value) {
							
							foreach ($value as $key_in => $value_in) {
								if($key_in == 'orderid'){
									$restaurants[$value_in][] = $orders[$key];
								}

							}
						}
				
						foreach ($orders as $key => $value) {
							$value->cust_address = $value->building.",".$value->landmark.",".$value->address;
							$value->time = date('H:i:s A', $value->time);
						}

						$orders = array_reduce($orders, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						} else {
							$carry[$item->orderid]->food_item .= ',' . $item->food_item;
						}
						return $carry;
						}, array());
						$restaurants=[];
						foreach ($restaurants as $key => $value) {
							$orders[$key]->count = sizeof($value);
						}

						$query1 = "SELECT DISTINCT(`od`.`res_id`) FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `bo`.`status` = '0' ".$cond." ORDER BY `od`.`time` DESC";
						$orders1 = \DB::select($query1);

					   $res_ids=[];
						foreach ($orders1 as $key => $value) {

							$res_ids[] = $value->res_id;

						}
						
						$qry = "SELECT `ar`.`id` as res_id,`ar`.`name`,`location`,`ao`.`id` FROM `abserve_restaurants` AS `ar` JOIN `abserve_order_details` AS `ao` ON `ar`.`id` = `ao`.`res_id` WHERE `ar`.`id` IN (".implode(',', $res_ids).") ".$cond1;
					
						$res_names = \DB::select($qry);
					

						foreach ($orders as $key => $value) {
							foreach ($res_names as $key_in => $value_in) {
								if($value_in->res_id === $value->res_id){
									$value->res_name = $value_in->name;
									$value->location = $value_in->location;
								}
							}
							if($value->orderid === $value->orderid)
								$orders_final[$value->orderid][] = $value;
						}
						$response['message'] 	= "New orders found";

						$response['new_orders']	= $orders_final;
						// echo "<pre>";
						// print_r($response);exit;
					}else{
						$response['message']	= "No orders found";
						$response['new_orders']	= '';
					}
				}else{
					$response['message']	= "No orders found";
					$response['new_orders']	= '';
				}
			} else {
				$response['message']	= "UserID Doesn't exists";
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	public function postBoyneworders_new_old( Request $request){

		$rules = array(
			'boy_id'		=>'required',
		);

		$boy_id = $_REQUEST['boy_id'];
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$orders_ids = \DB::table('abserve_boyorderstatus')->select(\DB::raw('GROUP_CONCAT(QUOTE( `oid` )) as oids'))->where('status','0')->where('bid',$boy_id)->first();

			if(!empty($orders_ids)){

				$query = "SELECT `oi`.`orderid`,`res_id`,`food_id`,`food_item`,`od`.`address`,`building`,`landmark`,`time` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `od`.`id` IN (".$orders_ids->oids.")  AND `bo`.`status` = '0' ORDER BY `od`.`time` DESC";
				$orders = \DB::select($query);
				foreach ($orders as $key => $value) {
					foreach ($value as $key_in => $value_in) {
						if($key_in == 'orderid'){
							$restaurants[$value_in][] = $orders[$key];
						}
					}
				}

				foreach ($orders as $key => $value) {
					$value->cust_address = $value->building.",".$value->landmark.",".$value->address;
					$value->time = date('H:i:s A', $value->time);
				}

				$orders = array_reduce($orders, function ($carry, $item) {
				if (!isset($carry[$item->orderid])) {
					$carry[$item->orderid] = $item;
				} else {
					$carry[$item->orderid]->food_item .= ',' . $item->food_item;
				}
				return $carry;
				}, array());

				foreach ($restaurants as $key => $value) {
					$orders[$key]->count = sizeof($value);
				}

				$query1 = "SELECT DISTINCT(`od`.`res_id`) FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `od`.`id` IN (".$orders_ids->oids.") AND `bo`.`status` = '0' ORDER BY `od`.`time` DESC";
				$orders1 = \DB::select($query1);
				foreach ($orders1 as $key => $value) {
					$res_ids[] = $value->res_id;
				}

				$qry = "SELECT `ar`.`id` as res_id,`ar`.`name`,`location`,`ao`.`id` FROM `abserve_restaurants` AS `ar` JOIN `abserve_order_details` AS `ao` ON `ar`.`id` = `ao`.`res_id` WHERE `ar`.`id` IN (".implode(',', $res_ids).") AND `ao`.`id` IN (".$orders_ids->oids.")";
				$res_names = \DB::select($qry);

				foreach ($orders as $key => $value) {
					foreach ($res_names as $key_in => $value_in) {
						if($value_in->res_id === $value->res_id){
							$value->res_name = $value_in->name;
							$value->location = $value_in->location;
						}
					}
					if($value->orderid === $value->orderid)
						$orders_final[$value->orderid][] = $value;
				}

				$response['message'] 	= "New orders found";
				$response['new_orders']	= $orders_final;
				// echo "<pre>";
				// print_r($response);exit;

			}else{
				$response['message'] = "No orders found";
				$response['new_orders']	= '';
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	public function getBoyneworders_old( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			$orders_ids = \DB::table('abserve_orders_boy')
			->select('*')
			->where('boy_id','=',$_REQUEST['boy_id'])
			->where('order_status','=',0)
			->get();
			// echo "<pre>";print_r($orders_ids);exit();
			if(count($orders_ids) > 0 && !empty($orders_ids)){

				foreach ($orders_ids as $key => $value) {
					$ids[] = $value->orderid;
				}

				$query = "SELECT `oi`.`orderid`,`res_id`,`food_id`,`food_item`,`od`.`address`,`building`,`landmark`,`time` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_boy` AS `bo` ON `od`.`id` = `bo`.`orderid` WHERE `od`.`id` IN (".implode(',', $ids).")  AND `bo`.`order_status` = '0' ORDER BY `od`.`time` DESC";
				$orders = \DB::select($query);

				foreach ($orders as $key => $value) {
					foreach ($value as $key_in => $value_in) {
						if($key_in == 'orderid'){
							$restaurants[$value_in][] = $orders[$key];
						}
					}
				}

				foreach ($orders as $key => $value) {
					$value->cust_address = $value->building.",".$value->landmark.",".$value->address;
					$value->time = date('H:i:s A', $value->time);
				}

				$orders = array_reduce($orders, function ($carry, $item) {
				if (!isset($carry[$item->orderid])) {
					$carry[$item->orderid] = $item;
				} else {
					$carry[$item->orderid]->food_item .= ',' . $item->food_item;
				}
				return $carry;
				}, array());

				// echo "<pre>";print_r($restaurants);exit();
				foreach ($restaurants as $key => $value) {
					$orders[$key]->count = sizeof($value);
				}

				$query1 = "SELECT DISTINCT(`od`.`res_id`) FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_boy` AS `bo` ON `od`.`id` = `bo`.`orderid` WHERE `od`.`id` IN (".implode(',', $ids).") AND `bo`.`order_status` = '0' ORDER BY `od`.`time` DESC";
				$orders1 = \DB::select($query1);

				foreach ($orders1 as $key => $value) {
					$res_ids[] = $value->res_id;
				}

				$qry = "SELECT `ar`.`name`,`location`,`ao`.`id` FROM `abserve_restaurants` AS `ar` JOIN `abserve_order_details` AS `ao` ON `ar`.`id` = `ao`.`res_id` WHERE `ar`.`id` IN (".implode(',', $res_ids).") AND `ao`.`id` IN (".implode(',', $ids).")";
				$res_names = \DB::select($qry);

				foreach ($orders as $key => $value) {
					foreach ($res_names as $key_in => $value_in) {
						if($value_in->id === $value->res_id){
							$value->res_name = $value_in->name;
							$value->location = $value_in->location;
						}
					}
					if($value->orderid === $value->orderid)
						$orders_final[$value->orderid][] = $value;
				}

				$response['message'] 	= "New orders found";
				$response['new_orders']	= $orders_final;
				echo "<pre>";
				print_r($response);exit;
				echo json_encode($response);exit;

			}else{
				$response['message'] = "No orders found";
				$response['new_orders']	= '';
				echo json_encode($response);exit;
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	//order_status = 2(Partner),1(Boy)
	public function postBorderacceptdecline1( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required',
			'status'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->exists();
					if($acess){
						$order_status = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->where('status',0)->exists();
						if($order_status){
							$update = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->update(['status'=>$_REQUEST['status']]);
							if($_REQUEST['status'] == 1){
								$status		 = "accepted";
								//Order data
								$order_datas = \DB::table('abserve_order_details')
												->select('abserve_order_details.*','abserve_restaurants.*','tb_users.id as partner_id','abserve_orders_customer.*')
												->leftjoin('abserve_restaurants','abserve_restaurants.id','=','abserve_order_details.res_id')
												->leftjoin('abserve_orders_customer','abserve_order_details.cust_id','=','abserve_orders_customer.cust_id')
												->leftjoin('tb_users', function ($join) {
													$join->on('tb_users.id', '=', 'abserve_restaurants.partner_id');
												})
												->where('abserve_order_details.id',$_REQUEST['order_id'])
												->first();
								\DB::table('abserve_orders_boy')->insertGetId(['boy_id'=>$_REQUEST['boy_id'],'orderid'=>$_REQUEST['order_id'],'partner_id'=>$order_datas->partner_id,'order_value'=>$order_datas->order_value,'order_details'=>$order_datas->order_details,'order_status'=>1,'current_order'=>1]);

								\DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
								\DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>1]);

								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
								$message 		= "Your order was accepted by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification2($app_api,$mobile_token,$message,$app_name);
								//$this->pushnotificationios($mobile_token,$message,$app_name);

								// Customer notification
								$appapi_details	= $this->appapimethod(1);
								$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
								$message 		= $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification($app_api,$mobile_token,$message,$app_name);
								//$this->pushnotificationios($mobile_token,$message,$app_name);
							}else{
								$status 	= "declined";
								$partner_id = \DB::table('abserve_orders_partner')->select('partner_id')->where('orderid',$_REQUEST['order_id'])->first();
								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($partner_id,'tb_users');
								$message 		= "Your order was declined by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification($app_api,$mobile_token,$message,$app_name);
								//$this->pushnotificationios($mobile_token,$message,$app_name);
							}
							if($update){
								$response['message'] = "Order ".$status." Successfully";
							} else {
								$response['message'] = "Order doesn't ".$status;
							}
						} else {
							$response['message'] = "Order has already been either accepted or rejected by you";
						}
					} else {
						$response['message'] = "It's Not your Order";		
					}
				}else{
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['message'] = "UserID Doesn't exists";	
			}
		}else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	//order_status = 2(Partner),1(Boy)
	/*public function postBorderacceptdecline( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required',
			'status'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->exists();
					if($acess){
						$order_status = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->where('status',0)->exists();
						if($order_status){
							$update = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->update(['status'=>$_REQUEST['status']]);
							if($_REQUEST['status'] == 1){
								$status		 = "accepted";
								//Get Order data
								$order_datas = $this->Order_data($_REQUEST['order_id'],'');

								\DB::table('abserve_orders_boy')->insertGetId(['boy_id'=>$_REQUEST['boy_id'],'orderid'=>$_REQUEST['order_id'],'partner_id'=>$order_datas->partner_id,'order_value'=>$order_datas->order_value,'order_details'=>$order_datas->order_details,'order_status'=>1,'current_order'=>1]);

								\DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
								\DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>1]);

								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
								$message 		= "Your order was accepted by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification2($app_api,$mobile_token,$message,$app_name);
								$this->pushnotificationios($mobile_token,$message,$app_name);
							}else{
								$status 	= "declined";
								$partner_id = \DB::table('abserve_orders_partner')->select('partner_id')->where('orderid',$_REQUEST['order_id'])->first();
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>0]);
								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($partner_id,'tb_users');
								$message 		= "Your order was declined by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification2($app_api,$mobile_token,$message,$app_name);
								$this->pushnotificationios($mobile_token,$message,$app_name);
							}
							if($update){
								$response['message'] = "Order ".$status." Successfully";
							} else {
								$response['message'] = "Order doesn't ".$status;
							}
						} else {
							$response['message'] = "Order has already been either accepted or rejected by you";
						}
					} else {
						$response['message'] = "It's Not your Order";		
					}
				}else{
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['message'] = "UserID Doesn't exists";	
			}
		}else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}*/


	public function getBorderacceptdecline( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required',
			'status'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
		  $boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->exists();
					if($acess){
						$order_status = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->where('status',0)->exists();
						if($order_status){
							$update = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->update(['status'=>$_REQUEST['status']]);
							if($_REQUEST['status'] == 1){
								$status		 = "accepted";
								//Get Order data
								$order_datas = $this->Order_data($_REQUEST['order_id'],'');


								\DB::table('abserve_orders_boy')->insertGetId(['boy_id'=>$_REQUEST['boy_id'],'orderid'=>$_REQUEST['order_id'],'partner_id'=>$order_datas->partner_id,'order_value'=>$order_datas->order_value,'order_details'=>$order_datas->order_details,'order_status'=>1,'current_order'=>1]);

								\DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
								\DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>1]);

								// Customer notification
								$appapi_details1	= $this->appapimethod(1);
								$mobile_token1 	= $this->userapimethod($order_datas->cust_id,'tb_users');
								$message1 		= $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
								$app_name1		= $appapi_details1->app_name;
								$app_api1 		= $appapi_details1->api;
								$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token1,$message1,'1');
					    		}else{
					    			$this->pushnotification($app_api1,$mobile_token1,$message1,$app_name1);	
								}
								

								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
								$message 		= "Your order was accepted by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'2');
						    	}else{
						    		$this->pushnotification2($app_api,$mobile_token,$message,$app_name);	
						    	}
								

                            }else{
								$status 	= "declined";
								$partner_id = \DB::table('abserve_orders_partner')->select('partner_id')->where('orderid',$_REQUEST['order_id'])->first();
								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($partner_id,'tb_users');
								$message 		= "Your order was declined by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'2');
						    	}else{
						    		$this->pushnotification2($app_api,$mobile_token,$message,$app_name);	
						    	}
							}
							if($update){
								$response['message'] = "Order ".$status." Successfully";
							} else {
								$response['message'] = "Order doesn't ".$status;
							}
						} else {
							$response['message'] = "Order has already been either accepted or rejected by you";
						}
					} else {
						$response['message'] = "It's Not your Order";		
					}
				}else{
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['message'] = "UserID Doesn't exists";	
			}
		}else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}


	//order_status = 2(Partner),1(Boy)
	public function postBorderacceptdecline_old( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required',
			'status'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->exists();
					if($acess){
						$order_status = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->where('status',0)->exists();
						if($order_status){
							$update = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->update(['status'=>$_REQUEST['status']]);
							if($_REQUEST['status'] == 1){
								$status		 = "accepted";
								//Get Order data
								$order_datas = $this->Order_data($_REQUEST['order_id'],'');

								\DB::table('abserve_orders_boy')->insertGetId(['boy_id'=>$_REQUEST['boy_id'],'orderid'=>$_REQUEST['order_id'],'partner_id'=>$order_datas->partner_id,'order_value'=>$order_datas->order_value,'order_details'=>$order_datas->order_details,'order_status'=>1,'current_order'=>1]);

								\DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
								\DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>1]);

								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
								$message 		= "Your order was accepted by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;

								$note_id = \DB::table('abserve_deliveryboys')->select('device')->where('id',$boy_assign_id['boy_id'])->get();
			
								
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'2');
							    }else{
							    	$this->pushnotification2($app_api,$mobile_token,$message,$app_name);
							    }
								
							}else{
								$status 	= "declined";
								$partner_id = \DB::table('abserve_orders_partner')->select('partner_id')->where('orderid',$_REQUEST['order_id'])->first();
								//echo "<pre>";print_r($partner_id);exit();
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>0]);
								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($partner_id->partner_id,'tb_users');
								$message 		= "Your order was declined by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();
			
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'2');
							    }else{
							    	$this->pushnotification2($app_api,$mobile_token,$message,$app_name);	
							    }
							}
							if($update){
								$response['message'] = "Order ".$status." Successfully";
							} else {
								$response['message'] = "Order doesn't ".$status;
							}
						} else {
							$response['message'] = "Order has already been either accepted or rejected by you";
						}
					} else {
						$response['message'] = "It's Not your Order";		
					}
				}else{
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['message'] = "UserID Doesn't exists";	
			}
		}else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}


	//order_status = 2(Partner),1(Boy)
	public function postBorderacceptdecline( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required',
			'status'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
		  $boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->exists();
					if($acess){
						$order_status = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->where('status',0)->exists();
						if($order_status){
							$update = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->where('bid','=',$_REQUEST['boy_id'])->update(['status'=>$_REQUEST['status']]);
							if($_REQUEST['status'] == 1){
								$status		 = "accepted";

								
								//Get Order data
								$order_datas = $this->Order_data($_REQUEST['order_id'],'');


								\DB::table('abserve_orders_boy')->insertGetId(['boy_id'=>$_REQUEST['boy_id'],'orderid'=>$_REQUEST['order_id'],'partner_id'=>$order_datas->partner_id,'order_value'=>$order_datas->order_value,'order_details'=>$order_datas->order_details,'order_status'=>1,'current_order'=>1]);

								\DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
								\DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
								\DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>1]);

								   // Customer notification
									$appapi_details	= $this->appapimethod(1);
									$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
									$message 		= $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
									$app_name		= $appapi_details->app_name;
									$app_api 		= $appapi_details->api;
									
									$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
									if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'1');
							    	}else{
							    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
							    	}

								//$this->pushnotificationios($mobile_token1,$message1,$app_name1);

								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
								$message 		= "Your order was accepted by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification($app_api,$mobile_token,$message,$app_name);
								//$this->pushnotificationios($mobile_token,$message,$app_name);

                            }else{
								$status 	= "declined";
								$partner_id = \DB::table('abserve_orders_partner')->select('partner_id')->where('orderid',$_REQUEST['order_id'])->first();
								// Partner notification
								$appapi_details	= $this->appapimethod(2);
								$mobile_token 	= $this->userapimethod($partner_id,'tb_users');
								$message 		= "Your order was declined by the delivery executive";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$this->pushnotification($app_api,$mobile_token,$message,$app_name);
								//$this->pushnotificationios($mobile_token,$message,$app_name);
							}
							if($update){
								$response['message'] = "Order ".$status." Successfully";
							} else {
								$response['message'] = "Order doesn't ".$status;
							}
						} else {
							$response['message'] = "Order has already been either accepted or rejected by you";
						}
					} else {
						$response['message'] = "It's Not your Order";		
					}
				}else{
					$response['message'] = "No Such Order found";
				}
			}else{
				$response['message'] = "UserID Doesn't exists";	
			}
		}else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
		}
		echo json_encode($response); exit;
	}

	//order_status = 2(Partner),1(Boy)
	public function postBorderaccept( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->exists();
					if($acess){
						$bup = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1,'current_order'=>1]);
						$pup = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
						\DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->update(['boy_status'=>1]);
						if($bup && $pup){
							$cust_id = \DB::table('abserve_order_details')->select('cust_id')->where('id','=',$_REQUEST['order_id'])->get();
							$appapi_details	= $this->appapimethod(1);
							$mobile_token 	= $this->userapimethod($cust_id[0]->cust_id,'tb_users');
							$message 		= "Delivery executive is waiting to pick up your order";
							$app_name		= $appapi_details->app_name;
							$app_api 		= $appapi_details->api;
							$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
								
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'1');
							    }else{
							    $this->pushnotification($app_api,$mobile_token,$message,$app_name);	
							}

							
							$ab_cu = \DB::table('abserve_orders_partner')->select('partner_id')->where('orderid','=',$_REQUEST['order_id'])->get();
							$appapi_details	= $this->appapimethod(2);
							$mobile_token 	= $this->userapimethod($ab_cu[0]->partner_id,'tb_users');
							$message 		= "Delivery boy accept your order";
							$app_name		= $appapi_details->app_name;
							$app_api 		= $appapi_details->api;
							$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();
							if($note_id[0]->device == 'ios'){
								$this->iospushnotification($mobile_token,$message,'2');
						    }else{
						    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
						    }
							$response['message'] = "Order Accepted";
							echo json_encode($response);exit;
						}else{
							$response['message'] = "Order Doesn't Accepted";
							echo json_encode($response);exit;
						}
					}else{
						$response['message'] = "It's Not your Order";
						echo json_encode($response);exit;		
					}
				}else{
					$response['message'] = "No Such Order found";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoycurrentorders( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$current_order_exists = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('current_order','=',1)->exists();
				if($current_order_exists){
					$current_order = \DB::select("SELECT * FROM `abserve_orders_boy` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `current_order` = '1'");
					if(!empty($current_order)){

						$query = "SELECT `oi`.*,`bo`.*,`od`.*,`ar`.*,`ac`.`phone_number` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` JOIN `abserve_order_items` AS `oi` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_restaurants` AS `ar` ON `ar`.`id` = `od`.`res_id` JOIN `tb_users` AS `ac` ON `ac`.`id` = `od`.`cust_id` WHERE `bo`.`current_order` = '1' AND `bo`.`boy_id` = ".$_REQUEST['boy_id']."";
						$orders1 = \DB::select($query);

						$orders1 = array_reduce($orders1, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						} else {
						$carry[$item->orderid]->food_id .= ',' . $item->food_id;
						$carry[$item->orderid]->food_item .= ',' . $item->food_item;
						$carry[$item->orderid]->quantity .= ',' . $item->quantity;
						$carry[$item->orderid]->price .= ',' . $item->price;
						}
						return $carry;
						}, array());

						foreach ($orders1 as $key => $value) {
							$orders[] = get_object_vars($value);
						}

						$allowed = array('orderid','food_id','food_item','quantity','price','distance','delivery_charges','order_status','grand_total','time','address','building','landmark','name','location','phone_number');
						$food_items = array_intersect_key($orders[0], array_flip($allowed));
						$orders = '';
						$orders[0] = $food_items;

						$response['message']		= "Current Order's Found";
						$response['current_order'] 	= $orders;
						/*echo "<pre>";
						print_r($response);exit;*/
						echo json_encode($response);exit;
					}else{
						$response['message'] = "No current Order's Found";
						echo json_encode($response);exit;
					}
				}else{
					$response['message'] = "No current Order's Found";
					echo json_encode($response);exit;			
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"]	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyordersdetailsold( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			'order_id'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$current_order_exists = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('current_order','=',1)->exists();
				if($current_order_exists){
					$current_order = \DB::select("SELECT * FROM `abserve_orders_boy` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `orderid` = '".$_REQUEST['order_id']."' AND `current_order` = '1'");
					if(!empty($current_order)){

						$query = "SELECT `oi`.*,`bo`.*,`od`.*,`ar`.*,`ac`.`phone_number` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` JOIN `abserve_order_items` AS `oi` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_restaurants` AS `ar` ON `ar`.`id` = `od`.`res_id` JOIN `tb_users` AS `ac` ON `ac`.`id` = `od`.`cust_id` WHERE `bo`.`current_order` = '1' AND `bo`.`orderid` = '".$_REQUEST['order_id']."' AND `bo`.`boy_id` = ".$_REQUEST['boy_id']." ORDER BY `oi`.`id` DESC ";
						$orders1 = \DB::select($query);
						$orders1 = array_reduce($orders1, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						} else {
							$carry[$item->orderid]->food_id .= ',' . $item->food_id;
							$carry[$item->orderid]->food_item .= ',' . $item->food_item;
							$carry[$item->orderid]->quantity .= ',' . $item->quantity;
							$carry[$item->orderid]->price .= ',' . $item->price;
						}
						return $carry;
						}, array());

						foreach ($orders1 as $key => $value) {
							$orders[] = get_object_vars($value);
						}

						$allowed = array('orderid','food_id','food_item','quantity','price','distance','delivery_charges','order_status','grand_total','time','address','building','landmark','name','lat','lang','location','phone_number');
						$food_items = array_intersect_key($orders[0], array_flip($allowed));
						$orders = '';
						$orders[0] = $food_items;

						$response['id']				= "1";
						$response['message']		= "Order's Detail Found";
						$response['current_order'] 	= $orders;
						// echo "<pre>";
						// print_r($response);exit;
						echo json_encode($response);exit;
					}else{
						$response['id'] 		= "2";
						$response['message'] 	= "No Order's Detail Found";
						echo json_encode($response);exit;
					}
				}else{
					$response['id'] 		= "2";
					$response['message'] 	= "No Order's Detail Found";
					echo json_encode($response);exit;			
				}
			}else{
				$response['id'] 	 = "3";
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"]			= "5";
			$response["message"]	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyordersdetails( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			'order_id'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$current_order_exists = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('current_order','=',1)->exists();
				if($current_order_exists){
					$current_order = \DB::select("SELECT * FROM `abserve_orders_boy` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `orderid` = '".$_REQUEST['order_id']."' AND `current_order` = '1'");
					if(!empty($current_order)){

						$query = "SELECT `oi`.*,`bo`.*,`od`.*,`ar`.*,`ac`.`phone_number` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` JOIN `abserve_order_items` AS `oi` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_restaurants` AS `ar` ON `ar`.`id` = `od`.`res_id` JOIN `tb_users` AS `ac` ON `ac`.`id` = `od`.`cust_id` WHERE `bo`.`current_order` = '1' AND `bo`.`orderid` = '".$_REQUEST['order_id']."' AND `bo`.`boy_id` = ".$_REQUEST['boy_id']." ORDER BY `oi`.`id` DESC ";
						$orders1 = \DB::select($query);
						$orders1 = array_reduce($orders1, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						} else {
							$carry[$item->orderid]->food_id .= ',' . $item->food_id;
							$carry[$item->orderid]->food_item .= ',' . $item->food_item;
							$carry[$item->orderid]->quantity .= ',' . $item->quantity;
							$carry[$item->orderid]->price .= ',' . $item->price;
						}
						return $carry;
						}, array());

						foreach ($orders1 as $key => $value) {
							$orders[] = get_object_vars($value);
						}

						$allowed = array('orderid','food_id','food_item','quantity','price','distance','delivery_charges','order_status','grand_total','time','address','building','landmark','lat','lang','name','location','phone_number');
						$food_items = array_intersect_key($orders[0], array_flip($allowed));
						$orders = '';
						$orders[0] = $food_items;

						$response['id']				= "1";
						$response['message']		= "Order's Detail Found";
						$response['current_order'] 	= $orders;
						/*echo "<pre>";
						print_r($response);exit;*/
						echo json_encode($response);exit;
					}else{
						$response['id'] 		= "2";
						$response['message'] 	= "No Order's Detail Found";
						echo json_encode($response);exit;
					}
				}else{
					$response['id'] 		= "2";
					$response['message'] 	= "No Order's Detail Found";
					echo json_encode($response);exit;			
				}
			}else{
				$response['id'] 	 = "3";
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"]			= "5";
			$response["message"]	= $error;
			echo json_encode($response); exit;
		}
	}


	public function getBoyordersdetails( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			'order_id'		=>'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$current_order_exists = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('current_order','=',1)->exists();
				if($current_order_exists){
					$current_order = \DB::select("SELECT * FROM `abserve_orders_boy` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `orderid` = '".$_REQUEST['order_id']."' AND `current_order` = '1'");
					if(!empty($current_order)){

						$query = "SELECT `oi`.*,`bo`.*,`od`.*,`ar`.*,`ac`.`phone_number` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` JOIN `abserve_order_items` AS `oi` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_restaurants` AS `ar` ON `ar`.`id` = `od`.`res_id` JOIN `tb_users` AS `ac` ON `ac`.`id` = `od`.`cust_id` WHERE `bo`.`current_order` = '1' AND `bo`.`orderid` = '".$_REQUEST['order_id']."' AND `bo`.`boy_id` = ".$_REQUEST['boy_id']."";
						$orders1 = \DB::select($query);
						$orders1 = array_reduce($orders1, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						} else {
							$carry[$item->orderid]->food_id .= ',' . $item->food_id;
							$carry[$item->orderid]->food_item .= ',' . $item->food_item;
							$carry[$item->orderid]->quantity .= ',' . $item->quantity;
							$carry[$item->orderid]->price .= ',' . $item->price;
						}
						return $carry;
						}, array());

						foreach ($orders1 as $key => $value) {
							$orders[] = get_object_vars($value);
						}

						$allowed = array('orderid','food_id','food_item','quantity','price','distance','delivery_charges','order_status','grand_total','time','address','building','landmark','name','location','phone_number');
						$food_items = array_intersect_key($orders[0], array_flip($allowed));
						$orders = '';
						$orders[0] = $food_items;

						$response['id']				= "1";
						$response['message']		= "Order's Detail Found";
						$response['current_order'] 	= $orders;
						// echo "<pre>";
						// print_r($response);exit;
						echo json_encode($response);exit;
					}else{
						$response['id'] 		= "2";
						$response['message'] 	= "No Order's Detail Found";
						echo json_encode($response);exit;
					}
				}else{
					$response['id'] 		= "2";
					$response['message'] 	= "No Order's Detail Found";
					echo json_encode($response);exit;			
				}
			}else{
				$response['id'] 	 = "3";
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"]			= "5";
			$response["message"]	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyconfirmedorders( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' ORDER BY `oi`.`id` DESC";
				$confirmed_orders_count = \DB::select($query);
				if(!empty($confirmed_orders_count) && $confirmed_orders_count != ''){
					foreach ($confirmed_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value); 
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`current_order`,`oi`.`orderid`,`od`.`time` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' GROUP BY `oi`.`orderid` ORDER BY `oi`.`id` DESC";
					$confirmed_orders = \DB::select($query1);

					foreach ($confirmed_orders as $key => $value) {
						$value->time = date('H:m:s A',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] 			= "Success";
					$response['confirmed_orders'] 	= $confirmed_orders;
					/*echo "<pre>";
					print_r($response);exit;*/
					echo json_encode($response);exit;
				}else{
					$response['message'] 		= "Confirmed Orders Doesn't exists";
					$response['confirmed_orders'] = '';
					echo json_encode($response);exit;		
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postNewboyconfirmedorders( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' ORDER BY `oi`.`id` DESC";
				$confirmed_orders_count = \DB::select($query);
				if(!empty($confirmed_orders_count) && $confirmed_orders_count != ''){
					foreach ($confirmed_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value); 
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`current_order`,`oi`.`orderid`,`od`.`time` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' GROUP BY `oi`.`orderid` ORDER BY `oi`.`id` DESC";
					$confirmed_orders = \DB::select($query1);

					foreach ($confirmed_orders as $key => $value) {
						$value->time = date('H:m:s A',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] 			= "Success";
					$response['confirmed_orders'] 	= $confirmed_orders;
					
					echo json_encode($response);exit;
				}else{
					$response['message'] 		= "Confirmed Orders Doesn't exists";
					$response['confirmed_orders'] = [];
					echo json_encode($response);exit;		
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function getBoyconfirmedorders( Request $request){

		$response = array();
	
		$rules = array(
			'boy_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0'";
				$confirmed_orders_count = \DB::select($query);
				if(!empty($confirmed_orders_count) && $confirmed_orders_count != ''){
					foreach ($confirmed_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value); 
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`current_order`,`oi`.`orderid`,`od`.`time` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' GROUP BY `oi`.`orderid` ORDER BY `oi`.`orderid` DESC";
					$confirmed_orders = \DB::select($query1);

					foreach ($confirmed_orders as $key => $value) {
						$value->time = date('H:m:s A',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] 			= "Success";
					$response['confirmed_orders'] 	= $confirmed_orders;
					/*echo "<pre>";
					print_r($response);exit;*/
					echo json_encode($response);exit;
				}else{
					$response['message'] 		= "Confirmed Orders Doesn't exists";
					$response['confirmed_orders'] = '';
					echo json_encode($response);exit;		
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function Order_data($order_id,$boy_datas=null){
		//Order data
		$order_datas = \DB::table('abserve_order_details')
						->select('abserve_order_details.*','abserve_restaurants.*','tb_users.id as partner_id','abserve_orders_customer.*');
		if($boy_datas != ''){
			$order_datas->addSelect('abserve_orders_boy.*','abserve_orders_boy.order_status as boy_status');
			$order_datas->leftjoin('abserve_orders_boy','abserve_order_details.id','=','abserve_orders_boy.orderid');
		}
		$order_datas->leftjoin('abserve_restaurants','abserve_restaurants.id','=','abserve_order_details.res_id');
		$order_datas->leftjoin('abserve_orders_customer','abserve_order_details.cust_id','=','abserve_orders_customer.cust_id');
		$order_datas->leftjoin('tb_users', function ($join) {
				$join->on('tb_users.id', '=', 'abserve_restaurants.partner_id');
			});
		$order_datas->where('abserve_order_details.id',$order_id);
		return $order_datas->first();
	}

	public function tabledata($table='',$get='',$where='',$cond='',$value=''){
		$get_val = \DB::table($table)->select($get)->where($where,$cond,$value)->first();
	}

	public function postArriverestaurant( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$boy_order = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('orderid','=',$_REQUEST['order_id'])->exists();
					if($boy_order){
						$boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>2]);
						$cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);

						//Get Order data
						$order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');
						$boy_info		= $this->tabledata('abserve_deliveryboys','*','id','=',$order_datas->boy_id);
						
						// Customer notification
						$appapi_details	= $this->appapimethod(1);
						$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
						$message 		= "Our delivery executive ".$boy_info->username." has arrived at ".$order_datas->name." and is waiting to pick up your order";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
					
						if($note_id[0]->device == 'ios'){
							$this->iospushnotification($mobile_token,$message,'1');
					    }else{
					    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
						}

						if($boy_up && $cus_up){
							$response["id"] 		= "1";
							$response["message"] 	= "Order Status updated";
							echo json_encode($response); exit;
						}else{
							$response["id"] 		= "2";
							$response["message"] 	= "Order Status Doesn't updated";
							echo json_encode($response); exit;
						}
					}else{
						$response["id"] 		= "3";
						$response["message"] 	= "Order Doesn't exists";
						echo json_encode($response); exit;						
					}
				}else{
					$response["id"] 		= "3";
					$response["message"] 	= "Order Doesn't exists";
					echo json_encode($response); exit;
				}
			}else{
				$response["id"] 		= "3";
				$response["message"] 	= "UserID Doesn't exists";
				echo json_encode($response); exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postOrderdispatch( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$partner_order = \DB::table('abserve_orders_partner')->where('partner_id','=',$_REQUEST['partner_id'])->where('orderid','=',$_REQUEST['order_id'])->exists();
					if($partner_order){
						$boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);
						if($boy_up){
							$cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);
							$par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>4]);

							//Get Order data
							$order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');
							$boy_info		= $this->tabledata('abserve_deliveryboys','*','id','=',$order_datas->boy_id);
							
							// Customer notification
							$appapi_details	= $this->appapimethod(1);
							$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
							$message 		= "Our delivery executive ".$boy_info->username."pick your order from ".$order_datas->name." and within few minutes the order will delivered to you ";
							$app_name		= $appapi_details->app_name;
							$app_api 		= $appapi_details->api;
							$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
							if($note_id[0]->device == 'ios'){
								$this->iospushnotification($mobile_token,$message,'1');
						    }else{
						    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
							}
						}

						if($boy_up && $cus_up){
							$response["message"] 	= "Order Status updated";
							echo json_encode($response); exit;
						}else{
							$response["message"] 	= "Order Status Doesn't updated";
							echo json_encode($response); exit;
						}
					}else{
						$response["message"] 	= "Order Doesn't exists";
						echo json_encode($response); exit;						
					}
				}else{
					$response["message"] 	= "Order Doesn't exists";
					echo json_encode($response); exit;
				}
			}else{
				$response["message"] 	= "UserID Doesn't exists";
				echo json_encode($response); exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postOrderdispatch_old_boy( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$boy_order = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('orderid','=',$_REQUEST['order_id'])->exists();
					if($boy_order){
						$boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>3]);
						$cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);
						$par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);

						//Get Order data
						$order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');
						$boy_info		= $this->tabledata('abserve_deliveryboys','*','id','=',$order_datas->boy_id);
						
						// Customer notification
						$appapi_details	= $this->appapimethod(1);
						$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
						$message 		= "Our delivery executive ".$boy_info->username."pick your order from ".$order_datas->name." and within few minutes the order will delivered to you ";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						$this->pushnotification($app_api,$mobile_token,$message,$app_name);
						//$this->pushnotificationios($mobile_token,$message,$app_name);
						if($boy_up && $cus_up){
							$response["message"] 	= "Order Status updated";
							echo json_encode($response); exit;
						}else{
							$response["message"] 	= "Order Status Doesn't updated";
							echo json_encode($response); exit;
						}
					}else{
						$response["message"] 	= "Order Doesn't exists";
						echo json_encode($response); exit;						
					}
				}else{
					$response["message"] 	= "Order Doesn't exists";
					echo json_encode($response); exit;
				}
			}else{
				$response["message"] 	= "UserID Doesn't exists";
				echo json_encode($response); exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postOrderfinished( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$boy_order = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('orderid','=',$_REQUEST['order_id'])->exists();
					if($boy_order){
						$boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>4,'order_done_status'=>$_REQUEST['done_status']]);
						$cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>4]);
						// $par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>4]);

							\DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->update(['boy_status'=>0]);

							if($boy_up && $cus_up){

								//Get Order data
								$order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');
								
								// Customer notification
								$appapi_details	= $this->appapimethod(1);
								$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
								$message 		= "Order delivered Successfully.Thank you for being an amazing customer";
								$app_name		= $appapi_details->app_name;
								$app_api 		= $appapi_details->api;
								$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
					
								if($note_id[0]->device == 'ios'){
									$this->iospushnotification($mobile_token,$message,'1');
							    }else{
							    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
								}

								$response["message"] 	= "Order Status updated";
								echo json_encode($response); exit;
							}else{
								$response["message"] 	= "Order Status Doesn't updated";
								echo json_encode($response); exit;
							}
					}else{
						$response["message"] 	= "Order Doesn't exists";
						echo json_encode($response); exit;						
					}
				}else{
					$response["message"] 	= "Order Doesn't exists";
					echo json_encode($response); exit;
				}
			}else{
				$response["message"] 	= "UserID Doesn't exists";
				echo json_encode($response); exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function getActivation( Request $request){
		$num = $request->input('code');
		if($num =='')
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('error','Invalid Code Activation!'));
		
		$user =  User::where('activation','=',$num)->get();
		if (count($user) >=1)
		{
			\DB::table('tb_users')->where('activation', $num )->update(array('active' => 1,'activation'=>''));
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Your account is active now!'));
			
		} else {
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('error','Invalid Code Activation!'));
		}
	}
	
	public function postChangepassword(Request $request){
		$rules = array(
			'new_password'=>'required|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		$userid = \Auth::id();
		if (\Auth::attempt(array('id' => $userid, 'password' => $_POST['old_password'])))
        {
			try 
			{
				$user = \Auth::user();
				$user->password = bcrypt($_POST['new_password']);
				$user->save();
				echo "success";
				/*return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Password has been saved!'));*/
			} 
				catch (Exception $e) 
			{
				echo '';
			}
        }
        else
        {
        	echo "Invalid";
        	/*return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();*/
        }
	}

	public function postUserupdate(Request $request){

		if(!\Auth::check()) return Redirect::to('user/login');
		$userid = Auth::user()->id;
			
			if(!is_null(Input::file('avatar')))
			{
				$file = $request->file('avatar'); 
				$destinationPath = './uploads/users/';
				$filename = $file->getClientOriginalName();

				$extension = $file->getClientOriginalExtension(); //if you need extension of the file
				 $newfilename = \Session::get('uid').'.'.$extension;
				$uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
				    $data['avatar'] = $newfilename; 
				} 
				
			}		
			
			$user = User::find(\Session::get('uid'));
			$user->first_name 	= $request->input('first_name');
			$user->last_name 	= $request->input('last_name');
			$user->email 		= $request->input('email');
			if(isset( $data['avatar']))  $user->avatar  = $newfilename; 			
			$user->save();

		$first_name 	= $_POST['first_name'];
		$last_name 		= $_POST['last_name'];
		$email 			= $_POST['email'];
		$phno 			= $_POST['phno'];
		$address 		= $_POST['address'];
		$city 			= $_POST['city'];
		$state 			= $_POST['state'];
		$pin 			= $_POST['pin'];
		$country 		= $_POST['country'];
		$username 		= $_POST['username'];

		User::where('id', '=',$userid)
		->update(array('first_name' => $first_name,"last_name"=>$last_name,"username"=>$username,"email"=>$email,"phone_number"=>$phno,"address"=>$address,"address"=>$address,"city"=>$city,"state"=>$state,"zip_code"=>$pin,"country"=>$country));

		return Redirect::to('user/profile')->with('messagetext','Profile has been saved!')->with('msgstatus','success');
	}
	
	public function postSaveprofile( Request $request){

		if(!\Auth::check()) return Redirect::to('user/login');
		$rules = array(
			'first_name'=>'required|alpha_num|min:2',
			'last_name'=>'required|alpha_num|min:2',
			);	
			
		if($request->input('email') != \Session::get('eid'))
		{
			$rules['email'] = 'required|email|unique:tb_users';
		}	
				
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			
			if(!is_null(Input::file('avatar')))
			{
				$file = $request->file('avatar'); 
				$destinationPath = './uploads/users/';
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension(); //if you need extension of the file
				 $newfilename = \Session::get('uid').'.'.$extension;
				$uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
				    $data['avatar'] = $newfilename; 
				} 
				
			}		
			
			$user = User::find(\Session::get('uid'));
			$user->first_name 	= $request->input('first_name');
			$user->last_name 	= $request->input('last_name');
			$user->email 		= $request->input('email');
			if(isset( $data['avatar']))  $user->avatar  = $newfilename; 			
			$user->save();

			return Redirect::to('user/profile')->with('messagetext','Profile has been saved!')->with('msgstatus','success');
		} else {
			return Redirect::to('user/profile')->with('messagetext','The following errors occurred')->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	}
	
	public function postSavepassword( Request $request){

		$rules = array(
			'password'=>'required|between:6,12',
			'password_confirmation'=>'required|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$user = User::find(\Session::get('uid'));
			
			$user->password = \Hash::make($request->input('password'));
			$user->save();

			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Password has been saved!'));
		} else {
			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	
	}	
	
	public function postPartnershow( Request $request){

		$Par_res ="SELECT `rd`.* FROM `abserve_restaurants` as `rs` INNER JOIN `abserve_order_details` as `rd` on `rs`.id=`rd`.res_id  WHERE `partner_id`=".$_REQUEST['partner_id'];

		$rsis=\DB::select($Par_res);

		$par_det=array();
		foreach ($rsis as $key => $value)
		{
			$par_det[]=(get_object_vars($value));
		}

		$foot_details="SELECT `ar`.*,`ap`.* FROM `abserve_order_items` as `ar` INNER JOIN `abserve_orders_partner` as `ap` on `ar`.orderid=`ap`.orderid  WHERE `partner_id`=".$_REQUEST['partner_id'];

		$arr_for=\DB::select($foot_details);

		$foo_or_de=array();
		foreach ($arr_for as $key => $value)
		{
			$foo_or_de[]=(get_object_vars($value));
		}

		$var=\DB::select("SELECT `ap`.`orderid` FROM `abserve_order_items` as `aw` INNER JOIN `abserve_orders_partner` as `ap` on `aw`.`orderid`=`ap`.`orderid` WHERE  `partner_id`=".$_REQUEST['partner_id']);

		$va=\DB::select("SELECT SUM(`quantity`) as `total_items`,`ai`.`orderid`  FROM `abserve_order_items` as `ae` JOIN `abserve_orders_partner` as `ai` on `ai`.`orderid`=`ae`.`orderid` WHERE `partner_id`=".$_REQUEST['partner_id']);

		$response['message']			= (array)"Success";
		$response['quantity']			= $va;
		$response['customer_details']	= $par_det;
		$response['Food_details']		= $foo_or_de;

		// echo "<pre>";
		// print_r($response);exit;

		echo json_encode($response);exit;
	}

	public function getReminder(){
	
		return view('user.remind');
	}	

	public function postRequest( Request $request){

		$rules = array(
			'credit_email'=>'required|email'
		);	
		
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	
	
			$user =  User::where('email','=',$request->input('credit_email'));
			if($user->count() >=1)
			{
				$user = $user->get();
				$user = $user[0];
				$data = array('token'=>$request->input('_token'));	
				$to 		= $request->input('credit_email');
				$subject 	= "[ " .CNF_APPNAME." ] REQUEST PASSWORD RESET "; 			
				$message 	= view('user.emails.auth.reminder', $data);
				$headers  	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers 	.= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);				
			
				
				$affectedRows = User::where('email', '=',$user->email)
								->update(array('reminder' => $request->input('_token')));
								
				return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success','Please check your email'));	
				
			} else {
				return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Cant find email address'));
			}

		}  else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	 
	}	
	
	public function getReset( $token = ''){

		if(\Auth::check()) return Redirect::to('dashboard');

		$user = User::where('reminder','=',$token);
		if($user->count() >=1)
		{
			$data = array('verCode'=>$token);
			return view('user.remind',$data);	
		} else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Cant find your reset code'));
		}
	}	
	
	public function postDoreset( Request $request , $token = ''){

		$rules = array(
			'password'=>'required|alpha_num|between:6,12|confirmed',
			'password_confirmation'=>'required|alpha_num|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			
			$user =  User::where('reminder','=',$token);
			if($user->count() >=1)
			{
				$data = $user->get();
				$user = User::find($data[0]->id);
				$user->reminder = '';
				$user->password = \Hash::make($request->input('password'));
				$user->save();			
			}

			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Password has been saved!'));
		} else {
			return Redirect::to('user/reset/'.$token)->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	
	}

	public function postBoylocationupdate( Request $request){
		$rules = array(
			'lat'=>'required',
			'lang'=>'required',
			'user_id'=>'numeric|required'
		);	
		
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	
			$up = \DB::table('abserve_deliveryboys')->where('id',$_REQUEST['user_id'])->update(['latitude'=>$_REQUEST['lat'],'longitude'=>$_REQUEST['lang']]);
			if($up){
				$response["id"] 		= "1";
				$response['message'] = "Location updated";
			} else {
				$response["id"] 		= "2";
				$response['message'] = "Location Doesn't updated";
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			if(!empty($error)){
				if(isset($error['lat'])){
					$response['message'] = $error['user_id'][0];
				} else if(isset($error['lat'])){
					$response['message'] = $error['lat'][0];
				} else if(isset($error['lang'])){
					$response['message'] = $error['lang'][0];
				}
			}
		}
		echo json_encode($response); exit;
	}

	public function postSignout( Request $request) {
		$rules = array(
			'user_type'	=>'required',
			'user_id'	=>'required'
		);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$user_type = $request->user_type;
			$user_id = $request->user_id;
			if($user_type == '1') {
				$table = 'tb_users';
			} elseif ($user_type == '2') {
				$table = 'tb_users';
			} elseif ($user_type == '3') {
				$table = 'abserve_deliveryboys';
			}
			$update = \DB::table($table)->where('id',$user_id)->update(['mobile_token'=>'']);
			if($update){
				$response['id'] = '1';
				$response['message'] = 'success';
			} else {
				$response['id'] = '2';
				$response['message'] = 'failure';
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["id"] 		= "5";
			if(!empty($error)){
				if(isset($error['user_type'])){
					$response['message'] = $error['user_type'][0];
				} else if(isset($error['user_id'])){
					$response['message'] = $error['user_id'][0];
				}
			}
		}
		echo json_encode($response); exit;
	}


	public function postBoyonline(Request $request)
	{    $val['online_sts'] =$request->boy_online;
		$aBoy_status =\DB::table('abserve_deliveryboys')->where('id',$request->boy_id)->update($val);
		if($aBoy_status==1){
				$response['message'] = "Updated Successfully";
		}else{
			$response['message'] = "Updated Successfully";
		}
		echo json_encode($response); exit;
	}


	 public function postCountrycode(Request $request)
    {
    	$country=\DB::table("abserve_countries")->select('*')->get();
    	$response['countrycode'] = $country;
    	echo json_encode($response,JSON_NUMERIC_CHECK); exit;
    	
    }

    public function postCountrylistwithcode(Request $request)
    {
    	$country=\DB::table("abserve_countries")->select('*')->get();
    	foreach ($country as $key => $value) {
    		if($value->nicename!=''){
    			$value->cuntrycode = $value->nicename.'('.'+'.$value->phonecode.')';
    		}
    	}
    	$response['countrycode'] = $country;
    	echo json_encode($response,JSON_NUMERIC_CHECK); exit;
    	
    }

    public function postCustomeroredrcancel(Request $request)
	{
		$orderid =$request->order_id;
		$aOrder =\DB::table('abserve_order_details')->where('id',$orderid)->delete();
		$aAssign_order =\DB::table('abserve_order_assign')->where('order_id',$orderid)->delete();
		$aPart_order =\DB::table('abserve_orders_partner')->where('orderid',$orderid)->delete();
		$aCus_order =\DB::table('abserve_orders_customer')->where('orderid',$orderid)->delete();
		$aBoy_order =\DB::table('abserve_orders_boy')->where('orderid',$orderid)->delete();
		$aItem_order =\DB::table('abserve_order_items')->where('orderid',$orderid)->delete();
         //$aadmin_order =\DB::table('abserve_order_admin')->where('orderid',$orderid)->delete();
         $admin_boy_order =\DB::table('abserve_boyorderstatus')->where('oid',$orderid)->delete();
         
         
		 $response['message'] = "Your order was canceled Successfully";
		
		echo json_encode($response); exit;
		
	}

    public function postOrderdispatchboy( Request $request){

		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'boy_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
			if($boy_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$boy_order = \DB::table('abserve_orders_boy')->where('boy_id','=',$_REQUEST['boy_id'])->where('orderid','=',$_REQUEST['order_id'])->exists();
					if($boy_order){
						$boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>3]);
						$cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);
						$par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);

						//Get Order data
						$order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');
						$boy_info		= $this->tabledata('abserve_deliveryboys','*','id','=',$order_datas->boy_id);
						
						// Customer notification
						$appapi_details	= $this->appapimethod(1);
						$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
						$message 		= "Our delivery executive ".$boy_info->username."pick your order from ".$order_datas->name." and within few minutes the order will delivered to you ";
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;

						$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
					
						if($note_id[0]->device == 'ios'){
							$this->iospushnotification($mobile_token,$message,'1');
					    }else{
					    	$this->pushnotification($app_api,$mobile_token,$message,$app_name);	
						}

						if($boy_up && $cus_up){
							$response["message"] 	= "Order Status updated";
							echo json_encode($response); exit;
						}else{
							$response["message"] 	= "Order Status Doesn't updated";
							echo json_encode($response); exit;
						}
					}else{
						$response["message"] 	= "Order Doesn't exists";
						echo json_encode($response); exit;						
					}
				}else{
					$response["message"] 	= "Order Doesn't exists";
					echo json_encode($response); exit;
				}
			}else{
				$response["message"] 	= "UserID Doesn't exists";
				echo json_encode($response); exit;				
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	
	public function exists($table,$field,$value){
		return \DB::table($table)->where($field,$value)->exists();
	}


	public function iospushnotification($moble_token,$message,$appid,$patch=0){
		 //echo $appid; 
		$deviceToken = $moble_token;

		$passphrase = 'abservetech@123';
        if($appid == '1'){
        	//echo 'aih';
          $path=base_path().'/ios/Foodstar Customer.pem';

        }elseif($appid == '2'){	
        	//echo 'hai';
			$path=base_path().'/ios/Foodstar Partner.pem';

		}else{
			//echo 'iah';
			$path =base_path().'/ios/Foodstar Delivery.pem';
		}
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if($fp)	{	
			
			$body['aps'] = array(
				'alert' 	=> $message, 
				'sound' 	=> 'default',
				'badge' 	=> $patch,

				);

			$payload = json_encode($body);
			//echo $payload;
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
			$result = fwrite($fp, $msg, strlen($msg));

			fclose($fp);
		}else{
			
		}
		
	}


	
}
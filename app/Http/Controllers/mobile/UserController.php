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
use DatePeriod;
use DateInterval;

class UserController extends Controller {

    protected $layout = "layouts.main";

    public function __construct() {
        parent::__construct();
        $free_boys = [];
    }

    public function cuisines($id=''){

        $cname	= \DB::select("SELECT GROUP_CONCAT(name) as name FROM abserve_food_cuisines where id IN (".$id.")");
        if($cname){
            return $cname[0]->name;
        } else {
            return '';
        }
    }

    public function iospushnotification($app_api,$mobile_token,$message,$message1,$app_name){

        define( 'API_ACCESS_KEY3', $app_api );
        /*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

        $registrationIds = [$mobile_token];

        // prep the bundle
        $msg = array
        (
            'message' 	=> $message1,
            'title'		=> 'Delivery Star',
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon'
        );

        $notification = array
        (
            'body' 		=> $message,
            'title'		=> 'Delivery Star',

        );

        $fields = array
        (
            'registration_ids' 	=> $registrationIds,
            'notification'		=> $notification,
            'data'				=> $msg,
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY3,
            'Content-Type: application/json'
        );

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

    /*public function iospushnotification($moble_token,$message,$appid,$patch=0){
		 //echo $appid;
		$deviceToken = $moble_token;

		//$passphrase = 'abservetech@123';
		$passphrase = 'bics@123';
        if($appid == '1'){
        	//echo 'aih';
          $path=base_path().'/ios/delivery_star.pem';

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

	}*/

    public function pushnotification2($app_api,$mobile_token,$message,$app_name){
        define( 'API_ACCESS_KEY2', $app_api );
        /*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

        $registrationIds = [$mobile_token];

        // prep the bundle
        $msg = array
        (
            'body' 	=> $message,
            'message'		=> 'Message from '.$app_name,
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

    public function pushnotification1($app_api,$mobile_token,$message,$app_name){
        define( 'API_ACCESS_KEY1', $app_api );
        /*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

        $registrationIds = [$mobile_token];

        // prep the bundle
        $msg = array
        (
            'body' 	=> $message,
            'message'		=> 'Message from '.$app_name,
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
            'Authorization: key=' . API_ACCESS_KEY1,
            'Content-Type: application/json'
        );

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

    public function pushnotification($app_api,$mobile_token,$message,$app_name, $body=null){

        define( 'API_ACCESS_KEY', $app_api );
        /*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

        $registrationIds = [$mobile_token];


        // prep the bundle
        $msg = array
        (
            'body' 	=> $message,
            'message'		=> $body ? $body : 'Message from '.$app_name,
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
            'data'				=> $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );


        info(print_r($fields, true));
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

        if($res->success == 0){

            $res_token = \DB::table('user_mobile_tokens')->where('device_token', '=', $mobile_token)->first();
            if(!empty($res_token)){
                \DB::table('user_mobile_tokens')->where('id', $res_token->id)->delete();
            }
        }
        //Getting value from success
        $flag = $res->success;
        return $flag;

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


    public function pushNotificationRestaurantOrder($token)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        #$token = 'eYAC6qp5TXuuTIei0Qb0NJ:APA91bGGNQAekiakunDBM6xbIaoW9xvk_tu7MFzSY8XlS0lP-9lQPeNLOTBCKDDGZcsFxtwvrBtpYC6OgOBym0LSDyGjFejQBdn0SRPDo2AstyjOzZbm8F97yZ62j6uE-8BEMkx0Vw1g';
        $registration_ids = [$token];
        $notification = array
        (
            'body' 	=> "New orders found in your restaurant",
            'message'		=> 'New orders found in your restaurant',
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon'
        );
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $notification
        );
        // Firebase API Key
        $headers = array(
            'Authorization:key=AIzaSyA5TeZvxciBVfwyO9tzDwLUf2v5vxg4rXc',
            'Content-Type:application/json'
        );

        info(print_r($fields, true));
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch );
        curl_close( $ch );

        info($result);
        //Decoding json from result
        $res = json_decode($result);

        if ($res === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        if($res->success == 0){

            $res_token = \DB::table('user_mobile_tokens')->where('device_token', '=', $mobile_token)->first();
            if(!empty($res_token)){
                \DB::table('user_mobile_tokens')->where('id', $res_token->id)->delete();
            }
        }
        //Getting value from success
        $flag = $res->success;
        return $flag;

    }


    public function sendSms($mobilenumber, $otp, $user_id) {

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
            CURLOPT_POSTFIELDS => "{ \"sender\": \"DSRVLR\", \"route\": \"4\", \"DLT_TE_ID\": \"1207161725542969395\", \"country\": \"91\", \"sms\": [ { \"message\": \"<#> Hi! Welcome to DS. Your OTP is: $otp WddRXrZU1Qd\", \"to\": [ \"$mobilenumber\" ] } ] }",
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

    public function appapimethod( $value = ''){

        $appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();

        return $appapi[0];
    }

    public function userapimethod($userid = '',$table){

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

    public function getDistance($lat1, $lon1, $lat2, $lon2) {

        $theta = $lon1 - $lon2;

        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));

        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;


        return $kilometers;}

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

    public function postCreate1( Request $request){

        $rules = array(
            'group_id'       =>'required',
            //'username'       =>'required',
            'firstname'      =>'required|min:2',
            //'lastname'       =>'required|alpha_num|min:2',
            //'email'          =>'required|email|unique:tb_users',
            'email'          =>'required|email',
            'password'       =>'required',
            'phone_number'	 =>'required|numeric',
            //'phone_code'	 =>'required|numeric',
            //'phone_otp'      =>'required|numeric',
        );


        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $exists = \DB::table('tb_users')->where('phone_number','=',$request->phone_number)->where('group_id','=',$request->group_id)->exists();
            // print_r($exists);   exit;
            if($exists == ''){

                $existsemail = \DB::table('tb_users')->where('email','=',$request->email)->where('group_id','=',$request->group_id)->exists();
                if($existsemail == ''){

                    $code = rand(10000,10000000);
                    $otp = rand(100000, 999999);

                    if(($request->device == "ios") || ($request->device == "iOS")){
                        $device = "ios";
                    } else {
                        $device = $request->device;
                    }

                    $authen = new User;
                    $authen->username   = $request->firstname;
                    $authen->first_name = $request->firstname;
                    //$authen->last_name  = $request->lastname;
                    $authen->phone_number = $request->phone_number;
                    //$authen->phone_code  = $request->phone_code;
                    $authen->phone_otp   = $otp;//$request->phone_otp;
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
                    $authen->mobile_token = $request->mobile_token;
                    $authen->device = $device;
                    $authen->ios_flag = 1;
                    /*if($request->group_id == 4  || $request->group_id == 0){
				if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			} else if($request->group_id == 3){
				$authen->active = '0';
			}*/
                    $authen->active = '0';
                    $authen->save();

                    $data = array(
                        'username'  => $request->firstname ,
                        'firstname'	=> $request->firstname ,
                        //'lastname'	=> $request->lastname,
                        'email'		=> $request->email ,
                        'phonenumber'=> $request->phone_number ,
                        'password'	=> $request->password,
                        'code'		=> $code,
                        //'phone_code'=>$request->phone_code ,
                        'address'   =>$request->address,

                    );
                    if($request->address!=''){
                        $val['address']=$request->address;
                        $val['user_id']=$authen->id;
                        $val['lat']=$request->lat;
                        $val['address_type']='home';
                        $val['lang']=$request->lang;
                        $val['building']='';
                        $val['landmark']='';

                        $ins=\DB::table('abserve_user_address')->insert($val);

                    }

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
                            $this->sendSms($request->phone_number, $otp, $authen->id);

                            $response["status"] 	= true;
                            $response["id"] 		= "2";
                            $response["message"] 	  = "Please check your otp for active account.";
                            //$response['message'] 	= "Thanks for registering! . We will validate you account before your account active";
                            echo json_encode($response);exit;
                        } else {

                            $sms = $this->sendSms($request->phone_number, $otp, $authen->id);

                            $user = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$authen->id)->get();

                            foreach ($user as $key => $valu) {
                                $valu->id = (string)$valu->id;
                                $valu->phone_number = (string)$valu->phone_number;
                            }

                            $response["status"] 	  = true;
                            $response["id"] 		  = "3";
                            $response["user_details"] = $user;
                            $response["message"] 	  = "Please check your otp for active account.";
                            //$response['message']	  = "Thanks for registering!. Your account is active now ";
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

                    /*foreach ($user as $key) {
				$userid = $key->id;
			}*/

                    foreach ($user as $key => $valu) {
                        $userid = $valu->id;
                        $valu->id = (string)$valu->id;
                        $valu->phone_number = (string)$valu->phone_number;
                    }

                    $user = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$authen->id)->get();
                    \DB::table('tb_users')->where('id','=',$userid)->update(['mobile_token'=>$_REQUEST['mobile_token'],'device'=>$_REQUEST['device']]);
                    $response["id"] 		= "3";
                    $response["message"] 	= "Your account is active now";
                    $response['user_details']	= $user;
                    echo json_encode($response);exit;

                }else{
                    $response["id"] 		= "5";
                    $response['message'] = "Email has already been taken";
                    echo json_encode($response); exit;
                }
            }else{
                $response["id"] 		= "5";
                $response['message'] = "Phone number has already been taken";
                echo json_encode($response); exit;
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            //$response["error"] 		= $error;
            if(!empty($error)){
                if(isset($error['firstname'])){
                    $response['message'] = $error['firstname'][0];
                } /*else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				}*/ else if(isset($error['email'])){
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

    public function postCreate( Request $request){

        $rules = array(
            'group_id'       =>'required',
            //'username'       =>'required',
            'firstname'      =>'required|min:2',
            //'lastname'       =>'required|alpha_num|min:2',
            'email'          =>'required|email',
            'password'       =>'required',
            'phone_number'	 =>'required|numeric',
            //'res_name'	 	 =>'required|min:2',
            //'phone_code'	 =>'required|numeric',
            //'phone_otp'      =>'required|numeric',
        );


        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $exists = \DB::table('tb_users')->where('phone_number','=',$request->phone_number)->where('group_id','=',$request->group_id)->exists();
            if($exists == ''){

                $existsemail = \DB::table('tb_users')->where('email','=',$request->email)->where('group_id','=',$request->group_id)->exists();

                if($existsemail == ''){

                    $code = rand(10000,10000000);

                    $authen = new User;
                    $authen->username   = $request->firstname;
                    $authen->first_name = $request->firstname;
                    //$authen->last_name  = $request->lastname;
                    $authen->phone_number = $request->phone_number;
                    $authen->res_name = $request->res_name;
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
                        //'lastname'	=> $request->lastname,
                        'email'		=> $request->email ,
                        'phonenumber'=> $request->phone_number ,
                        'password'	=> $request->password,
                        'code'		=> $code,
                        //'phone_code'=>$request->phone_code ,
                        'address'   =>$request->address,

                    );
                    if($request->address!=''){
                        $val['address']=$request->address;
                        $val['user_id']=$authen->id;
                        $val['lat']=$request->lat;
                        $val['address_type']='home';
                        $val['lang']=$request->lang;
                        $val['building']='';
                        $val['landmark']='';

                        $ins=\DB::table('abserve_user_address')->insert($val);

                    }

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
                            $user = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$authen->id)->get();
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
                    $user = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$authen->id)->get();
                    \DB::table('tb_users')->where('id','=',$userid)->update(['mobile_token'=>$_REQUEST['mobile_token'],'device'=>$_REQUEST['device']]);
                    $response["id"] 		= "3";
                    $response["message"] 	= "Your account is active now";
                    $response['user_details']	= $user;
                    echo json_encode($response);exit;

                }else{
                    $response["id"] 		= "5";
                    $response['message'] = "Email has already been taken";
                    echo json_encode($response); exit;
                }


            }else{
                $response["id"] 		= "5";
                $response['message'] = "Phone number has already been taken";
                echo json_encode($response); exit;
            }

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            //$response["error"] 		= $error;
            if(!empty($error)){
                if(isset($error['firstname'])){
                    $response['message'] = $error['firstname'][0];
                } /*else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				}*/ else if(isset($error['email'])){
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

    public function postPartnercreate( Request $request) {

        $_REQUEST 				= str_replace('"','', $_REQUEST);

        $response = array();

        $rules = array(
            'username'		=>'required|alpha_num|min:2',
            'email'			=>'required|email|unique:tb_users',
            'phone_number'	=>'required|numeric',
            'password'		=>'required|between:6,12',
        );

        // if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $exists = \DB::table('tb_users')->where('phone_number','=',$request->phone_number)->where('group_id','=','3')->exists();
            //print_r($exists);   exit;
            if($exists == ''){
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

            }else{
                $response["id"] 		= "5";
                $response['message'] = "Phone number has already been taken";
                echo json_encode($response); exit;
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
            'phone_number'=>'required|numeric',
            'password'=>'required|between:6,12',
        );
        // if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {


            $exists = \DB::table('tb_users')->where('phone_number','=',$request->phone_number)->where('group_id','=','4')->exists();
            //print_r($exists);   exit;
            if($exists == ''){

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

                        $user_id 	= \DB::select("SELECT `id`,`activation` FROM `tb_users` WHERE `phone_number` = '".$_REQUEST['phone_number']."' AND `group_id` = '4'");

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
                            $response["id"]				= "31";
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

                    $user_id 	= \DB::select("SELECT `id`,`activation` FROM `tb_users` WHERE `phone_number` = '".$_REQUEST['phone_number']."' AND `group_id` = '4'");
                    //print_r($user_id);  exit;
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
                        $response["id"]				= "32";
                        $response["message"]		= "Your account is active now";
                        $response["user_id"]		= $userid;
                        $response["invite_code"]	= $code;
                        echo json_encode($response);exit;
                    }
                }


            }else{
                $response["id"]		 = "7";
                $response['message'] = "Phone number has already been taken";
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

    public function postPartnersignin( Request $request) {

        $_REQUEST 				= str_replace('"','', $_REQUEST);
        //$_REQUEST['group_id'] =3;
        $response = array();

        $rules = array(
            'phone_number'=>'required',
            'password'=>'required',
            //'group_id'=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);
        if ($validator->passes()) {
            //if($_REQUEST['group_id'] ==3){
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
                    $response["message"] 		= "Your Account is Blocked";
                    echo json_encode($response); exit;
                } else {
                    //$userid[0]["id"]				= 	$row->id;

                    if($_REQUEST['device']) {

                        \DB::table('tb_users')->where('id','=',$row->id)->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token']]);
                    } else {

                        //\DB::table("UPDATE `tb_users` set `device` = 'android' where `id` = ".$row->id);
                        \DB::table('tb_users')->where('id','=',$row->id)->update(['device'=>'android','mobile_token'=>$_REQUEST['mobile_token']]);
                    }

                    $mobile_token = \DB::table('user_mobile_tokens')->where('user_id','=',$row->id)->where('device_token','=',$_REQUEST['mobile_token'])->get();
                    if(count($mobile_token) ==0){
                        $device_token = array("user_id"=>$row->id,"device_token"=>$_REQUEST['mobile_token']);
                        \DB::table('user_mobile_tokens')->insert($device_token);
                    }

                    $res_id = \DB::table('abserve_restaurants')->select('id','name','premium_plan','agreement_status')->where('partner_id','=',$row->id)->get();

                    //$resid[0]["id"]				= 	$res_id[0]->id;

                    $response["id"] 				= "3";
                    $response["message"] 			= "success";
                    $response["user_id"]      		= $row->id;
                    $response["user_name"]      	= $row->first_name;
                    $response["phone_number"]      	= $row->phone_number;
                    $response["restaurant_status"]  = $row->active;
                    $response["res_id"]      		= $res_id[0]->id;
                    $response["res_name"]      		= $res_id[0]->name;
                    $response["premium_plan"]  		= $res_id[0]->premium_plan;
                    $response["agreement_status"]   = $res_id[0]->agreement_status;

                    //generating access token
                    //$sToken = Authorizer::issueAccessToken();
                    //$response["access_token"] = $sToken['access_token'];
                    echo json_encode($response); exit;
                }
            }
            else {
                $response["id"] 		= "4";
                $response["message"] 	= "Your phonenumber,password combination was incorrect";
                echo json_encode($response); exit;
            }

            /*}else{
			$response["id"] 		= "6";
				$response["message"] 	= "gffhfh";
	            echo json_encode($response); exit;
		}*/
        }
        else {
            $messages 				= $validator->messages();
            $error 					= $messages->getMessages();
            $response["id"] 		= "5";
            $response["error"] 		= $error;
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
                    $c_time = date("Y-M-d H:i:s");

                    $profile	=	\DB::select("SELECT `group_id` FROM `abserve_deliveryboys` WHERE `id`=".$row[0]->id);
                    \DB::table('abserve_deliveryboys')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token'],'online_time' => $c_time]);

                    $response["id"] 				= "3";
                    $response["message"] 			= "success";
                    $response["user_id"]      		= $userid;
                    $response["user_name"]      	= $row[0]->username;
                    $response["agreement_status"]  	= $row[0]->agreement_status;

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
        //$_REQUEST['group_id'] =4;
        $response = array();

        $rules = array(
            'phone_number'	=>'required|numeric',
            'password'		=>'required',
            //'group_id'      =>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);
        if ($validator->passes()) {
            //if($_REQUEST['group_id'] ==4){
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
                    $users = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('phone_number','=',$_REQUEST['phone_number'])->get();

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
            /*}else{
			    $response["id"] 		= "4";
				$response["message"] 	= "your not";
				echo json_encode($response); exit;
		}*/
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response["error"] 		= $error;
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

    public function postCustomersignin1( Request $request) {

        $_REQUEST 	= str_replace('"','', $_REQUEST);
        //$_REQUEST['group_id'] =4;
        $response = array();

        $rules = array(
            'phone_number'	=>'required|numeric',
            'password'		=>'required',
            //'group_id'      =>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);
        if ($validator->passes()) {
            //if($_REQUEST['group_id'] ==4){
            $row = \DB::table('tb_users')->select('*')->where('phone_number','=',$_REQUEST['phone_number'])->where('group_id','=','4')->get();

            $password_match = Hash::check($_REQUEST['password'], $row[0]->password);

            if ($password_match == 1 && $row[0]->phone_number == $_REQUEST['phone_number']) {

                if($row[0]->active =='0')
                {
                    // inactive
                    if($row[0]->group_id == '4') {

                        $users = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('phone_number','=',$_REQUEST['phone_number'])->where('group_id','=','4')->get();

                        foreach ($users as $key => $valu) {
                            $valu->id = (string)$valu->id;
                            $valu->phone_number = (string)$valu->phone_number;
                            if($valu->avatar != ''){
                                $valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
                            }else{
                                $valu->avatar = \URL::to('').'/uploads/images/no-image.png';
                            }
                        }
                        $otp = rand(100000, 999999);
                        //$authen->phone_otp;
                        $update = \DB::table('tb_users')->where('phone_number','=',$_REQUEST['phone_number'])->where('group_id','=','4')->update(['phone_otp'=>$otp]);
                        $this->sendSms($request->phone_number, $otp, $users[0]->id);

                        $lunch_box_cust = \DB::table('lunch_box_customers')->select('id')->where('user_id','=',$users[0]->id)->where('primary_number','=',$_REQUEST['phone_number'])->first();
                        if(!empty($lunch_box_cust->id)){
                            $lunch_box_cust_id = $lunch_box_cust->id;
                        } else {
                            $lunch_box_cust_id = "";
                        }

                        $response["id"] 				= "1";
                        $response["message"] 			= "Your Account is not active";
                        $response["user_id"]      		= $users;
                        $response["lunch_box_cust_id"] 	= (string)$lunch_box_cust_id;
                        echo json_encode($response);exit;

                    } else {
                        $response["id"] 			= "1";
                        $response["message"] 		= "Your Account is not active";
                        echo json_encode($response);exit;
                    }

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

                    $users = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('phone_number','=',$_REQUEST['phone_number'])->where('group_id','=','4')->get();

                    foreach ($users as $key => $valu) {
                        $valu->id = (string)$valu->id;
                        $valu->phone_number = (string)$valu->phone_number;
                        if($valu->avatar != ''){
                            $valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
                        }else{
                            $valu->avatar = \URL::to('').'/uploads/images/no-image.png';
                        }
                    }

                    //$profile	=	\DB::select("SELECT `group_id` FROM `tb_users` WHERE `id`=".$row[0]->id);
                    //$user_details=	\DB::select("SELECT `id,first_name,last_name,avatar` FROM `tb_users` WHERE `id`=".$row[0]->id);
                    \DB::table('tb_users')->where('id','=',$userid[0]["id"])->update(['device'=>$_REQUEST['device'],'mobile_token'=>$_REQUEST['mobile_token'],'ios_flag'=>1]);

                    $lunch_box_cust = \DB::table('lunch_box_customers')->select('id')->where('user_id','=',$users[0]->id)->where('primary_number','=',$_REQUEST['phone_number'])->first();
                    if(!empty($lunch_box_cust->id)){
                        $lunch_box_cust_id = $lunch_box_cust->id;
                    } else {
                        $lunch_box_cust_id = "";
                    }

                    $response["id"] 				= "3";
                    $response["message"] 			= "success";
                    $response["user_id"]      		= $users;
                    $response["lunch_box_cust_id"] 	= (string)$lunch_box_cust_id;
                    $response["access_token"]      	= "";
                    $response["invite_code"]      	= $invite_code;

                    //generating access token
                    //$sToken = Authorizer::issueAccessToken();
                    //$response["access_token"] = $sToken['access_token'];
                    echo json_encode($response); exit;
                }

            } else {
                $response["id"] 		= "4";
                $response["message"] 	= "Your phonenumber,password combination was incorrect";
                echo json_encode($response); exit;
            }
            /*}else{
			    $response["id"] 		= "4";
				$response["message"] 	= "your not";
				echo json_encode($response); exit;
		}*/
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response["error"] 		= $error;
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

    public function postProfileedit(Request $request) {
        $rules = array(
            'user_id'=>'required|numeric',
        );
        $val  = array();
        $validator = Validator::make($_REQUEST, $rules);
        if ($validator->passes()) {

            $check = $this->exists('tb_users','id',$request->user_id);
            if($check){
                //$user_phone = $this->exists('tb_users','phone_number',$request->phone_number);
                $user_phone = \DB::table('tb_users')->where('phone_number','=',$request->phone_number)->first();

                if( $user_phone >0 ){
                    if($user_phone->id != $request->user_id){
                        $response['message'] = "The mobile number already assigned to another user";
                        echo json_encode($response,JSON_NUMERIC_CHECK); exit;
                    }
                }
                if($request->first_name!=""){
                    $val['first_name'] = $request->first_name;
                    $val['username'] = $request->first_name;
                }
                /*if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}*/
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
                    $userdetails = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();

                    foreach ($userdetails as $key => $valu) {
                        $valu->id = (string)$valu->id;
                        $valu->phone_number = (string)$valu->phone_number;
                        if($valu->avatar != ''){
                            $valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
                        }else{
                            $valu->avatar = \URL::to('').'/uploads/images/no-image.png';
                        }
                    }
                    if($up){
                        //$userdetails = \DB::select("SELECT 'id','username','first_name','email','phone_number','address','city','state','avatar' FROM `tb_users` where `id` = '".$_REQUEST['user_id']."'") ;

                        $response['userdetails'] = $userdetails;
                        $response['message'] = "Updated successfully";
                    } else {
                        $response['userdetails'] = $userdetails;
                        $response['message'] = "Updated successfully";
                    }
                } else {
                    $response['message'] = "Doesn't updated";
                }
                /*} else {
				 $response['message'] = "Mobile number already exists";
			  }*/
            } else {
                $response['message'] = "User ID Doesn't exists";
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= $messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
        echo json_encode($response,JSON_NUMERIC_CHECK); exit;
    }

    public function postProfileedit1(Request $request) {
        $rules = array(
            'user_id'=>'required|numeric',
            'group_id'=>'required|numeric',
        );
        $val  = array();
        $validator = Validator::make($_REQUEST, $rules);
        if ($validator->passes()) {

            $check = $this->exists('tb_users','id',$request->user_id);
            if($check){
                //$user_phone = $this->exists('tb_users','phone_number',$request->phone_number);
                $user_phone = \DB::table('tb_users')->where('phone_number','=',$request->phone_number)->where('group_id','=',$request->group_id)->first();
                if(($user_phone)>0){
                    if($user_phone->id != $request->user_id){
                        $response['message'] = "The mobile number already assigned to another user";
                        echo json_encode($response,JSON_NUMERIC_CHECK); exit;
                    }
                }
                if($request->first_name!=""){
                    $val['first_name'] = $request->first_name;
                    $val['username'] = $request->first_name;
                }
                /*if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}*/
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
                    $userdetails = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();

                    foreach ($userdetails as $key => $valu) {
                        $valu->id = (string)$valu->id;
                        $valu->phone_number = (string)$valu->phone_number;
                        if($valu->avatar != ''){
                            $valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
                        }else{
                            $valu->avatar = \URL::to('').'/uploads/images/no-image.png';
                        }
                    }
                    if($up){
                        //$userdetails = \DB::select("SELECT 'id','username','first_name','email','phone_number','address','city','state','avatar' FROM `tb_users` where `id` = '".$_REQUEST['user_id']."'") ;

                        $response['userdetails'] = $userdetails;
                        $response['message'] = "Updated successfully";
                    } else {
                        $response['userdetails'] = $userdetails;
                        $response['message'] = "Updated successfully";
                    }
                } else {
                    $response['message'] = "Doesn't updated";
                }
                /*} else {
				 $response['message'] = "Mobile number already exists";
			  }*/
            } else {
                $response['message'] = "User ID Doesn't exists";
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= $messages->getMessages();
            $response["message"] 	= $error;
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
                $profile = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();
                // foreach ($profile as $key => $value) {
                $prof[] = get_object_vars($profile[0]);
                // }

                foreach ($prof as $key => &$valu) {
                    $valu['id'] = (string)$valu['id'];
                    $valu['phone_number'] = (string)$valu['phone_number'];
                    if($valu['avatar'] != ''){
                        $valu['avatar'] = \URL::to('').'/uploads/customers/'.$valu['avatar'];
                    }else{
                        $valu['avatar'] = \URL::to('').'/uploads/images/no-image.png';
                    }
                }

                $response['Customer_profile'] = ($prof);
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

    public function postCedit(Request $request){
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
                /*if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}*/
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
                $profile = \DB::table('tb_users')->select('id','username','first_name','email','phone_number','address','city','state','avatar')->where('id','=',$_REQUEST['user_id'])->get();
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
                        $value->avatar = \URL::to('').'/uploads/delivery_boy/'.$value->avatar;
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

    public function postBoyedit(Request $request){
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
                foreach($addr as $address){
                    $address->id = (string)$address->id;
                    $address->user_id = (string)$address->user_id;
                    $address->lat = (string)$address->lat;
                    $address->lang = (string)$address->lang;
                    $address->type_check = (string)$address->type_check;
                }

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

        $note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();

        $mobile_token 	= $this->userapimethod($ab_cu[0]->partner_id,'tb_users');
        $message 		= "New orders found in your restaurant";

        if($note_id[0]->device == 'ios'){
            $appapi_details	= $this->appapimethod(4);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;
            $this->iospushnotification($app_api,$mobile_token,$message,$app_name);
        } else {
            $appapi_details	= $this->appapimethod(1);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;
            $this->pushnotification($app_api,$mobile_token,$message,$app_name);
        }

        $response['message'] = "Success";

        echo json_encode($response);exit;
    }

    public function postOrderinsert1( Request $request){

        $_REQUEST = (array) json_decode(file_get_contents("php://input"));
        $_REQUEST = str_replace('"','', $_REQUEST);

        //print_r($_REQUEST); exit;
        $cust_id = $_REQUEST['user_id'];
        $res_id = $_REQUEST['res_id'];
        $address = $_REQUEST['address'];
        $building = $_REQUEST['building'];
        $landmark = $_REQUEST['landmark'];
        $lat = $_REQUEST['lat'];
        $lang = $_REQUEST['lang'];
        $coupon_price = $_REQUEST['coupon_price'];
        $coupon_id = $_REQUEST['coupon_id'];
        $s_tax = $_REQUEST['s_tax'];
        //$delivery_type = $_REQUEST['delivery_charge'];
        $total_packaging_charge = $_REQUEST['total_packaging_charge'];
        $delivery = $_REQUEST['delivery'];
        $delivery_type = $_REQUEST['delivery_type'];
        $cart_total_price = $_REQUEST['cart_total_price'];
        $item_total_price = $_REQUEST['item_total_price'];
        $instructions = $_REQUEST['instructions'];
        $offer_price = $_REQUEST['offer_price'];
        $android_version = $_REQUEST['android_version'];
        $ios_version = $_REQUEST['ios_version'];

        $user = \DB::table('tb_users')->where('id','=',$cust_id)->first();

        if($user->device == "android"){
            if($android_version != ''){
                if($android_version != ANDROID_VERSION){
                    $response['message'] = 'Please Update Latest Version';
                    //echo json_encode($response); exit;
                    $message = "Please update latest version of Android app";
                    $this->sendSms1($user->phone_number, $message, $cust_id);
                }
            } else {
                $response['message'] = 'Please Update Latest Version';
                //echo json_encode($response); exit;
                $message = "Please update latest version of Android app";
                $this->sendSms1($user->phone_number, $message, $cust_id);
            }
        }
        if($user->device == "ios"){
            if($ios_version != ''){
                if($ios_version != IOS_VERSION){
                    $response['message'] = 'Please Update Latest Version';
                    //echo json_encode($response); exit;
                    $message = "Please update latest version of IOS app";
                    $this->sendSms1($user->phone_number, $message, $cust_id);
                }
            } else {
                //$code = 400;
                //header('Status: '.$code);
                $message = "Please update latest version of IOS app";
                $this->sendSms1($user->phone_number, $message, $cust_id);
            }
        }

        $res_owner = \DB::table('abserve_restaurants')->select('ds_commission')->where('id','=',$res_id)->first();
        $commission = $res_owner->ds_commission;

        $user = \DB::table('tb_users')->where('id','=',$cust_id)->update(['android_version'=>$android_version,'ios_version'=>$ios_version]);

        if($delivery_type == "cod"){
            $status = 0;
        }
        if($delivery_type == "ccavenue"){
            $status = 7;
        }

        $data 	=	$_REQUEST;
        unset($data['user_id']);
        $aFields = array('user_id','item_total_price','s_tax','coupon_id','coupon_price','cart_total_price','res_id','address','building','landmark','lat','lang','total_packaging_charge','delivery_charge','delivery','delivery_type','instructions','offer_price');

        $removepost=DB::table('abserve_user_cart')->where('user_id', '=', $cust_id)->delete();
        //print_r($data);   exit;
        foreach($_REQUEST as $key => $name_value){
            if(in_array($key, $aFields)){

                if($key == 'cart_total_price'){
                    $keys[] = 'grand_total';
                    $vals[] = $name_value;
                } else {
                    if($key == 'user_id'){
                        $keys[] = 'cust_id';
                        $vals[] = $name_value;
                    } else {
                        if($key == 'item_total_price'){
                            $keys[] = 'total_price';
                            $vals[] = $name_value;
                        } else {
                            if($key == 'total_packaging_charge'){
                                $keys[] = 'packaging_charge';
                                $vals[] = $name_value;
                            } else {
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
                                if($key != 'status'){
                                    $keys[] = 'status';
                                    $vals[] = $status;
                                }
                                /*if($key != 'ds_commission'){
									$keys[] = 'ds_commission';
									$vals[] = $commission;
								}*/
                            }
                        }
                    }
                }
            }
        }
        foreach($_REQUEST['restaurant'][0] as $key => $res){
            if(in_array($key, $aFields)){
                if($key == 'item_total_price'){
                    $keys[] = 'total_price';
                    $vals[] = $name_value;
                } else {
                    $keys[] = $key;
                    $vals[] = $res;
                }

            }
        }
        $details_ins = (array_combine($keys, $vals));
        //print_r($details_ins); exit;
        \DB::table('abserve_order_details')->insert($details_ins);
        $oid = \DB::getPdo()->lastInsertId();

        foreach($_REQUEST['restaurant'] as $key => $food){
            $res_id = $food->res_id;
            foreach($food->food as $key1 => $foods){
                if($foods->quantity !=0){

                    $topping_ids = $foods->topping_id;
                    $topping_names = $foods->topping_name;
                    $topping_prices = $foods->topping_price;

                    $_price[] = $foods->price;
                    if($topping_names !=''){
                        $order_details[] = $foods->quantity."x".$foods->food_item."-".$foods->price."-".$topping_names."-".$topping_prices;
                    } else {
                        $order_details[] = $foods->quantity."x".$foods->food_item."-".$foods->price;
                    }

                    /*$topping_id = "";
				$topping_name = "";
				$topping_price = "";
				if($foods->toppings){
					foreach($foods->toppings as $toppings){
						$topping_id[] = $toppings->topping_id;
						$topping_name[] = $toppings->topping_name;
						$topping_price[] = $toppings->topping_price;
					}

					$topping_ids = implode(",",$topping_id);
					$topping_names = implode(",",$topping_name);
					$topping_prices = implode(",",$topping_price);
				} else {
					$topping_ids = "";
					$topping_names = "";
					$topping_prices = "";
				}*/

                    $items = array("orderid"=>$oid,"food_id"=>$foods->food_id,"topping_id"=>$topping_ids,"topping_name"=>$topping_names,"topping_price"=>$topping_prices,"food_item"=>$foods->food_item,"quantity"=>$foods->quantity,"price"=>$foods->price);

                    \DB::table('abserve_order_items')->insert($items);
                }
            }
        }
        $ins_val['order_value'] = implode('+', $_price);
        if($ins_val['order_value'] != ''){
            if($s_tax != ''){
                $ins_val['order_value'] = $ins_val['order_value']."+".$s_tax;
            }
            if($coupon_price != ''){
                $ins_val['order_value'] = $ins_val['order_value']."-".$coupon_price;
            }
        }

        $orderdetails = implode(',',$order_details);
        if($instructions !=''){
            $ins_val['order_details'] = $orderdetails.'-'.$instructions;
        } else {
            $ins_val['order_details'] = $orderdetails;
        }

        $cust = array("cust_id"=>$cust_id,"res_id"=>$res_id,"order_value"=>$ins_val['order_value'],"order_details"=>$ins_val['order_details'],"orderid"=>$oid,"order_status"=>$status);

        $part = array("partner_id"=>0,"order_value"=>$ins_val['order_value'],"order_details"=>$ins_val['order_details'],"orderid"=>$oid,"order_status"=>$status);

        $sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$res_id;
        $ab_cu 	= \DB::select($sql2);

        \DB::table('abserve_orders_customer')->insert($cust);
        \DB::table('abserve_orders_partner')->insert($part);

        \DB::table('abserve_orders_partner')
            ->where('orderid', $oid)
            ->update(['partner_id' => $ab_cu[0]->partner_id]);

        $user = \DB::table('tb_users')->where('id',$cust_id)->first();

        $response['message'] 		= "Success";
        if($delivery_type == 'ccavenue'){
            $response['order_details'] 	= array(
                "accessCode"		=> CCAVENUE_ACCESSCODE,
                "merchantId"		=> CCAVENUE_MERCHANTID,
                "orderId"			=> (string)$oid,
                "currency"			=> "INR",
                "amount"			=> (string)$cart_total_price,
                "redirectUrl"		=> \URL::to('').'/mobile/user/ccavresponsehandler',
                "cancelUrl"			=> \URL::to('').'/mobile/user/ccavresponsehandler',
                "rsaKeyUrl"			=> \URL::to('').'/ccavenue/GetRSA.php',
                /*"billingName"		=> $billing_name,
										"billingAddress"	=> $billing_address,
										"billingZip"		=> $billing_zip,
										"billingCity"		=> $billing_city,
										"billingState"		=> $billing_state,*/
                "billingCountry"	=> "India",
                "billingMobilenumber"=> (string)$user->phone_number,
                "billingEmail"		=> $user->email
            );
        } else {

            if($coupon_id !='' && $coupon_id !=0){
                $coupon = \DB::table('coupon')->where('id','=',$coupon_id)->first();
                $coupon_values = array("user_id"=>$cust_id,"coupon_id"=>$coupon_id,"coupon_code"=>$coupon->coupon_code);
                \DB::table('coupon_check')->insert($coupon_values);
            }

            //Restaurant Notification
            $appapi_details	= $this->appapimethod(2);
            //$mobile_token 	= $this->userapimethod($ab_cu[0]->partner_id,'tb_users');
            $message 		= "New orders found in your restaurant";
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;

            //$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();

            //if($note_id[0]->device == 'ios'){
            //$this->iospushnotification($mobile_token,$message,'2');
            //}else{
            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$ab_cu[0]->partner_id)->get();
            foreach($device_tokens as $device_token){
                $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
            }
            //}

            $message = "#".$oid." order placed successfully";
            $this->sendSms1($user->phone_number, $message, $cust_id);

            $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
            $phonenumber = $resuser->phone;
            $message = "#".$oid." New orders found in your restaurant";

            $this->sendSms2($phonenumber, $message, $res_id);
        }
        echo json_encode($response);exit;
    }

    public function postOrderinsert2( Request $request){

        $_REQUEST = (array) json_decode(file_get_contents("php://input"));
        $_REQUEST = str_replace('"','', $_REQUEST);

        //print_r($_REQUEST); exit;
        $cust_id = $_REQUEST['user_id'];
        $res_id = $_REQUEST['res_id'];
        $address = $_REQUEST['address'];
        $building = $_REQUEST['building'];
        $landmark = $_REQUEST['landmark'];
        $lat = $_REQUEST['lat'];
        $lang = $_REQUEST['lang'];
        $coupon_price = $_REQUEST['coupon_price'];
        $coupon_id = $_REQUEST['coupon_id'];
        $coupon_type = $_REQUEST['coupon_type'];
        $s_tax = $_REQUEST['s_tax'];
        //$delivery_type = $_REQUEST['delivery_charge'];
        $total_packaging_charge = $_REQUEST['total_packaging_charge'];
        $delivery = $_REQUEST['delivery'];
        $delivery_type = $_REQUEST['delivery_type'];
        $cart_total_price = $_REQUEST['cart_total_price'];
        $item_total_price = $_REQUEST['item_total_price'];
        $instructions = $_REQUEST['instructions'];
        $offer_price = $_REQUEST['offer_price'];
        $android_version = $_REQUEST['android_version'];
        $ios_version = $_REQUEST['ios_version'];

        $user = \DB::table('tb_users')->where('id','=',$cust_id)->first();

        if(($user->active == 0) || ($user->active == 2)){
            $response['message'] = 'Please contact your support team to activate your account';
        } else {

            if($user->device == "android"){
                if($android_version != ''){
                    if($android_version != ANDROID_VERSION){
                        $response['message'] = 'Please Update Latest Version';
                        //echo json_encode($response); exit;
                        $message = "Please update latest version of Android app";
                        $this->sendSms1($user->phone_number, $message, $cust_id);
                    }
                } else {
                    $response['message'] = 'Please Update Latest Version';
                    //echo json_encode($response); exit;
                    $message = "Please update latest version of Android app";
                    $this->sendSms1($user->phone_number, $message, $cust_id);
                }
            }

            if($user->device == "ios"){
                if($ios_version != ''){
                    if($ios_version != IOS_VERSION){
                        $response['message'] = 'Please Update Latest Version';
                        //echo json_encode($response); exit;
                        $message = "Please update latest version of IOS app";
                        $this->sendSms1($user->phone_number, $message, $cust_id);
                    }
                } else {
                    //$code = 400;
                    //header('Status: '.$code);
                    $message = "Please update latest version of IOS app";
                    $this->sendSms1($user->phone_number, $message, $cust_id);
                }
            }

            $restaurant = $_REQUEST['restaurant'][0];
            $res_id = $restaurant->res_id;

            $res_owner = \DB::table('abserve_restaurants')->select('ds_commission','hd_gst')->where('id','=',$res_id)->first();
            $commission = $res_owner->ds_commission;
            $hd_gst = $res_owner->hd_gst;
            //print_r($res_owner); exit;

            $user = \DB::table('tb_users')->where('id','=',$cust_id)->update(['android_version'=>$android_version,'ios_version'=>$ios_version]);

            if($delivery_type == "cod"){
                $status = 0;
            }
            if($delivery_type == "ccavenue"){
                $status = 7;
            }

            $data 	=	$_REQUEST;
            unset($data['user_id']);
            $aFields = array('user_id','item_total_price','s_tax','coupon_id','coupon_price','coupon_type','cart_total_price','res_id','address','building','landmark','lat','lang','total_packaging_charge','delivery_charge','delivery','delivery_type','instructions','offer_price');

            $removepost=DB::table('abserve_user_cart')->where('user_id', '=', $cust_id)->delete();

            foreach($_REQUEST as $key => $name_value){
                if(in_array($key, $aFields)){

                    if($key == 'cart_total_price'){
                        $keys[] = 'grand_total';
                        $vals[] = $name_value;
                    } else {
                        if($key == 'user_id'){
                            $keys[] = 'cust_id';
                            $vals[] = $name_value;
                        } else {
                            if($key == 'item_total_price'){
                                $keys[] = 'total_price';
                                $vals[] = $name_value;
                            } else {
                                if($key == 'total_packaging_charge'){
                                    $keys[] = 'packaging_charge';
                                    $vals[] = $name_value;
                                } else {
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
                                    if($key != 'status'){
                                        $keys[] = 'status';
                                        $vals[] = $status;
                                    }
                                    if($key != 'hd_gst'){
                                        $keys[] = 'hd_gst';
                                        $vals[] = $hd_gst;
                                    }
                                    if($key != 'ds_commission'){
                                        $keys[] = 'ds_commission';
                                        $vals[] = $commission;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            foreach($_REQUEST['restaurant'][0] as $key => $res){
                if(in_array($key, $aFields)){
                    if($key == 'item_total_price'){
                        $keys[] = 'total_price';
                        $vals[] = $name_value;
                    } else {
                        $keys[] = $key;
                        $vals[] = $res;
                    }

                }
            }
            $details_ins = (array_combine($keys, $vals));
            //print_r($details_ins); exit;
            //echo $details_ins->cust_id;  exit;

            \DB::table('abserve_order_details')->insert($details_ins);
            $oid = \DB::getPdo()->lastInsertId();




            $current_date = strtotime(date("Y-m-d"));
            $current_date_time = strtotime(date("Y-m-d H:i:s"));

            foreach($_REQUEST['restaurant'] as $key => $food){
                $res_id = $food->res_id;
                foreach($food->food as $key1 => $foods){
                    if($foods->quantity !=0){

                        $topping_ids = $foods->topping_id;
                        $topping_names = $foods->topping_name;
                        $topping_prices = $foods->topping_price;

                        $hotel_items = \DB::table('abserve_hotel_items')->select('id','special_price','special_from','special_to','buy_qty','get_qty','bogo_item_id','bogo_name','bogo_start_date','bogo_end_date')->where('id',$foods->food_id)->first();
                        if($current_date > strtotime($hotel_items->special_to)){
                            $special_price = "0.00";
                            $special_from = "0000-00-00";
                            $special_to = "0000-00-00";
                            $_price[] = $foods->price;
                            $food_price = $foods->price;
                        } else {
                            $special_price = $hotel_items->special_price;
                            $special_from = $hotel_items->special_from;
                            $special_to = $hotel_items->special_to;
                            $_price[] = $hotel_items->special_price;
                            $food_price = $hotel_items->special_price;
                        }

                        if($hotel_items->buy_qty !=0){
                            if($current_date_time > strtotime($hotel_items->bogo_end_date)){
                                $hotel_items->buy_qty = "0";
                                $hotel_items->get_qty = "0";
                                $hotel_items->bogo_name = "";
                            } else {
                                //double calcFreeQuantity_one = productQuantity / buyQuantity;
                                $calc_qty = ($foods->quantity / $hotel_items->buy_qty);
                                //double  calcFreeQuantity_two = calcFreeQuantity_one * getQuantity;
                                $calc_get_qty = floor($calc_qty * $hotel_items->get_qty);

                                $hotel_items->buy_qty = $foods->quantity;
                                $hotel_items->get_qty = $calc_get_qty;
                            }
                        }

                        if($topping_names !=''){
                            if($hotel_items->buy_qty != "0"){
                                $bogo = " ( Buy ".$foods->food_item." ".$foods->buy_qty." Get ".$hotel_items->bogo_name." ".$hotel_items->get_qty." ) ";
                                $order_details[] = $foods->quantity."x".$foods->food_item.$bogo."-".$food_price."-".$topping_names."-".$topping_prices;
                            } else {
                                $order_details[] = $foods->quantity."x".$foods->food_item."-".$food_price."-".$topping_names."-".$topping_prices;
                            }
                        } else {
                            if($hotel_items->buy_qty != "0"){
                                $bogo = " ( Buy ".$foods->food_item." ".$foods->buy_qty." Get ".$hotel_items->bogo_name." ".$hotel_items->get_qty." ) ";
                                $order_details[] = $foods->quantity."x".$foods->food_item.$bogo."-".$food_price;
                            } else {
                                $order_details[] = $foods->quantity."x".$foods->food_item."-".$food_price;
                            }
                        }
                        /*$topping_id = "";
				$topping_name = "";
				$topping_price = "";
				if($foods->toppings){
					foreach($foods->toppings as $toppings){
						$topping_id[] = $toppings->topping_id;
						$topping_name[] = $toppings->topping_name;
						$topping_price[] = $toppings->topping_price;
					}

					$topping_ids = implode(",",$topping_id);
					$topping_names = implode(",",$topping_name);
					$topping_prices = implode(",",$topping_price);
				} else {
					$topping_ids = "";
					$topping_names = "";
					$topping_prices = "";
				}*/

                        $items = array("orderid"=>$oid,"food_id"=>$foods->food_id,"topping_id"=>$topping_ids,"topping_name"=>$topping_names,"topping_price"=>$topping_prices,"food_item"=>$foods->food_item,"quantity"=>$foods->quantity,"price"=>$foods->price,"special_price"=>$special_price,"special_from"=>$special_from,"special_to"=>$special_to,"buy_qty"=>$hotel_items->buy_qty,"get_qty"=>$hotel_items->get_qty,"bogo_item_id"=>$hotel_items->bogo_item_id,"bogo_name"=>$hotel_items->bogo_name,"bogo_start_date"=>$hotel_items->bogo_start_date,"bogo_end_date"=>$hotel_items->bogo_end_date);

                        \DB::table('abserve_order_items')->insert($items);
                    }
                }
            }
            $ins_val['order_value'] = implode('+', $_price);
            if($ins_val['order_value'] != ''){
                if($s_tax != ''){
                    $ins_val['order_value'] = $ins_val['order_value']."+".$s_tax;
                }
                if($coupon_price != ''){
                    $ins_val['order_value'] = $ins_val['order_value']."-".$coupon_price;
                }
            }

            $orderdetails = implode(',',$order_details);
            if($instructions !=''){
                $ins_val['order_details'] = $orderdetails.'-'.$instructions;
            } else {
                $ins_val['order_details'] = $orderdetails;
            }

            $res_owner = \DB::table('abserve_restaurants')->select('ds_commission')->where('id','=',$res_id)->first();

            $date_time = date("Y-m-d H:i:s");

            $amtupdate = array("orderid"=>$oid,"s_tax"=>$s_tax,"offer_price"=>$offer_price,"packaging_charge"=>$total_packaging_charge,"total_price"=>$item_total_price,"ds_commission"=>$res_owner->ds_commission,"coupon_price"=>$coupon_price,"delivery_charge"=>$_REQUEST['delivery_charge'],"grand_total"=>$cart_total_price,"res_id"=>$res_id,"date_time"=>$date_time);

            $cust = array("cust_id"=>$cust_id,"res_id"=>$res_id,"order_value"=>$ins_val['order_value'],"order_details"=>$ins_val['order_details'],"orderid"=>$oid,"order_status"=>$status);

            $part = array("partner_id"=>0,"order_value"=>$ins_val['order_value'],"order_details"=>$ins_val['order_details'],"orderid"=>$oid,"order_status"=>$status);

            $sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$res_id;
            $ab_cu 	= \DB::select($sql2);

            \DB::table('order_amount_update')->insert($amtupdate);
            \DB::table('abserve_orders_customer')->insert($cust);
            \DB::table('abserve_orders_partner')->insert($part);

            \DB::table('abserve_orders_partner')
                ->where('orderid', $oid)
                ->update(['partner_id' => $ab_cu[0]->partner_id]);

            $user = \DB::table('tb_users')->where('id',$cust_id)->first();

            $response['message'] 		= "Success";
            if($delivery_type == 'ccavenue'){
                $response['order_details'] 	= array(
                    "accessCode"		=> CCAVENUE_ACCESSCODE,
                    "merchantId"		=> CCAVENUE_MERCHANTID,
                    "orderId"			=> (string)$oid,
                    "currency"			=> "INR",
                    "amount"			=> (string)$cart_total_price,
                    "redirectUrl"		=> \URL::to('').'/mobile/user/ccavresponsehandler',
                    "cancelUrl"			=> \URL::to('').'/mobile/user/ccavresponsehandler',
                    "rsaKeyUrl"			=> \URL::to('').'/ccavenue/GetRSA.php',
                    /*"billingName"		=> $billing_name,
										"billingAddress"	=> $billing_address,
										"billingZip"		=> $billing_zip,
										"billingCity"		=> $billing_city,
										"billingState"		=> $billing_state,*/
                    "billingCountry"	=> "India",
                    "billingMobilenumber"=> (string)$user->phone_number,
                    "billingEmail"		=> $user->email
                );
            } else {

                if($coupon_id !='' && $coupon_id !=0){
                    $coupon = \DB::table('coupon')->where('id','=',$coupon_id)->first();
                    $coupon_values = array("user_id"=>$cust_id,"coupon_id"=>$coupon_id,"coupon_code"=>$coupon->coupon_code);
                    \DB::table('coupon_check')->insert($coupon_values);
                }

                //Restaurant Notification
                /* $appapi_details	= $this->appapimethod(2);
			//$mobile_token 	= $this->userapimethod($ab_cu[0]->partner_id,'tb_users');
			//$message 		= "New orders found in your restaurant";
			$app_name		= $appapi_details->app_name;
			$app_api 		= $appapi_details->api; */

                //$note_id = \DB::table('tb_users')->select('device')->where('id',$ab_cu[0]->partner_id)->get();

                //if($note_id[0]->device == 'ios'){
                //$this->iospushnotification($mobile_token,$message,'2');
                //}else{
                /* $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$ab_cu[0]->partner_id)->get();
				foreach($device_tokens as $device_token){
					$this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
				} */
                //}

                $message = "#".$oid." Order Placed Successfully";
                #$this->sendSms1($user->phone_number, $message, $cust_id);

                if($user->device == 'ios'){
                    $message1 = "5:0:".$order_id;
                    $appapi_details	= $this->appapimethod(4);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->iospushnotification($app_api,$user->mobile_token,$message,$message1,$app_name);
                } else {
                    $appapi_details	= $this->appapimethod(1);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->pushnotification($app_api,$user->mobile_token,$message,$app_name);
                }
                $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                $phonenumber = $resuser->phone;
                #$message = "#".$oid." New orders found in your restaurant";
                #$this->sendSms2($phonenumber, $message, $res_id);

                //Restaurant Notification
                $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                $appapi_details	= $this->appapimethod(2);
                $app_name		= $appapi_details->app_name;
                $app_api 		= $appapi_details->api;
                $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$ab_cu[0]->partner_id)->get();
                $message =  "New orders found in your restaurant";
                foreach($device_tokens as $device_token){
                    //NO need to send this notification during on order placed, because of every min api will called from mobile level. so comment this
                    //$this->pushNotificationRestaurantOrder($device_token->device_token);
                }



            }
        }
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
            $value['time']	 = date('h:i:s A',$value['time']);
            $iOrderId = $value['id'];
            $iBoyId = 0;
            $aBoyInfo = \DB::table('abserve_orders_boy')->select('*')->where('orderid',$iOrderId)->first();
            if(($aBoyInfo) > 0){
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

        $order_details = "SELECT `ao`.`name`,`ao`.`logo`,`ao`.`location`,`ao`.`latitude`,`ao`.`longitude`,`ao`.`cuisine`,`ao`.`max_packaging_charge`,`ao`.`offer`,`ao`.`min_order_value`,`ao`.`max_value`,`ao`.`offer_from`,`ao`.`offer_to`,`ah`.`id` as  `order_id`,`ah`.`res_id`,`ah`.`total_price`,`ah`.`s_tax`,`ah`.`coupon_price`,`ah`.`offer_price`,`ah`.`grand_total`,`ah`.`packaging_charge` as `total_packaging_charge`,`ah`.`delivery_charge`,`ah`.`address`,`ah`.`building`,`ah`.`landmark`,`ah`.`coupon_id`,`ah`.`time`,`ah`.`date`,`ah`.`order_reject_desc`,`ah`.`instructions`,`ah`.`lat`,`ah`.`lang`,`ah`.`rating_flag`,`aoc`.`order_status`from `abserve_order_details` as `ah` JOIN `abserve_restaurants` as `ao`  ON `ah`.`res_id`=`ao`.`id` LEFT JOIN `abserve_orders_customer` as `aoc` ON `ah`.`id`=`aoc`.`orderid` WHERE `ah`.`cust_id`=".$_REQUEST['user_id']." AND `ah`.`status` !='7' ORDER BY `ah`.`id` DESC";
        $det=\DB::select($order_details);

        $orde_det_item=array();
        foreach ($det as $key => $value) {
            $orde_det_item[]=(get_object_vars($value));
        }
        $current_date = strtotime(date("Y-m-d"));

        foreach ($orde_det_item as $key => & $value){

            $food_id = array();
            $food_item = array();
            if($value['order_id']!=''){
                $val_foods = \DB::table('abserve_order_items')->select('food_id','food_item','quantity','price')->where('orderid',$value['order_id'])->get();
                $val_food = "SELECT `oi`.`food_id`, `oi`.`topping_id`, `oi`.`topping_name`, `oi`.`topping_price`, `oi`.`food_item`, `oi`.`quantity`, `oi`.`price`,`hi`.`packaging_charge`, `hi`.`status`, `hi`.`item_status` FROM `abserve_order_items` as `oi` LEFT JOIN `abserve_hotel_items` as `hi` ON `oi`.`food_id`=`hi`.`id` WHERE `oi`.`orderid` = ".$value['order_id'];
                $val_foods = \DB::select($val_food);

                //$value['food'] = $val_foods;
                $toppings = array();
                $foods = "";
                foreach($val_foods as $val_food){
                    //$topping_ids = explode(",",$val_food->topping_id);
                    if($val_food->topping_id !=""){

                        $top_categories = \DB::select("SELECT `id`,`category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$val_food->topping_id.")");

                        $topping_items = "";
                        //$topping_cats = "";
                        foreach($top_categories as $top_cat){

                            $prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$val_food->food_id." AND `tp`.`id` = '".$top_cat->id."'");
                            foreach($prod_topp as $prod_toppings){
                                $topping_items[] = array(
                                    "topping_id"	=> (string)$prod_toppings->topping_id,
                                    "topping_name"	=> $prod_toppings->topping_name,
                                    "topping_type"	=> $prod_toppings->topping_type,
                                    "topping_price"	=> (string)$prod_toppings->topping_price,
                                );
                            }

                            /*$topping_cats[] = array(
										"toppings_cat"		=> $top_cat->toppings_cat,
										"toppings_type"		=> $top_cat->toppings_type,
										"toppings_items"	=> $topping_items,
									  );*/

                        }
                    } else {
                        $topping_items = array();
                    }

                    $foods[] = array(
                        "food_id" 			=> (string)$val_food->food_id,
                        "food_item"			=> $val_food->food_item,
                        "quantity"			=> (string)$val_food->quantity,
                        "price"				=> (string)$val_food->price,
                        "packaging_charge"	=> (string)$val_food->packaging_charge,
                        "status"			=> $val_food->status,
                        "item_status"		=> $val_food->item_status,
                        "toppings"			=> $topping_items
                    );
                    //print_r($foods); exit;
                }

                $value['food']	  = $foods;
                $value['cuisine'] = $this->cuisines($value['cuisine']);

                $offer_to = strtotime($value['offer_to']);
                if($current_date > $offer_to){
                    $value['offer_from'] = "0000-00-00";
                    $value['offer_to'] = "0000-00-00";
                    $value['offer'] = "0";
                }

                if($value['logo'] != ''){
                    $value['logo']=\URL::to('').'/uploads/restaurants/'.$value['logo'];
                }else{
                    $value['logo']=\URL::to('').'/uploads/restaurants/Default_food.jpg';
                }
                $value['order_id'] = (string)$value['order_id'];
                $value['max_packaging_charge'] = (string)$value['max_packaging_charge'];
                $value['offer'] = (string)$value['offer'];
                $value['min_order_value'] = (string)$value['min_order_value'];
                $value['max_value'] = (string)$value['max_value'];
                $value['time'] = (string)$value['time'];
                $value['res_id'] = (string)$value['res_id'];
                $value['total_price'] = (string)$value['total_price'];
                $value['s_tax'] = (string)$value['s_tax'];
                $value['coupon_price'] = (string)$value['coupon_price'];
                $value['offer_price'] = (string)$value['offer_price'];
                $value['grand_total'] = (string)$value['grand_total'];
                $value['total_packaging_charge'] = (string)$value['total_packaging_charge'];
                $value['delivery_charge'] = (string)$value['delivery_charge'];
                $value['coupon_id'] = (string)$value['coupon_id'];
                $value['lat'] = (string)$value['lat'];
                $value['lang'] = (string)$value['lang'];
                $value['latitude'] = (string)$value['latitude'];
                $value['longitude'] = (string)$value['longitude'];
                $value['rating_flag'] = (string)$value['rating_flag'];

                $order_time = $value['time'];
                $value['time']	 = date('h:i:s A',$order_time);
                $iOrderId = $value['order_id'];
                $iBoyId = 0;
                $aBoyInfo = \DB::table('abserve_orders_boy')->select('*')->where('orderid',$iOrderId)->first();
                if(($aBoyInfo) > 0){
                    $iBoyId = $aBoyInfo->boy_id;
                    $iBoyordersts = $aBoyInfo->order_status;
                }
                $value['boy_id']	 = $iBoyId;
                if($iBoyordersts=='1' || $iBoyordersts=='2' || $iBoyordersts=='3' || $iBoyordersts=='4'){
                    $value['boy_order_sts']	 = '1';
                }else{
                    $value['boy_order_sts']	 = '0';
                }
                $dBoyInfo = \DB::table('abserve_deliveryboys')->select('*')->where('id',$iBoyId)->first();
                if(($dBoyInfo)>0){
                    $value['boy_name']	 = $dBoyInfo->username;;
                } else {
                    $value['boy_name']	 = "";
                }

                if(($value['order_status'] ==0) || ($value['order_status'] ==1) || ($value['order_status'] ==2) || ($value['order_status'] ==5)){

                    $current_date_time = strtotime(date("Y-m-d H:i:s"));
                    $order_grace_time = $order_time+(60*5);
                    if($order_grace_time >= $current_date_time){
                        $value['cancel_status']	= 1;
                    } else {
                        $value['cancel_status']	= 0;
                    }
                } else {
                    $value['cancel_status']	= 0;
                }
            }

        }

        $response['message']		= "Success";
        $response['order_details'] 	= $orde_det_item;
        echo json_encode($response);exit;
    }

    public function postNewordershow1( Request $request){

        /*$order_details = "SELECT `ao`.`name`,`ao`.`logo`,`ao`.`location`,`ah`.`id`,`ah`.`res_id`,`ah`.`total_price`,`ah`.`s_tax`,`ah`.`coupon_price`,`ah`.`grand_total`,`ah`.`address`,`ah`.`building`,`ah`.`landmark`,`ah`.`coupon_id`,`ah`.`time`,`ah`.`date`,`aoc`.`order_status` as status from `abserve_order_details` as `ah` JOIN `abserve_restaurants` as `ao`  ON `ah`.`res_id`=`ao`.`id` LEFT JOIN `abserve_orders_customer` as `aoc` ON `ah`.`id`=`aoc`.`orderid` WHERE `ah`.`cust_id`=".$_REQUEST['user_id']." ORDER BY `ah`.`id` DESC";
		$det=\DB::select($order_details);*/

        $current_date = (date("Y-m-d"));
        $current_date_end = strtotime($current_date.' 23:59:59');

        $lastmonth_date = date("Y-m-d", strtotime("-1 months"));
        $lastmonth_date_start = strtotime($lastmonth_date.' 00:00:00');

        $order_details = "SELECT `ao`.`name`,`ao`.`logo`,`ao`.`location`,`ao`.`latitude`,`ao`.`longitude`,`ao`.`cuisine`,`ao`.`max_packaging_charge`,`ao`.`offer`,`ao`.`min_order_value`,`ao`.`max_value`,`ao`.`offer_from`,`ao`.`offer_to`,`ah`.`id` as  `order_id`,`ah`.`res_id`,`ah`.`total_price`,`ah`.`s_tax`,`ah`.`coupon_price`,`ah`.`offer_price`,`ah`.`grand_total`,`ah`.`packaging_charge` as `total_packaging_charge`,`ah`.`delivery_charge`,`ah`.`address`,`ah`.`building`,`ah`.`landmark`,`ah`.`coupon_id`,`ah`.`time`,`ah`.`date`,`ah`.`order_reject_desc`,`ah`.`instructions`,`ah`.`lat`,`ah`.`lang`,`ah`.`rating_flag`,`aoc`.`order_status`from `abserve_order_details` as `ah` JOIN `abserve_restaurants` as `ao`  ON `ah`.`res_id`=`ao`.`id` LEFT JOIN `abserve_orders_customer` as `aoc` ON `ah`.`id`=`aoc`.`orderid` WHERE `ah`.`cust_id`=".$_REQUEST['user_id']." AND `ah`.`status` !='7' AND `ah`.`time` >= ".$lastmonth_date_start." AND `ah`.`time` <= ".$current_date_end." ORDER BY `ah`.`id` DESC";
        $det=\DB::select($order_details);

        $orde_det_item=array();
        foreach ($det as $key => $value) {
            $orde_det_item[]=(get_object_vars($value));
        }
        $current_date = strtotime(date("Y-m-d"));

        foreach ($orde_det_item as $key => & $value){

            $food_id = array();
            $food_item = array();
            if($value['order_id']!=''){
                //$val_foods = \DB::table('abserve_order_items')->select('food_id','food_item','quantity','price')->where('orderid',$value['order_id'])->get();
                $val_food = "SELECT `oi`.`food_id`, `oi`.`topping_id`, `oi`.`topping_name`, `oi`.`topping_price`, `oi`.`food_item`, `oi`.`quantity`, `oi`.`price`,`oi`.`special_price`,`oi`.`buy_qty`,`oi`.`get_qty`,`oi`.`bogo_name`,`hi`.`packaging_charge`, `hi`.`status`, `hi`.`item_status` FROM `abserve_order_items` as `oi` LEFT JOIN `abserve_hotel_items` as `hi` ON `oi`.`food_id`=`hi`.`id` WHERE `oi`.`orderid` = ".$value['order_id'];
                $val_foods = \DB::select($val_food);

                //$value['food'] = $val_foods;
                $toppings = array();
                $foods = "";
                $foods = array();
                foreach($val_foods as $val_food){
                    //$topping_ids = explode(",",$val_food->topping_id);
                    if($val_food->topping_id !=""){

                        $top_categories = \DB::select("SELECT `id`,`category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$val_food->topping_id.")");

                        $topping_items = "";
                        //$topping_cats = "";
                        foreach($top_categories as $top_cat){

                            $prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$val_food->food_id." AND `tp`.`id` = '".$top_cat->id."'");
                            foreach($prod_topp as $prod_toppings){
                                $topping_items = array();
                                $topping_items[] = array(
                                    "topping_id"	=> (string)$prod_toppings->topping_id,
                                    "topping_name"	=> $prod_toppings->topping_name,
                                    "topping_type"	=> $prod_toppings->topping_type,
                                    "topping_price"	=> (string)$prod_toppings->topping_price,
                                );
                            }

                            /*$topping_cats[] = array(
										"toppings_cat"		=> $top_cat->toppings_cat,
										"toppings_type"		=> $top_cat->toppings_type,
										"toppings_items"	=> $topping_items,
									  );*/

                        }
                    } else {
                        $topping_items = array();
                    }

                    $foods[] = array(
                        "food_id" 			=> (string)$val_food->food_id,
                        "food_item"			=> $val_food->food_item,
                        "quantity"			=> (string)$val_food->quantity,
                        "price"				=> (string)$val_food->price,
                        "special_price"		=> (string)$val_food->special_price,
                        "packaging_charge"	=> (string)$val_food->packaging_charge,
                        "status"			=> $val_food->status,
                        "item_status"		=> $val_food->item_status,
                        "toppings"			=> $topping_items,
                        "buy_qty"			=> (string)$val_food->buy_qty,
                        "get_qty"			=> (string)$val_food->get_qty,
                        "bogo_name"			=> $val_food->bogo_name,
                    );
                    //print_r($foods); exit;
                }
                //$value = array();
                $value['food']	  = $foods;
                $value['cuisine'] = $this->cuisines($value['cuisine']);

                $offer_to = strtotime($value['offer_to']);
                if($current_date > $offer_to){
                    $value['offer_from'] = "0000-00-00";
                    $value['offer_to'] = "0000-00-00";
                    $value['offer'] = "0";
                }

                if($value['logo'] != ''){
                    $value['logo']=\URL::to('').'/uploads/restaurants/'.$value['logo'];
                }else{
                    $value['logo']=\URL::to('').'/uploads/restaurants/Default_food.jpg';
                }
                $value['order_id'] = (string)$value['order_id'];
                $value['max_packaging_charge'] = (string)$value['max_packaging_charge'];
                $value['offer'] = (string)$value['offer'];
                $value['min_order_value'] = (string)$value['min_order_value'];
                $value['max_value'] = (string)$value['max_value'];
                $value['time'] = (string)$value['time'];
                $value['res_id'] = (string)$value['res_id'];
                $value['total_price'] = (string)$value['total_price'];
                $value['s_tax'] = (string)$value['s_tax'];
                $value['coupon_price'] = (string)$value['coupon_price'];
                $value['offer_price'] = (string)$value['offer_price'];
                $value['grand_total'] = (string)$value['grand_total'];
                $value['total_packaging_charge'] = (string)$value['total_packaging_charge'];
                $value['delivery_charge'] = (string)$value['delivery_charge'];
                $value['coupon_id'] = (string)$value['coupon_id'];
                $value['lat'] = (string)$value['lat'];
                $value['lang'] = (string)$value['lang'];
                $value['latitude'] = (string)$value['latitude'];
                $value['longitude'] = (string)$value['longitude'];
                $value['rating_flag'] = (string)$value['rating_flag'];

                $order_time = $value['time'];
                $value['time']	 = date('h:i:s A',$order_time);
                $iOrderId = $value['order_id'];
                $iBoyId = 0;
                $aBoyInfo = \DB::table('abserve_orders_boy')->select('*')->where('orderid',$iOrderId)->first();
                //echo "".$aBoyInfo->boy_id;
                //if(count($aBoyInfo) > 0){
                if($aBoyInfo > 0){
                    $iBoyId = $aBoyInfo->boy_id;
                    //print "delivery boy id ---".$iBoyId ;
                    $iBoyordersts = $aBoyInfo->order_status;
                }
                $value['boy_id']	 = $iBoyId;
                if($iBoyordersts=='1' || $iBoyordersts=='2' || $iBoyordersts=='3' || $iBoyordersts=='4'){
                    $value['boy_order_sts']	 = '1';
                }else{
                    $value['boy_order_sts']	 = '0';
                }
                $dBoyInfo = \DB::table('abserve_deliveryboys')->select('*')->where('id',$iBoyId)->first();

                //print_r($dBoyInfo);
                //exit();

                //if(count($dBoyInfo)>0){
                if($dBoyInfo > 0){
                    if($iBoyordersts=='3' || $iBoyordersts=='4'){
                        $value['boy_name']	 = $dBoyInfo->username;
                        $value['boy_number'] = $dBoyInfo->phone_number;
                    } else {
                        $value['boy_name']	 = "";
                        $value['boy_number'] = "";
                    }
                } else {
                    $value['boy_name']	 = "";
                    $value['boy_number'] = "";
                }

                if(($value['order_status'] ==0) || ($value['order_status'] ==1) || ($value['order_status'] ==2) || ($value['order_status'] ==5)){

                    $current_date_time = strtotime(date("Y-m-d H:i:s"));
                    $order_grace_time = $order_time+(60*5);
                    if($order_grace_time >= $current_date_time){
                        $value['cancel_status']	= 1;
                    } else {
                        $value['cancel_status']	= 0;
                    }
                } else {
                    $value['cancel_status']	= 0;
                }
            }

        }

        $response['message']		= "Success";
        $response['order_details'] 	= $orde_det_item;
        //$response['order_details'] 	= $value;
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

                    $query = "SELECT `oi`.* FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` WHERE `orderid` IN (".implode(',', $result['orderid']).") AND `od`.`status`='0' ORDER BY `od`.`time` DESC";
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

                    $query1 = "SELECT `od`.`id`,`time`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price`,`op`.`order_status` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_partner` AS `op` ON `op`.`orderid` = `od`.`id` WHERE `oi`.`orderid` IN (".implode(',', $result['orderid']).") AND `op`.`order_status`='0' ORDER BY `od`.`time` DESC";
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
                        $order_data = \DB::table('abserve_order_details')->select('cust_id','res_id')->where('id','=',$_REQUEST['order_id'])->get();
                        $boy_assign_id = $this->getPassignorder($_REQUEST['partner_id'],$_REQUEST['order_id'],$order_data[0]->cust_id,$order_data[0]->res_id);

                        if(!empty($boy_assign_id['boy_id'])){
                            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                            $deliveryboys = \DB::table('abserve_deliveryboys')->where('id',$boy_assign_id['boy_id'])->where('boy_status',1)->get();
                            if(count($deliveryboys)>0){
                                \DB::table('abserve_deliveryboys')->where('id','=',$boy_assign_id['boy_id'])->update(['boy_status'=>2]);
                            } else {
                                \DB::table('abserve_deliveryboys')->where('id','=',$boy_assign_id['boy_id'])->update(['boy_status'=>1]);
                            }

                            //order assign id
                            $oassignexists = \DB::table('abserve_order_assign')->where('order_id',$_REQUEST['order_id'])->first();
                            if($oassignexists === null){
                                \DB::table('abserve_order_assign')->insert(['assign_id'=>$boy_assign_id['inserted_id'],'order_id'=>$_REQUEST['order_id']]);
                            }

                            $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                            // Customer notification
                            $note_id = \DB::table('tb_users')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon";
                            $msg = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon";
                            $phonenumber = $note_id[0]->phone_number;
                            #$this->sendSms1($phonenumber, $msg, $order_datas->cust_id);

                            if($note_id[0]->device == 'ios'){
                                $message = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon";
                                $message1 = "1:0:".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                            }

                            //Restaurant Notification
                            $appapi_details	= $this->appapimethod(2);
                            //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                            $message 		= $order_datas->name." order accepted by restaurant and assign to delivery boy";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                            foreach($device_tokens as $device_token){
                                $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
                            }

                            //DeliveryBoy Notification
                            $appapi_details	= $this->appapimethod(3);
                            $mobile_token 	= $this->userapimethod($boy_assign_id['boy_id'],'abserve_deliveryboys');
                            $message 		= "Your have new Order";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$note_id = \DB::table('abserve_deliveryboys')->select('device')->where('id',$boy_assign_id['boy_id'])->get();
                            //if($note_id[0]->device == 'ios'){
                            //$this->iospushnotification($mobile_token,$message,'3');
                            //}else{
                            $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                            //}

                            $boy_data = \DB::table('abserve_deliveryboys')->select('username','phone_number')->where('id',$boy_assign_id['boy_id'])->first();

                            $response['id'] 	 = '1';
                            $response['message'] = "Order accepted and assigned to ".$boy_data->username;
                            $response['order_status'] = "1";

                        }else{
                            //$response['message'] = "Please try again.";
                            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);

                            $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                            // Customer notification
                            $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message = $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon:0:".$_REQUEST['order_id'];

                            if($note_id[0]->device == 'ios'){
                                $message = $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
                                $message1 = "1:0:".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                            }

                            //Restaurant Notification
                            $appapi_details	= $this->appapimethod(2);
                            //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                            $message 		= $order_datas->name." order accepted by restaurant";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                            foreach($device_tokens as $device_token){
                                $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
                            }

                            $response['id'] 	 = '1';
                            $response['message'] = "Order accepted by restaurant but delivery boy not assigned";
                            $response['order_status'] = "1";
                        }
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

    //order_status = 1(Partner,Customer) status = 1(Order Detail)Push_notification for Delivery Boy
    public function postManualorderassigntoboy( Request $request){

        $response = array();

        $rules = array(
            'order_id'		=>'required',
            'partner_id'	=>'required',
            'boy_id'		=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($partner_exists){
                $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
                if($order_exists){

                    $acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
                    if($acess){
                        $order_data = \DB::table('abserve_order_details')->select('cust_id','res_id','status')->where('id','=',$_REQUEST['order_id'])->get();

                        if($order_data[0]->status ==0){
                            $order_datas = $this->Order_data($_REQUEST['order_id'],'');
                            // Customer notification
                            $note_id = \DB::table('tb_users')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon:0:".$_REQUEST['order_id'];
                            $msg = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon";
                            $phonenumber = $note_id[0]->phone_number;
                            $this->sendSms1($phonenumber, $msg, $order_datas->cust_id);

                            if($note_id[0]->device == 'ios'){
                                $message = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon";
                                $message1 = "1:0:".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                            }

                            //Restaurant Notification
                            $appapi_details	= $this->appapimethod(2);
                            //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                            $message 		= $order_datas->name." order accepted by restaurant";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                            foreach($device_tokens as $device_token){
                                $this->pushnotification1($app_api,$device_token->device_token,$message,$app_name);
                            }
                        }

                        $res_id	= \DB::table('abserve_order_details')->select('res_id')->where('id',$_REQUEST['order_id'])->first();

                        $boyorderstatus = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->get();
                        if(count($boyorderstatus)>0){

                            //DeliveryBoy Notification (Reassign DeliveryBoy)
                            $appapi_details	= $this->appapimethod(3);
                            $mobile_token 	= $this->userapimethod($boyorderstatus[0]->bid,'abserve_deliveryboys');
                            $message 			= "Order reassigned";
                            $app_name			= $appapi_details->app_name;
                            $app_api 			= $appapi_details->api;

                            $this->pushnotification2($app_api,$mobile_token,$message,$app_name);

                            $boy_assign_id['inserted_id']	= \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->update(['bid'=>$_REQUEST['boy_id'],'delivery_assign'=>date('Y-m-d H:i:s'),'status'=>'0']);
                            \DB::table('abserve_order_details')->where('id',$_REQUEST['order_id'])->update(['status'=>1]);
                            \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->delete();
                        } else {
                            $boy_assign_id['inserted_id']	= \DB::table('abserve_boyorderstatus')->insertGetId(['bid'=>$_REQUEST['boy_id'],'oid'=>$_REQUEST['order_id'],'pid'=>$_REQUEST['partner_id'],'cid'=>$order_data[0]->cust_id,'rid'=>$res_id->res_id,'delivery_assign'=>date('Y-m-d H:i:s'),'status'=>0]);
                            \DB::table('abserve_order_details')->where('id',$_REQUEST['order_id'])->update(['status'=>1]);
                        }

                        if(!empty($boy_assign_id['inserted_id'])){
                            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                            $deliveryboys = \DB::table('abserve_deliveryboys')->where('id',$_REQUEST['boy_id'])->where('boy_status',0)->get();
                            if(count($deliveryboys)>0){
                                \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->update(['boy_status'=>1]);
                            } else {
                                $delboys = \DB::table('abserve_deliveryboys')->where('id',$_REQUEST['boy_id'])->where('boy_status',1)->get();
                                if(count($delboys)>0){
                                    \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->update(['boy_status'=>2]);
                                }
                            }

                            //order assign id
                            $oassignexists = \DB::table('abserve_order_assign')->where('order_id',$_REQUEST['order_id'])->first();
                            if($oassignexists === null){
                                \DB::table('abserve_order_assign')->insert(['assign_id'=>$boy_assign_id['inserted_id'],'order_id'=>$_REQUEST['order_id']]);
                            }

                            //DeliveryBoy Notification
                            $appapi_details	= $this->appapimethod(3);
                            $mobile_token 	= $this->userapimethod($_REQUEST['boy_id'],'abserve_deliveryboys');
                            $message 		= "Your have new Order";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            $this->pushnotification2($app_api,$mobile_token,$message,$app_name);

                            $boy_data = \DB::table('abserve_deliveryboys')->select('username','phone_number')->where('id',$_REQUEST['boy_id'])->first();

                            $response['id'] 	 = '1';
                            $response['message'] = "Order accepted and assigned to ".$boy_data->username;
                            $response['order_status'] = "1";

                        }
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

    //order_status = 5(Partner,Customer)Push_notification for customer
    public function postPorderreject( Request $request){
        $response = array();

        $rules = array(
            'order_id'			=>'required',
            'partner_id'		=>'required',
            //'order_reject_desc'	=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($partner_exists){
                $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
                if($order_exists){
                    $acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
                    if($acess){
                        $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>5]);
                        $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>5]);
                        $abc1 = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['order_reject_desc'=>$_REQUEST['order_reject_desc'],'status'=>5]);
                        if($abp && $abc){
                            $response['order_status'] 	= "5";
                            $response['message'] 		= "Order Rejected by Restaurant";
                            $response['id'] 			= "5";

                            //Get Order data
                            $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                            //Restaurant Notification
                            $appapi_details	= $this->appapimethod(2);
                            //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                            $message 		= $order_datas->name." order rejected by restaurant";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                            foreach($device_tokens as $device_token){
                                $this->pushnotification1($app_api,$device_token->device_token,$message,$app_name);
                            }

                            $res_id  = $order_datas->res_id;
                            $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                            $phonenumber = $resuser->phone;
                            $message = "The order #".$_REQUEST['order_id']." has been rejected";

                            $this->sendSms2($phonenumber, $message, $order_datas->res_id);

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

    public function postPorderreject1( Request $request){
        $response = array();

        $_REQUEST = (array) json_decode(file_get_contents("php://input"));
        $_REQUEST = str_replace('"','', $_REQUEST);


        $rules = array(
            'order_id'			=>'required',
            'partner_id'		=>'required',
            'partial_status'	=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($partner_exists){
                $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
                if($order_exists){
                    $acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
                    if($acess){
                        $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>5]);
                        $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>5]);

                        if($_REQUEST['partial_status'] == "false"){
                            foreach($_REQUEST['order_items'] as $oreder_items){
                                //$oreder_items->food_id;
                                //$oreder_items->food_item;
                                if($oreder_items->quantity !=''){
                                    $order_qty = $oreder_items->quantity;
                                } else {
                                    $order_qty = 0;
                                }

                                $order_reject .= $order_qty.'x'.$oreder_items->food_item.',';
                            }
                            $order_reject_desc = rtrim($order_reject,',');

                        } else {
                            $order_reject_desc = "Restaurant rejected all the items";
                        }
                        //echo $order_reject_desc; exit;
                        $abc1 = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['order_reject_desc'=>$order_reject_desc,'status'=>5]);
                        if($abp && $abc){
                            $response['order_status'] 	= "5";
                            $response['message'] 		= "Order Rejected by Restaurant";
                            $response['id'] 			= "5";

                            //Get Order data
                            $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                            //Restaurant Notification
                            $appapi_details	= $this->appapimethod(2);
                            //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                            $message 		= $order_datas->name." order rejected by restaurant";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                            foreach($device_tokens as $device_token){
                                $this->pushnotification1($app_api,$device_token->device_token,$message,$app_name);
                            }

                            $res_id  = $order_datas->res_id;
                            $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                            $phonenumber = $resuser->phone;
                            $message = "The order #".$_REQUEST['order_id']." has been rejected";

                            $this->sendSms2($phonenumber, $message, $order_datas->res_id);


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

    //order_status = 6 Push_notification for customer
    public function postAdminorderreject( Request $request){
        $response = array();

        $rules = array(
            'order_id'			=>'required',
            'partner_id'		=>'required',
            //'order_reject_desc'	=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($partner_exists){
                $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
                if($order_exists){
                    $acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
                    if($acess){

                        $order = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->where('status','!=','5')->first();
                        if($order !=''){
                            $user = \DB::table('tb_users')->where('id',$order->cust_id)->first();
                            $phonenumbekr = $user->phone_number;
                            $message = "Your order #".$order->id." has been cancelled";
                            if($order->delivery_type == "cod"){

                                \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(["status"=>'10']);
                                \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);
                                \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);

                                $this->sendSms1($phonenumber, $message, $order->cust_id);

                                $res_id  = $order->res_id;
                                $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                                $phonenumber = $resuser->phone;
                                $message = "The order #".$order->id." has been cancelled";

                                $this->sendSms2($phonenumber, $message, $order->res_id);

                                $response["status"] 	= "true";
                                $response["message"] = "Order Cancelled Successfully";
                                $response["order_id"] 	= $_REQUEST['order_id'];


                            } else {

                                include(app_path() . '/Functions/Crypto.php');

                                error_reporting(0);
                                // Provide working key share by CCAvenues
                                $working_key = 'A3B3AFEB86009252B14B60ACA1319CE0';
                                // Provide access code Shared by CCAVENUES
                                $access_code = 'AVIJ79FG38BY48JIYB';
                                // Provide URL shared by ccavenue (UAT OR Production url)
                                $URL="https://api.ccavenue.com/apis/servlet/DoWebTrans";

                                // Sample request string for the API call
                                $data[] = array(
                                    "reference_no" => $order->reference_no,
                                    "amount" => $order->grand_total
                                );
                                $merchant_json_data = array(
                                    "order_List" => $data
                                );

                                // Generate json data after call below method
                                $merchant_data = json_encode($merchant_json_data);
                                // Encrypt merchant data with working key shared by ccavenue
                                $encrypted_data = encrypt($merchant_data, $working_key);
                                //make final request string for the API call
                                $final_data ="request_type=JSON&access_code=".$access_code."&command=cancelOrder&response_type=JSON&enc_request=".$encrypted_data;

                                // Initiate api call on shared url by CCAvenues
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL,$URL);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_VERBOSE, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS,$final_data);

                                // Get server response ... curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $result = curl_exec ($ch);

                                curl_close ($ch);

                                $information=explode('&',$result);
                                $dataSize=sizeof($information);
                                $status1=explode('=',$information[0]);
                                $status2=explode('=',$information[1]);
                                if($status1[1] == '1'){
                                    $status=$status2[1];
                                }else{
                                    $status=decrypt($status2[1],$working_key);
                                }
                                \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(["status"=>'10']);
                                \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);
                                \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);

                                $this->sendSms1($phonenumber, $message, $order->cust_id);


                                $res_id  = $order->res_id;
                                $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                                $phonenumber = $resuser->phone;
                                $message = "The order #".$order->id." has been cancelled";

                                $this->sendSms2($phonenumber, $message, $order->res_id);

                                $response['order_status'] 	= "10";
                                $response["status"] 	= "true";
                                $response["message"] 	= "Order Cancelled";
                                $response["order_id"] 	= $_REQUEST['order_id'];

                            }

                            $boyid = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->first();
                            $dboy = \DB::table('abserve_deliveryboys')->where('id','=',$boyid->bid)->first();
                            if($dboy->boy_status == '2'){
                                \DB::table('abserve_deliveryboys')->where('id','=',$boyid->bid)->where('boy_status','=','2')->update(["boy_status"=>'1']);
                            } else {
                                if($dboy->boy_status == '1'){
                                    \DB::table('abserve_deliveryboys')->where('id','=',$boyid->bid)->where('boy_status','=','1')->update(["boy_status"=>'0']);
                                }
                            }

                            //Get Order data
                            $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                            //Customer notification
                            $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message = "Order Cancelled:0:".$_REQUEST['order_id'];

                            if($note_id[0]->device == 'ios'){
                                $message = $order_datas->name." Order Cancelled";
                                $message1 = "10:0:".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                            }

                            //Restaurant Notification
                            $appapi_details	= $this->appapimethod(2);
                            //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                            $message 		= $order_datas->name." Order Cancelled";
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;

                            //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                            foreach($device_tokens as $device_token){
                                $this->pushnotification1($app_api,$device_token->device_token,$message,$app_name);
                            }

                            //DeliveryBoy Notification (Cancel Order)
                            $boyorderstatus = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->get();
                            if(count($boyorderstatus)>0){
                                $appapi_details	= $this->appapimethod(3);
                                $mobile_token 	= $this->userapimethod($boyorderstatus[0]->bid,'abserve_deliveryboys');
                                $message 			= "Order Cancelled";
                                $app_name			= $appapi_details->app_name;
                                $app_api 			= $appapi_details->api;

                                $this->pushnotification2($app_api,$mobile_token,$message,$app_name);

                                \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>'10']);
                            }

                            echo json_encode($response); exit;

                        } else {

                            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>6]);
                            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>6]);
                            //$abc1 = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['order_reject_desc'=>$_REQUEST['order_reject_desc'],'status'=>6]);
                            $abc1 = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>6]);
                            if($abp && $abc){
                                $response['order_status'] 	= "6";
                                $response['message'] 		= "Order Rejected By Admin";
                                $response['id'] 			= "6";

                                //Get Order data
                                $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                                //Customer notification
                                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                                $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                                $message = $order_datas->name." rejected your order:0:".$_REQUEST['order_id'];

                                if($note_id[0]->device == 'ios'){
                                    $message = $order_datas->name." rejected your order ";
                                    $message1 = "5:0:".$_REQUEST['order_id'];

                                    $appapi_details	= $this->appapimethod(4);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                                } else {
                                    $appapi_details	= $this->appapimethod(1);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                                }

                                //Restaurant Notification
                                $appapi_details	= $this->appapimethod(2);
                                //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                                $message 		= $order_datas->name." order rejected by Admin";
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;

                                //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                                $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                                foreach($device_tokens as $device_token){
                                    $this->pushnotification1($app_api,$device_token->device_token,$message,$app_name);
                                }

                            }else{
                                $response['id'] 	 = "2";
                                $response['message'] = "Order Doesn't Rejected";
                            }
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
        $radius	= 15;
        $res_id	= \DB::table('abserve_order_details')->select('res_id')->where('id',$order_id)->first();
        $res_details = \DB::table('abserve_restaurants')->select('id','latitude','longitude')->where('id',$res_id->res_id)->first();
        $order_datas = \DB::table('abserve_orders_customer')->select('*')->where('orderid',$order_id)->first();

        //get nearby delivery boys
        $boys	= $this->nearbyDeliveryBoys($radius,$res_details->latitude,$res_details->longitude,$order_id);
        //$random_id	= array_rand($boys,1);

        $return['boy_id']	= $boys[0]->id;//$boys[$random_id]->id;
        $orderAlredy 		= \DB::table('abserve_boyorderstatus')->where('oid',$order_id)->exists();

        if($orderAlredy){
            $orderData 	= \DB::table('abserve_boyorderstatus')->select('*')->where('oid',$order_id)->first();
            \DB::table('abserve_boyorderstatus')->where('id',$orderData->id)->update(['bid'=>$boys[0]->id,'status'=>0]);
            $return['inserted_id']	= $orderData->id;
        } else {
            if(!empty($boys)){
                $return['inserted_id']	= \DB::table('abserve_boyorderstatus')->insertGetId(['bid'=>$boys[0]->id,'oid'=>$order_id,'pid'=>$partner_id,'cid'=>$cust_id,'rid'=>$res_id->res_id,'delivery_assign'=>date('Y-m-d H:i:s'),'status'=>0]);
                \DB::table('abserve_order_details')->where('id',$order_id)->update(['status'=>1]);
            }
        }
        return $return;
    }

    public function nearbyDeliveryBoys($radius,$latitude,$longitude,$order_id){
        $hav = $lat_lng = $bids_check = '';$orderDeclinedBoys = [];

        $orderAlredyDeclined = \DB::table('abserve_boyorderstatus')->where('status','2')->where('oid',$order_id)->exists();
        $orderAlredyAccepted = \DB::table('abserve_boyorderstatus')->where('status','1')->where('oid',$order_id)->exists();
        $orderAlredyAssigned = \DB::table('abserve_boyorderstatus')->where('status','0')->where('oid',$order_id)->exists();

        //extra query to update the main table
        //\DB::table('abserve_order_details')->where('id',$order_id)->update(['status'=>1]);
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
        $sql = "SELECT ".$select.$lat_lng." FROM `abserve_deliveryboys` WHERE `boy_status` = '0' AND `active` = 1 AND `online_sts` = '1' ".$bids_check.$hav;
        $this->free_boys= \DB::select($sql);
        if(empty($this->free_boys)){
            //$this->nearbyDeliveryBoys('',$latitude,$longitude,$order_id);
            $sql = "SELECT ".$select.$lat_lng." FROM `abserve_deliveryboys` WHERE `boy_status` = '1' AND `active` = 1 AND `online_sts` = '1' ".$bids_check.$hav;
            $this->free_boys= \DB::select($sql);
            return $this->free_boys;
        } else {
            return $this->free_boys;
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
                        $value->time = date('H:i:s',$value->time);
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


                                \DB::table('abserve_orders_boy')->insertGetId(['boy_id'=>$_REQUEST['boy_id'],'orderid'=>$_REQUEST['order_id'],'partner_id'=>$order_datas->partner_id,'order_value'=>$order_datas->order_value,'order_details'=>$order_datas->order_details,'order_status'=>1,'current_order'=>1,'delivery_accept'=>date('Y-m-d H:i:s')]);

                                \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>2]);
                                \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                                \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>1]);


                                //Customer notification
                                $note_id = \DB::table('tb_users')->where('id',$order_datas->cust_id)->get();

                                $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                                $message = "Delivery Boy Assigned:".$_REQUEST['boy_id'].":".$_REQUEST['order_id'];

                                $msg = "#".$_REQUEST['order_id']." Delivery Boy Assigned";
                                $phonenumber = $note_id[0]->phone_number;
                                #$this->sendSms1($phonenumber, $msg, $order_datas->cust_id);

                                if($note_id[0]->device == 'ios'){
                                    $message = "Delivery Boy Assigned";
                                    $message1 = "2:".$_REQUEST['boy_id'].":".$_REQUEST['order_id'];

                                    $appapi_details	= $this->appapimethod(4);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                                } else {
                                    $appapi_details	= $this->appapimethod(1);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                                }

                                // Partner notification
                                $appapi_details	= $this->appapimethod(2);
                                $mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
                                $message 		= "Your order was accepted by the delivery executive";
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;

                                //$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->partner_id)->get();
                                //if($note_id[0]->device == 'ios'){
                                //$this->iospushnotification($mobile_token,$message,'2');
                                //}else{
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                                //}

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
                                //$this->iospushnotification($mobile_token,$message,'1');
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
                                //$this->iospushnotification($mobile_token,$message,'2');
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
                        $value->time = date('H:i:s',$value->time);
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
                $query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' AND `order_status` != '10' ORDER BY `oi`.`id` DESC";
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

                    $query1 = "SELECT `bo`.`orderid`,`order_status`,`current_order`,`oi`.`orderid`,`od`.`res_id`,`cust_id`,`address`,`building`,`landmark`,`date`,`time`,`grand_total`,`delivery_type`,`lat` as `cust_lat`,`lang` as `cust_long` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' AND `order_status` != '10' GROUP BY `oi`.`orderid` ORDER BY `oi`.`id` DESC";
                    $confirmed_orders = \DB::select($query1);

                    foreach ($confirmed_orders as $key => $value) {
                        $value->cust_address = $value->building.",".$value->landmark.",".$value->address;
                        $value->date = date('d-m-Y', strtotime($value->date));
                        $value->time = date('h:i:s A',$value->time);

                        $user = \DB::table('tb_users')->where('id',$value->cust_id)->first();
                        $value->phone_number = $user->phone_number;

                        $rest = \DB::select("select * from `abserve_restaurants` where `id` = ".$value->res_id);
                        $value->res_id = $rest[0]->id;
                        $value->res_name = $rest[0]->name;
                        $value->location = $rest[0]->location;
                        $value->res_lat = $rest[0]->latitude;
                        $value->res_long = $rest[0]->longitude;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$value->orderid);

                        $aItem=array();
                        foreach ($aOrderItems as $key => $avalue) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$avalue->food_id)->first();

                            $aItem[] = array(
                                "id"			=> $avalue->id,
                                "orderid"		=> $avalue->orderid,
                                "food_id"		=> $avalue->food_id,
                                "food_item"		=> $avalue->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $avalue->topping_id,
                                "topping_name"	=> $avalue->topping_name,
                                "topping_price"	=> $avalue->topping_price,
                                "quantity"		=> $avalue->quantity,
                                "price"			=> $avalue->price
                            );
                        }
                        if(!empty($aItem)){
                            $value->food_item = $aItem;
                        }else{
                            $value->food_item =[];
                        }

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

    public function postNewboyconfirmedorders1( Request $request){

        $response = array();

        $rules = array(
            'boy_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
            if($boy_exists){
                $query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' AND `order_status` != '10' ORDER BY `oi`.`id` DESC";
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

                    $query1 = "SELECT `bo`.`orderid`,`order_status`,`current_order`,`oi`.`orderid`,`od`.`res_id`,`cust_id`,`address`,`building`,`landmark`,`date`,`time`,`grand_total`,`delivery_type`,`lat` as `cust_lat`,`lang` as `cust_long` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND `order_status` != '0' AND `od`.`date`> DATE_SUB(now(), INTERVAL 16 DAY) AND `order_status` != '10' GROUP BY `oi`.`orderid` ORDER BY `oi`.`id` DESC";
                    $confirmed_orders = \DB::select($query1);

                    foreach ($confirmed_orders as $key => $value) {
                        $value->cust_address = $value->building.",".$value->landmark.",".$value->address;
                        $value->date = date('d-m-Y', strtotime($value->date));
                        $value->time = date('h:i:s A',$value->time);

                        $user = \DB::table('tb_users')->where('id',$value->cust_id)->first();
                        $value->phone_number = $user->phone_number;

                        $rest = \DB::select("select * from `abserve_restaurants` where `id` = ".$value->res_id);
                        $value->res_id = $rest[0]->id;
                        $value->res_name = $rest[0]->name;
                        $value->location = $rest[0]->location;
                        $value->res_lat = $rest[0]->latitude;
                        $value->res_long = $rest[0]->longitude;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$value->orderid);

                        $aItem=array();
                        foreach ($aOrderItems as $key => $avalue) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$avalue->food_id)->first();

                            $aItem[] = array(
                                "id"			=> $avalue->id,
                                "orderid"		=> $avalue->orderid,
                                "food_id"		=> $avalue->food_id,
                                "food_item"		=> $avalue->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $avalue->topping_id,
                                "topping_name"	=> $avalue->topping_name,
                                "topping_price"	=> $avalue->topping_price,
                                "quantity"		=> $avalue->quantity,
                                "price"			=> $avalue->price,
                                "special_price"	=> $avalue->special_price,
                                "buy_qty"		=> $avalue->buy_qty,
                                "get_qty"		=> $avalue->get_qty,
                                "bogo_name"		=> $avalue->bogo_name,
                            );
                        }
                        if(!empty($aItem)){
                            $value->food_item = $aItem;
                        }else{
                            $value->food_item =[];
                        }

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

    public function postBoyprocessingorders( Request $request){

        $response = array();

        $rules = array(
            'boy_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $boy_exists = \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->exists();
            if($boy_exists){
                $query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND (`order_status` = '1' OR `order_status` = '3') ORDER BY `oi`.`id` DESC";
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

                    $query1 = "SELECT `bo`.`orderid`,`order_status`,`current_order`,`oi`.`orderid`,`od`.`res_id`,`cust_id`,`address`,`building`,`landmark`,`date`,`time`,`grand_total`,`delivery_type`,`lat` as `cust_lat`,`lang` as `cust_long` FROM `abserve_orders_boy` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `boy_id` = ".$_REQUEST['boy_id']." AND (`order_status` = '1' OR `order_status` = '3') GROUP BY `oi`.`orderid` ORDER BY `oi`.`id` DESC";
                    $confirmed_orders = \DB::select($query1);

                    foreach ($confirmed_orders as $key => $value) {
                        $value->cust_address = $value->building.",".$value->landmark.",".$value->address;
                        $value->date = date('d-m-Y', strtotime($value->date));
                        $value->time = date('h:i:s A',$value->time);

                        $user = \DB::table('tb_users')->where('id',$value->cust_id)->first();
                        $value->phone_number = $user->phone_number;

                        $rest = \DB::select("select * from `abserve_restaurants` where `id` = ".$value->res_id);
                        $value->res_id = $rest[0]->id;
                        $value->res_name = $rest[0]->name;
                        $value->location = $rest[0]->location;
                        $value->res_lat = $rest[0]->latitude;
                        $value->res_long = $rest[0]->longitude;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$value->orderid);

                        $aItem=array();
                        foreach ($aOrderItems as $key => $avalue) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$avalue->food_id)->first();

                            $aItem[] = array(
                                "id"			=> $avalue->id,
                                "orderid"		=> $avalue->orderid,
                                "food_id"		=> $avalue->food_id,
                                "food_item"		=> $avalue->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $avalue->topping_id,
                                "topping_name"	=> $avalue->topping_name,
                                "topping_price"	=> $avalue->topping_price,
                                "quantity"		=> $avalue->quantity,
                                "price"			=> $avalue->price,
                                "special_price"	=> $avalue->special_price,
                                "buy_qty"		=> $avalue->buy_qty,
                                "get_qty"		=> $avalue->get_qty,
                                "bogo_name"		=> $avalue->bogo_name,
                            );
                        }
                        if(!empty($aItem)){
                            $value->food_item = $aItem;
                        }else{
                            $value->food_item =[];
                        }

                        foreach ($count as $key_c => $value_c) {
                            if($key_c == $value->orderid)
                                $value->count = $value_c['count'];
                        }
                    }

                    $response['message'] 			= "Success";
                    $response['confirmed_orders'] 	= $confirmed_orders;

                    echo json_encode($response);exit;
                }else{
                    $response['message'] 		= "Processing Orders Doesn't exists";
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

    public function Order_data($order_id,$boy_datas=null){
        //Order data
        $order_datas = \DB::table('abserve_order_details')
            ->select('abserve_order_details.*','abserve_restaurants.*','tb_users.id as partner_id','tb_users.phone_number','abserve_orders_customer.*');
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
                            //$this->iospushnotification($mobile_token,$message,'1');
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

                            //Customer notification
                            $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message 		= "Our delivery executive ".$boy_info->username." pick your order from ".$order_datas->name." and within few minutes the order will be delivered to you";

                            if($note_id[0]->device == 'ios'){
                                //$message = "Delivery Boy Assigned";
                                $message1 = "3:".$order_datas->boy_id.":".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
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
                        if($_REQUEST['mop'] == 'Online'){
                            \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>4,'delivery'=>'paid']);
                        } else {
                            \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>4,'delivery'=>'paid','mop'=>$_REQUEST['mop']]);
                        }
                        $boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>4,'delivery_complete'=>date('Y-m-d H:i:s'),'distance'=>$_REQUEST['total_kilometer'],'order_done_status'=>$_REQUEST['done_status']]);
                        $cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>4]);
                        $par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>4]);

                        $deliveryboys = \DB::table('abserve_boyorderstatus')->where('bid',$_REQUEST['boy_id'])->get();
                        foreach($deliveryboys as $deliveryboy){
                            $oid[] = $deliveryboy->oid;
                        }
                        $delivery_boys = \DB::table('abserve_orders_customer')->where('order_status','!=',4)->whereIn('orderid',$oid)->get();

                        if(count($delivery_boys)==0){
                            \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->update(['boy_status'=>0]);
                        }

                        if($boy_up && $cus_up){

                            //Get Order data
                            $order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');

                            //Customer notification
                            $note_id = \DB::table('tb_users')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message = "Order delivered successfully. Thank you for being an amazing customer:".$_REQUEST['boy_id'].":".$_REQUEST['order_id'];
                            $msg = "Order delivered successfully. Thank you for being an amazing customer";
                            $phonenumber = $note_id[0]->phone_number;
                            $this->sendSms1($phonenumber, $msg, $order_datas->cust_id);

                            if($note_id[0]->device == 'ios'){
                                $message = "Order delivered Successfully. Thank you for being an amazing customer";
                                $message1 = "4:".$order_datas->boy_id.":".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
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

    public function postOrderreturn( Request $request){

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

                        $order = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->first();

                        /*if($order->delivery_type != "cod"){

							include(app_path() . '/Functions/Crypto.php');

							error_reporting(0);
							// Provide working key share by CCAvenues
							$working_key = 'A3B3AFEB86009252B14B60ACA1319CE0';
							// Provide access code Shared by CCAVENUES
							$access_code = 'AVIJ79FG38BY48JIYB';
							// Provide URL shared by ccavenue (UAT OR Production url)
							$URL="https://api.ccavenue.com/apis/servlet/DoWebTrans";

							// Sample request string for the API call
							$data[] = array(
										"reference_no" => $order->reference_no,
										"amount" => $order->grand_total
									  );
							$merchant_json_data = array(
													"order_List" => $data
												  );

							// Generate json data after call below method
							$merchant_data = json_encode($merchant_json_data);
							// Encrypt merchant data with working key shared by ccavenue
							$encrypted_data = encrypt($merchant_data, $working_key);
							//make final request string for the API call
							$final_data ="request_type=JSON&access_code=".$access_code."&command=cancelOrder&response_type=JSON&enc_request=".$encrypted_data;

							// Initiate api call on shared url by CCAvenues
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,$URL);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_VERBOSE, 1);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS,$final_data);

							// Get server response ... curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$result = curl_exec ($ch);

							curl_close ($ch);

							$information=explode('&',$result);
							$dataSize=sizeof($information);
							$status1=explode('=',$information[0]);
							$status2=explode('=',$information[1]);
							if($status1[1] == '1'){
								$status=$status2[1];
							}else{
								$status=decrypt($status2[1],$working_key);
							}

						}*/

                        \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>11]);
                        $boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>11]);
                        $cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>11]);
                        $par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>11]);

                        $deliveryboys = \DB::table('abserve_boyorderstatus')->where('bid',$_REQUEST['boy_id'])->get();
                        foreach($deliveryboys as $deliveryboy){
                            $oid[] = $deliveryboy->oid;
                        }
                        $delivery_boys = \DB::table('abserve_orders_customer')->where('order_status','!=',4)->whereIn('orderid',$oid)->get();

                        if(count($delivery_boys)==0){
                            \DB::table('abserve_deliveryboys')->where('id','=',$_REQUEST['boy_id'])->update(['boy_status'=>0]);
                        }

                        if($boy_up && $cus_up){

                            //Get Order data
                            $order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');

                            //Customer notification
                            $note_id = \DB::table('tb_users')->where('id',$order_datas->cust_id)->get();

                            $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                            $message = "#".$_REQUEST['order_id']." Order Returned:".$_REQUEST['boy_id'].":".$_REQUEST['order_id'];
                            $msg = "#".$_REQUEST['order_id']." Order Returned";
                            $phonenumber = $note_id[0]->phone_number;
                            #$this->sendSms1($phonenumber, $msg, $order_datas->cust_id);

                            if($note_id[0]->device == 'ios'){
                                $message = "#".$_REQUEST['order_id']." Order Returned";
                                $message1 = "11:".$order_datas->boy_id.":".$_REQUEST['order_id'];

                                $appapi_details	= $this->appapimethod(4);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                            } else {
                                $appapi_details	= $this->appapimethod(1);
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                            }

                            $response["message"] 	= "Order Returned";
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

    public function postChangepassword( Request $request){
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

    public function postUserupdate( Request $request){

        if(!\Auth::check())
            return Redirect::to('user/login');

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
        //$user->last_name 	= $request->input('last_name');
        $user->email 		= $request->input('email');
        if(isset( $data['avatar']))  $user->avatar  = $newfilename;
        $user->save();

        $first_name 	= $_POST['first_name'];
        //$last_name 		= $_POST['last_name'];
        $email 			= $_POST['email'];
        $phno 			= $_POST['phno'];
        $address 		= $_POST['address'];
        $city 			= $_POST['city'];
        $state 			= $_POST['state'];
        $pin 			= $_POST['pin'];
        $country 		= $_POST['country'];
        $username 		= $_POST['username'];

        User::where('id', '=',$userid)
            ->update(array('first_name' => $first_name,"username"=>$username,"email"=>$email,"phone_number"=>$phno,"address"=>$address,"address"=>$address,"city"=>$city,"state"=>$state,"zip_code"=>$pin,"country"=>$country));

        return Redirect::to('user/profile')->with('messagetext','Profile has been saved!')->with('msgstatus','success');
    }

    public function postSaveprofile( Request $request){

        if(!\Auth::check()) return Redirect::to('user/login');
        $rules = array(
            'first_name'=>'required|alpha_num|min:2',
            //'last_name'=>'required|alpha_num|min:2',
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
            //$user->last_name 	= $request->input('last_name');
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
                $response['message'] 	= "Location updated";
            } else {
                $response["id"] 		= "2";
                $response['message'] 	= "Location Doesn't updated";
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

    public function postBoylocationget( Request $request){
        $rules = array(
            'boy_id'=>'numeric|required'
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $dboy = \DB::table('abserve_deliveryboys')->where('id',$_REQUEST['boy_id'])->get();
            if($dboy){
                $response['message'] = "Success";
                $response["lat"] 	= (string)$dboy[0]->latitude;
                $response['lang'] 	= (string)$dboy[0]->longitude;
            } else {
                $response["id"] 		= "2";
                $response['message'] 	= "User Doesn't exist";
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
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

    public function postBoyonline( Request $request){

        $c_time = date("Y-M-d H:i:s");

        if($request->boy_online == 0){
            //Checking the offline status before 5 hours
            $online_time = \DB::table('abserve_deliveryboys')->select('online_time')->where('id',$request->boy_id)->first();
            $after_five_hrs = date('Y-M-d H:i:s', strtotime('+2 hours', strtotime( $online_time->online_time)));
            $actual_time = date( "Y-M-d H:i:s");

            $orders_to_boy = \DB::table('abserve_orders_boy')->select('order_status')->where('boy_id', '=', $request->boy_id)->where('order_status', '!=', '4')->get();

            $orders_stat_boy = \DB::table('abserve_boyorderstatus')->where('bid', '=', $request->boy_id)->where('status', '=', '0')->get();

            if(count($orders_to_boy) > 0 || count($orders_stat_boy) > 0){
                $response['message'] = "Sorry, cannot go offline you have pending orders";
            }else{
                if(strtotime($after_five_hrs) > strtotime($actual_time)){
                    $response['message'] = "Sorry, cannot go offline less than 2 hours";
                }else{
                    $aBoy_status = \DB::table('abserve_deliveryboys')->where('id',$request->boy_id)->update(['online_sts' => $request->boy_online, 'online_time' => $c_time]);

                    if($aBoy_status==1){
                        $response['message'] = "Updated Successfully";
                    }else{
                        $response['message'] = "Updated Successfully";
                    }
                }
            }

        } else{

            $aBoy_status =\DB::table('abserve_deliveryboys')->where('id',$request->boy_id)->update(['online_sts' => $request->boy_online, 'online_time' => $c_time]);

            if($aBoy_status==1){
                $response['message'] = "Updated Successfully";
            }else{
                $response['message'] = "Updated Successfully";
            }
        }
        echo json_encode($response); exit;
    }

    public function postCountrycode( Request $request){

        $country=\DB::table("abserve_countries")->select('*')->get();
        $response['countrycode'] = $country;
        echo json_encode($response,JSON_NUMERIC_CHECK); exit;

    }

    public function postCountrylistwithcode( Request $request){

        $country=\DB::table("abserve_countries")->select('*')->get();
        foreach ($country as $key => $value) {
            if($value->nicename!=''){
                $value->cuntrycode = $value->nicename.'('.'+'.$value->phonecode.')';
            }
        }
        $response['countrycode'] = $country;
        echo json_encode($response,JSON_NUMERIC_CHECK); exit;

    }

    public function postCustomeroredrcancel( Request $request){

        $orderid =$request->order_id;
        $aOrder =\DB::table('abserve_order_details')->where('id',$orderid)->delete();
        $aAssign_order =\DB::table('abserve_order_assign')->where('order_id',$orderid)->delete();
        $aPart_order =\DB::table('abserve_orders_partner')->where('orderid',$orderid)->delete();
        $aCus_order =\DB::table('abserve_orders_customer')->where('orderid',$orderid)->delete();
        $aBoy_order =\DB::table('abserve_orders_boy')->where('orderid',$orderid)->delete();
        $aItem_order =\DB::table('abserve_order_items')->where('orderid',$orderid)->delete();
        //$aadmin_order =\DB::table('abserve_order_admin')->where('orderid',$orderid)->delete();
        $admin_boy_order =\DB::table('abserve_boyorderstatus')->where('oid',$orderid)->delete();


        $response['message'] = "Your order was cancelled Successfully";

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
                        $boy_up = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>3]);
                        $boy_up = \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->where('boy_id','=',$_REQUEST['boy_id'])->update(['order_status'=>3,'delivery_dispatch'=>date('Y-m-d H:i:s')]);
                        $cus_up = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);
                        $par_up = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>3]);

                        //Get Order data
                        $order_datas	= $this->Order_data($_REQUEST['order_id'],'boy_datas');
                        $boy_info		= $this->tabledata('abserve_deliveryboys','*','id','=',$order_datas->boy_id);

                        //Customer notification
                        $note_id = \DB::table('tb_users')->where('id',$order_datas->cust_id)->get();

                        $mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
                        $message 		= "Our delivery executive ".$boy_info->username."picked your order from ".$order_datas->name." and within few minutes the order will be delivered to you :".$_REQUEST['boy_id'].":".$_REQUEST['order_id'];

                        $msg = "Our delivery executive ".$boy_info->username." picked your order from ".$order_datas->name." and within few minutes the order will be delivered to you";
                        $phonenumber = $note_id[0]->phone_number;
                        #$this->sendSms1($phonenumber, $msg, $order_datas->cust_id);

                        if($note_id[0]->device == 'ios'){
                            $message 		= "Our delivery executive ".$boy_info->username." picked your order from ".$order_datas->name." and within few minutes the order will be delivered to you";
                            $message1 = "3:".$_REQUEST['boy_id'].":".$_REQUEST['order_id'];

                            $appapi_details	= $this->appapimethod(4);
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;
                            $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                        } else {
                            $appapi_details	= $this->appapimethod(1);
                            $app_name		= $appapi_details->app_name;
                            $app_api 		= $appapi_details->api;
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

    public function getFilter( Request $request){

        //$response = array();

        $result = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines`");

        if($result){

            foreach ($result as $key => $cuisines) {
                $filter[] = array(
                    'cuisines_id'	=>	(string)$cuisines->id,
                    'cuisines_name'	=>	$cuisines->name,
                );
            }

            $response['message'] 	= "Success";
            $response['cuisines'] 	= $filter;
            $response['restaurant'] = array('offer' => 'Offers', 'pure_veg' => 'Pure Veg');
            /*echo "<pre>";
				print_r($response);exit;*/
            echo json_encode($response);exit;

        }else{

            $response['message'] 	= "Cuisines filter values doesn't exists";
            $response['filter'] 	= '';
            echo json_encode($response);exit;

        }

    }

    public function postPartnerstatus( Request $request) {
        $rules = array(
            'res_id'		=>'required',
            'user_status'	=>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $res_id = $request->res_id;
            $res_status = $request->user_status;

            $p_Orders = \DB::select("select * from `abserve_orders_partner` inner join `abserve_restaurants` on `abserve_orders_partner`.`partner_id` = `abserve_restaurants`.`partner_id` WHERE `abserve_restaurants`.`id` = ".$request->res_id." AND abserve_orders_partner.order_status IN ('0')");

            if(count($p_Orders) > 0){

                $response['message'] = 'Sorry, You have pending orders cannot go offline';

            }else{

                if($_REQUEST['res_logout'] == 1){
                    $mobile_token = $request->mobile_token;
                    $res_token = \DB::table('user_mobile_tokens')->where('device_token', '=', $mobile_token)->first();
                    if(!empty($res_token)){
                        \DB::table('user_mobile_tokens')->where('id', $res_token->id)->delete();
                    }
                    $response['message'] = 'Restaurant logout successfully';
                } else {

                    $update = \DB::table('abserve_restaurants')->where('id',$res_id)->update(['active'=>$res_status]);


                    $rest = \DB::select("SELECT `res`.`name`,`user`.`email` from `abserve_restaurants` as `res` JOIN `tb_users` as `user`  ON `res`.`partner_id`=`user`.`id` WHERE `res`.`id`=".$request->res_id);

                    if($update){

                        if($res_status == 1){

                            $response['message'] = 'Restaurant Activated Successfully';
                            $response['user_status'] = $res_status;
                        } else {

                            $name        = $rest[0]->name;
                            $email       = $rest[0]->email;
                            $to          = $email;  //rshobana@bicsglobal.com
                            $from        = "Delivery Star ";
                            $subject     = "Restaurant Status";
                            $message     = "Hi Sir,
$name Restaurant has been closed";
                            $headers     = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers  = "From: $from <admin@deliverystar.in>";

                            mail($to, $subject, $message, $headers);

                            $response['message'] = 'Restaurant Inactivated Successfully';
                            $response['user_status'] = $res_status;
                        }
                    } else {

                        if($res_status == 1){

                            $response['message'] = 'Restaurant Activated Successfully';
                            $response['user_status'] = $res_status;
                        } else {

                            $name        = $rest[0]->name;
                            $email       = $rest[0]->email;
                            $to          = $email;  //rshobana@bicsglobal.com
                            $from        = "Delivery Star ";
                            $subject     = "Restaurant Status";
                            $message     = "Hi Sir,
$name Restaurant has been closed";
                            $headers     = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers  = "From: $from <admin@deliverystar.in>";

                            mail($to, $subject, $message, $headers);

                            $response['message'] = 'Restaurant Inactivated Successfully';
                            $response['user_status'] = $res_status;
                        }
                    }

                    // Customer notification
                    /*$customers = \DB::table('tb_users')->select('*')->where('group_id','=',4)->where('mobile_token','!=','')->where('ios_flag','=',1)->get();

				foreach($customers as $customer){

					$mobile_token = $customer->mobile_token;
					$message = "Restaurant reload:".$res_id.":".$res_status;

					if($customer->device == 'ios'){
						$message = "Restaurant reload";
						$message1 = $res_id.":".$res_status;

						$appapi_details	= $this->appapimethod(4);
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						$this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
					} else {
						$appapi_details	= $this->appapimethod(1);
						$app_name		= $appapi_details->app_name;
						$app_api 		= $appapi_details->api;
						$this->pushnotification($app_api,$mobile_token,$message,$app_name);
					}
				}*/
                }
            }

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            //$response["id"] 		= "5";
            if(!empty($error)){
                if(isset($error['user_status'])){
                    $response['message'] = $error['user_status'][0];
                } else if(isset($error['user_id'])){
                    $response['message'] = $error['res_id'][0];
                }
            }
        }
        echo json_encode($response); exit;
    }

    public function postPartnerpastorders( Request $request){

        /*$response = array();

		$rules = array(
			'partner_id'	=>'required',
			);

		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$query = "SELECT `oi`.`orderid`,`food_item` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0' AND (`order_status` = '4' OR `order_status` = '5')";
				$past_orders_count = \DB::select($query);
				if(!empty($past_orders_count) && $past_orders_count != ''){
					foreach ($past_orders_count as $key => $value) {
						if($value->orderid === $value->orderid)
							$orders[$value->orderid][] = $value;
					}
					foreach ($orders as $key => $value) {
						$count[$key]['count'] = sizeof ($value);
					}
					$orders = '';

					$query1 = "SELECT `bo`.`orderid`,`order_status`,`oi`.`orderid`,`od`.`time`,`grand_total` FROM `abserve_orders_partner` AS `bo` JOIN `abserve_order_items` AS `oi` ON `oi`.`orderid` = `bo`.`orderid` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `bo`.`orderid` WHERE `partner_id` = ".$_REQUEST['partner_id']." AND `order_status` != '0' AND ( `order_status` = '4' OR `order_status` = '5') GROUP BY `oi`.`orderid`";
					$past_orders = \DB::select($query1);

					foreach ($past_orders as $key => $value) {
						$value->time = date('H:i:s',$value->time);
						foreach ($count as $key_c => $value_c) {
							if($key_c == $value->orderid)
								$value->count = $value_c['count'];
						}
					}

					$response['message'] = "Success";
					$response['past_orders'] = $past_orders;

					echo json_encode($response);exit;
				}else{
					$response['message'] = "Past Orders Doesn't exists";
					$response['past_orders'] = '';
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
		}*/

        $response = $whole_orders = array();

        $rules = array(
            'partner_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($id_exists){
                $aResponse = array();

                if($_REQUEST['duration'] != 'ALL'){
                    if($_REQUEST['duration'] == 'TODAY'){
                        $cond .= "AND `date`= CURDATE()";
                    }
                    if($_REQUEST['duration'] == 'WEEK'){
                        $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 WEEK)";
                    }
                    if($_REQUEST['duration'] == 'MONTH'){
                        $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 MONTH)";
                    }
                    if($_REQUEST['duration'] == 'CUSTOM'){
                        if(isset($_REQUEST['from_date']) && ($_REQUEST['from_date'] !='')){
                            if(isset($_REQUEST['to_date']) && ($_REQUEST['to_date'] !='')){
                                $cond .= "AND `date` >= '".$_REQUEST['from_date']."' AND `date` <= '".$_REQUEST['to_date']."'";
                            } else {
                                $response["message"] 	= (array)"To Date Missing";
                                echo json_encode($response); exit;
                            }
                        } else {
                            $response["message"] 	= (array)"From Date Missing";
                            echo json_encode($response); exit;
                        }

                    }
                }

                $aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `op`.`order_status` != '0' AND (`op`.`order_status` = '4' OR `op`.`order_status` = '6' OR `op`.`order_status` = '10' OR `op`.`order_status` = '11') ".$cond." order by `od`.`id` desc");

                if(!empty($aOrders)){

                    $aTot = array();
                    foreach ($aOrders as $sKey => $aValue) {
                        $aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $array['count'] 		= $aRes[0]->total_count;
                        $array['id'] 			= $aValue->orderid;
                        //$array['time'] 		= $aValue->time;
                        $array['date'] 			= date('d-m-Y',strtotime($aValue->date));
                        $array['time'] 			= date('h:i:s A',$aValue->time);
                        $array['instructions'] 	= $aValue->instructions;
                        $array['status'] 		= $aValue->status;
                        $array['order_status'] 	= $aValue->order_status;
                        $array['total_price'] 	= ($aValue->grand_total - $aValue->delivery_charge);
                        $array['subtotal']	 	= $aValue->grand_total;
                        $array['tax']		 	= $aValue->s_tax;
                        $array['packaging_charge'] = $aValue->packaging_charge;
                        $array['coupon_price'] 	= $aValue->coupon_price;
                        $array['offer_price'] 	= $aValue->offer_price;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $aItem=array();
                        foreach ($aOrderItems as $key => $value) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$value->food_id)->first();

                            $tot_topping_price = 0;
                            if($value->topping_price !=""){
                                $tot_topping_price = array_sum( explode( ',', $value->topping_price ) );
                            }
                            $aItem[] = array(
                                "id"			=> $value->id,
                                "orderid"		=> $value->orderid,
                                "food_id"		=> $value->food_id,
                                "food_item"		=> $value->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $value->topping_id,
                                "topping_name"	=> $value->topping_name,
                                "topping_price"	=> $value->topping_price,
                                "quantity"		=> $value->quantity,
                                "price"			=> ($value->price + $tot_topping_price)
                            );
                        }
                        if(!empty($aItem)){
                            $array['items'] = $aItem;
                        }else{
                            $array['items'] =[];
                        }

                        $aTot[] = $array;
                    }

                    $response['message'] = "Success";
                    $response['past_orders'] = $aTot;
                    //echo "<pre>";var_dump($array); exit;
                    echo json_encode($response);exit;

                }else{
                    $response['message'] = "No orders found";
                    $response['past_orders'] = '';
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

    public function postPartnerpastorders1( Request $request){

        $response = $whole_orders = array();

        $rules = array(
            'partner_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($id_exists){
                $aResponse = array();

                if($_REQUEST['duration'] != 'ALL'){
                    if($_REQUEST['duration'] == 'TODAY'){
                        $cond .= "AND `date`= CURDATE()";
                    }
                    if($_REQUEST['duration'] == 'WEEK'){
                        $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 WEEK)";
                    }
                    if($_REQUEST['duration'] == 'MONTH'){
                        $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 MONTH)";
                    }
                    if($_REQUEST['duration'] == 'CUSTOM'){
                        if(isset($_REQUEST['from_date']) && ($_REQUEST['from_date'] !='')){
                            if(isset($_REQUEST['to_date']) && ($_REQUEST['to_date'] !='')){
                                $cond .= "AND `date` >= '".$_REQUEST['from_date']."' AND `date` <= '".$_REQUEST['to_date']."'";
                            } else {
                                $response["message"] 	= (array)"To Date Missing";
                                echo json_encode($response); exit;
                            }
                        } else {
                            $response["message"] 	= (array)"From Date Missing";
                            echo json_encode($response); exit;
                        }

                    }
                }

                $aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `op`.`order_status` != '0' AND (`op`.`order_status` = '4' OR `op`.`order_status` = '6' OR `op`.`order_status` = '10' OR `op`.`order_status` = '11') ".$cond." order by `od`.`id` desc");

                if(!empty($aOrders)){

                    $aTot = array();
                    foreach ($aOrders as $sKey => $aValue) {
                        $aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $array['count'] 		= $aRes[0]->total_count;
                        $array['id'] 			= $aValue->orderid;
                        //$array['time'] 		= $aValue->time;
                        $array['date'] 			= date('d-m-Y',strtotime($aValue->date));
                        $array['time'] 			= date('h:i:s A',$aValue->time);
                        $array['instructions'] 	= $aValue->instructions;
                        $array['status'] 		= $aValue->status;
                        $array['order_status'] 	= $aValue->order_status;
                        $array['total_price'] 	= ($aValue->grand_total - $aValue->delivery_charge);
                        $array['subtotal']	 	= $aValue->grand_total;
                        $array['tax']		 	= $aValue->s_tax;
                        $array['packaging_charge'] = $aValue->packaging_charge;
                        $array['coupon_price'] 	= $aValue->coupon_price;
                        $array['offer_price'] 	= $aValue->offer_price;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $aItem=array();
                        foreach ($aOrderItems as $key => $value) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$value->food_id)->first();

                            $tot_topping_price = 0;
                            if($value->topping_price !=""){
                                $tot_topping_price = array_sum( explode( ',', $value->topping_price ) );
                            }

                            if($value->special_price !="0.00"){
                                $value->special_price = $value->special_price + $tot_topping_price;
                            } else {
                                $value->price = $value->price + $tot_topping_price;
                            }

                            $aItem[] = array(
                                "id"			=> $value->id,
                                "orderid"		=> $value->orderid,
                                "food_id"		=> $value->food_id,
                                "food_item"		=> $value->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $value->topping_id,
                                "topping_name"	=> $value->topping_name,
                                "topping_price"	=> $value->topping_price,
                                "quantity"		=> $value->quantity,
                                "price"			=> $value->price,
                                "special_price"	=> $value->special_price,
                                "buy_qty"		=> $value->buy_qty,
                                "get_qty"		=> $value->get_qty,
                                "bogo_name"		=> $value->bogo_name,
                            );
                        }
                        if(!empty($aItem)){
                            $array['items'] = $aItem;
                        }else{
                            $array['items'] =[];
                        }

                        $aTot[] = $array;
                    }

                    $response['message'] = "Success";
                    $response['past_orders'] = $aTot;
                    //echo "<pre>";var_dump($array); exit;
                    echo json_encode($response);exit;

                }else{
                    $response['message'] = "No orders found";
                    $response['past_orders'] = '';
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

    public function postPartnerpastorders2( Request $request){

        $response = $whole_orders = array();

        $rules = array(
            'partner_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($id_exists){
                $aResponse = array();

                if($_REQUEST['duration'] != 'ALL'){
                    if($_REQUEST['duration'] == 'TODAY'){
                        $cond .= "AND `date`= CURDATE()";
                    }
                    if($_REQUEST['duration'] == 'WEEK'){
                        $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 WEEK)";
                    }
                    if($_REQUEST['duration'] == 'MONTH'){
                        $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 MONTH)";
                    }
                    if($_REQUEST['duration'] == 'CUSTOM'){
                        if(isset($_REQUEST['from_date']) && ($_REQUEST['from_date'] !='')){
                            if(isset($_REQUEST['to_date']) && ($_REQUEST['to_date'] !='')){
                                $cond .= "AND `date` >= '".$_REQUEST['from_date']."' AND `date` <= '".$_REQUEST['to_date']."'";
                            } else {
                                $response["message"] 	= (array)"To Date Missing";
                                echo json_encode($response); exit;
                            }
                        } else {
                            $response["message"] 	= (array)"From Date Missing";
                            echo json_encode($response); exit;
                        }

                    }
                }

                $aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `op`.`order_status` != '0' AND (`op`.`order_status` = '4' OR `op`.`order_status` = '6' OR `op`.`order_status` = '10' OR `op`.`order_status` = '11') ".$cond." order by `od`.`id` desc");

                if(!empty($aOrders)){

                    $aTot = array();
                    foreach ($aOrders as $sKey => $aValue) {
                        $aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);
                        if($aValue->coupon_type == 2){//DS Coupon
                            $coupon_price = $aValue->coupon_price;
                        } else {//Restaurant Coupon
                            $coupon_price = 0;
                        }
                        if($aValue->hd_gst == 1){
                            $gst = $aValue->s_tax;
                            $total_price = (($aValue->grand_total - $aValue->delivery_charge)+($coupon_price));
                        } else {
                            $gst = 0;
                            $total_price = ((($aValue->grand_total - $aValue->delivery_charge)+($coupon_price))-($aValue->s_tax));
                        }

                        $array['count'] 		= $aRes[0]->total_count;
                        $array['id'] 			= $aValue->orderid;
                        //$array['time'] 		= $aValue->time;
                        $array['date'] 			= date('d-m-Y',strtotime($aValue->date));
                        $array['time'] 			= date('h:i:s A',$aValue->time);
                        $array['instructions'] 	= $aValue->instructions;
                        $array['status'] 		= $aValue->status;
                        $array['order_status'] 	= $aValue->order_status;
                        $array['total_price'] 	= $total_price;
                        $array['subtotal']	 	= $aValue->grand_total;
                        $array['tax']		 	= $gst;
                        $array['packaging_charge'] = $aValue->packaging_charge;
                        $array['coupon_price'] 	= $aValue->coupon_price;
                        $array['offer_price'] 	= $aValue->offer_price;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $aItem=array();
                        foreach ($aOrderItems as $key => $value) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$value->food_id)->first();

                            $tot_topping_price = 0;
                            if($value->topping_price !=""){
                                $tot_topping_price = array_sum( explode( ',', $value->topping_price ) );
                            }

                            if($value->topping_id != ""){

                                $topping_id = $value->topping_id;
                                $top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type`,`id` FROM `toppings` WHERE `id` IN (".$topping_id.") group by `category`");

                                $topping_cats = array();

                                foreach($top_categories as $top_cat){

                                    $prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->food_id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."' AND `pt`.`topping_id` IN (".$topping_id.")");

                                    $topping_items = array();
                                    foreach($prod_topp as $prod_toppings){
                                        $topping_items[] = array(
                                            "topping_id"	=> (string)$prod_toppings->topping_id,
                                            "topping_name"	=> $prod_toppings->topping_name,
                                            "topping_type"	=> $prod_toppings->topping_type,
                                            "topping_price"	=> (string)$prod_toppings->topping_price,
                                        );
                                    }

                                    $topping_cats[] = array(
                                        "toppings_cat"		=> $top_cat->toppings_cat,
                                        "toppings_type"		=> $top_cat->toppings_type,
                                        "toppings_items"	=> $topping_items,
                                    );
                                }

                            } else {
                                $topping_cats = array();
                            }




                            if($value->special_price !="0.00"){
                                $value->special_price = $value->special_price + $tot_topping_price;
                            } else {
                                $value->price = $value->price + $tot_topping_price;
                            }

                            $aItem[] = array(
                                "id"			=> $value->id,
                                "orderid"		=> $value->orderid,
                                "food_id"		=> $value->food_id,
                                "food_item"		=> $value->food_item,
                                "food_description"	=> $food_items->description,
                                //"topping_id"	=> $value->topping_id,
                                //"topping_name"	=> $value->topping_name,
                                "toppings"	    => $topping_cats,
                                "quantity"		=> $value->quantity,
                                "price"			=> $value->price,
                                "special_price"	=> $value->special_price,
                                "buy_qty"		=> $value->buy_qty,
                                "get_qty"		=> $value->get_qty,
                                "bogo_name"		=> $value->bogo_name,
                            );
                        }
                        if(!empty($aItem)){
                            $array['items'] = $aItem;
                        }else{
                            $array['items'] =[];
                        }

                        $aTot[] = $array;
                    }

                    $response['message'] = "Success";
                    $response['past_orders'] = $aTot;
                    //echo "<pre>";var_dump($array); exit;
                    echo json_encode($response);exit;

                }else{
                    $response['message'] = "No orders found";
                    $response['past_orders'] = '';
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

    public function postPartnerprocessingorders( Request $request){

        $response = $whole_orders = array();

        $rules = array(
            'partner_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($id_exists){
                $aResponse = array();

                $aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `op`.`order_status` != '0' AND (`op`.`order_status` = '1' OR `op`.`order_status` = '2' OR `op`.`order_status` = '3' OR `op`.`order_status` = '5') order by `od`.`id` desc");

                if(!empty($aOrders)){

                    $aTot = array();
                    foreach ($aOrders as $sKey => $aValue) {
                        $aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $array['count'] 		= $aRes[0]->total_count;
                        $array['id'] 			= $aValue->orderid;
                        //$array['time'] 		= $aValue->time;
                        $array['time'] 			= date('h:i:s A',$aValue->time);
                        $array['instructions'] 	= $aValue->instructions;
                        $array['status'] 		= $aValue->status;
                        $array['order_status'] 	= $aValue->order_status;
                        $array['total_price'] 	= ($aValue->grand_total - $aValue->delivery_charge);
                        $array['subtotal'] 		= $aValue->grand_total;
                        $array['tax'] 			= $aValue->s_tax;
                        $array['packaging_charge'] = $aValue->packaging_charge;
                        $array['coupon_price'] 	= $aValue->coupon_price;
                        $array['offer_price'] 	= $aValue->offer_price;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);
                        $array['delivery_boy'] = '';

                        if($aValue->order_status == '2' || $aValue->order_status == '3'){
                            $delivery_boy = \DB::select("select `db`.id as boy_id, `db`.username, `db`.phone_number from `abserve_orders_boy` as `ob` join `abserve_deliveryboys` as `db` on `db`.`id` = `ob`.`boy_id` where `ob`.`orderid` = ".$aValue->orderid."");

                            $array['delivery_boy'] = $delivery_boy;
                        }

                        $aItem=array();
                        foreach ($aOrderItems as $key => $value) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$value->food_id)->first();

                            $tot_topping_price = 0;
                            if($value->topping_price !=""){
                                $tot_topping_price = array_sum( explode( ',', $value->topping_price ) );
                            }
                            $aItem[] = array(
                                "id"			=> $value->id,
                                "orderid"		=> $value->orderid,
                                "food_id"		=> $value->food_id,
                                "food_item"		=> $value->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $value->topping_id,
                                "topping_name"	=> $value->topping_name,
                                "topping_price"	=> $value->topping_price,
                                "quantity"		=> $value->quantity,
                                "price"			=> ($value->price + $tot_topping_price)
                            );
                        }
                        if(!empty($aItem)){
                            $array['items'] = $aItem;
                        }else{
                            $array['items'] =[];
                        }

                        $aTot[] = $array;
                    }

                    $response['message'] = "Success";
                    $response['processing_orders'] = $aTot;
                    //echo "<pre>";var_dump($array); exit;
                    echo json_encode($response);exit;

                }else{
                    $response['message'] = "No orders found";
                    $response['processing_orders'] = '';
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

    public function postPartnerprocessingorders1( Request $request){

        $response = $whole_orders = array();

        $rules = array(
            'partner_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($id_exists){
                $aResponse = array();

                $aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `op`.`order_status` != '0' AND (`op`.`order_status` = '1' OR `op`.`order_status` = '2' OR `op`.`order_status` = '3' OR `op`.`order_status` = '5') order by `od`.`id` desc");

                if(!empty($aOrders)){

                    $aTot = array();
                    foreach ($aOrders as $sKey => $aValue) {
                        $aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);

                        $array['count'] 		= $aRes[0]->total_count;
                        $array['id'] 			= $aValue->orderid;
                        //$array['time'] 		= $aValue->time;
                        $array['time'] 			= date('h:i:s A',$aValue->time);
                        $array['instructions'] 	= $aValue->instructions;
                        $array['status'] 		= $aValue->status;
                        $array['order_status'] 	= $aValue->order_status;
                        $array['total_price'] 	= ($aValue->grand_total - $aValue->delivery_charge);
                        $array['subtotal'] 		= $aValue->grand_total;
                        $array['tax'] 			= $aValue->s_tax;
                        $array['packaging_charge'] = $aValue->packaging_charge;
                        $array['coupon_price'] 	= $aValue->coupon_price;
                        $array['offer_price'] 	= $aValue->offer_price;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);
                        $array['delivery_boy'] = '';

                        if($aValue->order_status == '2' || $aValue->order_status == '3'){
                            $delivery_boy = \DB::select("select `db`.id as boy_id, `db`.username, `db`.phone_number from `abserve_orders_boy` as `ob` join `abserve_deliveryboys` as `db` on `db`.`id` = `ob`.`boy_id` where `ob`.`orderid` = ".$aValue->orderid."");

                            $array['delivery_boy'] = $delivery_boy;
                        }

                        $aItem=array();
                        foreach ($aOrderItems as $key => $value) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$value->food_id)->first();

                            $tot_topping_price = 0;
                            if($value->topping_price !=""){
                                $tot_topping_price = array_sum( explode( ',', $value->topping_price ) );
                            }

                            if($value->special_price !="0.00"){
                                $value->special_price = $value->special_price + $tot_topping_price;
                            } else {
                                $value->price = $value->price + $tot_topping_price;
                            }

                            $aItem[] = array(
                                "id"			=> $value->id,
                                "orderid"		=> $value->orderid,
                                "food_id"		=> $value->food_id,
                                "food_item"		=> $value->food_item,
                                "food_description"	=> $food_items->description,
                                "topping_id"	=> $value->topping_id,
                                "topping_name"	=> $value->topping_name,
                                "topping_price"	=> $value->topping_price,
                                "quantity"		=> $value->quantity,
                                "price"			=> $value->price,
                                "special_price"	=> $value->special_price,
                                "buy_qty"		=> $value->buy_qty,
                                "get_qty"		=> $value->get_qty,
                                "bogo_name"		=> $value->bogo_name,
                            );
                        }
                        if(!empty($aItem)){
                            $array['items'] = $aItem;
                        }else{
                            $array['items'] =[];
                        }

                        $aTot[] = $array;
                    }

                    $response['message'] = "Success";
                    $response['processing_orders'] = $aTot;
                    //echo "<pre>";var_dump($array); exit;
                    echo json_encode($response);exit;

                }else{
                    $response['message'] = "No orders found";
                    $response['processing_orders'] = '';
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


    public function postPartnerprocessingorders2( Request $request){

        $response = $whole_orders = array();

        $rules = array(
            'partner_id'		=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($id_exists){
                $aResponse = array();

                $aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `op`.`order_status` != '0' AND (`op`.`order_status` = '1' OR `op`.`order_status` = '2' OR `op`.`order_status` = '3' OR `op`.`order_status` = '5') order by `od`.`id` desc");

                if(!empty($aOrders)){

                    $aTot = array();
                    foreach ($aOrders as $sKey => $aValue) {
                        $aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);
                        if($aValue->coupon_type == 2){//DS Coupon
                            $coupon_price = $aValue->coupon_price;
                        } else {//Restaurant Coupon
                            $coupon_price = 0;
                        }
                        if($aValue->hd_gst == 1){
                            $gst = $aValue->s_tax;
                            $total_price = (($aValue->grand_total - $aValue->delivery_charge)+($coupon_price));
                        } else {
                            $gst = 0;
                            $total_price = ((($aValue->grand_total - $aValue->delivery_charge)+($coupon_price))-($aValue->s_tax));
                        }

                        $array['count'] 		= $aRes[0]->total_count;
                        $array['id'] 			= $aValue->orderid;
                        //$array['time'] 		= $aValue->time;
                        $array['time'] 			= date('h:i:s A',$aValue->time);
                        $array['instructions'] 	= $aValue->instructions;
                        $array['status'] 		= $aValue->status;
                        $array['order_status'] 	= $aValue->order_status;
                        $array['total_price'] 	= $total_price;
                        $array['subtotal'] 		= $aValue->grand_total;
                        $array['tax'] 			= $gst;
                        $array['packaging_charge'] = $aValue->packaging_charge;
                        $array['coupon_price'] 	= $aValue->coupon_price;
                        $array['offer_price'] 	= $aValue->offer_price;

                        $aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);
                        $array['delivery_boy'] = '';

                        if($aValue->order_status == '2' || $aValue->order_status == '3'){
                            $delivery_boy = \DB::select("select `db`.id as boy_id, `db`.username, `db`.phone_number from `abserve_orders_boy` as `ob` join `abserve_deliveryboys` as `db` on `db`.`id` = `ob`.`boy_id` where `ob`.`orderid` = ".$aValue->orderid."");

                            $array['delivery_boy'] = $delivery_boy;
                        }

                        $aItem=array();
                        foreach ($aOrderItems as $key => $value) {
                            $food_items = \DB::table('abserve_hotel_items')->select('description')->where('id',$value->food_id)->first();

                            $tot_topping_price = 0;
                            if($value->topping_price !=""){
                                $tot_topping_price = array_sum( explode( ',', $value->topping_price ) );
                            }


                            if($value->topping_id != ""){

                                $topping_id = $value->topping_id;
                                $top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type`,`id` FROM `toppings` WHERE `id` IN (".$topping_id.") group by `category`");

                                $topping_cats = array();

                                foreach($top_categories as $top_cat){

                                    $prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->food_id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."' AND `pt`.`topping_id` IN (".$topping_id.")");

                                    $topping_items = array();
                                    foreach($prod_topp as $prod_toppings){
                                        $topping_items[] = array(
                                            "topping_id"	=> (string)$prod_toppings->topping_id,
                                            "topping_name"	=> $prod_toppings->topping_name,
                                            "topping_type"	=> $prod_toppings->topping_type,
                                            "topping_price"	=> (string)$prod_toppings->topping_price,
                                        );
                                    }

                                    $topping_cats[] = array(
                                        "toppings_cat"		=> $top_cat->toppings_cat,
                                        "toppings_type"		=> $top_cat->toppings_type,
                                        "toppings_items"	=> $topping_items,
                                    );
                                }

                            } else {
                                $topping_cats = array();
                            }


                            if($value->special_price !="0.00"){
                                $value->special_price = $value->special_price + $tot_topping_price;
                            } else {
                                $value->price = $value->price + $tot_topping_price;
                            }

                            $aItem[] = array(
                                "id"			=> $value->id,
                                "orderid"		=> $value->orderid,
                                "food_id"		=> $value->food_id,
                                "food_item"		=> $value->food_item,
                                "food_description"	=> $food_items->description,
                                //"topping_id"	=> $value->topping_id,
                                //"topping_name"	=> $value->topping_name,
                                "toppings"	    => $topping_cats,
                                "quantity"		=> $value->quantity,
                                "price"			=> $value->price,
                                "special_price"	=> $value->special_price,
                                "buy_qty"		=> $value->buy_qty,
                                "get_qty"		=> $value->get_qty,
                                "bogo_name"		=> $value->bogo_name,
                            );
                        }
                        if(!empty($aItem)){
                            $array['items'] = $aItem;
                        }else{
                            $array['items'] =[];
                        }

                        $aTot[] = $array;
                    }

                    $response['message'] = "Success";
                    $response['processing_orders'] = $aTot;
                    //echo "<pre>";var_dump($array); exit;
                    echo json_encode($response);exit;

                }else{
                    $response['message'] = "No orders found";
                    $response['processing_orders'] = '';
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

    public function postPartnercashondelivery( Request $request){

        $response = array();

        $rules = array(
            'partner_id'	=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($partner_exists){
                $query = "SELECT `od`.*, `op`.order_details, `op`.order_status FROM abserve_order_details AS `od` INNER JOIN abserve_orders_partner AS `op` ON `od`.id = `op`.orderid WHERE `od`.id IS NOT NULL AND `od`.delivery_type = 'cash on delivery' AND `op`.partner_id = ".$_REQUEST['partner_id']."";
                $cashondelivery_count = \DB::select($query);
                if(!empty($cashondelivery_count) && $cashondelivery_count != ''){
                    foreach ($cashondelivery_count as $key => $value) {
                        $time = $value->time;
                        $order_time = date('H:i:s', $time);
                        $orders[] = array(
                            "id"			=> $value->id,
                            "cust_id"		=> $value->cust_id,
                            "res_id"		=> $value->res_id,
                            "total_price"	=> $value->total_price,
                            "s_tax"			=> $value->s_tax,
                            "coupon_price"	=> $value->coupon_price,
                            "grand_total"	=> $value->grand_total,
                            "address"		=> $value->address,
                            "building"		=> $value->building,
                            "landmark"		=> $value->landmark,
                            "status"		=> $value->status,
                            "coupon_id"		=> $value->coupon_id,
                            "time"			=> $order_time,
                            "date"			=> $value->date,
                            "delivery"		=> $value->delivery,
                            "delivery_type"	=> $value->delivery_type,
                            "lat"			=> $value->lat,
                            "lang"			=> $value->lang,
                            "order_details"	=> $value->order_details,
                            "order_status"	=> $value->order_status,
                        );
                    }

                    $response['message'] = "Success";
                    $response['cashondelivery'] = $orders;
                    /*echo "<pre>";
					print_r($response);exit;*/
                    echo json_encode($response);exit;
                }else{
                    $response['message'] = "Cash On Delivery Orders Doesn't exists";
                    $response['cashondelivery'] = '';
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

    public function postPartneronlinepayment( Request $request){

        $response = array();

        $rules = array(
            'partner_id'	=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
            if($partner_exists){
                $query = "SELECT `od`.*, `op`.order_details, `op`.order_status FROM abserve_order_details AS `od` INNER JOIN abserve_orders_partner AS `op` ON `od`.id = `op`.orderid WHERE `od`.id IS NOT NULL AND (`od`.delivery_type = '2checkout' OR `od`.delivery_type = 'ccavenue') AND `op`.partner_id = ".$_REQUEST['partner_id']."";
                $cashondelivery_count = \DB::select($query);
                if(!empty($cashondelivery_count) && $cashondelivery_count != ''){
                    foreach ($cashondelivery_count as $key => $value) {
                        $time = $value->time;
                        $order_time = date('H:i:s', $time);
                        $orders[] = array(
                            "id"			=> $value->id,
                            "cust_id"		=> $value->cust_id,
                            "res_id"		=> $value->res_id,
                            "total_price"	=> $value->total_price,
                            "s_tax"			=> $value->s_tax,
                            "coupon_price"	=> $value->coupon_price,
                            "grand_total"	=> $value->grand_total,
                            "address"		=> $value->address,
                            "building"		=> $value->building,
                            "landmark"		=> $value->landmark,
                            "status"		=> $value->status,
                            "coupon_id"		=> $value->coupon_id,
                            "time"			=> $order_time,
                            "date"			=> $value->date,
                            "delivery"		=> $value->delivery,
                            "delivery_type"	=> $value->delivery_type,
                            "lat"			=> $value->lat,
                            "lang"			=> $value->lang,
                            "order_details"	=> $value->order_details,
                            "order_status"	=> $value->order_status,
                        );
                    }

                    $response['message'] = "Success";
                    $response['onlinepayment'] = $orders;

                    echo json_encode($response);exit;
                }else{
                    $response['message'] = "Online Payment Orders Doesn't exists";
                    $response['onlinepayment'] = '';
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

    public function postMobilesendotp( Request $request){

        $response = array();

        $rules = array(
            'mobile_number'	=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $phonenumber = $_REQUEST['mobile_number'];

            $authUser = User::where('phone_number',$phonenumber)->first();
            if($authUser->phone_number == $_REQUEST['mobile_number']){

                if($authUser->active =='0')
                {
                    // inactive
                    $response["id"] 			= "1";
                    $response["message"] 		= "Your Account is not active";
                    echo json_encode($response);exit;
                }
                else if($authUser->active=='2')
                {
                    // BLocked users
                    $response["id"] 			= "2";
                    $response["message"] 		= "Your Account is BLocked";
                    echo json_encode($response); exit;
                } else {

                    $otp = rand(100000, 999999);

                    $user = \DB::table('tb_users')->where('id','=',$authUser->id)->update(['phone_otp'=>$otp]);

                    $this->sendSms($phonenumber, $otp, $authUser->id);
                    $response["user_id"] 		= (string)$authUser->id;
                    //$response["message"] 		= "Please check your otp for reset password.";
                    echo json_encode($response); exit;
                }

            } else {
                $response["message"] 	= "Mobile number does not exist";
                echo json_encode($response); exit;
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postMobilesendotp1( Request $request){

        $response = array();

        $rules = array(
            'mobile_number'	=>'required',
            'group_id'	=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $phonenumber = $_REQUEST['mobile_number'];
            $group_id = $_REQUEST['group_id'];

            $authUser = User::where('phone_number',$phonenumber)->where('group_id','=',$group_id)->first();
            //print_r($authUser);  exit;
            if($authUser->phone_number == $_REQUEST['mobile_number']){

                if($authUser->active =='0')
                {
                    // inactive
                    $response["id"] 			= "1";
                    $response["message"] 		= "Your Account is not active";
                    echo json_encode($response);exit;
                }
                else if($authUser->active=='2')
                {
                    // BLocked users
                    $response["id"] 			= "2";
                    $response["message"] 		= "Your Account is BLocked";
                    echo json_encode($response); exit;
                } else {

                    $otp = rand(100000, 999999);

                    $user = \DB::table('tb_users')->where('id','=',$authUser->id)->update(['phone_otp'=>$otp]);

                    $this->sendSms($phonenumber, $otp, $authUser->id);
                    $response["user_id"] 		= (string)$authUser->id;
                    //$response["message"] 		= "Please check your otp for reset password.";
                    echo json_encode($response); exit;
                }

            } else {
                $response["message"] 	= "Mobile number does not exist";
                echo json_encode($response); exit;
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postResendotp( Request $request){

        $response = array();

        $rules = array(
            'user_id'	=> 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $authUser = User::where('id',$_REQUEST['user_id'])->first();

            $this->sendSms($authUser->phone_number, $authUser->phone_otp, $authUser->id);
            $response["user_id"] = (string)$authUser->id;
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postOtpconfirm( Request $request){

        $response = array();

        $rules = array(
            'user_id'	=> 'required|numeric',
            'otp'		=> 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $authUser = User::where('id',$_REQUEST['user_id'])->first();
            if($authUser->phone_otp == $_REQUEST['otp']){

                if($authUser->active == 0){
                    $user = \DB::table('tb_users')->where('id','=',$authUser->id)->update(['active'=>1]);
                }

                $response['message'] = "Success";
                $response['user_id'] = (string)$authUser->id;
                echo json_encode($response); exit;
            }else{
                $response['message'] = "OTP does not match";
                echo json_encode($response); exit;
            }

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postChangepsw( Request $request){

        $response = array();

        $rules = array(
            'user_id'	=>'required|numeric',
            'password'=>'required|between:6,12'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $user_id = $_REQUEST['user_id'];

            $authUser = User::where('id',$user_id)->first();

            $authUser->password = \Hash::make($request->input('password'));

            $authUser->save();

            $response['message'] = "Password Changed Successfully";
            $response['user_id'] = (string)$authUser->id;
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function getDeliverycharges( Request $request){

        $delivery_charges = \DB::table('delivery_charges')->get();

        if(count($delivery_charges)>0){
            foreach($delivery_charges as $delivery_charge){
                $deliverycharges[] = array(
                    "id"		=> (string)$delivery_charge->id,
                    "start_km"	=> (string)$delivery_charge->start_km,
                    "end_km"	=> (string)$delivery_charge->end_km,
                    "price"		=> (string)$delivery_charge->price,
                );
            }
            $response["message"] 	= "Success";
            $response["delivery_charges"] = $deliverycharges;
        } else {
            $response["message"] 	= "Failure";
            $response["delivery_charges"] = array();
        }
        echo json_encode($response); exit;

    }

    public function postDeliverycharges1( Request $request){

        $response = array();

        $rules = array(
            'pin_code'	=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
            $region =  $area->region_id;

            $delivery_charge_type = \DB::table('delivery_charge_type')->where('region',$region)->first();

            if(!empty($delivery_charge_type)){
                if($delivery_charge_type->delivery_charge_type == 1){
                    $delivery_charges = \DB::table('delivery_charges')->where('region',$region)->get();
                } else {
                    $delivery_charges = \DB::table('raining_delivery_charges')->where('region',$region)->get();
                }

                if(count($delivery_charges)>0){
                    foreach($delivery_charges as $delivery_charge){
                        $deliverycharges[] = array(
                            "id"		=> (string)$delivery_charge->id,
                            "start_km"	=> (string)$delivery_charge->start_km,
                            "end_km"	=> (string)$delivery_charge->end_km,
                            "price"		=> (string)$delivery_charge->price,
                        );
                    }
                    $response["message"] 	= "Success";
                    $response["delivery_charges"] = $deliverycharges;
                } else {
                    $response["message"] 	= "Failure";
                    $response["delivery_charges"] = array();
                }
            } else {
                $response["message"] 	= "Failure";
                $response["delivery_charges"] = array();
            }
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postDboysendotp( Request $request){

        $response = array();

        $rules = array(
            'mobile_number'	=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $phonenumber = $_REQUEST['mobile_number'];

            $dboyUser = \DB::table('abserve_deliveryboys')->where('phone_number',$phonenumber)->first();
            if($dboyUser->phone_number == $_REQUEST['mobile_number']){

                if($dboyUser->active =='0')
                {
                    // inactive
                    $response["id"] 			= "1";
                    $response["message"] 		= "Your Account is not active";
                    //$response['boy_id'] 		= $dboyUser->id;
                    echo json_encode($response);exit;
                }
                else if($dboyUser->active=='2')
                {
                    // BLocked users
                    $response["id"] 			= "2";
                    $response["message"] 		= "Your Account is BLocked";
                    echo json_encode($response); exit;
                } else {
                    $otp = rand(100000, 999999);

                    $user = \DB::table('abserve_deliveryboys')->where('id','=',$dboyUser->id)->update(['phone_otp'=>$otp]);

                    $this->sendSms($phonenumber, $otp, $dboyUser->id);
                    $response['message'] 		= "Success";
                    $response["boy_id"] 		= $dboyUser->id;
                    //$response["message"] 		= "Please check your otp for reset password.";
                    echo json_encode($response); exit;
                }

            } else {
                $response["message"] 	= "Mobile number does not exist";
                echo json_encode($response); exit;
            }
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postDboyresendotp( Request $request){

        $response = array();

        $rules = array(
            'boy_id'	=> 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $dboyUser = \DB::table('abserve_deliveryboys')->where('id',$_REQUEST['boy_id'])->first();

            $this->sendSms($dboyUser->phone_number, $dboyUser->phone_otp, $dboyUser->id);

            $response['message'] 	= "Success";
            $response["boy_id"] = $dboyUser->id;
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postDboyotpconfirm( Request $request){

        $response = array();

        $rules = array(
            'boy_id'	=> 'required|numeric',
            'otp'		=> 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $dboyUser = \DB::table('abserve_deliveryboys')->where('id',$_REQUEST['boy_id'])->first();
            if($dboyUser->phone_otp == $_REQUEST['otp']){

                $response['message'] 	= "Success";
                $response['boy_id']	= $dboyUser->id;
                echo json_encode($response); exit;
            }else{
                $response['message'] = "OTP does not match";
                echo json_encode($response); exit;
            }

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postDboychangepsw( Request $request){

        $response = array();

        $rules = array(
            'boy_id'	=>'required|numeric',
            'password'=>'required|between:6,12'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $boy_id = $_REQUEST['boy_id'];

            $password = \Hash::make($request->input('password'));

            \DB::table('abserve_deliveryboys')->where('id',$boy_id)->update(array('password'=>$password));

            $response['message'] = "Password Changed Successfully";
            $response['boy_id'] = $boy_id;
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postUploadagreementpdf(Request $request) {

        $response = array();

        $rules = array(
            'boy_id'	=>'required|numeric',
            'pdf'		=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $boy_id = $_REQUEST['boy_id'];
            $file = Input::file('pdf');
            //$name = $file->getClientOriginalName();
            $path = "/uploads/delivery_boy/";
            $extension = Input::file('pdf')->getClientOriginalExtension();

            $filename = rand(11111111, 99999999). '.' . $extension;

            $request->file('pdf')->move(
                base_path() . $path, $filename
            );

            if($filename !="")
            {
                \DB::table('abserve_deliveryboys')->where('id',$boy_id)->update(array('agreement'=>$filename,'agreement_status'=>1));
                $response['message'] = "Uploaded Successfully";
                $response['boy_id'] = $boy_id;
                echo json_encode($response); exit;
            } else {
                $response['message'] = "Failure";
                $response['boy_id'] = $boy_id;
                echo json_encode($response); exit;
            }

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }

    }

    public function postRestaurantresponse( Request $request){

        $response = array();

        $rules = array(
            'order_id'		=>'required',
            'partner_id'  	=>'required',
            'order_status'  =>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            if($_REQUEST['order_status'] == 1){

                $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
                if($partner_exists){
                    $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
                    if($order_exists){

                        $acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
                        if($acess){
                            $order_data = \DB::table('abserve_order_details')->select('cust_id','res_id')->where('id','=',$_REQUEST['order_id'])->get();
                            $boy_assign_id = $this->getPassignorder($_REQUEST['partner_id'],$_REQUEST['order_id'],$order_data[0]->cust_id,$order_data[0]->res_id);

                            if(!empty($boy_assign_id['boy_id'])){
                                $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                                $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                                $deliveryboys = \DB::table('abserve_deliveryboys')->where('id',$boy_assign_id['boy_id'])->where('boy_status',1)->get();
                                if(count($deliveryboys)>0){
                                    \DB::table('abserve_deliveryboys')->where('id','=',$boy_assign_id['boy_id'])->update(['boy_status'=>2]);
                                } else {
                                    \DB::table('abserve_deliveryboys')->where('id','=',$boy_assign_id['boy_id'])->update(['boy_status'=>1]);
                                }

                                //order assign id
                                $oassignexists = \DB::table('abserve_order_assign')->where('order_id',$_REQUEST['order_id'])->first();
                                if($oassignexists === null){
                                    \DB::table('abserve_order_assign')->insert(['assign_id'=>$boy_assign_id['inserted_id'],'order_id'=>$_REQUEST['order_id']]);
                                }

                                $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                                // Customer notification
                                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                                $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                                $message = $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon:0:".$_REQUEST['order_id'];

                                if($note_id[0]->device == 'ios'){
                                    $message = $order_datas->name." has started preparing your order.Our delivery executive will pick it up soon";
                                    $message1 = "1:0:".$_REQUEST['order_id'];

                                    $appapi_details	= $this->appapimethod(4);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                                } else {
                                    $appapi_details	= $this->appapimethod(1);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                                }

                                //Restaurant Notification
                                $appapi_details	= $this->appapimethod(2);
                                //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                                $message 		= $order_datas->name." order accepted by restaurant and assign to delivery boy";
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;

                                //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                                $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                                foreach($device_tokens as $device_token){
                                    $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
                                }

                                //DeliveryBoy Notification
                                $appapi_details	= $this->appapimethod(3);
                                $mobile_token 	= $this->userapimethod($boy_assign_id['boy_id'],'abserve_deliveryboys');
                                $message 		= "Your have new Order";
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;

                                //$note_id = \DB::table('abserve_deliveryboys')->select('device')->where('id',$boy_assign_id['boy_id'])->get();
                                //if($note_id[0]->device == 'ios'){
                                //$this->iospushnotification($mobile_token,$message,'3');
                                //}else{
                                $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                                //}

                                $boy_data = \DB::table('abserve_deliveryboys')->select('username','phone_number')->where('id',$boy_assign_id['boy_id'])->first();

                                $response['id'] 	 = '1';
                                $response['message'] = "Order accepted and assigned to ".$boy_data->username;
                                $response['order_status'] = "1";
                                $response['status'] = true;

                            }else{
                                //$response['message'] = "Please try again.";
                                $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);
                                $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>1]);

                                $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                                // Customer notification
                                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                                $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                                $message = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon:0:".$_REQUEST['order_id'];

                                if($note_id[0]->device == 'ios'){
                                    $message = $order_datas->name." has started preparing your order. Our delivery executive will pick it up soon";
                                    $message1 = "1:0:".$_REQUEST['order_id'];

                                    $appapi_details	= $this->appapimethod(4);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                                } else {
                                    $appapi_details	= $this->appapimethod(1);
                                    $app_name		= $appapi_details->app_name;
                                    $app_api 		= $appapi_details->api;
                                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                                }

                                //Restaurant Notification
                                $appapi_details	= $this->appapimethod(2);
                                //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                                $message 		= $order_datas->name." order accepted by restaurant";
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;

                                //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                                $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                                foreach($device_tokens as $device_token){
                                    $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
                                }

                                $response['id'] 	 = '1';
                                $response['message'] = "Order accepted by restaurant but delivery boy not assigned";
                                $response['order_status'] = "1";
                                $response['status'] = true;
                            }
                        }else{
                            $response["status"] 	= false;
                            //$response['message'] 	= "It's Not your Order";
                        }
                    }else{
                        $response["status"] 	= false;
                        //$response['message'] 	= "No Such Order found";
                    }
                }else{
                    $response["status"] 	= false;
                    //$response['message'] 	= "UserID Doesn't exists";
                }

            } elseif($_REQUEST['order_status'] == 2){

                $partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
                if($partner_exists){
                    $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
                    if($order_exists){
                        $acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
                        if($acess){
                            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>5]);
                            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>5]);
                            $abc1 = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(['status'=>5]);
                            if($abp && $abc){
                                $response["status"] 		= true;
                                $response['order_status'] 	= "5";
                                $response['message'] 		= "Order Rejected by Restaurant";
                                //$response['id'] 			= "5";
                                $response['status'] 		= true;

                                //Get Order data
                                $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                                // Customer notification
                                /*$appapi_details	= $this->appapimethod(1);
									$mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
									$message 		= $order_datas->name." rejected  your order ";
									$app_name		= $appapi_details->app_name;
									$app_api 		= $appapi_details->api;

									$note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();
									if($note_id[0]->device == 'ios'){
									//$this->iospushnotification($mobile_token,$message,'1');
									}else{
									$this->pushnotification($app_api,$mobile_token,$message,$app_name);
									}*/

                                //Restaurant Notification
                                $appapi_details	= $this->appapimethod(2);
                                //$mobile_token 	= $this->userapimethod($_REQUEST['partner_id'],'tb_users');
                                $message 		= $order_datas->name." order rejected by restaurant";
                                $app_name		= $appapi_details->app_name;
                                $app_api 		= $appapi_details->api;

                                //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                                $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$_REQUEST['partner_id'])->get();
                                foreach($device_tokens as $device_token){
                                    $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
                                }

                            }else{
                                $response["status"] 	= false;
                                //$response['message'] 	= "Order Doesn't Rejected";
                            }
                        }else{
                            $response["status"] 	= false;
                            //$response['message'] 	= "It's Not your Order";
                        }
                    }else{
                        $response["status"] 	= false;
                        //$response['message'] 	= "No Such Order found";
                    }
                }else{
                    $response["status"] 	= false;
                    //$response['message'] 	= "UserID Doesn't exists";
                }

            }

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
        echo json_encode($response); exit;
    }

    public function postCcavresponsehandler( Request $request){
        //echo "test"; exit;
        include(app_path() . '/Functions/Crypto.php');

        $workingKey=CCAVENUE_WORKINGCODE;		//Working Key should be provided here.
        $encResponse=$_REQUEST["encResp"];			//This is the response sent by the CCAvenue Server
        $rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
        $order_status="";
        $order_id="";
        $decryptValues=explode('&', $rcvdString);
        $dataSize=sizeof($decryptValues);
        echo "<center>";

        for($i = 0; $i < $dataSize; $i++)
        {
            $information=explode('=',$decryptValues[$i]);

            if($information[0] == 'tracking_id')
                $tracking_id=$information[1];

            if($information[0] == 'order_id')
                $order_id=$information[1];

            if($information[0] == 'order_status')
                $order_status=$information[1];

            if($information[0] == 'payment_mode')
                $mop=$information[1];
        }

        if($order_status==="Success")
        {
            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$order_id)->update(['order_status'=>0]);
            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$order_id)->update(['order_status'=>0]);
            $abc1 = \DB::table('abserve_order_details')->where('id','=',$order_id)->update(['status'=>0,'delivery'=>'paid','reference_no'=>$tracking_id,'mop'=>$mop]);

            //Get Order data
            $order_datas = $this->Order_data($order_id,'');

            //Restaurant Notification
            //$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
            $message 		= "#".$order_id." New orders found in your restaurant";;

            $appapi_details	= $this->appapimethod(2);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;
            //$this->pushnotification($app_api,$mobile_token,$message,$app_name);
            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$order_datas->partner_id)->get();
            foreach($device_tokens as $device_token){
                //Note: This line code is no needed
                //$this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
            }

            $customer = \DB::table('tb_users')->where('id',$order_datas->cust_id)->first();

            $message1 = "#".$order_id." order placed successfully";
            $customerAppApiDetail	= $this->appapimethod(1);
            $this->pushnotification($customerAppApiDetail->api, $customer->mobile_token, $message1, $customerAppApiDetail->app_name);
            //$this->sendSms1($customer->phone_number, $message1, $order_datas->cust_id);

            $resuser = \DB::table('abserve_restaurants')->where('id',$order_datas->res_id)->first();
            $phonenumber = $resuser->phone;
            $message = "New orders found in your restaurant";
            #$this->sendSms2($phonenumber, $message, $order_datas->res_id);

            //Restaurant Notification
            $appapi_details	= $this->appapimethod(2);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;
            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$resuser->partner_id)->get();
            foreach($device_tokens as $device_token){
                #$this->pushnotification($app_api,$device_token->device_token,$message,$app_name, $message);
                $this->pushNotificationRestaurantOrder($device_token->device_token);
            }

            if($order_datas->coupon_id !='' && $order_datas->coupon_id !=0){
                $coupon = \DB::table('coupon')->where('id','=',$order_datas->coupon_id)->first();
                $coupon_values = array("user_id"=>$order_datas->cust_id,"coupon_id"=>$coupon->id,"coupon_code"=>$coupon->coupon_code);
                \DB::table('coupon_check')->insert($coupon_values);
            }

            //echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Success</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/success.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }
        else if($order_status==="Aborted")
        {
            /*$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
			if($order_exists){
				$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
				if($acess){*/
            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$order_id)->update(['order_status'=>8]);
            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$order_id)->update(['order_status'=>8]);
            $abc1 = \DB::table('abserve_order_details')->where('id','=',$order_id)->update(['status'=>8,'reference_no'=>$tracking_id,'mop'=>$mop]);
            if($abp && $abc){
                /*$response['order_status'] 	= "5";
                $response['message'] 		= "Order Rejected";
                $response['id'] 			= "5";*/

                //Get Order data
                $order_datas = $this->Order_data($order_id,'');

                //Customer notification
                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                $mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
                $message 		= "#".$order_id." rejected  your order ";

                if($note_id[0]->device == 'ios'){
                    //$message 		= "Our delivery executive ".$boy_info->username." pick your order from ".$order_datas->name." and within few minutes the order will delivered to you";
                    $message1 = "5:0:".$order_id;

                    $appapi_details	= $this->appapimethod(4);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                } else {
                    $appapi_details	= $this->appapimethod(1);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                }

            }/*else{
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
			}*/
            //echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Aborted</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }
        else if($order_status==="Failure")
        {
            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$order_id)->update(['order_status'=>9]);
            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$order_id)->update(['order_status'=>9]);
            $abc1 = \DB::table('abserve_order_details')->where('id','=',$order_id)->update(['status'=>9,'reference_no'=>$tracking_id,'mop'=>$mop]);
            if($abp && $abc){
                //Get Order data
                $order_datas = $this->Order_data($order_id,'');

                //Customer notification
                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                $mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
                $message 		= "#".$order_id." Payment Failed";

                if($note_id[0]->device == 'ios'){
                    //$message 		= "Our delivery executive ".$boy_info->username." pick your order from ".$order_datas->name." and within few minutes the order will delivered to you";
                    $message1 = "5:0:".$order_id;

                    $appapi_details	= $this->appapimethod(4);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                } else {
                    $appapi_details	= $this->appapimethod(1);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                }

            }

            //echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Failure</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';
        }
        else
        {
            $abp = \DB::table('abserve_orders_partner')->where('orderid','=',$order_id)->update(['order_status'=>9]);
            $abc = \DB::table('abserve_orders_customer')->where('orderid','=',$order_id)->update(['order_status'=>9]);
            $abc1 = \DB::table('abserve_order_details')->where('id','=',$order_id)->update(['status'=>9,'reference_no'=>$tracking_id,'mop'=>$mop]);
            if($abp && $abc){
                //Get Order data
                $order_datas = $this->Order_data($order_id,'');

                //Customer notification
                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                $mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
                $message 		= "#".$order_id." Order Failed";

                if($note_id[0]->device == 'ios'){
                    //$message 		= "Our delivery executive ".$boy_info->username." pick your order from ".$order_datas->name." and within few minutes the order will delivered to you";
                    $message1 = "5:0:".$order_id;

                    $appapi_details	= $this->appapimethod(4);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                } else {
                    $appapi_details	= $this->appapimethod(1);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                }

            }

            //echo "<br>Security Error. Illegal access detected";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Aborted</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }

        /*echo "<br><br>";

		echo "<table cellspacing=4 cellpadding=4>";
		for($i = 0; $i < $dataSize; $i++)
		{
			$information=explode('=',$decryptValues[$i]);
				echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
		}

		echo "</table><br>";
		echo "</center>";*/
    }

    public function postCancelorder( Request $request){


        $response = array();

        $rules = array(
            'order_id' => 'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $order = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->first();

            $current_date_time = strtotime(date("Y-m-d H:i:s"));
            $order_grace_time = $order->time+(60*1);
            if($order_grace_time >= $current_date_time){

                $user = \DB::table('tb_users')->where('id',$order->cust_id)->first();
                $phonenumber = $user->phone_number;
                $message = "Your order #".$order->id." has been cancelled";

                if($order->delivery_type == "cod"){

                    \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(["status"=>'10']);
                    \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);
                    \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);

                    $this->sendSms1($phonenumber, $message, $order->cust_id);

                    $res_id  = $order->res_id;
                    $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                    $phonenumber = $resuser->phone;
                    $message = "The order #".$order->id." has been cancelled";

                    $this->sendSms2($phonenumber, $message, $order->res_id);

                    $response["status"] 	= "true";
                    $response["message"] 	= "Order Cancelled";
                    $response["order_id"] 	= $_REQUEST['order_id'];
                    //echo json_encode($response);    exit;

                } else {

                    include(app_path() . '/Functions/Crypto.php');

                    error_reporting(0);
                    // Provide working key share by CCAvenues
                    $working_key = 'A3B3AFEB86009252B14B60ACA1319CE0';
                    // Provide access code Shared by CCAVENUES
                    $access_code = 'AVIJ79FG38BY48JIYB';
                    // Provide URL shared by ccavenue (UAT OR Production url)
                    $URL="https://api.ccavenue.com/apis/servlet/DoWebTrans";

                    // Sample request string for the API call
                    $data[] = array(
                        "reference_no" => $order->reference_no,
                        "amount" => $order->grand_total
                    );
                    $merchant_json_data = array(
                        "order_List" => $data
                    );

                    // Generate json data after call below method
                    $merchant_data = json_encode($merchant_json_data);
                    // Encrypt merchant data with working key shared by ccavenue
                    $encrypted_data = encrypt($merchant_data, $working_key);
                    //make final request string for the API call
                    $final_data ="request_type=JSON&access_code=".$access_code."&command=cancelOrder&response_type=JSON&enc_request=".$encrypted_data;

                    // Initiate api call on shared url by CCAvenues
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$URL);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$final_data);

                    // Get server response ... curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec ($ch);

                    curl_close ($ch);

                    $information=explode('&',$result);
                    $dataSize=sizeof($information);
                    $status1=explode('=',$information[0]);
                    $status2=explode('=',$information[1]);
                    if($status1[1] == '1'){
                        $status=$status2[1];
                    }else{
                        $status=decrypt($status2[1],$working_key);
                    }
                    \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->update(["status"=>'10']);
                    \DB::table('abserve_orders_customer')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);
                    \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->update(["order_status"=>'10']);

                    $this->sendSms1($phonenumber, $message, $order->cust_id);

                    $response["status"] 	= "true";
                    $response["message"] 	= "Order Cancelled and Refund Initiated";
                    $response["order_id"] 	= $_REQUEST['order_id'];

                    $res_id  = $order->res_id;
                    $resuser = \DB::table('abserve_restaurants')->where('id',$res_id)->first();
                    $phonenumber = $resuser->phone;
                    $message = "The order #".$order->id." has been cancelled";

                    $this->sendSms2($phonenumber, $message, $order->res_id);

                    $response["status"] 	= "true";
                    $response["message"] 	= "Order Cancelled";
                    $response["order_id"] 	= $_REQUEST['order_id'];
                    //echo json_encode($response);    exit;

                }

                $boyid = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->first();
                $dboy = \DB::table('abserve_deliveryboys')->where('id','=',$boyid->bid)->first();
                if($dboy->boy_status == '2'){
                    \DB::table('abserve_deliveryboys')->where('id','=',$boyid->bid)->where('boy_status','=','2')->update(["boy_status"=>'1']);
                } else {
                    if($dboy->boy_status == '1'){
                        \DB::table('abserve_deliveryboys')->where('id','=',$boyid->bid)->where('boy_status','=','1')->update(["boy_status"=>'0']);
                    }
                }

                //Get Order data
                $order_datas = $this->Order_data($_REQUEST['order_id'],'');

                //Customer notification
                $note_id = \DB::table('tb_users')->select('device')->where('id',$order_datas->cust_id)->get();

                $mobile_token = $this->userapimethod($order_datas->cust_id,'tb_users');
                $message = "Order Cancelled:0:".$_REQUEST['order_id'];

                if($note_id[0]->device == 'ios'){
                    $message = $order_datas->name." Order Cancelled ";
                    $message1 = "10:0:".$_REQUEST['order_id'];

                    $appapi_details	= $this->appapimethod(4);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->iospushnotification($app_api,$mobile_token,$message,$message1,$app_name);
                } else {
                    $appapi_details	= $this->appapimethod(1);
                    $app_name		= $appapi_details->app_name;
                    $app_api 		= $appapi_details->api;
                    $this->pushnotification($app_api,$mobile_token,$message,$app_name);
                }

                //Restaurant Notification
                $appapi_details	= $this->appapimethod(2);
                //$mobile_token 	= $this->userapimethod($order_datas->partner_id,'tb_users');
                $message 		= $order_datas->name." Order Cancelled";
                $app_name		= $appapi_details->app_name;
                $app_api 		= $appapi_details->api;

                //$this->pushnotification1($app_api,$mobile_token,$message,$app_name);
                $partner = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->first();
                $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$partner->partner_id)->get();
                foreach($device_tokens as $device_token){
                    $this->pushnotification1($app_api,$device_token->device_token,$message,$app_name);
                }

                //DeliveryBoy Notification (Cancel Order)
                $boyorderstatus = \DB::table('abserve_boyorderstatus')->where('oid','=',$_REQUEST['order_id'])->get();
                if(count($boyorderstatus)>0){
                    $appapi_details	= $this->appapimethod(3);
                    $mobile_token 	= $this->userapimethod($boyorderstatus[0]->bid,'abserve_deliveryboys');
                    $message 			= "Order Cancelled";
                    $app_name			= $appapi_details->app_name;
                    $app_api 			= $appapi_details->api;

                    $this->pushnotification2($app_api,$mobile_token,$message,$app_name);

                    \DB::table('abserve_orders_boy')->where('orderid','=',$_REQUEST['order_id'])->update(['order_status'=>'10']);
                }

                echo json_encode($response); exit;

            } else {
                $response["status"] = "false";
                $response["message"] = "Contact to admin for cancel order";
                echo json_encode($response); exit;
            }

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }

    }

    public function getOrderlist( Request $request){
        //echo "test"; exit;
        $currentDateTime = date("Y-m-d h:i:s A");
        $datetimestring = strtotime($currentDateTime);
        $before_one_min = $datetimestring-(60*1);

        $orders = \DB::table('abserve_order_details')->select('id','res_id','time')->where('time', '<=', $before_one_min)->where('status','=','0')->where('flag','=','0')->get();


        foreach($orders as $order){
            $restaurant = \DB::table('abserve_restaurants')->select('*')->where('id', '=', $order->res_id)->first();
            $partner = \DB::table('tb_users')->select('*')->where('id', '=', $restaurant->partner_id)->first();

            // The data to send to the API
            $postData = array(
                'phone_number' 	=> $restaurant->phone,
                'alt_number_1' 	=> $restaurant->secondary_phone_number,
                'alt_number_2' 	=> $restaurant->secondary_phone_number2,
                'order_id' 		=> $order->id,
                'partner_id' 	=> $partner->id
            );

            // Setup cURL
            $ch = curl_init('http://103.207.0.124/aster-dialer/services/manualOriginatedialer.php');
            curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if($response === FALSE){
                die(curl_error($ch));
            }

            // Decode the response
            $responseData = json_decode($response, TRUE);

            // Print the date from the response
            //echo $responseData['published'];
            //print_r($responseData); exit;
            if($responseData['status'] == "Ok"){
                //echo $responseData['status'];
                $flag = \DB::table('abserve_order_details')->where('id','=',$order->id)->update(array('flag' => '1'));
            }
        }

    }

    public function getDboynotacceptedlist( Request $request){
        //echo "test"; exit;
        $currentDateTime = date("Y-m-d H:i:s");
        $datetimestring = strtotime($currentDateTime);
        $before_one_min = $datetimestring-(60*2);

        $orders = \DB::table('abserve_boyorderstatus')->where('delivery_assign', '<=', date("Y-m-d H:i:s",$before_one_min))->where('status','=',0)->where('flag','=',0)->get();

        //print_r($orders); exit;
        if(count($orders)>0){
            foreach($orders as $order){
                $dboy = \DB::table('abserve_deliveryboys')->select('*')->where('id', '=', $order->bid)->first();
                $agent_number = "04166650211"; //Agent Mumber
                $dboy_number = $dboy->phone_number; //Delivery Boy Number

                // The data to send to the API
                $postData = array(
                    'user' 		=> $agent_number,
                    'customer' 	=> $dboy_number
                );

                // Setup cURL
                $ch = curl_init('http://103.207.0.124/aster-dialer/services/manualOriginateapi.php');
                curl_setopt_array($ch, array(
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                    CURLOPT_POSTFIELDS => json_encode($postData)
                ));

                // Send the request
                $response = curl_exec($ch);

                // Check for errors
                if($response === FALSE){
                    die(curl_error($ch));
                }

                // Decode the response
                $responseData = json_decode($response, TRUE);

                // Print the date from the response
                //echo $responseData['published'];
                //print_r($responseData); exit;
                if($responseData['status'] == "Ok"){
                    //echo $responseData['status'];
                    $flag = \DB::table('abserve_boyorderstatus')->where('id','=',$order->id)->update(array('flag' => 1));
                }
            }
        } else {
            $response["message"] 	= "No records found";
            echo json_encode($response); exit;
        }

    }

    public function postIospushnotifications( Request $request){
        $order_id = $_REQUEST['order_id'];
        $order_datas = $this->Order_data($order_id,'');

        //Customer Notification
        $mobile_token 	= $this->userapimethod($order_datas->cust_id,'tb_users');
        $message = "#".$order_id." order placed successfully";

        $appapi_details	= $this->appapimethod(4);
        $app_name		= $appapi_details->app_name;
        $app_api 		= $appapi_details->api;
        echo $this->pushnotification($app_api,$mobile_token,$message,$app_name);
    }

    public function postCouponcode( Request $request){

        $response = array();

        $rules = array(
            'user_id' => 'required',
            'res_id'  => 'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $query=\DB::table('tb_users')->where('id','=', $request->user_id)->get();
            // $rest_region = $query[0];
            $region_id = $query[0]->region;
            //print $region_id;
            $current_date = date("Y-m-d");
            $coupon_check= \DB::table('coupon_check')->select('coupon_id')->where('user_id','=',$_REQUEST['user_id'])->where('res_id', '=', 0)->orWhere('res_id', '=', $_REQUEST['res_id'])->groupBy('user_id')->groupBy('res_id')->groupBy('coupon_id')->get();
            if(count($coupon_check)>0){
                foreach($coupon_check as $couponcheck){
                    $coupon_id[] = $couponcheck->coupon_id;
                }

                $coupons = \DB::table('coupon')->get();

                foreach($coupons as $coupon){
                    if(in_array($coupon->id,$coupon_id)){
                        if($coupon->coupon_use_type !=1){
                            $coupon_ids[] = $coupon->id;
                        }
                    } else {
                        $coupon_ids[] = $coupon->id;
                    }
                }

                //$query = "SELECT * FROM `coupon` WHERE `id` IN(".implode(",",$coupon_ids).")";
                $query = "SELECT * FROM `coupon` WHERE `id` IN(".implode(",",$coupon_ids).") AND (`res_id`=0 OR `res_id`=".$_REQUEST['res_id'].") AND (`offer_from` <='".$current_date."' AND `offer_to` >='".$current_date."') AND `region`=".$region_id;
                $coupon = \DB::select($query);
                //print_r($coupon); exit;
            } else {
                //$coupon = \DB::table('coupon')->where('res_id', '=', 0)->orWhere('res_id', '=', $_REQUEST['res_id'])->whereDate('offer_from', '<=', $current_date)->whereDate('offer_to', '>=', $current_date)->get();
                $query = "select * from `coupon` where (`res_id` = 0 OR `res_id` = ".$_REQUEST['res_id'].") AND date(`offer_from`) <= '".$current_date."' AND date(`offer_to`) >= '".$current_date."' AND `region`=".$region_id;
                $coupon = \DB::select($query);
            }
            //print_r($coupon); exit;
            if(count($coupon)>0){
                foreach($coupon as $_coupon){
                    if($_coupon->res_id == 0){
                        $coupon_type = "2";//DS Coupon
                    } else {
                        $coupon_type = "1";//Restaurant Coupon
                    }
                    $_coupons[] = array(
                        "id"				=> (string)$_coupon->id,
                        "res_id"			=> (string)$_coupon->res_id,
                        "coupon_type"		=> $coupon_type,
                        "coupon_name"		=> $_coupon->coupon_name,
                        "coupon_code"		=> $_coupon->coupon_code,
                        "coupon_desc"		=> $_coupon->coupon_desc,
                        "coupon_use_type"	=> (string)$_coupon->coupon_use_type,
                        "offer_type"		=> (string)$_coupon->offer_type,
                        "offer"				=> (string)$_coupon->offer,
                        "min_order_value"	=> (string)$_coupon->min_order_value,
                        "max_value"			=> (string)$_coupon->max_value,
                        "offer_from"		=> $_coupon->offer_from,
                        "offer_to"			=> $_coupon->offer_to,
                        "region"			=> $_coupon->region,
                    );
                }

                $response["status"] 	= true;
                $response["coupons"] 	= $_coupons;
                echo json_encode($response); exit;
            }else{
                $response["status"] 	= false;
                echo json_encode($response); exit;
            }

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function getFaq( Request $request){

        $response = array();
        $alias = 'faq';

        $pages = \DB::table('tb_pages')->where('alias','=',$alias)->first();

        $filename = base_path() ."/resources/views/pages/".$pages->filename.".blade.php";

        if(file_exists($filename))
        {
            $faq = file_get_contents($filename);
        } else {
            $faq = '';
        }

        echo $faq; exit;

    }

    public function getPrivacypolicy( Request $request){

        $response = array();
        $alias = 'privacy-policy';

        $pages = \DB::table('tb_pages')->where('alias','=',$alias)->first();

        $filename = base_path() ."/resources/views/pages/".$pages->filename.".blade.php";

        if(file_exists($filename))
        {
            $faq = file_get_contents($filename);
        } else {
            $faq = '';
        }

        echo $faq; exit;

    }

    public function getTermsandconditions( Request $request){

        $response = array();
        $alias = 'terms-conditions';

        $pages = \DB::table('tb_pages')->where('alias','=',$alias)->first();

        $filename = base_path() ."/resources/views/pages/".$pages->filename.".blade.php";

        if(file_exists($filename))
        {
            $faq = file_get_contents($filename);
        } else {
            $faq = '';
        }

        echo $faq; exit;

    }

    public function getTcservices( Request $request){

        $response = array();
        if($request->type == "Service"){
            $alias = 'tc-service';
        } elseif($request->type == "Delivery"){
            $alias = 'tc-delivery';
        } elseif($request->type == "Relocation"){
            $alias = 'tc-relocation';
        }

        $pages = \DB::table('tb_pages')->where('alias','=',$alias)->first();

        $filename = base_path() ."/resources/views/pages/".$pages->filename.".blade.php";

        if(file_exists($filename))
        {
            $faq = file_get_contents($filename);
        } else {
            $faq = '';
        }

        echo $faq; exit;

    }

    public function postIosnotificationflag( Request $request){

        $response = array();

        $rules = array(
            'user_id' 		=> 'required',
            'mobile_token'  => 'required',
            'ios_flag'  	=> 'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {
            $user_id = (int)$_REQUEST['user_id'];
            $ios_flag = (int)$_REQUEST['ios_flag'];
            $coupon_check= \DB::table('tb_users')->where('id','=',$user_id)->update(array('ios_flag' => $ios_flag));
            if($coupon_check){
                $response["status"] 	= true;
                $response["user_id"] 	= $_REQUEST['user_id'];
                $response["mobile_token"] 	= $_REQUEST['mobile_token'];
                $response["ios_flag"] 	= $_REQUEST['ios_flag'];
            } else {
                $response["status"] 	= false;
                $response["user_id"] 	= $_REQUEST['user_id'];
                $response["mobile_token"] 	= $_REQUEST['mobile_token'];
                $response["ios_flag"] 	= $_REQUEST['ios_flag'];
            }
            echo json_encode($response); exit;

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    //IVR Support Services Start
    public function postOrderidvalidation( Request $request){

        $response = array();

        $rules = array(
            'order_id'		=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
            if($order_exists){

                $query = "select * from `abserve_order_details` where `date` >= now()-interval 3 month AND `id`=".$_REQUEST['order_id'];
                $order = \DB::select($query);

                if(count($order)>0){
                    $response["status"] 	= true;
                    $response["message"] 	= "Customer support team is available";
                    $response["order_id"] 	= $_REQUEST['order_id'];
                    echo json_encode($response); exit;
                }else{
                    $response["status"] 	= false;
                    $response["message"] 	= "The Order ID is not match with last 3 month records";
                    echo json_encode($response); exit;
                }

            }else{
                $response["status"] 	= false;
                $response["message"] 	= "Order ID doesn't exist";
                echo json_encode($response); exit;
            }

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postSpamcall( Request $request){

        $response = array();

        $rules = array(
            'phone_number'		=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $query = "select * from `spam` where `phone_number`=".$_REQUEST['phone_number'];
            $spam = \DB::select($query);

            if(count($spam)>0){
                $response["status"] 	= true;
                echo json_encode($response); exit;
            }else{
                $response["status"] 	= false;
                echo json_encode($response); exit;
            }

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postOrderdetails( Request $request){

        $response = array();

        $rules = array(
            'order_id'		=>'required'
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
            if($order_exists){

                $query = "SELECT `od`.`id`,`od`.`cust_id`,`od`.`grand_total`,`od`.`status`,`od`.`time`,`od`.`delivery_type`, `res`.`name` as `res_name`, `oc`.`order_details`, `boy`.`bid` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_restaurants` as `res` ON `od`.`res_id` = `res`.`id` LEFT JOIN `abserve_orders_customer` as `oc` ON `od`.`id` = `oc`.`orderid` LEFT JOIN `abserve_boyorderstatus` as `boy` ON `od`.`id` = `boy`.`oid` WHERE `od`.`id` = ".$_REQUEST['order_id']." ORDER BY `od`.`id` DESC";

                $order_detail = \DB::select($query);

                $response["status"] 	= true;
                foreach($order_detail as $order){
                    $time = time();
                    $date = date("Y-m-d h:m:s A", $order->time);
                    $orderdetails[] = array(
                        'order_id'			=> $order->id,
                        'cust_id'			=> $order->cust_id,
                        'res_name'			=> $order->res_name,
                        'order_details'		=> $order->order_details,
                        'order_amount'		=> $order->grand_total,
                        'order_status'		=> $order->status,
                        'order_date_time'	=> $date,
                        'mop'				=> $order->delivery_type,
                        'delivery_boy_id'	=> $order->bid,
                    );
                }
                $response["order_details"] 	= $orderdetails;
                echo json_encode($response); exit;


            }else{
                $response["status"] 	= false;
                $response["message"] 	= "Order ID doesn't exist";
                echo json_encode($response); exit;
            }

        }else {
            $response["status"] 	= false;
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }
    //IVR Support Services End


    public function postBuyget( Request $request){

        //$_REQUEST = (array) json_decode(file_get_contents("php://input"));
        //print_r($_REQUEST);  exit;
        $_REQUEST = str_replace('"','', $_REQUEST);

        $query=\DB::table('abserve_hotel_items')->where('id','=', $_REQUEST['food_item_get_id'])->get();
        $food_get_name = $query[0];

        // $value['restaurant_id'] = $_REQUEST['restaurant_id'];
        $value['bogo_item_id'] = $_REQUEST['food_item_get_id'];
        $date_time_start = strtotime($_REQUEST['start_date']);
        $date_time_end = strtotime($_REQUEST['end_date']);

        $update =	\DB::table('abserve_hotel_items')->where('id', $_REQUEST['food_item_buy_id'] )->update(array('bogo_item_id' => $_REQUEST['food_item_get_id'],'buy_qty' => $_REQUEST['buy_qty'],'get_qty' => $_REQUEST['get_qty'],'bogo_name' => $food_get_name->food_item,'bogo_start_date' => date("Y-m-d H:i:s",$date_time_start),'bogo_end_date' => date("Y-m-d H:i:s",$date_time_end)));

        $response['message'] = "Updated successfully";
        echo json_encode($response); exit;

    }

    public function postBuygetdelete( Request $request){

        //$_REQUEST = (array) json_decode(file_get_contents("php://input"));
        //print_r($_REQUEST);  exit;
        $_REQUEST = str_replace('"','', $_REQUEST);

        $buy_qty = "0";
        $get_qty = "0";
        $bogo_item_id = "0";
        $bogo_name = "0";
        $bogo_start_date = "0000-00-00 00:00:00";
        $bogo_end_date = "00000-00-00 00:00:00";

        $update =	\DB::table('abserve_hotel_items')->where('id', $_REQUEST['food_item_buy_id'] )->update(array('bogo_item_id' => $bogo_item_id,'buy_qty' => $buy_qty,'get_qty' => $get_qty,'bogo_name' => $bogo_name,'bogo_start_date' => $bogo_start_date,'bogo_end_date' => $bogo_start_date));

        $response['message'] = "Deleted successfully";
        echo json_encode($response); exit;

    }

    public function postBuygetdetails( Request $request){

        $response = array();
        $rules = array(
            'restaurant_id'	=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $buyget = \DB::table('abserve_hotel_items')->where('restaurant_id','=',$_REQUEST['restaurant_id'])->where('bogo_item_id','!=','0')->get();
            //print_r($buyget);  exit;
            $res_name = \DB::table('abserve_restaurants')->select('*')->where('id','=',$_REQUEST['restaurant_id'])->first();

            if($buyget){

                foreach ($buyget as $key => $value) {

                    $name = \DB::table('abserve_hotel_items')->select('*')->where('id','=',$value->bogo_item_id)->get();
                    $name_get = $name[0];
                    $buy = $name_get->food_item;

                    $from_date = strtotime($value->bogo_start_date);
                    $to_date = strtotime($value->bogo_end_date);

                    $buygetoffers[] = array(
                        "food_item_buy"	=> $value->food_item,
                        "food_item_buy_id"	=> $value->id,
                        "buy_qty" => $value->buy_qty,
                        "food_item_get"	=> $buy,
                        "food_item_get_id"	=> $value->bogo_item_id,
                        "get_qty" => $value->get_qty,
                        "start_date" => date("d/m/Y H:i:s",$from_date),
                        "end_date" => date("d/m/Y H:i:s",$to_date),
                    );
                }

                $response['message'] = "Success";
                $response['buyget'] = $buygetoffers;
                echo json_encode($response); exit;

            }else{
                $response['message'] = "No buy and get offers";
                echo json_encode($response);exit;
            }
        }else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postSpecialpriceupdate( Request $request){

        //$_REQUEST = (array) json_decode(file_get_contents("php://input"));
        $_REQUEST = str_replace('"','', $_REQUEST);

        $date_time_start = strtotime($_REQUEST['special_start_date']);
        $date_time_end = strtotime($_REQUEST['special_end_date']);

        $update =	\DB::table('abserve_hotel_items')->where('id', $_REQUEST['product_id'] )->update(array('special_price' => $_REQUEST['special_price'],'special_from' => date("Y-m-d",$date_time_start),'special_to' => date("Y-m-d",$date_time_end)));

        $response['message'] = "Updated successfully";
        echo json_encode($response); exit;

    }

    public function postSpecialpricedelete( Request $request){

        //$_REQUEST = (array) json_decode(file_get_contents("php://input"));
        //print_r($_REQUEST);  exit;
        $_REQUEST = str_replace('"','', $_REQUEST);

        $special_price = "0";
        $special_from = "0000-00-00 00:00:00";
        $special_to = "00000-00-00 00:00:00";


        $delete =	\DB::table('abserve_hotel_items')->where('id', $_REQUEST['product_id'] )->update(array('special_price' => $special_price,'special_from' => $special_from,'special_to' => $special_to));

        $response['message'] = "Deleted successfully";
        echo json_encode($response); exit;

    }

    public function postSpecialpricedetails( Request $request){

        $response = array();
        $rules = array(
            'restaurant_id'	=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $special = \DB::table('abserve_hotel_items')->where('restaurant_id','=',$_REQUEST['restaurant_id'])->where('special_price','!=','0')->get();

            if($special){

                foreach ($special as $key => $value) {

                    $from_date = strtotime($value->special_from);
                    $to_date = strtotime($value->special_to);

                    $specialpricedetails[] = array(
                        "food_item"	=> $value->food_item,
                        "product_id"	=> $value->id,
                        "price"	=> $value->price,
                        "special_price"	=> $value->special_price,
                        "special_from_date" => date("d/m/Y",$from_date),
                        "special_to_date" => date("d/m/Y",$to_date),
                    );
                }

                $response['message'] = "Success";
                $response['specialprice'] = $specialpricedetails;
                echo json_encode($response); exit;

            }else{
                $response['message'] = "No special price";
                echo json_encode($response);exit;
            }
        }else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }



    public function postDeliverypointdropdown( Request $request){

        $response = array();
        $rules = array(
            'pin_code'	=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
            $region =  $area->region_id;

            $special = \DB::table('delivery_point')->where('region','=',$region)->where('status',1)->orderBy('id')->get();

            if($special){

                foreach ($special as $key => $value) {

                    $deliverypoint[] = array(
                        "id"	=> $value->id,
                        "school_name"	=> $value->name,
                        "latitude"	=> $value->latitude,
                        "longitude"	=> $value->longitude,
                    );
                }

                $response['message'] = "Success";
                $response['delivery_point'] = $deliverypoint;
                echo json_encode($response); exit;

            }else{
                $response['message'] = "No delivery point";
                echo json_encode($response);exit;
            }
        }else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function locationdistance( $pin_code = ''){

        //$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();
        $area = \DB::select("SELECT `distance`,`region_id` FROM `area` WHERE `pin_code`=".$pin_code);

        return $area;
    }


    public function postCreatelunchbox( Request $request){

        if($request->user_id ==''){
            $rules = array(
                'group_id'       =>'required',
                'firstname'      =>'required|min:2',
                'email'          =>'required|email|unique:tb_users',
                //'password'       =>'required',
                'phone_number'	 =>'required|numeric',
                'secondary_number'	 =>'required',
                'pin_code'	 =>'required',
            );
        } else {
            $rules = array(
                'group_id'       =>'required',
                'firstname'      =>'required|min:2',
                'email'          =>'required',
                //'password'       =>'required',
                'phone_number'	 =>'required|numeric',
                'secondary_number'	 =>'required',
                'pin_code'	 =>'required',
            );
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            if(($request->device == "ios") || ($request->device == "iOS")){
                $device = "ios";
            } else {
                $device = $request->device;
            }
            $cust_id = $request->cust_id;

            $area = $this->locationdistance($request['pin_code']);
            //echo $area[0]->region_id;   exit;
            if(count($area)>0){

                if($request->user_id ==''){
                    $authen = new User;
                    $authen->username   = $request->firstname;
                    $authen->first_name = $request->firstname;
                    $authen->phone_number = $request->phone_number;
                    //$authen->phone_otp   = $otp;//$request->phone_otp;
                    $authen->phone_verified   = 1;
                    $authen->email = trim($request->email);
                    $authen->group_id = $request->group_id;
                    $authen->password = \Hash::make($request->password);
                    $authen->mobile_token = $request->mobile_token;
                    $authen->device = $device;
                    $authen->ios_flag = 1;
                    $authen->secondary_number = $request->secondary_number;
                    $authen->region = $area[0]->region_id;

                    $authen->active = '1';
                    $authen->save();

                    $val['user_id'] = (string)$authen->id;
                } else {
                    $val['user_id'] = (string)$request->user_id;
                }

                /*$data = array(
				'username'  	=> $request->firstname ,
				'firstname'		=> $request->firstname ,
				//'lastname'	=> $request->lastname,
				'email'			=> $request->email ,
				'phonenumber'	=> $request->phone_number ,
				'password'		=> $request->password,
				'code'			=> $code,
				'mobile_token' 	=> $request->mobile_token,
			    'device' 		=> $request->$device,
			);*/

                $val['first_name'] = $request->firstname;
                $val['email'] = $request->email;
                $val['primary_number'] = $request->phone_number;
                $val['active'] = '1';
                $val['secondary_number'] = $request->secondary_number;
                $val['region'] = $area[0]->region_id;

                if($cust_id ==''){
                    $ins=\DB::table('lunch_box_customers')->insert($val);
                    $cust_id = \DB::getPdo()->lastInsertId();
                } else {
                    \DB::table('lunch_box_customers')->where('id', $cust_id)->update($val);
                }


                $user = \DB::table('lunch_box_customers')->select('id','user_id','first_name','email','primary_number','secondary_number')->where('id','=',$cust_id)->get();

                $response["status"] 	  = true;
                $response["message"] 	  = "Success";
                $response["id"] 		  = "3";
                //$response["cust_id"] 	  = $cust_id;
                $response["user_details"] = $user;

            } else {
                $messages 				= $validator->messages();
                $error 					= (array)$messages->getMessages();
                $response["id"] 		= "5";
                $response["status"] 	= false;
                //$response["error"] 		= $error;
                if(!empty($error)){
                    if(isset($error['firstname'])){
                        $response['message'] = $error['firstname'][0];
                    } /*else if(isset($error['lastname'])){
						$response['message'] = $error['lastname'][0];
					}*/ else if(isset($error['email'])){
                        $response['message'] = $error['email'][0];
                    } else if(isset($error['password'])){
                        $response['message'] = $error['password'][0];
                    } else if(isset($error['phone_number'])){
                        $response['message'] = $error['phone_number'][0];
                    }
                }
            }
            echo json_encode($response);exit;

        } else {

            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            //$response["error"] 		= $error;
            if(!empty($error)){
                if(isset($error['firstname'])){
                    $response['message'] = $error['firstname'][0];
                } /*else if(isset($error['lastname'])){
					$response['message'] = $error['lastname'][0];
				}*/ else if(isset($error['email'])){
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


    public function postOtpconfirmlunchbox( Request $request){

        $response = array();

        $rules = array(
            'phone_number'	=> 'required|numeric',
            'otp'		=> 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $authUser = \DB::table('otp_verify')->select('*')->where('phone_number','=',$_REQUEST['phone_number'])->first();

            if($authUser->otp == $_REQUEST['otp']){

                $response['message'] = "Success";
                echo json_encode($response); exit;
            }else{
                $response['message'] = "OTP does not match";
                echo json_encode($response); exit;
            }

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }

    public function postOtpsendprimary( Request $request){

        $response = array();

        $rules = array(
            'phone_number'	 =>'required|numeric|unique:tb_users',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $otp = rand(100000, 999999);
            $exists = \DB::table('tb_users')->where('secondary_number','=',$request->phone_number)->exists();
            if($exists == ''){

                $phonecheck = \DB::table('otp_verify')->where('phone_number','=',$request->phone_number)->first();
                if($phonecheck){
                    $value['otp']=$otp;
                    $query=\DB::table('otp_verify')->where('phone_number','=', $request->phone_number)->update($value);

                    $sms = $this->sendSms($request->phone_number, $otp, $phonecheck->id);
                    $response['message'] = "Success";
                    echo json_encode($response); exit;

                }else {

                    $val['phone_number']=$request->phone_number;
                    $val['otp']=$otp;
                    $ins=\DB::table('otp_verify')->insert($val);
                    $oid = \DB::getPdo()->lastInsertId();

                    $sms = $this->sendSms($request->phone_number, $otp, $oid);
                    $response['message'] = "Success";
                    echo json_encode($response); exit;

                }
            }else{
                $response['message'] = "Phone number has already been taken";
                echo json_encode($response); exit;
            }
        } else {
            $response['message'] = "Phone number has exist";
            echo json_encode($response); exit;
        }

    }

    public function postOtpsendsecondary( Request $request){

        $response = array();

        $rules = array(
            'secondary_number'	 =>'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $otp = rand(100000, 999999);

            $phonecheck = \DB::table('otp_verify')->where('phone_number','=',$request->secondary_number)->first();
            if($phonecheck){
                $value['otp']=$otp;
                $query=\DB::table('otp_verify')->where('phone_number','=', $request->secondary_number)->update($value);

                $sms = $this->sendSms($request->secondary_number, $otp, $phonecheck->id);
                $response['message'] = "Success";
                echo json_encode($response); exit;

            }else {

                $val['phone_number']=$request->secondary_number;
                $val['otp']=$otp;
                $ins=\DB::table('otp_verify')->insert($val);
                $oid = \DB::getPdo()->lastInsertId();

                $sms = $this->sendSms($request->secondary_number, $otp, $oid);

                $response['message'] = "Success";
                // $response['user_id'] = (string)$authUser->user_id;
                echo json_encode($response); exit;

            }
            echo json_encode($response); exit;
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;

        }
        echo json_encode($response); exit;
    }

    public function postCreatestudentform (Request $request){

        $rules = array(
            'cust_id'             =>'required',
            'user_id'             =>'required',
            'stud_name'           =>'required',
            'standard'   	      =>'required',
            'section'	 		  =>'required',
            'dob'	      		  =>'required',
            'school_id'	  		  =>'required',
            'subscription_plan'   =>'required',
            'delivery_type'	      =>'required',
            'delivery_charge'	  =>'required',
            'pickup_time'	      =>'required',
            'plan_id'	          =>'required',
            'total_price'	      =>'required',
            'permanent_address'	  =>'required',
            'pickup_address'      =>'required',
            'permanent_lat'	      =>'required',
            'permanent_lang'	  =>'required',
            'pickup_lat'	      =>'required',
            'pickup_lang'	      =>'required',
            'permanent_pin_code'  =>'required',
            'pickup_pin_code'	  =>'required',

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $date = $request->dob;
            $dob =  date('Y-m-d', strtotime($date));

            if($request->subscription_plan == 'weekly'){
                $date1 = strtotime("+7 day");
                $plan_to =  date('Y-m-d', $date1);
            }

            if($request->subscription_plan == 'monthly'){
                $date2 = strtotime("+1 month");
                $plan_to =  date('Y-m-d', $date2);
            }

            $val['cust_id']=$request->cust_id;
            $val['user_id']=$request->user_id;
            $val['stud_name']=$request->stud_name;
            $val['standard']=$request->standard;
            $val['section']=$request->section;
            $val['dob']=$dob;
            $val['school_id']=$request->school_id;
            $val['subscription_plan']=$request->subscription_plan;
            $val['delivery_type']=$request->delivery_type;
            $val['delivery_charge']=$request->delivery_charge;
            $val['plan_from']=date("Y-m-d");
            $val['plan_to']= $plan_to;
            $val['pickup_time']=$request->pickup_time;
            $val['return_time']=$request->return_time;
            $val['plan_id']=$request->plan_id;
            $val['total_price']=$request->total_price;
            $val['permanent_address']=$request->permanent_address;
            $val['pickup_address']=$request->pickup_address;
            $val['permanent_lat']=$request->permanent_lat;
            $val['permanent_lang']=$request->permanent_lang;
            $val['pickup_lat']=$request->pickup_lat;
            $val['pickup_lang']=$request->pickup_lang;
            $val['permanent_pin_code']=$request->permanent_pin_code;
            $val['pickup_pin_code']=$request->pickup_pin_code;

            if($request->student_id == ''){
                $ins=\DB::table('lunch_box_student_info')->insert($val);
                $lastid = \DB::getPdo()->lastInsertId();
            }else{
                $update = \DB::table('lunch_box_student_info')->where('id', $request->student_id)->update($val);
                $lastid = $request->student_id;
            }

            if($request->permanent_address){

                $new['cust_id']=$request->cust_id;
                $new['user_id']=$request->user_id;
                $new['address']=$request->permanent_address;
                $new['lat']=$request->permanent_lat;
                $new['lang']=$request->permanent_lang;
                $new['pin_code']=$request->permanent_pin_code;
                $new['type']='1';

                if($request->student_id == ''){
                    $new['stud_id']=$lastid;
                    $ins=\DB::table('lunch_box_customer_address')->insert($new);
                }else{
                    //$new['stud_id']=$request->student_id;
                    $update = \DB::table('lunch_box_customer_address')->where('stud_id', $request->student_id)->where('type','=',1)->update($new);
                }
            }

            if($request->pickup_address){

                $val1['cust_id']=$request->cust_id;
                $val1['user_id']=$request->user_id;
                $val1['address']=$request->pickup_address;
                $val1['lat']=$request->pickup_lat;
                $val1['lang']=$request->pickup_lang;
                $val1['pin_code']=$request->pickup_pin_code;
                $val1['type']='2';
                //$val1['stud_id']=$lastid;

                if($request->student_id == ''){
                    $val1['stud_id']=$lastid;
                    $ins=\DB::table('lunch_box_customer_address')->insert($val1);
                }else{
                    //$val1['stud_id']=$request->student_id;
                    $update = \DB::table('lunch_box_customer_address')->where('stud_id', $request->student_id)->where('type','=',2)->update($val1);
                }
            }

            if($request->total_price){

                $val_new['cust_id']=$request->cust_id;
                $val_new['user_id']=$request->user_id;
                $val_new['stud_id']=$lastid;
                $val_new['price']=$request->total_price;
                $val_new['plan']=$request->subscription_plan;
                $val_new['delivery_type']=$request->delivery_type;
                $ins=\DB::table('lunchbox_orderitems')->insert($val_new);
                $_lastid = \DB::getPdo()->lastInsertId();

                $order = 'lb'.$_lastid;
                $update = \DB::table('lunchbox_orderitems')->where('id','=',$_lastid)->update(['orderid'=>$order]);
            }

            $lunch_box_cust = \DB::table('lunch_box_customers')->where('id','=',$request->cust_id)->first();

            $response['message'] = "Success";
            $response['student_id'] = $lastid;
            $response['order_details'] 	= array(
                "accessCode"		=> CCAVENUE_ACCESSCODE,
                "merchantId"		=> CCAVENUE_MERCHANTID,
                "orderId"			=> (string)$order,
                "currency"			=> "INR",
                "amount"			=> (string)$request->total_price,
                "redirectUrl"		=> \URL::to('').'/mobile/user/lbccavresponsehandler',
                "cancelUrl"			=> \URL::to('').'/mobile/user/lbccavresponsehandler',
                "rsaKeyUrl"			=> \URL::to('').'/ccavenue/GetRSA.php',
                /*"billingName"		=> $billing_name,
										"billingAddress"	=> $billing_address,
										"billingZip"		=> $billing_zip,
										"billingCity"		=> $billing_city,
										"billingState"		=> $billing_state,*/
                "billingCountry"	=> "India",
                "billingMobilenumber"=> (string)$lunch_box_cust->primary_number,
                "billingEmail"		=> $lunch_box_cust->email
            );
            echo json_encode($response); exit;

        } else {

            $response["id"] 		= "5";
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();

            if(!empty($error)){
                if(isset($error['user_id'])){
                    $response['message'] = $error['user_id'][0];
                } else if(isset($error['cust_id'])){
                    $response['message'] = $error['cust_id'][0];
                } else if(isset($error['stud_name'])){
                    $response['message'] = $error['stud_name'][0];
                } else if(isset($error['standard'])){
                    $response['message'] = $error['standard'][0];
                } else if(isset($error['section'])){
                    $response['message'] = $error['section'][0];
                }else if(isset($error['dob'])){
                    $response['message'] = $error['dob'][0];
                }else if(isset($error['school_id'])){
                    $response['message'] = $error['school_id'][0];
                }else if(isset($error['subscription_plan'])){
                    $response['message'] = $error['subscription_plan'][0];
                }else if(isset($error['delivery_type'])){
                    $response['message'] = $error['delivery_type'][0];
                }else if(isset($error['delivery_charge'])){
                    $response['message'] = $error['delivery_charge'][0];
                }else if(isset($error['pickup_time'])){
                    $response['message'] = $error['pickup_time'][0];
                }else if(isset($error['plan_id'])){
                    $response['message'] = $error['plan_id'][0];
                }else if(isset($error['total_price'])){
                    $response['message'] = $error['total_price'][0];
                }else if(isset($error['permanent_address'])){
                    $response['message'] = $error['permanent_address'][0];
                }else if(isset($error['pickup_address'])){
                    $response['message'] = $error['pickup_address'][0];
                }else if(isset($error['permanent_lat'])){
                    $response['message'] = $error['permanent_lat'][0];
                }else if(isset($error['permanent_lang'])){
                    $response['message'] = $error['permanent_lang'][0];
                }else if(isset($error['pickup_lat'])){
                    $response['message'] = $error['pickup_lat'][0];
                }else if(isset($error['pickup_lang'])){
                    $response['message'] = $error['pickup_lang'][0];
                }else if(isset($error['permanent_pin_code'])){
                    $response['message'] = $error['permanent_pin_code'][0];
                }else if(isset($error['pickup_pin_code'])){
                    $response['message'] = $error['pickup_pin_code'][0];
                }
            }

            $response["error"] 		= $error;

        }
        echo json_encode($response); exit;
    }


    public function postLbccavresponsehandler( Request $request){
        //print_r($request->all());  exit;
        include(app_path() . '/Functions/Crypto.php');

        error_reporting(0);

        $workingKey=CCAVENUE_WORKINGCODE;		//Working Key should be provided here.
        $encResponse=$_REQUEST["encResp"];			//This is the response sent by the CCAvenue Server
        $rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
        $subscription_status="";
        $orderid="";
        $decryptValues=explode('&', $rcvdString);
        $dataSize=sizeof($decryptValues);
        echo "<center>";

        for($i = 0; $i < $dataSize; $i++)
        {
            $information=explode('=',$decryptValues[$i]);

            if($information[0] == 'tracking_id')
                $tracking_id=$information[1];

            if($information[0] == 'order_id')
                $orderid=$information[1];

            if($information[0] == 'order_status')
                $subscription_status=$information[1];

            if($information[0] == 'payment_mode')
                $mop=$information[1];
        }

        $order = \DB::table('lunchbox_orderitems')->where('orderid','=',$orderid)->first();
        $stud_id = $order->stud_id;

        if($subscription_status==="Success")
        {

            $abp = \DB::table('lunchbox_orderitems')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>1]);
            \DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>1]);

            $order_datas = \DB::table('lunchbox_orderitems')->where('orderid','=',$orderid)->first();

            $customer = \DB::table('tb_users')->where('id',$order_datas->user_id)->first();

            $message1 = $order_datas->plan." plan subscribed successfully";
            $this->sendSms1($customer->phone_number, $message1, $order_datas->user_id);


            //echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Success</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/success.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }
        else if($subscription_status==="Aborted")
        {
            $abp = \DB::table('lunchbox_orderitems')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>2]);
            \DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>2]);


            //echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Aborted</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }
        else if($subscription_status==="Failure")
        {
            $abp = \DB::table('lunchbox_orderitems')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>3]);
            \DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>3]);

            //echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Failure</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';
        }
        else
        {
            $abp = \DB::table('lunchbox_orderitems')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>4]);
            \DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>4]);


            //echo "<br>Security Error. Illegal access detected";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Aborted</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }

        /*echo "<br><br>";

		echo "<table cellspacing=4 cellpadding=4>";
		for($i = 0; $i < $dataSize; $i++)
		{
			$information=explode('=',$decryptValues[$i]);
				echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
		}

		echo "</table><br>";
		echo "</center>";*/


    }



    public function postLunchboxdetails (Request $request){

        $rules = array(
            'cust_id'       =>'required',
            'pin_code'      =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
            //$lunchbox = \DB::table('delivery_point')->where('region',$area->region_id)->get();
            $region =  $area->region_id;

            $lunchbox = \DB::table('delivery_point')->where('region',$area->region_id)->where('status',1)->get();
            if(count($lunchbox)>0){
                $response['status'] = "true";
            }else{
                $response['status'] = "false";
            }

            $lunch_box_cust = \DB::table('lunch_box_customers')->where('id','=',$request->cust_id)->get();

            $response['message'] = "true";
            foreach($lunch_box_cust as $value){
                $lunchbox_cust[] = array(
                    "cust_id"				=> $value->id,
                    "user_id"				=> $value->user_id,
                    "first_name"			=> $value->first_name,
                    "email"					=> $value->email,
                    "primary_number"		=> $value->primary_number,
                    "secondary_number"		=> $value->secondary_number,
                    "subscription_plan"		=> $value->subscription_plan,
                    "active"				=> $value->active,
                );
            }
            $response["lunch_box_cust_details"] = $lunchbox_cust;
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            //$response["error"] 		= $error;

        }
        echo json_encode($response); exit;
    }


    public function postSubscriptionplan (Request $request){


        $response = array();
        $rules = array(
            'pin_code'	=>'required',
        );

        $validator = Validator::make($_REQUEST, $rules);

        if ($validator->passes()) {

            $area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
            $region =  $area->region_id;


            /*---------region code get values start------*/
            $subcharges = \DB::table('subscription_plan')->where('region','=',$region)->groupBy('start_km')->get();
            $subsciption_plan = array();
            if($subcharges){
                foreach($subcharges as $value){

                    $subc = \DB::table('subscription_plan')->where('start_km','=',$value->start_km)->where('region','=',$region)->groupBy('plan')->get();
                    //print_r($subc);  exit;
                    $plan = array();
                    foreach($subc as $subplan){


                        $subgetplan = \DB::table('subscription_plan')->where('plan','=',$subplan->plan)->where('region','=',$region)->where('start_km','=',$subplan->start_km)->get();
                        //	print_r($subgetplan);
                        $delivers = array();

                        foreach($subgetplan as $subgetplan1){

                            $delivers[] = array(

                                "plan_id"	=> $subgetplan1->id,
                                "delivery_type"	=> $subgetplan1->deliverytype,
                                "price" => $subgetplan1->price,
                            );
                        }



                        $plan[] = array(

                            "plan_type"	=> $subplan->plan,
                            "delivers"		=> $delivers,
                        );


                    }

                    $subsciption_plan[] = array(

                        "start_km"	=> $value->start_km,
                        "end_km"	=> $value->end_km,
                        "plan"		=> $plan,
                    );

                }
            }

            /*$lunch_box_offers = \DB::table('lunch_box_offers')->where('region','=',$region)->orderBy('id')->get();
			if($lunch_box_offers){


				foreach ($lunch_box_offers as $key => $value) {


					$lb_offers[] = array(
											"coupon_code"	=> $value->coupon_code,
											"flat_rs"	=> $value->flat_rs,
											"km_min"	=> $value->km_min,
											"km_max"	=> $value->km_max,
											"deliverytype"	=> $deliverytype,
											"offer_from"	=> date("d/m/Y", strtotime($value->offer_from)),
											"offer_to"	=> date("d/m/Y", strtotime($value->offer_to)),
									          );
				}

			}else {
			$lb_offers[] = "No offers";
			}
			*/

            $response['message'] = "Success";
            $response['date'] = date("d/m/Y");
            $response['subsciption_plan'] = $subsciption_plan;
            //$response['lb_offers'] = $lb_offers;
            echo json_encode($response); exit;

        }else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }


    }

    public function postLbstudentdetails (Request $request){

        $rules = array(
            'cust_id'       =>'required',
            //'pin_code'      =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $current_date = date('Y-m-d');

            $student_details= \DB::table('lunch_box_student_info')->where('cust_id','=',$request->cust_id)->get();
            $student_details_info = array();
            foreach($student_details as $value){

                $plan_to = $value->plan_to;
                if($current_date <= $plan_to){
                    $plan_status = "true";
                } else {
                    $plan_status = "false";
                }
                $payment_status = $value->payment_status;
                if($payment_status == 1){
                    $payment_status = "Paid";
                } else {
                    $payment_status = "Unpaid";
                }

                $schollname = \DB::table('delivery_point')->where('id','=',$value->school_id)->where('status',1)->first();
                //echo $schollname->name ; exit;
                $leave_days = \DB::table('lunchbox_leave_days')->where('stud_id','=',$value->id)->whereRaw('"'.$current_date.'" between `leave_date_from` and `leave_date_to`')->first();
                if($leave_days->id !=''){
                    $leave_status = "true";
                    $leave_id = $leave_days->id;
                } else {
                    $leave_status = "false";
                    $leave_id = "";
                }


                if($value->pickuptime_checked !=''){
                    $remainder_status = $value->pickuptime_checked;
                } else {
                    $remainder_status = "0";
                }

                $student_details_info[] = array(
                    "student_id"			=> $value->id,
                    "cust_id"				=> $value->cust_id,
                    "user_id"				=> $value->user_id,
                    "stud_name"			    => $value->stud_name,
                    "standard"			    => $value->standard,
                    "section"		        => $value->section,
                    "dob"		            => date("d-m-Y", strtotime($value->dob)),
                    "school_id"		        => $value->school_id,
                    "school_name"		    => $schollname->name,
                    "school_lat"		    => $schollname->latitude,
                    "school_lang"		    => $schollname->longitude,
                    "subscription_plan"		=> $value->subscription_plan,
                    "delivery_type"	        => $value->delivery_type,
                    "delivery_charge"	    => $value->delivery_charge,
                    "pickup_time"			=> $value->pickup_time,
                    "return_time"			=> $value->return_time,
                    "plan_from"             => date("d-m-Y", strtotime($value->plan_from)),
                    "plan_to"	            => date("d-m-Y", strtotime($value->plan_to)),
                    "total_price"			=> $value->total_price,
                    "permanent_address"	    => $value->permanent_address,
                    "pickup_address"        => $value->pickup_address,
                    "permanent_lat"		    => $value->permanent_lat,
                    "permanent_lang"		=> $value->permanent_lang,
                    "pickup_lat"		    => $value->pickup_lat,
                    "pickup_lang"			=> $value->pickup_lang,
                    "permanent_pin_code"    => $value->permanent_pin_code,
                    "pickup_pin_code"	    => $value->pickup_pin_code,
                    "plan_id"	            => $value->plan_id,
                    "plan_status"	        => $plan_status,
                    "payment_status"        => $payment_status,
                    "nopickup_status"       => $leave_status,
                    "remainder_status"      => $remainder_status,
                    "duration"              => $value->duration,
                    "leave_id"        		=> $leave_id,

                );

            }
            $response["datetime"] = date('d-m-Y H:i:s');
            $response["lb_student_details"] = $student_details_info;
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            echo json_encode($response); exit;

        }
        //
    }


    public function postLbleavedays (Request $request){

        $rules = array(
            'cust_id'             =>'required',
            'stud_id'             =>'required',
            'leave_date_from'     =>'required',
            'leave_date_to'       =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $date = $request->leave_date_from;
            $leave_date_from =  strtotime(str_replace('/', '-', $date));
            $passed_time = '10:30:00';
            $current_time = date("H:i:s");
            $today = strtotime(date("Y-m-d"));

            $leave_from = date("Y-m-d", strtotime($request->leave_date_from));
            $leave_to = date("Y-m-d", strtotime($request->leave_date_to));

            if($today == $leave_date_from){

                if((strtotime($passed_time)) > (strtotime($current_time))){

                    $leave_days = \DB::table('lunchbox_leave_days')->where('stud_id','=',$request->stud_id)->where('leave_date_from','>=',$leave_from)->where('leave_date_to','<=',$leave_to)->get();
                    if(count($leave_days)>0){
                        $response['message'] = "Already leave applied on the day";
                    } else {

                        $val['cust_id']=$request->cust_id;
                        $val['stud_id']=$request->stud_id;
                        /*$val['leave_date_from']= date('Y-m-d', strtotime($request->leave_date_from));
						$val['leave_date_to']=  date('Y-m-d', strtotime($request->leave_date_to));
						$val['status']=$request->status;*/
                        $begin = new DateTime(date('Y-m-d',strtotime($request->leave_date_from)));
                        $end = new DateTime(date('Y-m-d',strtotime($request->leave_date_to)));
                        $end = $end->modify( '+1 day' );
                        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

                        foreach($daterange as $date){
                            $val['leave_date_from'] = $date->format("Y-m-d");
                            $val['leave_date_to'] = $date->format("Y-m-d");
                            $val['status']=$request->status;
                            $ins=\DB::table('lunchbox_leave_days')->insert($val);
                        }
                        //$ins=\DB::table('lunchbox_leave_days')->insert($val);
                        $response['message'] = "Success";
                    }
                }else{
                    $response['message'] = "Time over";
                }

            }elseif($leave_date_from > $today){
                $leave_days = \DB::table('lunchbox_leave_days')->where('stud_id','=',$request->stud_id)->where('leave_date_from','>=',$leave_from)->where('leave_date_to','<=',$leave_to)->get();
                if(count($leave_days)>0){
                    $response['message'] = "Already leave applied on the day";
                } else {
                    $val['cust_id']=$request->cust_id;
                    $val['stud_id']=$request->stud_id;
                    //$val['leave_date_from']= date('Y-m-d', strtotime($request->leave_date_from));
                    //$val['leave_date_to']=  date('Y-m-d', strtotime($request->leave_date_to));
                    //$val['status']=$request->status;

                    $begin = new DateTime(date('Y-m-d',strtotime($request->leave_date_from)));
                    $end = new DateTime(date('Y-m-d',strtotime($request->leave_date_to)));
                    $end = $end->modify( '+1 day' );
                    $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

                    foreach($daterange as $date){
                        $val['leave_date_from'] = $date->format("Y-m-d");
                        $val['leave_date_to'] = $date->format("Y-m-d");
                        $val['status']=$request->status;
                        $ins=\DB::table('lunchbox_leave_days')->insert($val);
                    }

                    //$ins=\DB::table('lunchbox_leave_days')->insert($val);
                    $response['message'] = "Success";
                }

            }
            else{
                $response['message'] = "Time over";
            }
            echo json_encode($response); exit;
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            //$response["error"] 		= $error;
            echo json_encode($response); exit;
        }

    }

    public function postLbleaveinfo (Request $request){

        $rules = array(
            'cust_id'             =>'required',
            'stud_id'             =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $current_date = date("Y-m-d");
            $stud_id = $request->stud_id;
            $leave_days = \DB::table('lunchbox_leave_days')->where('stud_id', '=', $stud_id)->where('leave_date_to', '>=', $current_date)->where('status', '=', 1)->get();

            $leave_info = array();
            foreach($leave_days as $leave_day){
                $leave_info[] = array(
                    "leave_id" 			=> $leave_day->id,
                    "cust_id" 			=> $leave_day->cust_id,
                    "stud_id" 			=> $leave_day->stud_id,
                    "leave_date_from" 	=> date('d-m-Y', strtotime($leave_day->leave_date_from)),
                    "leave_date_to" 	=> date('d-m-Y', strtotime($leave_day->leave_date_to)),
                    "status" 			=> $leave_day->status,
                );
            }

            $response['leave_info'] = $leave_info;

            echo json_encode($response); exit;
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            //$response["error"] 		= $error;
            echo json_encode($response); exit;
        }
    }


    public function postLbleavedelete (Request $request){

        $rules = array(
            'leave_id'            =>'required',
            //'stud_id'             =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $leave_id = $request->leave_id;
            $leave_days = \DB::table('lunchbox_leave_days')->where('id', '=', $leave_id)->delete();
            //$leave_days = \DB::table('lunchbox_leave_days')->where('id', '=', $leave_id)->update(['status' => 0]);

            if($leave_days){
                $response['message'] = "Leave deleted successfully";
            } else {
                $response['message'] = "Leave date is not available";
            }

            //$response['leave_info'] = $leave_info;

            echo json_encode($response); exit;
        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response['message'] 	= "Leave id empty";
            //$response["error"] 		= $error;
            echo json_encode($response); exit;
        }
    }


    public function postFeedbackmail (Request $request){

        $rules = array(
            'content'       =>'required',
            'email'       	=>'required',
            'phone_number'  =>'',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $content = $request->content;
            $phone = $request->phone_number;

            $to = "feedback@deliverystar.in";
            $subject = "Feedback mail";
            //$message = $content;
            $message     = "$content <br><br><br>
			
			Phone number - $phone ";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: '.$request->email . "\r\n";


            mail($to,$subject,$message,$headers);

            $response["message"] 		= "Success";
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response["message"] 	= "false";
            $response["error"] 		= $error;

        }
        echo json_encode($response); exit;
    }

    public function postServiceccavresponsehandler( Request $request){
        //print_r($request->all());  exit;
        include(app_path() . '/Functions/Crypto.php');

        error_reporting(0);

        $workingKey=CCAVENUE_WORKINGCODE;		//Working Key should be provided here.
        $encResponse=$_REQUEST["encResp"];			//This is the response sent by the CCAvenue Server
        $rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
        $subscription_status="";
        $orderid="";
        $decryptValues=explode('&', $rcvdString);
        $dataSize=sizeof($decryptValues);
        echo "<center>";

        for($i = 0; $i < $dataSize; $i++)
        {
            $information=explode('=',$decryptValues[$i]);

            if($information[0] == 'tracking_id')
                $tracking_id=$information[1];

            if($information[0] == 'order_id')
                $orderid=$information[1];

            if($information[0] == 'order_status')
                $subscription_status=$information[1];

            if($information[0] == 'payment_mode')
                $mop=$information[1];
        }

        $order = \DB::table('service_form')->where('orderid','=',$orderid)->first();
        //$stud_id = $order->stud_id;

        if($subscription_status==="Success")
        {

            $abp = \DB::table('service_form')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>1]);
            //\DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>1]);

            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Success</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/success.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }
        else if($subscription_status==="Aborted")
        {
            $abp = \DB::table('service_form')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>2]);
            //\DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>2]);


            //echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Aborted</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }
        else if($subscription_status==="Failure")
        {
            $abp = \DB::table('service_form')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>3]);
            //\DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>3]);

            //echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Failure</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';
        }
        else
        {
            $abp = \DB::table('service_form')->where('orderid','=',$orderid)->update(['reference_no'=>$tracking_id,'mop'=>$mop,'subscription_status'=>4]);
            //\DB::table('lunch_box_student_info')->where('id','=',$stud_id)->update(['payment_status'=>4]);


            //echo "<br>Security Error. Illegal access detected";
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Aborted</title>
				</head>
				<body style="text-align:center; display:block; width:100%;">'?>
            <img src="<?php echo \URL::to('').'/uploads/ccavenue/cancel.png'; ?>" width="100%" />
            <?php '</body>
				</html>';

        }


    }



    public function iospushnotification5($app_api,$mobile_token,$message,$app_name)
    {


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

    public function pushnotification5($app_api,$mobile_token,$message,$app_name)
    {



        $registrationIds = $mobile_token;
        //print_r($mobile_token);   exit;

        // prep the bundle
        $msg = array
        (
            'body' 	=> $message,
            'message'		=> 'Message from '.$app_name,
            /*'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'=> 'Ticker text here...Ticker text here...Ticker text here',*/
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon',
            //'mediaUrl' =>   $image,
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

        if ($res === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Getting value from success
        $flag = $res->success;
        //echo $flag;  exit;
    }


    public function postRenewal (Request $request){

        $date = date("Y-m-d", strtotime("+2 day"));
        $message = "Please active subscription plan.It will expire within two days";

        $query = "SELECT `tb`.*,`lbs`.`id` as `stud_id`,`lbs`.`stud_name`,`lbs`.`plan_from`,`lbs`.`plan_to` FROM `tb_users` AS `tb` JOIN `lunch_box_student_info` AS `lbs` ON `tb`.`id` = `lbs`.`user_id` WHERE `lbs`.`plan_to` = '".$date."' AND `lbs`.`payment_status`= '1' AND `tb`.`device`='ios'";
        $renewal = \DB::select($query);

        // print_r($renewal);
        $mobile_token_value = array();
        foreach($renewal as $renewal1){

            $val['user_id'] = $renewal1->id;
            $val['stud_id'] = $renewal1->stud_id;
            $val['stud_name'] = $renewal1->stud_name;
            $val['message'] =  $message;
            $val['status'] =  '1';
            $ins=\DB::table('lb_subscription_notification')->insert($val);

            $mobile_token_value[] = $renewal1->mobile_token;
            $message = $message;
            $appapi_details	= $this->appapimethod(4);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;

        }

        $mobile_tokens = array();
        $k = count($mobile_token_value);

        if($k > 1000){
            $mobile_tokens = array_chunk($mobile_token_value, 1000);
            $j = count($mobile_tokens);

            for($i = 0; $i < $j; $i++){

                $this->iospushnotification5($app_api,$mobile_tokens[$i],$message,$app_name,$file_name);
            }

        } else {

            $this->iospushnotification5($app_api,$mobile_token_value,$message,$app_name,$file_name);

        }

        $query1 = "SELECT `tb`.*,`lbs`.`id` as `stud_id`,`lbs`.`stud_name`,`lbs`.`plan_from`,`lbs`.`plan_to` FROM `tb_users` AS `tb` JOIN `lunch_box_student_info` AS `lbs` ON `tb`.`id` = `lbs`.`user_id` WHERE `lbs`.`plan_to` = '".$date."' AND `lbs`.`payment_status`= '1' AND `tb`.`device`='android'";
        $renewaland = \DB::select($query1);
        // print_r($renewaland);  exit;
        $mobile_token_value = array();
        foreach($renewaland as $renewal1){


            $val['user_id'] = $renewal1->id;
            $val['stud_id'] = $renewal1->stud_id;
            $val['stud_name'] = $renewal1->stud_name;
            $val['message'] =  $message;
            $val['status'] =  '1';
            $ins=\DB::table('lb_subscription_notification')->insert($val);

            $mobile_token_value[] = $renewal1->mobile_token;
            $message = $message;
            $appapi_details	= $this->appapimethod(1);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;

        }
        // print_r($mobile_token_value);

        $mobile_tokens = array();
        $k = count($mobile_token_value);
        //print_r($k);
        if($k > 1000){
            $mobile_tokens = array_chunk($mobile_token_value, 1000);
            $j = count($mobile_tokens);
            //print_r($j);
            for($i = 0; $i < $j; $i++){
                //print_r($mobile_tokens[$i]);
                $this->pushnotification5($app_api,$mobile_tokens[$i],$message,$app_name,$file_name);
            }

        } else {
            //$j = count($mobile_token_value);
            //print_r($j);
            $this->pushnotification5($app_api,$mobile_token_value,$message,$app_name,$file_name);
        }


    }


    public function postRenewaldata (Request $request){

        $rules = array(
            'user_id'       =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $renewalid = $request->user_id;
            $renewaldata = \DB::table('lb_subscription_notification')->where('user_id', '=', $renewalid)->where('status', '=','1')->get();

            $renewaldata1 = array();
            foreach($renewaldata as $renewaldatas){

                $studetails = \DB::table('lunch_box_student_info')->where('id', '=', $renewaldatas->stud_id)->first();

                $plan_to = $studetails->plan_to;
                if($current_date <= $plan_to){
                    $plan_status = "true";
                } else {
                    $plan_status = "false";
                }

                $payment_status = $studetails->payment_status;
                if($payment_status == 1){
                    $payment_status = "Paid";
                } else {
                    $payment_status = "Unpaid";
                }

                $schollname = \DB::table('delivery_point')->where('id','=',$studetails->school_id)->first();
                //print_r($schollname);  exit;
                $leave_days = \DB::table('lunchbox_leave_days')->where('stud_id','=',$studetails->id)->whereRaw('"'.$current_date.'" between `leave_date_from` and `leave_date_to`')->first();
                if($leave_days->id !=''){
                    $leave_status = "true";
                    $leave_id = $leave_days->id;
                } else {
                    $leave_status = "false";
                    $leave_id = "";
                }

                if($renewaldatas->pickuptime_checked !=''){
                    $remainder_status = $renewaldatas->pickuptime_checked;
                } else {
                    $remainder_status = "0";
                }


                $renewaldata1[] = array(
                    "id" 			    => $renewaldatas->id,
                    "user_id" 			=> $renewaldatas->user_id,
                    "cust_id" 			=> $studetails->cust_id,
                    "stud_id" 			=> $renewaldatas->stud_id,
                    "stud_name" 		=> $renewaldatas->stud_name,
                    "message" 			=> $renewaldatas->message,
                    "standard" 			=> $studetails->standard,
                    "section" 		    => $studetails->section,
                    "dob"		        => date("d-m-Y", strtotime($studetails->dob)),
                    "pickup_time" 	    => $studetails->pickup_time,
                    "return_time" 		=> $studetails->return_time,
                    "permanent_address" => $studetails->permanent_address,
                    "pickup_address" 	=> $studetails->pickup_address,
                    "permanent_lat" 	=> $studetails->permanent_lat,
                    "permanent_lang" 	=> $studetails->permanent_lang,
                    "pickup_lat" 		=> $studetails->pickup_lat,
                    "pickup_lang" 		=> $studetails->pickup_lang,
                    "permanent_pin_code"=> $studetails->permanent_pin_code,
                    "pickup_pin_code"   => $studetails->pickup_pin_code,
                    "school_id" 		=> $studetails->school_id,
                    "subscription_plan" => $studetails->subscription_plan,
                    "plan_from" 		=> date("d-m-Y", strtotime($studetails->plan_from)),
                    "plan_to" 		    => date("d-m-Y", strtotime($studetails->plan_to)),
                    "delivery_type"     => $studetails->delivery_type,
                    "delivery_charge" 	=> $studetails->delivery_charge,
                    "plan_status" 		=> $plan_status,
                    "plan_id"	        => $studetails->plan_id,
                    "total_price" 		=> $studetails->total_price,
                    "payment_status"    => $payment_status,
                    "school_name"	    => $schollname->name,
                    "school_lat"		=> $schollname->latitude,
                    "school_lang"	    => $schollname->longitude,
                    "nopickup_status"   => $leave_status,
                    "remainder_status"  => $remainder_status,
                    "duration"          => $renewaldatas->duration,
                    "leave_id"          => $leave_id,
                );
            }

            $response["message"] 		= "Success";
            $response["datetime"] = date('d-m-Y H:i:s');
            $response['renewaldata1'] = $renewaldata1;

            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response["message"] 	= "false";
            $response["error"] 		= $error;

        }
        echo json_encode($response); exit;
    }


    public function postRenewaldelete (Request $request){

        $rules = array(
            'id'       =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $renewalid = $request->id;

            $val['status'] = '0';

            $renewaldata = \DB::table('lb_subscription_notification')->where('id', '=', $renewalid)->update($val);

            $response["message"] 		= "Success";
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response["message"] 	= "false";
            $response["error"] 		= $error;

        }
        echo json_encode($response); exit;
    }



    public function getLbpickuptimeduration (Request $request){


        $lbduration = \DB::select("SELECT * FROM `lb_pickuptime_dropdown`");
        //print_r($lbduration);

        $duration = array();
        foreach($lbduration as $lbduration1){
            $duration[] = array(
                "id" 			    => $lbduration1->id,
                "duration" 			=> $lbduration1->duration,
            );
        }

        $response["message"] 		= "Success";
        $response['duration'] = $duration;

        echo json_encode($response); exit;

    }


    public function postPickuptimesave (Request $request){

        $rules = array(
            'stud_id'       =>'required',
            'duration'       =>'required',
            'checked'       =>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $renewalid = $request->stud_id;

            $query = \DB::table('lunch_box_student_info')->where('id', '=', $renewalid)->first();
            $pickuptime =  strtotime($query->pickup_time);
            $before_min_duration = $pickuptime-(60*($request->duration));
            $before_min = date("h:ia", $before_min_duration);

            $val['notification_time'] = $before_min;
            $val['duration'] = $request->duration;
            $val['pickuptime_checked'] = $request->checked;


            $renewaldata = \DB::table('lunch_box_student_info')->where('id', '=', $renewalid)->update($val);

            $response["message"] 		= "Success";
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["id"] 		= "5";
            $response["message"] 	= "false";
            $response["error"] 		= $error;

        }
        echo json_encode($response); exit;
    }

    public function postPickuptimealert( Request $request){

        $current_date_time = strtotime(date("Y-m-d H:i:s"));
        $current_time =  date("h:ia", $current_date_time);

        $query1 = "SELECT `tb`.*,`lbs`.`id` as `stud_id`,`lbs`.`notification_time`,`lbs`.`payment_status`,`lbs`.`duration`,`lbs`.`pickuptime_checked` FROM `tb_users` AS `tb` JOIN `lunch_box_student_info` AS `lbs` ON `tb`.`id` = `lbs`.`user_id` WHERE `lbs`.`notification_time` = '".$current_time."' AND `lbs`.`payment_status`= '1'  AND `lbs`.`pickuptime_checked`= '1' AND `tb`.`device`='ios'";
        $pickuptime = \DB::select($query1);
        //print_r($pickuptime);    exit;

        $mobile_token_value = array();
        foreach($pickuptime as $pickuptime1){

            //$message = $pickuptime1->duration.'mins '.'left for pickup';
            $message = 'Few mins left for pickup';

            $mobile_token_value[] = $pickuptime1->mobile_token;
            $message = $message;
            $appapi_details	= $this->appapimethod(4);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;

        }

        $mobile_tokens = array();
        $k = count($mobile_token_value);

        if($k > 1000){
            $mobile_tokens = array_chunk($mobile_token_value, 1000);
            $j = count($mobile_tokens);

            for($i = 0; $i < $j; $i++){

                $this->iospushnotification5($app_api,$mobile_tokens[$i],$message,$app_name,$file_name);
            }

        } else {

            $this->iospushnotification5($app_api,$mobile_token_value,$message,$app_name,$file_name);

        }

        $query2 = "SELECT `tb`.*,`lbs`.`id` as `stud_id`,`lbs`.`notification_time`,`lbs`.`payment_status`,`lbs`.`duration`,`lbs`.`pickuptime_checked` FROM `tb_users` AS `tb` JOIN `lunch_box_student_info` AS `lbs` ON `tb`.`id` = `lbs`.`user_id` WHERE `lbs`.`notification_time` = '".$current_time."' AND `lbs`.`payment_status`= '1'  AND `lbs`.`pickuptime_checked`= '1' AND `tb`.`device`='android'";
        $pickuptime_android = \DB::select($query2);
        //print_r($pickuptime_android);    exit;

        $mobile_token_value = array();
        foreach($pickuptime_android as $pickuptime_android1){

            //$message = $pickuptime_android1->duration.'mins '.'left for pickup';
            $message = 'Few mins left for pickup';

            $mobile_token_value[] = $pickuptime_android1->mobile_token;
            $message = $message;
            $appapi_details	= $this->appapimethod(1);
            $app_name		= $appapi_details->app_name;
            $app_api 		= $appapi_details->api;

        }

        $mobile_tokens = array();
        $k = count($mobile_token_value);
        //print_r($k);
        if($k > 1000){
            $mobile_tokens = array_chunk($mobile_token_value, 1000);
            $j = count($mobile_tokens);
            //print_r($j);
            for($i = 0; $i < $j; $i++){
                //print_r($mobile_tokens[$i]);
                $this->pushnotification5($app_api,$mobile_tokens[$i],$message,$app_name,$file_name);
            }

        } else {
            //$j = count($mobile_token_value);
            //print_r($j);
            $this->pushnotification5($app_api,$mobile_token_value,$message,$app_name,$file_name);
        }



    }


    public function postServicebanners( Request $request){

        $response = array();

        $rules = array(
            'pin_code'	=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
            $region =  $area->region_id;

            $service_banners = \DB::table('service_banners')->where('region',$region)->where('status',1)->get();

            if(count($service_banners)>0){
                foreach($service_banners as $service_banner){

                    if($service_banner->banner_image != ''){
                        $bannerimage = \URL::to('').'/uploads/servicebanners/'.$service_banner->banner_image;
                    }else{
                        $bannerimage = \URL::to('').'/uploads/images/no-image.png';
                    }
                    $servicebanner[] = array(
                        "id"		=> (string)$service_banner->id,
                        "bannerimage"	=> $bannerimage,
                    );
                }
                $response["message"] 	= "Success";
                $response["servicebanner"] = $servicebanner;
            } else {
                $response["message"] 	= "Failure";
                $response["servicebanner"] = array();
            }
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= $error;
            echo json_encode($response); exit;
        }
    }


    public function postServiceform(Request $request) {

        $response = array();

        $rules1 = array(
            'user_id'		=>'required|numeric',
            'cust_id'		=>'required|numeric',
            'c_phone_number'=>'required|numeric',
            'email'			=>'required',
            'location'		=>'required',
            //'comments'		=>'required',
            'description'	=>'required',
            'service_charge'=>'required|numeric',
            'type'			=>'required',
            'pin_code'		=>'required|numeric',
            'start_time'	=>'required',
            'end_time'		=>'required'
        );

        $rules2 = array();
        if($request->type == "Relocation"){
            $rules2 = array(
                'to_location'	=>'required',
                'to_pin_code'	=>'required|numeric',
            );
        }
        $rules = array_merge($rules1,$rules2);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $currentDateTime = date("h:i:sa");
            $datetimestring = strtotime($currentDateTime); //6

            $endtime  = $request->end_time;
            $endtime1 = strtotime($endtime);  //5
            if($endtime1 >= $datetimestring){

                $val['user_id'] = $request->user_id;
                $val['cust_id'] = $request->cust_id;
                $val['c_phone_number'] = $request->c_phone_number;
                $val['email'] = $request->email;
                $val['to_location'] = $request->location;
                $val['comments'] = $request->comments;
                $val['description'] = $request->description;
                $val['category'] = $request->category;
                $val['subcategory'] = $request->subcategory;
                $val['subnode'] = $request->subnode;
                $val['service_charge'] = $request->service_charge;
                $val['type'] = $request->type;
                $val['location'] = $request->to_location;
                $val['to_pin_code'] = $request->pin_code;
                $val['pin_code'] = $request->to_pin_code;
                $val['start_time'] = $request->start_time;
                $val['end_time'] = $request->end_time;
                $val["date"] 	= date("Y-m-d h:i:sa");

                $pincodecheck1 = \DB::select("select * from `area` where `pin_code` = ".$request->pin_code);
                if($pincodecheck1) {

                    $area = \DB::table('area')->where('pin_code',$request->pin_code)->first();
                    $val['region'] =  $area->region_id;


                    if($request->file != ''){
                        $file = Input::file('file');
                        //$name = $file->getClientOriginalName();
                        $path = "/uploads/serviceform/";
                        $extension = Input::file('file')->getClientOriginalExtension();
                        $filename = rand(11111111, 99999999). '.' . $extension;

                        $request->file('file')->move(
                            base_path() . $path, $filename
                        );
                        $val['file'] = $path.$filename;
                    }else{
                        $val['file'] = '';
                    }

                    //$response["file"] 	= $val['file'];
                    //echo json_encode($response); exit;

                    if($request->service_charge == '0'){
                        $delivery_type  = "cod";
                        //$val['subscription_status'] = "1";
                    }else{
                        $delivery_type  = "ccavenue";
                    }

                    $val['delivery_type'] = $delivery_type;

                    $response["message"] 	= "Success";
                    $response["delivery_type"] 	= $delivery_type;
                    $ins=\DB::table('service_form')->insert($val);
                    $lastid = \DB::getPdo()->lastInsertId();

                    $order = 'SE'.$lastid;
                    $update = \DB::table('service_form')->where('id','=',$lastid)->update(['orderid'=>$order]);


                    $details = \DB::table('service_form')->where('id','=',$lastid)->get();
                    $response["details"] 	= $details;

                    if($delivery_type == 'ccavenue'){
                        $response['order_details'] 	= array(
                            "accessCode"		=> CCAVENUE_ACCESSCODE,
                            "merchantId"		=> CCAVENUE_MERCHANTID,
                            "orderId"			=> (string)$order,
                            "currency"			=> "INR",
                            "amount"			=> (string)$request->service_charge,
                            "redirectUrl"		=> \URL::to('').'/mobile/user/serviceccavresponsehandler',
                            "cancelUrl"			=> \URL::to('').'/mobile/user/serviceccavresponsehandler',
                            "rsaKeyUrl"			=> \URL::to('').'/ccavenue/GetRSA.php',
                            /*"billingName"		=> $billing_name,
										"billingAddress"	=> $billing_address,
										"billingZip"		=> $billing_zip,
										"billingCity"		=> $billing_city,
										"billingState"		=> $billing_state,*/
                            "billingCountry"	=> "India",
                            "billingMobilenumber"=> (string)$request->c_phone_number,
                            "billingEmail"		=> $request->email
                        );
                    }

                    echo json_encode($response); exit;

                }else{

                    $response["message"] 	= "Service not available for this region";
                    echo json_encode($response); exit;

                }	}else{

                $response["message"] 	= "Time over";
                echo json_encode($response); exit;
            }


        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            if(!empty($error)){
                if(isset($error['user_id'])){
                    $response['message'] = $error['user_id'][0];
                } else if(isset($error['cust_id'])){
                    $response['message'] = $error['cust_id'][0];
                } else if(isset($error['email'])){
                    $response['message'] = $error['email'][0];
                } else if(isset($error['c_phone_number'])){
                    $response['message'] = $error['c_phone_number'][0];
                } else if(isset($error['location'])){
                    $response['message'] = $error['location'][0];
                }else if(isset($error['description'])){
                    $response['message'] = $error['description'][0];
                }else if(isset($error['start_time'])){
                    $response['message'] = $error['start_time'][0];
                }else if(isset($error['end_time'])){
                    $response['message'] = $error['end_time'][0];
                }else if(isset($error['service_charge'])){
                    $response['message'] = $error['service_charge'][0];
                }else if(isset($error['type'])){
                    $response['message'] = $error['type'][0];
                }else if(isset($error['pin_code'])){
                    $response['message'] = $error['pin_code'][0];
                }else if(isset($error['to_location'])){
                    $response['message'] = $error['to_location'][0];
                }else if(isset($error['to_pin_code'])){
                    $response['message'] = $error['to_pin_code'][0];
                }
            }

            echo json_encode($response); exit;
        }

    }


    public function postServicemaincat( Request $request){

        $response = array();

        $rules = array(
            'pin_code'	=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $area = $this->locationdistance($request['pin_code']);
            $region =  $area[0]->region_id;
            /*$area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
			$region =  $area->region_id;*/

            if($request['lunchbox'] == 1){
                $servicecat[] = array(
                    "id"		=> (string)0,
                    "cat_name"	=> (string)"Lunch Box",
                    "cat_icon"	=> \URL::to('')."/abserve/assets/images/services/service-11.png",
                    "type"		=> "",
                    "node"		=> array(),
                );
            }

            $region_services = \DB::table('region_services')->where('region',$region)->first();

            //if(count($region_services)>0){
            if( $region_services > 0 ) {
                $tree = explode(",",$region_services->tree);
                $node = explode(",",$region_services->node);
                $sub_node = explode(",",$region_services->sub_node);

                $services_cat = \DB::table('service_categories')->where('level',0)->whereIn('id',$tree)->orderBy('cat_name', 'asc')->get();

                $i=0; $j=0;
                foreach($services_cat as $service_cat){
                    $cat_icon = "";
                    if($service_cat->cat_icon !=''){
                        $cat_icon = \URL::to('')."/uploads/service_cat_icon/".$service_cat->cat_icon;
                    }
                    $services_node = \DB::table('service_categories')->where('level',1)->where('cat_id',$service_cat->id)->whereIn('id',$node)->get();
                    $servicenode = array();

                    foreach($services_node as $service_node){
                        $_node = explode("-",$node[$i]);

                        $services_subnode = \DB::table('service_categories')->where('level',2)->where('cat_id',$service_node->id)->whereIn('id',$sub_node)->get();
                        $servicesubnode = array();
                        foreach($services_subnode as $service_subnode){
                            $sub_nodes = explode("-",$sub_node[$j]);

                            $servicesubnode[] = array(
                                "id"				=> (string)$service_subnode->id,
                                "subnode_cat_name"	=> (string)$service_subnode->cat_name,
                                "description"		=> (string)$service_subnode->description,
                                "buffer_time"		=> (string)$service_subnode->buffer_time,
                                "start_time"		=> (string)$sub_nodes[1],
                                "end_time"			=> (string)$sub_nodes[2],
                                "service_charge"	=> (string)$sub_nodes[3],
                            );

                            if($service_subnode->id==$sub_nodes[0]){ $j++; }
                        }

                        $servicenode[] = array(
                            "id"			=> (string)$service_node->id,
                            "node_cat_name"	=> (string)$service_node->cat_name,
                            "description"	=> (string)$service_node->description,
                            "buffer_time"	=> (string)$service_node->buffer_time,
                            "start_time"	=> (string)$_node[1],
                            "end_time"		=> (string)$_node[2],
                            "service_charge"=> (string)$_node[3],
                            "sub_node"		=> $servicesubnode,
                        );
                        if($service_node->id==$_node[0]){ $i++; }
                    }

                    $servicecat[] = array(
                        "id"		=> (string)$service_cat->id,
                        "cat_name"	=> (string)$service_cat->cat_name,
                        "cat_icon"	=> $cat_icon,
                        "type"		=> $service_cat->service_type,
                        "node"		=> $servicenode,
                    );
                }
                $response["message"] 	= "Success";
                $response["current_time"] 	= date("h:i:sa");
                $response["service_cat"] = $servicecat;
            } else {
                $response["message"] 	= "Failure";
                $response["service_cat"] = array();
            }
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= "Pincode field is required";
            $response["msg"] 		= $error;
            echo json_encode($response); exit;
        }
    }

    public function postServiceorders( Request $request){

        $response = array();

        $rules = array(
            'cust_id'	=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $service_orders = \DB::table('service_form')->where('cust_id',$request['cust_id'])->orderBy('id', 'desc')->get();

            //print_r($service_orders); exit;

            if(count($service_orders)>0){

                foreach($service_orders as $service_order){

                    $tree = \DB::table('service_categories')->select('cat_name')->where('id',$service_order->category)->first();
                    $node = \DB::table('service_categories')->select('cat_name')->where('id',$service_order->subcategory)->first();
                    $subnode = \DB::table('service_categories')->select('cat_name')->where('id',$service_order->subnode)->first();

                    if($service_order->order_amount == ''){
                        $service_order->order_amount = 0;
                    }

                    $total_amount = $service_order->service_charge + $service_order->order_amount;
                    $service_status = $service_order->service_status;
                    $subscription_status = $service_order->subscription_status;
                    if($service_order->delivery_type == "cod"){
                        if($service_status == 0){
                            $order_status = "Pending";
                        } elseif($service_status == 1){
                            $order_status = "Service Accepted";
                        } elseif($service_status == 2){
                            $order_status = "Service Rejected";
                        } elseif($service_status == 3){
                            $order_status = "Assign to Delivery Boy";
                        } elseif($service_status == 4){
                            $order_status = "Service Finished";
                        }
                    } else {
                        if($subscription_status = 0){
                            $order_status = "Payment Pending";
                        } elseif($subscription_status = 1){
                            if($service_status == 0){
                                $order_status = "Pending";
                            } elseif($service_status == 1){
                                $order_status = "Service Accepted";
                            } elseif($service_status == 2){
                                $order_status = "Service Rejected";
                            } elseif($service_status == 3){
                                $order_status = "Assign to Delivery Boy";
                            } elseif($service_status == 4){
                                $order_status = "Service Finished";
                            }
                        } elseif($subscription_status = 2){
                            $order_status = "Aborted";
                        } elseif($subscription_status = 3){
                            $order_status = "Failure";
                        } elseif($subscription_status = 4){
                            $order_status = "Cancelled";
                        }
                    }

                    $serviceorders[] = array(
                        "order_id"				=> (string)$service_order->id,
                        "location"				=> (string)$service_order->to_location,
                        "to_location"			=> (string)$service_order->location,
                        "comments"				=> (string)$service_order->comments,
                        "type"					=> (string)$service_order->type,
                        "order_instruction"		=> (string)$service_order->order_instruction,
                        "current_time"			=> (string)date("h:i:sa"),
                        "start_time"			=> (string)$service_order->start_time,
                        "end_time"				=> (string)$service_order->end_time,
                        "tree"					=> (string)$tree->cat_name,
                        "node"					=> (string)$node->cat_name,
                        "subnode"				=> (string)$subnode->cat_name,
                        "service_charge"		=> (string)$service_order->service_charge,
                        "order_amount"			=> (string)$service_order->order_amount,
                        "total_amount"			=> (string)$total_amount,
                        "delivery_type"			=> (string)$service_order->delivery_type,
                        "order_status"			=> (string)$order_status,
                        "created_date"			=> (string)$service_order->date,
                    );
                }
                $response["message"] 	= "Success";
                $response["service_orders"] = $serviceorders;
            } else {
                $response["message"] 	= "No records found.";
                $response["service_orders"] = array();
            }
            echo json_encode($response); exit;

        } else {
            $messages 				= $validator->messages();
            $error 					= (array)$messages->getMessages();
            $response["message"] 	= "The cust id field is required";
            $response["msg"] 		= $error;
            echo json_encode($response); exit;
        }
    }

    public function getNeworderresnotification( Request $request){
        //echo "test"; exit;
        $currentDateTime = date("Y-m-d h:i:s A");
        $datetimestring = strtotime($currentDateTime);
        $before_one_min = $datetimestring-(57*1);

        $orders = \DB::table('abserve_order_details')->select('id','res_id','res_notification_flag')->where('time', '<=', $before_one_min)->where('status','=','0')->where('res_notification_flag','=','0')->get();

        //Restaurant Notification
        $appapi_details	= $this->appapimethod(2);
        $app_name		= $appapi_details->app_name;
        $app_api 		= $appapi_details->api;

        foreach($orders as $order){
            $sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$order->res_id;
            $ab_cu 	= \DB::select($sql2);

            $device_tokens = \DB::table('user_mobile_tokens')->where('user_id',$ab_cu[0]->partner_id)->get();
            $message = "New orders found in your restaurant";
            $flag = array();
            foreach($device_tokens as $device_token){
                #$flag[] = $this->pushnotification($app_api,$device_token->device_token,$message,$app_name);
                $flag[] = $this->pushNotificationRestaurantOrder($device_token->device_token);

            }

            if(in_array("1", $flag)){
                //echo $responseData['status'];
                $flag = \DB::table('abserve_order_details')->where('id','=',$order->id)->update(array('res_notification_flag' => '1'));
            }
        }

    }


    public function CustomerPushNotification($message, $token){

        $app_api = 'AIzaSyBolzOUPWBcMTyF1fSjzKPHwnn4Te0lUIo';
        $app_name = 'Customer App';
        define('API_ACCESS_KEY', $app_api);
        $registrationIds = [$token];

        // prep the bundle
        $msg = array
        (
            'message' 	=> $message,
            'body'		=> $message,
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
            'data'				=> $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close($ch);

        $res = json_decode($result);

        //Getting value from success
        $flag = $res->success;
        return $flag;
    }


}


<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Http\Controllers\PayPalRefund;
use App\Http\Controllers\lib\Twocheckout;
use Omnipay\TwoCheckout\Gateway;
use App\Models\Payment;
use App\User;
use App\Models\Restaurant;
use App\Models\Partnertransac;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use Omnipay\Omnipay;
use Session, DB, DateTime, Auth;
use App\Library\PaypalComponent;		
use App\Library\paypal;
require_once(app_path('Http/Controllers/stripe/Stripe.php'));


class OmniPaymentController extends Controller {

	public function paypalValues() {
		$values = \DB::table('abserve_paypal_settings')->select('*')->first();
		return $values;
	}

	public function postPayment() {	//Paypal
		if(\Auth::check()){
			$orders			= $this->paymentorder($_POST['address_id'],"paypal");
			$order_details	= \DB::table('abserve_order_details')->select('*')-> where("id",'=',$orders['id'])->get();

			$params = array(
				'cancelUrl'		=> url().'/payment/cancelorder',
				'returnUrl'		=> url().'/payment/paymentsuccess', 
				'name'			=> 'New order',
				'description'	=> 'description', 
				'amount'		=> $order_details[0]->grand_total,
				'currency'		=> 'USD',
				'order_id'		=> $orders['id'],
				'partner_id'	=> $orders['partner_id']
			);

			Session::put('params', $params);
			Session::save();
			$Paypal		= $this->paypalValues();
			$gateway	= Omnipay::create('PayPal_Express');
			$gateway->setUsername($Paypal->username);
			$gateway->setPassword($Paypal->password);
			$gateway->setSignature($Paypal->signature);
			$gateway->setTestMode($Paypal->url_setting);

		  	$response = $gateway->purchase($params)->send();
		  	if ($response->isSuccessful()) {
				// payment was successful: update database
		  		print_r($response);
		  	} elseif ($response->isRedirect()) {
				// redirect to offsite payment gateway
		  		$response->redirect();
		  	} else {
				// payment failed: display message to customer
		  		echo $response->getMessage();
		  	}
		} else {
			return Redirect::to('user/login');
		}
	}	

	public function postTwocheckout(){//checkout

			$orders = $this->paymentorder($_POST['address_id'],"2checkout");   

			$grand_total = \DB::select("SELECT `grand_total` from abserve_order_details where `abserve_order_details`.`id`='".$orders['id']."'");       

			$params = array(
			'order_id' 	=> $orders['id'],
			'partner_id' => $orders['partner_id'],
			'total' => $grand_total[0]->grand_total
				
			);

        	Session::put('order', $params);
        	Session::save(); 

        	$data['order_id'] = $orders['id'];

        	$data['total'] = $grand_total[0]->grand_total;

        	echo json_encode($data);

           // echo $orders['id'];

		 exit;

	}



	public function paymentorder($address_id,$paytype) {
		$address = \DB::table('abserve_user_address')->select('*')->where("id",'=',$address_id)->get();
		if(!empty($address)){
			$address_location =$address[0]->address;
			$landmark =$address[0]->landmark;
			$building =$address[0]->building;
		}
		$user_id = Auth::user()->id;
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
		$food_item ="";
		$food_id ="";
		$total =0;
		foreach ($foods_items as $key => $value) {
			if($food_item==""){
				$food_item = $value->food_item;
				$food_id = $value->food_id;
				$food_quantity = $value->quantity;
				$food_price = $value->price;
			} else {
				$food_item .= ",".$value->food_item;
				$food_id .= ",".$value->food_id;
				$food_quantity .=",".$value->quantity;
				$food_price.= ",".$value->price;
			}
			$total	= $total + ($value->price * $value->quantity);
			$res_id	= $value->res_id;
		}
		$res_charge = \DB::table('abserve_restaurants')->select('delivery_charge')->where('id',$res_id)->first();
		$del_charge = $res_charge->delivery_charge;
		$grand_total = $total + $del_charge;

		$data =array('cust_id'=>$user_id,'total_price'=>$total,'s_tax'=>$del_charge,'coupon_id'=>0,'grand_total'=>$grand_total,'res_id'=>$res_id,'address'=>$address_location,'building'=>$building,'landmark'=>$landmark,'food_item'=>$food_item,'food_id'=>$food_id,'quantity'=>$food_quantity,'price'=>$food_price,'coupon_price'=>'');
		$in_data = $data;
		$aFields = array('cust_id','total_price','s_tax','coupon_id','coupon_price','grand_total','res_id','address','building','landmark');
		$cust_id = $user_id;
		$removepost=DB::table('abserve_user_cart')->where('user_id', '=', $user_id)->delete();
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
		$keys[]='delivery';
		$vals[]='unpaid';
		$keys[]='delivery_type';
		$vals[]=$paytype;
		$details_ins = (array_combine($keys, $vals));
		\DB::table('abserve_order_details')->insert($details_ins);
		$oid = \DB::getPdo()->lastInsertId();
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
		$ins_val = $in_data;
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
		$var 	= $res_id;
		$sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$var;
		$ab_cu 	= \DB::select($sql2);
		\DB::table('abserve_orders_customer')->insert($ins);
		\DB::table('abserve_orders_partner')->insert($pre);
		\DB::table('abserve_orders_partner')
		->where('orderid', $oid)
		->update(['partner_id' => $ab_cu[0]->partner_id]);
		$this->data['id'] = $oid;
		$this->data['partner_id'] = $ab_cu[0]->partner_id;
		return $this->data;
	}
	
	public function getCancelorder()
	{
		$params = Session::get('params');
		\DB::select("UPDATE `abserve_order_details` SET `status`= '5' WHERE `abserve_order_details`.`id` ='".$params['order_id']."'");	
	
		return Redirect::to(url());

		/*$user_id		= Session::get('uid');
		$room_id		= $_POST['room_id'];
		$status			= $_POST['status'];
		$transaction_id = $_POST['transaction_id'];
		$get_price		= $_POST['room_prize'];
		$room_prize		= number_format((float)$get_price, 2, '.', '');		
		if($status == '0')
		{
			// print_r("expression");exit();
			\DB::statement("UPDATE `abserve_orders` SET `status` = 2 WHERE `id` = ".$room_id." AND `user_id` = ".$user_id);
			echo "Success";
		}
		if($status == '1')
		{
			//refund only transaction completed
			$aryData['transactionID'] 	= $transaction_id;
			$aryData['refundType'] 		= "Full"; //Partial or Full
			$aryData['currencyCode'] 	= "USD";
			$aryData['amount'] 			= $room_prize;
			$aryData['memo'] = "There Memo Detail entered for Partial Refund";
			$ref = new PayPalRefund("sandbox");
			$aryRes = $ref->refundAmount($aryData);
			// print_r($aryRes);exit();
			if($aryRes['ACK'] == "Success"){
				\DB::statement("UPDATE `abserve_orders` SET `status` = 2 WHERE `id` = ".$room_id." AND `user_id` = ".$user_id);
				// $status_update = \DB::table('abserve_orders')->where('id', $room_id)->where('user_id', $user_id)->update(['status' => 2]);
				$values = array('check_in_time'=>'','check_out_time'=>'');
				\DB::table('abserve_rooms')->where('room_id', $room_id)->update($values);
				echo $aryRes['ACK'];
			} else {
				echo $aryRes['L_LONGMESSAGE0'];
			}
		}*/
	}

	public function getPaymentsuccess()
  	{

		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername('jambulingam-business-us_api1.gmail.com');
		$gateway->setPassword('TS7NKH2H7FBNV46C');
		$gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31ACLXnJYTapj4v820AqE2FhH6UzI9');
		$gateway->setTestMode(true);
      	
		$params = Session::get('params');
		$params['token']=$_GET['token'];

  		$response = $gateway->completePurchase($params)->send();
  		$paypalResponse = $response->getData(); // this is the raw response object
  	
  		//echo "<pre>dsfdsdf";print_r($paypalResponse);exit;
  		if(isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
         	$updated = \DB::select("UPDATE `abserve_order_details` SET `delivery`='paid' WHERE `abserve_order_details`.`id` ='".$params['order_id']."'");  
        	/*Partner Commission*/
			$order_details = \DB::table('abserve_order_details')->select('*')->where('id','=',$params['order_id'])->first();
			$cart_tot = $order_details->grand_total;
			$resInfo = Restaurant::find($order_details->res_id);
			$pid = $resInfo->partner_id;
			$partnerInfo = User::find($pid);
			$comm_per = $partnerInfo->commission;
			if($comm_per !=  0 && $comm_per != '') {
				$commission = number_format((float)($cart_tot * ($comm_per / 100)),2,'.','');
				$part_amount = number_format((float)($cart_tot - $commission),2,'.','');
			} else {
				$commission = number_format((float)($cart_tot * 0.05),2,'.','');
				$part_amount = number_format((float)$cart_tot - $commission,2,'.','');
			}
			$pbal = \DB::table('abserve_partner_balance')->where('partner_id',$pid)->first();
			if(count($pbal) > 0 ) {
				$pre_bal = $pbal->balance;
				$balance	= number_format((float)($pre_bal + $part_amount),2,'.','');	
				\DB::table('abserve_partner_balance')->where('partner_id',$pid)->update(array('balance'=>$balance));
			} else {
				$part_bal['partner_id'] = $pid;
				$part_bal['balance'] 	= $part_amount;
				\DB::table('abserve_partner_balance')->insert(array($part_bal));
			}

	      	/* \DB::table('abserve_payments')->insert([
	            'partner_id' => $params['partner_id'] , 
	            'user_id' => \Auth::user()->id,
	            'order_id' => $params['order_id'],
	            'through' => 'paypal' ,	         
	            'amount' => $params['amount'],
	            'book_time' => date("Y-m-d H:i:s")		          
	         
	          ]);*/
			return Redirect::to('/payment/thankyouorder');
		} else {
			echo 'transaction failed';
		}
	}		

  	public function postCancel()
  	{
  		return redirect('user/login');
  	}

  	public function postCheckoutpayment() {  
 		$params= $_REQUEST;
 		$hashSecretWord = 'YjA2YTU4ZWItYTg5MS00NjU3LTlkMjQtYWNhMjExYjMzNTI1'; //2Checkout Secret Word
		$hashSid = 901350888; //2Checkout account number
		$hashTotal = $params['total']; //Sale total to validate against
		$hashOrder =$params['order_number']; //2Checkout Order Number
	        
        $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
		if ($StringToHash != $_REQUEST['key']) {
			$result = 'Fail - Hash Mismatch'; 
			return false;
		} else { 
			$result = 'Success - Hash Matched';
			$order_det = Session::get('order');
			\DB::select("UPDATE `abserve_order_details` SET `delivery`='paid' WHERE `abserve_order_details`.`id` ='".$order_det['order_id']."'");
            /*admin Commission*/
			$order_details = \DB::table('abserve_order_details')->select('*')->where('id','=',$order_det['order_id'])->first();
			$cart_tot = $order_details->grand_total;
			$resInfo = Restaurant::find($order_details->res_id);
			$pid = $resInfo->partner_id;
			$partnerInfo = User::find($pid);
			$comm_per = $partnerInfo->commission;
			if($comm_per !=  0 && $comm_per != '') {
				$commission = number_format((float)($cart_tot * ($comm_per / 100)),2,'.','');
				$part_amount = number_format((float)($cart_tot - $commission),2,'.','');
			} else {
				$commission = number_format((float)($cart_tot * 0.05),2,'.','');
				$part_amount = number_format((float)$cart_tot - $commission,2,'.','');
			}
			$pbal = \DB::table('abserve_partner_balance')->where('partner_id',$pid)->first();
			if(count($pbal) > 0 ) {
				$pre_bal = $pbal->balance;
				$balance	= number_format((float)($pre_bal + $part_amount),2,'.','');	
				\DB::table('abserve_partner_balance')->where('partner_id',$pid)->update(array('balance'=>$balance));
			} else {
				$part_bal['partner_id'] = $pid;
				$part_bal['balance'] 	= $part_amount;
				\DB::table('abserve_partner_balance')->insert(array($part_bal));
			} 
			/*admin commission end*/ 
			/*   $grand_total = \DB::select("SELECT `grand_total` from abserve_order_details where `abserve_order_details`.`id`='".$order_det['order_id']."'");   */

			/*	 \DB::table('abserve_payments')->insert([
		            'partner_id' => $order_det['partner_id'] , 
		            'user_id' => \Auth::user()->id,
		            'order_id' => $order_det['order_id'],
		            'through' => '2checkout' ,	 
		            'amount' =>  $order_det['total'],       	           
		            'book_time' => date("Y-m-d H:i:s")		          
		         
		          ]);   */
             
            return Redirect::to('/payment/thankyouorder');
		}
	} 

	public function getThankyouorder() {
		$this->data['pages'] 		= 'frontend.thankyou';
		$page = 'layouts.'.CNF_THEME.'.index';
		return view($page, $this->data);
   }

    public function postDelivery(){
    	if(\Auth::check()){
			$address_id = $_POST['address_id']; 

			$address = \DB::table('abserve_user_address')->select('*')->where("id",'=',$address_id)->get();

			if(!empty($address)){

			$address_location =$address[0]->address;
			$landmark =$address[0]->landmark;
			$building =$address[0]->building;

			}

			$user_id = Auth::user()->id;

			$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
			$food_item ="";
			$food_id ="";
			$total =0;

			// echo "<pre>";print_r($foods_items);
			foreach ($foods_items as $key => $value) {
				if($food_item=="") {
					$food_item = $value->food_item;
					$food_id = $value->food_id;
					$food_quantity = $value->quantity;
					$food_price = $value->price;
				} else {
					$food_item .= ",".$value->food_item;
					$food_id .= ",".$value->food_id;
					$food_quantity .=",".$value->quantity;
					$food_price.= ",".$value->price;
				}
				$total	= $total + ($value->price * $value->quantity);
				$res_id	= $value->res_id;
			}


			$res_charge = \DB::table('abserve_restaurants')->select('delivery_charge')->where('id',$res_id)->first();
			$del_charge = $res_charge->delivery_charge;

			$grand_total = $total + $del_charge;
			/*echo "<pre>";
			print_r($s_tax);echo "<br>";
			print_r($total);echo "<br>";
			print_r($grand_total);exit();*/

			$data =array('cust_id'=>$user_id,'total_price'=>$total,'s_tax'=>$del_charge,'coupon_id'=>0,'grand_total'=>$grand_total,'res_id'=>$res_id,'address'=>$address_location,'building'=>$building,'landmark'=>$landmark,'food_item'=>$food_item,'food_id'=>$food_id,'quantity'=>$food_quantity,'price'=>$food_price,'coupon_price'=>'');

			$in_data = $data;


			/*	$data 	=	$_REQUEST;*/
			/*	unset($data['user_id']);*/
			$aFields = array('cust_id','total_price','s_tax','coupon_id','coupon_price','grand_total','res_id','address','building','landmark');

	 		$cust_id = $user_id;

			$removepost=DB::table('abserve_user_cart')->where('user_id', '=', $user_id)->delete();
		
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

				 
				 $keys[]='delivery';
				 $vals[]='on_delivery';

				$keys[]='delivery_type';
			    $vals[]='cash on delivery';

				$details_ins = (array_combine($keys, $vals));

			    \DB::table('abserve_order_details')->insert($details_ins);
				$oid = \DB::getPdo()->lastInsertId();

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


	          
	            $ins_val = $in_data;

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

				$var 	= $res_id;
			
				$sql2	= "SELECT `partner_id` FROM `abserve_restaurants` WHERE `id`=".$var;
				$ab_cu 	= \DB::select($sql2);

				\DB::table('abserve_orders_customer')->insert($ins);
				\DB::table('abserve_orders_partner')->insert($pre);

				\DB::table('abserve_orders_partner')
				->where('orderid', $oid)
				->update(['partner_id' => $ab_cu[0]->partner_id]);

				 return Redirect::to('/payment/thankyouorder'); 
			} else {
				return Redirect::to('user/login');
			}   
		}

	public function postPaypaltransaction(Request $request) { 
		//echo '<pre>';print_r($request);exit;
		$ptransid = $request->ptransid;
		$req_amount = $_POST['req_amount'];
		$acc_email = trim($_POST['account_email']);
		$id = $_POST['hidden_id'];
		/*$available = DB::select("SELECT `balance` FROM `abserve_partner_balance` WHERE `partner_id` = ".$id);
		$available_balance = $available[0]->balance;
		$paypal = new Paypal();
		$result = $paypal->withdraw($acc_email,$req_amount,$id); 
		if($result['ACK']=='Success')
		{
			$trans_no = rand();
			$today_date = date("Y-m-d");
			$trans_thru ='pay pal';
			$new_bal=0;
			$new_bal = $available_balance-$req_amount;
			DB::table('abserve_partner_balance')
			->where('partner_id', $id)
			->update(['balance' => $new_bal]);
			$insert_mywallet = "INSERT INTO `abserve_partner_wallet` (`transaction_id`,`partner_id`,`transac_through`,`transaction_amount`,`trans_date`) VALUES ('".$trans_no."','".$id."','".$trans_thru."','".$req_amount."','".$today_date."')";
			$query  =  \DB::insert($insert_mywallet);
			return Redirect::to('partnertransac')->with('messagetext','Transfered Successfully')->with('msgstatus','success');
		}elseif ($result['ACK']=='Failure') {
			return Redirect::to('partnertransac')->with('messagetext','Sorry Not able to transfered')->with('msgstatus','error');
		}*/
		$error_msg='';
		$abserve_stripe_settings 	=  \DB::select("SELECT * FROM `abserve_stripe_settings` ");
		\Stripe\Stripe::setApiKey($abserve_stripe_settings[0]->private_key);
		$host=$_POST['hidden_id'];
		$tra_amt=(($_POST['req_amount'])-($_POST['req_amount']*(5/100)));
		$tra_amt1=number_format($tra_amt, 2, '.', '');
		$amt=($tra_amt1)*100;
		//$amt=($_POST['amount'])*100;
		$ac=\DB::table('tb_users')->select('ext_acc_id')->where('id',$host)->first();
		$acc_id=$ac->ext_acc_id;
		if($acc_id=='')
			$error_msg.='Bank account details not created. ';
		if($amt<1)
			$error_msg.='Minimum Request amount is &euro; 1. ';
		if($acc_id!='' and $amt>=1)
		{
			$v=\Stripe\Balance::retrieve();
			$bal_amt=0;
			foreach($v->available as $key => $vv)
			{
				$currency=$v->available[$key]['currency'];
				if($currency=="eur")
					$bal_amt=($v->available[$key]['amount']);
			}
			//echo $bal_amt;
			if($bal_amt>=$amt)
			{
				$trans=\Stripe\Transfer::create(array(
				  "amount" => $amt,
				  "currency" => "eur",
				  "destination" => $acc_id,
				  "transfer_group" => "Group_Host_".$host
				));
				//print_r($trans);
				$trans_id=$trans->id;

				$data=Array ( 
				  'host_id'				=> $host,
				  'amount'				=> $req_amount,
				  'status' 				=>'Completed',
				  'trans_id'			=>$trans_id,
				  'transfered_amount'	=>$tra_amt1
				  );
				$id = \DB::table('abserve_host_transfer')->insertGetId(array($data));
				$old_pbal = \DB::table('abserve_partner_balance')->where('partner_id',$host)->first();
				$old_bal = $old_pbal->balance;
				if($old_bal <= $req_amount)
					$newBal = $req_amount - $old_bal;
				elseif($old_bal > $req_amount)
					$newBal = $old_bal - $req_amount;
				\DB::table('abserve_partner_balance')->where('partner_id',$host)->update(array('balance'=>$newBal));

			}
			else
			{
				$error_msg.='You have only &euro; '.($bal_amt/100).' in your account';
			}
			
		}
		if($trans_id!='')
		{
			//return Redirect::to('transactionrequest?return=')->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
		}
		else
		{
			$error_msg.='Your Transaction is failed.';
			//return Redirect::to('transactionrequest/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus',$error_msg);
		}
		$return = 'partnertransac/show/'.$ptransid.'?return='.self::returnUrl();
		return Redirect::to($return)->with('messagetext',$error_msg)->with('msgstatus','success');
	}
}
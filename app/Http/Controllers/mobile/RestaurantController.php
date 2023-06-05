<?php namespace App\Http\Controllers\mobile;

use App\Http\Controllers\controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect,RecursiveIteratorIterator,RecursiveArrayIterator ; 
use DB;


class RestaurantController extends Controller {

	public $module = 'restaurant';
	
	public function cuisines($id=''){

		$cname	= \DB::select("SELECT GROUP_CONCAT(name ORDER BY field(id, ".$id.")) as name FROM abserve_food_cuisines where id IN (".$id.")");
		if($cname){
			return $cname[0]->name;
		} else {
			return '';
		}
	}
	
	public function iospushnotification($app_api,$mobile_token,$message,$message1,$app_name){	 
		 
		define( 'API_ACCESS_KEY1', $app_api );
		
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
		//print_r($res);
				
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;
		
	}
	
	public function pushnotification($app_api,$mobile_token,$message,$app_name){	 
		 
		define( 'API_ACCESS_KEY', $app_api );
		
		$registrationIds = [$mobile_token];

		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> 'Message from '.$app_name,
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
		curl_close( $ch );

		//Decoding json from result 
		$res = json_decode($result);
		//print_r($res);
		
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;		
		
	}

	public function calculate_dist($from,$to){
		$ch		= curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/distancematrix/json?origins='.urlencode($from).'&destinations='.urlencode($to).'&mode=drive&sensor=false'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output	= curl_exec($ch);
		curl_close($ch);

		$rwes	= json_decode($output);
		if($rwes->status == "OK"){
			$distance = $rwes->rows[0]->elements[0]->distance->text;
		} else {
			$distance = 0;
		}
		if($distance != 0){
			$rdis = explode(' ', $distance);
			if($rdis[1] == 'm'){
				$result = ceil($rdis[0]) * 0.001;
			} else {
				$result = ceil($rdis[0]);
			}
		} else {
			$result = $distance;
		}
		return $result;
	}
	
	public function locationdistance( $pin_code = ''){   

		//$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();
		$area = \DB::select("SELECT `distance`,`region_id`,`region_keyword` FROM `area` WHERE `pin_code`=".$pin_code);

		return $area;
	}

	public function address($lat,$lng){
		$ch		= curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output	= curl_exec($ch);
		curl_close($ch);

		$data	= json_decode($output);
		if($data->status=="OK"){
			$address = $data->results[0]->formatted_address;
		} else {
			$address = false;
		}
		return $address;
	}

	public function getRestaurantresults( Request $request){
		
		$_REQUEST = str_replace('"','', $_REQUEST);

		$radius = 15;
		$whr	= "WHERE 1 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);

		$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond);

		foreach ($restaurants as $key => $value) {
			$dist = $this->calculate_dist($from,$value->location);
			if(!(isset($_REQUEST['location']) != '')){
				if($dist <= $radius){
					$res_restaurnts[] = $value;
				}
			} else {
				$res_restaurnts[] = $value;
			}
		}
		
			foreach ($res_restaurnts as $key => &$value) {
				if($value->logo != ''){
					$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
				} else {
					$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				if($value->cuisine != ''){
					$value->cuisine = $this->cuisines($value->cuisine);
				}
				foreach ($rat_res as $ky => $uid) {
					if($value->id == $uid->res_id){
						$value->rating = $uid->rating;
					}
				}
			}


		$response["message"]				= (array)"success";
		if($eoffer){
			$response["Offers"][0]['offer']	= "Offer's Found";
		}else{
			$response["Offers"][0]['offer']	= "Offer's Not Found";
		}

		if(isset($_REQUEST['user_id']) != ''){
			$user_id	= $_REQUEST['user_id'];
			$card		= "SELECT  SUM(`quantity`) as total_items ,`ae`.`res_id`,`ai`.`name` FROM `abserve_user_cart` as `ae` JOIN `abserve_restaurants` as `ai` on `ai`.`id`=`ae`.`res_id` WHERE `ae`.`user_id`=".$_REQUEST['user_id'];
			$tb_cu =\DB::SELECT($card);
		} else {
			$tb_cu = [0 => ['total_items'=>'','res_id'=>'','name'=>'']];
		}

		$response["restaurants"]	= $res_restaurnts;
		$response['cart_details']	= $tb_cu;
	   	echo "<pre>";print_r($response);exit();
		echo json_encode($response); exit;
		
	}	
	
	public function postRestaurantresults( Request $request){ 
	
		$rules = array(
			'lat'=>'required',
			'lang'=>'required'
		);
		
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			$_REQUEST = str_replace('"','', $_REQUEST);
	
			$radius = 15;
			$whr	= "WHERE 1 AND active!=2 ";
	
			if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
				$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
				$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
				$select = '*'.$lat_lng;
				$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
				if($offer){
					$eoffer = true;
				} else {
					$eoffer = false;
				}
				$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
			} else {
				$lat_lng = $hav	= '';
				$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
				$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
				$from = $_REQUEST['location'];
			}
	
			if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
				$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
			}
	
			/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
				$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
			}*/
	
			if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
				$whr .= " AND `offer` <> 0 ";
			}
	
			if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
				$cond = " ORDER BY `delivery_time`";
			}
			//echo "SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav; exit;
	
			$res_restaurnts = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);
			
			//$restaurants = \DB::select("SELECT *  FROM `abserve_restaurants` ");
	
			//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond);
			//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`user_id` as cust_id,`ar`.`res_id` from `abserve_food_reviews` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
	
			/*foreach ($restaurants as $key => $value) {
				$value->available_status = \SiteHelpers::gettimeval($value->id);
				if($_REQUEST['available'] == 'yes'){
				  if($value->available_status!='0'){
					$dist = $this->calculate_dist($from,$value->location);
					if(!(isset($_REQUEST['location']) != '')){
						if($dist <= $radius){
							$res_restaurnts[] = $value;
						}
					} else {
						$res_restaurnts[] = $value;
					}
				  }
				}else{
				  $dist = $this->calculate_dist($from,$value->location);
				  if(!(isset($_REQUEST['location']) != '')){
					  if($dist <= $radius){
						  $res_restaurnts[] = $value;
					  }
				  } else {
					  $res_restaurnts[] = $value;
				  }
				}
				
			}*/
			
			if(count($res_restaurnts)>0){
				
				$current_date = strtotime(date("Y-m-d"));
			
				foreach ($res_restaurnts as $key => &$value) {
					
					$dist = round($value->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($value->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($value->delivery_time) + (75);
					}
					
					$value->id = (string)$value->id;
					$value->partner_id = (string)$value->partner_id;
					$value->phone = (string)$value->phone;
					$value->secondary_phone_number = (string)$value->secondary_phone_number;
					$value->service_tax = (string)$value->service_tax;
					$value->max_packaging_charge = (string)$value->max_packaging_charge;
					$value->delivery_charge = (string)$value->delivery_charge;
					$value->vat = (string)$value->vat;
					$value->call_handling = (string)$value->call_handling;
					$value->delivery_time = (string)$delivery_time;
					$value->pure_veg = (string)$value->pure_veg;
					$value->offer = (string)$value->offer;
					$value->min_order_value = (string)$value->min_order_value;
					$value->max_value = (string)$value->max_value;
					$value->budget = (string)$value->budget;
					$value->entry_by = (string)$value->entry_by;
					$value->latitude = (string)$value->latitude;
					$value->longitude = (string)$value->longitude;
					$value->active = (string)$value->active;					
					$value->distance = (string)$value->distance;
					$value->available_status = (string)$value->available_status;
					
					$offer_to = strtotime($value->offer_to);
					if($current_date > $offer_to){
						$value->offer_from = "0000-00-00";
						$value->offer_to = "0000-00-00";
						$value->offer = "0";
					}
			
					if($value->active == 1){
						$value->available_status = \SiteHelpers::getrestimeval($value->id);
						if($value->available_status == 1){
							$value->res_status = "open";
						} else {
							$value->res_status = "close";
						}
					} else {
						$value->res_status = "close";
					}
					
					if($value->logo != ''){
						$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
					} else {
						$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($value->cuisine != ''){
						$value->cuisine = $this->cuisines($value->cuisine);
					}
					
					$value->rating = "";
					$rating = "";
					
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$value->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($value->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$value->rating = round(($rating/$reviews_count), 1);
					} else {
						$value->rating = 5;
					}
				}
				
				$response["message"]				= "success";
				if($eoffer){
					$response["Offers"][0]['offer']	= "Offer's Found";
				}else{
					$response["Offers"][0]['offer']	= "Offer's Not Found";
				}
		
				if(isset($_REQUEST['user_id']) != ''){
					$user_id	= $_REQUEST['user_id'];
					$card		= "SELECT  SUM(`quantity`) as total_items ,`ae`.`res_id`,`ai`.`name` FROM `abserve_user_cart` as `ae` JOIN `abserve_restaurants` as `ai` on `ai`.`id`=`ae`.`res_id` WHERE `ae`.`user_id`=".$_REQUEST['user_id'];
					$tb_cu =\DB::SELECT($card);
				} else {
					$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				}
		
				$response["restaurants"]	= $res_restaurnts;
				$response['cart_details']	= $tb_cu;
			} else {
				$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				
				$response["message"]			= "failure";
				$response["Offers"][0]['offer']	= "Offer's Not Found";			
				$response["restaurants"]		= "";
				$response['cart_details']		= $tb_cu;
			}
			
			echo json_encode($response); exit;
		}
		else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postRestaurantresults1( Request $request){ 
	
		$rules = array(
			'lat'=>'required',
			'lang'=>'required'
		);
		
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			$_REQUEST = str_replace('"','', $_REQUEST);
			
			$area = $this->locationdistance($_REQUEST['pin_code']);
			//print_r($area);
			//echo $area[0]->distance; exit;
			if(count($area)>0){
				$radius = $area[0]->distance;
				$region_id = $area[0]->region_id;
			} else {
				$radius = 0;
				$region_id = "";
			}
			
			if($region_id != ""){
				if($_REQUEST['user_id'] !=''){
					$region_update = \DB::table('tb_users')->where('id', $_REQUEST['user_id'])->update(['region' => $region_id]);
				}
			}
			
			$whr	= "WHERE 1 AND active!=2 ";
	
			if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
				$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
				$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
				$select = '*'.$lat_lng;
				$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
				if($offer){
					$eoffer = true;
				} else {
					$eoffer = false;
				}
				$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
			} else {
				$lat_lng = $hav	= '';
				$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
				$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
				$from = $_REQUEST['location'];
			}
	
			if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
				$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
			}
	
			/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
				$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
			}*/
	
			if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
				$whr .= " AND `offer` <> 0 ";
			}
	
			if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
				$cond = " ORDER BY `delivery_time`";
			}
			//echo "SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav; exit;
	
			$res_restaurnts = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);
			
			//$restaurants = \DB::select("SELECT *  FROM `abserve_restaurants` ");
	
			//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond);
			//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`user_id` as cust_id,`ar`.`res_id` from `abserve_food_reviews` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
	
			/*foreach ($restaurants as $key => $value) {
				$value->available_status = \SiteHelpers::gettimeval($value->id);
				if($_REQUEST['available'] == 'yes'){
				  if($value->available_status!='0'){
					$dist = $this->calculate_dist($from,$value->location);
					if(!(isset($_REQUEST['location']) != '')){
						if($dist <= $radius){
							$res_restaurnts[] = $value;
						}
					} else {
						$res_restaurnts[] = $value;
					}
				  }
				}else{
				  $dist = $this->calculate_dist($from,$value->location);
				  if(!(isset($_REQUEST['location']) != '')){
					  if($dist <= $radius){
						  $res_restaurnts[] = $value;
					  }
				  } else {
					  $res_restaurnts[] = $value;
				  }
				}
				
			}*/
			
			if(count($res_restaurnts)>0){
				
				$current_date = strtotime(date("Y-m-d"));
			
				foreach ($res_restaurnts as $key => &$value) {
					
					$resId[] = $value->id;
					
					$dist = round($value->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($value->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($value->delivery_time) + (75);
					}
					
					$value->id = (string)$value->id;
					$value->partner_id = (string)$value->partner_id;
					$value->phone = (string)$value->phone;
					$value->secondary_phone_number = (string)$value->secondary_phone_number;
					$value->service_tax = (string)$value->service_tax;
					$value->max_packaging_charge = (string)$value->max_packaging_charge;
					$value->delivery_charge = (string)$value->delivery_charge;
					$value->vat = (string)$value->vat;
					$value->call_handling = (string)$value->call_handling;
					$value->delivery_time = (string)$delivery_time;
					$value->pure_veg = (string)$value->pure_veg;
					$value->offer = (string)$value->offer;
					$value->min_order_value = (string)$value->min_order_value;
					$value->max_value = (string)$value->max_value;
					$value->budget = (string)$value->budget;
					$value->entry_by = (string)$value->entry_by;
					$value->latitude = (string)$value->latitude;
					$value->longitude = (string)$value->longitude;
					$value->active = (string)$value->active;					
					$value->distance = (string)$value->distance;
					$value->available_status = (string)$value->available_status;
					
					$offer_to = strtotime($value->offer_to);
					if($current_date > $offer_to){
						$value->offer_from = "0000-00-00";
						$value->offer_to = "0000-00-00";
						$value->offer = "0";
					}
			
					if($value->active == 1){
						$value->available_status = \SiteHelpers::getrestimeval($value->id);
						if($value->available_status == 1){
							$value->res_status = "open";
						} else {
							$value->res_status = "close";
						}
					} else {
						$value->res_status = "close";
					}
					
					if($value->logo != ''){
						$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
					} else {
						$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($value->cuisine != ''){
						$value->cuisine = $this->cuisines($value->cuisine);
					}
					
					$value->rating = "";
					$rating = "";
					
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$value->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($value->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$value->rating = round(($rating/$reviews_count), 1);
					} else {
						$value->rating = 5;
					}
				}
	
				$response["message"]				= "success";
				$response["banners"] = array();	
				
				if($eoffer){
					$response["Offers"][0]['offer']	= "Offer's Found";
				}else{
					$response["Offers"][0]['offer']	= "Offer's Not Found";
				}
		
				if(isset($_REQUEST['user_id']) != ''){
					$user_id	= $_REQUEST['user_id'];
					$card		= "SELECT  SUM(`quantity`) as total_items ,`ae`.`res_id`,`ai`.`name` FROM `abserve_user_cart` as `ae` JOIN `abserve_restaurants` as `ai` on `ai`.`id`=`ae`.`res_id` WHERE `ae`.`user_id`=".$_REQUEST['user_id'];
					$tb_cu =\DB::SELECT($card);
				} else {
					$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				}
		
				$response["restaurants"]	= $res_restaurnts;
				$response['cart_details']	= $tb_cu;
			} else {
				$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				
				$response["message"]			= "failure";
				$response["Offers"][0]['offer']	= "Offer's Not Found";			
				$response["restaurants"]		= "";
				$response['cart_details']		= $tb_cu;
			}
			
			echo json_encode($response); exit;
		}
		else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postRestaurantresults2( Request $request){ 
	
		$rules = array(
			'lat'=>'required',
			'lang'=>'required'
		);
		
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			$_REQUEST = str_replace('"','', $_REQUEST);
			
			$area = $this->locationdistance($_REQUEST['pin_code']);
			//print_r($area);
			//echo $area[0]->distance; exit;
			if(count($area)>0){
				$radius = $area[0]->distance;
				$region_id = $area[0]->region_id;
			} else {
				$radius = 0;
				$region_id = "";
			}
			
			if($region_id != ""){
				if($_REQUEST['user_id'] !=''){
					$region_update = \DB::table('tb_users')->where('id', $_REQUEST['user_id'])->update(['region' => $region_id]);
				}
			}
			
			$whr	= "WHERE 1 AND active!=2 ";
	
			if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
				$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
				$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
				$select = '*'.$lat_lng;
				$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
				if($offer){
					$eoffer = true;
				} else {
					$eoffer = false;
				}
				$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
			} else {
				$lat_lng = $hav	= '';
				$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
				$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
				$from = $_REQUEST['location'];
			}
	
			if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
				$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
			}
	
			/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
				$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
			}*/
	
			if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
				$whr .= " AND `offer` <> 0 ";
			}
	
			if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
				$cond = " ORDER BY `delivery_time`";
			}
			//echo "SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav; exit;
	
			$res_restaurnts = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);
			
			//$restaurants = \DB::select("SELECT *  FROM `abserve_restaurants` ");
	
			//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond);
			//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`user_id` as cust_id,`ar`.`res_id` from `abserve_food_reviews` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
	
			/*foreach ($restaurants as $key => $value) {
				$value->available_status = \SiteHelpers::gettimeval($value->id);
				if($_REQUEST['available'] == 'yes'){
				  if($value->available_status!='0'){
					$dist = $this->calculate_dist($from,$value->location);
					if(!(isset($_REQUEST['location']) != '')){
						if($dist <= $radius){
							$res_restaurnts[] = $value;
						}
					} else {
						$res_restaurnts[] = $value;
					}
				  }
				}else{
				  $dist = $this->calculate_dist($from,$value->location);
				  if(!(isset($_REQUEST['location']) != '')){
					  if($dist <= $radius){
						  $res_restaurnts[] = $value;
					  }
				  } else {
					  $res_restaurnts[] = $value;
				  }
				}
				
			}*/
			
			if(count($res_restaurnts)>0){
				
				$current_date = strtotime(date("Y-m-d"));
			
				foreach ($res_restaurnts as $key => &$value) {
					
					$resId[] = $value->id;
					
					$dist = round($value->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($value->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($value->delivery_time) + (75);
					}
					
					$value->id = (string)$value->id;
					$value->partner_id = (string)$value->partner_id;
					$value->phone = (string)$value->phone;
					$value->secondary_phone_number = (string)$value->secondary_phone_number;
					$value->service_tax = (string)$value->service_tax;
					$value->max_packaging_charge = (string)$value->max_packaging_charge;
					$value->delivery_charge = (string)$value->delivery_charge;
					$value->vat = (string)$value->vat;
					$value->call_handling = (string)$value->call_handling;
					$value->delivery_time = (string)$delivery_time;
					$value->pure_veg = (string)$value->pure_veg;
					$value->offer = (string)$value->offer;
					$value->min_order_value = (string)$value->min_order_value;
					$value->max_value = (string)$value->max_value;
					$value->budget = (string)$value->budget;
					$value->entry_by = (string)$value->entry_by;
					$value->latitude = (string)$value->latitude;
					$value->longitude = (string)$value->longitude;
					$value->active = (string)$value->active;					
					$value->distance = (string)$value->distance;
					$value->available_status = (string)$value->available_status;
					
					$start_date = strtotime($value->new_start_date);
	 	            $end_date = strtotime($value->new_end_date);
					
					if($value->new_start_date && $value->new_end_date != 0){
				   		if($current_date >= $start_date && $current_date <= $end_date){   
							$value->res_new = "New";
				   		}else{
					   		$value->res_new = ""; 
				   		}
					}else{
					   $value->res_new = ""; 
				   	}
					
					$offer_to = strtotime($value->offer_to);
					if($current_date > $offer_to){
						$value->offer_from = "0000-00-00";
						$value->offer_to = "0000-00-00";
						$value->offer = "0";
					}
			
					if($value->active == 1){
						$value->available_status = \SiteHelpers::getrestimeval($value->id);
						if($value->available_status == 1){
							$value->res_status = "open";
						} else {
							$value->res_status = "close";
						}
					} else {
						$value->res_status = "close";
					}
					
					if($value->logo != ''){
						$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
					} else {
						$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($value->cuisine != ''){
						$value->cuisine = $this->cuisines($value->cuisine);
					}
					
					$value->rating = "";
					$rating = "";
					
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$value->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($value->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$value->rating = round(($rating/$reviews_count), 1);
					} else {
						$value->rating = 5;
					}
				}
	
				$response["message"]				= "success";
				
				$current_date_time = date("Y-m-d H:i:s");
				$area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
				$banners = \DB::table('banners')->where('region',$area->region_id)->where('from_date','<=',$current_date_time)->where('to_date','>=',$current_date_time)->get();
				
				if(count($banners)>0){
					foreach($banners as $banner){
						if($banner->status == 0){
							$event = "non_click";
						} else {
							$event = "click";
						}
						$response["banners"][] = array(
													"id"			=> $banner->id,
													"res_id"		=> $banner->res_id,
													"event"			=> $event,
													"banner_image"	=> \URL::to('')."/uploads/banners/".$banner->banner_image
												  );
					
					}
				} else {
					$response["banners"] = array();
				}	
				
				if($eoffer){
					$response["Offers"][0]['offer']	= "Offer's Found";
				}else{
					$response["Offers"][0]['offer']	= "Offer's Not Found";
				}
		
				if(($_REQUEST['user_id']) != ''){
					$user_id	= $_REQUEST['user_id'];
					$card		= "SELECT  SUM(`quantity`) as total_items ,`ae`.`res_id`,`ai`.`name` FROM `abserve_user_cart` as `ae` JOIN `abserve_restaurants` as `ai` on `ai`.`id`=`ae`.`res_id` WHERE `ae`.`user_id`=".$_REQUEST['user_id'];
					$tb_cu =\DB::SELECT($card);
				} else {
					$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				}
		
				$response["restaurants"]	= $res_restaurnts;
				$response['cart_details']	= $tb_cu;
			} else {
				$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				
				$response["message"]			= "failure";
				$response["Offers"][0]['offer']	= "Offer's Not Found";			
				$response["restaurants"]		= "";
				$response['cart_details']		= $tb_cu;
			}
			
			echo json_encode($response); exit;
		}
		else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postRestaurantresults3( Request $request){ 
	
		$rules = array(
			'lat'=>'required',
			'lang'=>'required'
		);
		
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			$_REQUEST = str_replace('"','', $_REQUEST);
			//print_r($_REQUEST);
			//exit;
			$area = $this->locationdistance($_REQUEST['pin_code']);
			//print_r($area);
			//echo $area[0]->distance; 
			
			if(count($area)>0){
				$radius = $area[0]->distance;
				$region_id = $area[0]->region_id;
			} else {
				$radius = 0;
				$region_id = "";
			}
			
			if($region_id != ""){
				if($_REQUEST['user_id'] !=''){
					$region_update = \DB::table('tb_users')->where('id', $_REQUEST['user_id'])->update(['region' => $region_id]);
				}
			}
			
			$whr	= "WHERE 1 AND active!=2 ";
	
			if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
				$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
				$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
				$select = '*'.$lat_lng;
				$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
				if($offer){
					$eoffer = true;
				} else {
					$eoffer = false;
				}
				$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
			} else {
				$lat_lng = $hav	= '';
				$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
				$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
				$from = $_REQUEST['location'];
			}
	
			if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
				$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
			}
	
			/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
				$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
			}*/
	
			if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
				$whr .= " AND `offer` <> 0 ";
			}
	
			if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
				$cond = " ORDER BY `delivery_time`";
			}
			
			/*for restaurant sequenece  start*/
			
			$current_date = date("Y-m-d");
			//echo "SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav; exit;
			$hav1	= "HAVING distance <= ".$radius." ORDER BY res_sequence";
	        $seq =" AND `res_sequence` != ''";
			$seq .=" AND '".$current_date."' >= `res_seq_start` AND '".$current_date."' <= `res_seq_end`";
			$resId_seq = array();
			$res_restaurnts1 = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$seq.$cond.$hav1);  
			foreach ($res_restaurnts1 as $key => &$value) {
			$resId_seq[] = $value->id;	
			}
			
			/*for restaurant sequenece  end */
			
			if(count($res_restaurnts1)>0){
			$quer = "AND `ID` NOT IN (".implode(',',$resId_seq).")";
		    $res_restaurnts = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$quer.$cond.$hav);  
			}else{
			$res_restaurnts = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);
			}
			
			if((count($res_restaurnts)>0) || (count($res_restaurnts1)>0)){
				
				$current_date = strtotime(date("Y-m-d"));
		     	
		    	foreach ($res_restaurnts1 as $key => &$value) {
					
					$resId_seq[] = $value->id;
					
					$dist = round($value->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($value->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($value->delivery_time) + (75);
					}
					
					$value->id = (string)$value->id;
					$value->partner_id = (string)$value->partner_id;
					$value->phone = (string)$value->phone;
					$value->secondary_phone_number = (string)$value->secondary_phone_number;
					$value->service_tax = (string)$value->service_tax;
					$value->max_packaging_charge = (string)$value->max_packaging_charge;
					$value->delivery_charge = (string)$value->delivery_charge;
					$value->vat = (string)$value->vat;
					$value->call_handling = (string)$value->call_handling;
					$value->delivery_time = (string)$delivery_time;
					$value->pure_veg = (string)$value->pure_veg;
					$value->offer = (string)$value->offer;
					$value->min_order_value = (string)$value->min_order_value;
					$value->max_value = (string)$value->max_value;
					$value->budget = (string)$value->budget;
					$value->entry_by = (string)$value->entry_by;
					$value->latitude = (string)$value->latitude;
					$value->longitude = (string)$value->longitude;
					$value->active = (string)$value->active;					
					$value->distance = (string)$value->distance;
					$value->available_status = (string)$value->available_status;
					
					$start_date = strtotime($value->new_start_date);
	 	            $end_date = strtotime($value->new_end_date);
					
					if($value->new_start_date && $value->new_end_date != 0){
				   		if($current_date >= $start_date && $current_date <= $end_date){   
							$value->res_new = "New";
				   		}else{
					   		$value->res_new = ""; 
				   		}
					}else{
					   $value->res_new = ""; 
				   	}
					
					$offer_to = strtotime($value->offer_to);
					if($current_date > $offer_to){
						$value->offer_from = "0000-00-00";
						$value->offer_to = "0000-00-00";
						$value->offer = "0";
					}
			
					if($value->active == 1){
						$value->available_status = \SiteHelpers::getrestimeval($value->id);
						if($value->available_status == 1){
							$value->res_status = "open";
						} else {
							$value->res_status = "close";
						}
					} else {
						$value->res_status = "close";
					}
					
					if($value->logo != ''){
						$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
					} else {
						$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($value->cuisine != ''){
						$value->cuisine = $this->cuisines($value->cuisine);
					}
					
					$value->rating = "";
					$rating = 0;
					
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$value->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($value->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$value->rating = round(($rating/$reviews_count), 1);
					} else {
						$value->rating = 5;
					}
				}				
				
				foreach ($res_restaurnts as $key => &$value) {
					
					$seq = $resId_seq;
				
					$resId[] = $value->id;
					
					$dist = round($value->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($value->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($value->delivery_time) + (75);
					}
					
					$value->id = (string)$value->id;
					$value->partner_id = (string)$value->partner_id;
					$value->phone = (string)$value->phone;
					$value->secondary_phone_number = (string)$value->secondary_phone_number;
					$value->service_tax = (string)$value->service_tax;
					$value->max_packaging_charge = (string)$value->max_packaging_charge;
					$value->delivery_charge = (string)$value->delivery_charge;
					$value->vat = (string)$value->vat;
					$value->call_handling = (string)$value->call_handling;
					$value->delivery_time = (string)$delivery_time;
					$value->pure_veg = (string)$value->pure_veg;
					$value->offer = (string)$value->offer;
					$value->min_order_value = (string)$value->min_order_value;
					$value->max_value = (string)$value->max_value;
					$value->budget = (string)$value->budget;
					$value->entry_by = (string)$value->entry_by;
					$value->latitude = (string)$value->latitude;
					$value->longitude = (string)$value->longitude;
					$value->active = (string)$value->active;					
					$value->distance = (string)$value->distance;
					$value->available_status = (string)$value->available_status;
					
					$start_date = strtotime($value->new_start_date);
	 	            $end_date = strtotime($value->new_end_date);
					
					if($value->new_start_date && $value->new_end_date != 0){
				   		if($current_date >= $start_date && $current_date <= $end_date){   
							$value->res_new = "New";
				   		}else{
					   		$value->res_new = ""; 
				   		}
					}else{
					   $value->res_new = ""; 
				   	}
					
					$offer_to = strtotime($value->offer_to);
					if($current_date > $offer_to){
						$value->offer_from = "0000-00-00";
						$value->offer_to = "0000-00-00";
						$value->offer = "0";
					}
			
					if($value->active == 1){
						$value->available_status = \SiteHelpers::getrestimeval($value->id);
						if($value->available_status == 1){
							$value->res_status = "open";
						} else {
							$value->res_status = "close";
						}
					} else {
						$value->res_status = "close";
					}
					
					if($value->logo != ''){
						$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
					} else {
						$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($value->cuisine != ''){
						$value->cuisine = $this->cuisines($value->cuisine);
					}
					
					$value->rating = "";
					$rating = 0;
					
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$value->id)->where('rating','!=',0)->get();	
					
					if(count($Reviews)>0){	
						$rating = 0;
						foreach ($Reviews as $key => $aReviews) {
							if($value->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$value->rating = round(($rating/$reviews_count), 1);
					} else {
						$value->rating = 5;
					}
				}
				$response["message"]				= "success";
				
				$current_date_time = date("Y-m-d H:i:s");
				$area = \DB::table('area')->where('pin_code',$_REQUEST['pin_code'])->first();
				$banners = \DB::table('banners')->where('region',$area->region_id)->where('from_date','<=',$current_date_time)->where('to_date','>=',$current_date_time)->get();
				//print_r($area);  exit;
				$lunchbox = \DB::table('delivery_point')->where('region',$area->region_id)->where('status',1)->get();
				
				$region_services = \DB::table('region_services')->where('region',$area->region_id)->where('status',1)->get();
				
				if((count($lunchbox)>0) && (count($region_services)>0)){
				 	$icon = "true";
					$_lunchbox = 1;
					$services = 1;
				}else{
					if((count($lunchbox)>0) || (count($region_services)>0)){
						$icon = "true";
						if(count($lunchbox)>0){
							$_lunchbox = 1;
						}else{
							$_lunchbox = 0;
						}
						if(count($region_services)>0){
							$services = 1;
						}else{
							$services = 0;
						}
					} else {
						$icon = "false";
						$_lunchbox = 0;
						$services = 0;
					}
				}
				
				$response["lunchbox"][] = array(
												"icon"			=> $icon,
												"lunchbox"		=> $_lunchbox,
												"services"		=> $services,
											    );
				
				
				if(count($banners)>0){
					foreach($banners as $banner){
						if($banner->status == 0){
							$event = "non_click";
						} else {
							$event = "click";
						}
						$response["banners"][] = array(
													"id"			=> $banner->id,
													"res_id"		=> $banner->res_id,
													"event"			=> $event,
													"banner_image"	=> \URL::to('')."/uploads/banners/".$banner->banner_image
												  );
					
					}
				} else {
					$response["banners"] = array();
				}	
				
				if($eoffer){
					$response["Offers"][0]['offer']	= "Offer's Found";
				}else{
					$response["Offers"][0]['offer']	= "Offer's Not Found";
				}
		
				if(($_REQUEST['user_id']) != ''){
					$user_id	= $_REQUEST['user_id'];
					$card		= "SELECT  SUM(`quantity`) as total_items ,`ae`.`res_id`,`ai`.`name` FROM `abserve_user_cart` as `ae` JOIN `abserve_restaurants` as `ai` on `ai`.`id`=`ae`.`res_id` WHERE `ae`.`user_id`=".$_REQUEST['user_id'];
					$tb_cu =\DB::SELECT($card);
				} else {
					$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				}
		
		
			   
			    $res1	= $res_restaurnts1;
				$res	= $res_restaurnts;
				$res_results = array_merge($res1,$res); 
				
				$response['restaurants']	= $res_results; 
				$response['cart_details']	= $tb_cu;
			} else {
				$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
				
				$response["message"]			= "failure";
				$response["lunchbox"][] 		= array(
													"icon"			=> "false",
													"lunchbox"		=> 0,
													"services"		=> 0,
													);
				$response["banners"] 			= array();
				$response["Offers"][0]['offer']	= "Offer's Not Found";			
				$response["restaurants"]		= "";
				$response['cart_details']		= $tb_cu;
			}
			
			echo json_encode($response); exit;
		}
		else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"] 		= $error;
			echo json_encode($response); exit;
		}
	}

	public function postRescategories(Request $request){
		$response = array();

		$restaurant_id	=	$_REQUEST['res_id'];

		$categories = \DB::select("SELECT DISTINCT(`hi`.`main_cat`) as id,`fc`.`cat_name` as name FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `fc` ON `hi`.`main_cat` = `fc`.`id` WHERE `restaurant_id` = ".$restaurant_id);

		$recomend = \DB::select("SELECT DISTINCT(`recommended`) AS recomnd FROM `abserve_hotel_items` WHERE `restaurant_id` = ".$_REQUEST['res_id']);

		foreach ($recomend as $key => $val) {
			$rend[] = get_object_vars($val);
		}
		if (!empty($rend)) {
			$result = call_user_func_array('array_merge_recursive', $rend);
			$rec_val = array("id"=>0,"name"=>"Recommended");

			if(is_array($result['recomnd'])){
				if(in_array('1',$result['recomnd'])){
					array_unshift($categories, $rec_val);
				}
			}else{
				if($result['recomnd'] == 1){
					array_unshift($categories, $rec_val);
				}
			}
		}		

		$response["categories"] = $categories;
		echo json_encode($response); exit;
	}
	
	public function postRescategories1(Request $request){
		$response = array();

		$restaurant_id	=	$_REQUEST['res_id'];

		$_categories = \DB::select("SELECT DISTINCT(`hi`.`main_cat`) as id,`fc`.`cat_name` as name FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `fc` ON `hi`.`main_cat` = `fc`.`id` WHERE `restaurant_id` = ".$restaurant_id);

		$recomend = \DB::select("SELECT DISTINCT(`recommended`) AS recomnd FROM `abserve_hotel_items` WHERE `restaurant_id` = ".$_REQUEST['res_id']);
		
		foreach($_categories as $category){
			$sub_categories = array();
			$sub_categories = \DB::select("SELECT DISTINCT(`hi`.`sub_cat`) as sub_cat_id,`fc`.`cat_name` as sub_cat FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `fc` ON `hi`.`sub_cat` = `fc`.`id` WHERE `restaurant_id` = ".$restaurant_id." AND `main_cat` = ".$category->id." AND `sub_cat` != ".$category->id);
			
			$categories[] = array(
								"id"				=> $category->id,
								"name"				=> $category->name,
								"sub_categories"	=> $sub_categories,
							);
		}

		foreach ($recomend as $key => $val) {
			$rend[] = get_object_vars($val);
		}
		if (!empty($rend)) {
			$result = call_user_func_array('array_merge_recursive', $rend);
			$rec_val = array("id"=>0,"name"=>"Recommended","sub_categories"	=> array());

			if(is_array($result['recomnd'])){
				if(in_array('1',$result['recomnd'])){
					array_unshift($categories, $rec_val);
				}
			}else{
				if($result['recomnd'] == 1){
					array_unshift($categories, $rec_val);
				}
			}
		}
		

		$response["categories"]      	= $categories;
		echo json_encode($response); exit;
	}

	public function postNewresdetails( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
	
		$rules = array(
			//'user_id'		=>'required',
			'res_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			// `hi`.*,`c`.`cat_name` AS Main_category,`hc`.`cat_name` AS sub_category
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond = " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}
				
				/*if(isset($_REQUEST['item_status']))
				{
					$cond = " AND `hi`.`item_status` = '".$_REQUEST['item_status']."'";
				}*/

				if($_REQUEST['main_cat'] == "Recommended"){
					$cond .= " AND `hi`.`recommended` = '1'";
				}else{
					$cond .= " AND `c`.`cat_name` = '".$_REQUEST['main_cat']."'";
				}

				$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`ingredients`,`hi`.`main_cat`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond;
				$arry = \DB::select($qwert);
				$items=[];
				if(isset($_REQUEST['user_id']))
				{
				$query = "SELECT * FROM `abserve_user_cart` WHERE `user_id` = ".$_REQUEST['user_id'];
				$items = \DB::select($query);

				}


				
				
				foreach($arry as $key => $values) {
					$values->quantity = 0;
					foreach ($items as $key_i => $value_i) {
						if($value_i->food_id === $values->id)
							$values->quantity = $value_i->quantity;
					}
			 		if($values->image != ''){
						$values->image = \URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$values->image;
					}else{
						$values->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
				}

				//echo "<pre>";print_r($arry);exit();
				/*if($_REQUEST['main_cat'] == "Recommended"){
					foreach ($arry as $key => $value) {
						// $restaurants[$value['Sub_cat']][$value['Sub_cat']][] = $arr[$key];
						foreach ($value as $key_in => $value_in) {
							if($key_in == 'Main_cat'){
								$restaurants[$value_in][] = $arry[$key];
							}
						}
					}
				}else{
					foreach ($arry as $key => $value) {
						foreach ($value as $key_in => $value_in) {
							if($key_in == 'Sub_cat'){
								$restaurants[$value_in][] = $arry[$key];
							}
						}
					}
				}*/
			}

			$response["message"] 			= (array)"success";
			$response["restaurants"]     	= $arry;
			/*echo "<pre>";
			print_r($response);exit();*/
			echo json_encode($response); exit;
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postResdetails( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
	
		$rules = array(
			'user_id'		=>'required',
			'res_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			// `hi`.*,`c`.`cat_name` AS Main_category,`hc`.`cat_name` AS sub_category
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond = " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}

				if($_REQUEST['main_cat'] == "Recommended"){
					$cond .= " AND `hi`.`recommended` = '1'";
				}else{
					$cond .= " AND `c`.`cat_name` = '".$_REQUEST['main_cat']."'";
				}

				$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`main_cat`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond;
				$arry = \DB::select($qwert);

				$query = "SELECT * FROM `abserve_user_cart` WHERE `user_id` = ".$_REQUEST['user_id'];
				$items = \DB::select($query);

				foreach($arry as $key => $values) {
					$values->quantity = 0;
					foreach ($items as $key_i => $value_i) {
						if($value_i->food_id === $values->id)
							$values->quantity = $value_i->quantity;
					}
			 		if($values->image != ''){
						$values->image = \URL::to('').'/uploads/restaurants/'.$values->image;
					}else{
						$values->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
				}

				if($_REQUEST['main_cat'] == "Recommended"){
					foreach ($arry as $key => $value) {
						// $restaurants[$value['Sub_cat']][$value['Sub_cat']][] = $arr[$key];
						foreach ($value as $key_in => $value_in) {
							if($key_in == 'Main_cat'){
								$restaurants[$value_in][] = $arry[$key];
							}
						}
					}
				}else{
					foreach ($arry as $key => $value) {
						foreach ($value as $key_in => $value_in) {
							if($key_in == 'Sub_cat'){
								$restaurants[$value_in][] = $arry[$key];
							}
						}
					}
				}
			}

			$response["message"] 			= (array)"success";
			$response["restaurants"]     	= $restaurants;
			/*echo "<pre>";
			print_r($response);exit();*/
			echo json_encode($response); exit;
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postBoyorders( Request $request){

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
						$query = "SELECT `oi`.`orderid`,`res_id`,`food_id`,`food_item`,`od`.`cust_id`,`address`,`building`,`landmark`,`lat` as `cust_lat`,`lang` as `cust_long`,`date`,`time`,`grand_total` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `bo`.`status` = '0' AND `od`.`status` !='10' ".$cond." ORDER BY `od`.`time` DESC";
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
							$value->date = date('d-m-Y', strtotime($value->date));
							$value->time = date('h:i:s A', $value->time);
							
							$user = \DB::table('tb_users')->where('id',$value->cust_id)->first();
							$value->phone_number = $user->phone_number;
							
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
							
						}

						$orders = array_reduce($orders, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						}/* else {
							$carry[$item->orderid]->food_item .= ',' . $item->food_item;
						}*/
						return $carry;
						}, array());
						$restaurants=[];
						foreach ($restaurants as $key => $value) {
							$orders[$key]->count = sizeof($value);
						}

						$query1 = "SELECT DISTINCT(`od`.`res_id`) FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `bo`.`status` = '0' AND `od`.`status` !='10' ".$cond." ORDER BY `od`.`time` DESC";
						$orders1 = \DB::select($query1);
					  
						foreach ($orders1 as $key => $value) {
							$res_ids[] = $value->res_id;
						}
						
						$qry = "SELECT `ar`.`id` as res_id,`ar`.`name`,`ar`.`location`,`ar`.`latitude`,`ar`.`longitude`,`ao`.`id`,`ao`.`grand_total`,`ao`.`delivery_type` FROM `abserve_restaurants` AS `ar` JOIN `abserve_order_details` AS `ao` ON `ar`.`id` = `ao`.`res_id` WHERE `ar`.`id` IN (".implode(',', $res_ids).") ".$cond1;
					
						$res_names = \DB::select($qry);
					

						foreach ($orders as $key => $value) {
							foreach ($res_names as $key_in => $value_in) {
								if($value_in->res_id === $value->res_id){
									$value->res_name = $value_in->name;
									$value->location = $value_in->location;
									$value->delivery_type = $value_in->delivery_type;
									$value->res_lat = $value_in->latitude;
									$value->res_long = $value_in->longitude;
								}
							}
							if($value->orderid === $value->orderid)
								$orders_final[] = $value;
						}
						$response['message'] 	= "New orders found";

						$response['new_orders']	= $orders_final;
						// echo "<pre>";
						// print_r($response);exit;
					}else{
						$response['message']	= "No orders found";
						$response['new_orders']	= [];
					}
				}else{
					$response['message']	= "No orders found";
					$response['new_orders']	= [];
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

	public function postBoyorders1( Request $request){

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
						$query = "SELECT `oi`.`orderid`,`res_id`,`food_id`,`food_item`,`od`.`cust_id`,`address`,`building`,`landmark`,`lat` as `cust_lat`,`lang` as `cust_long`,`date`,`time`,`grand_total` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `bo`.`status` = '0' AND `od`.`status` !='10' ".$cond." ORDER BY `od`.`time` DESC";
						$orders = \DB::select($query);
					
					  if(count($orders)>0){
						
						foreach ($orders as $key => $value) {
							
							foreach ($value as $key_in => $value_in) {
								if($key_in == 'orderid'){
									$restaurants[$value_in][] = $orders[$key];
								}

							}
						}
				
						foreach ($orders as $key => $value) {

							$value->cust_address = $value->building.",".$value->landmark.",".$value->address;
							$value->date = date('d-m-Y', strtotime($value->date));
							$value->time = date('h:i:s A', $value->time);
							
							$user = \DB::table('tb_users')->where('id',$value->cust_id)->first();
							$value->phone_number = $user->phone_number;
							
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
							
						}

						$orders = array_reduce($orders, function ($carry, $item) {
						if (!isset($carry[$item->orderid])) {
							$carry[$item->orderid] = $item;
						}/* else {
							$carry[$item->orderid]->food_item .= ',' . $item->food_item;
						}*/
						return $carry;
						}, array());
						$restaurants=[];
						foreach ($restaurants as $key => $value) {
							$orders[$key]->count = sizeof($value);
						}

						$query1 = "SELECT DISTINCT(`od`.`res_id`) FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_boyorderstatus` AS `bo` ON `od`.`id` = `bo`.`oid` WHERE `bo`.`status` = '0' AND `od`.`status` !='10' ".$cond." ORDER BY `od`.`time` DESC";
						$orders1 = \DB::select($query1);
					  
						foreach ($orders1 as $key => $value) {
							$res_ids[] = $value->res_id;
						}
						
						$qry = "SELECT `ar`.`id` as res_id,`ar`.`name`,`ar`.`location`,`ar`.`latitude`,`ar`.`longitude`,`ao`.`id`,`ao`.`grand_total`,`ao`.`delivery_type` FROM `abserve_restaurants` AS `ar` JOIN `abserve_order_details` AS `ao` ON `ar`.`id` = `ao`.`res_id` WHERE `ar`.`id` IN (".implode(',', $res_ids).") ".$cond1;
					
						$res_names = \DB::select($qry);
					

						foreach ($orders as $key => $value) {
							foreach ($res_names as $key_in => $value_in) {
								if($value_in->res_id === $value->res_id){
									$value->res_name = $value_in->name;
									$value->location = $value_in->location;
									$value->delivery_type = $value_in->delivery_type;
									$value->res_lat = $value_in->latitude;
									$value->res_long = $value_in->longitude;
								}
							}
							if($value->orderid === $value->orderid)
								$orders_final[] = $value;
						}
						$response['message'] 	= "New orders found";

						$response['new_orders']	= $orders_final;
						// echo "<pre>";
						// print_r($response);exit;
						
					  } else {
						$response['message']	= "No orders found";
						$response['new_orders']	= [];
					  }
					  
					}else{
						$response['message']	= "No orders found";
						$response['new_orders']	= [];
					}
				}else{
					$response['message']	= "No orders found";
					$response['new_orders']	= [];
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

	public function postResdetailsold( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
	
		$rules = array(
			'user_id'		=>'required',
			'res_id'		=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			// `hi`.*,`c`.`cat_name` AS Main_category,`hc`.`cat_name` AS sub_category
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond = " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}

				if($_REQUEST['main_cat'] == "Recommended"){
					$cond .= " AND `hi`.`recommended` = '1'";
				}else{
					$cond .= " AND `c`.`cat_name` = '".$_REQUEST['main_cat']."'";
				}

				$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`main_cat`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond;
				$arry = \DB::select($qwert);

				$query = "SELECT * FROM `abserve_user_cart` WHERE `user_id` = ".$_REQUEST['user_id'];
				$items = \DB::select($query);

				foreach($arry as $key => $values) {
					$values->quantity = 0;
					foreach ($items as $key_i => $value_i) {
						if($value_i->food_id === $values->id)
							$values->quantity = $value_i->quantity;
					}
			 		if($values->image != ''){
						$values->image = \URL::to('').'/uploads/restaurants/'.$values->image;
					}else{
						$values->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
				}

				if($_REQUEST['main_cat'] == "Recommended"){
					foreach ($arry as $key => $value) {
						// $restaurants[$value['Sub_cat']][$value['Sub_cat']][] = $arr[$key];
						foreach ($value as $key_in => $value_in) {
							if($key_in == 'Main_cat'){
								$restaurants[$value_in][] = $arry[$key];
							}
						}
					}
				}else{
					foreach ($arry as $key => $value) {
						foreach ($value as $key_in => $value_in) {
							if($key_in == 'Sub_cat'){
								$restaurants[$value_in][] = $arry[$key];
							}
						}
					}
				}
			}

			$response["message"] 			= (array)"success";
			$response["restaurants"]     	= $restaurants;
			/*echo "<pre>";
			print_r($response);exit();*/
			echo json_encode($response); exit;
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCuisines( Request $request){
		
		$cuisine = \DB::select("SELECT DISTINCT(`cuisine`) FROM `abserve_restaurants` WHERE `location` LIKE 'Chennai'");
		foreach ($cuisine as $key => $value) {
			$getCuisines[] = get_object_vars($value);
		}
		foreach($getCuisines as $ky => $val){
			foreach ($val as $ke => $vale) {
				if( strpos($vale, ',') !== false ){
					$vsls = explode(',', $vale);
				}
				else{
					$vsls = $vale;
				}
			}
			$allCuisines[] = ($vsls);
		}
			$finalArray = array();
			$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($allCuisines)); //$a is your multidimentional original array
			foreach($iterator as $v) {
			    $finalArray[] = $v;
			}
			// echo "<pre>";
			$cuisines = implode(',', array_unique($finalArray));  //print your array

			$result = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines` WHERE `id` IN (".$cuisines.")");

		$response['cuisines'] = $result;
		echo json_encode($response);exit;
	}

	public function postResitemssearch( Request $request){

		$query = "SELECT DISTINCT(`ar`.`id`),`ar`.*  FROM `abserve_restaurants` AS `ar` JOIN `abserve_hotel_items` AS `hi` /*ON `ar`.`id` = `hi`.`restaurant_id`*/ WHERE `ar`.`name` LIKE '%".$_REQUEST['search_text']."%'";
		$res = \DB::select($query);

		$query1 = "SELECT `restaurant_id`,`food_item` FROM `abserve_restaurants` AS `ar` JOIN `abserve_hotel_items` AS `hi` ON `ar`.`id` = `hi`.`restaurant_id` WHERE `hi`.`food_item` LIKE '%".$_REQUEST['search_text']."%' GROUP BY `hi`.`id`";
		$items = \DB::select($query1);

		if(empty($res) && empty($items)){

			$response['message'] 		= "No results found";
			$response['restaurants'] 	= '';
			echo json_encode($response);exit;
		}
		elseif(empty($res) && !empty($items) || !empty($res) && !empty($items)) {
			foreach($items as $value){
				$newArr[$value->restaurant_id][] = $value->food_item;
			}
			foreach($newArr as $key => $value){
				$finalArr[] = (object) array('restaurant_id' => $key, 'food_item' => implode(',', $value));
			}

			foreach ($finalArr as $key => $value) {
				$res_ids[] = $value->restaurant_id;
			}
			$query2 = "SELECT DISTINCT(`ar`.`id`),`ar`.*  FROM `abserve_restaurants` as `ar` WHERE `id` IN (".implode(',', $res_ids).")";
			$res1 = \DB::select($query2);
			$res = array_unique(array_merge($res,$res1), SORT_REGULAR);

			foreach ($res as $ke => $valu) {
				$valu->food_item = '';
				foreach ($finalArr as $key_in => $value_in) {
					if($value_in->restaurant_id === $valu->id){
						$valu->food_item = $value_in->food_item;
					}
				}
			}

			$response['message'] 		= "Success";
			$response['restaurants'] 	= $res;
			/*echo "<pre>";
			print_r($response);exit;*/
			echo json_encode($response);exit;
		}
		elseif(!empty($res) && empty($items)) {
			foreach ($res as $ke => $valu) {
				$valu->food_item = '';
			}

			$response['message'] 		= "Success";
			$response['restaurants'] 	= $res;
			echo json_encode($response);exit;
		}
	}

	public function postResprodsuggsearch( Request $request) {
		
		$query = "SELECT `res`.*,`fc`.`name` as `cuisines_name`,`hi`.`food_item` FROM `abserve_food_cuisines` as `fc` JOIN `abserve_restaurants` as `res` on FIND_IN_SET(`fc`.`id`, `res`.`cuisine`) != 0 JOIN `abserve_hotel_items` AS `hi` ON `res`.`id` = `hi`.`restaurant_id` where ((`res`.`name` LIKE '%".$_REQUEST['search_text']."%') OR (`fc`.`name` LIKE '%".$_REQUEST['search_text']."%') OR (`hi`.`food_item` LIKE '%".$_REQUEST['search_text']."%')) GROUP BY `res`.`id`";
		$newArr = \DB::select($query);

		$query1 = "SELECT `food_item` FROM `abserve_hotel_items` WHERE `food_item` LIKE '%".$_REQUEST['search_text']."%' GROUP BY `food_item`";
		$items = \DB::select($query1);
		
		foreach($newArr as $res){			
			
			if($res->logo != ''){
				$res->logo=\URL::to('').'/uploads/restaurants/'.$res->logo;
			} else {
				$res->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
			}
			if($res->cuisine != ''){
				$res->cuisine = $this->cuisines($res->cuisine);
			}
			
			$restaurants[] = array(											
						  "id"				=> $res->id,
						  "name"			=> $res->name,
						  "location"		=> $res->location,
						  "logo"			=> $res->logo,
						  "partner_id"		=> $res->partner_id,
						  "opening_time"	=> $res->opening_time,
						  "closing_time"	=> $res->closing_time,
						  "phone"			=> $res->phone,
						  "secondary_phone_number"=> $res->secondary_phone_number,
           				  "max_packaging_charge"=> $res->max_packaging_charge,
						  "service_tax"		=> $res->service_tax,
						  "delivery_charge"	=> $res->delivery_charge,
						  "vat"				=> $res->vat,
						  "cuisine"			=> $res->cuisine,
						  "call_handling"	=> $res->call_handling,
						  "delivery_time"	=> $res->delivery_time,
						  "pure_veg"		=> $res->pure_veg,
						  "offer"			=> $res->offer,
						  "budget"			=> $res->budget,
						  "rating"			=> $res->rating,
						  "entry_by"		=> $res->entry_by,
						  "latitude"		=> $res->latitude,
						  "longitude"		=> $res->longitude,
						  "res_desc"		=> $res->res_desc,
						  "food_item"		=> $res->food_item,							
						);
		}

		if(empty($restaurants) && empty($items)){

			$response['message'] 			= "No results found";
			$response['restaurants'] 		= array();
			$response['prod_suggestion'] 	= array();
			echo json_encode($response);exit;
		
		} elseif(empty($restaurants) && !empty($items) || !empty($restaurants) && !empty($items)) {	
		
			$items1[] = array("food_item" => $_REQUEST['search_text']);
			$items = array_unique(array_merge($items1,$items), SORT_REGULAR);

			$response['message'] 			= "Success";
			
			if(empty($restaurants)){
				$response['restaurants'] 	= array();
			} else {
				$response['restaurants'] 	= $restaurants;
			}
			if(empty($items)){
				$response['prod_suggestion'] = array();
			} else {
				$response['prod_suggestion'] = $items;
			}
			
			echo json_encode($response);exit;
		
		} elseif(!empty($restaurants) && empty($items)) {			
			
			$response['message'] 			= "Success";
			$response['restaurants'] 		= $restaurants;
			$response['prod_suggestion'] 	= array();
			echo json_encode($response);exit;
			
		}
	}

	public function postResprodsuggsearch1( Request $request) {
		
		$_REQUEST = str_replace('"','', $_REQUEST);
	
		$radius = 15;
		$whr	= "WHERE 1 AND active!=2 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$_restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);		
		
		//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
		
		foreach ($_restaurants as $key => $_rest) {
			if($_rest->active == 1){
				$_rest->available_status = \SiteHelpers::getrestimeval($_rest->id);
				if($_rest->available_status == 1){
					$res_id[] = $_rest->id;
				}
			}
			$resid[] = $_rest->id;
		}
		
		if(count($res_id)>0){
			
			$resids = implode("','",$resid);
			$res_ids = implode("','",$res_id);
					
			$query = "SELECT `res`.*,`fc`.`name` as `cuisines_name`,`hi`.`food_item` FROM `abserve_food_cuisines` as `fc` JOIN `abserve_restaurants` as `res` on FIND_IN_SET(`fc`.`id`, `res`.`cuisine`) != 0 JOIN `abserve_hotel_items` AS `hi` ON `res`.`id` = `hi`.`restaurant_id` where `hi`.`item_status`=1 AND `hi`.`restaurant_id` IN ('".$resids."') AND ((`res`.`name` LIKE '%".$_REQUEST['search_text']."%') OR (`fc`.`name` LIKE '%".$_REQUEST['search_text']."%') OR (`hi`.`food_item` LIKE '%".$_REQUEST['search_text']."%')) GROUP BY `res`.`id`";
			$newArr = \DB::select($query);
			
			$current_time = date("H:i:s");
	
			$query1 = "SELECT `food_item` FROM `abserve_hotel_items` WHERE `item_status`=1 AND `restaurant_id` IN ('".$res_ids."') and `food_item` LIKE '%".$_REQUEST['search_text']."%' AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) GROUP BY `food_item`";
			$items = \DB::select($query1);
			
			$current_date = strtotime(date("Y-m-d"));
			
			foreach($newArr as $res){
				if($res->active == 1){
					$res->available_status = \SiteHelpers::getrestimeval($res->id);
					if($res->available_status == 1){
						$res->res_status = "open";
					} else {
						$res->res_status = "close";
					}
				} else {
					$res->res_status = "close";
				}
				
				$offer_to = strtotime($res->offer_to);				
				if($current_date > $offer_to){
					$res->offer_from = "0000-00-00";
					$res->offer_to = "0000-00-00";
					$res->offer = "0";
				}
				
				if($res->logo != ''){
					$res->logo=\URL::to('').'/uploads/restaurants/'.$res->logo;
				} else {
					$res->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				if($res->cuisine != ''){
					$res->cuisine = $this->cuisines($res->cuisine);
				}
				
				$res->rating = "";
				$rating = "";				
				$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$res->id)->where('rating','!=',0)->get();			
				
				if(count($Reviews)>0){											
					foreach ($Reviews as $key => $aReviews) {
						if($res->id == $aReviews->res_id){
							//$Reviews[] = $aReviews->id;
							$rating += $aReviews->rating;
						}
					}
					$reviews_count = count($Reviews);
					$res->rating = round(($rating/$reviews_count), 1);
				} else {
					$res->rating = 5;
				}
				
				$dist = round($res->distance);
				$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
				if($delivery_dist !=''){
					$delivery_time = ($res->delivery_time) + ($delivery_dist->mins);
				} else {
					$delivery_time = ($res->delivery_time) + (75);
				}
				
				$restaurants[] = array(											
							  "id"				=> (string)$res->id,
							  "name"			=> $res->name,
							  "location"		=> $res->location,
							  "logo"			=> $res->logo,
							  "partner_id"		=> (string)$res->partner_id,
							  "opening_time"	=> $res->opening_time,
							  "closing_time"	=> $res->closing_time,
							  "phone"			=> (string)$res->phone,
							  "secondary_phone_number"=> (string)$res->secondary_phone_number,
							  "max_packaging_charge"=> (string)$res->max_packaging_charge,
							  "service_tax"		=> (string)$res->service_tax,
							  "delivery_charge"	=> (string)$res->delivery_charge,
							  "vat"				=> (string)$res->vat,
							  "cuisine"			=> $res->cuisine,
							  "call_handling"	=> (string)$res->call_handling,
							  "delivery_time"	=> (string)$delivery_time,
							  "pure_veg"		=> (string)$res->pure_veg,
							  "offer"			=> (string)$res->offer,
							  "min_order_value"	=> (string)$res->min_order_value,
							  "max_value"		=> (string)$res->max_value,
							  "offer_from"		=> $res->offer_from,
							  "offer_to"		=> $res->offer_to,
							  "rating"			=> (string)$res->rating,
							  "entry_by"		=> (string)$res->entry_by,
							  "latitude"		=> (string)$res->latitude,
							  "longitude"		=> (string)$res->longitude,
							  "res_desc"		=> $res->res_desc,
							  "food_item"		=> $res->food_item,
							  "res_status"		=> $res->res_status,
							);
			}
	
			if(empty($restaurants) && empty($items)){
	
				$response['message'] 			= "No results found";
				$response['restaurants'] 		= array();
				$response['prod_suggestion'] 	= array();
				echo json_encode($response);exit;
			
			} elseif(empty($restaurants) && !empty($items) || !empty($restaurants) && !empty($items)) {	
			
				$items1[] = array("food_item" => $_REQUEST['search_text']);
				$items = array_unique(array_merge($items1,$items), SORT_REGULAR);
	
				$response['message'] 			= "Success";
				
				if(empty($restaurants)){
					$response['restaurants'] 	= array();
				} else {
					$response['restaurants'] 	= $restaurants;
				}
				if(empty($items)){
					$response['prod_suggestion'] = array();
				} else {
					$response['prod_suggestion'] = $items;
				}
				
				echo json_encode($response);exit;
			
			} elseif(!empty($restaurants) && empty($items)) {			
				
				$response['message'] 			= "Success";
				$response['restaurants'] 		= $restaurants;
				$response['prod_suggestion'] 	= array();
				echo json_encode($response);exit;
				
			}
		} else {
			$response['message'] 			= "No results found";
			$response['restaurants'] 		= array();
			$response['prod_suggestion'] 	= array();
			echo json_encode($response); exit;
		}
	}

	public function postResprodsuggsearch2( Request $request) {
		
		$_REQUEST = str_replace('"','', $_REQUEST);
	
		$area = $this->locationdistance($_REQUEST['pin_code']);		
		if(count($area)>0){
			$radius = $area[0]->distance;
		} else {
			$radius = 0;
		}
		
		$whr	= "WHERE 1 AND active!=2 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$_restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);		
		
		//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
		
		foreach ($_restaurants as $key => $_rest) {
			if($_rest->active == 1){
				$_rest->available_status = \SiteHelpers::getrestimeval($_rest->id);
				if($_rest->available_status == 1){
					$res_id[] = $_rest->id;
				}
			}
			$resid[] = $_rest->id;
		}
		
		if(count($res_id)>0){
			
			$resids = implode("','",$resid);
			$res_ids = implode("','",$res_id);
					
			$query = "SELECT `res`.*,`fc`.`name` as `cuisines_name`,`hi`.`food_item` FROM `abserve_food_cuisines` as `fc` JOIN `abserve_restaurants` as `res` on FIND_IN_SET(`fc`.`id`, `res`.`cuisine`) != 0 JOIN `abserve_hotel_items` AS `hi` ON `res`.`id` = `hi`.`restaurant_id` where `hi`.`item_status`=1 AND `hi`.`restaurant_id` IN ('".$resids."') AND ((`res`.`name` LIKE '%".$_REQUEST['search_text']."%') OR (`fc`.`name` LIKE '%".$_REQUEST['search_text']."%') OR (`hi`.`food_item` LIKE '%".$_REQUEST['search_text']."%')) GROUP BY `res`.`id`";
			$newArr = \DB::select($query);
			
			$current_time = date("H:i:s");
	
			$query1 = "SELECT `food_item` FROM `abserve_hotel_items` WHERE `item_status`=1 AND `restaurant_id` IN ('".$res_ids."') and `food_item` LIKE '%".$_REQUEST['search_text']."%' AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) GROUP BY `food_item`";
			$items = \DB::select($query1);
			
			$current_date = strtotime(date("Y-m-d"));
			
			foreach($newArr as $res){
				if($res->active == 1){
					$res->available_status = \SiteHelpers::getrestimeval($res->id);
					if($res->available_status == 1){
						$res->res_status = "open";
					} else {
						$res->res_status = "close";
					}
				} else {
					$res->res_status = "close";
				}
				
				$offer_to = strtotime($res->offer_to);				
				if($current_date > $offer_to){
					$res->offer_from = "0000-00-00";
					$res->offer_to = "0000-00-00";
					$res->offer = "0";
				}
				
				if($res->logo != ''){
					$res->logo=\URL::to('').'/uploads/restaurants/'.$res->logo;
				} else {
					$res->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				if($res->cuisine != ''){
					$res->cuisine = $this->cuisines($res->cuisine);
				}
				
				$res->rating = "";
				$rating = "";				
				$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$res->id)->where('rating','!=',0)->get();			
				$rating = 0;
				if(count($Reviews)>0){											
					foreach ($Reviews as $key => $aReviews) {
						if($res->id == $aReviews->res_id){
							//$Reviews[] = $aReviews->id;
							$rating += $aReviews->rating;
						}
					}
					$reviews_count = count($Reviews);
					$res->rating = round(($rating/$reviews_count), 1);
				} else {
					$res->rating = 5;
				}
				
				$current_date = strtotime(date("Y-m-d"));
			  	$start_date = strtotime($res->new_start_date);
			  	$end_date = strtotime($res->new_end_date);
					
				if($res->new_start_date && $res->new_end_date != 0){
					if($current_date >= $start_date && $current_date <= $end_date){   
						$res->res_new = "New";
					}else{
						$res->res_new = ""; 
					}
				}else{
					$res->res_new = ""; 
				}
				
				$dist = round($res->distance);
				$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
				if($delivery_dist !=''){
					$delivery_time = ($res->delivery_time) + ($delivery_dist->mins);
				} else {
					$delivery_time = ($res->delivery_time) + (75);
				}
				
				$restaurants[] = array(											
							  "id"				=> (string)$res->id,
							  "name"			=> $res->name,
							  "location"		=> $res->location,
							  "logo"			=> $res->logo,
							  "partner_id"		=> (string)$res->partner_id,
							  "opening_time"	=> $res->opening_time,
							  "closing_time"	=> $res->closing_time,
							  "phone"			=> (string)$res->phone,
							  "secondary_phone_number"=> (string)$res->secondary_phone_number,
							  "max_packaging_charge"=> (string)$res->max_packaging_charge,
							  "service_tax"		=> (string)$res->service_tax,
							  "delivery_charge"	=> (string)$res->delivery_charge,
							  "vat"				=> (string)$res->vat,
							  "cuisine"			=> $res->cuisine,
							  "call_handling"	=> (string)$res->call_handling,
							  "delivery_time"	=> (string)$delivery_time,
							  "pure_veg"		=> (string)$res->pure_veg,
							  "offer"			=> (string)$res->offer,
							  "min_order_value"	=> (string)$res->min_order_value,
							  "max_value"		=> (string)$res->max_value,
							  "offer_from"		=> $res->offer_from,
							  "offer_to"		=> $res->offer_to,
							  "rating"			=> (string)$res->rating,
							  "entry_by"		=> (string)$res->entry_by,
							  "latitude"		=> (string)$res->latitude,
							  "longitude"		=> (string)$res->longitude,
							  "res_desc"		=> $res->res_desc,
							  "food_item"		=> $res->food_item,
							  "res_new"		    => $res->res_new,
							  "res_status"		=> $res->res_status,
							);
			}
	
			if(empty($restaurants) && empty($items)){
	
				$response['message'] 			= "No results found";
				$response['restaurants'] 		= array();
				$response['prod_suggestion'] 	= array();
				echo json_encode($response);exit;
			
			} elseif(empty($restaurants) && !empty($items) || !empty($restaurants) && !empty($items)) {	
			
				$items1[] = array("food_item" => $_REQUEST['search_text']);
				$items = array_unique(array_merge($items1,$items), SORT_REGULAR);
	
				$response['message'] 			= "Success";
				
				if(empty($restaurants)){
					$response['restaurants'] 	= array();
				} else {
					$response['restaurants'] 	= $restaurants;
				}
				if(empty($items)){
					$response['prod_suggestion'] = array();
				} else {
					$response['prod_suggestion'] = $items;
				}
				
				echo json_encode($response);exit;
			
			} elseif(!empty($restaurants) && empty($items)) {			
				
				$response['message'] 			= "Success";
				$response['restaurants'] 		= $restaurants;
				$response['prod_suggestion'] 	= array();
				echo json_encode($response);exit;
				
			}
		} else {
			$response['message'] 			= "No results found";
			$response['restaurants'] 		= array();
			$response['prod_suggestion'] 	= array();
			echo json_encode($response); exit;
		}
	}
	
	public function postResprodsearch( Request $request) {
				
		$query1 = "SELECT * FROM `abserve_hotel_items` WHERE `food_item` LIKE '%".$_REQUEST['search_text']."%'";
		$items = \DB::select($query1);

		if(empty($items)){

			$response['message'] 			= "No results found";
			$response['restaurants'] 		= '';
			$response['prod_suggestion'] 	= '';
			echo json_encode($response);exit;
		
		} else {
			
			foreach($items as $value){
				$newArr[$value->restaurant_id][] = $value;
			}
			foreach($newArr as $key => $values){
				$query = "SELECT * FROM `abserve_restaurants` WHERE `id`='".$key."'";
				$res = \DB::select($query);
				
				/*$cuisines = $res[0]->cuisine;
				
				$cusine = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines` WHERE `id` IN (".$cuisines.")");
				foreach($cusine as $cus){
					$_cus[] = $cus->name;
				}				
				$_cusine = implode(',',$_cus);*/
				if($res[0]->logo != ''){
					$res[0]->logo=\URL::to('').'/uploads/restaurants/'.$res[0]->logo;
				} else {
					$res[0]->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				if($res[0]->cuisine != ''){
					$res[0]->cuisine = $this->cuisines($res[0]->cuisine);
				}
				
				foreach($values as $value){
				
					if($value->topping_category != ""){ 
								
						$topping_id = $value->topping_category;								
						$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
													
						foreach($top_categories as $top_cat){
															
							$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
							$topping_items = "";
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
					
					if($value->image != ''){
						$value->image = \URL::to('').'/uploads/res_items/'.$value->restaurant_id.'/'.$value->image;
					}else{
						$value->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					
					$products[] = array(
									"id"			=> $value->id,
									"restaurant_id"	=> $value->restaurant_id,
									"food_item"		=> $value->food_item,
									"description"	=> $value->description,
									"price"			=> $value->price,
									"status"		=> $value->status,
									"available_from"=> $value->available_from,
									"available_to"	=> $value->available_to,
									"item_status"	=> $value->item_status,
									"image"			=> $value->image,
									"main_cat"		=> $value->main_cat,
									"Sub_cat"		=> $value->Sub_cat,
									"recommended"	=> $value->recommended,
									"customize"		=> $value->customize,
									"cust_count"	=> $value->cust_count,
									"custom_title"	=> $value->custom_title,
									"custom_prize"	=> $value->custom_prize,
									"ingredients"	=> $value->ingredients,
									"topping_category"	=> $value->topping_category,
									"entry_by"		=> $value->entry_by,
									"toppings"		=> $topping_cats,
								);
				}
				$restaurants[] = array(											
							  "id"				=> $res[0]->id,
							  "name"			=> $res[0]->name,
							  "location"		=> $res[0]->location,
							  "logo"			=> $res[0]->logo,
							  "partner_id"		=> $res[0]->partner_id,
							  "opening_time"	=> $res[0]->opening_time,
							  "closing_time"	=> $res[0]->closing_time,
							  "phone"			=> $res[0]->phone,
						  	  "secondary_phone_number"=> $res[0]->secondary_phone_number,
           				  	  "max_packaging_charge"=> $res[0]->max_packaging_charge,
							  "service_tax"		=> $res[0]->service_tax,
							  "delivery_charge"	=> $res[0]->delivery_charge,
							  "vat"				=> $res[0]->vat,
							  "cuisine"			=> $res[0]->cuisine,
							  "call_handling"	=> $res[0]->call_handling,
							  "delivery_time"	=> $res[0]->delivery_time,
							  "pure_veg"		=> $res[0]->pure_veg,
							  "offer"			=> $res[0]->offer,
							  "budget"			=> $res[0]->budget,
							  "rating"			=> $res[0]->rating,
							  "entry_by"		=> $res[0]->entry_by,
							  "latitude"		=> $res[0]->latitude,
							  "longitude"		=> $res[0]->longitude,
							  "res_desc"		=> $res[0]->res_desc,
							  "products"		=> $products,							  
							);
			}
			
			$response['message'] 			= "Success";			
			$response['restaurants'] 		= $restaurants;
			echo json_encode($response);exit;
			
		}
	}
	
	public function postResprodsearch1( Request $request) {
				
		$_REQUEST = str_replace('"','', $_REQUEST);
	
		$radius = 15;
		$whr	= "WHERE 1 AND active!=2 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$_restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);		
		
		$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
		
		foreach ($_restaurants as $key => $_rest) {
			if($_rest->active == 1){
				$_rest_available_status = \SiteHelpers::getrestimeval($_rest->id);
				if($_rest_available_status == 1){
					$res_id[] = $_rest->id;
				}
			}
		}
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		if(count($res_id)>0){			
			$res_ids = implode("','",$res_id);			
			$query1 = "SELECT * FROM `abserve_hotel_items` WHERE `item_status`=1 AND `restaurant_id` IN ('".$res_ids."') and `food_item` LIKE '%".$_REQUEST['search_text']."%' AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
			$items = \DB::select($query1);

			if(empty($items)){
	
				$response['message'] 			= "No results found";
				$response['restaurants'] 		= '';
				$response['prod_suggestion'] 	= '';
				echo json_encode($response);exit;
			
			} else {
				
				foreach($items as $value){
					$newArr[$value->restaurant_id][] = $value;
				}
				$current_date = strtotime(date("Y-m-d"));
				
				foreach($newArr as $key => $values){
					$query = "SELECT * FROM `abserve_restaurants` WHERE `id`='".$key."'";
					$res = \DB::select($query);
					
					/*$cuisines = $res[0]->cuisine;
					
					$cusine = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines` WHERE `id` IN (".$cuisines.")");
					foreach($cusine as $cus){
						$_cus[] = $cus->name;
					}				
					$_cusine = implode(',',$_cus);*/
					if($res[0]->logo != ''){
						$res[0]->logo=\URL::to('').'/uploads/restaurants/'.$res[0]->logo;
					} else {
						$res[0]->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($res[0]->cuisine != ''){
						$res[0]->cuisine = $this->cuisines($res[0]->cuisine);
					}
						
					if($res[0]->active == 1){	
						$res[0]->available_status = \SiteHelpers::getrestimeval($res[0]->id);
						if($res[0]->available_status == 1){
							$res[0]->res_status = "open";
						} else {
							$res[0]->res_status = "close";
						}
					} else {
						$res[0]->res_status = "close";
					}
					
					$products = "";
					foreach($values as $value){
					
						if($value->topping_category != ""){ 
									
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
								$topping_items = "";
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
						
						if($value->image != ''){
							$value->image = \URL::to('').'/uploads/res_items/'.$value->restaurant_id.'/'.$value->image;
						}else{
							$value->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						$products[] = array(
										"id"			=> (string)$value->id,
										"restaurant_id"	=> (string)$value->restaurant_id,
										"food_item"		=> $value->food_item,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"image"			=> $value->image,
										"main_cat"		=> (string)$value->main_cat,
										"Sub_cat"		=> $value->Sub_cat,
										"recommended"	=> $value->recommended,
										"customize"		=> $value->customize,
										"cust_count"	=> (string)$value->cust_count,
										"custom_title"	=> $value->custom_title,
										"custom_prize"	=> (string)$value->custom_prize,
										"ingredients"	=> $value->ingredients,
										"topping_category"	=> $value->topping_category,
										"entry_by"		=> (string)$value->entry_by,
										"toppings"		=> $topping_cats,
									);
					}
					
					$offer_to = strtotime($res[0]->offer_to);
					if($current_date > $offer_to){
						$res[0]->offer_from = "0000-00-00";
						$res[0]->offer_to = "0000-00-00";
						$res[0]->offer = "0";
					}
					$res[0]->rating = "";
					$rating = "";				
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$res[0]->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($res[0]->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$res[0]->rating = round(($rating/$reviews_count), 1);
					} else {
						$res[0]->rating = 5;
					}
					
					$dist = round($res->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res->delivery_time) + (75);
					}
					
					$dist = round($res[0]->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res[0]->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res[0]->delivery_time) + (75);
					}
					
					$restaurants[] = array(											
								  "id"				=> (string)$res[0]->id,
								  "name"			=> $res[0]->name,
								  "location"		=> $res[0]->location,
								  "logo"			=> $res[0]->logo,
								  "partner_id"		=> (string)$res[0]->partner_id,
								  "opening_time"	=> $res[0]->opening_time,
								  "closing_time"	=> $res[0]->closing_time,
								  "phone"			=> (string)$res[0]->phone,
								  "secondary_phone_number"=> (string)$res[0]->secondary_phone_number,
								  "max_packaging_charge"=> (string)$res[0]->max_packaging_charge,
								  "service_tax"		=> (string)$res[0]->service_tax,
								  "delivery_charge"	=> (string)$res[0]->delivery_charge,
								  "vat"				=> (string)$res[0]->vat,
								  "cuisine"			=> $res[0]->cuisine,
								  "call_handling"	=> (string)$res[0]->call_handling,
								  "delivery_time"	=> (string)$delivery_time,
								  "pure_veg"		=> (string)$res[0]->pure_veg,
								  "offer"			=> (string)$res[0]->offer,
								  "min_order_value"	=> (string)$res[0]->min_order_value,
								  "max_value"		=> (string)$res[0]->max_value,
								  "offer_from"		=> $res[0]->offer_from,
								  "offer_to"		=> $res[0]->offer_to,
								  "budget"			=> (string)$res[0]->budget,
								  "rating"			=> (string)$res[0]->rating,
								  "entry_by"		=> (string)$res[0]->entry_by,
								  "latitude"		=> (string)$res[0]->latitude,
								  "longitude"		=> (string)$res[0]->longitude,
								  "res_desc"		=> $res[0]->res_desc,
								  "available_status"=> $res[0]->available_status,
								  "res_status"		=> $res[0]->res_status,
								  "products"		=> $products,							  
								);
				}
				
				$response['message'] 			= "Success";			
				$response['restaurants'] 		= $restaurants;
				echo json_encode($response);exit;
				
			}
			
		} else {
			$response['message'] 			= "No results found";
			$response['restaurants'] 		= '';
			$response['prod_suggestion'] 	= '';
			echo json_encode($response);exit;	
		}
	}
	
	public function postResprodsearch2( Request $request) {
				
		$_REQUEST = str_replace('"','', $_REQUEST);
	
		$area = $this->locationdistance($_REQUEST['pin_code']);
		//print_r($area);
		//echo $area[0]->distance; exit;
		if(count($area)>0){
			$radius = $area[0]->distance;
		} else {
			$radius = 0;
		}
		
		$whr	= "WHERE 1 AND active!=2 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$_restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);		
		
		$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
		
		foreach ($_restaurants as $key => $_rest) {
			if($_rest->active == 1){
				$_rest_available_status = \SiteHelpers::getrestimeval($_rest->id);
				if($_rest_available_status == 1){
					$res_id[] = $_rest->id;
				}
			}
		}
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		if(count($res_id)>0){			
			$res_ids = implode("','",$res_id);			
			$query1 = "SELECT * FROM `abserve_hotel_items` WHERE `item_status`=1 AND `restaurant_id` IN ('".$res_ids."') and `food_item` LIKE '%".$_REQUEST['search_text']."%' AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
			$items = \DB::select($query1);

			if(empty($items)){
	
				$response['message'] 			= "No results found";
				$response['restaurants'] 		= '';
				$response['prod_suggestion'] 	= '';
				echo json_encode($response);exit;
			
			} else {
				
				foreach($items as $value){
					$newArr[$value->restaurant_id][] = $value;
				}
				$current_date = strtotime(date("Y-m-d"));
				
				foreach($newArr as $key => $values){
					$query = "SELECT * FROM `abserve_restaurants` WHERE `id`='".$key."'";
					$res = \DB::select($query);
					
					/*$cuisines = $res[0]->cuisine;
					
					$cusine = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines` WHERE `id` IN (".$cuisines.")");
					foreach($cusine as $cus){
						$_cus[] = $cus->name;
					}				
					$_cusine = implode(',',$_cus);*/
					if($res[0]->logo != ''){
						$res[0]->logo=\URL::to('').'/uploads/restaurants/'.$res[0]->logo;
					} else {
						$res[0]->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($res[0]->cuisine != ''){
						$res[0]->cuisine = $this->cuisines($res[0]->cuisine);
					}
						
					if($res[0]->active == 1){	
						$res[0]->available_status = \SiteHelpers::getrestimeval($res[0]->id);
						if($res[0]->available_status == 1){
							$res[0]->res_status = "open";
						} else {
							$res[0]->res_status = "close";
						}
					} else {
						$res[0]->res_status = "close";
					}
					
					$products = "";
					foreach($values as $value){
					
						if($value->topping_category != ""){ 
									
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
								$topping_items = "";
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
						
						if($value->image != ''){
							$value->image = \URL::to('').'/uploads/res_items/'.$value->restaurant_id.'/'.$value->image;
						}else{
							$value->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						$products[] = array(
										"id"			=> (string)$value->id,
										"restaurant_id"	=> (string)$value->restaurant_id,
										"food_item"		=> $value->food_item,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"image"			=> $value->image,
										"main_cat"		=> (string)$value->main_cat,
										"Sub_cat"		=> $value->Sub_cat,
										"recommended"	=> $value->recommended,
										"customize"		=> $value->customize,
										"cust_count"	=> (string)$value->cust_count,
										"custom_title"	=> $value->custom_title,
										"custom_prize"	=> (string)$value->custom_prize,
										"ingredients"	=> $value->ingredients,
										"topping_category"	=> $value->topping_category,
										"entry_by"		=> (string)$value->entry_by,
										"toppings"		=> $topping_cats,
									);
					}
					
					$offer_to = strtotime($res[0]->offer_to);
					if($current_date > $offer_to){
						$res[0]->offer_from = "0000-00-00";
						$res[0]->offer_to = "0000-00-00";
						$res[0]->offer = "0";
					}
					$res[0]->rating = "";
					$rating = "";				
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$res[0]->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($res[0]->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$res[0]->rating = round(($rating/$reviews_count), 1);
					} else {
						$res[0]->rating = 5;
					}
					
					$dist = round($res->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res->delivery_time) + (75);
					}
					
					$dist = round($res[0]->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res[0]->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res[0]->delivery_time) + (75);
					}
					
					$restaurants[] = array(											
								  "id"				=> (string)$res[0]->id,
								  "name"			=> $res[0]->name,
								  "location"		=> $res[0]->location,
								  "logo"			=> $res[0]->logo,
								  "partner_id"		=> (string)$res[0]->partner_id,
								  "opening_time"	=> $res[0]->opening_time,
								  "closing_time"	=> $res[0]->closing_time,
								  "phone"			=> (string)$res[0]->phone,
								  "secondary_phone_number"=> (string)$res[0]->secondary_phone_number,
								  "max_packaging_charge"=> (string)$res[0]->max_packaging_charge,
								  "service_tax"		=> (string)$res[0]->service_tax,
								  "delivery_charge"	=> (string)$res[0]->delivery_charge,
								  "vat"				=> (string)$res[0]->vat,
								  "cuisine"			=> $res[0]->cuisine,
								  "call_handling"	=> (string)$res[0]->call_handling,
								  "delivery_time"	=> (string)$delivery_time,
								  "pure_veg"		=> (string)$res[0]->pure_veg,
								  "offer"			=> (string)$res[0]->offer,
								  "min_order_value"	=> (string)$res[0]->min_order_value,
								  "max_value"		=> (string)$res[0]->max_value,
								  "offer_from"		=> $res[0]->offer_from,
								  "offer_to"		=> $res[0]->offer_to,
								  "budget"			=> (string)$res[0]->budget,
								  "rating"			=> (string)$res[0]->rating,
								  "entry_by"		=> (string)$res[0]->entry_by,
								  "latitude"		=> (string)$res[0]->latitude,
								  "longitude"		=> (string)$res[0]->longitude,
								  "res_desc"		=> $res[0]->res_desc,
								  "available_status"=> $res[0]->available_status,
								  "res_status"		=> $res[0]->res_status,
								  "products"		=> $products,							  
								);
				}
				
				$response['message'] 			= "Success";			
				$response['restaurants'] 		= $restaurants;
				echo json_encode($response);exit;
				
			}
			
		} else {
			$response['message'] 			= "No results found";
			$response['restaurants'] 		= '';
			$response['prod_suggestion'] 	= '';
			echo json_encode($response);exit;	
		}
	}
	
	public function postResprodsearch3( Request $request) {
				
		$_REQUEST = str_replace('"','', $_REQUEST);
	
		$area = $this->locationdistance($_REQUEST['pin_code']);
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
		//print_r($area);
		//echo $area[0]->distance; exit;
		if(count($area)>0){
			$radius = $area[0]->distance;
		} else {
			$radius = 0;
		}
		
		$whr	= "WHERE 1 AND active!=2 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$_restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);		
		
		$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
		
		foreach ($_restaurants as $key => $_rest) {
			if($_rest->active == 1){
				$_rest_available_status = \SiteHelpers::getrestimeval($_rest->id);
				if($_rest_available_status == 1){
					$res_id[] = $_rest->id;
				}
			}
		}
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		if(count($res_id)>0){			
			$res_ids = implode("','",$res_id);			
			$query1 = "SELECT * FROM `abserve_hotel_items` WHERE `item_status`=1 AND `restaurant_id` IN ('".$res_ids."') and `food_item` LIKE '%".$_REQUEST['search_text']."%' AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
			$items = \DB::select($query1);

			if(empty($items)){
	
				$response['message'] 			= "No results found";
				$response['restaurants'] 		= '';
				$response['prod_suggestion'] 	= '';
				echo json_encode($response);exit;
			
			} else {
				
				foreach($items as $value){
					$newArr[$value->restaurant_id][] = $value;
				}
				$current_date = strtotime(date("Y-m-d"));
				
				foreach($newArr as $key => $values){
					$query = "SELECT * FROM `abserve_restaurants` WHERE `id`='".$key."'";
					$res = \DB::select($query);
					
					/*$cuisines = $res[0]->cuisine;
					
					$cusine = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines` WHERE `id` IN (".$cuisines.")");
					foreach($cusine as $cus){
						$_cus[] = $cus->name;
					}				
					$_cusine = implode(',',$_cus);*/
					if($res[0]->logo != ''){
						$res[0]->logo=\URL::to('').'/uploads/restaurants/'.$res[0]->logo;
					} else {
						$res[0]->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($res[0]->cuisine != ''){
						$res[0]->cuisine = $this->cuisines($res[0]->cuisine);
					}
						
					if($res[0]->active == 1){	
						$res[0]->available_status = \SiteHelpers::getrestimeval($res[0]->id);
						if($res[0]->available_status == 1){
							$res[0]->res_status = "open";
						} else {
							$res[0]->res_status = "close";
						}
					} else {
						$res[0]->res_status = "close";
					}
					
					$products = "";
					foreach($values as $value){
					
						if($value->topping_category != ""){ 
									
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
								$topping_items = "";
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
						
						if($value->image != ''){
							$value->image = \URL::to('').'/uploads/res_items/'.$value->restaurant_id.'/'.$value->image;
						}else{
							$value->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						if($value->special_from !="0000-00-00"){
							if($current_date > strtotime($value->special_to)){
								$value->special_price = "0.00";
								$value->special_from = "0000-00-00";
								$value->special_to = "0000-00-00";
							} else {
								$value->special_price = $value->special_price;
								$value->special_from = date("d-m-Y",strtotime($value->special_from));
								$value->special_to = date("d-m-Y",strtotime($value->special_to));
							}
						}
						if($value->buy_qty !=0){
							if($current_date_time > strtotime($value->bogo_end_date)){
								$value->buy_qty = "0";
								$value->get_qty = "0";
								$value->bogo_name = "";
							}
						}
						
						$products[] = array(
										"id"			=> (string)$value->id,
										"restaurant_id"	=> (string)$value->restaurant_id,
										"food_item"		=> $value->food_item,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"special_price"	=> (string)$value->special_price,
										"special_from"	=> $value->special_from,
										"special_to"	=> $value->special_to,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"image"			=> $value->image,
										"main_cat"		=> (string)$value->main_cat,
										"Sub_cat"		=> $value->Sub_cat,
										"recommended"	=> $value->recommended,
										"customize"		=> $value->customize,
										"cust_count"	=> (string)$value->cust_count,
										"custom_title"	=> $value->custom_title,
										"custom_prize"	=> (string)$value->custom_prize,
										"ingredients"	=> $value->ingredients,
										"topping_category"	=> $value->topping_category,
										"entry_by"		=> (string)$value->entry_by,
										"toppings"		=> $topping_cats,
										"buy_qty"		=> (string)$value->buy_qty,
										"get_qty"		=> (string)$value->get_qty,
										"bogo_name"		=> $value->bogo_name,
									);
					}
					
					$offer_to = strtotime($res[0]->offer_to);
					if($current_date > $offer_to){
						$res[0]->offer_from = "0000-00-00";
						$res[0]->offer_to = "0000-00-00";
						$res[0]->offer = "0";
					}
					$res[0]->rating = "";
					$rating = "";				
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$res[0]->id)->where('rating','!=',0)->get();			
					
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($res[0]->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$res[0]->rating = round(($rating/$reviews_count), 1);
					} else {
						$res[0]->rating = 5;
					}
					
					$dist = round($res->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res->delivery_time) + (75);
					}
					
					$dist = round($res[0]->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res[0]->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res[0]->delivery_time) + (75);
					}
					
					$restaurants[] = array(											
								  "id"				=> (string)$res[0]->id,
								  "name"			=> $res[0]->name,
								  "location"		=> $res[0]->location,
								  "logo"			=> $res[0]->logo,
								  "partner_id"		=> (string)$res[0]->partner_id,
								  "opening_time"	=> $res[0]->opening_time,
								  "closing_time"	=> $res[0]->closing_time,
								  "phone"			=> (string)$res[0]->phone,
								  "secondary_phone_number"=> (string)$res[0]->secondary_phone_number,
								  "max_packaging_charge"=> (string)$res[0]->max_packaging_charge,
								  "service_tax"		=> (string)$res[0]->service_tax,
								  "delivery_charge"	=> (string)$res[0]->delivery_charge,
								  "vat"				=> (string)$res[0]->vat,
								  "cuisine"			=> $res[0]->cuisine,
								  "call_handling"	=> (string)$res[0]->call_handling,
								  "delivery_time"	=> (string)$delivery_time,
								  "pure_veg"		=> (string)$res[0]->pure_veg,
								  "offer"			=> (string)$res[0]->offer,
								  "min_order_value"	=> (string)$res[0]->min_order_value,
								  "max_value"		=> (string)$res[0]->max_value,
								  "offer_from"		=> $res[0]->offer_from,
								  "offer_to"		=> $res[0]->offer_to,
								  "budget"			=> (string)$res[0]->budget,
								  "rating"			=> (string)$res[0]->rating,
								  "entry_by"		=> (string)$res[0]->entry_by,
								  "latitude"		=> (string)$res[0]->latitude,
								  "longitude"		=> (string)$res[0]->longitude,
								  "res_desc"		=> $res[0]->res_desc,
								  "available_status"=> $res[0]->available_status,
								  "res_status"		=> $res[0]->res_status,
								  "products"		=> $products,							  
								);
				}
				
				$response['message'] 			= "Success";			
				$response['restaurants'] 		= $restaurants;
				echo json_encode($response);exit;
				
			}
			
		} else {
			$response['message'] 			= "No results found";
			$response['restaurants'] 		= '';
			$response['prod_suggestion'] 	= '';
			echo json_encode($response);exit;	
		}
	}
	
	public function postResprodsearch4( Request $request) {
				
		$_REQUEST = str_replace('"','', $_REQUEST);
	
		$area = $this->locationdistance($_REQUEST['pin_code']);
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
		//print_r($area);
		//echo $area[0]->distance; exit;
		if(count($area)>0){
			$radius = $area[0]->distance;
		} else {
			$radius = 0;
		}
		
		$whr	= "WHERE 1 AND active!=2 ";

		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){
			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ORDER BY distance";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND active!=2 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
		} else {
			$lat_lng = $hav	= '';
			$whr .= "AND `location` LIKE '%".$_REQUEST['location']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['location'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['location'];
		}

		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ''){
			$whr .= " AND `budget` IN (".$_REQUEST['budget'].")";
		}

		/*if(isset($_REQUEST['cuisine']) && $_REQUEST['cuisine'] != ''){
			$whr .= " AND `cuisine` IN (".$_REQUEST['cuisine'].") ";
		}*/

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ''){
			$whr .= " AND `offer` <> 0 ";
		}

		if(isset($_REQUEST['delivery_time']) && $_REQUEST['delivery_time'] == '1'){
			$cond = " ORDER BY `delivery_time`";
		}

		$_restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);		
		
		$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");
		
		foreach ($_restaurants as $key => $_rest) {
			if($_rest->active == 1){
				$_rest_available_status = \SiteHelpers::getrestimeval($_rest->id);
				if($_rest_available_status == 1){
					$res_id[] = $_rest->id;
				}
			}
		}
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		if(count($res_id)>0){			
			$res_ids = implode("','",$res_id);			
			$query1 = "SELECT * FROM `abserve_hotel_items` WHERE `item_status`=1 AND `restaurant_id` IN ('".$res_ids."') and `food_item` LIKE '%".$_REQUEST['search_text']."%' AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
			$items = \DB::select($query1);

			if(empty($items)){
	
				$response['message'] 			= "No results found";
				$response['restaurants'] 		= '';
				$response['prod_suggestion'] 	= '';
				echo json_encode($response);exit;
			
			} else {
				
				foreach($items as $value){
					$newArr[$value->restaurant_id][] = $value;
				}
				$current_date = strtotime(date("Y-m-d"));
				
				foreach($newArr as $key => $values){
					$query = "SELECT * FROM `abserve_restaurants` WHERE `id`='".$key."'";
					$res = \DB::select($query);
					
					/*$cuisines = $res[0]->cuisine;
					
					$cusine = \DB::select("SELECT `id`,`name` FROM `abserve_food_cuisines` WHERE `id` IN (".$cuisines.")");
					foreach($cusine as $cus){
						$_cus[] = $cus->name;
					}				
					$_cusine = implode(',',$_cus);*/
					if($res[0]->logo != ''){
						$res[0]->logo=\URL::to('').'/uploads/restaurants/'.$res[0]->logo;
					} else {
						$res[0]->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					if($res[0]->cuisine != ''){
						$res[0]->cuisine = $this->cuisines($res[0]->cuisine);
					}
						
					if($res[0]->active == 1){	
						$res[0]->available_status = \SiteHelpers::getrestimeval($res[0]->id);
						if($res[0]->available_status == 1){
							$res[0]->res_status = "open";
						} else {
							$res[0]->res_status = "close";
						}
					} else {
						$res[0]->res_status = "close";
					}
					
					$products = "";
					foreach($values as $value){
					
						if($value->topping_category != ""){ 
									
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
								$topping_items = "";
								foreach($prod_topp as $prod_toppings){
									$topping_items = array();
									$topping_items[] = array(
														"topping_id"	=> (string)$prod_toppings->topping_id,
														"topping_name"	=> $prod_toppings->topping_name,
														"topping_type"	=> $prod_toppings->topping_type,
														"topping_price"	=> (string)$prod_toppings->topping_price,
													  );
								}
								$topping_cats = array();
								$topping_cats[] = array(
											"toppings_cat"		=> $top_cat->toppings_cat,
											"toppings_type"		=> $top_cat->toppings_type,
											"toppings_items"	=> $topping_items,
										  );
								
							}
							
						} else {
							$topping_cats = array();
						}
						
						if($value->image != ''){
							$value->image = \URL::to('').'/uploads/res_items/'.$value->restaurant_id.'/'.$value->image;
						}else{
							$value->image = \URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						if($value->special_from !="0000-00-00"){
							if($current_date > strtotime($value->special_to)){
								$value->special_price = "0.00";
								$value->special_from = "0000-00-00";
								$value->special_to = "0000-00-00";
							} else {
								$value->special_price = $value->special_price;
								$value->special_from = date("d-m-Y",strtotime($value->special_from));
								$value->special_to = date("d-m-Y",strtotime($value->special_to));
							}
						}
						if($value->buy_qty !=0){
							if($current_date_time > strtotime($value->bogo_end_date)){
								$value->buy_qty = "0";
								$value->get_qty = "0";
								$value->bogo_name = "";
							}
						}
						$products = array();
						$products[] = array(
										"id"			=> (string)$value->id,
										"restaurant_id"	=> (string)$value->restaurant_id,
										"food_item"		=> $value->food_item,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"special_price"	=> (string)$value->special_price,
										"special_from"	=> $value->special_from,
										"special_to"	=> $value->special_to,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"max_packaging_charge"	=> (string)$value->max_packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"image"			=> $value->image,
										"main_cat"		=> (string)$value->main_cat,
										"Sub_cat"		=> $value->Sub_cat,
										"recommended"	=> $value->recommended,
										"customize"		=> $value->customize,
										"cust_count"	=> (string)$value->cust_count,
										"custom_title"	=> $value->custom_title,
										"custom_prize"	=> (string)$value->custom_prize,
										"ingredients"	=> $value->ingredients,
										"topping_category"	=> $value->topping_category,
										"entry_by"		=> (string)$value->entry_by,
										"toppings"		=> $topping_cats,
										"buy_qty"		=> (string)$value->buy_qty,
										"get_qty"		=> (string)$value->get_qty,
										"bogo_name"		=> $value->bogo_name,
									);
					}
					
					$offer_to = strtotime($res[0]->offer_to);
					if($current_date > $offer_to){
						$res[0]->offer_from = "0000-00-00";
						$res[0]->offer_to = "0000-00-00";
						$res[0]->offer = "0";
					}
					$res[0]->rating = "";
					$rating = "";				
					$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$res[0]->id)->where('rating','!=',0)->get();			
					$rating = 0;
					if(count($Reviews)>0){											
						foreach ($Reviews as $key => $aReviews) {
							if($res[0]->id == $aReviews->res_id){
								//$Reviews[] = $aReviews->id;
								$rating += $aReviews->rating;
							}
						}
						$reviews_count = count($Reviews);
						$res[0]->rating = round(($rating/$reviews_count), 1);
					} else {
						$res[0]->rating = 5;
					}
					
					$dist = round($res->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res->delivery_time) + (75);
					}
					
					$dist = round($res[0]->distance);
					$delivery_dist = \DB::table('delivery_time')->select('*')->where('start_km', '<=', ($dist))->where('end_km', '>=', ($dist))->first();
					if($delivery_dist !=''){
						$delivery_time = ($res[0]->delivery_time) + ($delivery_dist->mins);
					} else {
						$delivery_time = ($res[0]->delivery_time) + (75);
					}
					
					$restaurants[] = array(											
								  "id"				=> (string)$res[0]->id,
								  "name"			=> $res[0]->name,
								  "location"		=> $res[0]->location,
								  "logo"			=> $res[0]->logo,
								  "partner_id"		=> (string)$res[0]->partner_id,
								  "opening_time"	=> $res[0]->opening_time,
								  "closing_time"	=> $res[0]->closing_time,
								  "phone"			=> (string)$res[0]->phone,
								  "secondary_phone_number"=> (string)$res[0]->secondary_phone_number,
								 // "max_packaging_charge"=> (string)$res[0]->max_packaging_charge,
								  "service_tax"		=> (string)$res[0]->service_tax,
								  "delivery_charge"	=> (string)$res[0]->delivery_charge,
								  "vat"				=> (string)$res[0]->vat,
								  "cuisine"			=> $res[0]->cuisine,
								  "call_handling"	=> (string)$res[0]->call_handling,
								  "delivery_time"	=> (string)$delivery_time,
								  "pure_veg"		=> (string)$res[0]->pure_veg,
								  "offer"			=> (string)$res[0]->offer,
								  "min_order_value"	=> (string)$res[0]->min_order_value,
								  "max_value"		=> (string)$res[0]->max_value,
								  "offer_from"		=> $res[0]->offer_from,
								  "offer_to"		=> $res[0]->offer_to,
								  "budget"			=> (string)$res[0]->budget,
								  "rating"			=> (string)$res[0]->rating,
								  "entry_by"		=> (string)$res[0]->entry_by,
								  "latitude"		=> (string)$res[0]->latitude,
								  "longitude"		=> (string)$res[0]->longitude,
								  "res_desc"		=> $res[0]->res_desc,
								  "available_status"=> $res[0]->available_status,
								  "res_status"		=> $res[0]->res_status,
								  "products"		=> $products,							  
								);
				}
				
				$response['message'] 			= "Success";			
				$response['restaurants'] 		= $restaurants;
				echo json_encode($response);exit;
				
			}
			
		} else {
			$response['message'] 			= "No results found";
			$response['restaurants'] 		= '';
			$response['prod_suggestion'] 	= '';
			echo json_encode($response);exit;	
		}
	}

	public function postAddcommentsrating( Request $request)
	{
		$rules = array(
			'user_id'    =>'required|numeric',
			'res_id'     =>'required|numeric',
			'order_id'    =>'required|numeric',
			//'comments'   =>'required',
			'rating'     =>'required|numeric',
			);
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$food_reviews = \DB::table('abserve_food_reviews')->where('order_id', $request->order_id)->get();

			if(count($food_reviews)>0){
				$response["id"]         = "0";
				$response["message"]    = "Reviews already added";
			} else {

				$aFood_comment = array(
					'user_id'     =>$request->user_id,
					'res_id'      =>$request->res_id,
					'order_id'    =>$request->order_id,
					'comments'    =>$request->comments,
					'rating'      =>$request->rating,
					'created'     => time(),
					);
							
				$cinsert= \DB::table('abserve_food_reviews')->insertGetId($aFood_comment);
							
				$order_rating_update = \DB::table('abserve_order_details')->where('id', $request->order_id)
						 ->update(['rating_flag' => 1]);
							
				if($cinsert!='0'){
					$response["id"]         = "1";
					$response["message"]    = "Added successfully";
				}else{
					$response["id"]         = "0";
					$response["message"]    = "Dosen't added";
				}

			}

		}else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["id"] 		= "5";
			$response["message"]    = $error;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;
		
	}

	public function postShowreviews(Request $request)
	{
		$rules = array(
			'res_id'     =>'required|numeric',
			'food_id'    =>'required|numeric',
			);
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
		$aReviews=\DB::table('abserve_food_reviews')->select('*')->where('food_id',$request->food_id)->where('res_id',$request->res_id)->get();
		if(count($aReviews)>0){
			    $response["id"]         = "1";
				$response["reviews"]    = $aReviews;
			}else{
				$response["id"]         = "0";
				$response["reviews"]    = [];
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["id"] 		= "5";
			$response["message"]    = $error;
		}
		echo json_encode($response); exit;
	}
	
	public function postRestaurantreviews(Request $request)
	{
		$rules = array( 
			'res_id'     =>'required|numeric',
			);
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			//$aReviews=\DB::table('abserve_food_reviews')->select('*')->where('res_id',$request->res_id)->get();
                        
			$Reviews = \DB::table('abserve_food_reviews')->select('*')->where('res_id',$_REQUEST['res_id'])->groupBy('order_id')->orderBy('id', 'DESC')->get();
			$rating = 0;
					
			$restaurant = \DB::table('abserve_restaurants')->select('*')->where('id',$_REQUEST['res_id'])->first();			
			
			$res_cuisine = "";
			if($restaurant->logo != ''){
				$res_img=\URL::to('').'/uploads/restaurants/'.$restaurant->logo;
			} else {
				$res_img=\URL::to('').'/uploads/restaurants/Default_food.jpg';
			}
			if($restaurant->cuisine != ''){
				$res_cuisine = $this->cuisines($restaurant->cuisine);
			}
			
			if(count($Reviews)>0){
			    $response["id"]         = "1";
				
				foreach ($Reviews as $key => $aReviews) {
					
					$res_id = $aReviews->res_id;
					
					$user = \DB::table('tb_users')->select('*')->where('id',$aReviews->user_id)->first();
					if($aReviews->rating !=0){
						$rating += $aReviews->rating;
						$review_count[] = $aReviews->user_id;
					}
					
					$reviews[] = array(
										"id"		=> $aReviews->id,
										"user_id"	=> $aReviews->user_id,
										"first_name"=> $user->first_name,
										"last_name"	=> $user->last_name,
										"res_id"	=> $aReviews->res_id,
										"order_id"	=> $aReviews->order_id,
										"comments"	=> $aReviews->comments,
										"rating"	=> $aReviews->rating,
										"created"	=> date('Y-m-d', $aReviews->created),
								);
				}
				
				if($rating !=""){
					$reviews_count = count($review_count);
					$ratings = $rating/$reviews_count;
				} else {
					$ratings = "5";
				}
				
				$response["res_name"] = $restaurant->name;
				$response["res_img"] = $res_img;
				$response["res_cuisine"] = $res_cuisine;
				$response["overall_ratings"] = $ratings;
				$response["reviews"] = $reviews;
			}else{
				$response["id"]         = "0";
				$response["res_name"] 	= $restaurant->name;
				$response["res_img"] 	= $res_img;
				$response["res_cuisine"] = $res_cuisine;
				$response["overall_ratings"] = "5";
				$response["reviews"]    = [];
			}
		}else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["id"] 		= "5";
			$response["message"]    = $error;
		}
		echo json_encode($response); 
		exit;
	}

	
	public function postNewrorderspartners( Request $request){
		
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
							// $odval['time'] = date('Y-m-d H:i:s', $odval['time']);
							foreach ($odval as $key_in => $value_in) {
								if($key_in == 'orderid'){
									$whole_orders[$value_in][] = $ods[$od];
								}
							}
						}
					}
					//echo "<pre>";print_r($orders);exit();
					$pquery = "SELECT * FROM `abserve_orders_partner` AS `po` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `po`.`orderid` WHERE `po`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$porders = \DB::select($pquery);
					// echo $pquery."<pre>";print_r($porders);exit();

					$query1 = "SELECT `od`.`id`,`time`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price`,`op`.`order_status` FROM `abserve_order_items` AS `oi` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `oi`.`orderid` JOIN `abserve_orders_partner` AS `op` ON `op`.`orderid` = `od`.`id` WHERE `oi`.`orderid` IN (".implode(',', $result['orderid']).") ORDER BY `od`.`time` DESC";
					$orders1 = \DB::select($query1);

					$data['oreders_value']['menu_orders'] = $orders1;

					if(!empty($orders1)){
						foreach ($orders1 as $ke => $vals) {
							$ods1[] = get_object_vars($vals);
						}

						foreach ($ods1 as $od1 => &$odval1) {
							$odval1['time'] = date('H:i:s', $odval1['time']);
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

						echo '<pre>';
						var_dump($whole_orders);
						exit;

						echo '<pre>';
						var_dump($array);
						exit;

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
                      /*foreach ($orders1 as $key => $val) {
                      	$val[$key]=$orders[$key];
                      }
*/
					 //echo "<pre>";print_r($orders1);exit();
					//$response['oreders_value']['new_orders'] = $orders;
					//$response['oreders_value']['menu_orders']['new_orders'] = $orders1;
					$response['message'] 		= "New orders found";
					$response['orders_values']['oredrs'] 	= $whole_orders;
					//$response['orders_partners'] 	=$orders;
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

	public function postNewrorderspartners1( Request $request){
	
		$response = $whole_orders = array();
	
		$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($id_exists){
				$aResponse = array();

				$aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `od`.`status`='0' AND `op`.`order_status`='0' order by `od`.`id` desc");
				
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
					$array['total_price'] 	= $aValue->total_price;
					$array['subtotal'] 		= $aValue->grand_total;
					$array['tax'] 			= $aValue->s_tax;
					$array['packaging_charge'] 	= $aValue->packaging_charge;
					$array['coupon_price'] 	= $aValue->coupon_price;

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

				
				$response['message'] = "New orders found";
				$response['order_value'] = $aTot;
				//echo "<pre>";var_dump($array); exit;
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

	public function postNewrorderspartners2( Request $request){
	
		$response = $whole_orders = array();
	
		$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($id_exists){
				$aResponse = array();

				$aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `od`.`status`='0' AND `op`.`order_status`='0' order by `od`.`id` desc");
				
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
					$array['total_price'] 	= $aValue->total_price;
					$array['subtotal'] 		= $aValue->grand_total;
					$array['tax'] 			= $aValue->s_tax;
					$array['packaging_charge'] 	= $aValue->packaging_charge;
					$array['coupon_price'] 	= $aValue->coupon_price;

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

				
				$response['message'] = "New orders found";
				$response['order_value'] = $aTot;
				//echo "<pre>";var_dump($array); exit;
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
	
	public function postNewrorderspartners3( Request $request){
	
		$response = $whole_orders = array();
	
		$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);
		$current_time = time();
		$before_five_min = $current_time-(60*1);

		if ($validator->passes()) {
			$id_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($id_exists){
				$aResponse = array();

				$aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." AND `od`.`status`='0' AND `od`.`time` <= ".$before_five_min." AND `op`.`order_status`='0' order by `od`.`id` desc");
				
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
					$array['packaging_charge'] 	= $aValue->packaging_charge;
					$array['coupon_price'] 	= $aValue->coupon_price;

					$aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);
					
					$aItem=array();
					//$topping_items = array();
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

				
				$response['message'] = "New orders found";
				$response['order_value'] = $aTot;
				//echo "<pre>";var_dump($array); exit;
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

	public function postPartnercategories(Request $request){
		$response = array();

		$restaurant_id	=	$_REQUEST['res_id'];
		
		$rules = array(
			'res_id'		=>'required'
			);		
		
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {

			//partner id based resturant categories
			//$categories = \DB::select("SELECT DISTINCT(`hi`.`main_cat`) as id,`fc`.`cat_name` as name FROM `abserve_restaurants` as `rs` JOIN `abserve_hotel_items` AS `hi` ON `rs`.id = `hi`.`restaurant_id` LEFT JOIN `abserve_food_categories` AS `fc` ON `hi`.`main_cat` = `fc`.`id` WHERE `rs`.`partner_id` = ".$partner_id);
			
			$categories = \DB::select("SELECT DISTINCT(`hi`.`main_cat`) as id,`fc`.`cat_name` as name FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `fc` ON `hi`.`main_cat` = `fc`.`id` WHERE `restaurant_id` = ".$restaurant_id);
	
			$response["categories"]      	= $categories;
			echo json_encode($response); exit;
		
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function appapimethod( $value = ''){   

		$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();

		return $appapi[0];
	}
	
	public function postProdstatusupdate(Request $request){
		$response = array();		
		
		$rules = array(
			'res_id'		=>'required',
			'food_id'		=>'required',
			'item_status'	=>'required'
			);		
		
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			$restaurant_id	=	$_REQUEST['res_id'];
			$food_id		=	$_REQUEST['food_id'];
			$item_status	=	$_REQUEST['item_status'];

			$update = \DB::table('abserve_hotel_items')->where('id','=',$food_id)->where('restaurant_id','=',$restaurant_id)->update(['item_status'=>$item_status]);	
			
			if($update){

				// Customer notification						
				/*$customers = \DB::table('tb_users')->select('*')->where('group_id','=',4)->where('mobile_token','!=','')->where('ios_flag','=',1)->get();
				
				foreach($customers as $customer){

					$mobile_token = $customer->mobile_token;
					$message = "Product reload:".$restaurant_id.":".$food_id.":".$item_status;
					
					if($customer->device == 'ios'){
						$message = "Product reload";
						$message1 = $restaurant_id.":".$food_id.":".$item_status;
					
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
				
				$response['message'] = "Item status updated successfully";
				
			} else {
				$response['message'] = "Not updated";
			}		
	
			echo json_encode($response); exit;
		
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postResproducts( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
	
		$rules = array(
			'main_cat'		=>'required',
			'res_id'		=>'required|numeric',
			'item_status'	=>'required|numeric'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond = " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}
				$current_time = date("H:i:s");
				$daynum = date("N", strtotime(date("D")));
				
				if(isset($_REQUEST['item_status']))
				{
					$cond = " AND `hi`.`item_status` = '".$_REQUEST['item_status']."' AND ((`hi`.`available_from` <= '".$current_time."' AND `hi`.`available_to` >= '".$current_time."') OR (`hi`.`breakfast_available_from` <= '".$current_time."' AND `hi`.`breakfast_available_to` >= '".$current_time."') OR (`hi`.`lunch_available_from` <= '".$current_time."' AND `hi`.`lunch_available_to` >= '".$current_time."') OR (`hi`.`dinner_available_from` <= '".$current_time."' AND `hi`.`dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`hi`.`available_days`)";
					
				}
				if($_REQUEST['main_cat'] != 'All'){

					if($_REQUEST['main_cat'] == "Recommended"){
						$cond .= " AND `hi`.`recommended` = '1'";
					}else{
						$cond .= ' AND `c`.`cat_name` = "'.$_REQUEST['main_cat'].'"';
					}
				}

				$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`ingredients`,`hi`.`main_cat`,`food_item` as item_name,`description`,`price`,`packaging_charge`,`status`,`available_from`,`available_to`,`display_order`,`item_status`,`recommended`,`topping_category`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond." ORDER BY `hi`.`display_order` ASC";
				
				$arry = \DB::select($qwert);	
				
				if(!empty($arry)){
					foreach ($arry as $key=>&$value) {
						
						if($value->image != ''){
							$value->image=\URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$value->image;
						}else{
							$value->image=\URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						if($value->topping_category != ""){ 
							
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							$topping_cats = array();							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
								$topping_items = "";
								foreach($prod_topp as $prod_toppings){
									$topping_items = array(
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
						
						$res_prods[] = array(
										"id"			=> (string)$value->id,
										"ingredients"	=> $value->ingredients,
										"main_cat"		=> (string)$value->main_cat,
										"item_name"		=> $value->item_name,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"recommended"	=> $value->recommended,
										"Sub_cat"		=> $value->Sub_cat,
										"Main_cat"		=> $value->Main_cat,
										"image"			=> $value->image,
										"toppings"		=> $topping_cats,
									);
					}
	
					$response["message"] 			= (array)"success";
					$response["restaurants"]     	= $res_prods;					
					echo json_encode($response); exit;
				} else {
					$response["message"] 			= (array)"No records found";
					echo json_encode($response); exit;
				}			
				
			}
			
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postResproducts1( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
		$current_date = strtotime(date("Y-m-d"));
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
	
		$rules = array(
			'main_cat'		=>'required',
			'res_id'		=>'required|numeric',
			'item_status'	=>'required|numeric'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond = " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}
				$current_time = date("H:i:s");
				$daynum = date("N", strtotime(date("D")));
				
				if(isset($_REQUEST['item_status']))
				{
					$cond = " AND `hi`.`item_status` = '".$_REQUEST['item_status']."' AND ((`hi`.`available_from` <= '".$current_time."' AND `hi`.`available_to` >= '".$current_time."') OR (`hi`.`breakfast_available_from` <= '".$current_time."' AND `hi`.`breakfast_available_to` >= '".$current_time."') OR (`hi`.`lunch_available_from` <= '".$current_time."' AND `hi`.`lunch_available_to` >= '".$current_time."') OR (`hi`.`dinner_available_from` <= '".$current_time."' AND `hi`.`dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`hi`.`available_days`)";
					
				}
				if($_REQUEST['main_cat'] != 'All'){

					if($_REQUEST['main_cat'] == "Recommended"){
						$cond .= " AND `hi`.`recommended` = '1'";
					}else{
						$cond .= " AND `c`.`cat_name` = '".$_REQUEST['main_cat']."'";
					}
				}

				$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`ingredients`,`hi`.`main_cat`,`food_item` as item_name,`description`,`price`,`special_price`,`special_from`,`special_to`,`packaging_charge`,`status`,`available_from`,`available_to`,`item_status`,`recommended`,`topping_category`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image`,`buy_qty`,`get_qty`,`bogo_item_id`,`bogo_name`,`bogo_start_date`,`bogo_end_date` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond."ORDER BY `hi`.`display_order` ASC";
				
				$arry = \DB::select($qwert);	
				
				if(!empty($arry)){
					foreach ($arry as $key=>&$value) {
						
						if($value->image != ''){
							$value->image=\URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$value->image;
						}else{
							$value->image=\URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						if($value->topping_category != ""){ 
							
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							$topping_cats = array();							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
								$topping_items = "";
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
						
						if($value->special_from !="0000-00-00"){							
							if($current_date > strtotime($value->special_to)){
								$value->special_price = "0.00";
								$value->special_from = "0000-00-00";
								$value->special_to = "0000-00-00";
							} else {
								$value->special_price = $value->special_price;
								$value->special_from = date("d-m-Y",strtotime($value->special_from));
								$value->special_to = date("d-m-Y",strtotime($value->special_to));
							}
						}
						if($value->buy_qty !=0){
							if($current_date_time > strtotime($value->bogo_end_date)){
								$value->buy_qty = "0";
								$value->get_qty = "0";
								$value->bogo_name = "";
							}
						}
						
						$res_prods[] = array(
										"id"			=> (string)$value->id,
										"ingredients"	=> $value->ingredients,
										"main_cat"		=> (string)$value->main_cat,
										"item_name"		=> $value->item_name,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"special_price"	=> (string)$value->special_price,
										"special_from"	=> $value->special_from,
										"special_to"	=> $value->special_to,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"recommended"	=> $value->recommended,
										"Sub_cat"		=> $value->Sub_cat,
										"Main_cat"		=> $value->Main_cat,
										"image"			=> $value->image,
										"toppings"		=> $topping_cats,
										"buy_qty"		=> (string)$value->buy_qty,
										"get_qty"		=> (string)$value->get_qty,
										"bogo_name"		=> $value->bogo_name,
										
									);
					}
	
					$response["message"] 			= (array)"success";
					$response["restaurants"]     	= $res_prods;					
					echo json_encode($response); exit;
				} else {
					$response["message"] 			= (array)"No records found";
					echo json_encode($response); exit;
				}			
				
			}
			
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postResproducts2( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
		$current_date = strtotime(date("Y-m-d"));
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
	
		$rules = array(
			'main_cat'		=>'required',
			'res_id'		=>'required|numeric',
			'item_status'	=>'required|numeric'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond .= " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}
				$current_time = date("H:i:s");
				$daynum = date("N", strtotime(date("D")));
				
				if(isset($_REQUEST['item_status']))
				{
					$cond .= " AND `hi`.`item_status` = '".$_REQUEST['item_status']."' AND ((`hi`.`available_from` <= '".$current_time."' AND `hi`.`available_to` >= '".$current_time."') OR (`hi`.`breakfast_available_from` <= '".$current_time."' AND `hi`.`breakfast_available_to` >= '".$current_time."') OR (`hi`.`lunch_available_from` <= '".$current_time."' AND `hi`.`lunch_available_to` >= '".$current_time."') OR (`hi`.`dinner_available_from` <= '".$current_time."' AND `hi`.`dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`hi`.`available_days`)";
				}
				
				
				if($_REQUEST['main_cat'] != 'All'){

					if($_REQUEST['main_cat'] == "Recommended"){
						$cond .= " AND `hi`.`recommended` = '1'";
					}else{
						$cond .= " AND `hi`.`main_cat` = '".$_REQUEST['main_cat']."'";
					}
				}
				
			
			$recommented = "SELECT `hi`.`main_cat`,`hi`.`id`,`hi`.`ingredients`,`hi`.`food_item`,`hi`.`description`,`hi`.`topping_category`,`hi`.`price`,`hi`.`item_status`,`hi`.`buy_qty`,`hi`.`get_qty`,`hi`.`status`,`hi`.`recommended`,`hi`.`image`,`hi`.`bogo_end_date`,`hi`.`sub_cat`,`hi`.`bogo_name`,`hi`.`special_price`,`hi`.`special_from`,`hi`.`special_to`,`hi`.`packaging_charge`,`afc`.`cat_name` from `abserve_hotel_items` as `hi` JOIN `abserve_food_categories` as `afc`  ON `hi`.`main_cat`=`afc`.`id`  WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond." AND `hi`.`recommended` = '1'";
			$recommented_items = \DB::select($recommented);
			
			$recomment_prods = array();
			foreach ($recommented_items as $key=>&$value) {
						
				if($value->image != ''){
					$value->image=\URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$value->image;
				}else{
					$value->image=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				
				if($value->topping_category != ""){ 
							
					$topping_id = $value->topping_category;								
					$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
					
					$topping_cats = array();	
										
					foreach($top_categories as $top_cat){
																
						$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
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
						
				if($value->special_from !="0000-00-00"){							
					if($current_date > strtotime($value->special_to)){
						$value->special_price = "0.00";
						$value->special_from = "0000-00-00";
						$value->special_to = "0000-00-00";
					} else {
						$value->special_price = $value->special_price;
						$value->special_from = date("d-m-Y",strtotime($value->special_from));
						$value->special_to = date("d-m-Y",strtotime($value->special_to));
					}
				}
				if($value->buy_qty !=0){
					if($current_date_time > strtotime($value->bogo_end_date)){
						$value->buy_qty = "0";
						$value->get_qty = "0";
						$value->bogo_name = "";
					}
				}
						
					
				$recomment_prods[] = array(
								"id"			=> (string)$value->id,
								"ingredients"	=> $value->ingredients,
								"main_cat"		=> (string)$value->main_cat,
								"item_name"		=> $value->food_item,
								"description"	=> $value->description,
								"price"			=> (string)$value->price,
								"special_price"	=> (string)$value->special_price,
								"special_from"	=> $value->special_from,
								"special_to"	=> $value->special_to,
								"packaging_charge"	=> (string)$value->packaging_charge,
								"status"		=> $value->status,
								"available_from"=> date("h:i:s A",strtotime($value->available_from)),
								"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
								"item_status"	=> $value->item_status,
								"recommended"	=> $value->recommended,
								"Sub_cat"		=> $value->sub_cat,
								"Main_cat"		=> $value->main_cat,
								"image"			=> $value->image,
								"toppings"		=> $topping_cats,
								"buy_qty"		=> (string)$value->buy_qty,
								"get_qty"		=> (string)$value->get_qty,
								"bogo_name"		=> $value->bogo_name,
								
							);
							
			}
			
			$cat_name2[] = array(
											"cat_name"		=> "Recommended",
											"products"		=> $recomment_prods,
										  );
			
			$qwert = "SELECT `hi`.`main_cat`,`hi`.`sub_cat`,`afc`.`cat_name` from `abserve_hotel_items` as `hi` JOIN `abserve_food_categories` as `afc`  ON `hi`.`main_cat`=`afc`.`id`  WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond." group by `hi`.`main_cat` ORDER BY `hi`.`cat_order_display` ASC";
				$arry1 = \DB::select($qwert);	
				//print_r($qwert);  exit;
				if(!empty($arry1)){
				foreach ($arry1 as $arr) {
				    
				$resproducts1 = "SELECT * FROM `abserve_hotel_items` as `hi` WHERE `hi`.`restaurant_id`= ".$_REQUEST['res_id'].$cond." AND `hi`.`main_cat` = '".$arr->main_cat."' ORDER BY `hi`.`display_order` ASC";
			//print_r($resproducts1);  exit;
			$resproducts = \DB::select($resproducts1);	
						
						$res_prods = array();
					foreach ($resproducts as $key=>&$value) {
						
						if($value->image != ''){
							$value->image=\URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$value->image;
						}else{
							$value->image=\URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						if($value->topping_category != ""){ 
							
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							$topping_cats = array();							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
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
						
						if($value->special_from !="0000-00-00"){							
							if($current_date > strtotime($value->special_to)){
								$value->special_price = "0.00";
								$value->special_from = "0000-00-00";
								$value->special_to = "0000-00-00";
							} else {
								$value->special_price = $value->special_price;
								$value->special_from = date("d-m-Y",strtotime($value->special_from));
								$value->special_to = date("d-m-Y",strtotime($value->special_to));
							}
						}
						if($value->buy_qty !=0){
							if($current_date_time > strtotime($value->bogo_end_date)){
								$value->buy_qty = "0";
								$value->get_qty = "0";
								$value->bogo_name = "";
							}
						}						
					
						$res_prods[] = array(
										"id"			=> (string)$value->id,
										"ingredients"	=> $value->ingredients,
										"main_cat"		=> (string)$value->main_cat,
										"item_name"		=> $value->food_item,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"special_price"	=> (string)$value->special_price,
										"special_from"	=> $value->special_from,
										"special_to"	=> $value->special_to,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"recommended"	=> $value->recommended,
										"Sub_cat"		=> $value->sub_cat,
										"Main_cat"		=> $value->main_cat,
										"image"			=> $value->image,
										"toppings"		=> $topping_cats,
										"buy_qty"		=> (string)$value->buy_qty,
										"get_qty"		=> (string)$value->get_qty,
										"bogo_name"		=> $value->bogo_name,
										
									);
								
					}
					
								$cat_name[] = array(
											"cat_name"		=> $arr->cat_name,
											"products"		=> $res_prods,
										  );
						
					 }
					 
					  
	
					$response["message"] 			= (array)"success";
					$response["restaurants"]     	= array_merge($cat_name2, $cat_name);;					
					echo json_encode($response); exit;
				} else {
					$response["message"] 			= (array)"No records found";
					echo json_encode($response); exit;
				}			
				
			}
			
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postResproducts3( Request $request){

		$response = array();
		$_REQUEST 	= str_replace('"','', $_REQUEST);
		$current_date = strtotime(date("Y-m-d"));
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
	
		$rules = array(
			'main_cat'		=>'required',
			'res_id'		=>'required|numeric',
			'item_status'	=>'required|numeric'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			if(isset($_REQUEST['main_cat']))
			{
				if(isset($_REQUEST['status']))
				{
					$cond .= " AND `hi`.`status` = '".$_REQUEST['status']."'";
				}
				$current_time = date("H:i:s");
				$daynum = date("N", strtotime(date("D")));
				
				if(isset($_REQUEST['item_status']))
				{
					$cond .= " AND `hi`.`item_status` = '".$_REQUEST['item_status']."' AND ((`hi`.`available_from` <= '".$current_time."' AND `hi`.`available_to` >= '".$current_time."') OR (`hi`.`breakfast_available_from` <= '".$current_time."' AND `hi`.`breakfast_available_to` >= '".$current_time."') OR (`hi`.`lunch_available_from` <= '".$current_time."' AND `hi`.`lunch_available_to` >= '".$current_time."') OR (`hi`.`dinner_available_from` <= '".$current_time."' AND `hi`.`dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`hi`.`available_days`)";
				}
				
				
				if($_REQUEST['main_cat'] != 'All'){

					if($_REQUEST['main_cat'] == "Recommended"){
						$cond .= " AND `hi`.`recommended` = '1'";
					}else{
						$cond .= " AND `hi`.`main_cat` = '".$_REQUEST['main_cat']."'";
					}
				}
				
			
			$recommented = "SELECT `hi`.`main_cat`,`hi`.`id`,`hi`.`ingredients`,`hi`.`food_item`,`hi`.`description`,`hi`.`topping_category`,`hi`.`price`,`hi`.`item_status`,`hi`.`buy_qty`,`hi`.`get_qty`,`hi`.`status`,`hi`.`recommended`,`hi`.`image`,`hi`.`bogo_end_date`,`hi`.`sub_cat`,`hi`.`bogo_name`,`hi`.`special_price`,`hi`.`special_from`,`hi`.`special_to`,`hi`.`packaging_charge`,`hi`.`max_packaging_charge`,`afc`.`cat_name` from `abserve_hotel_items` as `hi` JOIN `abserve_food_categories` as `afc`  ON `hi`.`main_cat`=`afc`.`id`  WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond." AND `hi`.`recommended` = '1'";
			$recommented_items = \DB::select($recommented);
			
			$recomment_prods = array();
			foreach ($recommented_items as $key=>&$value) {
						
				if($value->image != ''){
					$value->image=\URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$value->image;
				}else{
					$value->image=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				
				if($value->topping_category != ""){ 
							
					$topping_id = $value->topping_category;								
					$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
					
					$topping_cats = array();	
										
					foreach($top_categories as $top_cat){
																
						$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
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
						
				if($value->special_from !="0000-00-00"){							
					if($current_date > strtotime($value->special_to)){
						$value->special_price = "0.00";
						$value->special_from = "0000-00-00";
						$value->special_to = "0000-00-00";
					} else {
						$value->special_price = $value->special_price;
						$value->special_from = date("d-m-Y",strtotime($value->special_from));
						$value->special_to = date("d-m-Y",strtotime($value->special_to));
					}
				}
				if($value->buy_qty !=0){
					if($current_date_time > strtotime($value->bogo_end_date)){
						$value->buy_qty = "0";
						$value->get_qty = "0";
						$value->bogo_name = "";
					}
				}
						
					
				$recomment_prods[] = array(
								"id"			=> (string)$value->id,
								"ingredients"	=> $value->ingredients,
								"main_cat"		=> (string)$value->main_cat,
								"item_name"		=> $value->food_item,
								"description"	=> $value->description,
								"price"			=> (string)$value->price,
								"special_price"	=> (string)$value->special_price,
								"special_from"	=> $value->special_from,
								"special_to"	=> $value->special_to,
								"packaging_charge"	=> (string)$value->packaging_charge,
								"max_packaging_charge"	=> (string)$value->max_packaging_charge,
								"status"		=> $value->status,
								"available_from"=> date("h:i:s A",strtotime($value->available_from)),
								"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
								"item_status"	=> $value->item_status,
								"recommended"	=> $value->recommended,
								"Sub_cat"		=> $value->sub_cat,
								"Main_cat"		=> $value->main_cat,
								"image"			=> $value->image,
								"toppings"		=> $topping_cats,
								"buy_qty"		=> (string)$value->buy_qty,
								"get_qty"		=> (string)$value->get_qty,
								"bogo_name"		=> $value->bogo_name,
								
							);
							
			}
			
			$cat_name2[] = array(
											"cat_name"		=> "Recommended",
											"products"		=> $recomment_prods,
										  );
			
			$qwert = "SELECT `hi`.`main_cat`,`hi`.`sub_cat`,`afc`.`cat_name` from `abserve_hotel_items` as `hi` JOIN `abserve_food_categories` as `afc`  ON `hi`.`main_cat`=`afc`.`id`  WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond." group by `hi`.`main_cat` ORDER BY `hi`.`cat_order_display` ASC";
				$arry1 = \DB::select($qwert);	
				//print_r($qwert);  exit;
				if(!empty($arry1)){
				foreach ($arry1 as $arr) {
				    
				$resproducts1 = "SELECT * FROM `abserve_hotel_items` as `hi` WHERE `hi`.`restaurant_id`= ".$_REQUEST['res_id'].$cond." AND `hi`.`main_cat` = '".$arr->main_cat."' ORDER BY `hi`.`display_order` ASC";
			//print_r($resproducts1);  exit;
			$resproducts = \DB::select($resproducts1);	
						
						$res_prods = array();
					foreach ($resproducts as $key=>&$value) {
						
						if($value->image != ''){
							$value->image=\URL::to('').'/uploads/res_items/'.$_REQUEST['res_id'].'/'.$value->image;
						}else{
							$value->image=\URL::to('').'/uploads/restaurants/Default_food.jpg';
						}
						
						if($value->topping_category != ""){ 
							
							$topping_id = $value->topping_category;								
							$top_categories = \DB::select("SELECT `category` as `toppings_cat`, `type` as `toppings_type` FROM `toppings` WHERE `id` IN (".$topping_id.")");
							
							$topping_cats = array();							
							foreach($top_categories as $top_cat){
																
								$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`,  `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$value->id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
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
						
						if($value->special_from !="0000-00-00"){							
							if($current_date > strtotime($value->special_to)){
								$value->special_price = "0.00";
								$value->special_from = "0000-00-00";
								$value->special_to = "0000-00-00";
							} else {
								$value->special_price = $value->special_price;
								$value->special_from = date("d-m-Y",strtotime($value->special_from));
								$value->special_to = date("d-m-Y",strtotime($value->special_to));
							}
						}
						if($value->buy_qty !=0){
							if($current_date_time > strtotime($value->bogo_end_date)){
								$value->buy_qty = "0";
								$value->get_qty = "0";
								$value->bogo_name = "";
							}
						}						
					
						$res_prods[] = array(
										"id"			=> (string)$value->id,
										"ingredients"	=> $value->ingredients,
										"main_cat"		=> (string)$value->main_cat,
										"item_name"		=> $value->food_item,
										"description"	=> $value->description,
										"price"			=> (string)$value->price,
										"special_price"	=> (string)$value->special_price,
										"special_from"	=> $value->special_from,
										"special_to"	=> $value->special_to,
										"packaging_charge"	=> (string)$value->packaging_charge,
										"max_packaging_charge"	=> (string)$value->max_packaging_charge,
										"status"		=> $value->status,
										"available_from"=> date("h:i:s A",strtotime($value->available_from)),
										"available_to"	=> date("h:i:s A",strtotime($value->available_to)),
										"item_status"	=> $value->item_status,
										"recommended"	=> $value->recommended,
										"Sub_cat"		=> $value->sub_cat,
										"Main_cat"		=> $value->main_cat,
										"image"			=> $value->image,
										"toppings"		=> $topping_cats,
										"buy_qty"		=> (string)$value->buy_qty,
										"get_qty"		=> (string)$value->get_qty,
										"bogo_name"		=> $value->bogo_name,
										
									);
								
					}
					
								$cat_name[] = array(
											"cat_name"		=> $arr->cat_name,
											"products"		=> $res_prods,
										  );
						
					 }
					 
					  
	
					$response["message"] 			= (array)"success";
					$response["restaurants"]     	= array_merge($cat_name2, $cat_name);;					
					echo json_encode($response); exit;
				} else {
					$response["message"] 			= (array)"No records found";
					echo json_encode($response); exit;
				}			
				
			}
			
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postBusinessmetrics(Request $request){
		$response = array();		
		
		$rules = array(
			'res_id'		=>'required|numeric',
			'duration'		=>'required'
		);		
		
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			$restaurant_id	=	$_REQUEST['res_id'];
			
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
			
			$query = "SELECT `od`.`id`,`res_id`,`status`,`total_price`,`grand_total`,`s_tax`,`coupon_price`,`delivery_charge`,`op`.`order_status` FROM `abserve_orders_partner` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `od`.`res_id`='".$restaurant_id."' AND (`op`.`order_status`='4' OR `op`.`order_status` = '11') ".$cond;
			$today_order = \DB::select($query);	
			
			if(!empty($today_order)){
				$order_count = count($today_order);
				
				$total_price=0;
				$grand_total=0;
				foreach($today_order as $key=>$today_orders)
				{			   
				   $total_price += $today_orders->total_price;
				   $grand_total += ($today_orders->grand_total - $today_orders->delivery_charge);
				}
				
				$metrics = array(
							'total_price'	=> $total_price,
							'grand_total'	=> $grand_total,
							'order_count'	=> $order_count
							);
								
				$response["message"] 		= (array)"success";
				$response["metrics"]     	= $metrics;
				echo json_encode($response); exit;
			} else {
				$response["message"] 	= (array)"No records found";
				echo json_encode($response); exit;
			}
		
		} else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}	
	
	public function postDeliveryboypaymentcount(Request $request){
		$response = array();		
		
		$rules = array(
			'boy_id'		=>'required|numeric',
			'duration'		=>'required'
		);		
		
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			$boy_id	=	$_REQUEST['boy_id'];
			
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
		
		
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='cod' AND `od`.`mop`='cash'".$cond;
			$cod = \DB::select($query);
			foreach($cod as $key=>$today_orders)
				{			   
				   $grand_total_cod += ($today_orders->grand_total);
				}
			
			
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='ccavenue'".$cond;
			$ccavenue = \DB::select($query);
			foreach($ccavenue as $key=>$today_orders)
				{			   
				   $grand_total_ccavenue += ($today_orders->grand_total);
				}
			
			
			$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='cod' AND `od`.`mop`='tez'".$cond;
			$tez = \DB::select($query);	
			foreach($tez as $key=>$today_orders)
				{			   
				   $grand_total_tez += ($today_orders->grand_total);
				   
				}
			
			$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='cod' AND `od`.`mop`='paytm'".$cond;
			$paytm = \DB::select($query);	
			foreach($paytm as $key=>$today_orders)
				{			   
				   $grand_total_paytm += ($today_orders->grand_total);
				}	
				
			
			$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='11' AND `od`.`delivery_type`='cod'".$cond;
			$return_cod = \DB::select($query);		
			foreach($return_cod as $key=>$today_orders)
				{			   
				   $grand_return_cod += ($today_orders->grand_total);
				}
			
			$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='11' AND `od`.`delivery_type`='ccavenue'".$cond;
			$return_ccavenue = \DB::select($query);
			foreach($return_ccavenue as $key=>$today_orders)
				{			   
				   $grand_return_ccaveneue += ($today_orders->grand_total);
				}		
			
			$query = "SELECT SUM(`op`.`distance`) as `total_kilometer` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND ( `op`.`order_status`='4' OR `op`.`order_status`='11' ) ".$cond;
			$total_kilometers = \DB::select($query);
			
			if(!empty($cod) || !empty($ccavenue) || !empty($tez) || !empty($paytm) || !empty($return_cod) || !empty($return_ccavenue) ){
			
			
				if($grand_total_cod == ''){
				$grand_total_cod = 0;	
				}if($grand_total_ccavenue == ''){
				$grand_total_ccavenue = 0;	
				}
				if($grand_total_tez == ''){
				$grand_total_tez = 0;
				}
				if($grand_total_paytm == ''){
				$grand_total_paytm = 0;	
				}if($grand_return_cod == ''){
				$grand_return_cod = 0;	
				}if($grand_return_ccaveneue == ''){
				$grand_return_ccaveneue = 0;	
				}
				if($total_kilometers == ''){
				$total_kilometers = 0;	
				}
			
				$order_count = array(
							'cod'	=> count($cod),
							'cod_amount' => $grand_total_cod,
							'ccavenue'	=> count($ccavenue),
							'ccavenue_amount' => $grand_total_ccavenue,
							'tez'	=> count($tez),
							'tez_amount' => $grand_total_tez,
							'paytm'	=> count($paytm),
							'paytm_amount' => $grand_total_paytm,
							);
							
				$returned_order = array(
							'cod'	=> count($return_cod),
							'cod_returned_amount' => $grand_return_cod,
							'ccavenue'	=> count($return_ccavenue),
							'ccavenue_returned_amount' => $grand_return_ccaveneue,
							);
							
							
								
				$response["message"] 			= (array)"success";
				$response["total_kilometer"] 	= $total_kilometers[0]->total_kilometer;
				$response["order_count"]    	= $order_count;
				$response["returned_order"] 	= $returned_order;
				echo json_encode($response); exit;
			} else {
				$response["message"] 	= (array)"No records found";
				echo json_encode($response); exit;
			}
		
		} else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}
	
	public function postContactus( Request $request)
	{
		$rules = array(
			'user_id'    =>'required|numeric',
			'category'   =>'required',
			'subject'    =>'required',
			'comments'   =>'required',
		);
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			$contact_us = array(
    			'user_id'     		=> $request->user_id,
    			'query_category'    => $request->category,
    			'query_subject'     => $request->subject,
    			'query_comments'    => $request->comments,
				'created'     		=> date('Y-m-d H:i:s'),
    			);
			$cinsert= \DB::table('contact_us')->insertGetId($contact_us);
			if($cinsert!='0'){
			    $response["id"]         = "1";
				$response["message"]    = "Added successfully";
			}else{
				$response["id"]         = "0";
				$response["message"]    = "Doesn't added";
			}

		}else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["id"] 		= "5";
			$response["message"]    = $error;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;
		
	}
	
	function postRestofferget( Request $request)
	{  
		$rules = array(
			'res_id' =>'required|numeric'
		);         
        
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			$rest = \DB::table('abserve_restaurants')->where('id','=',$_REQUEST['res_id'])->get();
				
			$offer = array(
						"offer"				=> $rest[0]->offer,
						"min_order_value"	=> $rest[0]->min_order_value,
						"max_value"			=> $rest[0]->max_value,
						"offer_from"		=> date("d/m/Y", strtotime($rest[0]->offer_from)),
						"offer_to"			=> date("d/m/Y", strtotime($rest[0]->offer_to))
					);
					
			$coupons = \DB::table('coupon')->where('res_id','=',$_REQUEST['res_id'])->get();
			
			if(count($coupons)>0){
			  foreach($coupons as $coupon){	
				$coupon_code[] = array(
									"id"				=> $coupon->id,
									"res_id"			=> $coupon->res_id,
									"coupon_name"		=> $coupon->coupon_name,
									"coupon_code"		=> $coupon->coupon_code,
									"coupon_desc"		=> $coupon->coupon_desc,
									"coupon_use_type"	=> $coupon->coupon_use_type,
									"offer_type"		=> $coupon->offer_type,
									"offer"				=> $coupon->offer,
									"min_order_value"	=> $coupon->min_order_value,
									"max_value"			=> $coupon->max_value,
									"offer_from"		=> date("d/m/Y", strtotime($coupon->offer_from)),
									"offer_to"			=> date("d/m/Y", strtotime($coupon->offer_to))
								);
			  }
			} else {
				$coupon_code = array();
			}
			
			$response["rest_offer"][]	= $offer;
			$response["coupon"]    	 	= $coupon_code;
			
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"]    = $error;
		}	
		echo json_encode($response);exit;
	}
	
	function postRestofferupdate( Request $request)
	{  //echo "test"; exit;
		$rules = array(
			'res_id'			=>'required|numeric',
			'offer'				=>'required|numeric',
			'min_order_value'	=>'required|numeric',
			'max_value'			=>'required|numeric',
			'offer_from'		=>'required',
			'offer_to'			=>'required'
		);         
        
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			$start_date = str_replace('/', '-', $_REQUEST['offer_from']);
			$end_date = str_replace('/', '-', $_REQUEST['offer_to']);
			
			$values = array(
				"offer"				=> $_REQUEST['offer'],
				"min_order_value"	=> $_REQUEST['min_order_value'],
				"max_value"			=> $_REQUEST['max_value'],
				"offer_from"		=> date("Y-m-d", strtotime($start_date)),
				"offer_to"			=> date("Y-m-d", strtotime($end_date))
			);			
			
			$updated = \DB::table('abserve_restaurants')->where('id','=',$_REQUEST['res_id'])->update($values);
			
			//All Offers Table Values
			\DB::table('offers')->where('res_id','=',$_REQUEST['res_id'])->where('offer_name','=','Restaurant Offer')->update($values);
			
			if($updated){
				$response["message"]    = "Success";
			} else {
				$response["message"]    = "Failure";
			}
			
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"]    = $error;
		}	
		echo json_encode($response);exit;
	}
	
	function postRestofferdelete( Request $request)
	{  
		$rules = array(
			'res_id' =>'required|numeric'
		);         
        
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
				
			$values = array(
				"offer"				=> 0,
				"min_order_value"	=> 0,
				"max_value"			=> 0,
				"offer_from"		=> "0000-00-00",
				"offer_to"			=> "0000-00-00"
			);
			$updated = \DB::table('abserve_restaurants')->where('id','=',$_REQUEST['res_id'])->update($values);
			
			//All Offers Table Values
			\DB::table('offers')->where('res_id','=',$_REQUEST['res_id'])->where('offer_name','=','Restaurant Offer')->update($values);
			
			if($updated){
				$response["message"]    = "Success";
			} else {
				$response["message"]    = "Failure";
			}
			
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"]    = $error;
		}	
		echo json_encode($response);exit;
	}
	
	function getGetalloffers( Request $request)
	{  
		$now = (date('Y-m-d'));
			
		$offers = \DB::table('abserve_restaurants')->select('*')->where('offer_from', '<=', ($now))->where('offer_to', '>=', ($now))->get();
		
		if(count($offers)>0){
			foreach($offers as $offer){
				
				if($offer->logo != ''){
					$res_img=\URL::to('').'/uploads/restaurants/'.$offer->logo;
				} else {
					$res_img=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				
				$_offer[] = array(
								"rest_id"			=> (string)$offer->id,
								"rest_name"			=> $offer->name,
								"res_img"			=> $res_img,
								"offer"				=> (string)$offer->offer,
								"min_order_value"	=> (string)$offer->min_order_value,
								"max_value"			=> (string)$offer->max_value,
								"offer_from"		=> date("d/m/Y", strtotime($offer->offer_from)),
								"offer_to"			=> date("d/m/Y", strtotime($offer->offer_to))
							);
			}
		} else {
			$_offer = array();
		}
		
		$coupons = \DB::table('coupon')->whereDate('offer_from', '<=', $now)->whereDate('offer_to', '>=', $now)->get();
			
		if(count($coupons)>0){
		  foreach($coupons as $coupon){	
			$coupon_code[] = array(
								"id"				=> $coupon->id,
								"res_id"			=> $coupon->res_id,
								"coupon_name"		=> $coupon->coupon_name,
								"coupon_code"		=> $coupon->coupon_code,
								"coupon_desc"		=> $coupon->coupon_desc,
								"coupon_use_type"	=> $coupon->coupon_use_type,
								"offer_type"		=> $coupon->offer_type,
								"offer"				=> $coupon->offer,
								"offer_from"		=> date("d/m/Y", strtotime($coupon->offer_from)),
								"offer_to"			=> date("d/m/Y", strtotime($coupon->offer_to))
							);
		  }
		} else {
			$coupon_code = array();
		}
		
		$response["rest_offer"] = $_offer;
		$response["coupons"]    = $coupon_code;
		
		echo json_encode($response);exit;
	}
	
	function postGetalloffers( Request $request)
	{
		$rules = array(
			'pin_code' 	 	=> 'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
		  $_REQUEST = str_replace('"','', $_REQUEST);		  
		  $area = $this->locationdistance($_REQUEST['pin_code']);
		  
		  if(count($area)>0){
			  
			$region_key = $area[0]->region_keyword;
			$region_id = $area[0]->region_id;			
			$now = (date('Y-m-d'));
				
			$offers = \DB::table('abserve_restaurants')->select('*')->where('offer_from', '<=', ($now))->where('offer_to', '>=', ($now))->where('region', '=',$region_key)->get();
			
			if(count($offers)>0){
				foreach($offers as $offer){
					
					if($offer->logo != ''){
						$res_img=\URL::to('').'/uploads/restaurants/'.$offer->logo;
					} else {
						$res_img=\URL::to('').'/uploads/restaurants/Default_food.jpg';
					}
					
					$_offer[] = array(
									"rest_id"			=> (string)$offer->id,
									"rest_name"			=> $offer->name,
									"res_img"			=> $res_img,
									"offer"				=> (string)$offer->offer,
									"min_order_value"	=> (string)$offer->min_order_value,
									"max_value"			=> (string)$offer->max_value,
									"offer_from"		=> date("d/m/Y", strtotime($offer->offer_from)),
									"offer_to"			=> date("d/m/Y", strtotime($offer->offer_to))
								);
				}
			} else {
				$_offer = array();
			}
			
			$coupons = \DB::table('coupon')->whereDate('offer_from', '<=', $now)->whereDate('offer_to', '>=', $now)->where('region', '=',$region_id)->get();
				
			if(count($coupons)>0){
			  foreach($coupons as $coupon){
				  if($coupon->res_id == 0){
					  $coupon_type = "2";//DS Coupon
				  } else {
					  $coupon_type = "1";//Restaurant Coupon
				  }
				$coupon_code[] = array(
									"id"				=> $coupon->id,
									"res_id"			=> $coupon->res_id,
									"coupon_type"		=> $coupon_type,
									"coupon_name"		=> $coupon->coupon_name,
									"coupon_code"		=> $coupon->coupon_code,
									"coupon_desc"		=> $coupon->coupon_desc,
									"coupon_use_type"	=> $coupon->coupon_use_type,
									"offer_type"		=> $coupon->offer_type,
									"offer"				=> $coupon->offer,
									"offer_from"		=> date("d/m/Y", strtotime($coupon->offer_from)),
									"offer_to"			=> date("d/m/Y", strtotime($coupon->offer_to))
								);
			  }
			} else {
				$coupon_code = array();
			}
			$response["status"] 	= true;
			
		  } else {
			  $response["status"] 	= false;
			  $_offer = array();
			  $coupon_code = array();
		  }
			
			$response["rest_offer"] = $_offer;
			$response["coupons"]    = $coupon_code;
			
			echo json_encode($response);exit;
		}else {
			$response["status"] 	= false;
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response['message'] 	= $error['pin_code'][0];
			$response["error"] 		= $error;
			//$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function postCouponcodeaddupdate( Request $request){

		$response = array();
	
		$rules = array(
			'res_id' 	 	=> 'required',
			'coupon_name'  	=> 'required',
			'coupon_code'  	=> 'required',
			'coupon_desc'  	=> 'required',
			'coupon_use_type'  => 'required',
			'offer_type'  	=> 'required',
			'offer' 	 	=> 'required',
			'min_order_value' 	=> 'required',
			'max_value' 	 	=> 'required',
			'offer_from'  	=> 'required',
			'offer_to'  	=> 'required',
		);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			
			$restaurants = \DB::table('abserve_restaurants')->where('id',$_REQUEST['res_id'])->first();
			$region = \DB::table('region')->where('region_keyword',$restaurants->region)->first();
			
			$start_date = str_replace('/', '-', $_REQUEST['offer_from']);
			$end_date = str_replace('/', '-', $_REQUEST['offer_to']);
			$coupon = array("res_id"=>$_REQUEST['res_id'],"coupon_name"=>$_REQUEST['coupon_name'],"coupon_code"=>$_REQUEST['coupon_code'],"coupon_desc"=>$_REQUEST['coupon_desc'],"coupon_use_type"=>$_REQUEST['coupon_use_type'],"offer_type"=>$_REQUEST['offer_type'],"offer"=>$_REQUEST['offer'],"min_order_value"=>$_REQUEST['min_order_value'],"max_value"=>$_REQUEST['max_value'],"offer_from"=>date("Y-m-d", strtotime($start_date)),"offer_to"=>date("Y-m-d", strtotime($end_date)));

			$_coupon = array("res_id"=>$_REQUEST['res_id'],"coupon_name"=>$_REQUEST['coupon_name'],"coupon_code"=>$_REQUEST['coupon_code'],"coupon_desc"=>$_REQUEST['coupon_desc'],"coupon_use_type"=>$_REQUEST['coupon_use_type'],"offer_type"=>$_REQUEST['offer_type'],"offer"=>$_REQUEST['offer'],"min_order_value"=>$_REQUEST['min_order_value'],"max_value"=>$_REQUEST['max_value'],"offer_from"=>date("Y-m-d", strtotime($start_date)),"offer_to"=>date("Y-m-d", strtotime($end_date)),"region"=>$region->id);

			//print_r($coupon);
			if($_REQUEST['id'] =='0'){
				$id = \DB::table('coupon')->insertGetId($_coupon);
				
				//All Offers Table Values
				$couponid = array("coupon_id"=>$id,"offer_name"=>"Coupon");
				$result = array_merge($coupon, $couponid);
				\DB::table('offers')->insertGetId($result);
												
				$response["status"] 	= true;
				$response["message"] 	= "Coupon added successfully";
				echo json_encode($response); exit;	
			} else {
				\DB::table('coupon')->where('id','=',$_REQUEST['id'])->update($_coupon);
				
				//All Offers Table Values
				$couponid = array("offer_name"=>"Coupon");
				$result = array_merge($coupon, $couponid);				
				\DB::table('offers')->where('coupon_id','=',$_REQUEST['id'])->where('offer_name','=','Coupon')->update($result);
				
				$response["status"] 	= true;
				$response["message"] 	= "Coupon updated successfully";
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
	
	function postCouponcodedelete( Request $request)
	{  
		$rules = array(
			'id' =>'required|numeric'
		);         
        
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			$deleted = \DB::table('coupon')->where("id",'=',$_REQUEST['id'])->delete();
			$deleted = \DB::table('offers')->where("coupon_id",'=',$_REQUEST['id'])->delete();
			if($deleted){
				$response["message"]    = "Coupon deleted successfully";
			} else {
				$response["message"]    = "Failure";
			}
			
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["message"]    = $error;
		}	
		echo json_encode($response);exit;
	}
	
	public function postUploadagreementpdf(Request $request) {
		
		$response = array();
	
		$rules = array(
			'res_id'	=>'required|numeric',
			'pdf'		=>'required'
		);	
			
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
				
			$res_id = $_REQUEST['res_id'];
			$file = Input::file('pdf');
			//$name = $file->getClientOriginalName();
			$path = "/uploads/restaurants/";
			$extension = Input::file('pdf')->getClientOriginalExtension();	
			
			$filename = rand(11111111, 99999999). '.' . $extension;		
		
			$request->file('pdf')->move(
				base_path() . $path, $filename
			);		
		
			if($filename !="" && $extension == "pdf")
			{
				\DB::table('abserve_restaurants')->where('id',$res_id)->update(array('agreement'=>$filename,'agreement_status'=>1));
				$response['message'] = "Uploaded Successfully";
				$response['res_id'] = $res_id;
				echo json_encode($response); exit;
			} else {
				$response['message'] = "Failure";
				$response['res_id'] = $res_id;
				echo json_encode($response); exit;
			}
			
		} else {
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}

	}
	
	function getBanners( Request $request)
	{			
		$now = (date('Y-m-d'));
		
		$offers = \DB::table('offers')->select('res_id')->where('offer_from', '<=', ($now))->where('offer_to', '>=', ($now))->groupBy('res_id')->get();
		
		if(count($offers)>0){
			foreach($offers as $offer){
				$res_id[] = $offer->res_id;
			}
			$ids = implode(",", $res_id);
			//$banners = DB::table('banners')->whereIn('res_id',array($ids))->get(); 
			$banners = DB::select('select * from `banners` where `res_id` in ('.$ids.')');
						
			if(count($banners)>0){
				foreach($banners as $banner){
					$response["message"]    = "true";
					$response["banners"][]    = array(
												"id"			=> $banner->id,
												"res_id"		=> $banner->res_id,
												"banner_image"	=> \URL::to('')."/uploads/banners/".$banner->banner_image
											  );
				
				}
			} else {
				$response["message"]    = "false";
			}
		} else {
			$response["message"]    = "false";
		}
		
		echo json_encode($response);exit;
	}
		
}
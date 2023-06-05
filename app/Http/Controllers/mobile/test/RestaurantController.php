<?php namespace App\Http\Controllers\mobile\test;

use App\Http\Controllers\controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect,RecursiveIteratorIterator,RecursiveArrayIterator ; 


class RestaurantController extends Controller {

	public $module = 'restaurant';
	
	public function cuisines($id=''){

		$cname	= \DB::select("SELECT GROUP_CONCAT(name) as name FROM abserve_food_cuisines where id IN (".$id.")");
		if($cname){
			return $cname[0]->name;
		} else {
			return '';
		}
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

		$radius = 5;
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
			$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
		}

		$response["restaurants"]	= $res_restaurnts;
		$response['cart_details']	= $tb_cu;
	   echo "<pre>";print_r($response);exit();
		echo json_encode($response); exit;
	}

	public function postRestaurantresults( Request $request){
		$_REQUEST = str_replace('"','', $_REQUEST);

		$radius = 5;
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

		//$restaurants = \DB::select("SELECT * ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);
		$restaurants = \DB::select("SELECT *  FROM `abserve_restaurants` ");

		//$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond);
		$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id`");

		foreach ($restaurants as $key => $value) {
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
			$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];
		}

		$response["restaurants"]	= $res_restaurnts;
		$response['cart_details']	= $tb_cu;
		// echo "<pre>";print_r($response);exit();
		echo json_encode($response); exit;
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

				if($_REQUEST['main_cat'] == "Recommended"){
					$cond .= " AND `hi`.`recommended` = '1'";
				}else{
					$cond .= " AND `c`.`cat_name` = '".$_REQUEST['main_cat']."'";
				}

				$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`ingredients`,`hi`.`main_cat`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$_REQUEST['res_id'].$cond;
				$arry = \DB::select($qwert);
				$items =[];
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

	public function postAddcommentsrating( Request $request)
	{
		$rules = array(
			'user_id'    =>'required|numeric',
			'res_id'     =>'required|numeric',
			'food_id'    =>'required|numeric',
			'comments'   =>'required',
			'rating'     =>'required|numeric',
			);
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {
			$aFood_comment = array(
    			'user_id'     =>$request->user_id,
    			'res_id'      =>$request->res_id,
    			'food_id'     =>$request->food_id,
    			'comments'    =>$request->comments,
    			'rating'      =>$request->rating,
    			'created'     => time(),
    			);
			$cinsert= \DB::table('abserve_food_reviews')->insertGetId($aFood_comment);
			if($cinsert!='0'){
			    $response["id"]         = "1";
				$response["message"]    = "Added successfully";
			}else{
				$response["id"]         = "0";
				$response["message"]    = "Dose'nt added";
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
		}else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$response["id"] 		= "5";
			$response["message"]    = $error;
		}
		echo json_encode($response); exit;

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
							// $odval['time'] = date('Y-m-d H:i:s A', $odval['time']);
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

					$aOrders = \DB::select("select * from `abserve_order_details` as `od` inner join `abserve_orders_partner` as `op` on `od`.`id` = `op`.`orderid` where `op`.`partner_id` = ".$_REQUEST['partner_id']." order by `od`.`id` desc");
					

					$aTot = array();
					foreach ($aOrders as $sKey => $aValue) {
						$aRes = \DB::select("select count(*) as `total_count` from `abserve_order_items` where `orderid` = ".$aValue->orderid);
						
						$array['count'] = $aRes[0]->total_count;
						$array['id'] = $aValue->orderid;
						//$array['time'] = $aValue->time;
						$array['time'] = date('H:i:s A',$aValue->time);
						$array['status'] = $aValue->status;
						$array['order_status'] = $aValue->order_status;
						$array['total_price'] = $aValue->total_price;
						$array['subtotal'] = $aValue->grand_total;
						$array['tax'] = $aValue->s_tax;
						$array['coupon_price'] = $aValue->coupon_price;

						$aOrderItems = \DB::select("select * from `abserve_order_items` where `orderid` = ".$aValue->orderid);
						
						$aItem=array();
						foreach ($aOrderItems as $key => $value) {
							$aItem[] = (array)$value;
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



	

}
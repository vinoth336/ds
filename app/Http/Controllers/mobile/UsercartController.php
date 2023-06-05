<?php namespace App\Http\Controllers\mobile;

use App\Http\Controllers\controller;
use App\Models\Usercart;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class UsercartController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'usercart';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Usercart();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'usercart',
			'return'	=> self::returnUrl()
			
		);
		
	}	

	// public function postAddcart( Request $request)
	// {
	// 	$_REQUEST 		= str_replace('"','', $_REQUEST);

	// 	$values = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$_REQUEST['quantity']);

	// 		$user_res_equal = \DB::table('abserve_user_cart')
	// 		->where("user_id",'=',$_REQUEST['user_id'])
	// 		->where("res_id",'=',$_REQUEST['res_id'])
	// 		->exists();

	// 		if($user_res_equal){
	// 			$user_food_equal = \DB::table('abserve_user_cart')
	// 			->where("user_id",'=',$_REQUEST['user_id'])
	// 			->where("food_id",'=',$_REQUEST['food_id'])
	// 			->exists();

	// 			if($user_food_equal){

	// 				$quantity = \DB::table('abserve_user_cart')
	// 				->select('*')
	// 				->where("user_id",'=',$_REQUEST['user_id'])
	// 				->where("food_id",'=',$_REQUEST['food_id'])
	// 				->get();

	// 				$fid = $quantity[0]->id;
	// 				 $qty = $quantity[0]->quantity + 1;

	// 				if($_REQUEST['quantity'] == 0){

	// 					\DB::table('abserve_user_cart')
	// 					->where("id",'=',$fid)
	// 					->delete();

	// 				}else{
	// 					$vals = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$_REQUEST['quantity']);

	// 					\DB::table('abserve_user_cart')
	// 					->where("id",'=',$fid)
	// 					->update($vals);
	// 				}

	// 				$response['id'] 		= "1";
	// 				$response['message'] 	= "Same Food added";
	// 				echo json_encode($response);exit;

	// 			}else{
	// 				\DB::table('abserve_user_cart')->insert($values);

	// 				$response['id'] 		= "1";
	// 				$response['message'] = "Another Food added";
	// 				echo json_encode($response);exit;
	// 			}
	// 		}else{
	// 			\DB::table('abserve_user_cart')->where('user_id', '=', $_REQUEST['user_id'])->delete();
	// 			\DB::table('abserve_user_cart')->insert($values);

		
	// 			$response['id'] 		= "1";
	// 			$response['message'] 	= "New List";

	// 			echo json_encode($response);exit;
	// 		}
	// }	

	// public function postShowcart( Request $request)
	// {
	// 	$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$_REQUEST['user_id'])->get();
	// 	foreach ($foods_items as $ky => $val) {
	// 		$foods_item[] = get_object_vars($val);
	// 	}

	// 	$sum = 0;
	// 	foreach ($foods_item as $key => &$value) {
	// 		$value['total'] = ($value['quantity'] * $value['price']);
	// 		/*$sum += $values['price'];
	// 		print_r($sum);*/
	// 	}
	// 	$sum = array_sum(array_column($foods_item, 'total'));

	// 	$response['cart_details'] 				= $foods_item;
	// 	$response['cart_total'][0]['total']		= $sum;
	// 	echo json_encode($response);exit;
	// }


	public function postAddcart( Request $request)
	{
		$_REQUEST 		= str_replace('"','', $_REQUEST);

		$values = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$_REQUEST['quantity']);

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$_REQUEST['user_id'])
			->where("res_id",'=',$_REQUEST['res_id'])
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$_REQUEST['user_id'])
				->where("food_id",'=',$_REQUEST['food_id'])
				->exists();

				if($user_food_equal){

					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$_REQUEST['user_id'])
					->where("food_id",'=',$_REQUEST['food_id'])
					->get();

					$fid = $quantity[0]->id;
					 $qty = $quantity[0]->quantity + 1;

					if($_REQUEST['quantity'] == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();

					}else{
						$vals = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$qty);

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($vals);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "Same Food added";
					echo json_encode($response);exit;

				}else{
					\DB::table('abserve_user_cart')->insert($values);

					$response['id'] 		= "1";
					$response['message'] = "Another Food added";
					echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $_REQUEST['user_id'])->delete();
				\DB::table('abserve_user_cart')->insert($values);

			
				$response['id'] 		= "1";
				$response['message'] 	= "New List";

				echo json_encode($response);exit;
			}
	}
	
	public function cuisines($id=''){

		$cname	= \DB::select("SELECT GROUP_CONCAT(name) as name FROM abserve_food_cuisines where id IN (".$id.")");
		if($cname){
			return $cname[0]->name;
		} else {
			return '';
		}
	}
	
	public function postAddcart1( Request $request)
	{
		$_REQUEST = (array) json_decode(file_get_contents("php://input"));
		$_REQUEST 		= str_replace('"','', $_REQUEST);
		
		//print_r($_REQUEST); exit;
		$user_id = $_REQUEST['user_id'];
		foreach($_REQUEST['restaurant'] as $key => $user){
			
		  $res_id = $user->res_id;			
		  foreach($user->food as $res){
			//print_r($res);
			$food_id = $res->food_id;
			$food_qty = $res->quantity;
			
			//if($food_qty != 0){			
				if($res->toppings){
					$topping_id = "";
					$topping_name = "";
					$topping_price = "";
					foreach($res->toppings as $toppings){
						$topping_id[] = $toppings->topping_id;
						$topping_name[] = $toppings->topping_name;
						$topping_price[] = $toppings->topping_price;
					}
					$topping_ids 	= implode(",",$topping_id);
					$topping_names 	= implode(",",$topping_name);
					$topping_prices = implode("+",$topping_price);
				} else {
					$topping_ids 	= "";
					$topping_names 	= "";
					$topping_prices = "";
				}			
			
			if($food_qty != 0){
				$values = array("user_id"=>$user_id,"res_id"=>$res_id,"food_id"=>$food_id,"topping_id"=>$topping_ids,"topping_name"=>$topping_names,"topping_price"=>$topping_prices,"food_item"=>$res->food_item,"price"=>$res->price,"quantity"=>$res->quantity);
			}

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$user_id)
			->where("res_id",'=',$res_id)
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$user_id)
				->where("food_id",'=',$food_id)
				->where("topping_id",'=',$topping_ids)
				->exists();

				if($user_food_equal){
					
					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$user_id)
					->where("food_id",'=',$food_id)
					->where("topping_id",'=',$topping_ids)
					->get();

					$fid = $quantity[0]->id;
					//$qty = $quantity[0]->quantity + 1;					

					if($res->quantity == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						//->where("user_id",'=',$user_id)->where("food_id",'=',$fid)->where("topping_id",'=',$topping_ids)
						->delete();
						//echo $fid; exit;

					}else{
						//$vals = array("user_id"=>$user_id,"res_id"=>$res_id,"food_id"=>$food_id,"food_item"=>$res->food_item,"price"=>$res->price,"quantity"=>$qty);						

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($values);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "Same Food added";
					//echo json_encode($response);exit;

				}else{
					if($food_qty != 0){
						\DB::table('abserve_user_cart')->insert($values);
					}

					$response['id'] 	 	= "1";
					$response['message'] 	= "Another Food added";
					//echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $user_id)->delete();
				if($food_qty != 0){
					\DB::table('abserve_user_cart')->insert($values);
				}

				$response['id'] 		= "1";
				$response['message'] 	= "New List";
				
			}
			
		  }
		}
		
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
		$foods_item =array();
		foreach ($foods_items as $ky => $val) {
			$foods_item[] = get_object_vars($val);
		}
	
		$sum = 0;
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		$rest=\DB::table('abserve_restaurants')->select('*')->where('id',$res_id)->get();
		$name = $rest[0]->name;
		if($rest[0]->logo != ''){
			$logo=\URL::to('').'/uploads/restaurants/'.$rest[0]->logo;
		}else{
			$logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
		}
		$max_packaging_charge = $rest[0]->max_packaging_charge;
		$delivery_charge = $rest[0]->delivery_charge;
		$service_tax = $rest[0]->service_tax;
		$offer = $rest[0]->offer;
		$cuisine = $this->cuisines($rest[0]->cuisine);
		
		$current_date = strtotime(date("Y-m-d"));
		$offer_to = strtotime($rest[0]->offer_to);
		if($current_date > $offer_to){
			$rest[0]->offer_from = "0000-00-00";
			$rest[0]->offer_to = "0000-00-00";
			$offer = "0";
		}
		
		if($rest[0]->active == 1){
			$available_status = \SiteHelpers::getrestimeval($rest[0]->id);
			if($available_status == 1){
				$res_status = "open";
			} else {
				$res_status = "close";
			}
		} else {
			$res_status = "close";
		}
		
		$response['res_id'] 			= (string)$res_id;
		$response['res_name'] 			= $name;
		$response['cuisines'] 			= $cuisine;
		$response['res_logo'] 			= $logo;
		$response['max_packaging_charge'] = (string)$max_packaging_charge;
		$response['delivery_charge'] 	= (string)$delivery_charge;
		$response['s_tax'] 				= (string)$service_tax;
		$response['offer'] 				= (string)$offer;
		$response['min_order_value'] 	= (string)$rest[0]->min_order_value;
		$response['max_value'] 			= (string)$rest[0]->max_value;
		$response['offer_from'] 		= $rest[0]->offer_from;
		$response['offer_to'] 			= $rest[0]->offer_to;
		$response['latitude'] 			= (string)$rest[0]->latitude;
		$response['longitude'] 			= (string)$rest[0]->longitude;
		$response['available_status']	= (string)$available_status;
		$response['res_status'] 		= (string)$res_status;		
		
		foreach ($foods_item as $key => &$value) {
			if($value['topping_price'] != ""){
				$topping_prices = explode('+', $value['topping_price']);
				$topping_price = array_sum($topping_prices);
				$tot_topping_price = ($value['quantity'] * $topping_price);
			} else {
				$tot_topping_price = 0;
			}
			$value['total'] = "";
			$tot_quan_price = ($value['quantity'] * $value['price']);			
			$value['total'] = ($tot_quan_price + $tot_topping_price);
			
			$toppingid = explode(",",$value['topping_id']);
						
			$fooditem=\DB::table('abserve_hotel_items')->select('*')->where('id',$value['food_id'])->get();
			
			if(($rest[0]->active == 1) && ($available_status == 1)){
				$qwert = "SELECT * FROM `abserve_hotel_items` WHERE `id` = ".$value['food_id']." AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
				$prod_item = \DB::select($qwert);
				
				if(count($prod_item)>0){
					$fooditem_status = $fooditem[0]->item_status;
				} else {
					$fooditem_status = 0;
				}
			} else {
				$fooditem_status = 0;
			}
			$food_status = $fooditem[0]->status;
			$packaging_charge = $fooditem[0]->packaging_charge;
			$res_id = $value['res_id'];
			$topping_types=\DB::table('toppings')->select('*')->whereIn('id',$toppingid)->get();
			$toppingtype = array();
			foreach($topping_types as $topping_type){
				$toppingtype[] = $topping_type->type;
			}			
			$toppingstype = implode(",",$toppingtype);
			
			
			$response['cart_details'][] = array(
										'id'			=> (string)$value['id'],
										'user_id'		=> (string)$value['user_id'],
										'cookie_id'		=> $value['cookie_id'],
										'food_id'		=> (string)$value['food_id'],
										'topping_id'	=> (string)$value['topping_id'],
										'topping_name'	=> $value['topping_name'],
										'topping_type'	=> $toppingstype,
										'topping_price'	=> (string)$value['topping_price'],
										'food_item'		=> $value['food_item'],
										'status'		=> $food_status,
										'item_status'	=> (string)$fooditem_status,
										'quantity'		=> (string)$value['quantity'],
										'price'			=> (string)$value['price'],
										'packaging_charge' => (string)$packaging_charge,
										'tax'			=> (string)$value['tax'],
										'total'			=> $value['total']
										);
		}
		
		$sum = array_sum(array_column($foods_item, 'total'));
		$tax = array_sum(array_column($foods_item, 'tax'));
	
		$response['cart_total'][0]['total']		= $sum;
		$response['cart_total'][0]['tax']		= $tax;
		
		echo json_encode($response);exit;
		
	}
	
	public function postAddcart2( Request $request)
	{
		$_REQUEST = (array) json_decode(file_get_contents("php://input"));
		$_REQUEST 		= str_replace('"','', $_REQUEST);
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
		
		//print_r($_REQUEST); exit;
		$user_id = $_REQUEST['user_id'];
		$current_date = strtotime(date("Y-m-d"));
		foreach($_REQUEST['restaurant'] as $key => $user){
			
		  $res_id = $user->res_id;
		  
		  if($user_id !=''){
			  $res = \DB::table('abserve_restaurants')->select('region')->where('id','=',$res_id)->first();
			  $region = \DB::table('region')->select('id')->where('region_keyword','=',$res->region)->first();
			  $region_update = \DB::table('tb_users')->where('id', $user_id)->update(['region' => $region->id]);
		  }
		  
		  foreach($user->food as $res){
			//print_r($res);
			$food_id = $res->food_id;
			$food_qty = $res->quantity;
			
			//if($food_qty != 0){			
				if($res->toppings){
					$topping_id = "";
					$topping_name = "";
					$topping_price = "";
					foreach($res->toppings as $toppings){
						$topping_id[] = $toppings->topping_id;
						$topping_name[] = $toppings->topping_name;
						$topping_price[] = $toppings->topping_price;
					}
					$topping_ids 	= implode(",",$topping_id);
					$topping_names 	= implode(",",$topping_name);
					$topping_prices = implode("+",$topping_price);
				} else {
					$topping_ids 	= "";
					$topping_names 	= "";
					$topping_prices = "";
				}
				
			$hotel_items = \DB::table('abserve_hotel_items')->select('id','special_price','special_from','special_to')->where('id',$food_id)->first();
			
			//if($hotel_items->special_from !="0000-00-00"){
				if($current_date > strtotime($hotel_items->special_to)){
					$special_price = "0.00";
					$special_from = "0000-00-00";
					$special_to = "0000-00-00";
				} else {
					$special_price = $hotel_items->special_price;
					$special_from = $hotel_items->special_from;
					$special_to = $hotel_items->special_to;
				}
			//}			
			
			if($food_qty != 0){
				$values = array("user_id"=>$user_id,"res_id"=>$res_id,"food_id"=>$food_id,"topping_id"=>$topping_ids,"topping_name"=>$topping_names,"topping_price"=>$topping_prices,"food_item"=>$res->food_item,"price"=>$res->price,"special_price"=>$special_price,"special_from"=>$special_from,"special_to"=>$special_to,"quantity"=>$res->quantity);
			}

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$user_id)
			->where("res_id",'=',$res_id)
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$user_id)
				->where("food_id",'=',$food_id)
				->where("topping_id",'=',$topping_ids)
				->exists();

				if($user_food_equal){
					
					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$user_id)
					->where("food_id",'=',$food_id)
					->where("topping_id",'=',$topping_ids)
					->get();

					$fid = $quantity[0]->id;
					//$qty = $quantity[0]->quantity + 1;					

					if($res->quantity == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						//->where("user_id",'=',$user_id)->where("food_id",'=',$fid)->where("topping_id",'=',$topping_ids)
						->delete();
						//echo $fid; exit;

					}else{
						//$vals = array("user_id"=>$user_id,"res_id"=>$res_id,"food_id"=>$food_id,"food_item"=>$res->food_item,"price"=>$res->price,"quantity"=>$qty);						

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($values);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "Same Food added";
					//echo json_encode($response);exit;

				}else{
					if($food_qty != 0){
						\DB::table('abserve_user_cart')->insert($values);
					}

					$response['id'] 	 	= "1";
					$response['message'] 	= "Another Food added";
					//echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $user_id)->delete();
				if($food_qty != 0){
					\DB::table('abserve_user_cart')->insert($values);
				}

				$response['id'] 		= "1";
				$response['message'] 	= "New List";
				
			}
			
		  }
		}
		
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
		$foods_item =array();
		foreach ($foods_items as $ky => $val) {
			$foods_item[] = get_object_vars($val);
		}
	
		$sum = 0;
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		$rest=\DB::table('abserve_restaurants')->select('*')->where('id',$res_id)->get();
		$name = $rest[0]->name;
		if($rest[0]->logo != ''){
			$logo=\URL::to('').'/uploads/restaurants/'.$rest[0]->logo;
		}else{
			$logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
		}
		$max_packaging_charge = $rest[0]->max_packaging_charge;
		$delivery_charge = $rest[0]->delivery_charge;
		$service_tax = $rest[0]->service_tax;
		$offer = $rest[0]->offer;
		$cuisine = $this->cuisines($rest[0]->cuisine);
		
		$offer_to = strtotime($rest[0]->offer_to);
		if($current_date > $offer_to){
			$rest[0]->offer_from = "0000-00-00";
			$rest[0]->offer_to = "0000-00-00";
			$offer = "0";
		}
		
		if($rest[0]->active == 1){
			$available_status = \SiteHelpers::getrestimeval($rest[0]->id);
			if($available_status == 1){
				$res_status = "open";
			} else {
				$res_status = "close";
			}
		} else {
			$res_status = "close";
		}
		
		$response['res_id'] 			= (string)$res_id;
		$response['res_name'] 			= $name;
		$response['cuisines'] 			= $cuisine;
		$response['res_logo'] 			= $logo;
		$response['max_packaging_charge'] = (string)$max_packaging_charge;
		$response['delivery_charge'] 	= (string)$delivery_charge;
		$response['s_tax'] 				= (string)$service_tax;
		$response['offer'] 				= (string)$offer;
		$response['min_order_value'] 	= (string)$rest[0]->min_order_value;
		$response['max_value'] 			= (string)$rest[0]->max_value;
		$response['offer_from'] 		= $rest[0]->offer_from;
		$response['offer_to'] 			= $rest[0]->offer_to;
		$response['latitude'] 			= (string)$rest[0]->latitude;
		$response['longitude'] 			= (string)$rest[0]->longitude;
		$response['available_status']	= (string)$available_status;
		$response['res_status'] 		= (string)$res_status;		
		
		foreach ($foods_item as $key => &$value) {
			if($value['topping_price'] != ""){
				$topping_prices = explode('+', $value['topping_price']);
				$topping_price = array_sum($topping_prices);
				$tot_topping_price = ($value['quantity'] * $topping_price);
			} else {
				$tot_topping_price = 0;
			}
			$value['total'] = "";
			
			if($current_date > strtotime($value['special_to'])){
				$special_price = "0.00";
				$special_from = "0000-00-00";
				$special_to = "0000-00-00";
				$tot_quan_price = ($value['quantity'] * $value['price']);
			} else {
				$special_price = $value['special_price'];
				$special_from = date("d-m-Y",strtotime($value['special_from']));
				$special_to = date("d-m-Y",strtotime($value['special_to']));
				$tot_quan_price = ($value['quantity'] * $value['special_price']);
			}
			$value['total'] = ($tot_quan_price + $tot_topping_price);
			
			$toppingid = explode(",",$value['topping_id']);
						
			$fooditem=\DB::table('abserve_hotel_items')->select('*')->where('id',$value['food_id'])->get();
			
			if(($rest[0]->active == 1) && ($available_status == 1)){
				$qwert = "SELECT * FROM `abserve_hotel_items` WHERE `id` = ".$value['food_id']." AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
				$prod_item = \DB::select($qwert);
				
				if(count($prod_item)>0){
					$fooditem_status = $fooditem[0]->item_status;
				} else {
					$fooditem_status = 0;
				}
			} else {
				$fooditem_status = 0;
			}
			$food_status = $fooditem[0]->status;
			$packaging_charge = $fooditem[0]->packaging_charge;
			$res_id = $value['res_id'];
			$topping_types=\DB::table('toppings')->select('*')->whereIn('id',$toppingid)->get();
			$toppingtype = array();
			foreach($topping_types as $topping_type){
				$toppingtype[] = $topping_type->type;
			}			
			$toppingstype = implode(",",$toppingtype);
			
			if($fooditem[0]->buy_qty !=0){
				if($current_date_time > strtotime($fooditem[0]->bogo_end_date)){
					$fooditem[0]->buy_qty = "0";
					$fooditem[0]->get_qty = "0";
					$fooditem[0]->bogo_name = "";
				}
			}
			
			
			$response['cart_details'][] = array(
										'id'			=> (string)$value['id'],
										'user_id'		=> (string)$value['user_id'],
										'cookie_id'		=> $value['cookie_id'],
										'food_id'		=> (string)$value['food_id'],
										'topping_id'	=> (string)$value['topping_id'],
										'topping_name'	=> $value['topping_name'],
										'topping_type'	=> $toppingstype,
										'topping_price'	=> (string)$value['topping_price'],
										'food_item'		=> $value['food_item'],
										'status'		=> $food_status,
										'item_status'	=> (string)$fooditem_status,
										'quantity'		=> (string)$value['quantity'],
										'price'			=> (string)$value['price'],
										"special_price"	=> (string)$special_price,
										"special_from"	=> $special_from,
										"special_to"	=> $special_to,
										'packaging_charge' => (string)$packaging_charge,
										'tax'			=> (string)$value['tax'],
										"buy_qty"		=> (string)$fooditem[0]->buy_qty,
										"get_qty"		=> (string)$fooditem[0]->get_qty,
										"bogo_name"		=> $fooditem[0]->bogo_name,
										'total'			=> $value['total']
										);
		}
		
		$sum = array_sum(array_column($foods_item, 'total'));
		$tax = array_sum(array_column($foods_item, 'tax'));
	
		$response['cart_total'][0]['total']		= $sum;
		$response['cart_total'][0]['tax']		= $tax;
		
		echo json_encode($response);exit;
		
	}
	
	public function postAddcart3( Request $request)
	{
		$_REQUEST = (array) json_decode(file_get_contents("php://input"));
		$_REQUEST 		= str_replace('"','', $_REQUEST);
		$current_date_time = strtotime(date("Y-m-d H:i:s"));
		
		
		$user_id = $_REQUEST['user_id'];
		$current_date = strtotime(date("Y-m-d"));
		//print_r($_REQUEST); //exit;
		foreach($_REQUEST['restaurant'] as $key => $user){
			
		  $res_id = $user->res_id;
		  
		  if($user_id !=''){
			  $res = \DB::table('abserve_restaurants')->select('region')->where('id','=',$res_id)->first();
			  $region = \DB::table('region')->select('id')->where('region_keyword','=',$res->region)->first();
			  $region_update = \DB::table('tb_users')->where('id', $user_id)->update(['region' => $region->id]);
			  $status = \DB::table('tb_users')->select('cod_status')->where('id', $user_id)->first();
			  $cod_status = $status->cod_status;
		  }

		  foreach($user->food as $res){
			//print_r($res);
			$food_id = $res->food_id;
			$food_qty = $res->quantity;
			
			//if($food_qty != 0){			
				if($res->toppings){
					$topping_id = "";
					$topping_name = "";
					$topping_price = "";
					foreach($res->toppings as $toppings){
						$topping_id = $toppings->topping_id;
						$topping_name = $toppings->topping_name;
						$topping_price = $toppings->topping_price;
					}
					
					//$topping_ids = implode(",",$topping_id);
					$topping_ids = $topping_id;
					$topping_names = $topping_name;
					$topping_prices = $topping_price;
					//$topping_names 	= implode(",",$topping_name);
					//$topping_prices = implode("+",$topping_price);
				} else {
					$topping_ids 	= "";
					$topping_names 	= "";
					$topping_prices = "";
				}
				
			$hotel_items = \DB::table('abserve_hotel_items')->select('id','special_price','special_from','special_to')->where('id',$food_id)->first();
			
			//if($hotel_items->special_from !="0000-00-00"){
				if($current_date > strtotime($hotel_items->special_to)){
					$special_price = "0.00";
					$special_from = "0000-00-00";
					$special_to = "0000-00-00";
				} else {
					$special_price = $hotel_items->special_price;
					$special_from = $hotel_items->special_from;
					$special_to = $hotel_items->special_to;
				}
			//}			
			
			if($food_qty != 0){
				$values = array("user_id"=>$user_id,"res_id"=>$res_id,"food_id"=>$food_id,"topping_id"=>$topping_ids,"topping_name"=>$topping_names,"topping_price"=>$topping_prices,"food_item"=>$res->food_item,"price"=>$res->price,"special_price"=>$special_price,"special_from"=>$special_from,"special_to"=>$special_to,"quantity"=>$res->quantity);
			}

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$user_id)
			->where("res_id",'=',$res_id)
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$user_id)
				->where("food_id",'=',$food_id)
				->where("topping_id",'=',$topping_ids)
				->exists();

				if($user_food_equal){
					
					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$user_id)
					->where("food_id",'=',$food_id)
					->where("topping_id",'=',$topping_ids)
					->get();

					$fid = $quantity[0]->id;
					//$qty = $quantity[0]->quantity + 1;					

					if($res->quantity == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						//->where("user_id",'=',$user_id)->where("food_id",'=',$fid)->where("topping_id",'=',$topping_ids)
						->delete();
						//echo $fid; exit;

					}else{
						//$vals = array("user_id"=>$user_id,"res_id"=>$res_id,"food_id"=>$food_id,"food_item"=>$res->food_item,"price"=>$res->price,"quantity"=>$qty);						

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($values);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "Same Food added";
					//echo json_encode($response);exit;

				}else{
					if($food_qty != 0){
						\DB::table('abserve_user_cart')->insert($values);
					}

					$response['id'] 	 	= "1";
					$response['message'] 	= "Another Food added";
					//echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $user_id)->delete();
				if($food_qty != 0){
					\DB::table('abserve_user_cart')->insert($values);
				}

				$response['id'] 		= "1";
				$response['message'] 	= "New List";
				
			}
			
		  }
		}
		
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
		$foods_item =array();
		foreach ($foods_items as $ky => $val) {
			$foods_item[] = get_object_vars($val);
		}
	
		$sum = 0;
		$current_time = date("H:i:s");
		$daynum = date("N", strtotime(date("D")));
		
		$rest=\DB::table('abserve_restaurants')->select('*')->where('id',$res_id)->get();
		$name = $rest[0]->name;
		if($rest[0]->logo != ''){
			$logo=\URL::to('').'/uploads/restaurants/'.$rest[0]->logo;
		}else{
			$logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
		}
		//$max_packaging_charge = $rest[0]->max_packaging_charge; // Restaurant Max Packaging Charge
		$delivery_charge = $rest[0]->delivery_charge;
		$service_tax = $rest[0]->service_tax;
		$offer = $rest[0]->offer;
		$cuisine = $this->cuisines($rest[0]->cuisine);
		
		$offer_to = strtotime($rest[0]->offer_to);
		if($current_date > $offer_to){
			$rest[0]->offer_from = "0000-00-00";
			$rest[0]->offer_to = "0000-00-00";
			$offer = "0";
		}
		
		if($rest[0]->active == 1){
			$available_status = \SiteHelpers::getrestimeval($rest[0]->id);
			if($available_status == 1){
				$res_status = "open";
			} else {
				$res_status = "close";
			}
		} else {
			$res_status = "close";
		}
		
		$response['res_id'] 			= (string)$res_id;
		$response['res_name'] 			= $name;
		$response['cuisines'] 			= $cuisine;
		$response['res_logo'] 			= $logo;
		//$response['max_packaging_charge'] = (string)$max_packaging_charge; // Restaurant Max Packaging Charge
		$response['delivery_charge'] 	= (string)$delivery_charge;
		$response['s_tax'] 				= (string)$service_tax;
		$response['offer'] 				= (string)$offer;
		$response['min_order_value'] 	= (string)$rest[0]->min_order_value;
		$response['max_value'] 			= (string)$rest[0]->max_value;
		$response['offer_from'] 		= $rest[0]->offer_from;
		$response['offer_to'] 			= $rest[0]->offer_to;
		$response['latitude'] 			= (string)$rest[0]->latitude;
		$response['longitude'] 			= (string)$rest[0]->longitude;
		$response['available_status']	= (string)$available_status;
		$response['res_status'] 		= (string)$res_status;		
		
		foreach ($foods_item as $key => &$value) {
			if($value['topping_price'] != ""){
				$topping_prices = explode('+', $value['topping_price']);
				$topping_price = array_sum($topping_prices);
				$tot_topping_price = ($value['quantity'] * $topping_price);
			} else {
				$tot_topping_price = 0;
			}
			$value['total'] = "";
			
			if($current_date > strtotime($value['special_to'])){
				$special_price = "0.00";
				$special_from = "0000-00-00";
				$special_to = "0000-00-00";
				$tot_quan_price = ($value['quantity'] * $value['price']);
			} else {
				$special_price = $value['special_price'];
				$special_from = date("d-m-Y",strtotime($value['special_from']));
				$special_to = date("d-m-Y",strtotime($value['special_to']));
				$tot_quan_price = ($value['quantity'] * $value['special_price']);
			}
			$value['total'] = ($tot_quan_price + $tot_topping_price);
			
			$toppingid = explode(",",$value['topping_id']);
						
			$fooditem=\DB::table('abserve_hotel_items')->select('*')->where('id',$value['food_id'])->get();
			
			if(($rest[0]->active == 1) && ($available_status == 1)){
				$qwert = "SELECT * FROM `abserve_hotel_items` WHERE `id` = ".$value['food_id']." AND ((`available_from` <= '".$current_time."' AND `available_to` >= '".$current_time."') OR (`breakfast_available_from` <= '".$current_time."' AND `breakfast_available_to` >= '".$current_time."') OR (`lunch_available_from` <= '".$current_time."' AND `lunch_available_to` >= '".$current_time."') OR (`dinner_available_from` <= '".$current_time."' AND `dinner_available_to` >= '".$current_time."')) AND FIND_IN_SET('".$daynum."',`available_days`)";
				$prod_item = \DB::select($qwert);
				
				if(count($prod_item)>0){
					$fooditem_status = $fooditem[0]->item_status;
				} else {
					$fooditem_status = 0;
				}
			} else {
				$fooditem_status = 0;
			}
			$food_status = $fooditem[0]->status;
			$packaging_charge = $fooditem[0]->packaging_charge;
			$max_packaging_charge = $fooditem[0]->max_packaging_charge; // Product Max Packaging Charge
			$res_id = $value['res_id'];
			$topping_types=\DB::table('toppings')->select('*')->whereIn('id',$toppingid)->get();
			$toppingtype = array();
			foreach($topping_types as $topping_type){
				$toppingtype[] = $topping_type->type;
			}			
			$toppingstype = implode(",",$toppingtype);
			
			if($fooditem[0]->buy_qty !=0){
				if($current_date_time > strtotime($fooditem[0]->bogo_end_date)){
					$fooditem[0]->buy_qty = "0";
					$fooditem[0]->get_qty = "0";
					$fooditem[0]->bogo_name = "";
				}
			}
			
			
			$response['cart_details'][] = array(
										'id'			=> (string)$value['id'],
										'user_id'		=> (string)$value['user_id'],
										'cookie_id'		=> $value['cookie_id'],
										'food_id'		=> (string)$value['food_id'],
										'topping_id'	=> (string)$value['topping_id'],
										'topping_name'	=> $value['topping_name'],
										'topping_type'	=> $toppingstype,
										'topping_price'	=> (string)$value['topping_price'],
										'food_item'		=> $value['food_item'],
										'status'		=> $food_status,
										'item_status'	=> (string)$fooditem_status,
										'quantity'		=> (string)$value['quantity'],
										'price'			=> (string)$value['price'],
										"special_price"	=> (string)$special_price,
										"special_from"	=> $special_from,
										"special_to"	=> $special_to,
										'packaging_charge' => (string)$packaging_charge,
										'max_packaging_charge' => (string)$max_packaging_charge,
										'tax'			=> (string)$value['tax'],
										"buy_qty"		=> (string)$fooditem[0]->buy_qty,
										"get_qty"		=> (string)$fooditem[0]->get_qty,
										"bogo_name"		=> $fooditem[0]->bogo_name,
										'total'			=> $value['total']
										);
		}
		
		$sum = array_sum(array_column($foods_item, 'total'));
		$tax = array_sum(array_column($foods_item, 'tax'));
	
		$response['cart_total'][0]['total']		= $sum;
		$response['cart_total'][0]['tax']		= $tax;
		$response['cod_status']	= $cod_status;
		$response['pg_status']	= CCAVENUE_PAYMENT_STATUS;
		echo json_encode($response);exit;
		
	}

	public function postShowcart( Request $request)
	{
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$_REQUEST['user_id'])->get();
		$foods_item =array();
		foreach ($foods_items as $ky => $val) {
			$foods_item[] = get_object_vars($val);
			
			//$fooditem = \DB::table('abserve_hotel_items')->select('*')->where("id",'=',$val->food_id)->get();
			//$fooditem=\DB::table('abserve_hotel_items')->select('*')->where('id',$val->food_id)->get();
			//echo $fooditem->status; exit;
			//$foods_item[]['status'] = $fooditem->status;
		}

		$sum = 0;
		
		foreach ($foods_item as $key => &$value) {
			$value['total'] = ($value['quantity'] * $value['price']);
			//$value['tax'] = ($value['quantity'] * $value['tax']);
			/*$sum += $values['price'];
			print_r($sum);*/
						
			$fooditem=\DB::table('abserve_hotel_items')->select('*')->where('id',$value['food_id'])->get();
			$fooditem_status = $fooditem[0]->status;
			$rest=\DB::table('abserve_restaurants')->select('id','name','logo','max_packaging_charge','delivery_charge')->where('id',$value['res_id'])->get();
			$name = $rest[0]->name;
			if($rest[0]->logo != ''){
				$logo=\URL::to('').'/uploads/restaurants/'.$rest[0]->logo;
			}else{
				$logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
			}
			$max_packaging_charge = $rest[0]->max_packaging_charge;
			$delivery_charge = $rest[0]->delivery_charge;
			$response['cart_details'][] = array(
										'id'		=> $value['id'],
										'user_id'	=> $value['user_id'],
										'cookie_id'	=> $value['cookie_id'],
										'res_id'	=> $value['res_id'],
										'res_name'	=> $name,
										'res_logo'	=> $logo,
										'food_id'	=> $value['food_id'],
										'food_item'	=> $value['food_item'],
										'status'	=> $fooditem_status,
										'quantity'	=> $value['quantity'],
										'price'		=> $value['price'],
										'tax'		=> $value['tax'],
										'total'		=> $value['total'],
										'max_packaging_charge'	=> $max_packaging_charge,
										'delivery_charge'	=> $delivery_charge
										);
		}
		$sum = array_sum(array_column($foods_item, 'total'));
		$tax = array_sum(array_column($foods_item, 'tax'));

		$response['cart_total'][0]['total']		= $sum;
		$response['cart_total'][0]['tax']		= $tax;
		echo json_encode($response);exit;
	}	

	/*public function postRemovecart( Request $request)
	{
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$_REQUEST['user_id'])->delete();
		if(count($foods_items)>0){
			$response['id'] 				= '1';
		$response['message']		= "Your cart is Empty";
		}else{
			$response['id'] 				= '0';
		$response['message']		= "Check your User ID";
		}
		
		echo json_encode($response);exit;
	}*/


	public function postRemovecart( Request $request)
	{
		$_REQUEST 		= str_replace('"','', $_REQUEST);

		$values = array("user_id"=>$_REQUEST['user_id'],
						"res_id"=>$_REQUEST['res_id'],
						"food_id"=>$_REQUEST['food_id'],
						"food_item"=>$_REQUEST['food_item'],
						"price"=>$_REQUEST['price'],
						"quantity"=>$_REQUEST['quantity']);

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$_REQUEST['user_id'])
			->where("res_id",'=',$_REQUEST['res_id'])
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$_REQUEST['user_id'])
				->where("food_id",'=',$_REQUEST['food_id'])
				->exists();

				if($user_food_equal){

					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$_REQUEST['user_id'])
					->where("food_id",'=',$_REQUEST['food_id'])
					->get();

					$fid = $quantity[0]->id;
					 $qty = $quantity[0]->quantity - $_REQUEST['quantity'];
					 if($qty==0){
					 	\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();
					 }

					if($_REQUEST['quantity'] == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();

					}else{
						$vals = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$qty);

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($vals);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "quantity reduced";
					echo json_encode($response);exit;

				}else{
					\DB::table('abserve_user_cart')->insert($values);

					$response['id'] 		= "1";
					$response['message'] = "Removed food";
					echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $_REQUEST['user_id'])->delete();
				\DB::table('abserve_user_cart')->insert($values);

			
				$response['id'] 		= "1";
				$response['message'] 	= "New List";

				echo json_encode($response);exit;
			}
	}	

	public function postUsercartcount(Request $request)
	{
		$sUser_cart_count=\DB::table('abserve_user_cart')
						->where("user_id",'=',$request->user_id)
						->count();
		if(($sUser_cart_count)>0){
			$response['count'] 	= $sUser_cart_count;
		}else{
			$response['count'] 	= 0;
		}
	  echo json_encode($response);exit;
	}


	public function postCustomercartdelete(Request $request)
	{
		$userid =$request->user_id;
		$aCart =\DB::table('abserve_user_cart')->where('user_id',$userid)->delete();
		$response['message'] = "Your order was canceled Successfully";
		echo json_encode($response); exit;
		
	}

	public function postProdtoppings(Request $request)
	{
		$prod_id = $request->food_id;
				
		$top_categories = \DB::select("SELECT `tp`.`id`,`tp`.`category` as `toppings_cat`, `tp`.`type` as `toppings_type` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$prod_id." GROUP BY `pt`.`topping_category` ORDER BY `tp`.`id` ASC");		
													
		foreach($top_categories as $top_cat){
											
			$prod_topp = \DB::select("SELECT `pt`.`topping_id`, `tp`.`label` as `topping_name`, `tp`.`type` as `topping_type`, `pt`.`topping_price` FROM `product_toppings` as `pt` JOIN `toppings` as `tp` ON `pt`.`topping_id` = `tp`.`id` WHERE `pt`.`prod_id` = ".$prod_id." AND `pt`.`topping_category` = '".$top_cat->toppings_cat."'");
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
			
			$topping_cats[] = array(
								"toppings_cat"		=> $top_cat->toppings_cat,
								"toppings_type"		=> $top_cat->toppings_type,
								"toppings_items"	=> $topping_items,
							  );
			
		}
		
		$response['toppings'] = $topping_cats;
		echo json_encode($response); exit;
		
	}
	

	


}
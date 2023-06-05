<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Frontend;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Orders;
use App\Models\Fooditems;
use App\Models\Customerorder;
use App\Models\Usercart;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ,DB, DateTime, Session , Response; 
use Auth;
class FrontEndController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public  function getSearch() {
		$_REQUEST = str_replace('"','', $_REQUEST);	
		$radius = 5;
		$whr	= "WHERE 1 ";
		$cond	= '';
		if(isset($_REQUEST['lat']) && isset($_REQUEST['lang']) && $_REQUEST['lang'] != '' && $_REQUEST['lat'] != ''){

			$lat_lng = ", ( 6371 * acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin( radians( latitude ) ) ) ) AS distance";

			$hav	= "HAVING distance <= ".$radius." ";
			$select = '*'.$lat_lng;
			$offer = \DB::select("SELECT ".$select." FROM abserve_restaurants WHERE 1 AND `offer` <> 0 ".$hav);
			if($offer){
				$eoffer = true;
			} else {
				$eoffer = false;
			}
			$from = $this->address($_REQUEST['lat'],$_REQUEST['lang']);
			//print_r($from);exit;

		} else {

			$lat_lng = $hav	= '';

			$_REQUEST['keyword'] = rtrim($_REQUEST['keyword'],"+");

			$_REQUEST['keyword'] = trim($_REQUEST['keyword']);

			$whr .= "AND `abserve_restaurants`.`location` LIKE '%".$_REQUEST['keyword']."%'";
			$eoffer = \DB::table('abserve_restaurants')->where('location','=',$_REQUEST['keyword'])->where('offer','<>',0)->exists();
			$from = $_REQUEST['keyword'];
		}

		$cuisine = \DB::select("SELECT id,cuisine ".$lat_lng." FROM `abserve_restaurants` ".$whr.$cond.$hav);   

		$re_cuisine = array();
		$k=0;
		foreach($cuisine as $cus){
			$array = explode(",",$cus->cuisine);
			for($i=0;$i<count($array);$i++){
				$re_cuisine[$k] = $array[$i];
				$k++;
			}
		}
		if(is_array($re_cuisine))
			$cc = "'" . implode ( "', '", $re_cuisine ) . "'";

	    	$cuisinename	= \DB::select("SELECT name,id FROM abserve_food_cuisines where id IN (".$cc.")");
		
		
		if(isset($_REQUEST['budget']) && $_REQUEST['budget'] != ' '){

			$result = "'" . implode ( "', '", explode(",",trim($_REQUEST['budget'])) ) . "'";
			$whr .= " AND `abserve_restaurants`.`budget` IN (".$result.")";
		}
		
		if(isset($_REQUEST['cuisines']) && $_REQUEST['cuisines'] != ' '){
			$choosed_cuisines = trim($_REQUEST['cuisines']);
			$choosed_array = explode(",", $choosed_cuisines);
			if(!empty($cuisine)){

				foreach ($cuisine as $key => $value) {
					$cuise_array = explode(",",$value->cuisine);
					foreach ($cuise_array as $akey => $avalue) {
						if (in_array($avalue, $choosed_array)){
							$cuisies[] = $value->id;
						}					 	
					}					
				}
			}

			if(!empty($cuisies)){
				$res_cus_ids = implode(",", $cuisies);
			}

			$cuisine = "'" . implode ( "', '", explode(",",trim($_REQUEST['cuisines'])) ) . "'";			
			$whr .= " AND `abserve_restaurants`.`id` IN (".$res_cus_ids.") ";
		}

		if(isset($_REQUEST['offer']) && $_REQUEST['offer'] != ' '){
			$whr .= " AND `abserve_restaurants`.`offer` <> 0 ";
		}
		
		if(isset($_REQUEST['sort_by']) && trim($_REQUEST['sort_by']) != ''){
			$cond = " ORDER BY `abserve_restaurants`.`" .trim($_REQUEST['sort_by'])."` DESC ";
		} else {
			$cond = " ORDER BY `abserve_restaurants`.`id` DESC ";
		}
		$page = 1;
		$this->data['page']=$page;
		
		if(isset($_REQUEST['page'])){
			$page = $_REQUEST['page'];
			$this->data['page']=$page;
		}

		$perPage = 5;
		$qsql = "SELECT * ".$lat_lng." FROM `abserve_restaurants` JOIN `abserve_hotel_items` ON `abserve_hotel_items`.`restaurant_id` = `abserve_restaurants`.`id` ".$whr." GROUP BY `abserve_restaurants`.`id` ".$hav.$cond;
		// echo $qsql;exit();
		$restaurants = \DB::select($qsql);

		/*echo "SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond; exit;*/

		/*	$rat_res = \DB::select("SELECT `ar`.`rating`,`ar`.`cust_id`,`ar`.`res_id` from `abserve_rating` as `ar` JOIN `abserve_restaurants` as `ah` ON `ah`.`id`=`ar`.`res_id` ".$whr.$cond);*/
		$res_restaurnts = array();

		foreach ($restaurants as $key => $value) {
			$dist = $this->calculate_dist($from,$value->location);
			if($dist <= 5000){
				// echo $value->id."--".$dist."<br>";
				$res_restaurnts[] = $value;
			}
		}

		$currentPage = $page - 1;
		$pagedData = array_slice($res_restaurnts, $currentPage * $perPage, $perPage);
		$res_restaurnts =  new Paginator($pagedData, count($res_restaurnts), $perPage);
		$res_restaurnts->setPath('search');
			foreach ($res_restaurnts as $key => &$value) {
				$image_file = base_path().'/uploads/restaurants/'.$value->logo;
				if($value->logo != '' && file_exists($image_file)){
					$value->logo=\URL::to('').'/uploads/restaurants/'.$value->logo;
				}
				else
				{
					$value->logo=\URL::to('').'/uploads/restaurants/Default_food.jpg';
				}
				if($value->cuisine != ''){
					$value->cuisine = $this->cuisines($value->cuisine);
				}
				/*foreach ($rat_res as $ky => $uid) {
					if($value->id == $uid->res_id){
						$value->rating = $uid->rating;
					}
				}*/
			}

		$this->data["message"]				= (array)"success";
		if($eoffer){
			$this->data["Offers"][0]['offer']	= "Offer's Found";
		}else{
			$this->data["Offers"][0]['offer']	= "Offer's Not Found";
		}
		
		/*if(isset($_REQUEST['user_id']) != ''){
			$user_id	= $_REQUEST['user_id'];
			$card		= "SELECT  SUM(`quantity`) as total_items ,`ae`.`res_id`,`ai`.`name` FROM `abserve_user_cart` as `ae` JOIN `abserve_restaurants` as `ai` on `ai`.`id`=`ae`.`res_id` WHERE `ae`.`user_id`=".$_REQUEST['user_id'];
			$tb_cu =\DB::SELECT($card);
		} else {
			
		}*/
		/*your cart*/
		if(\Auth::check()){
				$user_id	= Auth::user()->id;
				$yourcart1	=  \DB::table('abserve_user_cart')->where('user_id','=',$user_id)/*->where('id', DB::raw("(select max(`id`) from abserve_user_cart)"))*/->first();
				$restaurant_id	= $yourcart1->res_id;
				$cart_cookie_id	= $this->getCartCookie();
				$this->data['cart_items_html'] = $this->ShowSearchcCart($restaurant_id,$user_id,$cart_cookie_id);
				$this->data['search_cart_res_id'] = $restaurant_id;
			} else {
				$cart_cookie_id	= $this->getCartCookie();
				$yourcart1		=  \DB::table('abserve_user_cart')->where('cookie_id','=',$cart_cookie_id)/*->where('id', DB::raw("(select max(`id`) from abserve_user_cart)"))*/->first();
				$restaurant_id = $yourcart1->res_id;
				$this->data['cart_items_html'] = $this->ShowSearchcCart($restaurant_id,0,$cart_cookie_id);
			}

		$tb_cu = [0=>['total_items'=>'','res_id'=>'','name'=>'']];

		//$this->data["restaurants"]	= $res_restaurnts;
		$this->data['cart_details']	= $tb_cu;
		$this->data['cuisine']	= $cuisinename;
		$this->data['pages'] = 'frontend.searchresult';	
		$page = 'layouts.'.CNF_THEME.'.index';

		return view($page,$this->data,compact('res_restaurnts')); 
	}
	public function ShowSearchcCart($res_id,$user_id,$cookie_id) {
		$resInfo = Restaurant::find($res_id);
		$date = new \DateTime();
		$timeval1=date("H:i ", time());
		$timeval2=date("H:i ", strtotime($resInfo->opening_time));
		$timeval3=date("H:i ", strtotime($resInfo->closing_time));
		if ($timeval1 > $timeval2 && $timeval1 < $timeval3)
		{
			$res_timeValid = 1;
		} elseif($timeval1 > $timeval2 && $timeval1 > $timeval3){
			if($timeval3 >= 0 && $timeval3 < $timeval2){
				$res_timeValid = 1;
			} else {
				$res_timeValid = 0;
			}
		} else {
			$res_timeValid = 0;
		}
		if($res_timeValid == 1){
			if($user_id){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$user_id)
				->exists();
				if($user_food_equal){
					
					$foods_items = \DB::table('abserve_user_cart')->select('*')->where('res_id','=',$res_id)->where('user_id','=',$user_id)->get();
				}
			} else if($cookie_id) {
				$foods_items = \DB::table('abserve_user_cart')->select('*')->where("cookie_id",'=',$cookie_id)->where("res_id",'=',$res_id)->get();
			}

			$innerhtml = '';$item_total = 0;$delivery_charge = '0.00';
			//echo '<pre>';print_r($foods_items);exit;
			if(!empty($foods_items)){
				$currsymbol = (\Session::has('currency_symbol')) ? \Session::get('currency_symbol') : '$';
				foreach ($foods_items as $ky => $val) {
					$item_time_valid = \SiteHelpers::getItemTimeValid($val->food_id);
					if($item_time_valid == 1) {
						$price_val = number_format((float)\SiteHelpers::CurrencyValue($val->price),2,'.','');
						$total = ($val->quantity * $price_val);
						$innerhtml.=' <div class="menu-cart-items" id="item_'.$val->food_id.'">
				            <div class="container-fluid">
				                <div class="row no-pad">
				                    <div class="col-xs-5 no-pad">
				                        <p class="veg-item">'.$val->food_item.'</p>
				                    </div>
				                    <div class="col-xs-3 block-item text-center no-pad items_count" id="fnitem_'.$val->food_item.'">
				                     	<i data-faid="'.$val->food_id.'" class="fa fa-minus remove_cart_item" aria-hidden="true" style="cursor:pointer;"></i>
				                        <span class="item-count">'.$val->quantity.'</span>
				                      	<i data-faid="'.$val->food_id.'" id="fitem_'.$val->food_id.'" class="fa fa-plus add_cart_item" aria-hidden="true" style="cursor:pointer;"></i>
				                    </div>
				                    <div class="col-xs-4 no-left-pad block-item text-right">
				                        <div>
				                            '.$currsymbol.' <span class="item-price">'.number_format((float)$total, 2, '.', '').'</span>
				                        </div>
				                    </div>
				                </div>
				                
				            </div>
				        </div>';
				        $item_total+=$total;
				    }
				}
				$html = '';
				if($innerhtml != ''){
					/*if($item_total>500){
						$delivery_charge = 0.00;
					}
					else{*/
						$arr = \DB::table('abserve_restaurants')->select('delivery_charge')->where('id', '=', $res_id)->first();
						$delivery_charge = number_format((float)\SiteHelpers::CurrencyValue($arr->delivery_charge),2,'.','');
						//$delivery_charge = ($arr->delivery_charge);
					//}
					$html = ' <section>
				                
				                <div class="restaurent_name">Restaurant: '.$resInfo->name.'</div>
				                <div class="menu-cart-body " >
				                  '.$innerhtml.'
				                
				                </div>
				                <div class="menu-cart-footer">
							        <div class="sub-total" >
							            <div class="charges">
							                <table class="table">
							                    <tbody>
							                        <tr>
							                            <td class="">Item Total : </td>
							                            <td class="text-right" >'.$currsymbol.' '.number_format((float)$item_total, 2, '.', '').'</td>
							                        </tr>
							                        <tr>
							                            <td class="">Delivery Charges : </td>
							                            <td class="text-right">'.$currsymbol.' '.number_format((float)$delivery_charge, 2, '.', '').'</td>
							                        </tr>
							                    </tbody>
							                </table>
							            </div>
							        </div>';
				        	$grand_total = $item_total + $delivery_charge;
					     	$html.= '   <div class="final-total"  style="">
					           
					            <h5><span class="">Grand Total:</span>
					                
					                <span class="pull-right">'.$currsymbol.' <span class="grand_total">'.number_format((float)$grand_total, 2, '.', '').'</span></span>
					            </h5>
					        </div>';
					        if(Auth::check()){
					        $html.= "<a href='".\URL::to('/frontend/details/'.$res_id)."'><button class='btn btn-checkout' id='btn-checkout' >Checkout</button></a>";
					    	}else{
					    		 $html.= '<button class="btn btn-checkout" onclick="javascript:login_popup();" id="btn-checkout" >Checkout</button>';
					    	}
					     $html.= '</div>
		            </section>';
				}else{
					 $html.= '<section>
		                <div class="menu-cart-title"></div>
		                <div class="menu-cart-body empty" ><div class="cart-quotes"></div>Your Cart is empty</div>
		                <div class="menu-cart-footer">
		                    <button class="btn btn-checkout" disabled id="btn-checkout">Checkout</button>
		                </div>
		            </section>';
				}
			} else {

				$html.= '<section>
				    <div class="menu-cart-title"></div>
				    <div class="menu-cart-body empty" ><div class="cart-quotes"></div>Your Cart is empty</div>
				    <div class="menu-cart-footer">
				        <button class="btn btn-checkout" disabled id="btn-checkout">Checkout</button>
				    </div>
				</section>';
			}
		} else {
			$html.= '<section>
				    <div class="menu-cart-title"></div>
				    <div class="menu-cart-body empty" ><div class="cart-quotes"></div>Your Cart is empty</div>
				    <div class="menu-cart-footer">
				        <button class="btn btn-checkout" disabled id="btn-checkout">Checkout</button>
				    </div>
				</section>';
		}
		return $html;
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
		
	public function calculate_dist($from,$to){
		$ch		= curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/distancematrix/json?origins='.urlencode($from).'&destinations='.urlencode($to).'&mode=drive&sensor=false'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output	= curl_exec($ch);
		curl_close($ch);

		$rwes	= json_decode($output);
		if($rwes->status == "OK"){
			$distance = $rwes->rows[0]->elements[0]->distance->value;
		} else {
			$distance = 0;
		}
		return $distance;
	}

	public function cuisines($id=''){

		$cname	= \DB::select("SELECT GROUP_CONCAT(name) as name FROM abserve_food_cuisines where id IN (".$id.")");
		if($cname){
			return $cname[0]->name;
		} else {
			return '';
		}
	}

	public  function getDetails(Request $request){
		$keyinfo = \Session::get('keyinfo');
		\Session::forget('keyinfo');
		$restaurant = $this->data['cuisine_name'] = array();
		$restaurant_id	= $request->segment(3);
		$resexists		= \DB::table('abserve_restaurants')->where('id',$restaurant_id)->exists();
		if($resexists){
			$restaurant	=  \DB::table('abserve_restaurants')
						->select('id', 'name', 'location','logo','rating','cuisine','delivery_time','opening_time','closing_time','res_desc')
						->where('id', '=', $restaurant_id)->first();
				$cuisine	= explode(",",$restaurant->cuisine); 
				$array	= array_values($cuisine);

				$cuisine_q =  \DB::table('abserve_food_cuisines')->select('name')->whereIn('id',  $array)->get();
				foreach($cuisine_q as $cus){
					$cusine_name[] = $cus->name;
				}
			if($restaurant->logo){
				$furl = '/uploads/res_items/'.$restaurant->id.'/'.$restaurant->logo;
				if(\File::exists(public_path($furl))){
					$restaurant->logo=\URL::to($furl);
				} else {
					$restaurant->logo=\URL::to('/uploads/restaurants/Default_food.jpg');
				}
			} else {
				$restaurant->logo=\URL::to('/uploads/restaurants/Default_food.jpg');
			}
			// echo "<pre>";print_r($restaurant);exit();
			/*Minimum order value*/
			$min_order_val = 0;
			$minInfo = \DB::table('tb_settings')->where('name','min_order_value')->first();
			if(count($minInfo) > 0) {
				$min_order_val = $minInfo->value;
			}
			\Session::put('min_order_val',$min_order_val);
			$this->data['cuisine_name'] = implode(",",$cusine_name);
			$this->data['restaurant']	= $restaurant;
			$this->data['categories']	= json_decode($this->rescategories($request->segment(3)));
			$this->data['categories_count'] = count($this->data['categories']);
			//echo '<pre>';print_r($this->data['categories_count']);exit;
			$this->data['recomm_items']	= $this->getRecommItemdetails($restaurant_id);
			$this->data['hotel_items']	= $this->getItemdetails($restaurant_id);
			$this->data['keyinfo'] = $keyinfo;
			if(Auth::check()){
				$user_id = Auth::user()->id;
				$yourcart1	=  \DB::table('abserve_user_cart')->where('user_id','=',$user_id)/*->where('id', DB::raw("(select max(`id`) from abserve_user_cart)"))*/->first();
				$resId	= $yourcart1->res_id;
				$cur_resid = $restaurant_id;
				$cart_cookie_id = $this->getCartCookie();
				$this->data['Cookie_Id'] = $cart_cookie_id;
				$this->data['cart_items_html'] = $this->Showcart($cur_resid,$resId,$user_id,$cart_cookie_id,$keyinfo);
			} else {
				$cart_cookie_id = $this->getCartCookie();
				$yourcart1	=  \DB::table('abserve_user_cart')->where('cookie_id','=',$cart_cookie_id)/*->where('id', DB::raw("(select max(`id`) from abserve_user_cart)"))*/->first();
				$this->data['Cookie_Id'] = $cart_cookie_id;
				$resId	= $yourcart1->res_id;
				$cur_resid = $restaurant_id;
				$this->data['cart_items_html'] = $this->Showcart($cur_resid,$resId,0,$cart_cookie_id,$keyinfo);
			}
			$this->data['res_time_valid'] = \Session::get('restimevalid');
			\Session::forget('restimevalid');
			$this->data['pages'] = 'frontend.details';	
			$page = 'layouts.'.CNF_THEME.'.index';
			return view($page,$this->data);
		} else {
			return Redirect::to('frontend/search')->with('message',\SiteHelpers::alert('error','No such restaurant found'));
		}
	}

	public function rescategories($restaurant_id){

		$response = array();
		$categories = \DB::select("SELECT DISTINCT(`hi`.`main_cat`) as id,`fc`.`cat_name` as name FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `fc` ON `hi`.`main_cat` = `fc`.`id` WHERE `restaurant_id` = ".$restaurant_id." order by `hi`.`main_cat`");
		$recomend = \DB::select("SELECT DISTINCT(`recommended`) AS recomnd FROM `abserve_hotel_items` WHERE `restaurant_id` = ".$restaurant_id);
		if(!empty($recomend)){
			foreach ($recomend as $key => $val) {
				$rend[] = get_object_vars($val);
			}
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
		return json_encode($categories);
	}

	public function getRecommItemdetails( $restaurant_id){
		
		$recommended = array();
		
		$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`main_cat`,`hi`.`recommended`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$restaurant_id." and `hi`.`recommended` =1";

		$recommended = \DB::select($qwert);

		foreach ($recommended as $key => $value) {
			$furl = 'uploads/res_items/'.$restaurant_id.'/'.$value->image;
			if(\File::exists(base_path($furl))){
				$value->image=\URL::to($furl);
			} else {
				$value->image=\URL::to('/uploads/restaurants/Default_food.jpg');
			}
		}
		return $recommended;
	}

	public function getItemdetails( $restaurant_id){

		$hotel_item = array();
		
		$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`main_cat`,`hi`.`recommended`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$restaurant_id." and `hi`.`recommended` = '0' group by `hi`.`main_cat`,`hi`.`sub_cat`,`hi`.`id`";


		$hotel_item = \DB::select($qwert);

		return $hotel_item;
	}

	public static function setCartCookie(){
        $cookie_name = "mycart";
        $cart_cookie_val = uniqid();
        \Cookie::queue(\Cookie::make($cookie_name, $cart_cookie_val, 45000));
        return $cart_cookie_val;
    }

    public static function getCartCookie(){
        $cookie_name = "mycart";
        if(\Cookie::has($cookie_name) && \Cookie::get($cookie_name) != null)
        {
            return  \Cookie::get($cookie_name);
        }
        return '';
    }

	public function getAddtotcart(Request $request){
		
		$hotel_item	= array();
		$item_id	= $request->item;
		$res_id		= $request->res_id;
		$html		= '';$user_id = 0;$cart_cookie_id = 0;
		$hotel_item	= \DB::table('abserve_hotel_items')->select('food_item','price')->where('id', $item_id)->first();
		$stax = \DB::table('abserve_restaurants')->select('service_tax')->where('id',$res_id)->first();
		
		// echo "<pre>";print_r($stax);
		$product['food_id']		= $item_id;
		$product['food_item']	= $hotel_item->food_item;
		$product['price']		= $hotel_item->price;
		$product['quantity']	= $request->qty;
		$product['res_id']		= $res_id;
		$product['tax']			= $stax->service_tax;

		// print_r($product);exit();
		if(Auth::check()){
			$user_id = Auth::user()->id;
			$product['user_id'] = $user_id;
		}else{
			$product['user_id'] = 0;
		}
		$cart_cookie_id = $this->getCartCookie();
		if (!$cart_cookie_id) {
			$cart_cookie_id = $this->setCartCookie();
		}
		$product['cookie_id'] = $cart_cookie_id;
		$this->Addcart($product);
		if(isset($request->key) && $request->key == 'checkout') {
			$html = $this->ShowCheckoutcart($user_id,$res_id);
		} elseif(isset($request->key) && $request->key == 'searchcart') {
			$html = $this->ShowSearchcCart($res_id,$user_id,$cart_cookie_id);
		} else {
			$cur_resid = $res_id;
			$html = $this->Showcart($cur_resid,$res_id,$user_id,$cart_cookie_id);
		}
		return $html;
	}

	public function getRemovefromcart(Request $request){

		$hotel_item	= array();
		$item_id	= $request->item;
		$res_id		= $request->res_id;
		$html		= '';$user_id = 0;$cart_cookie_id = 0;
		$hotel_item	= \DB::table('abserve_hotel_items')->select('food_item','price')->where('id', $item_id)->first();
		$stax		= \DB::table('abserve_restaurants')->select('service_tax')->where('id',$res_id)->first();
		
		$product['food_id']		= $item_id;
		$product['food_item']	= $hotel_item->food_item;
		$product['price']		= $hotel_item->price;
		$product['quantity']	= $request->qty;
		$product['res_id']		= $res_id;
		$product['tax']			= $stax->service_tax;
		if(Auth::check()){
			$user_id = Auth::user()->id;
			$product['user_id'] = $user_id;
		} else {
			$product['user_id'] = 0;
		}
		$cart_cookie_id = $this->getCartCookie();
		if(!$cart_cookie_id) {
			$cart_cookie_id = $this->setCartCookie();
		}
		$product['cookie_id'] = $cart_cookie_id;
		$this->Addcart($product);
		if(isset($request->key) && $request->key == 'checkout'){
			$html = $this->ShowCheckoutcart($user_id,$res_id);
		} elseif(isset($request->key) && $request->key == 'searchcart') {
			$html = $this->ShowSearchcCart($res_id,$user_id,$cart_cookie_id);
		} else {
			$cur_resid = $res_id;
			$html = $this->Showcart($cur_resid,$res_id,$user_id,$cart_cookie_id);
		}
		//return $html;
		$response['html'] = (string) $html;
		if($html == '')
			$cart = 'empty';
		else
			$cart = 'notempty';
		$response['cart'] = $cart;
		return Response::json($response);
	}

	public function Addcart( $input){
		

		$values = array("user_id"=>$input['user_id'],"res_id"=>$input['res_id'],"food_id"=>$input['food_id'],"food_item"=>$input['food_item'],"price"=>$input['price'],"quantity"=>$input['quantity'],"cookie_id"=>$input['cookie_id'],"tax"=>$input['tax']);
			if($input['user_id'] != '0'){
				$user_res_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$input['user_id'])
				->where("res_id",'=',$input['res_id'])
				->exists();
			} else { 
				$user_res_equal = \DB::table('abserve_user_cart')
				->where("cookie_id",'=',$input['cookie_id'])
				->where("res_id",'=',$input['res_id'])
				->exists();	
			}

			if($user_res_equal){

				if($input['user_id'] != '0'){
					$user_food_equal = \DB::table('abserve_user_cart')
					->where("user_id",'=',$input['user_id'])
					->where("food_id",'=',$input['food_id'])
					->exists();
			    } else {
					$user_food_equal = \DB::table('abserve_user_cart')
					->where("cookie_id",'=',$input['cookie_id'])
					->where("food_id",'=',$input['food_id'])
					->exists();
				}

				if($user_food_equal){

					if($input['user_id'] != '0')
					{
					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$input['user_id'])
					->where("food_id",'=',$input['food_id'])
					->get();
				    } else { 
						$quantity = \DB::table('abserve_user_cart')
						->select('*')
						->where("cookie_id",'=',$input['cookie_id'])
						->where("food_id",'=',$input['food_id'])
						->get();
					}

					$fid = $quantity[0]->id;
					if($input['quantity'] == 0){
                        \DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();
					} else {
						$vals = array("user_id"=>$input['user_id'],"res_id"=>$input['res_id'],"food_id"=>$input['food_id'],"food_item"=>$input['food_item'],"price"=>$input['price'],"quantity"=>$input['quantity'],"cookie_id"=>$input['cookie_id'],"tax"=>$input['tax']);
						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($vals);
					}
				} else {
					\DB::table('abserve_user_cart')->insert($values);
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $input['user_id'])->delete();
				\DB::table('abserve_user_cart')->insert($values);
			}
	}	

	public function Showcart($cur_resid,$res_id,$user_id,$cookie_id,$keyinfo='') {
		$min_val = (\Session::has('min_order_val')) ? \Session::get('min_order_val') : '0';
		$min_order_val = number_format((float)\SiteHelpers::CurrencyValue($min_val));
		$resInfo = Restaurant::find($cur_resid);
		$date = new \DateTime();
		$timeval1=date("H:i ", time());
		$timeval2=date("H:i ", strtotime($resInfo->opening_time));
		$timeval3=date("H:i ", strtotime($resInfo->closing_time));
		//echo $res_id;exit;
		//echo $timeval1."===".$timeval2."===".$timeval3;exit;

		/*$timeval1 = DateTime::createFromFormat('H:i A', $current_time);
		$timeval2 = DateTime::createFromFormat('H:i A', $resInfo->opening_time);
		$timeval3 = DateTime::createFromFormat('H:i A', $resInfo->closing_time);*/
		
		if ($timeval1 > $timeval2 && $timeval1 < $timeval3)
		{
			$res_timeValid = 1;
		} elseif($timeval1 > $timeval2 && $timeval1 > $timeval3){
			if($timeval3 >= 0 && $timeval3 < $timeval2){
				$res_timeValid = 1;
			} else {
				$res_timeValid = 0;
			}
		} else {
			$res_timeValid = 0;
		}
		\Session::put('restimevalid',$res_timeValid);
		//echo '<pre>';print_r($timeval1);exit;
		if($res_timeValid == 1){
			if($user_id != '0'){

				$user_food_equal = \DB::table('abserve_user_cart')
					->where("user_id",'=',$user_id)
					->exists();
					if($user_food_equal){
						
						$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->where("res_id",'=',$res_id)->get();
					}else{
	                    
						$cookie_food_equal = \DB::table('abserve_user_cart')
							->where("cookie_id",'=',$cookie_id)
							->where("res_id",'=',$res_id)
							->exists();

							if($cookie_food_equal){
								$array['user_id'] = $user_id;
								\DB::table('abserve_user_cart')
								->where("cookie_id",'=',$cookie_id)
								->update($array);
								$foods_items = \DB::table('abserve_user_cart')->select('*')->where("cookie_id",'=',$cookie_id)->where("res_id",'=',$res_id)->get();
							}
					}
			} else if($cookie_id != '0') {
				$foods_items = \DB::table('abserve_user_cart')->select('*')->where("cookie_id",'=',$cookie_id)->where("res_id",'=',$res_id)->get();
			}

			$innerhtml = '';$item_total = 0;$delivery_charge = '0.00';

			if(!empty($foods_items)){ 
				$currsymbol = (\Session::has('currency_symbol')) ? \Session::get('currency_symbol') : '$';
				foreach ($foods_items as $ky => $val) {
					$item_time_valid = \SiteHelpers::getItemTimeValid($val->food_id);
					if($item_time_valid == 1){
						$price_val  = number_format((float)\SiteHelpers::CurrencyValue($val->price),2,'.','');
						$total = ($val->quantity * $price_val);
						$innerhtml.=' <div class="menu-cart-items" id="item_'.$val->food_id.'">
				            <div class="container-fluid">
				                <div class="row no-pad">
				                    <div class="col-xs-5 no-pad">
				                        <p class="veg-item">'.$val->food_item.'</p>
				                    </div>
				                    <div class="col-xs-3 block-item text-center no-pad items_count" id="fnitem_'.$val->food_item.'">
				                     	<i data-faid="'.$val->food_id.'" class="fa fa-minus remove_cart_item" aria-hidden="true" style="cursor:pointer;"></i>
				                        <span class="item-count">'.$val->quantity.'</span>
				                      	<i data-faid="'.$val->food_id.'" id="fitem_'.$val->food_id.'" class="fa fa-plus add_cart_item" aria-hidden="true" style="cursor:pointer;"></i>
				                    </div>
				                    <div class="col-xs-4 no-left-pad block-item text-right">
				                        <div>
				                            '.$currsymbol.' <span class="item-price">'.number_format((float)$total, 2, '.', '').'</span>
				                        </div>
				                    </div>
				                </div>
				                
				            </div>
				        </div>';
				        $item_total+=$total;
				    }
				}
				$html = '';
				if($innerhtml != ''){
					/*if($item_total>500){
						$delivery_charge = 0.00;
					}
					else{*/
						$arr = \DB::table('abserve_restaurants')->select('delivery_charge')->where('id', '=', $res_id)->first();
						$delivery_charge  = number_format((float)\SiteHelpers::CurrencyValue($arr->delivery_charge),2,'.','');
						//$delivery_charge = ($arr->delivery_charge);
					//}
					if($keyinfo == 'checkout'){
						$closetime = date("H:i a", strtotime($resInfo->opening_time));
						$bookingnote = '<h5><font color="red">Restaurent will be closed sooner.Next available at '.$closetime.'</font></h5>';
					} else {
						$bookingnote = '';
					}
					//if($item_total >= $min_order_val) {
						$checkout = "<a href='".\URL::to('/frontend/checkout')."'><button class='btn btn-checkout' id='btn-checkout' >Checkout</button></a>";
						/*$min_order_note ='<h5><font color="red">Atleast you have to purchase '.$currsymbol.' '.$min_order_val.'. ';
					} else {
						$checkout = "<a href='javascript:void(0);'><button class='btn btn-checkout' id='btn-checkout' >Checkout</button></a>";
						$min_order_note = '<h5><font color="red">Atleast you have to purchase '.$currsymbol.' '.$min_order_val.'. ';
					}*/
					$html = ' <section>
				                <div class="menu-cart-title">
				                    <h1>Your Cart</h1>'.$bookingnote.'
				                </div>
				                <div class="restaurent_name">Restaurant: '.$resInfo->name.'</div>
				                <div class="menu-cart-body " >
				                  '.$innerhtml.'
				                
				                </div>
				                <div class="menu-cart-footer">
							        <div class="sub-total" >
							            <div class="charges">
							                <table class="table">
							                    <tbody>
							                        <tr>
							                            <td class="">Item Total : </td>
							                            <td class="text-right" >'.$currsymbol.' '.number_format((float)$item_total, 2, '.', '').'</td>
							                        </tr>
							                        <tr>
							                            <td class="">Delivery Charges : </td>
							                            <td class="text-right">'.$currsymbol.' '.number_format((float)$delivery_charge, 2, '.', '').'</td>
							                        </tr>
							                    </tbody>
							                </table>
							            </div>
							        </div>';
				        	$grand_total = $item_total + $delivery_charge;
					     	$html.= '   <div class="final-total"  style="">
					           
					            <h5><span class="">Grand Total:</span>
					                
					                <span class="pull-right">'.$currsymbol.' <span class="grand_total">'.number_format((float)$grand_total, 2, '.', '').'</span></span>
					            </h5>
					        </div>';
					        if(Auth::check()){
					        $html.= $checkout;
					    	}else{
					    		 $html.= '<button class="btn btn-checkout" onclick="javascript:login_popup();" id="btn-checkout" >Checkout</button>';
					    	}
					     $html.= '</div>
		            </section>';
				}else{
					 $html.= '<section>
		                <div class="menu-cart-title"><h1>Your Cart</h1></div>
		                <div class="menu-cart-body empty" ><div class="cart-quotes"></div></div>
		                <div class="menu-cart-footer">
		                    <button class="btn btn-checkout" disabled id="btn-checkout">Checkout</button>
		                </div>
		            </section>';
				}
			} else {

				$html.= '<section>
				    <div class="menu-cart-title"><h1>Your Cart</h1></div>
				    <div class="menu-cart-body empty" ><div class="cart-quotes"></div></div>
				    <div class="menu-cart-footer">
				        <button class="btn btn-checkout" disabled id="btn-checkout">Checkout</button>
				    </div>
				</section>';
			}
		} else {
			$html.= '<section>
				    <div class="menu-cart-title"><h1>Your Cart</h1></div>
				    <h5><div class="info">
         			<span class="label label-warning">Next available at '.$resInfo->opening_time.'</span>
          			</div></h5>
				    <div class="menu-cart-body empty" ><div class="cart-quotes"></div></div>
				    <div class="menu-cart-footer">
				        <button class="btn btn-checkout" disabled id="btn-checkout">Checkout</button>
				    </div>
				</section>';
		}
		return $html;
	}	

	public  function getCheckout(Request $request){
		if(\Auth::check()){
			$user_id = Auth::user()->id;
			$rs_query = \DB::table('abserve_user_cart')->select('res_id')->where("user_id",'=',$user_id)->first();
			$exists = \DB::table('abserve_user_cart')->select('res_id')->where("user_id",'=',$user_id)->exists();
			$restaurant_id = $rs_query->res_id;
			if($exists){
				if($restaurant_id != ''){
					$resInfo = Restaurant::find($restaurant_id);
					$date = new DateTime();
					$datetime2 = new DateTime($resInfo->closing_time);
					$interval = $date->diff($datetime2);
					$elapsed = ($interval->format('%h') * 60 ) + $interval->format('%i');
					$current_time=date("H:i a", time());
					$timeval1=date("H:i ", time());
					$timeval2=date("H:i ", strtotime($resInfo->opening_time));
					$timeval3=date("H:i ", strtotime($resInfo->closing_time));
					//echo '<pre>';print_r($timeval1);exit;
					if ($timeval1 > $timeval2 && $timeval1 < $timeval3)
					{
					   $res_timeValid = 1;
					} elseif($timeval1 > $timeval2 && $timeval1 > $timeval3){
						if($timeval3 >= 0 && $timeval3 < $timeval2){
							$res_timeValid = 1;
						} else {
							$res_timeValid = 0;
						}
					} else {
						$res_timeValid = 0;
					}
					\Session::put('restimevalid',$res_timeValid);
					if($res_timeValid == 1){
						if($elapsed >= 60) {
							$restaurant =  \DB::table('abserve_restaurants')->select('id', 'name','delivery_time','latitude','longitude','location')
					            ->where('id', '=', $restaurant_id)->first();

					        $this->data['restaurant'] = $restaurant;
					        $user_address = \DB::table('abserve_user_address')
											->where("user_id",'=',$user_id)->get();
					      	$this->data['address'] = $user_address;
							$this->data['cart_items_html'] = $this->ShowCheckoutcart($user_id,$restaurant_id);
							$this->data['pages'] = 'frontend.checkout_old';	
							$page = 'layouts.'.CNF_THEME.'.index';
							return view($page,$this->data); 
						} else {
							\Session::put('keyinfo','checkout');
							return Redirect::to('frontend/details/'.$restaurant_id);
						}
					} else {
						\Session::put('keyinfo','checkout');
						return Redirect::to('frontend/details/'.$restaurant_id);
					}
				}else{
					//echo 'lastelse';exit;
					//return Redirect::to('/');
					return Redirect::to('frontend/search');
				}
			} else {
				return Redirect::to('frontend/search');
			}
		} else {
			return Redirect::to('user/login');
		}
	}	

	public function ShowCheckoutcart($user_id,$res_id){
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
		$innerhtml = '';$item_total = 0;$delivery_charge = '0.00';
		$i = 0;
		$currsymbol = (\Session::has('currency_symbol')) ? \Session::get('currency_symbol') : '$';
		foreach ($foods_items as $ky => $val) {
			$item_time_valid = \SiteHelpers::getItemTimeValid($val->food_id);
			if($item_time_valid == 1){
				if($val->quantity > 0){
					$i++;
				}
				$price_val = number_format((float)\SiteHelpers::CurrencyValue($val->price),2,'.','');
				$total = ($val->quantity * $price_val);
				$innerhtml.=' <div class="menu-cart-items" id="item_'.$val->food_id.'">
		            <div class="container-fluid">
		                <div class="row" style="padding:8px 8px 0;">
		                    <div class="col-xs-5 no-pad">
		                        <p class="veg-item">'.$val->food_item.'</p>
		                    </div>
		                    <div class="col-xs-3 block-item text-center no-pad" id="fnitem_'.$val->food_item.'">
		                     	<i data-faid="'.$val->food_id.'" class="fa fa-minus-circle remove_cart_item" aria-hidden="true" style="cursor:pointer;"></i>
		                        <span class="item-count">'.$val->quantity.'</span>
		                      	<i data-faid="'.$val->food_id.'" id="fitem_'.$val->food_id.'" class="fa fa-plus-circle add_cart_item" aria-hidden="true" style="cursor:pointer;"></i>
		                    </div>
		                    <div class="col-xs-4 no-pad block-item text-right">
		                        <div>
		                            '.$currsymbol.'<span class="item-price">'.number_format((float)$total, 2, '.', '').'</span>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>';
		        $item_total+=$total;
		    }
		}

		$html = '';
				/*if($item_total>500){
					$delivery_charge = 0.00;
				}
				else{*/
					$arr = \DB::table('abserve_restaurants')->select('delivery_charge')->where('id', '=', $res_id)->first();
					$arr_res_name = \DB::table('abserve_restaurants')->select('name')->where('id', '=', $res_id)->first();
					$delivery_charge = number_format((float)\SiteHelpers::CurrencyValue($arr->delivery_charge),2,'.','');
					//$delivery_charge = ($arr->delivery_charge);
					 $res_name       =  ($arr_res_name->name);
				//}
		if($i > 0){
			$html = ' <section>
		                <div class="menu-cart-title">
		                    <h1>Restaurant:'.$res_name.'</h1>
		                </div>
		                <div class="menu-cart-body " >
		                  '.$innerhtml.'
		                
		                </div>
		                <div class="menu-cart-footer">
					        <div class="sub-total" >
					            <div class="charges">
					                <table class="table">
					                    <tbody>
					                        <tr>
					                            <td class="">Item Total : </td>
					                            <td class="text-right" >'.$currsymbol.' '.number_format((float)$item_total, 2, '.', '').'</td>
					                        </tr>
					                        <tr>
					                            <td class="">Delivery Charges :<span class="posrelative"> <i class="fa fa-info-circle delivery_btn" aria-hidden="true"></i>
					                            <span class="delivery_content">Base delivery charges applicable on restaurant to help us serve you better</span></span>
					                            </td>
					                            <td class="text-right">'.$currsymbol.' '.number_format((float)$delivery_charge, 2, '.', '').'</td>
					                        </tr>
					                    </tbody>
					                </table>
					            </div>
					        </div>';
		        	$grand_total = $item_total + $delivery_charge ;
			     	$html.= '   <div class="final-total">
			           
			            <h5>Grand Total:
			                <span class="pull-right">'.$currsymbol.' <span class="grand_total checkout_payment">'.number_format((float)$grand_total, 2, '.', '').'</span></span>
			            </h5>
			        </div>';
			        
			     $html.= '</div>
            </section>';
        } else {
        	$html = '';
        }
		
        return $html;
	}	

	public function getCheckaddress(Request $request){
		$from = $request->from;
		$to = $request->addr;
		$data = $this->getDistance($from,$to,"K");
		
		if($data > 5){
			return 0;
		}else{

			return 1;
		}	
	}

	public function getCheckneareraddress(Request $request){
		if(\Auth::check()){
		 $restaurant = \DB::select("SELECT `location` FROM `abserve_restaurants` where `id`=".$_GET['res_id']);
		 $selectedaddress = \DB::select("SELECT `address` FROM `abserve_user_address` where `id`=".$_GET['address_id']);  
		 $distance = $this->calculate_dist($restaurant[0]->location,$selectedaddress[0]->address);
		 
			if($distance > 5000){
				return json_encode(0);
			}else{
				return json_encode(1);
			}	

	   	} else {
	   		return json_encode(2);
	   	}
	}

	public function getDistance($addressFrom, $addressTo, $unit){
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
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}

	public function postAddaddress(Request $request){
		$user_id = Auth::user()->id;
		$values = array("user_id"=>$user_id,
						"address_type"=>$request->address_type,
						"landmark"=>$request->landmark,
						"building"=>$request->building,
						"address"=>$request->a_addr,
						"lat"=>$request->a_lat,
						"lang"=>$request->a_lang);

		$addr_exists = \DB::table('abserve_user_address')
						->where("user_id",'=',$user_id)
						->where("address_type",'=',$request->address_type)
						->exists();

		if($addr_exists){
			$array['address_type'] = 3;
			\DB::table('abserve_user_address')
			->where("user_id",'=',$user_id)
			->update($array);
		}

		\DB::table('abserve_user_address')->insert($values);	
		$user_address = \DB::table('abserve_user_address')
						->where("user_id",'=',$user_id)->get();
		$html = '';
		foreach($user_address as $uaddr){
			if($uaddr->address_type == '1'){
				$add_type= "Home";$icon = '<i class="fa fa-home"></i>';
			}else if($uaddr->address_type == '2'){
				$add_type= "Work";$icon = '<i class="fa fa-briefcase"></i>';
			}
			else{
				$add_type= "Others";$icon = '<i class="fa fa-book"></i>';
			} 
				

			$html.='<div class="desktop clearfix">
		        <div class="left">
		            '.$icon.'
		            <h6 class="text-ellipsis">'.$add_type.'</h6>
		        </div>
		        <div class="middle">
		            <address>
		                <span class="addr-line">'.$uaddr->building.', '.$uaddr->landmark.', '.$uaddr->address.' </span>
		            </address>
		        </div>
		        <div class="right">
		            <div class="checkbox">
		                <label>
		                    <input type="radio" name="address" id="address"  value="'.$uaddr->id.'">
		                </label>
		            </div>
		        </div>
		    </div>';
		}
		return $html;	
	}

	public function getCheckcart(Request $request){
		
		
		if(Auth::check()){
			$user_id = Auth::user()->id;

		    $user_food_equal =	\DB::select("SELECT * FROM `abserve_user_cart` WHERE `user_id` = ".$user_id."");

				if(empty($user_food_equal)){
					return 0;
				}else{

					 if($_GET['res_id'] == $user_food_equal[0]->res_id){

					 	return 0;

					 }else{

					 	return 1;

					 }					
					
				}
		}else{
			$cart_cookie_id = $this->getCartCookie();
			$cookie_food_equal = \DB::table('abserve_user_cart')
								->where("cookie_id",'=',$cookie_id)
								->exists();
			if($cookie_food_equal){
				return 1;
			}else{
				return 0;
			}
		}	
	}

	public  function getMyaccount(Request $request){
		if(\Auth::check()){
			$user_id		= Auth::user()->id;
			//print_r($user_id);exit;
			$userData 		= \DB::table('tb_users')->select('*')->where('id',$user_id)->first();
		 	$user_address	= \DB::table('abserve_user_address')
							->where("user_id",'=',$user_id)->get();
	        
	        $order_details = \DB::select("SELECT * FROM abserve_order_details INNER JOIN abserve_orders_customer ON abserve_order_details.id = abserve_orders_customer.orderid where abserve_order_details.cust_id=".$user_id." ORDER BY abserve_order_details.id DESC");
	        	$this->data['userImg'] = $userData->avatar;
	        //echo $this->data['userImg'];exit;
	        $this->data['userdata']	= $userData;
	        $this->data['orders']	= $order_details;
	      	$this->data['address']	= $user_address;
			$this->data['pages']	= 'frontend.myaccount';
			$page = 'layouts.'.CNF_THEME.'.index';
			return view($page,$this->data); 
		} else {
			return Redirect::to('user/login');
		}
	}

	public function postUpdateaddress(Request $request){


		$user_id = Auth::user()->id;
		$array = array("user_id"=>$user_id,
						"address_type"=>$request->address_type,
						"landmark"=>$request->landmark,
						"building"=>$request->building,
						"address"=>$request->a_addr,
						"lat"=>$request->a_lat,
						"lang"=>$request->a_lang);
		$id =  $request->id;
		$addr_exists = \DB::table('abserve_user_address')
						->where("user_id",'=',$user_id)
						->whereNotIn("id",array($id))
						->where("address_type",'=',$request->address_type)
						->exists();
		if($addr_exists){
			$array['address_type'] = 3;
		}
			
		\DB::table('abserve_user_address')
			->where("id",'=',$id)
			->update($array);
		
		$user_address = \DB::table('abserve_user_address')
						->where("id",'=',$id)->first();
		$html = '';
			if($user_address->address_type == '1'){
				$add_type= "Home";$icon = '<i class="fa fa-home"></i>';
			}else if($user_address->address_type == '2'){
				$add_type= "Work";$icon = '<i class="fa fa-briefcase"></i>';
			}
			else{
				$add_type= "Others";$icon = '<i class="fa fa-book"></i>';
			} 
				
			$html.='
		        <div class="left">
		            <span class="annotation">'.$icon.'</span>
		            <h6 class="text-ellipsis">'.$add_type.'</h6>
		        </div>
		        <div class="actions">
			            <a href="javascript:edit('.$id.');" class="bootstrap-link edit_address" ><i class="fa fa-pencil"></i>&nbsp; Edit</a>
			            <a  class="bootstrap-link del_address " href="javascript:remove('.$id.');" ><i class="fa fa-trash"></i>&nbsp; Delete</a>
		        </div>
		        <div class="middle">
		                <span class="addr-line addressBlock">'.$user_address->building.', '.$user_address->landmark.', '.$user_address->address.' </span>
		        </div>
		             ';
		return $html;
	}

	public function getPendingcart(Request $request){		
		if(\Auth::check()){
			$oid = base64_decode($request->oid);
			$authid = \Auth::user()->id;
			if($oid != ''){
				$cart_cookie_id = $this->getCartCookie();
				$order_det = \DB::table('abserve_order_details')->where('id','=',$oid)->first();
				$rid = $order_det->res_id;
				$resInfo = Restaurant::find($rid);
				if(count($resInfo)>0 && $rid != ''){
					$res_timeValid = \SiteHelpers::getResTimeValid($rid);
					/*Restaurent time checking*/
					if($res_timeValid == 1){
						$stax = \DB::table('abserve_restaurants')->select('service_tax')->where('id',$rid)->first();
						$custid = $order_det->cust_id;
						if($custid == $authid){
							$prev_cart =Usercart::where('user_id',$authid)->get();
							if(count($prev_cart) > 0){
								Usercart::where('user_id',$authid)->delete();
							}
							$fooditems = \DB::table('abserve_order_items')->where('orderid','=',$oid)->get();
							if(count($fooditems) > 0){
								foreach($fooditems as $fitems){
									$item_info = Fooditems::find($fitems->food_id);
									/*Menu time checking*/
									$item_time_valid = \SiteHelpers::getItemTimeValid($item_info->id);
									if($item_time_valid == 1){
										$values = array("user_id"=>$authid,"res_id"=>$rid,"food_id"=>$fitems->food_id,"food_item"=>$fitems->food_item,"price"=>$fitems->price,"quantity"=>$fitems->quantity,"cookie_id"=>$cart_cookie_id,"tax"=>$stax->service_tax);
										\DB::table('abserve_user_cart')->insert($values);
									} 
								}
								return Redirect::to('frontend/details/'.$rid);
							}
						}
					} else {
						return Redirect::to('frontend/details/'.$rid);
					}
				} else {
					return Redirect::to('frontend/search')->with('message',\SiteHelpers::alert('error','No such restaurant found'));
				}
			}
		} else {
			return Redirect::to('user/login');
		}
	}

	public function postSetcartitmes(Request $request) {
		$cookieID = $request->cookieid;
		$cookie_id = $this->getCartCookie();
		$updated = \SiteHelpers::CartCookieItem($cookie_id);
		$response['message'] = $updated;
		return Response::json($response);
	}

	public function postSaverating (Request $request) {
		$rid = $request->rid;
		$rat = $request->rating;
		$authid = \Auth::user()->id;
		$rating_info = \DB::table('abserve_rating')->where('cust_id','=',$authid)->where('res_id','=',$rid)->first();
		if(count($rating_info) > 0) {
			$updated = \DB::table('abserve_rating')->where('cust_id',$authid)->where('res_id',$rid)->update(array('rating'=>$rat));
		} else {
			$updated = \DB::table('abserve_rating')->insert(array('cust_id'=>$authid,'res_id'=>$rid,'rating'=>$rat));
		}
		if($updated){
			$response['message'] = 'success';
		} else {
			$response['message'] = 'fail';
		}
		return Response::json($response);
	}
}
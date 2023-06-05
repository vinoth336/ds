<?php  namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Homebanner;
use App\Models\Foodbanner;
use App\Models\Servicebannerfront;
use App\Models\Foodslider;
use App\Models\Serviceslider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index( Request $request ) {
		if(Auth::user()->group_id == 3){
			return Redirect::to('/dashboard');
		}
		if(!(Session::get('lang')) || Session::get('lang') == '') {
			Session::put('lang','en');
		}
		$abserve_blocks = DB::select("SELECT * FROM `abserve_blocks` ");
		$this->data['abserve_blocks'] = $abserve_blocks;
		
		if(CNF_FRONT =='false' && $request->segment(1) =='' ) :
			return Redirect::to('dashboard');
		endif; 		

		$page = $request->segment(1); 	
		if($page !='') :
			$content = DB::table('tb_pages')->where('alias','=',$page)->where('status','=','enable')->get();
			if(count($content) >=1) {
							
				$row = $content[0];
				$this->data['pageTitle']	= $row->title;
				$this->data['pageNote']		= $row->note;
				$this->data['pageMetakey']	= ($row->metakey !='' ? $row->metakey : CNF_METAKEY) ;
				$this->data['pageMetadesc']	= ($row->metadesc !='' ? $row->metadesc : CNF_METADESC) ;
				$this->data['breadcrumb']	= 'active';
				
				
				$foodbanners 	= Foodbanner::where('status',1)->get();
				$servicebanners = Servicebannerfront::where('status',1)->get();
				$foodslider 	= Foodslider::where('status',1)->get();
				$serviceslider 	= Serviceslider::where('status',1)->get();
				
				$this->data['food_banners']		= $foodbanners;
				$this->data['service_banners']	= $servicebanners;
				$this->data['food_sliders']		= $foodslider;
				$this->data['service_sliders']	= $serviceslider;
				
				if($row->access !='') {
					$access = json_decode($row->access,true)	;	
				} else {
					$access = array();
				}	

				// If guest not allowed 
				if($row->allow_guest !=1) {	
					$group_id = Session::get('gid');
					$isValid =  (isset($access[$group_id]) && $access[$group_id] == 1 ? 1 : 0 );	
					if($isValid ==0) {
						return Redirect::to('')
							->with('message', \SiteHelpers::alert('error',Lang::get('core.note_restric')));				
					}
				}				
				if($row->template =='backend') {
					 $page = 'pages.'.$row->filename;
				} else {
					$page = 'layouts.'.CNF_THEME.'.index';
				}

				$filename = base_path() ."/resources/views/pages/".$row->filename.".blade.php";
				if($row->filename=='faq') {
					$this->data['faq_categories'] = DB::table('abserve_faq_categories')->select('*')->get();
					
					$this->data['faq_contents'] = DB::table('abserve_faq_contents')->select('*')->get();
				}
				if(file_exists($filename)) {
					$this->data['pages'] = 'pages.'.$row->filename;

					return view($page,$this->data);
				} else {
					return Redirect::to('')
						->with('message', \SiteHelpers::alert('error',\Lang::get('core.note_noexists')));					
				}
			} else {
				return Redirect::to('')
					->with('message', \SiteHelpers::alert('error',\Lang::get('core.note_noexists')));	
			}
	
			else :
			if(\Auth::check()){
				$user_id	= Auth::user()->id;
				$yourcart1	=  DB::table('abserve_user_cart')->where('user_id','=',$user_id)/*->where('id', DB::raw("(select max(`id`) from abserve_user_cart)"))*/->first();
				$restaurant_id	= $yourcart1->res_id;
				$cart_cookie_id	= $this->getCartCookie();
				$cart_items_html = $this->data['cart_items_html'] = $this->ShowSearchcCart($restaurant_id,$user_id,$cart_cookie_id);
				$this->data['search_cart_res_id'] = $restaurant_id;
			} else {
				$cart_cookie_id	= $this->getCartCookie();
				$yourcart1		=  DB::table('abserve_user_cart')->where('cookie_id','=',$cart_cookie_id)/*->where('id', DB::raw("(select max(`id`) from abserve_user_cart)"))*/->first();
				$restaurant_id = $yourcart1->res_id;
				$this->data['cart_items_html'] = $this->ShowSearchcCart($restaurant_id,0,$cart_cookie_id);
			}
			$this->data['pageTitle']	= 'Home';
			$this->data['pageNote']		= 'Welcome To Our Site';
			$this->data['breadcrumb']	= 'inactive';	
			$this->data['pageMetakey']	=  CNF_METAKEY ;
			$this->data['pageMetadesc']	= CNF_METADESC ;
			$this->data['pages']		= 'pages.home';	
			
			$banners 		= Homebanner::where('status',1)->get();			
			$this->data['home_banners']		= $banners;
			
			$page = 'layouts.'.CNF_THEME.'.index';
			return view($page,$this->data); 
		endif;
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
				$user_food_equal = DB::table('abserve_user_cart')
				->where("user_id",'=',$user_id)
				->exists();
				if($user_food_equal){
					
					$foods_items = DB::table('abserve_user_cart')->select('*')->where('res_id','=',$res_id)->where('user_id','=',$user_id)->get();
				}
			} else if($cookie_id) {
				$foods_items = DB::table('abserve_user_cart')->select('*')->where("cookie_id",'=',$cookie_id)->where("res_id",'=',$res_id)->get();
			}

			$innerhtml = '';$item_total = 0;$delivery_charge = '0.00';
			//echo '<pre>';print_r($foods_items);exit;
			if(!empty($foods_items)){
				$currsymbol = (Session::has('currency_symbol')) ? Session::get('currency_symbol') : '£';
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
						$arr = DB::table('abserve_restaurants')->select('delivery_charge')->where('id', '=', $res_id)->first();
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
							                        
							                    </tbody>
							                </table>
							            </div>
							        </div>';
				        	$grand_total = $item_total ;
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
		$hotel_item	= DB::table('abserve_hotel_items')->select('food_item','price')->where('id', $item_id)->first();
		$stax = DB::table('abserve_restaurants')->select('service_tax')->where('id',$res_id)->first();
		
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
		$hotel_item	= DB::table('abserve_hotel_items')->select('food_item','price')->where('id', $item_id)->first();
		$stax		= DB::table('abserve_restaurants')->select('service_tax')->where('id',$res_id)->first();
		
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

	public function Showcart($cur_resid,$res_id,$user_id,$cookie_id,$keyinfo='') {
		$min_val = (Session::has('min_order_val')) ? Session::get('min_order_val') : '0';
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
		Session::put('restimevalid',$res_timeValid);
		//echo '<pre>';print_r($timeval1);exit;
		if($res_timeValid == 1){
			if($user_id != '0'){

				$user_food_equal = DB::table('abserve_user_cart')
					->where("user_id",'=',$user_id)
					->exists();
					if($user_food_equal){
						
						$foods_items = DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->where("res_id",'=',$res_id)->get();
					}else{
	                    
						$cookie_food_equal = DB::table('abserve_user_cart')
							->where("cookie_id",'=',$cookie_id)
							->where("res_id",'=',$res_id)
							->exists();

							if($cookie_food_equal){
								$array['user_id'] = $user_id;
								DB::table('abserve_user_cart')
								->where("cookie_id",'=',$cookie_id)
								->update($array);
								$foods_items = DB::table('abserve_user_cart')->select('*')->where("cookie_id",'=',$cookie_id)->where("res_id",'=',$res_id)->get();
							}
					}
			} else if($cookie_id != '0') {
				$foods_items = DB::table('abserve_user_cart')->select('*')->where("cookie_id",'=',$cookie_id)->where("res_id",'=',$res_id)->get();
			}

			$innerhtml = '';$item_total = 0;$delivery_charge = '0.00';

			if(!empty($foods_items)){ 
				$currsymbol = (Session::has('currency_symbol')) ? Session::get('currency_symbol') : '£';
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
						$arr = DB::table('abserve_restaurants')->select('delivery_charge')->where('id', '=', $res_id)->first();
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
							                       
							                    </tbody>
							                </table>
							            </div>
							        </div>';
				        	$grand_total = $item_total ;
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
			$rs_query = DB::table('abserve_user_cart')->select('res_id')->where("user_id",'=',$user_id)->first();
			$exists = DB::table('abserve_user_cart')->select('res_id')->where("user_id",'=',$user_id)->exists();
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
					Session::put('restimevalid',$res_timeValid);
					if($res_timeValid == 1){
						if($elapsed >= 60) {
							$restaurant =  DB::table('abserve_restaurants')->select('id', 'name','delivery_time','latitude','longitude','location')
					            ->where('id', '=', $restaurant_id)->first();

					        $this->data['restaurant'] = $restaurant;
					        $user_address = DB::table('abserve_user_address')
											->where("user_id",'=',$user_id)->get();
					      	$this->data['address'] = $user_address;
					      	
					      	$user_address_new = DB::table('abserve_user_address')
											->where("user_id",'=',$user_id)->limit(1)->get();
					      	$this->data['address1'] = $user_address_new;
							$this->data['cart_items_html'] = $this->ShowCheckoutcart($user_id,$restaurant_id);
							$this->data['pages'] = 'frontend.checkout_old';	
							$page = 'layouts.'.CNF_THEME.'.index';
							return view($page,$this->data); 
						} else {
							Session::put('keyinfo','checkout');
							return Redirect::to('frontend/details/'.$restaurant_id);
						}
					} else {
						Session::put('keyinfo','checkout');
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
		$foods_items = DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$user_id)->get();
		$innerhtml = '';$item_total = 0;$delivery_charge = '0.00';
		$i = 0;
		$currsymbol = (Session::has('currency_symbol')) ? Session::get('currency_symbol') : '£';
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
					$arr = DB::table('abserve_restaurants')->select('delivery_charge')->where('id', '=', $res_id)->first();
					$arr_res_name = DB::table('abserve_restaurants')->select('name')->where('id', '=', $res_id)->first();
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
	public function getCheckcart(Request $request){
		
		
		if(Auth::check()){
			$user_id = Auth::user()->id;

		    $user_food_equal =	DB::select("SELECT * FROM `abserve_user_cart` WHERE `user_id` = ".$user_id."");

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
			$cookie_food_equal = DB::table('abserve_user_cart')
								->where("cookie_id",'=',$cookie_id)
								->exists();
			if($cookie_food_equal){
				return 1;
			}else{
				return 0;
			}
		}	
	}

	public function getPendingcart(Request $request){		
		if(\Auth::check()){
			$oid = base64_decode($request->oid);
			$authid = \Auth::user()->id;
			if($oid != ''){
				$cart_cookie_id = $this->getCartCookie();
				$order_det = DB::table('abserve_order_details')->where('id','=',$oid)->first();
				$rid = $order_det->res_id;
				$resInfo = Restaurant::find($rid);
				if(count($resInfo)>0 && $rid != ''){
					$res_timeValid = \SiteHelpers::getResTimeValid($rid);
					/*Restaurent time checking*/
					if($res_timeValid == 1){
						$stax = DB::table('abserve_restaurants')->select('service_tax')->where('id',$rid)->first();
						$custid = $order_det->cust_id;
						if($custid == $authid){
							$prev_cart =Usercart::where('user_id',$authid)->get();
							if(count($prev_cart) > 0){
								Usercart::where('user_id',$authid)->delete();
							}
							$fooditems = DB::table('abserve_order_items')->where('orderid','=',$oid)->get();
							if(count($fooditems) > 0){
								foreach($fooditems as $fitems){
									$item_info = Fooditems::find($fitems->food_id);
									/*Menu time checking*/
									$item_time_valid = \SiteHelpers::getItemTimeValid($item_info->id);
									if($item_time_valid == 1){
										$values = array("user_id"=>$authid,"res_id"=>$rid,"food_id"=>$fitems->food_id,"food_item"=>$fitems->food_item,"price"=>$fitems->price,"quantity"=>$fitems->quantity,"cookie_id"=>$cart_cookie_id,"tax"=>$stax->service_tax);
										DB::table('abserve_user_cart')->insert($values);
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

	/*public function  getLang($lang='en') {
		Session::put('lang', $lang);
		return  Redirect::back();
	}*/

	public function  getSkin($skin='abserve') {
		Session::put('themes', $skin);
		return  Redirect::back();
	}		

	public  function  postContact( Request $request) {
		$this->beforeFilter('csrf', array('on'=>'post'));
		$rules = array(
				'name'		=>'required',
				'subject'	=>'required',
                'sender'	=>'required|email',
				'message'	=>'required|min:20',
		);
		$validator = Validator::make(Input::all(), $rules);	
		if ($validator->passes()) {
			
			$data = array('name'=>$request->input('name'),'sender'=>$request->input('sender'),'subject'=>$request->input('subject'),'notes'=>$request->input('message')); 
			$message = view('emails.contact', $data); 		
			
			$to 		= 	CNF_EMAIL;
			$subject 	= $request->input('subject');
			$headers  	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers 	.= 'From: '.$request->input('name').' <'.$request->input('sender').'>' . "\r\n";
				mail($to, $subject, $message, $headers);			

			return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('success','Thank You , Your message has been sent !'));	
				
		} else {
			return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('error','The following errors occurred'))
			->withErrors($validator)->withInput();
		}		
	}

	public function Nearrest(Request $request) {
		if(isset($request->lat) && isset($request->lng) && $request->lng != '' && $request->lat != ''){
			$radius = 5;
			$whr	= "WHERE 1 ";
			$lat_lng = ", ( 6371 * acos( cos( radians(".$request->lat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$request->lng.") ) + sin( radians(".$request->lat.") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ";
			$select = '*'.$lat_lng;
			$data['offer'] = DB::select("SELECT ".$select." FROM abserve_restaurants ".$hav);
			//print_r($data['offer']);exit;
			$response['result']	= (string) view('pages.restaurants_result', $data);
			echo json_encode($response);
			exit;
		} else {
			exit;
		}
	}

	public function Nearrestplace(Request $request) {
		if(isset($request->lat) && isset($request->lng) && $request->lng != '' && $request->lat != ''){
			$radius = 5;
			$whr	= "WHERE 1 ";
			$lat_lng = ", ( 6371 * acos( cos( radians(".$request->lat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$request->lng.") ) + sin( radians(".$request->lat.") ) * sin( radians( latitude ) ) ) ) AS distance";
			$hav	= "HAVING distance <= ".$radius." ";
			$select = '*'.$lat_lng;
			$offer = DB::select("SELECT ".$select." FROM abserve_restaurants ".$hav);
			if(count($offer) > 0){
				echo "1";exit;
			}else{
				echo "Sorry! We don't serve at your location currently.";exit;
			}
			
			exit;
		} else {
			exit;
		}
	}

	public function  getCurrency(Request $request) {
		$cur = $request->name;
		Session::put('currency', $cur);
		$currency = DB::table('abserve_currency')->select('*')->where('currency_name',Session::get('currency'))->first();
	    Session::put('currency_symbol', $currency->symbol);
	    Session::put('currency_value', $currency->value);
		return Redirect::back();
	}

	public function  getLang(Request $request)
	{
		$lang_val = $request->name;
		if($lang_val != '')
			$lang = $lang_val;
		else
			$lang = 'en';
		\Session::put('lang', $lang);
		return  Redirect::back();
	}
}
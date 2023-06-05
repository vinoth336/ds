<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Fooditems;
use App\Models\Restaurant;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect , Session,Response; 


class FooditemsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'fooditems';
	static $per_page	= '30';

	public function __construct()
	{
		if(!\Auth::check()) return Redirect::to('');
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Fooditems();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'fooditems',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex1( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'desc');
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
		
		
		$results	= $this->model->getUserrestaurants(\Auth::id(),\Auth::user()->group_id);

		$this->data['tot_count']	= count($results);

		$this->data['rowData'] = $results;

		if($request->input('page', 1) != ''){
			$page = Input::get('page', 1) - 1;
		} else {
			$page = 1;
		}

		$currentPage	= $page;
		$pagedData		= array_slice($results, $currentPage * static::$per_page, static::$per_page);
		$results		= new Paginator($pagedData, count($results), static::$per_page);
		$results->setPath('rooms');

		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();	
		$this->data['layouts']		= 'layouts.app';

		// Render into template
		return view('fooditems.index_new',$this->data)->with('model',new Fooditems)->with('rmodel',new Restaurant);
	}

	public function getIndex( Request $request )
	{
     // print_r($request->session()->all()); exit;
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'name'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		//$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
		if((!is_null($request->input('search')))){
			$get_region = explode(":",$request->input('search'));
			$filter = " AND abserve_restaurants.region = '".$get_region[2]."' ";
		} else {
			$filter = "";
		}
		
		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query for admin and franchise
		  
		  
		$model = new Restaurant();
		$results = $model->getRows( $params );
		$this->data['rowData']		= $results['rows'];
		
		if(\Auth::user()->group_id == 3){
			$results['rows'] = \DB::select("SELECT `id` FROM `abserve_restaurants` WHERE `abserve_restaurants`.`partner_id` = ".\Auth::user()->id." ORDER BY `abserve_restaurants`.`id` DESC");
			$results['total'] = count($results['rows']);
			$this->data['rowData']		= $results['rows'];
			$this->data['regions'] 		= array();
		}
			
		if(session()->get('gid') == '1'){
			$this->data['regions'] = Region::all();
		}elseif(session()->get('gid') == '7'){
			$this->data['regions'] = Region::where('id',session()->get('rid'))->get();
		}
		 
			
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('fooditems');
	
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
		$this->data['access']	= $this->access;
		// Detail from master if any
		
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
		// Render into template
		return view('fooditems.index',$this->data)->with('model',new Fooditems)->with('rmodel',new Restaurant);
	}	
	
	public function getImport(Request $request )
	{
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'desc');
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
		return view('fooditems.import',$this->data)->with('model',new Fooditems)->with('rmodel',new Restaurant);	
		
	}
	
	function postSaveimport(Request $request){
		
		
		if($_FILES['upload']['name'] == ''){
			return Redirect::to('fooditems/import')->with('messagetext','Please select a csv file')->with('msgstatus','error');
		}
		
		$ext = explode(".",$_FILES['upload']['name']);
		
		if($ext[1] == "csv"){
			
			$filename = base_path().'/uploads/imports/'. $_FILES["upload"]['name'];
			move_uploaded_file($_FILES['upload']['tmp_name'], $filename);			
		    $customerArr = $this->csvToArray($filename);
			
			foreach($customerArr as $items){
			  
			  if($items['restaurant_name'] !=''){
							
				$resInfo = \DB::table('abserve_restaurants')->select('id')->where('name', $items['restaurant_name'])->first();	
				unset($items['restaurant_name']);				
				$entryid = \Session::get('uid');
								
				$main_cat_name = \DB::table('abserve_food_categories')->select('id')->where('cat_name', $items['main_cat'])->first();
				
				if(isset($items['sub_cat']) == ''){
				
					if(isset($main_cat_name->id)){
						$sub_cat = \DB::table('abserve_food_categories')->select('id')->where('cat_name', $items['sub_cat'])->where('root_id', $main_cat_name->id)->first();
					}					
					$sub_cat_id = $sub_cat->id;
				
				} else{//checking the sub cate
								
					if($items['main_cat_new'] !=""){
						$maincatname = \DB::table('abserve_food_categories')->select('id')->where('cat_name', $items['main_cat_new'])->first();
						$main_catid  = $maincatname->id;					
						$sub_cat_id  = $maincatname->id;						
					} else {
						$main_catid  = $main_cat_name->id;					
						$sub_cat_id  = $main_cat_name->id;
					}
				}
					
				$main_cat_id = $main_cat_name->id;
				
				trim($items['food_item']);
				if(trim($items['status']) == "Non Veg"){
					$status = "Non_veg";
				} else {
					$status = trim($items['status']);
				}
				
				if($items['available_from'] !=''){
					$available_from = date("H:i:s", strtotime(trim($items['available_from'])));
					$available_to = date("H:i:s", strtotime(trim($items['available_to'])));
				} else {
					$available_from = "";
					$available_to = "";
				}
				
				if($items['breakfast_available_from'] !=''){
					$breakfast_available_from = date("H:i:s", strtotime(trim($items['breakfast_available_from'])));
					$breakfast_available_to = date("H:i:s", strtotime(trim($items['breakfast_available_to'])));
				} else {
					$breakfast_available_from = "";
					$breakfast_available_to = "";
				}
				
				if($items['lunch_available_from'] !=''){
					$lunch_available_from = date("H:i:s", strtotime(trim($items['lunch_available_from'])));
					$lunch_available_to = date("H:i:s", strtotime(trim($items['lunch_available_to'])));
				} else {
					$lunch_available_from = "";
					$lunch_available_to = "";
				}
				
				if($items['dinner_available_from'] !=''){
					$dinner_available_from = date("H:i:s", strtotime(trim($items['dinner_available_from'])));
					$dinner_available_to = date("H:i:s", strtotime(trim($items['dinner_available_to'])));
				} else {
					$dinner_available_from = "";
					$dinner_available_to = "";
				}
				$recommended = trim($items['recommended']);
				if($recommended == ''){
					$recommended = "0";
				}
				
				if($items['food_item_new'] !=""){
					$food_item = $items['food_item_new'];
				} else {
					$food_item = $items['food_item'];
				}
				
				if($items['special_from'] !=''){
					$special_from = date("Y-m-d", strtotime(trim($items['special_from'])));
					$special_to = date("Y-m-d", strtotime(trim($items['special_to'])));
				} else {
					$special_from = "";
					$special_to = "";
				}
				
				$insert = [
					"restaurant_id" => $resInfo->id,
					"food_item" => trim($food_item),
					"description" => trim($items['description']),
					"price" => trim($items['price']),
					"special_price" => trim($items['special_price']),
					"special_from" => $special_from,
					"special_to" => $special_to,
					"packaging_charge" => trim($items['packaging_charge']),
					"max_packaging_charge" => trim($items['max_packaging_charge']),
					"status" => $status,
					"available_from" => $available_from,
					"available_to" => $available_to,
					"breakfast_available_from" => $breakfast_available_from,
					"breakfast_available_to" => $breakfast_available_to,
					"lunch_available_from" => $lunch_available_from,
					"lunch_available_to" => $lunch_available_to,
					"dinner_available_from" => $dinner_available_from,
					"dinner_available_to" => $dinner_available_to,
					"available_days" => trim($items['available_days']),
					"item_status" => trim($items['item_status']),
					"image" => trim($items['image']),
					"main_cat" => $main_catid,
					"sub_cat" => $sub_cat_id,
					"recommended" => $recommended,
					"ingredients" => trim($items['ingredients']),
					"cat_order_display" => trim($items['cat_order']),
					"display_order" => trim($items['product_order']),
					"entry_by" => $entryid		
				];
				
				$foodInfo = \DB::table('abserve_hotel_items')->select('id')->where('restaurant_id', $resInfo->id)->where('food_item', trim($items['food_item']))->where('main_cat', $main_cat_id)->first();
				if($foodInfo->id == ''){
					$ins = \DB::table('abserve_hotel_items')->insert($insert);
				} else {
					$update = \DB::table('abserve_hotel_items')->where('id', $foodInfo->id)->update($insert);
				}
				
			  }
			
			}
			return Redirect::to('fooditems/import')->with('messagetext','Food Items imported successfully')->with('msgstatus','success');
				
		} else {
			return Redirect::to('fooditems/import')->with('messagetext','Incompatible File Format! System expects .csv file to upload')->with('msgstatus','error');
		}		
		
	}
	
	function postSavecatimport(Request $request){
		
		if($_FILES['catupload']['name'] == ''){
			return Redirect::to('fooditems/import')->with('messagetext','Please select a csv file')->with('msgstatus','error');
		}
		
		$ext = explode(".",$_FILES['catupload']['name']);
		
		if($ext[1] == "csv" ){
			
			$filename = base_path().'/uploads/imports/'. $_FILES["catupload"]['name'];
			move_uploaded_file($_FILES['catupload']['tmp_name'], $filename);			
		    $customerArr = $this->csvToArray($filename);
			
			foreach($customerArr as $items){						
				$entryid = \Session::get('uid');
			
				$insert = [
					"cat_name" => trim($items['cat_name']),
					"root_id" => trim($items['root_id']),
					"entry_by" => $entryid		
				];
				
				$catInfo = \DB::table('abserve_food_categories')->select('id')->where('cat_name', trim($items['cat_name']))->first();
				
				if($catInfo->id == ''){
					$ins = \DB::table('abserve_food_categories')->insert($insert);
				}
			
			}

			return Redirect::to('fooditems/import')->with('messagetext','Categories imported successfully')->with('msgstatus','success');
		} else {
			return Redirect::to('fooditems/import')->with('messagetext','Incompatible File Format! System expects .csv file to upload')->with('msgstatus','error');
		}
			
	}	

	function getUpdate(Request $request, $id = null)
	{
		
		if($id =='')
		{
			if($this->access['is_add'] ==0 ){
				return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
			} else {
				$this->data['sresId'] = \Session::get('resID');
			}
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		$row = $this->model->find($id);
		if($row)
		{
			$main_cat = \DB::table('abserve_food_categories')->select('id','cat_name')->where('root_id','=',0)->get();
			$subcategories 	=	\DB::select( "SELECT * FROM abserve_food_categories WHERE `root_id` = ".$row->main_cat);
			$toppings = \DB::table('toppings')->select('*')->where('id','!=','NULL')->groupBy('category')->get();
			$this->data['main_cat'] 		=	$main_cat;
			$this->data['subcategories'] 	=	$subcategories;
			$this->data['toppings'] 		=	$toppings;
			$this->data['row'] 				=  	$row;
		} else {
			$main_cat = \DB::table('abserve_food_categories')->select('id','cat_name')->where('root_id','=',0)->get();
			$toppings = \DB::table('toppings')->select('*')->where('id','!=','NULL')->groupBy('category')->get();
			$this->data['main_cat'] 		=	$main_cat;
			$this->data['row'] 				= 	$this->model->getColumnTable('abserve_hotel_items');
			$this->data['toppings'] 		=	$toppings;
		}
		$this->data['partner_hotels'] =\DB::select( "SELECT * FROM abserve_restaurants WHERE `partner_id` = ".\Auth::user()->id);


		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		if($id =='')
			$this->data['row']['restaurant_id'] = $this->data['sresId'];
		return view('fooditems.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_hotel_items'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('fooditems.view',$this->data);	
	}	

	public function getPartnerid($res_id){
		return \DB::table('abserve_restaurants')->select('partner_id')->where('id',$res_id)->get();
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

	public function appapimethod( $value = ''){   

		$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();

		return $appapi[0];
	}

	function postSave( Request $request)
	{
		
		//print_r($request->all());  exit;
		if(!(isset($_POST['sub_cat'])) || $_POST['sub_cat'] == ''){
			$_POST['sub_cat'] = $_POST['main_cat'];
		}
		
		if(isset($_POST['restaurant_id']) && $_POST['restaurant_id'] != ''){
			$pid = $this->getPartnerid($_POST['restaurant_id']);
			if(!empty($pid)){
				if(!empty($pid[0])){
					$_POST['entry_by'] = $pid[0]->partner_id;
				} else {
					$_POST['entry_by'] = 1;
				}
			}
		} else {
			$_POST['entry_by'] = 1;
		}

		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_fooditems',$request->input('id'),$request->input('restaurant_id'));
			// $id = $this->model->insertRow($data , $request->input('id'));
				//print_r($data['available_days']);
				//echo $data['available_days']; exit;
			if($request->available_from !=''){
				$available_from = strtotime($request->available_from);
				$available_to = strtotime($request->available_to);
				$data['available_from'] = date("H:i:s",$available_from);
				$data['available_to'] = date("H:i:s",$available_to);
			} else {
				$data['available_from'] = '';
				$data['available_to'] = '';
			}
			if($request->breakfast_available_from !=''){
				$breakfast_available_from = strtotime($request->breakfast_available_from);
				$breakfast_available_to = strtotime($request->breakfast_available_to);
				$data['breakfast_available_from'] = date("H:i:s",$breakfast_available_from);
				$data['breakfast_available_to'] = date("H:i:s",$breakfast_available_to);
			} else {
				$data['breakfast_available_from'] = '';
				$data['breakfast_available_to'] = '';
			}
			if($request->lunch_available_from !=''){
				$lunch_available_from = strtotime($request->lunch_available_from);
				$lunch_available_to = strtotime($request->lunch_available_to);				
				$data['lunch_available_from'] = date("H:i:s",$lunch_available_from);
				$data['lunch_available_to'] = date("H:i:s",$lunch_available_to);
			} else {
				$data['lunch_available_from'] = '';
				$data['lunch_available_to'] = '';
			}
			if($request->dinner_available_from !=''){
				$dinner_available_from = strtotime($request->dinner_available_from);
				$dinner_available_to = strtotime($request->dinner_available_to);
				$data['dinner_available_from'] = date("H:i:s",$dinner_available_from);
				$data['dinner_available_to'] = date("H:i:s",$dinner_available_to);
			} else {
				$data['dinner_available_from'] = '';
				$data['dinner_available_to'] = '';
			}
			
			$data['special_price'] = $request->special_price;
			
			if($request->special_from !=''){
			 $data['special_from'] = $request->special_from;
			}else {
			 $data['special_from'] ='';	
			}
			
			if($request->special_to !=''){
			 $data['special_to'] = $request->special_to;
			}else {
			 $data['special_to'] ='';	
			}
			
			if($request->input('id') == ''){
				 $query = \DB::table('abserve_hotel_items')->where('restaurant_id', $request->restaurant_id)->where('main_cat', $request->main_cat)->where('display_order','!=','0')->get();
				 $display = count($query);  
			if($display > 0){
				 $data['display_order'] = $display+1;	
			}else{
				 $data['display_order'] = 0;		
			}
			
		
			$query1 = \DB::table('abserve_hotel_items')->where('restaurant_id', $request->restaurant_id)->where('main_cat', $request->main_cat)->where('cat_order_display','!=','0')->get();
			$display = count($query1);
			if($display > 0){
			 $query2 = $query1[0];   
		    $data['cat_order_display'] = $query2->cat_order_display;
			}else{
			$data['cat_order_display'] = 0;	
			}
			}
			// $data['special_to'] = $request->special_to;
			 
			if($request->input('id') == '')
			{
				$res_id = $request->input('restaurant_id');

				$max_id = \DB::select("SELECT Max(id) AS ids FROM abserve_hotel_items");

				$data['id'] = $max_id[0]->ids +1;
                

				$id = \DB::table('abserve_hotel_items')->insertGetId($data);
				// $id = $this->model->insertRow($data , $request->input('id'));
				if($_POST['topping_id'] !=''){
					foreach($_POST['topping_id'] as $topping_id){
						
						$topping_price = $_POST['topping_price_'.$topping_id];
						$topping_cat = $_POST['topping_cat_'.$topping_id];
						$value[] = array("prod_id" => $data['id'],"topping_id" => $topping_id,"topping_category"=>$topping_cat,"topping_price"=>$topping_price);
					}
					\DB::table('product_toppings')->insert($value);
				}
				
				if(!is_null(Input::file('image')))
				{
					// $destinationPath = '.'. $f['option']['path_to_upload'];
					$dir	= 'uploads/res_items/'.$res_id.'/';
					$directory	= base_path().'/uploads/res_items/'.$res_id.'/';
					if (!(\File::exists($directory))) {
						$destinationPath = \File::makeDirectory($directory, 0777, true);
					}
					$destinationPath = $directory;
					// foreach($_FILES['image']['tmp_name'] as $key => $tmp_name ){
					 	$org_name	= $_FILES['image']['name']/*[$key]*/;
						// $exp		= explode(".",$org_name);
						$ext		= pathinfo($org_name, PATHINFO_EXTENSION);
					 	$file_name	= time()."-".rand(10,100)./*.$key.*/'.'.$ext;
						$file_tmp	= $_FILES['image']['tmp_name']/*[$key]*/;
						if($file_name !=''){
							$upload = move_uploaded_file($file_tmp,$destinationPath.$file_name);
							$files .= /*$dir.*/$file_name.',';
						}
					// }
					if($files !='')	$files = substr($files,0,strlen($files)-1);
					$data['image'] .= $files;
					$data['customize'] = $request->customize;
					\DB::table('abserve_hotel_items')->where('id',$id)->update($data);
				}
			} else {
				$id = $request->input('id');
				$data['customize'] = $request->customize;
				
				/*$food_item = \DB::table('abserve_hotel_items')->where('id','=',$id)->first();
				
				if($food_item->item_status != $data['item_status']){
					$customers = \DB::table('tb_users')->select('*')->where('group_id','=',4)->where('mobile_token','!=','')->where('ios_flag','=',1)->get();
				
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
					}
				}*/
				
				\DB::table('abserve_hotel_items')->where('id',$id)->update($data);
				
				
				if($_POST['topping_id'] !=''){
					$_toppings = \DB::table('product_toppings')->select('*')->where('prod_id','=',$id)->get();
					
					$_topping_id = array();
					foreach($_toppings as $key => $_topping){
						$_topping_id[] = $_topping->topping_id;
					}
					
					if(!empty($_topping_id)){
						$delete_toppings = array_diff($_topping_id,$_POST['topping_id']);
										
						foreach($delete_toppings as $delete_topping){
							\DB::table('product_toppings')->where("prod_id",'=',$id)->where('topping_id', '=', $delete_topping)->delete();
						}
					}
				
					foreach($_POST['topping_id'] as $topping_id){
						
						$topping_price = $_POST['topping_price_'.$topping_id];
						$topping_cat = $_POST['topping_cat_'.$topping_id];
						if(in_array("$topping_id", $_topping_id)){
							
							$value_update = array("topping_price"=>$topping_price,"topping_category"=>$topping_cat);
							\DB::table('product_toppings')->where("prod_id",'=',$id)->where('topping_id', '=', $topping_id)->update($value_update);
							
						} else {
							
							$value_insert[] = array("prod_id"=>$id,"topping_id"=>$topping_id,"topping_category"=>$topping_cat,"topping_price"=>$topping_price);
							
						}
					}
					if(count($value_insert)>0){
						\DB::table('product_toppings')->insert($value_insert);
					}
				}
				
			}

			if(!is_null($request->input('apply')))
			{
				$return = 'fooditems/update';
			} else {
				$res_id = $request->input('restaurant_id');
				$return = 'fooditems/resdatas/'.$res_id.'?return='.self::returnUrl();
				//$return = 'fooditems/update';
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

			return Redirect::to('fooditems/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}

	public function getFooddelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if($request->segment(3) != '')
		{
			$id 	= $request->segment(3);
			$res_id = $request->segment(4);
			//echo '<pre>';print_r($id);exit;
			$res_exists  = $this->single_exists('abserve_restaurants','id',$res_id);
			if($res_exists){
				if(\Auth::user()->group_id != 1){
						$owner_check = $this->double_exists('abserve_restaurants','id,partner_id',$res_id.",".\Auth::id());
					} else {
						$owner_check = 1;
				    }
				

				if($owner_check){
					$this->model->destroy($id);					
					\SiteHelpers::auditTrail( $request , "ID : ".($id)."  , Has Been Removed Successfull");
					// redirect
					return Redirect::to('fooditems/resdatas/'.$res_id)->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');
				} else {
					return Redirect::to('fooditems/resdatas/'.$res_id)->with('messagetext',"Sorry!.. You're not allow to access this Restaurant")->with('msgstatus','error');
				}
			} else {
				return Redirect::to('fooditems/resdatas/'.$res_id)->with('messagetext','No Such Restaurant Found')->with('msgstatus','error');
			}
		} else {
			return Redirect::to('fooditems/resdatas/'.$res_id)
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
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
			return Redirect::to('fooditems')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('fooditems')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}
	}			

	public function getSubcat(Request $request) 
	{

		$subcat 	=	\DB::select( "SELECT * FROM abserve_food_categories WHERE `root_id` = ".$_REQUEST['cat_id']);
		echo '<option value="">-- Please Select --</option>';
  		foreach($subcat as $sub)
  		{
		echo '<option value="'.$sub->id.'">'.$sub->cat_name.'</option>';
		}
		exit;
	}

	public function single_exists($table='',$fields='',$value=''){
		return \DB::table($table)->where($fields,'=',$value)->exists();
	}

	public function double_exists($table='',$fields='',$value=''){
		$columns 	= explode(',', $fields);
		$data 		= explode(',', $value);
		return \DB::table($table)->where($columns[0],'=',$data[0])->where($columns[1],'=',$data[1])->exists();
	}

	public function getResdatas(Request $request, $res_id = null){

		if($res_id != ''){
			$res_exists  = $this->single_exists('abserve_restaurants','id',$res_id);
			if($res_exists){
				if(\Auth::user()->group_id != 1){
					if(\Auth::user()->group_id != 7){
						$owner_check = $this->double_exists('abserve_restaurants','id,partner_id',$res_id.",".\Auth::id());
					} else {
						$owner_check = 1;
				    }
				} else {
					$owner_check = 1;
				}
				if($owner_check){
					$this->data['res_id'] 		= $res_id;
					Session::put('resID',$res_id);
					$restaurant =  \DB::table('abserve_restaurants')->select('*')->where('id', '=', $res_id)->first();
					$this->data['restaurant'] 	= $restaurant;
					$this->data['categories'] 	= $this->rescategories($res_id);
					$this->data['recomm_items'] = $this->getRecommItemdetails($res_id);
					$this->data['hotel_items'] 	= $this->getItemdetails($res_id);
					$this->data['hotel_item_single'] 	= $this->getItembysinglecat($res_id);

					return view('fooditems.food_items',$this->data);	
				} else {
					return Redirect::to('fooditems')->with('messagetext',"Sorry!.. You're not allow to access this Restaurant")->with('msgstatus','error');
				}
			} else {
				return Redirect::to('fooditems')->with('messagetext','No Such Restaurant Found')->with('msgstatus','error');
			}
		} else {
			return Redirect::to('fooditems')->with('messagetext','No Such Restaurant')->with('msgstatus','error');	
		}
	}

	public function rescategories($res_id=''){
		$categories = array();
		$categories = \DB::select("SELECT DISTINCT(`hi`.`main_cat`) as id,`fc`.`cat_name` as name FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `fc` ON `hi`.`main_cat` = `fc`.`id` WHERE `restaurant_id` = ".$res_id);

		$recomend = \DB::select("SELECT DISTINCT(`recommended`) AS recomnd FROM `abserve_hotel_items` WHERE `restaurant_id` = ".$res_id);

		foreach ($recomend as $key => $val) {
			$rend[] = get_object_vars($val);
		}
		if( $rend > 0){
			$result		= call_user_func_array('array_merge_recursive', $rend);
			$rec_val	= array("id"=>0,"name"=>"Recommended");

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
		return json_decode(json_encode($categories));
	}

	public function getRecommItemdetails($res_id){

		$recommended = [];
		$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`main_cat`,`hi`.`recommended`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$res_id." and `hi`.`recommended` =1 ORDER BY `hi`.`display_order` ASC";

		$recommended = \DB::select($qwert);
		return $recommended;
	}

	public function getItemdetails($res_id){

		$hotel_item = [];
		$qwert = "SELECT DISTINCT(`hi`.`id`),`hi`.`main_cat`,`hi`.`recommended`,`food_item` as item_name,`description`,`price`,`status`,`available_from`,`available_to`,`item_status`,`hc`.`cat_name` as Sub_cat,`hm`.`cat_name` as Main_cat,`hi`.`image` FROM `abserve_hotel_items` AS `hi` JOIN `abserve_food_categories` AS `hc` ON `hc`.`id` = `hi`.`sub_cat` JOIN `abserve_food_categories` AS `hm` ON `hm`.`id` = `hi`.`main_cat` JOIN `abserve_food_categories` AS `c` ON `c`.`id` = `hi`.`main_cat` WHERE `hi`.`restaurant_id` = ".$res_id." and `hi`.`recommended` = '0' group by `hi`.`main_cat`,`hi`.`sub_cat`,`hi`.`id` ORDER BY `hi`.`cat_order_display` ASC";

		$hotel_item = \DB::select($qwert);
		return $hotel_item;
	}
	
	
	
	public function getItembysinglecat($res_id){

		$hotel_item_single = [];
		
		$qwert = "SELECT `hi`.`main_cat`,`hi`.`sub_cat`,`afc`.`cat_name` from `abserve_hotel_items` as `hi` JOIN `abserve_food_categories` as `afc`  ON `hi`.`main_cat`=`afc`.`id`  WHERE `hi`.`restaurant_id` = ".$res_id." group by `hi`.`main_cat` ORDER BY `hi`.`cat_order_display` ASC";
		
		$hotel_item_single = \DB::select($qwert);		
		return $hotel_item_single;
	}

	//Override validatepost function
	function validatePost(  $table ,$id = '',$res_id='')
	{	
		$request	= new Request;	
		$str		= $this->info['config']['forms'];
		$data = array();
		foreach($str as $f){
			$field = $f['field'];
			if($f['view'] ==1) 
			{
				if(isset($_FILES[$field]))
				{
					if($_FILES[$field]['name'][0] == '')
						continue;
				}

				if( is_array($_POST[$field]) )
				{
					$multival = (is_array($_POST[$field]) ? implode(",",$_POST[$field]) :  ($_POST[$field] == '' ? '' : $_POST[$field]) ); 
					$data[$field] = $multival;
				} else {

					if($f['type'] =='textarea_editor' || $f['type'] =='textarea')
					{
						$content = (isset($_POST[$field]) ? $_POST[$field] : '');
						$data[$field] = $content;
					} else {

						if(isset($_POST[$field]))
						{
							$data[$field] = $_POST[$field];				
						}

						if($id != ''){
							// if post is file or image
							if(isset($_POST['curr'.$field])){
								$curr =  '';
								for($i=0; $i<count($_POST['curr'.$field]);$i++)
								{
									$files .= $_POST['curr'.$field][$i].',';
								}
								$data[$field] .= $files;
							}

							if(!is_null(Input::file($field)))
							{
								// $destinationPath = '.'. $f['option']['path_to_upload'];
								$dir	= 'uploads/res_items/'.$res_id.'/';
								$directory	= base_path().'/uploads/res_items/'.$res_id.'/';
								if (!(\File::exists($directory))) {
									$destinationPath = \File::makeDirectory($directory, 0777, true);
								}
								$destinationPath = $directory;
								// foreach($_FILES[$field]['tmp_name'] as $key => $tmp_name ){
								 	$org_name	= $_FILES[$field]['name']/*[$key]*/;
									// $exp		= explode(".",$org_name);
									$ext		= pathinfo($org_name, PATHINFO_EXTENSION);
								 	$file_name	= time()."-".rand(10,100)./*.$key.*/'.'.$ext;
									$file_tmp	= $_FILES[$field]['tmp_name']/*[$key]*/;
									if($file_name !=''){
										$upload = move_uploaded_file($file_tmp,$destinationPath.$file_name);
										$files .= /*$dir.*/$file_name.',';
									}
								// }
								if($files !='')	$files = substr($files,0,strlen($files)-1);
								$data[$field] .= $files;
							}
							if($field == 'image'){
								$data['image'] = $files;
							}
						}

						// if post is checkbox	
						if($f['type'] =='checkbox')
						{
							if(!is_null($_POST[$field]))
							{
								$data[$field] = implode(",",$_POST[$field]);
							}	
						}

						// if post is date						
						if($f['type'] =='date')
						{
							$data[$field] = date("Y-m-d",strtotime($request->input($field)));
						}

						// if post is seelct multiple						
						if($f['type'] =='select')
						{
							if( isset($f['option']['select_multiple']) &&  $f['option']['select_multiple'] ==1 )
							{
								$multival = (is_array($_POST[$field]) ? implode(",",$_POST[$field]) :  ($_POST[$field] == '' ? '' : $_POST[$field]) ); 
								$data[$field] = $multival;
							} else {
								$data[$field] = $_POST[$field];
							}	
						}									
						
					}	
				} 						

			}	
		}

		$global	= (isset($this->access['is_global']) ? $this->access['is_global'] : 0 );
		
		if($global == 0 )
			$data['entry_by'] = \Session::get('uid');

		return $data;
	}
	
	public function getToppingprice(Request $request) 
	{
  		foreach($_REQUEST['topping_category'] as $topping_category){
			$toppings = \DB::table('toppings')->select('*')->where('category','=',$topping_category)->get();
			$input .= '<div class="col-md-12"><label class=" control-label col-md-8 text-left" for="'.$topping_category.'">'.$topping_category.'</label></div>';
			foreach($toppings as $topping){
				$topping_label = $topping->label;
				$topping_ids = $topping->id;
				
				if($_REQUEST['prod_id'] !=''){
					$toppings = \DB::table('product_toppings')->select('*')->where('prod_id','=',$_REQUEST['prod_id'])->where('topping_id','=',$topping_ids)->get();
				}
				if($topping_ids ==$toppings[0]->topping_id){ $checked = 'checked'; } else { $checked = ''; }				
								
				$input .= '<div class="col-md-12"><label class=" control-label col-md-4 text-left" for="'.$topping_label.'"><input type="checkbox" name="topping_id[]" value="'.$topping_ids.'" ' . $checked . ' > '.$topping_label.'</label>';
				$input .= '<div class="col-md-6" style="padding-bottom:5px">';
				$input .= '<input type="text" name="topping_price_'.$topping_ids.'" id="topping_price_'.$topping_ids.'" value="'.$toppings[0]->topping_price.'"  >';
				$input .= '<input type="hidden" name="topping_cat_'.$topping_ids.'" id="topping_cat_'.$topping_ids.'" value="'.$topping->category.'"  >';
				$input .=  '</div><div class="col-md-2"></div></div>';
			}
		}
		echo $input;
		//exit;
	}
	
	function csvToArray($filename = '', $delimiter = ',')
	{
		if (!file_exists($filename) || !is_readable($filename))
			return false;
	
		$header = null;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
			{
				if (!$header)
					$header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
	
		return $data;
	}
	
	
	
	
	/*
	
	public function postReorder(Request $request )
	{
		
		 $rcom = $request->res_id;
	
		 $display_order1 = $request->position; 
		 $rowgrp = explode(",",$display_order1);
		
		     $i=0;
          foreach($rowgrp as $disid){
	    
		  		$values['display_order'] = $i;  
		        $update = \DB::table('abserve_hotel_items')->where('id', $disid)->update($values);
	
	         $i++;	
	      }

   
	}
	*/

	public function postOrdercategory(Request $request )
	{
	
		$rcom = $request->res_id;
		$cat_display = $request->position; 
		$cat_order = explode(",",$cat_display);
		$i=1;
		
		foreach($cat_order as $cat_order1){
		  $values['cat_order_display'] = $i;  
		  $query = \DB::table('abserve_hotel_items')->where('restaurant_id', $rcom)->where('main_cat', $cat_order1)->update($values);
		  
		  $i++;
		}
	
	}


   public function postOrderproducts(Request $request )
	{
	
		$rid = $request->res_id;
		$main_cat = $request->main_cat;
		$pro_display = $request->position; 
		//print_r($main_cat);
		$product_order = explode(",",$pro_display);
		//print_r($product_order);
		$i=1;
		
		foreach($product_order as $product_order1){
		  $values['display_order'] = $i;  
		  $query = \DB::table('abserve_hotel_items')->where('restaurant_id', $rid)->where('main_cat', $main_cat)->where('id', $product_order1)->update($values);
		  
		  $i++;
		}
	
	}
	
	
	
	public function getFoodtruncate( Request $request)
	{
		$rid =  $request->resid; 
		
		  $query = 	\DB::table('abserve_hotel_items')->where("restaurant_id",'=',$rid)->delete();
		
	return Redirect::to('fooditems/resdatas/'.$rid)->with('messagetext', 'Food Items Truncated Successfully')->with('msgstatus','success');
		}



}
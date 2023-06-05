<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Restaurant;
use App\Models\Fooditems;
use App\Models\Ondeliveryorder;
use App\Models\Customerorder;
use App\Models\Paymentorder;
use App\Models\Usercart;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class RestaurantController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'restaurant';
	static $per_page	= '30';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Restaurant();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'restaurant',
			'return'	=> self::returnUrl()
			
		);
		
	}

	/*public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

	
		// Get Query 
		$results = $this->model->getlogo();	
		print_r($results)	;
		$this->data['rowData'] = $results;
		$this->data['group_id'] = \Auth::user()->group_id;
		
		return view('restaurant.index_new',$this->data);
	}*/	

	public function getIndex( Request $request )
	{
		
	   // print_r($request->session()->all()); exit;
		//print_r(session()->get('eid')); // exit;
		
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'name'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
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
		
	  
		// Get Query for admin and franchise
		//if(session()->get('gid') == '1'){
			//echo  $params['limit'];			
			$results = $this->model->getRows( $params );
		    $this->data['rowData']		= $results['rows'];
		//}elseif(session()->get('gid') == '7'){
			//echo  $params['limit'];
		   // $results['rows'] = \DB::select("SELECT * FROM `abserve_restaurants` WHERE `region`='".session()->get('rkey')."'");
		//	$results['total'] = count($results['rows']); 
			//$this->data['rowData']	= $results['rows']; 
				
	   // }
	   
		if(session()->get('gid') == '1'){
			 $this->data['regions'] = Region::all();
		}elseif(session()->get('gid') == '7'){
			$this->data['regions'] = Region::where('id',session()->get('rid'))->get();
		}
		
		//print_r($results);
		// Build pagination setting
	    $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('restaurant');
		
	
		
		
		// Build Pagination 
		$this->data['pagination']	= $pagination;
		
		//echo count($pagination);  
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
		return view('restaurant.index',$this->data)->with('model',new Restaurant);
	}

	public function postUploadphoto( Request $request ){
		// echo "<pre>";print_r($_FILES);exit();
		// get the tmp url
		$photo_src	= $_FILES['photo']['tmp_name'];
        $exp		= explode(".",$_FILES['photo']['name']); 
		// test if the photo realy exists
		if (is_file($photo_src)) {
			// photo path in our example
			$name = time().'-'.rand(1000,100).'.'.$exp[1];
			$photo_dest = public_path().'/temp/'.$name;
			$photo_url	= \URL::to('/temp/'.$name);
			// copy the photo from the tmp path to our path
			copy($photo_src, $photo_dest);
			// call the show_popup_crop function in JavaScript to display the crop popup
			echo '<script type="text/javascript">window.top.window.show_popup_crop("'.$photo_url.'")</script>';
		}
	}

	public function postCropimage( Request $request ){
		// echo "<pre>";print_r($_REQUEST);exit();
		$filpath	= $_POST['photo_url'];
		$explde = explode('/', $filpath);
		$src	= 'temp/'.end($explde);
		// $src		= 'uploads/restaurants/'. $file_name;
		$quality	= 90;
		// jpg, png, gif or bmp?
		$exploded	= explode('.',$src);
		$ext		= $exploded[count($exploded) - 1]; 

		if (preg_match('/jpg|jpeg/i',$ext))
			$img	= imagecreatefromjpeg($src);
		else if (preg_match('/png/i',$ext))
			$img	= imagecreatefrompng($src);
		else if (preg_match('/gif/i',$ext))
			$img	= imagecreatefromgif($src);
		else if (preg_match('/bmp/i',$ext))
			$img	= imagecreatefrombmp($src);
		else
			return 0;

		$dest = ImageCreateTrueColor($_POST['targ_w'],$_POST['targ_h']);

		imagecopyresampled($dest, $img, 0, 0, $_POST['x'],
		    $_POST['y'], $_POST['targ_w'], $_POST['targ_h'],
		    $_POST['w'], $_POST['h']);
		imagejpeg($dest, $src, $quality);
		$image	=	$file_name;
		echo '<img src="'.$filpath.'?'.time().'"><input type="hidden" name="image_src" value="'.$filpath.'">';exit;
	}

	function getUpdate(Request $request, $id = null){
		
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}


        if($id !='')
		{  
			if(\Auth::user()->group_id !="1"){
				if(\Auth::user()->group_id !="7"){
				$results = \DB::select("SELECT * FROM `abserve_restaurants` WHERE `id` = '".$id."' AND `partner_id` = '".\Auth::user()->id."'");
				if (empty($results)){
					return Redirect::to('restaurant');
				} else {
					if($this->access['is_edit'] ==0 )
						return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
				}
				}} else {
				if($this->access['is_edit'] ==0 )
					return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
			}
		}				
				
		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$partners = \DB::select("SELECT `id`,`username` FROM `tb_users` WHERE `group_id` = 3");
			$this->data['partners'] = $partners;
           
			$this->data['row'] = $this->model->getColumnTable('abserve_restaurants'); 
		}
		
		$cuisines = \DB::table('abserve_food_cuisines')->select('*')->where('id','!=','NULL')->get();
		$this->data['cuisines'] 	=	$cuisines;
		
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('restaurant.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_restaurants'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('restaurant.view',$this->data);	
	}	

	public function getLatlan( $address = ''){
		$formattedAddr = str_replace(' ','+',$address);
		//Send request and receive json data by address
		$geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false'); 
		$output = json_decode($geocodeFromAddr);
		//Get latitude and longitute from json data
		$data['latitude']  = $output->results[0]->geometry->location->lat; 
		$data['longitude'] = $output->results[0]->geometry->location->lng;
		//Return latitude and longitude of the given address
		return $data;
	}

	function postSave( Request $request)
	{  
	
	//print_r($request->all());  exit;
		if (isset($_REQUEST['call_handling']) && $_REQUEST['call_handling'] != '') {
			$_REQUEST['call_handling'] = '1';
		} else {
			$_REQUEST['call_handling'] = '0';
		}
         
        $rules = $this->validateForm();

		/*if($_REQUEST['location'] != ''){
			$data = $this->getLatlan($_REQUEST['location']);
		}*/
		$_REQUEST['cuisine']	= implode(',', $_REQUEST['cuisine']);

		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			if($_REQUEST['latitude'] == '' || $_REQUEST['longitude'] == ''){
				$validator->getMessageBag()->add('location', 'Enter valid Address!');
				return Redirect::to('restaurant/update/'.$_REQUEST['id'])->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
				->withErrors($validator)->withInput();
			} else {
				/*if(!is_null(Input::file('logo'))){

					$quality = 90;

					$file = Input::file('logo');
					// $file_name = $file->getClientOriginalName();
					$extension 		= $file->getClientOriginalExtension();
					$rand 			= rand(1000,100);
					$file_name 		= strtotime(date('H:i:s')).'-'.$rand.'.'.$extension;

					$file->move('uploads/restaurants', $file_name);
					$src  = 'uploads/restaurants/'. $file_name;

					// jpg, png, gif or bmp?
					$exploded	= explode('.',$file_name);
					$ext		= $exploded[count($exploded) - 1]; 

					if (preg_match('/jpg|jpeg/i',$ext))
						$img	= imagecreatefromjpeg($src);
					else if (preg_match('/png/i',$ext))
						$img	= imagecreatefrompng($src);
					else if (preg_match('/gif/i',$ext))
						$img	= imagecreatefromgif($src);
					else if (preg_match('/bmp/i',$ext))
						$img	= imagecreatefrombmp($src);
					else
						return 0;

					// // quality is a value from 0 (worst) to 100 (best)
					// imagejpeg($imageTmp, $outputImage, $quality);
					// imagedestroy($imageTmp);

					// $src  = 'uploads/restaurants/'. $file_name;
					// $img  = imagecreatefromjpeg($src);
					$dest = ImageCreateTrueColor(Input::get('w'),
					    Input::get('h'));

					imagecopyresampled($dest, $img, 0, 0, Input::get('x'),
					    Input::get('y'), Input::get('w'), Input::get('h'),
					    Input::get('w'), Input::get('h'));
					imagejpeg($dest, $src, $quality);
					$image	=	$file_name;
				} else {
					$image = '';
				}*/
		   
	
		
				if($_REQUEST['vat'] == ''){
					$_REQUEST['vat'] = 0;
				}
				if(isset($_REQUEST['partner_id'])) {
					$partner_id = $_REQUEST['partner_id'];
				} else {
					$partner_id = \Auth::user()->id;
				}
				$values = array(
					"name"						=> $_REQUEST['name'],
					"location"					=> $_REQUEST['location'],
					"region"					=> $_REQUEST['region'],
					"partner_id"				=> $partner_id,
					"premium_plan"				=> $_REQUEST['premium_plan'],
					"res_desc"					=> $request->res_desc,
					"entry_by"					=> $partner_id,
					"opening_time"				=> $_REQUEST['opening_time'],
					"closing_time"				=> $_REQUEST['closing_time'],
					"breakfast_opening_time"	=> $_REQUEST['breakfast_opening_time'],
					"breakfast_closing_time"	=> $_REQUEST['breakfast_closing_time'],
					"lunch_opening_time"		=> $_REQUEST['lunch_opening_time'],
					"lunch_closing_time"		=> $_REQUEST['lunch_closing_time'],
					"dinner_opening_time"		=> $_REQUEST['dinner_opening_time'],
					"dinner_closing_time"		=> $_REQUEST['dinner_closing_time'],
					"phone"						=> $_REQUEST['phone'],
					"secondary_phone_number" 	=> $_REQUEST['secondary_phone_number'],
					"secondary_phone_number2" 	=> $_REQUEST['secondary_phone_number2'],
					"hd_gst"					=> $_REQUEST['hd_gst'],
					"service_tax"				=> $_REQUEST['service_tax'],
					"ds_commission"				=> $_REQUEST['ds_commission'],
					//"delivery_charge"			=> $_REQUEST['delivery_charge'],
					"max_packaging_charge"		=> $_REQUEST['max_packaging_charge'],
					"vat"						=> $_REQUEST['vat'],
					"delivery_time"				=> $_REQUEST['delivery_time'],
					"pure_veg"					=> $_REQUEST['pure_veg'],
					"offer"						=> $_REQUEST['offer'],
					"min_order_value"			=> $_REQUEST['min_order_value'],
					"max_value"					=> $_REQUEST['max_value'],
					"offer_from"				=> $_REQUEST['offer_from'],
					"offer_to"					=> $_REQUEST['offer_to'],
					"budget"					=> $_REQUEST['budget'],
					// "logo"					=> $image,
					//"cuisine"					=> $_REQUEST['cuisine'],
					"cuisine"					=> $_REQUEST['cuisine_val'],
					"latitude"					=> $_REQUEST/*data*/['latitude'],
					"longitude"					=> $_REQUEST/*data*/['longitude'],
					"call_handling"				=> $_REQUEST['call_handling'],
					"active"					=> $_REQUEST['active'],
					"new_start_date"			=> $_REQUEST['new_start_date'],
					"new_end_date"				=> $_REQUEST['new_end_date'],
					"res_sequence"				=> $_REQUEST['res_sequence'],
					"res_seq_start"		    	=> $_REQUEST['res_seq_start'],
					"res_seq_end"				=> $_REQUEST['res_seq_end'],
					// "status"        			=> /*$_REQUEST['status']*/0
				);
				// echo "<pre>";
				// print_r($values);exit;
				if($_REQUEST['user_image']!=''){
				 $data = $_REQUEST['user_image'];
				$name = time().'-'.rand(1000,100).'.png';
				list($type, $data) = explode(';', $data);
				list(, $data)      = explode(',', $data);
				$data = base64_decode($data);
				//echo public_path()."/uploads/restaurants/imageroja.png";

				file_put_contents(base_path()."/uploads/restaurants/".$name, $data);
				$values['logo']=$name;
				}else{
					$values['logo']='';
				}
				// if(isset($_POST['image_src']) != ''){
				// 	$file_path 	= explode('/', $_POST['image_src']);
				// 	$img = public_path()."/uploads/restaurants/".end($file_path);
				// 	$suc = file_put_contents($img, file_get_contents($_POST['image_src']));
				// 	if($suc){
				// 		$ipath = public_path()."/temp/".end($file_path);
				// 		if(\File::exists($ipath)){
				// 			\File::Delete($ipath);
				// 		}
				// 		$values['logo'] = end($file_path);
				// 	} else {
				// 		$values['logo'] = '';
				// 	}
				// }				
				if($_REQUEST['id'] !=''){
				  if($_REQUEST['active'] == '0'){
					  		
					$rest = \DB::select("SELECT `res`.`name`,`user`.`email` from `abserve_restaurants` as `res` JOIN `tb_users` as `user`  ON `res`.`partner_id`=`user`.`id` WHERE `res`.`id`=".$_REQUEST['id']);
						
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
							
				  }
				}
				

				if($request->input('id') == ''){
					// $data = $this->validatePost('tb_restaurant');	
					// $id = $this->model->insertRow($data , $request->input('id'));
					$id = \DB::table('abserve_restaurants')->insertGetId($values);
				} else {
					$image = \DB::select("SELECT `logo` FROM `abserve_restaurants` WHERE `id` = ".$_REQUEST['id']);
					$image = $image[0]->logo;

					$updated = \DB::table('abserve_restaurants')->where('id','=',$request->input('id'))->update($values);
					$id = $request->input('id');
				}

				//All Offers Table Values Start
				$_values = array(
					"res_id"					=> $id,
					"offer"						=> $_REQUEST['offer'],
					"min_order_value"			=> $_REQUEST['min_order_value'],
					"max_value"					=> $_REQUEST['max_value'],
					"offer_from"				=> $_REQUEST['offer_from'],
					"offer_to"					=> $_REQUEST['offer_to'],
					"offer_name"				=> "Restaurant Offer",
				);
				$offers = \DB::table('offers')->where('res_id','=',$id)->where('offer_name','=','Restaurant Offer')->first();
				if($offers ==''){
					\DB::table('offers')->insertGetId($_values);
				} else {
					\DB::table('offers')->where('res_id','=',$id)->where('offer_name','=','Restaurant Offer')->update($_values);
				}
				//All Offers Table Values End
				
				if(!is_null($request->input('apply')))
				{
					$return = 'restaurant/update';
				} else {
					$return = 'restaurant?return='.self::returnUrl();
				}

				// Insert logs into database
				if($request->input('id') =='')
				{
					\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
				} else {
					\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
				}
				return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			}
			
		} else {
			return Redirect::to('restaurant/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}

	public function getResdelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if($request->segment(3) != '')
		{
			$id = $request->segment(3);
			/*abserve_boyorderstatus*/
			$boyorderstatus = \DB::table('abserve_boyorderstatus')->where('rid','=',$id)->get();
			if(count($boyorderstatus) > 0)
				\DB::table('abserve_boyorderstatus')->where('rid','=',$id)->delete();
			/*abserve_hotel_items*/
			$fooditems = Fooditems::where('restaurant_id',$id)->get();
			if(count($fooditems) > 0)
				Fooditems::where('restaurant_id',$id)->delete();
			/*abserve_normal_order*/
			$ondelivery = Ondeliveryorder::where('res_id',$id)->get();
			if(count($ondelivery) > 0)
				Ondeliveryorder::where('res_id',$id)->delete();
			/*abserve_orders_customer*/
			$customorder = Customerorder::where('res_id',$id)->get();
			if(count($customorder) > 0)
				Customerorder::where('res_id',$id)->delete();
			/*abserve_order_details*/
			$orderdetail = \DB::table('abserve_order_details')->where('res_id','=',$id)->get();
			if(count($orderdetail) > 0)
				\DB::table('abserve_order_details')->where('res_id','=',$id)->delete();
			/*abserve_payment_order*/
			$paymentorder = Paymentorder::where('res_id',$id)->get();
			if(count($paymentorder) > 0)
				Paymentorder::where('res_id',$id)->delete();
			/*abserve_rating*/
			$absrating = \DB::table('abserve_rating')->where('res_id','=',$id)->get();
			if(count($absrating) > 0 )
				\DB::table('abserve_rating')->where('res_id','=',$id)->delete();
			/*abserve_user_cart*/
			$usercart = Usercart::where('res_id',$id)->get();
			if(count($usercart) > 0)
				Usercart::where('res_id',$id)->delete();

			$this->model->destroy($id);
			
			\SiteHelpers::auditTrail( $request , "ID : ".($id)."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('restaurant')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('restaurant')
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
			return Redirect::to('restaurant')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('restaurant')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}			

/*	public function postUpload(Request $request){
		// print_r(Input::file('image'));exit;

		$quality = 90;

		$file = Input::file('image');
		$file_name = $file->getClientOriginalName();
		$file->move('uploads', $file_name);

		$src  = 'uploads/'. $file_name;
		$img  = imagecreatefromjpeg($src);
		$dest = ImageCreateTrueColor(Input::get('w'),
		    Input::get('h'));

		imagecopyresampled($dest, $img, 0, 0, Input::get('x'),
		    Input::get('y'), Input::get('w'), Input::get('h'),
		    Input::get('w'), Input::get('h'));
		imagejpeg($dest, $src, $quality);
		$image_src = \URL::to('').'/'. $src;
		echo "<img src='".$image_src."' border='0' width='50' class='img-circle'>";
	}*/
	
	function getResdetails(Request $request){
	
		$partner_id = $_REQUEST['partner_id'];
		$user = \DB::table('tb_users')->where('id',$partner_id)->first();
		$region = \DB::table('region')->where('id',$user->region)->first();
		echo $user->res_name.'@@'.$user->phone_number.'@@'.$user->address;
		
	}

}
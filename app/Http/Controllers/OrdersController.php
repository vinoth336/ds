<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use App\Http\Controllers\NewordersController;
use App\Http\Controllers\mobile\UserController as usercon;


use DB;


class OrdersController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'orders';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Orders();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'orders',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
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
		// Get Query 
		$results = $this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('orders');
		
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
		/*echo "<pre>";
		print_r($this->info);exit;*/
		if(\Auth::user()->group_id == 1){
			// Render into template
			return view('orders.index',$this->data);
		} else {
			$user_id = \Auth::user()->id;

			$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".$user_id."' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC");
			/*echo "<pre>";
			print_r($results);exit;

			$results = $this->postPartnernerorders($user_id);*/			

			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".$user_id."' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC LIMIT 1");

			$partner_details = \DB::table('tb_users')->select('*')->where('id',$user_id)->get();
			$this->data['pphone']	= $partner_details[0]->phone_number;
			$this->data['results']	= $results;
			$this->data['last_id']	= json_encode($lastid[0]->id);
			//print_r($this->data['last_id']);exit;
			$this->data['tableview']= 'orders.table_view';
			return view('orders.index_new',$this->data)->with('model',new Orders);
		}
	}

	//Parnerorders function for status of the order
	public function postPartnernerorders($user_id){
		//echo "string";exit;
		//print_r($user_id);exit;
		$response = $whole_orders = array();
		$partner_id = $user_id;
	
		/*$rules = array(
			'partner_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);*/

		/*if ($validator->passes()) {*/
			$id_exists = \DB::table('tb_users')->where('id','=',$partner_id)->exists();
			if($id_exists){
				$order_ids = \DB::table('abserve_orders_partner')
				->select('orderid')
				->where('partner_id','=',$partner_id)
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
					return $response;

				}else{
					$response['message'] = "No orders found";
					return $response;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				return $response;
			}
		/*}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}*/		
	}

	public function postLastid(Request $request){
		$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".$_REQUEST['partner_id']."' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC LIMIT 1");
		if(!empty($lastid)){
			$last_id = json_encode($lastid[0]->id);
		} else {
			$last_id = '';
		}
		
		return $last_id;
	}

	function getUpdate(Request $request, $id = null)
	{
	
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if(\Auth::user()->group_id !="1"){
				$results = \DB::select("SELECT * FROM `abserve_orders` WHERE `id` = '".$id."' AND `partner_id` = '".\Auth::user()->id."'");
				if (empty($results)){
					return Redirect::to('orders');
				} else {
					if($this->access['is_edit'] ==0 )
						return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
				}
			} else {
				if($this->access['is_edit'] ==0 )
					return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
			}
		}				
		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('abserve_orders'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('orders.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_orders'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('orders.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_orders');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'orders/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'orders?return='.self::returnUrl();
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

			return Redirect::to('orders/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	}	

	public function postPartnerdelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		// delete multipe rows 
				//print_r($request->input('ids'));exit;
		if(count($request->input('ids')) >=1)
		{
			
			// $this->model->destroy($request->input('ids'));
			//echo "string2";exit;
			\DB::table('abserve_order_details')->whereIn('id', $request->input('ids'))->delete();
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('orders')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('orders')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}
	}		

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		// delete multipe rows 
				//print_r($request->input('ids'));exit;
		if(count($request->input('ids')) >=1)
		{
			
			$this->model->destroy($request->input('ids'));
			//echo "string2";exit;
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('orders')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('orders')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}
	}			

	public function postAjaxload( Request $request){
		if(isset($_POST['value']) && $_POST['value'] != ''){
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".\Auth::id()."' AND `po`.`order_status` = '0' ORDER BY `od`.`id` DESC LIMIT 1");
			if(!empty($lastid)){
				/*echo $_POST['value']."-----";
				echo $lastid[0]->id;*/
				if($_POST['value']	!= $lastid[0]->id){
					$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".\Auth::id()."' ORDER BY `od`.`id` DESC");
					$this->data['results']	= $results;
					$response['msg']	= 'success';
					$response['lastid']	= $lastid[0]->id;

					
					// return view('orders.table_view', $this->data)->with('model',new Orders);
				} else {
					$response['msg']	= 'failed';
				}
			} else {
				$response['msg']	= 'failed';
				// echo "failed";exit();
			}

			echo json_encode($response);exit();
		}
	}

	public function postTablelist( Request $request){
		
		$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".\Auth::id()."' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC LIMIT 1");
		
		
		$res_detail = $this->model->resname($results[0]->orderid);
		$html = '<tr><td width="50"><input type="checkbox" class="ids" name="ids[]" value="'.$results[0]->id.'" /></td><td width="50">5374675346857</td><td width="50">#'.$results[0]->orderid.'</td><td width="50">'.$res_detail[0]->name.'</td><td width="50">'.$results[0]->order_details.'</td><td width="50">'.$results[0]->delivery_type.'</td><td><i class="fa fa-volume-up buzzer_vol fn_buzzer_off"></i><i class="icon-checkmark-circle2 fn_accept" aria-hidden="true"></i><i class="icon-cancel-circle2 fn_reject" aria-hidden="true"></i><input type="hidden" value="'.$results[0]->partner_id.'" class="partner_id" /><input type="hidden" value="'.$results[0]->orderid.'" class="orderid" /></td></tr>';
		// $response['msg']	= '<tr><td width="30"> id </td><td width="50"><input type="checkbox" class="ids" name="ids[]" value="" /></td><td width="50">time</td><td width="50">#233343</td><td width="50">fdgfdgdfhfdh</td><td width="50">veg</td><td><i class="fa fa-volume-up"></i><i class="icon-checkmark-circle2" aria-hidden="true"></i><i class="icon-cancel-circle2" aria-hidden="true"></i></td></tr>';
		//return view('orders.table_view', $this->data)->with('model',new Orders);
		return $html;
	}

	public function postAdminajaxload( Request $request){
		if(isset($_POST['value']) && $_POST['value'] != ''){
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE    `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC LIMIT 1");
			if(!empty($lastid)){
				
				if($_POST['value']	!= $lastid[0]->id){
					$results =  \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE  `po`.`order_status` = '0' AND  `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC ");
					$this->data['results']	= $results;
					$response['msg']	= 'success';
					$response['lastid']	= $lastid[0]->id;

					
					
				} else {
					$response['msg']	= 'failed';
				}
			} else {
				$response['msg']	= 'failed';
				// echo "failed";exit();
			}

			echo json_encode($response);exit();
		}
	}

	public function postAdmintablelist( Request $request){
		
		$results =  \DB::select("SELECT `po`.*,`rs`.name as hotel_name FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE    `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC LIMIT 1");

		$res_detail = $this->model->partnerphone($results[0]->partner_id);
		$deliveryboys = $this->model->alldeliveryboys();$option = '';
		foreach($deliveryboys as $dboys){
			$option.= '<option value="'.$dboys->id.'">'.$dboys->username.'</option>';
		}
		$html = '<tr class="fn_'.$results[0]->orderid.'"><td width="50"><input type="checkbox" class="ids" name="ids[]" value="'.$results[0]->id.'" /></td><td width="50">5374675346857</td><td width="50">#'.$results[0]->orderid.'</td><td width="50">'.$results[0]->partner_id.'</td><td width="50">'.$results[0]->hotel_name.'</td><td width="50">'.$results[0]->order_details.'</td><td width="50"><div style="float:left;">'.$res_detail[0]->phone_number.'</div><div style="float:right;"><i class="icon-checkmark-circle2 fn_accept" aria-hidden="true"></i>&nbsp;&nbsp;<i class="icon-cancel-circle2 fn_reject" aria-hidden="true"></i></div></td><td><select name="delivery_boy" class="delivery_boy">'.$option.'</select><i class="icon-checkmark-circle2 fn_deliveryboy" aria-hidden="true"></i><i class="icon-cancel-circle2 " aria-hidden="true"></i><input type="hidden" value="'.$results[0]->partner_id.'" class="partner_id" /><input type="hidden" value="'.$results[0]->orderid.'" class="orderid" /></td></tr>';
		return $html;
	}


	public function getPorderaccept( Request $request){
		//echo "string";exit;
		$response = array();
		$Myvar = new usercon();
		$response = $Myvar->postPorderaccept($request);
		//print_r($response);exit;
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

		$return['boy_id']	= $boys[$random_id]->id;
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

		$assignedBoyData = \DB::table('abserve_deliveryboys')
						->select('abserve_deliveryboys.username')
						->leftjoin('abserve_boyorderstatus','abserve_boyorderstatus.bid','=','abserve_deliveryboys.id')
						->where('abserve_boyorderstatus.oid',$order_id)->first();

		if($orderAlredyAccepted){
			$response['message'] = "Order already accepted by delivery executive ".$assignedBoyData->username;
			echo json_encode($response);exit;
		} else if($orderAlredyDeclined) {
			$orderDeclinedBoys = \DB::table('abserve_boyorderstatus')->select(\DB::raw('GROUP_CONCAT(QUOTE( `bid` )) as bids'))->where('status','2')->where('oid',$order_id)->first();
		} else if($orderAlredyAssigned) {
			$response['message'] = "Order already assigned to delivery executive ".$assignedBoyData->username;
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


	public function getPorderreject( Request $request){
		$response = array();
		$Myvar = new usercon();
		//$Myvar->postPorderreject($request);
		$Myvar->postAdminorderreject($request);
	}

	//order_status = 1(Partner,Customer) status = 1(Order Detail)Push_notification for customer
	public function getPorderaccept_old( Request $request){
		
		$Myvar = new NewordersController();
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
							$appapi_details	= $Myvar->appapimethod(1);
							$mobile_token 	= $Myvar->userapimethod($cust_id[0]->cust_id,'abserve_customers');
							$message 		= "Your order Hasbeen Accepted";
							$app_name		= $appapi_details->app_name;
							$app_api 		= $appapi_details->api;
							/*print_r($mobile_token);
							print_r($app_api);
							print_r($app_name);*/
							$Myvar->pushnotification($app_api,$mobile_token,$message,$app_name);
							$response['message'] = "Order Accepted";
							$response['alert'] = "alert-success";
							echo json_encode($response);exit;
						}else{
							$response['message'] = "Order Doesn't Accepted";
							$response['alert'] = "alert-danger";
							echo json_encode($response);exit;
						}
					}else{
						$response['message'] = "It's Not your Order";
						$response['alert'] = "alert-danger";
						echo json_encode($response);exit;		
					}
				}else{
					$response['message'] = "No Such Order found";
					$response['alert'] = "alert-danger";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				$response['alert'] = "alert-danger";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	//order_status = 5(Partner,Customer)Push_notification for customer
	public function getPorderreject_old( Request $request){
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
							$response['message'] = "Order Rejected";
							$response['alert'] = "alert-success";
							echo json_encode($response);exit;
						}else{
							$response['message'] = "Order Doesn't Rejected";
							$response['alert'] = "alert-danger";
							echo json_encode($response);exit;
						}
					}else{
						$response['message'] = "It's Not your Order";
						$response['alert'] = "alert-danger";
						echo json_encode($response);exit;		
					}
				}else{
					$response['message'] = "No Such Order found";
					$response['alert'] = "alert-danger";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				$response['alert'] = "alert-danger";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}


    public function getPendingMails(Request $request)
    {


     	$loged_user = \Auth::user()->id;


        $select = "SELECT id as action,cust_id as Subject FROM `tb_support` ";//\DB::select();

        $countqry = "SELECT COUNT(*) FROM `abserve_order_details` ";
        
        $columns = array(


            array( 'db' => 'id',  'dt' => 0 ),

            array( 'db' => 'cust_id',  'dt' => 1 ),
          
        );

        $sql_details = array(
            'user' => "root",
            'pass' => "",
            'db'   => "food_back",
            'host' => "localhost"
        );

        $whereResult = "`s_tax` ='18'";

        echo json_encode(
            SSP::complex ( $_POST, $sql_details, 'tb_support', 'id', $columns, $whereResult )
        );


    }





}
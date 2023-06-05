<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Orderdetails;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class OrderdetailsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'orderdetails';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Orderdetails();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'orderdetails',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{
		
		$region_id = $request->region;
		
		$regionid = \DB::table('region')->select('*')->where("id",'=',$region_id)->first();
		$region_key = $regionid->region_keyword;	
		
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
		if(\Auth::user()->group_id == 1)
			$results = $this->model->getRows($params);		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('orderdetails');
		
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
		/*if(\Auth::user()->group_id == 1){
			$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `order_status` = '0' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC");
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE  `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC LIMIT 1");
			$this->data['results']	= $results;
			$this->data['last_id']	= json_encode($lastid[0]->id);
			$this->data['tableview']= 'orders.table_view';
			return view('orders.index_new',$this->data)->with('model',new Orders);
		} else {
			$user_id = \Auth::user()->id;

			$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `order_status` = '0' AND `partner_id` = '".$user_id."' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC");
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".$user_id."' AND `delivery` IN ('on_delivery','paid') ORDER BY `od`.`id` DESC LIMIT 1");

			$partner_details = \DB::table('tb_users')->select('*')->where('id',$user_id)->get();
			$this->data['pphone']	= $partner_details[0]->phone_number;
			$this->data['results']	= $results;
			$this->data['last_id']	= json_encode($lastid[0]->id);
			$this->data['tableview']= 'orders.table_view';
			return view('orders.index_new',$this->data)->with('model',new Orders);
		}*/
		/*Pending Order Details*/
		if($region_id !='')  {
			
			$user_id = \Auth::user()->id;
		
			
		$where = " (`po`.`order_status` = '0' OR `po`.`order_status` = '1' OR `po`.`order_status` = '2' OR `po`.`order_status` = '3' OR `po`.`order_status` = '5' OR `od`.`status` = '2') ";
		$whereLast 	= ' 1=1';
		if(\Auth::user()->group_id == 3) { //partner details
			$partner_details = \DB::table('tb_users')->select('*')->where('id',$user_id)->where('region',$region_id)->get();
			//$user_id = \Auth::user()->id;
			$where .= " AND `po`.`partner_id` = '".$user_id."' ";
			$whereLast .= " AND `po`.`partner_id` = '".$user_id."' ";
			$this->data['pphone']	= $partner_details[0]->phone_number;
		} elseif(\Auth::user()->group_id == 7) { //franchies details
			$franchies_details = \DB::table('tb_users')->select('id')->where('region',$region_id)->get();
			//$user_id = \Auth::user()->id;
			foreach($franchies_details as $franchies_detail){
				$franchies[] = $franchies_detail->id;
				$id = $franchies_detail->id;
			}
			if(count($franchies)>0){
				$where .= " AND `po`.`partner_id` IN (".implode(",",$franchies).") ";
				$whereLast .= " AND `po`.`partner_id` = '".$id."' ";
				$this->data['pphone']	= $franchies_details[0]->phone_number;
			}
		}
	$results = \DB::select("SELECT `po`.*,`od`.*,`ar`.`name`,`ar`.`region`,`ob`.`boy_id`,`ob`.`delivery_accept`,`ob`.`delivery_dispatch`,`ob`.`delivery_complete` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `od`.`res_id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$where."  AND `ar`.`region`='".$region_key."' ORDER BY `od`.`id` DESC");
		$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `od`.`res_id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$whereLast."  AND `ar`.`region`='".$region_key."' ORDER BY `od`.`id` DESC LIMIT 1");
		
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$region_id)->get();
		
		$deliveryboys .= '<option value=""> </option>';
		foreach ($delivery_boys as $key => $delivery_boy){
			$deliveryboys .= '<option value="'.$delivery_boy->id.'">'.$delivery_boy->username.'</option>';
		}	
			
		}else{
		
		
		$user_id = \Auth::user()->id;
		$region_id = session()->get('rid');
			
		$where = " (`po`.`order_status` = '0' OR `po`.`order_status` = '1' OR `po`.`order_status` = '2' OR `po`.`order_status` = '3' OR `po`.`order_status` = '5' OR `od`.`status` = '2') ";
		$whereLast 	= ' 1=1';
		if(\Auth::user()->group_id == 3) { //partner details
			$partner_details = \DB::table('tb_users')->select('*')->where('id',$user_id)->get();
			//$user_id = \Auth::user()->id;
			$where .= " AND `po`.`partner_id` = '".$user_id."' ";
			$whereLast .= " AND `po`.`partner_id` = '".$user_id."' ";
			$this->data['pphone']	= $partner_details[0]->phone_number;
		} elseif(\Auth::user()->group_id == 7) { //franchies details
			$franchies_details = \DB::table('tb_users')->select('id')->where('region',$region_id)->get();
			//$user_id = \Auth::user()->id;
			foreach($franchies_details as $franchies_detail){
				$franchies[] = $franchies_detail->id;
				$id = $franchies_detail->id;
			}
			if(count($franchies)>0){
				$where .= " AND `po`.`partner_id` IN (".implode(",",$franchies).") ";
				$whereLast .= " AND `po`.`partner_id` = '".$id."' ";
				$this->data['pphone']	= $franchies_details[0]->phone_number;
			}
		}
		$results = \DB::select("SELECT `po`.*,`od`.*,`ob`.`boy_id`,`ob`.`delivery_accept`,`ob`.`delivery_dispatch`,`ob`.`delivery_complete` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$where." ORDER BY `od`.`id` DESC");
		$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$whereLast." ORDER BY `od`.`id` DESC LIMIT 1");
		//$sql="SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE ".$where." ORDER BY `od`.`id` DESC";
		//echo $sql;exit;
		if(\Auth::user()->group_id == 7) {
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$region_id)->get();
		} else {
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->get();
		}
		
		$deliveryboys .= '<option value=""> </option>';
		foreach ($delivery_boys as $key => $delivery_boy){
			$deliveryboys .= '<option value="'.$delivery_boy->id.'">'.$delivery_boy->username.'</option>';
		}
		}
		
		$this->data['deliveryboys'] = $deliveryboys;
		$this->data['results']	= $results;
		//echo '<pre>';print_r($results);exit;
		$this->data['last_id']	= json_encode($lastid[0]->id);
		$this->data['tableview']= 'orders.table_view';
		return view('orders.index_new',$this->data)->with('model',new Orders);
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
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('abserve_orders_partner'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('orderdetails.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_orders_partner'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('orderdetails.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_orderdetails');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'orderdetails/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'orderdetails?return='.self::returnUrl();
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

			return Redirect::to('orderdetails/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
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
			return Redirect::to('orderdetails')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('orderdetails')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}		

	public function getAjaxtableload( Request $request){
		
		//echo $request->regionselect;
		
		if($request->regionselect != ''){
			
		$user_id = \Auth::user()->id;
		$region_id = $request->regionselect;
		
		$regionid = \DB::table('region')->select('*')->where("id",'=',$region_id)->first();
		$region_key = $regionid->region_keyword;	
			
		
		$where = " (`po`.`order_status` = '0' OR `po`.`order_status` = '1' OR `po`.`order_status` = '2' OR `po`.`order_status` = '3' OR `po`.`order_status` = '5' OR `od`.`status` = '2') ";
		$whereLast 	= ' 1=1';
		if(\Auth::user()->group_id == 3) { //partner details
			$partner_details = \DB::table('tb_users')->select('*')->where('id',$user_id)->where('region',$region_id)->get();
			//$user_id = \Auth::user()->id;
			$where .= " AND `po`.`partner_id` = '".$user_id."' ";
			$whereLast .= " AND `po`.`partner_id` = '".$user_id."' ";
			$this->data['pphone']	= $partner_details[0]->phone_number;
		} elseif(\Auth::user()->group_id == 7) { //franchies details
			$franchies_details = \DB::table('tb_users')->select('id')->where('region',$region_id)->get();
			//$user_id = \Auth::user()->id;
			foreach($franchies_details as $franchies_detail){
				$franchies[] = $franchies_detail->id;
				$id = $franchies_detail->id;
			}
			if(count($franchies)>0){
				$where .= " AND `po`.`partner_id` IN (".implode(",",$franchies).") ";
				$whereLast .= " AND `po`.`partner_id` = '".$id."' ";
				$this->data['pphone']	= $franchies_details[0]->phone_number;
			}
		}
		$results = \DB::select("SELECT `po`.*,`od`.*,`ar`.`name`,`ar`.`region`,`ob`.`boy_id`,`ob`.`delivery_accept`,`ob`.`delivery_dispatch`,`ob`.`delivery_complete` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `od`.`res_id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$where."  AND `ar`.`region`='".$region_key."' ORDER BY `od`.`id` DESC");
		$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `od`.`res_id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$whereLast."  AND `ar`.`region`='".$region_key."' ORDER BY `od`.`id` DESC LIMIT 1");
		
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$region_id)->get();
	
		
		}else{
		$user_id = \Auth::user()->id;
		$region_id = session()->get('rid');
		
		$where = " (`po`.`order_status` = '0' OR `po`.`order_status` = '1' OR `po`.`order_status` = '2' OR `po`.`order_status` = '3' OR `po`.`order_status` = '5' OR `od`.`status` = '2') ";
		$whereLast 	= ' 1=1';
		if(\Auth::user()->group_id == 3) { //partner details
			$partner_details = \DB::table('tb_users')->select('*')->where('id',$user_id)->get();
			//$user_id = \Auth::user()->id;
			$where .= " AND `po`.`partner_id` = '".$user_id."' ";
			$whereLast .= " AND `po`.`partner_id` = '".$user_id."' ";
			$this->data['pphone']	= $partner_details[0]->phone_number;
		} elseif(\Auth::user()->group_id == 7) { //franchies details
			$franchies_details = \DB::table('tb_users')->select('id')->where('region',$region_id)->get();
			//$user_id = \Auth::user()->id;
			foreach($franchies_details as $franchies_detail){
				$franchies[] = $franchies_detail->id;
				$id = $franchies_detail->id;
			}
			if(count($franchies)>0){
				$where .= " AND `po`.`partner_id` IN (".implode(",",$franchies).") ";
				$whereLast .= " AND `po`.`partner_id` = '".$id."' ";
				$this->data['pphone']	= $franchies_details[0]->phone_number;
			}
		}
		$results = \DB::select("SELECT `po`.*,`od`.*,`ob`.`boy_id`,`ob`.`delivery_accept`,`ob`.`delivery_dispatch`,`ob`.`delivery_complete` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$where." ORDER BY `od`.`id` DESC");
		$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` LEFT JOIN `abserve_orders_boy` as `ob` ON `po`.`orderid` = `ob`.`orderid` WHERE ".$whereLast." ORDER BY `od`.`id` DESC LIMIT 1");
		
		if(\Auth::user()->group_id == 7) {
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->where('region','=',$region_id)->get();
		} else {
			$delivery_boys = \DB::table('abserve_deliveryboys')->select('id','username')->where('active','=','1')->where('online_sts','=','1')->get();
		}
			
			
		}
		
		$deliveryboys .= '<option value=""> </option>';
		foreach ($delivery_boys as $key => $delivery_boy){
			$deliveryboys .= '<option value="'.$delivery_boy->id.'">'.$delivery_boy->username.'</option>';
		}
		if(!empty($results)){
			foreach($results as $order){
				$res_detail = $this->model->resname($order->orderid);
				$cname = \SiteHelpers::hostname($order->cust_id);
				//$total_price = ($order->grand_total - $order->delivery_charge);
				$date_time = date('Y-m-d h:i:s A',$order->time);
				$bid = \SiteHelpers::getBoyid($order->orderid);
				$boyname = \SiteHelpers::getBoyname($bid);
				$action = "";
				if($order->order_status == 0){
								
					$action .='<i data-toggle="tooltip" title="Accept your order" class="icon-checkmark-circle2 fn_accept" aria-hidden="true" style="cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->partner_id.'" class="partner_id" /><input type="hidden" value="'.$order->orderid.'" class="orderid" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 " onclick="stop()">'.$deliveryboys.'</select>';
				} else {
					if($order->order_status == 5){
						$action .='<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i> <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->partner_id.'" class="partner_id" /><input type="hidden" value="'.$order->orderid.'" class="orderid" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 " onclick="stop()">'.$deliveryboys.'</select>';
					} else {
						$action .='<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>';
						if($order->order_status == 1 || $order->order_status == 2){
							$action .=' <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i><input type="hidden" value="'.$order->partner_id.'" class="partner_id" /><input type="hidden" value="'.$order->orderid.'" class="orderid" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 " onclick="stop()">'.$deliveryboys.'</select>';
						} else {
							$action .=' <i data-toggle="tooltip" title="Action disabled" class="icon-cancel-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>';
							if($bid ==''){
								$action .='<input type="hidden" value="'.$order->partner_id.'" class="partner_id" /><input type="hidden" value="'.$order->orderid.'" class="orderid" /> <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 " onclick="stop()">'.$deliveryboys.'</select>';
							} else {
								$action .=' <select name="delivery_boy" rows="5" id="delivery_boy" class="select1 " disabled="disabled" ></select>';
							}
						}
					}
				}
				
				if($order->order_status == 1 ){
					$status ='<span class="label status label-success">Accepted by Restaurant</span>';
				} elseif($order->order_status == 2){
					$status ='<span class="label status label-primary">'. trans("core.abs_accept_by_boy") .'</span>';
				} elseif($order->order_status == 3){
					$status ='<span class="label status label-info">'. trans("core.abs_order_dispatch") .'</span>';
				} elseif($order->order_status == 5){
					$status ='<span class="label status label-info">Rejected by Restaurant</span>';
				} else {
					$status ='<span class="label label-warning status">'. trans("core.pending") .'</span>';
				}
				
				$address = "";
				if($order->building !=''){
					$address .= $order->building.', ';
				}
				if($order->landmark !=''){
					$address .= $order->landmark.', ';
				}
				$address .= $order->address;
				
				$resph = \SiteHelpers::getRestaurantPhone($order->res_id);
				$custph = \SiteHelpers::getCustomerPhone($order->cust_id);
				$offer = \SiteHelpers::getOfferPrice($order->res_id,$order->total_price,$order->date);
				$region = \SiteHelpers::getRegionKeyword($order->res_id);
				if($order->hd_gst ==1){
					$gst = "HGST - ".$order->s_tax;
				} else {
					$gst = "DGST - ".$order->s_tax;
				}
				if($order->coupon_type ==1){
					$coupon_price = "Res - ".$order->coupon_price;
				} else if($order->coupon_type ==2){
					$coupon_price = "DS - ".$order->coupon_price;
				} else {
					$coupon_price = $order->coupon_price;
				}
				
				$html .= '<tr><td width="50"><input type="checkbox" class="ids" name="ids[]" value="'.$order->orderid.'" /></td><td width="50">#'.$region.$order->orderid.'</td><td width="50">'.$order->cust_id.'</td><td width="50">'.$cname.'</td><td width="50" style="width:400px; white-space:pre-line;">'.$address.'</td><td width="50">'.$custph.'</td><td width="50">'.$res_detail[0]->name.'</td><td width="50">'.$order->total_price.'</td><td width="50">'.$order->delivery_charge.'</td><td width="50">'.$order->packaging_charge.'</td><td width="50">'.$order->grand_total.'</td><td width="50" style="width:100px; white-space:pre-line;">'.$date_time.'</td><td width="50" style="width:400px; white-space:pre-line;">'.$order->order_details.'</td><td width="50">'.$order->delivery_type.'</td><td width="50">'.$boyname.'</td><td>'.$action.'</td><td>'.$status.'</td><td width="50" style="width:200px; white-space:pre-line;">'.$order->order_reject_desc.'</td><td width="50">'.$gst.'</td><td width="50">'.$coupon_price.'</td><td width="50">'.$offer.'</td>';
				$html .= '<td width="50"><p>DA: ';
				if($order->delivery_accept != '0000-00-00 00:00:00' ){
					$html .= $order->delivery_accept;
				}
				$html .= '</p><p>DD: ';
				if($order->delivery_dispatch != '0000-00-00 00:00:00' ){
					$html .= $order->delivery_dispatch;
				}
				$html .= '</p></td>';
				$html .= '<td><a style="text-align: center; color: green;" class="call_ivrs" href="#" data-restaurant-phone="'. $resph .'"><i style="font-size: 22px;" class="fa fa-phone-square"></i> Call</a></td><td><a style="text-align: center; color: green;" class="call_ivrs" data-customer-phone="'. $custph .'" id="" href="#"><i style="font-size: 22px;" class="fa fa-phone-square"></i> Call</a></td></tr>';
			}
			return $html;
		} else {
			$html .= '<tr class="odd"><td valign="top" colspan="21" class="dataTables_empty">No data available in table</td></tr>';
			return $html;
		}
	}			


}
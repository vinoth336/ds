<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Cashondeliveryorder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;


class CashondeliveryorderController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'cashondeliveryorder';
	/*static $per_page	= '50';*/

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Cashondeliveryorder();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'cashondeliveryorder',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'desc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
		
		$partner_details = \DB::select("SELECT abserve_order_details.* FROM abserve_order_details WHERE abserve_order_details.id IS NOT NULL AND abserve_order_details.delivery_type = 'cod' AND (status ='4' OR status ='6' OR status ='10')");

		
		$page = $request->input('page', 1);
		$params = array( 
			'page'		=> $page ,
			'limit'		=> count($partner_details),
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
		$pagination->setPath('cashondeliveryorder');
		
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
		return view('cashondeliveryorder.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_order_details'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('cashondeliveryorder.form',$this->data);
	}	
	
	/** To show the report **/
	
	function getReports(Request $request, $id = null) 
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
		
		
		$region_id = session()->get('rid');
		$region_key = session()->get('rkey');
		
		if(\Auth::user()->group_id == 7) {
			$this->data['partner_hotels'] =\DB::select( "SELECT * FROM abserve_restaurants WHERE region='".$region_key."'"); /** restaurent details **/		
			$this->data['deliveryboy_details'] =\DB::select( "SELECT * FROM abserve_deliveryboys WHERE region='".$region_id."'"); /** delivery boy details **/		
			$this->data['customer_details'] =\DB::select( "SELECT * FROM tb_users WHERE group_id=4 AND region='".$region_id."'"); /** customer details **/
			$this->data['region_details'] =\DB::select( "SELECT * FROM region WHERE id='".$region_id."'");
		} else {
			$this->data['partner_hotels'] =\DB::select( "SELECT * FROM abserve_restaurants"); /** restaurent details **/		
			$this->data['deliveryboy_details'] =\DB::select( "SELECT * FROM abserve_deliveryboys"); /** delivery boy details **/		
			$this->data['customer_details'] =\DB::select("SELECT * FROM tb_users WHERE `group_id`=4 AND `region` != 1 AND `region` != 2"); /** customer details **/
			$this->data['region_details'] =\DB::select( "SELECT * FROM region"); /** region details **/
		}
		
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('abserve_order_details'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);

        $this->data['report_to_date'] = null;
		$this->data['id'] = $id;
		return view('cashondeliveryorder.deliveryreport',$this->data);
	}	
	
	
	
	public function postRegionselect( Request $request)
	{
	
	$region_key = $request->regionselect; 
	/*for restaurant based on region*/
	if($region_key != ''){
	$restregion =\DB::select( "SELECT * FROM abserve_restaurants WHERE region='".$region_key."'");
	}else{
	$restregion =\DB::select( "SELECT * FROM abserve_restaurants");	
	}
	$html = $html.'<select rows="9" class="" name="restaurant_id" id="restaurant_id">';
	$html = $html.'<option value="">'.'Select Restaurant Name'.'</option>' ;
	
	    foreach($restregion as $restregion1){
	               
		            $html = $html.'<option ' ;
                    $html = $html.'value="'.$restregion1->id.'">';
                    $html = $html.$restregion1->name.'</option>';
		}
		$html = $html.'</select>';
		
		$regionid = \DB::table('region')->select('*')->where("region_keyword",'=',$region_key)->first();
		$region_id = $regionid->id;
		
		/*for customer based on region*/
		if($region_key != ''){
		$userregion = \DB::select("SELECT * FROM tb_users WHERE group_id=4 AND region='".$region_id."'"); 
		}else{
		$userregion = \DB::select("SELECT * FROM tb_users WHERE group_id=4");	
		}
	    $user = $user.'<select rows="9" class="" name="customer_id" id="customer_id">';
		$user = $user.'<option value="">'.'Select Customer Name'.'</option>' ;
	
	    foreach($userregion as $userregion1){
	               
		            $user = $user.'<option ' ;
                    $user = $user.'value="'.$userregion1->id.'">';
                    $user = $user.$userregion1->first_name. ''.$userregion1->last_name.'</option>';
					
		}
		$user = $user.'</select>';
		
		/*for deliveryboy based on region*/
		if($region_key != ''){
		$deliveryregion = \DB::select("SELECT * FROM abserve_deliveryboys WHERE region='".$region_id."'"); 
		}else{
		$deliveryregion = \DB::select("SELECT * FROM abserve_deliveryboys");	
		}
	    $dboy = $dboy.'<select rows="9" class="" name="deliveryboy_id" id="deliveryboy_id">';
	    $dboy = $dboy.'<option value="">'.'Select Delivery Boy'.'</option>' ;
	    foreach($deliveryregion as $deliveryregion1){
	               
		            $dboy = $dboy.'<option ' ;
                    $dboy = $dboy.'value="'.$deliveryregion1->id.'">';
                    $dboy = $dboy.$deliveryregion1->username.'</option>';
					
		}
		$dboy = $dboy.'</select>';
		
		return $user.'@@'.$html.'@@'.$dboy;
 
	
	
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
			$this->data['row'] = $this->model->getColumnTable('abserve_order_details'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('cashondeliveryorder.view',$this->data);	
	}
	
	/** delivery order view start **/
	function postDeliveryview( Request $request)
	{
		$restaurent_id = $_POST['restaurant_id'];
		$customer_id = $_POST['customer_id'];		
		$delivery_type = $_POST['delivery_type'];
		$deliveryboy_id = $_POST['deliveryboy_id'];
		$status = $_POST['status'];
		$report_from_date = date('Y-m-d',strtotime($_POST['report_from_date']));
		$report_to_date = date('Y-m-d',strtotime($_POST['report_to_date']));
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			/*if($restaurent_id !='' && $customer_id !='' && $delivery_type !='' && $deliveryboy_id !='')
			{
				$where = " `res_id` = '".$restaurent_id."' AND `cust_id` = '".$customer_id."' AND `delivery_type` = '".$delivery_type."' AND `boy_id` = '".$deliveryboy_id."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			if($restaurent_id =='' && $customer_id =='' && $delivery_type =='' && $deliveryboy_id =='')
			{
				$where = " `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			
			if($restaurent_id =='' && $customer_id !='' && $delivery_type !='' && $deliveryboy_id !='')
			{
				$where = " `cust_id` = '".$customer_id."' AND `delivery_type` = '".$delivery_type."' AND `boy_id` = '".$deliveryboy_id."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			
			if($restaurent_id !='' && $customer_id =='' && $delivery_type !='' && $deliveryboy_id !='')
			{
				$where = " `res_id` = '".$restaurent_id."' AND `delivery_type` = '".$delivery_type."' AND `boy_id` = '".$deliveryboy_id."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			
			if($restaurent_id !='' && $customer_id !='' && $delivery_type =='' && $deliveryboy_id !='')
			{
				$where = " `res_id` = '".$restaurent_id."' AND `cust_id` = '".$customer_id."' AND `boy_id` = '".$deliveryboy_id."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			
			if($restaurent_id !='' && $customer_id !='' && $delivery_type !='' && $deliveryboy_id =='')
			{
				$where = " `res_id` = '".$restaurent_id."' AND `cust_id` = '".$customer_id."' AND `delivery_type` = '".$delivery_type."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			
			if($restaurent_id =='' && $customer_id =='' && $delivery_type !='' && $deliveryboy_id !='')
			{
				$where = " `delivery_type` = '".$delivery_type."' AND `boy_id` = '".$deliveryboy_id."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}
			
			if($restaurent_id !='' && $customer_id !='' && $delivery_type !='' && $deliveryboy_id !='')
			{
				$where = " `res_id` = '".$restaurent_id."' AND `cust_id` = '".$customer_id."' AND `delivery_type` = '".$delivery_type."' AND `boy_id` = '".$deliveryboy_id."' AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' "; 
			}*/
			
			if($restaurent_id !=''){
				$where .= " `res_id` = '".$restaurent_id."' AND ";
			}
			if($customer_id !=''){
				$where .= " `cust_id` = '".$customer_id."' AND ";
			}
			if($delivery_type !=''){
				$_delivery_type = implode("') OR (`delivery_type` ='",$delivery_type);
				$where .= " ((`delivery_type` = '".$_delivery_type."')) AND ";
			}
			if($deliveryboy_id !=''){
				$where .= " `ob`.`boy_id` = '".$deliveryboy_id."' AND ";
			}
			/*for franchise and super admin*/
			if(session()->get('gid') == '1'){
			  if($status !=''){
				$_status = implode("') OR (`status` ='",$status);
				$where .= "((`status` = '".$_status."')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' ";
			  }else{
				$where .= "((`status` = '4') OR (`status` = '5') OR (`status` = '6') OR (`status` = '7') OR (`status` = '8') OR (`status` = '9') OR (`status` = '10') OR (`status` = '11')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' ";
			  }
			}elseif(session()->get('gid') == '7'){
			  if($status !=''){
				$_status = implode("') OR (`status` ='",$status);
				$where .= "((`status` = '".$_status."')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' AND `res`.`region` = '".session()->get('rkey')."'";
			  }else{
				$where .= "((`status` = '4') OR (`status` = '5') OR (`status` = '6') OR (`status` = '7') OR (`status` = '8') OR (`status` = '9') OR (`status` = '10') OR (`status` = '11')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' AND `res`.`region` = '".session()->get('rkey')."'";	
			  }
			}
			
			$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region`,`tb`.`first_name`,`tb`.`last_name` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `tb_users` AS `tb` ON `od`.`cust_id` = `tb`.`id` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." ORDER BY `od`.`id` DESC");  
			
						
			$countries = $results; //json_decode(json_encode($results), true);
						
			$tot_record_found=0;
				if(count($countries)>0){
					$tot_record_found=1;
					 
					$CsvData=array('S.No~Order Id~Res Name~Cust Name~Boy Name~Item Total~Res Offer Price~Res Coupon Price~DS Coupon Price~HGST~DGST~Delivery Charge~Packaging Charge~Grand Total~Date and Time~MOP~Delivery Type~status~Order details');
					$i = 1;					 
					foreach($countries as $value){
						//if($customer_id)
//						{
//							$cname = \SiteHelpers::hostname($customer_id);   /** customer name details **/
//							$custph = \SiteHelpers::getCustomerPhone($customer_id); 
//						}
//						else
//						{
//							$cname = \SiteHelpers::hostname($value->cust_id);   /** customer name details **/
//							$custph = \SiteHelpers::getCustomerPhone($value->cust_id); 
//						}
						// $res_detail = $model->resname($value->res_id);
						// $res_detail[0]->name;
						$offer = \SiteHelpers::getOfferPrice($value->res_id,$value->total_price,$value->date);
						date_default_timezone_set("Asia/Kolkata");
						//$bid = \SiteHelpers::getBoyid($value->orderid);
						if($value->boy_id !='')
						{
							$boyname = \SiteHelpers::getBoyname($value->boy_id);
						}
						else
						{
							$boyname = $value->boy_id; 
						}
						
						$addr_details =  $value->address;
						
						$status = $value->status;
						if($status == '4'){
							$order_status = "Order Finished";
						} elseif($status == '5'){
							$order_status = "Rejected by Restaurant";
						} elseif($status == '6'){
							$order_status = "Rejected by Admin";
						} elseif($status == '7'){
							$order_status = "Payment Pending";
						} elseif($status == '8'){
							$order_status = "Payment Aborted";
						} elseif($status == '9'){
							$order_status = "Payment Failure";
						} elseif($status == '10'){
							$order_status = "Order Canceled";
						} elseif($status == '11'){
							$order_status = "Order Returned";
						} 
						
						if($value->hd_gst == 1){
							$hgst = $value->s_tax;
							$dgst = 0;
						} else {
							$hgst = 0;
							$dgst = $value->s_tax;
						}
						if($value->coupon_type ==1){
							$res_coupon_price = $value->coupon_price;
							$ds_coupon_price = 0;
						} else if($value->coupon_type ==2){
							$res_coupon_price = 0;
							$ds_coupon_price = $value->coupon_price;
						} else {
							$res_coupon_price = 0;
							$ds_coupon_price = 0;
						}
						
						$CsvData[]=$i.'~'.$value->region.$value->id.'~'.$value->name.'~'.$value->first_name.' '.$value->last_name.'~'.$boyname.'~'.$value->total_price.'~'.$value->offer_price.'~'.$res_coupon_price.'~'.$ds_coupon_price.'~'.$hgst.'~'.$dgst.'~'.$value->delivery_charge.'~'.$value->packaging_charge.'~'.$value->grand_total.'~'.date('m/d/Y H:i:s',$value->time).'~'.$value->mop.'~'.$value->delivery_type.'~'.$order_status.'~'.$value->order_details; 
						$i++;
					}
					
					$filename = "Reports_".date('Y-m-d-H:i:s').".csv";
					$file_path = base_path().'/export_download/'.$filename;   
					$file = fopen($file_path,"w+");
					foreach ($CsvData as $exp_data){
					  fputcsv($file,explode('~',$exp_data));
					}   
					fclose($file);          
			 
					$headers = ['Content-Type' => 'application/csv'];
					return response()->download($file_path,$filename,$headers );
				} else {
					return Redirect::to('cashondeliveryorder/reports/')->with('messagetext','No Records Found')->with('msgstatus','error')
			->withErrors($validator)->withInput();
				}		
			
		} else {

			return Redirect::to('cashondeliveryorder/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}
	/** delivery order view end **/	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_cashondeliveryorder');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'cashondeliveryorder/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'cashondeliveryorder?return='.self::returnUrl();
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

			return Redirect::to('cashondeliveryorder/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('cashondeliveryorder')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('cashondeliveryorder')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	
	
	
	
	function postReportview( Request $request)
	{
		//echo "dsfdsf";  exit;
	    $region_key = $_POST['regionid'];
		$restaurent_id = $_POST['resname'];
		$customer_id = $_POST['cusname'];		
		$delivery_type = $_POST['deltype'];
		$deliveryboy_id = $_POST['delboy'];
		$status = $_POST['status'];
		$report_from_date = date('Y-m-d',strtotime($_POST['from_date']));
		$report_to_date = date('Y-m-d',strtotime($_POST['to_date']));
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
		if($region_key != ''){
		$reg .= " AND `res`.`region` = '".$region_key."'";
		}else{
		$reg .= " ";	
		}
	
			
			
			if($restaurent_id !=''){
				$where .= " `res_id` = '".$restaurent_id."' AND ";
			}
			if($customer_id !=''){
				$where .= " `cust_id` = '".$customer_id."' AND ";
			}
			if($delivery_type !=''){
				$_delivery_type = implode("') OR (`delivery_type` ='",$delivery_type);
				$where .= " ((`delivery_type` = '".$_delivery_type."')) AND ";
			}
			if($deliveryboy_id !=''){
				$where .= " `ob`.`boy_id` = '".$deliveryboy_id."' AND ";
			}
	       	/*for franchise and super admin*/
		    if(session()->get('gid') == '1'){
			  if($status !=''){
				$_status = implode("') OR (`status` ='",$status);
				$where .= "((`status` = '".$_status."')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."'".$reg."";
			  }else{
				$where .= "((`status` = '4') OR (`status` = '5') OR (`status` = '6') OR (`status` = '7') OR (`status` = '8') OR (`status` = '9') OR (`status` = '10') OR (`status` = '11')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."'".$reg."";
			  }
			}elseif(session()->get('gid') == '7'){
			  if($status !=''){
				$_status = implode("') OR (`status` ='",$status);
				$where .= "((`status` = '".$_status."')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' AND `res`.`region` = '".session()->get('rkey')."'";
			  }else{
				$where .= "((`status` = '4') OR (`status` = '5') OR (`status` = '6') OR (`status` = '7') OR (`status` = '8') OR (`status` = '9') OR (`status` = '10') OR (`status` = '11')) AND `date` BETWEEN '".$report_from_date."' AND  '".$report_to_date."' AND `res`.`region` = '".session()->get('rkey')."'";	
			  }
			}
			
			$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region`,`tb`.`first_name`,`tb`.`last_name` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `tb_users` AS `tb` ON `od`.`cust_id` = `tb`.`id` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." ORDER BY `od`.`id` DESC"); 
		
			//print_r($results); 
			
			$countries = $results; //json_decode(json_encode($results), true);
						
			$tot_record_found=0;
			if(count($countries)>0){
				$tot_record_found=1;
			
				$amount .= '<table style="overflow-x:auto!important;">
					 
				<tr>
					<th width="">S.No</th>
					<th>Order Id</th>
					<th>Res Name</th>
					<th>Cus Name</th>
					<th>Delivery Boy Name</th>
					<th>Item Total</th>	 
					<th>Res Offer Price</th>
					<th>Res Coupon Price</th>
					<th>DS Coupon Price</th>
					<th>HGST</th>
					<th>DGST</th>	
					<th>Delivery Charge</th>
					<th>Packaging Charge</th>
					<th>Grand Total</th>
					<th>Date and Time</th>	
					<th>MOP</th>
					<th>Delivery Type</th>
					<th>Status</th>	 
					<th>Order details</th>			  
				</tr>';
									 
				 $i = 1;					 
				foreach($countries as $value){
					//if($customer_id)
//						{
//							$cname = \SiteHelpers::hostname($customer_id);   /** customer name details **/
//							$custph = \SiteHelpers::getCustomerPhone($customer_id); 
//						}
//						else
//						{
//							$cname = \SiteHelpers::hostname($value->cust_id);   /** customer name details **/
//							$custph = \SiteHelpers::getCustomerPhone($value->cust_id); 
//						}
					// $res_detail = $model->resname($value->res_id);
					// $res_detail[0]->name;
					$offer = \SiteHelpers::getOfferPrice($value->res_id,$value->total_price,$value->date);
					date_default_timezone_set("Asia/Kolkata");
					//$bid = \SiteHelpers::getBoyid($value->orderid);
					if($value->boy_id !='')
					{
						$boyname = \SiteHelpers::getBoyname($value->boy_id);
					}
					else
					{
						$boyname = $value->boy_id; 
					}
					
					$addr_details =  $value->address;
					
					$status = $value->status;
					if($status == '4'){
						$order_status = "Order Finished";
					} elseif($status == '5'){
						$order_status = "Rejected by Restaurant";
					} elseif($status == '6'){
						$order_status = "Rejected by Admin";
					} elseif($status == '7'){
						$order_status = "Payment Pending";
					} elseif($status == '8'){
						$order_status = "Payment Aborted";
					} elseif($status == '9'){
						$order_status = "Payment Failure";
					} elseif($status == '10'){
						$order_status = "Order Canceled";
					} elseif($status == '11'){
						$order_status = "Order Returned";
					}
					
					if($value->hd_gst ==1){
						$hgst = $value->s_tax;
						$dgst = 0;
					} else {
						$dgst = $value->s_tax;
						$hgst = 0;
					}
					if($value->coupon_type ==1){
						$res_coupon_price = $value->coupon_price;
						$ds_coupon_price = 0;
					} else if($value->coupon_type ==2){
						$res_coupon_price = 0;
						$ds_coupon_price = $value->coupon_price;
					} else {
						$res_coupon_price = 0;
						$ds_coupon_price = 0;
					}
			
					$amount .= '<tr>';
		
					$amount .= '<td>'.$i.'</td>';
					$amount .= '<td >'.'#'.$value->region.$value->id.'</td>';	
					$amount .= '<td>'.$value->name.'</td>';
					$amount .= '<td>'.$value->first_name.' '.$value->last_name.'</td>';
					$amount .= '<td>'.$boyname.'</td>';
					$amount .= '<td>'.$value->total_price.'</td>';
					$amount .= '<td>'.$value->offer_price.'</td>';
					$amount .= '<td>'.$res_coupon_price.'</td>';
					$amount .= '<td>'.$ds_coupon_price.'</td>';
					$amount .= '<td>'.$hgst.'</td>';
					$amount .= '<td>'.$dgst.'</td>';
					$amount .= '<td>'.$value->delivery_charge.'</td>';
					$amount .= '<td>'.$value->packaging_charge.'</td>';	
					$amount .= '<td>'.$value->grand_total.'</td>';
					$amount .= '<td>'.date('m/d/Y H:i:s',$value->time).'</td>';
					$amount .= '<td>'.$value->mop.'</td>';
					$amount .= '<td>'.$value->delivery_type.'</td>';
					$amount .= '<td >'.$order_status.'</td>';
					$amount .= '<td>'.$value->order_details.'</td>';
						
					$amount .= '</tr>';
					
					$cum_total += $value->total_price;
					$cum_offer += $value->offer_price;
					$cum_res += $res_coupon_price;
					$cum_ds += $ds_coupon_price;
					$cum_hgst += $hgst;
					$cum_dgst += $dgst;
					$cum_delivery_charge += $value->delivery_charge;
					$cum_packaging_charge += $value->packaging_charge;
					$cum_grand_total += $value->grand_total;
					$i++;
				}
			
			} else {
				$amount .= '<style="text-align: center";><b>No records found</b>';	
			}		
			
		}
		
		$amount .= '<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>'.$cum_total.'</td>
						<td>'.$cum_offer.'</td>
						<td>'.$cum_res.'</td>
						<td>'.$cum_ds.'</td>
						<td>'.$cum_hgst.'</td>
						<td>'.$cum_dgst.'</td>
						<td>'.$cum_delivery_charge.'</td>
						<td>'.$cum_packaging_charge.'</td>
						<td>'.$cum_grand_total.'</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>';
		
		$amount .= '</table>';	
		$amount .= '<style>
			table { width: 300%; }
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			th, td {	
				padding: 5px;
				text-align: left;
			}
			table {
				display: block;
				overflow-x: auto;
			}
			</style>';
		
		echo $amount;	
	}

    public function getCod( Request $request )
	{
		return view('cashondeliveryorder.cod');
	}
	
	public function getCcavenue( Request $request )
	{
		return view('cashondeliveryorder.ccavenue');
	}
	
	public function getCompletedorders( Request $request )
	{
		return view('cashondeliveryorder.completedorders');
	}
	
	function postCodview( Request $request)
	{
		$orderid = $_POST['id']; 
	    $method = $_POST['method'];   
	
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			if($orderid !=''){
				$where .= " `od`.`id` = '".$orderid."' AND ";
			}
			$where .= "((`status` = '4') OR (`status` = '6') OR (`status` = '10')) ";
			
		if(session()->get('gid') == '1'){
			if($method !=''){
			$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." AND `od`.`delivery_type`='ccavenue' ORDER BY `od`.`id` DESC"); 
			}else{
			$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." AND `od`.`delivery_type`='cod' ORDER BY `od`.`id` DESC"); 	
				
			}
			}elseif(session()->get('gid') == '7') {
			$region = session()->get('rkey');
				
			if($method !=''){
			$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." AND `od`.`delivery_type`='ccavenue' AND `res`.`region`='".$region."' ORDER BY `od`.`id` DESC"); 
			}else{
			
			$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." AND `od`.`delivery_type`='cod' AND `res`.`region`='".$region."' ORDER BY `od`.`id` DESC"); 	
				
			}	
		
			}
		
			//print_r($results); 
			
			$countries = $results; //json_decode(json_encode($results), true);
						
			$tot_record_found=0;
				if(count($countries)>0){
					$tot_record_found=1;
				
			 $amount .= '<table style="overflow-x:auto!important;">
                     
		<tr>
			<th class="weight-600">Order Id</th>
			<th class="weight-600">Cust Id</th>
			<th class="weight-600">Res Id</th>
			<th class="weight-600">Cust Name</th>
			<th class="weight-600">Res Name</th>
			<th class="weight-600">Item Total</th>	 
			<th class="weight-600">DS Offer Price</th>
			<th class="weight-600">Rest Offer Price</th>
			<th class="weight-600">GST</th>	
			<th class="weight-600">Delivery Charge</th>
			<th class="weight-600">Packaging Charge</th>
			<th class="weight-600">Grand Total</th>
			<th class="weight-600">Status</th>
			<th class="weight-600">Date</th>
			<th class="weight-600">Time</th>	 
			<th class="weight-600">Payment Status</th>	 
			<th class="weight-600">Delivery Type</th>
			<th class="weight-600">MOP</th>
			<th class="weight-600" style="width:183px">Order details</th>
			<th class="weight-600">Boy Id</th>	
			<th class="weight-600">Boy Name</th>
		
			
			  
		</tr>';
										 
										 
			foreach($countries as $value){
						if($customer_id)
						{
							$cname = \SiteHelpers::hostname($customer_id);   /** customer name details **/
						}
						else
						{
							$cname = \SiteHelpers::hostname($value->cust_id);   /** customer name details **/
						}
					
						$offer = \SiteHelpers::getOfferPrice($value->res_id,$value->total_price,$value->date);
						date_default_timezone_set("Asia/Kolkata");
					
						if($value->boy_id !='')
						{
							$boyname = \SiteHelpers::getBoyname($value->boy_id);
						}
						else
						{
							$boyname = $value->boy_id; 
						}
						
						
						$status = $value->status;
						if($status == '4'){
							$order_status = "Order Finished";
						} elseif($status == '6'){
							$order_status = "Rejected by Admin";
						} elseif($status == '10'){
							$order_status = "Order Canceled";
						} 
						
					
			
					 $amount .= '<tr>';
            
            $amount .= '<td style="width:1%">'.'#'.$value->region.$value->id.'</td>';
            $amount .= '<td>'.$value->cust_id.'</td>';
			$amount .= '<td>'.$value->res_id.'</td>';
            $amount .= '<td style="width:1%">'.$cname.'</td>';
			$amount .= '<td>'.$value->name.'</td>';
            $amount .= '<td>'.$value->total_price.'</td>';
			$amount .= '<td>'.$value->coupon_price.'</td>';
            $amount .= '<td>'.$offer.'</td>';
			$amount .= '<td>'.$value->s_tax.'</td>';
            $amount .= '<td>'.$value->delivery_charge.'</td>';
            $amount .= '<td>'.$value->packaging_charge.'</td>';	
			$amount .= '<td>'.$value->grand_total.'</td>';
			$amount .= '<td style="width:1%">'.$order_status.'</td>';
            $amount .= '<td>'.$value->date.'</td>';
			$amount .= '<td>'.date('H:i:s',$value->time).'</td>';
			$amount .= '<td>'.$value->delivery.'</td>';
			$amount .= '<td>'.$value->delivery_type.'</td>';
			$amount .= '<td>'.$value->mop.'</td>';	
			$amount .= '<td>'.$value->order_details.'</td>';
			$amount .= '<td>'.$value->boy_id.'</td>';	
            $amount .= '<td>'.$boyname.'</td>';
         

		
						
					$amount .= '</tr>';	
					
					}
	} else {
				$amount .= '<style="text-align: center";><b>No records found</b>';	
				}		
			
		}
	$amount .= '</table>';	
	$amount .= '<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
	 width: 300%;
}
th, td {
    padding: 15px;
    text-align: left;
}
table {
        display: block;
        overflow-x: auto;
    }


</style>';
					echo $amount;	
	
	}
	
	
	function postCompletedordersview( Request $request)
	{
		$orderid = $_POST['id']; 
	    $method = $_POST['method'];   
	
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			if($orderid !=''){
				$where .= " `od`.`id` = '".$orderid."' AND ";
			}
			$where .= "((`status` = '4') OR (`status` = '6') OR (`status` = '10')) ";
			
			if(session()->get('gid') == '1'){
			
				$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." ORDER BY `od`.`id` DESC");
			
			}elseif(session()->get('gid') == '7') {
				$region = session()->get('rkey');
			
				$results = \DB::select("SELECT `od`.*, `ob`.`boy_id`, `op`.`partner_id`,`op`.`order_details`, `res`.`name`, `res`.`region` FROM `abserve_order_details` as `od` LEFT JOIN `abserve_orders_boy` AS `ob` ON `od`.`id` = `ob`.`orderid` INNER JOIN `abserve_orders_partner` AS `op` ON `od`.`id` = `op`.`orderid` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE ".$where." AND `res`.`region`='".$region."' ORDER BY `od`.`id` DESC"); 				
		
			}		
			//print_r($results); 
			
			$countries = $results; //json_decode(json_encode($results), true);
						
			$tot_record_found=0;
			if(count($countries)>0){
				$tot_record_found=1;
				
			 	$amount .= '<table style="overflow-x:auto!important;">                     
				<tr>
					<th class="weight-600">Order Id</th>
					<th class="weight-600">Cust Id</th>
					<th class="weight-600">Res Id</th>
					<th class="weight-600">Cust Name</th>
					<th class="weight-600">Res Name</th>
					<th class="weight-600">Item Total</th>	 
					<th class="weight-600">Coupon Offer Price</th>
					<th class="weight-600">Rest Offer Price</th>
					<th class="weight-600">HGST</th>	
					<th class="weight-600">DGST</th>	
					<th class="weight-600">Delivery Charge</th>
					<th class="weight-600">Packaging Charge</th>
					<th class="weight-600">Grand Total</th>
					<th class="weight-600">Status</th>
					<th class="weight-600">Date</th>
					<th class="weight-600">Time</th>	 
					<th class="weight-600">Payment Status</th>	 
					<th class="weight-600">Delivery Type</th>
					<th class="weight-600">MOP</th>
					<th class="weight-600" style="width:183px">Order details</th>
					<th class="weight-600">Boy Id</th>	
					<th class="weight-600">Boy Name</th>
				</tr>';
										 
										 
				foreach($countries as $value){
					if($customer_id)
					{
						$cname = \SiteHelpers::hostname($customer_id);   /** customer name details **/
					}
					else
					{
						$cname = \SiteHelpers::hostname($value->cust_id);   /** customer name details **/
					}
				
					$offer = \SiteHelpers::getOfferPrice($value->res_id,$value->total_price,$value->date);
					date_default_timezone_set("Asia/Kolkata");
				
					if($value->boy_id !='')
					{
						$boyname = \SiteHelpers::getBoyname($value->boy_id);
					}
					else
					{
						$boyname = $value->boy_id; 
					}					
					
					$status = $value->status;
					if($status == '4'){
						$order_status = "Order Finished";
					} elseif($status == '6'){
						$order_status = "Rejected by Admin";
					} elseif($status == '10'){
						$order_status = "Order Canceled";
					}
					if($value->hd_gst == 1){
						$hgst = $value->s_tax;
						$dgst = 0;
					} else {
						$dgst = $value->s_tax;
						$hgst = 0;
					}
					if($value->coupon_type ==1){
						$coupon_price = "Res - ".$value->coupon_price;
					} else if($value->coupon_type ==2){
						$coupon_price = "DS - ".$value->coupon_price;
					} else {
						$coupon_price = $value->coupon_price;
					}
			
					$amount .= '<tr>';
            
					$amount .= '<td style="width:1%">'.'#'.$value->region.$value->id.'</td>';
					$amount .= '<td>'.$value->cust_id.'</td>';
					$amount .= '<td>'.$value->res_id.'</td>';
					$amount .= '<td style="width:1%">'.$cname.'</td>';
					$amount .= '<td>'.$value->name.'</td>';
					$amount .= '<td>'.$value->total_price.'</td>';
					$amount .= '<td>'.$coupon_price.'</td>';
					$amount .= '<td>'.$offer.'</td>';
					$amount .= '<td>'.$hgst.'</td>';
					$amount .= '<td>'.$dgst.'</td>';
					$amount .= '<td>'.$value->delivery_charge.'</td>';
					$amount .= '<td>'.$value->packaging_charge.'</td>';	
					$amount .= '<td>'.$value->grand_total.'</td>';
					$amount .= '<td style="width:1%">'.$order_status.'</td>';
					$amount .= '<td>'.$value->date.'</td>';
					$amount .= '<td>'.date('H:i:s',$value->time).'</td>';
					$amount .= '<td>'.$value->delivery.'</td>';
					$amount .= '<td>'.$value->delivery_type.'</td>';
					$amount .= '<td>'.$value->mop.'</td>';	
					$amount .= '<td>'.$value->order_details.'</td>';
					$amount .= '<td>'.$value->boy_id.'</td>';	
					$amount .= '<td>'.$boyname.'</td>';	
						
					$amount .= '</tr>';	
					
				}
			} else {
				$amount .= '<style="text-align: center";><b>No records found</b>';	
			}		
			
		}
		$amount .= '</table>';	
		$amount .= '<style>
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				 width: 300%;
			}
			th, td {
				padding: 15px;
				text-align: left;
			}
			table {
					display: block;
					overflow-x: auto;
				}
			</style>';
		
		echo $amount;	
	
	}

}
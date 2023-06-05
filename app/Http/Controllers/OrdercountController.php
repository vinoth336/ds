<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Ordercount;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class OrdercountController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'ordercount';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Ordercount();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'ordercount',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
				
		if(session()->get('gid') == '1'){
			$this->data['regions'] = Region::all();
		}elseif(session()->get('gid') == '7'){
			$this->data['regions'] = Region::where('id',session()->get('rkey'))->get();
		}

		
		return view('ordercount.index',$this->data);
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
				
		$this->data['access']		= $this->access;
		return view('ordercount.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		
		$this->data['access']		= $this->access;
		return view('ordercount.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
	
	}	

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		
	}
	
	function postOrdercount( Request $request)
	{
	
		$from_date = date('Y-m-d',strtotime($_POST['from_date']));
    	$to_date = date('Y-m-d',strtotime($_POST['to_date'])); 
			
		$boy_id	=	$_POST['deliveryboys'];
		$region	=	$_POST['region'];
			
		if($_POST['duration'] != '0'){ 
			if($_POST['duration'] == '1'){
				$cond .= "AND `date`= CURDATE()";
			}
			if($_POST['duration'] == '2'){
				$cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 WEEK)";
			}
			if($_POST['duration'] == '3'){
				$cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 MONTH)";
			}
			if($_POST['duration'] == '4'){
				if(isset($from_date) && ($to_date !='')){
					if(isset($from_date) && ($to_date !='')){
						$cond .= "AND `date` >= '".$from_date."' AND `date` <= '".$to_date."'";
					} 
				}					
			}
		}
		
		$amount .='<table id="" class="" width="100%" ">
                     
		<tr>
		<th class="weight-600">MODE OF PAYMENT</th>
		<th class="weight-600">TOTAL DELIVERED ORDERS</th>
		<th class="weight-600">TOTAL DELIVERED AMOUNT</th>
		</tr>
					 
		<tr>
		<td class="weight-600">COD</td>';
				
		if(session()->get('gid') == '1'){
			if($region == ''){
				$query = "SELECT `id`,`delivery_type`,`date`,`mop`,`grand_total` FROM `abserve_order_details` WHERE `status`='4' AND `delivery_type`='cod' AND `mop`='cash' ".$cond;
			} else {
				$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb`  ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='cod' AND `aod`.`mop`='cash' AND `tb`.`region`='".$region."'".$cond;
			}
		}elseif(session()->get('gid') == '7') {
			$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb`  ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='cod' AND `aod`.`mop`='cash' AND `tb`.`region`='".session()->get('rkey')."'".$cond;	
		}
		//$amount .= $query;
		$cod = \DB::select($query);
		$amount .= '<td>'.count($cod).'</td>';
		foreach($cod as $key=>$today_orders)
		{			   
			$grand_total_cod += ($today_orders->grand_total);
		}
		if($grand_total_cod == ''){
			$grand_total_cod = 0;	
		}
		$amount .= '<td>'.$grand_total_cod.'</td></tr>';
	

		$amount .= '<tr>
				  <td class="weight-600">CCAVENUE</td>';
		
		
		if(session()->get('gid') == '1'){
			if($region == ''){
				$query = "SELECT `id`,`delivery_type`,`date`,`mop`,`grand_total` FROM `abserve_order_details` WHERE `status`='4' AND `delivery_type`='ccavenue' ".$cond;
			} else {
				$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb`  ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='ccavenue' AND `tb`.`region`='".$region."'".$cond;
			}
		}elseif(session()->get('gid') == '7') {
			$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb`  ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='ccavenue' AND `tb`.`region`='".session()->get('rkey')."'".$cond;	
		}
		
		$ccavenue = \DB::select($query);
		$amount .= '<td>'.count($ccavenue).'</td>';
		foreach($ccavenue as $key=>$today_orders)
		{			   
			$grand_total_ccavenue += ($today_orders->grand_total);
		}
		if($grand_total_ccavenue == ''){
			$grand_total_ccavenue = 0;	
		}
		$amount .= '<td>'.$grand_total_ccavenue.'</td></tr>';

		$amount .= '<tr>
				  <td class="weight-600">TEZ</td>';
				  
		
		if(session()->get('gid') == '1'){
			if($region == ''){
				$query = "SELECT `id`,`delivery_type`,`date`,`mop`,`grand_total` FROM `abserve_order_details` WHERE `status`='4' AND `delivery_type`='cod' AND `mop`='tez' ".$cond;
			} else {
				$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb`  ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='cod' AND `aod`.`mop`='tez'  AND `tb`.`region`='".$region."'".$cond;
			}
		}elseif(session()->get('gid') == '7') {
			$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb`  ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='cod' AND `aod`.`mop`='tez'  AND `tb`.`region`='".session()->get('rkey')."'".$cond;	
		}
		
	
		$tez = \DB::select($query);	
		$amount .= '<td>'.count($tez).'</td>';
		foreach($tez as $key=>$today_orders)
		{			   
			$grand_total_tez += ($today_orders->grand_total);				   
		}
		if($grand_total_tez == ''){
			$grand_total_tez = 0;
		}
		$amount .= '<td>'.$grand_total_tez.'</td></tr>';
	
		$amount .= '<tr>
				  <td class="weight-600">PAYTM</td>';
		
		
		if(session()->get('gid') == '1'){
			if($region == ''){
				$query = "SELECT `id`,`delivery_type`,`date`,`mop`,`grand_total` FROM `abserve_order_details` WHERE `status`='4' AND `delivery_type`='cod' AND `mop`='paytm' ".$cond;
			} else {
				$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb` ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='cod' AND `aod`.`mop`='paytm'  AND `tb`.`region`='".$region."'".$cond;
			}
		}elseif(session()->get('gid') == '7') {
			$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb` ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='4' AND `aod`.`delivery_type`='cod' AND `aod`.`mop`='paytm'  AND `tb`.`region`='".session()->get('rkey')."'".$cond;
		}
		
		
		$paytm = \DB::select($query);
		$amount .= '<td>'.count($paytm).'</td>';	
		foreach($paytm as $key=>$today_orders)
		{			   
			$grand_total_paytm += ($today_orders->grand_total);
		}	
		if($grand_total_paytm == ''){
			$grand_total_paytm = 0;	
		}			
		$amount .= '<td>'.$grand_total_paytm.'</td></tr>';

		$amount .= '<tr>
				  <td class="weight-600">TOTAL COUNT & VALUE</td>';
				
		$total_orders_delivered = count($cod)+count($ccavenue)+count($paytm)+count($tez);
		$total_orders_delivered_amount = $grand_total_cod+$grand_total_ccavenue+$grand_total_tez+$grand_total_paytm;	
		$amount .= '<td>'.$total_orders_delivered.'</td>';
		$amount .= '<td>'.$total_orders_delivered_amount.'</td></tr>';

		$amount .='                     
		<tr>
		<th class="weight-600">MODE OF PAYMENT</th>
		<th class="weight-600">TOTAL RETURNED ORDERS</th>
		<th class="weight-600">TOTAL RETURNED AMOUNT</th>
		</tr>

		<tr>
		<td class="weight-600">COD</td>';
		
		
		if(session()->get('gid') == '1'){
			if($region == ''){
				$query = "SELECT `id`,`delivery_type`,`date`,`mop`,`grand_total` FROM `abserve_order_details` WHERE `status`='11' AND `delivery_type`='cod' ".$cond;
			} else {
				$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb` ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='11' AND `aod`.`delivery_type`='cod' AND `tb`.`region`='".$region."'".$cond;	
			}
		}elseif(session()->get('gid') == '7') {
			$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb` ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='11' AND `aod`.`delivery_type`='cod' AND `tb`.`region`='".session()->get('rkey')."'".$cond;	
		}
		
		$return_cod = \DB::select($query);	
		$amount .= '<td>'.count($return_cod).'</td>';	
		foreach($return_cod as $key=>$today_orders)
		{			   
		   $grand_return_cod += ($today_orders->grand_total);
		}
		if($grand_return_cod == ''){
			$grand_return_cod = 0;	
		}
		
		$amount .= '<td>'.$grand_return_cod.'</td></tr>';
		
		$amount .= '<tr>
				  <td class="weight-600">CCAVENUE</td>';
		
			
		if(session()->get('gid') == '1'){
			if($region == ''){
				$query = "SELECT `id`,`delivery_type`,`date`,`mop`,`grand_total` FROM `abserve_order_details` WHERE `status`='11' AND `delivery_type`='ccavenue' ".$cond;
			} else {
				$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb` ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='11' AND `aod`.`delivery_type`='ccavenue' AND `tb`.`region`='".$region."'".$cond;
			}
		}elseif(session()->get('gid') == '7') {
			$query = "SELECT `aod`.`id`,`aod`.`delivery_type`,`aod`.`date`,`aod`.`mop`,`aod`.`grand_total` FROM `abserve_order_details` as `aod` JOIN `abserve_restaurants` as `tb` ON `aod`.`res_id`=`tb`.`id` WHERE `aod`.`status`='11' AND `aod`.`delivery_type`='ccavenue' AND `tb`.`region`='".session()->get('rkey')."'".$cond;	
		}
		
		$return_ccavenue = \DB::select($query);
		$amount .= '<td>'.count($return_ccavenue).'</td>';
		foreach($return_ccavenue as $key=>$today_orders)
		{			   
		   $grand_return_ccaveneue += ($today_orders->grand_total);
		}		
		
		if($grand_return_ccaveneue == ''){
			$grand_return_ccaveneue = 0;	
		}
		$amount .= '<td>'.$grand_return_ccaveneue.'</td></tr>';		

		$amount .= '<tr>
				  <td class="weight-600">TOTAL COUNT & VALUE</td>';
				
		$total_orders_returned = count($return_cod)+count($return_ccavenue);
		$total_orders_returned_amount = $grand_return_cod+$grand_return_ccaveneue;	
		$amount .= '<td>'.$total_orders_returned.'</td>';
		$amount .= '<td>'.$total_orders_returned_amount.'</td></tr></table>';

		$amount .= '<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		th, td {
			padding: 5px;
			text-align: left;
		}
		</style>';
		echo $amount;
		//return view('deliveryordercount.index');
	}


}
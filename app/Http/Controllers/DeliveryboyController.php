<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Deliveryboy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
use DB;
use Datatables; 


class DeliveryboyController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'deliveryboy';
	static $per_page	= '50';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Deliveryboy();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'deliveryboy',
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
		
		// Get Query for admin and franchise
		
			 $results = $this->model->getRows( $params );
		     $this->data['rowData']		= $results['rows'];
	
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('deliveryboy');
		
		//$this->data['rowData']		= $results['rows'];
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
		return view('deliveryboy.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_deliveryboys'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('deliveryboy.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_deliveryboys'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('deliveryboy.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$id=$request->input('id');
		$rules = $this->validateForm();
		if($request->input('id') =='')
		{
			$rules['password'] 				= 'required|between:6,12';
			$rules['password_confirmation'] = 'required|between:6,12';
			$rules['email'] 				= 'required|email|unique:abserve_deliveryboys';
			$rules['username'] 				= 'required|alpha_num||min:2|unique:abserve_deliveryboys';
			
		} else {
			if($request->input('password') !='')
			{
				$rules['password'] 				='required|between:6,12';
				$rules['password_confirmation'] ='required|between:6,12';
				$rules['password_confirmation'] ='required|same:password';			
			}
		}
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_deliveryboy');

			if($request->input('id') =='')
			{
				$data['password'] = \Hash::make(Input::get('password'));
			} else {
				if(Input::get('password') !='')
				{
					$data['password'] = \Hash::make(Input::get('password'));
				} else {
					unset($data['password']);
				}
			}

			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				/*$return = 'deliveryboy/update/'.$id.'?return='.self::returnUrl();*/
				$return = 'deliveryboy/update';
			} else {
				$return = 'deliveryboy?return='.self::returnUrl();
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

			return Redirect::to('deliveryboy/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('deliveryboy')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('deliveryboy')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}			


public function getMap(Request $request )
	{
		
	return view('deliveryboy.map',$this->data);
	
	}

public function getMapdetails( Request $request)
	{
		
		
function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

		
header("Content-type: text/xml");

// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<markers>';
// Iterate through the rows, printing XML nodes for each
		if(session()->get('gid') == '1'){
		  $deliveryboy = \DB::select("SELECT *  FROM `abserve_deliveryboys` ");
		}elseif(session()->get('gid') == '7'){
		  $deliveryboy = \DB::select("SELECT *  FROM `abserve_deliveryboys` WHERE `region`='".session()->get('rid')."'");
		}	

foreach($deliveryboy as $deliveryboys)  {
if($deliveryboys->online_sts == "1"){
  echo '<marker ';
  echo 'id="' . $deliveryboys->id . '" ';
  echo 'name="' . parseToXML($deliveryboys->username) . '" ';
  echo 'address="' . parseToXML($deliveryboys->address) . '" ';
  echo 'lat="' . $deliveryboys->latitude . '" ';
  echo 'lng="' . $deliveryboys->longitude . '" ';
  echo '/>';
}
}

// End XML file
echo '</markers>';		
		
	exit;	
	
		}			


 public function getOrder(Request $request )
	{
		
	return view('deliveryboy.order',$this->data);
	
	}
		
		
		
		
		
		function postOrdercount( Request $request)
	{
	
	
	$from_date = date('Y-m-d',strtotime($_POST['from_date']));
    $to_date = date('Y-m-d',strtotime($_POST['to_date'])); 
			
			$boy_id	=	$_POST['deliveryboys']; 
			
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
					
			$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`od`.`mop`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='cod' AND `od`.`mop`='cash' ".$cond;
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
					
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='ccavenue' ".$cond;
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
					
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='cod' AND `od`.`mop`='tez' ".$cond;
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
					
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='4' AND `od`.`delivery_type`='cod' AND `od`.`mop`='paytm' ".$cond;
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
					
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='11' AND `od`.`delivery_type`='cod' ".$cond;
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
					
		$query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`op`.`order_status`,`od`.`date`,`grand_total` FROM `abserve_orders_boy` AS `op` JOIN `abserve_order_details` AS `od` ON `od`.`id` = `op`.`orderid` WHERE `op`.`boy_id`='".$boy_id."' AND `op`.`order_status`='11' AND `od`.`delivery_type`='ccavenue' ".$cond;
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
	
	

public function getDeliveryboy(){ 


		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
		$deliveryboy = DB::table('abserve_deliveryboys')->select(['abserve_deliveryboys.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','abserve_deliveryboys.region','=','region.id')->where('region',$region);
		} else {
			$region = \Session::get('rid');
		$deliveryboy = DB::table('abserve_deliveryboys')->select(['abserve_deliveryboys.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','abserve_deliveryboys.region','=','region.id');
		}
        return Datatables::of($deliveryboy)
            ->addColumn('action', function ($deliveryboy) {		
				return '<a href="deliveryboy/show/'.$deliveryboy->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="deliveryboy/update/'.$deliveryboy->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }							

}
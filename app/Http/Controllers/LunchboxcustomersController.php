<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Lunchboxcustomers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
use DB;
use Datatables;
//use Yajra\Datatables\Datatables;


class LunchboxcustomersController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'lunchboxcustomers';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Lunchboxcustomers();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'lunchboxcustomers',
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
		$pagination->setPath('lunchboxcustomers');
		
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
	
		
		return view('lunchboxcustomers.index',$this->data);
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
			
			$lb_student = \DB::table('lunch_box_student_info')->where('cust_id',$id)->get();
			$this->data['stud_info'] =  $lb_student;
			
		} else {
			$this->data['row'] = $this->model->getColumnTable('lunch_box_customers'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('lunchboxcustomers.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('lunch_box_customers'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('lunchboxcustomers.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_lunchboxcustomers');
			
			$secondary_number = $request->secondary_number;
			if($request->id !=''){				
				$cust = \DB::table('lunch_box_customers')->where('secondary_number',$secondary_number)->first();
				if($cust->secondary_number ==''){
					$data['phone_change_status'] = 1;
				}
			} /*else {
				$cust = array();
			}
			
			print_r($cust);
			exit;*/
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'lunchboxcustomers/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'lunchboxcustomers?return='.self::returnUrl();
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

			return Redirect::to('lunchboxcustomers/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('lunchboxcustomers')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('lunchboxcustomers')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

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
			$this->data['zones'] =\DB::select( "SELECT * FROM zone WHERE region='".$region_id."'");
		}else{
			$this->data['zones'] =\DB::select( "SELECT * FROM zone");	
		}
		
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('abserve_order_details'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('lunchboxcustomers.lbreport',$this->data);
	}
	
	function postLunchboxreport( Request $request)
	{
		$zone = $_POST['zone'];
		$no_pickup = $_POST['no_pickup'];
		$today = date('Y-m-d');
		
		$results = \DB::select("SELECT *,lunch_box_student_info.id as sid,zone.name as zone_name from lunch_box_customers INNER JOIN lunch_box_student_info ON lunch_box_customers.id = lunch_box_student_info.cust_id INNER JOIN delivery_point ON lunch_box_student_info.school_id = delivery_point.id INNER JOIN zone ON lunch_box_student_info.zone = zone.id where lunch_box_student_info.zone = '".$zone."' AND lunch_box_student_info.payment_status = 1 AND lunch_box_student_info.plan_from <= '".$today."' AND lunch_box_student_info.plan_to >= '".$today."' GROUP BY lunch_box_student_info.id ORDER BY lunch_box_customers.id Desc");
		
				
				//echo '<pre>'; print_r($results); //die;	
				if(count($results)>0){
		
				$amount .= '<table style="overflow-x:auto!important;">
					 
				<tr>
					<th width="">S.No</th>
					<th>Name</th>
					<th>Primary Number</th>	 
					<th>Secondary Number</th>
					<th>Pickup Address</th>
					<th>Pickup Time</th>
					<th>Return Time</th>  
					<th>Student Name</th>
					<th>School Name</th>
					<th>Standard & Section</th>
					<th>Delivery Address</th>		
					<th>Zone</th>
				</tr>';
									 
				$i = 1;
				if($no_pickup == "pickup"){
					
					foreach($results as $value){
						
						$check_stat = $this->CheckLeaveDays($value->sid);
						if($check_stat != 'out'){
						//echo $value->leave_date;
					
						$amount .= '<tr>';
			
						$amount .= '<td>'.$i.'</td>';
						$amount .= '<td>'.$value->first_name.'</td>';
						$amount .= '<td>'.$value->primary_number.'</td>';
						$amount .= '<td>'.$value->secondary_number.'</td>';
						$amount .= '<td>'.$value->pickup_address.'</td>';
						$amount .= '<td>'.$value->pickup_time.'</td>';
						$amount .= '<td>'.$value->return_time.'</td>';
						$amount .= '<td>'.$value->stud_name.'</td>';
						$amount .= '<td>'.$value->name.'</td>';
						$amount .= '<td>'.$value->standard.'&nbsp;-&nbsp;'.$value->section.'</td>';
						$amount .= '<td>'.$value->location.'</td>';
						$amount .= '<td>'.$value->zone_name.'</td>';
							
						$amount .= '</tr>';
						
						$i++;
						}
					}
				
				} else {
					
					foreach($results as $value){
						
						$check_stat = $this->CheckLeaveDays($value->sid);
						if($check_stat == 'out'){
						//echo $value->leave_date;
					
						$amount .= '<tr>';
			
						$amount .= '<td>'.$i.'</td>';
						$amount .= '<td>'.$value->first_name.'</td>';
						$amount .= '<td>'.$value->primary_number.'</td>';
						$amount .= '<td>'.$value->secondary_number.'</td>';
						$amount .= '<td>'.$value->pickup_address.'</td>';
						$amount .= '<td>'.$value->pickup_time.'</td>';
						$amount .= '<td>'.$value->return_time.'</td>';
						$amount .= '<td>'.$value->stud_name.'</td>';
						$amount .= '<td>'.$value->name.'</td>';
						$amount .= '<td>'.$value->standard.'&nbsp;-&nbsp;'.$value->section.'</td>';
						$amount .= '<td>'.$value->location.'</td>';
						$amount .= '<td>'.$value->zone_name.'</td>';
							
						$amount .= '</tr>';
						
						$i++;
						}
					}
				}
			
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
		}else{
			$amount = '<style="text-align: center";><b>No records found</b>';	
			echo $amount;
		}
		
	}
	
	function postReportcustomers( Request $request)
	{
		$zone = $_POST['zone_id'];
		$no_pickup = $_POST['no_pickup'];
		$today = date('Y-m-d');
		
		$results = \DB::select("SELECT *,lunch_box_student_info.id as sid,zone.name as zone_name from lunch_box_customers INNER JOIN lunch_box_student_info ON lunch_box_customers.id = lunch_box_student_info.cust_id INNER JOIN delivery_point ON lunch_box_student_info.school_id = delivery_point.id INNER JOIN zone ON lunch_box_student_info.zone = zone.id where lunch_box_student_info.zone = '".$zone."' AND lunch_box_student_info.payment_status = 1 AND lunch_box_student_info.plan_from <= '".$today."' AND lunch_box_student_info.plan_to >= '".$today."' GROUP BY lunch_box_student_info.id ORDER BY lunch_box_customers.id Desc");
		
		$CsvData=array('S.No~Name~Primary Number~Secondary Number~Pickup Address~Pickup Time~Return Time~Student Name~School Name~Standard & Section~Delivery Address~Zone');//~Grand Total~Date and Time~MOP~Delivery Type~status~Order details
		
		$i = 1;	
		if($no_pickup == "pickup"){				 
			foreach($results as $value){
				$check_stat = $this->CheckLeaveDays($value->sid);
				if($check_stat != 'out'){
			
				$std = $value->standard.' - '.$value->section;
					
			$CsvData[]=$i.'~'.$value->first_name.'~'.$value->primary_number.'~'.$value->secondary_number.'~'.$value->pickup_address.'~'.$value->pickup_time.'~'.$value->return_time.'~'.$value->stud_name.'~'.$value->name.'~'.$std.'~'.$value->location.'~'.$value->zone_name;//.'~'.$order_status.'~'.$value->order_details 
			
				$i++;
				}
			}
		} else {
			foreach($results as $value){
				$check_stat = $this->CheckLeaveDays($value->sid);
				if($check_stat == 'out'){
			
				$std = $value->standard.' - '.$value->section;
					
			$CsvData[]=$i.'~'.$value->first_name.'~'.$value->primary_number.'~'.$value->secondary_number.'~'.$value->pickup_address.'~'.$value->pickup_time.'~'.$value->return_time.'~'.$value->stud_name.'~'.$value->name.'~'.$std.'~'.$value->location.'~'.$value->zone_name;//.'~'.$order_status.'~'.$value->order_details 
			
				$i++;
				}
			}
		}
		$filename = "Lunchboxreports_".date('Y-m-d-H:i:s').".csv";
		$file_path = base_path().'/export_download/'.$filename;   
		$file = fopen($file_path,"w+");
		foreach ($CsvData as $exp_data){
		  fputcsv($file,explode('~',$exp_data));
		}   
		fclose($file);          
		
		$headers = ['Content-Type' => 'application/csv'];
		return response()->download($file_path,$filename,$headers );
	}
	
	public function CheckLeaveDays($cust_id){      	   
	  
	  $today = date('Y-m-d');
	  $results = \DB::select("SELECT * from lunchbox_leave_days where stud_id ='".$cust_id."'");
	 
	  foreach($results as $row){
		  
			  if($row->leave_date_from <= $today && $row->leave_date_to >= $today){
			$check_stat = 'out';  
			  }
		  
	   }
	  
	  return $check_stat;

    }
	
	public function getLunchboxcustomers( Request $request){ 
	  
	 $regionselect = \Session::get('regionselect');	
	
		DB::statement(DB::raw('set @rownum=0'));
		
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');	
		
		$lunchboxcustomers = DB::table('lunch_box_customers')->select(['lunch_box_customers.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','lunch_box_customers.region','=','region.id')->where('lunch_box_customers.region',$region);
		}else{
			
			if($regionselect){
			
			$lunchboxcustomers = DB::table('lunch_box_customers')->select(['lunch_box_customers.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','lunch_box_customers.region','=','region.id')->where('lunch_box_customers.region',$regionselect);;	
			
			}else{
				
			$lunchboxcustomers = DB::table('lunch_box_customers')->select(['lunch_box_customers.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','lunch_box_customers.region','=','region.id');		
				
			}
		}
        return Datatables::of($lunchboxcustomers)
            ->addColumn('action', function ($lunchboxcustomers) {
				return '<a href="lunchboxcustomers/show/'.$lunchboxcustomers->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="lunchboxcustomers/update/'.$lunchboxcustomers->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            //->editColumn('id', 'ID: {{$id}}')
			//->addIndexColumn()
            ->make(true);

    }			

}
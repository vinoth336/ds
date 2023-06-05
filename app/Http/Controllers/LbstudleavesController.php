<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Lbstudleaves;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;  
use DB;
use Datatables;


class LbstudleavesController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'lbstudleaves';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Lbstudleaves();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'lbstudleaves',
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
		$pagination->setPath('lbstudleaves');
		
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
		return view('lbstudleaves.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('lunchbox_leave_days'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('lbstudleaves.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('lunchbox_leave_days'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('lbstudleaves.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_lbstudleaves');
				
			/*$leave_date_from = $request->leave_date_from;
			$leave_date_to = $request->leave_date_to; exit;
			
			\DB::table('lunchbox_leave_days')->whereRaw('? between lunchbox_leave_days.leave_date_from and lunchbox_leave_days.leave_date_to', [$leave_date_to])->get();
			\DB::select("select * from `lunchbox_leave_days` where (`leave_date_from` = ".$leave_date_from." OR `leave_date_to` = ".$leave_date_to." OR (`leave_date_from` <= ".$leave_date_from." OR `leave_date_to` >= ".$leave_date_to.")");*/
			
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'lbstudleaves/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'lbstudleaves?return='.self::returnUrl();
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

			return Redirect::to('lbstudleaves/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('lbstudleaves')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('lbstudleaves')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}
	
	function getStuddetails(Request $request){
	
		$cust_id = $_REQUEST['cust_id'];
		$stud_info = \DB::table('lunch_box_student_info')->where('cust_id',$cust_id)->where('payment_status',1)->get();
		
		foreach($stud_info as $studinfo){
			
			$stud .= '<option value="'.$studinfo->id.'">'.$studinfo->stud_name.'</option>';
			
		}
		return $stud;
				
	}
	
	public function getLbstudleaves(){
	   	
        //return \Datatables::of(DB::table('abserve_food_cuisines'))->make(true);
		$current_date = date('Y-m-d');
		DB::statement(DB::raw('set @rownum=0'));
		
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');	
			
			$lbleavedays = DB::table('lunchbox_leave_days')->select(['lunchbox_leave_days.*','lunch_box_student_info.stud_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('lunch_box_student_info','lunchbox_leave_days.stud_id','=','lunch_box_student_info.id')->leftJoin('lunch_box_customers','lunchbox_leave_days.cust_id','=','lunch_box_customers.id')->where('lunch_box_customers.region',$region)->whereRaw('? between lunchbox_leave_days.leave_date_from and lunchbox_leave_days.leave_date_to', [$current_date]);
		}else{
			
			$lbleavedays = DB::table('lunchbox_leave_days')->select(['lunchbox_leave_days.*','lunch_box_student_info.stud_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('lunch_box_student_info','lunchbox_leave_days.stud_id','=','lunch_box_student_info.id')->where('lunchbox_leave_days.leave_date_from', '>=', $current_date);
			//->whereRaw('? between lunchbox_leave_days.leave_date_from and lunchbox_leave_days.leave_date_to', [$current_date]);
			//->orWhere('lunchbox_leave_days.leave_date_from', '>=', $current_date)->orWhere('lunchbox_leave_days.leave_date_to', '<=', $current_date)
			
		}
        return Datatables::of($lbleavedays)
            ->addColumn('action', function ($lbleavedays) {
				return '<a href="lbstudleaves/show/'.$lbleavedays->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="lbstudleaves/update/'.$lbleavedays->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            //->editColumn('id', 'ID: {{$id}}')
			//->addIndexColumn()
            ->make(true);

    }			


}
<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Lbstudent;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;


class LbstudentController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'lbstudent';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Lbstudent();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'lbstudent',
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
		$pagination->setPath('lbstudent');
		
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
		return view('lbstudent.index',$this->data);
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
			
			$stud_leave_date = \DB::table('lunchbox_leave_days')->where('stud_id',$id)->where('status',1)->first();
			$this->data['stud_leave_date'] =  $stud_leave_date;
			
		} else {
			$this->data['row'] = $this->model->getColumnTable('lunch_box_student_info'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('lbstudent.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('lunch_box_student_info'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('lbstudent.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		print_r($request->all()); //exit;
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_lbstudent');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			$leave_date_from = $request->input('leave_date_from');
			$leave_date_to = $request->input('leave_date_to');
			$cust_id = $request->input('cust_id');
			if($leave_date_from !=''){ 
				$leave_days = \DB::table('lunchbox_leave_days')->where('stud_id',$id)->where('leave_date_from',$leave_date_from)->where('leave_date_to',$leave_date_to)->first();
				
				if($leave_days == 0){
					$val['cust_id']=$cust_id;
					$val['stud_id']=$id;
					$val['leave_date_from']=$leave_date_from;
					$val['leave_date_to']=$leave_date_to;
					\DB::table('lunchbox_leave_days')->where('stud_id','=',$id)->update(['status'=>0]);
					\DB::table('lunchbox_leave_days')->insert($val);
				}
			}
			
			/*if(!is_null($request->input('apply')))
			{
				$return = 'lbstudent/update/'.$id.'?return='.self::returnUrl();
			} else {*/				
				//$return = 'lunchboxcustomers/update/'.$cust_id.'?return='.self::returnUrl();
				$return = 'lbstudent?return='.self::returnUrl();
			//}

			// Insert logs into database
			if($request->input('id') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('lbstudent/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('lbstudent')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('lbstudent')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}
	
	public function getLbstudent(){      	   
	   	
        //return \Datatables::of(DB::table('abserve_food_cuisines'))->make(true);
		DB::statement(DB::raw('set @rownum=0'));
		
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');	
		
		$lbstudent = DB::table('lunch_box_student_info')->select(['lunch_box_student_info.*','lunch_box_customers.first_name','lunch_box_customers.primary_number','lunch_box_customers.secondary_number','lunch_box_customers.region','zone.name as zone_name','delivery_point.name as school_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('lunch_box_customers','lunch_box_student_info.cust_id','=','lunch_box_customers.id')->leftJoin('zone','lunch_box_student_info.zone','=','zone.id')->leftJoin('delivery_point','lunch_box_student_info.school_id','=','delivery_point.id')->where('lunch_box_customers.region',$region);
		}else{
			
		$lbstudent = DB::table('lunch_box_student_info')->select(['lunch_box_student_info.*','lunch_box_customers.first_name','lunch_box_customers.primary_number','lunch_box_customers.secondary_number','lunch_box_customers.region','zone.name as zone_name','delivery_point.name as school_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('lunch_box_customers','lunch_box_student_info.cust_id','=','lunch_box_customers.id')->leftJoin('zone','lunch_box_student_info.zone','=','zone.id')->leftJoin('delivery_point','lunch_box_student_info.school_id','=','delivery_point.id');	
			
		}
        return Datatables::of($lbstudent)
            ->addColumn('action', function ($lbstudent) {
				return '<a href="lbstudent/show/'.$lbstudent->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="lbstudent/update/'.$lbstudent->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            //->editColumn('id', 'ID: {{$id}}')
			//->addIndexColumn()
            ->make(true);

    }			


}
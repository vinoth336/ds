<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Cumulativeorders;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;


class CumulativeordersController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'cumulativeorders';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Cumulativeorders();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'cumulativeorders',
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
		$results = array();//$this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('cumulativeorders');
		
		$this->data['rowData']		= $results['rows'];
		
		if(session()->get('gid') == '1'){
			$this->data['regions'] = Region::all();
		}elseif(session()->get('gid') == '7'){
			$this->data['regions'] = Region::where('id',session()->get('rkey'))->get();
		}
		
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
		return view('cumulativeorders.index',$this->data);
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
		return view('cumulativeorders.form',$this->data);
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
		return view('cumulativeorders.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_cumulativeorders');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'cumulativeorders/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'cumulativeorders?return='.self::returnUrl();
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

			return Redirect::to('cumulativeorders/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('cumulativeorders')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('cumulativeorders')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}

	public function getCumulativeorders(){   
	
		$regionselect = \Session::get('regionselect');
	   	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rkey');
			$region1 = \Session::get('rid');	
		
			$cumulativeorders = DB::table('abserve_order_details')->select(['abserve_order_details.*','abserve_orders_customer.order_details','tb_users.username','tb_users.region','tb_users.phone_number','abserve_restaurants.name','abserve_restaurants.region',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('abserve_orders_customer','abserve_order_details.id','=','abserve_orders_customer.orderid')->leftJoin('tb_users','abserve_order_details.cust_id','=','tb_users.id')->leftJoin('abserve_restaurants','abserve_order_details.res_id','=','abserve_restaurants.id')->where('abserve_restaurants.region',$region)->where(function($q) {
         	$q->where('abserve_order_details.status',7)
            ->orWhere('abserve_order_details.status',8)
			 ->orWhere('abserve_order_details.status',9);
      		});
			
		}else{
			
			if($regionselect){
				$cumulativeorders = DB::table('abserve_order_details')->select(['abserve_order_details.*','abserve_orders_customer.order_details','tb_users.username','tb_users.phone_number','abserve_restaurants.name','abserve_restaurants.region',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('abserve_orders_customer','abserve_order_details.id','=','abserve_orders_customer.orderid')->leftJoin('tb_users','abserve_order_details.cust_id','=','tb_users.id')->leftJoin('abserve_restaurants','abserve_order_details.res_id','=','abserve_restaurants.id')->where('abserve_restaurants.region',$regionselect)->where(function($q) {
				$q->where('abserve_order_details.status',7)
				->orWhere('abserve_order_details.status',8)
				 ->orWhere('abserve_order_details.status',9);
				});
			} else {
		
				$cumulativeorders = DB::table('abserve_order_details')->select(['abserve_order_details.*','abserve_orders_customer.order_details','tb_users.username','tb_users.phone_number','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('abserve_orders_customer','abserve_order_details.id','=','abserve_orders_customer.orderid')->leftJoin('tb_users','abserve_order_details.cust_id','=','tb_users.id')->leftJoin('abserve_restaurants','abserve_order_details.res_id','=','abserve_restaurants.id')->where(function($q) {
				$q->where('abserve_order_details.status',7)
				->orWhere('abserve_order_details.status',8)
				 ->orWhere('abserve_order_details.status',9);
				});
			}
		}
	
	    return Datatables::of($cumulativeorders)
            ->make(true);

    }							
	

}
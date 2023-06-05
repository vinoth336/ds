<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Orderupdateamount;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;

class OrderupdateamountController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'orderupdateamount';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Orderupdateamount();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'orderupdateamount',
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
		//$results = $this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('orderupdateamount');
		
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
		$region_id = session()->get('rid');
		if(\Auth::user()->group_id == 7) {
			$this->data['region'] =\DB::select( "SELECT * FROM region WHERE region='".$region_id."'");
		}else{
			$this->data['region'] =\DB::select( "SELECT * FROM region");	
		}
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
		// Render into template
		return view('orderupdateamount.index',$this->data);
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
		return view('orderupdateamount.form',$this->data);
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
		return view('orderupdateamount.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		//print_r($request->all());  exit;
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			$data = $this->validatePost('tb_orderupdateamount');
			$id = $this->model->insertRow($data , $request->input('id'));
			
			$val['orderid'] =$request->id;
			$val['total_price'] =$request->total_price;
			$val['s_tax'] =$request->s_tax;
			//$val['ds_commission'] =$request->ds_commission;
			$val['coupon_price'] =$request->coupon_price;
			$val['offer_price'] =$request->offer_price;
			$val['grand_total'] = $request->grand_total;
			$val['packaging_charge'] =$request->packaging_charge;
			$val['delivery_charge'] =$request->delivery_charge;
			$val['date_time'] =date("Y-m-d H:i:s");
			
			$ins=\DB::table('order_amount_update')->insert($val);
		
			
			if(!is_null($request->input('apply')))
			{
				$return = 'orderupdateamount/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'orderupdateamount?return='.self::returnUrl();
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

			return Redirect::to('orderupdateamount/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('orderupdateamount')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('orderupdateamount')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}
	
	
	public function getOrderupdateamount(){       	   
	   	
	  $regionselect1 = \Session::get('regionselect1');	
	
	$regionid = \DB::table('region')->select('*')->where("id",'=',$regionselect1)->first();
	$region_key = $regionid->region_keyword;	
		
		DB::statement(DB::raw('set @rownum=0'));
		
			if($regionselect1){
			 
		 $orderupdateamount = DB::table('abserve_order_details')->select(['abserve_order_details.*','abserve_restaurants.id as newid','abserve_restaurants.region',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('abserve_restaurants','abserve_order_details.res_id','=','abserve_restaurants.id')->where('abserve_restaurants.region','=',$region_key);
			
		
			}else{
				
				$orderupdateamount = DB::table('abserve_order_details')->select(['abserve_order_details.*',DB::raw('@rownum := @rownum + 1 AS rownum')]);
				
			}
		
   
        return Datatables::of($orderupdateamount)
            ->addColumn('action', function ($orderupdateamount) {		
				return '<a  href="/orderupdateamount/update/'.$orderupdateamount->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }										
			


}
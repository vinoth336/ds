<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;

class DiscountController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'discount';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Discount();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'discount',
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
		$pagination->setPath('discount');
		
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
		return view('discount.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('coupon'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('discount.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('coupon'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('discount.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		//print_r($request->all()); exit;
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_discount');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				/*$return = 'discount/update/'.$id.'?return='.self::returnUrl();*/
				$return = 'discount/update';
			} else {
				$return = 'discount?return='.self::returnUrl();
			}
			
			$coupon = array("res_id"=>$_REQUEST['res_id'],"coupon_name"=>$_REQUEST['coupon_name'],"coupon_code"=>$_REQUEST['coupon_code'],"coupon_desc"=>$_REQUEST['coupon_desc'],"coupon_use_type"=>$_REQUEST['coupon_use_type'],"offer_type"=>$_REQUEST['offer_type'],"offer"=>$_REQUEST['offer'],"offer_from"=>$_REQUEST['offer_from'],"offer_to"=>$_REQUEST['offer_to']);

			// Insert logs into database
			if($request->input('id') =='')
			{
				//All Offers Table Values
				$couponid = array("coupon_id"=>$id,"offer_name"=>"Coupon");
				$result = array_merge($coupon, $couponid);
				\DB::table('offers')->insertGetId($result);
				
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				//All Offers Table Values
				$couponid = array("offer_name"=>"Coupon");
				$result = array_merge($coupon, $couponid);
				\DB::table('offers')->where('coupon_id','=',$id)->where('offer_name','=','Coupon')->update($result);
				
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('discount/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			
			$ids = $request->input('ids');
			foreach($ids as $id){
				$deleted = \DB::table('offers')->where('coupon_id', $id)->delete();
			}			
        	//$ids = implode(",", $request->input('ids'));		
			//$deleted = \DB::table('offers')->whereIn('coupon_id', [$ids])->delete();
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('discount')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('discount')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}
	

		
		public function getDiscount(){       	   
	   	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
		$discount = DB::table('coupon')->select(['coupon.*','region.region_name','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','coupon.region','=','region.id')->leftJoin('abserve_restaurants','coupon.res_id','=','abserve_restaurants.id')->where('coupon.region',$region);
		}else{
		$discount = DB::table('coupon')->select(['coupon.*','region.region_name','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','coupon.region','=','region.id')->leftJoin('abserve_restaurants','coupon.res_id','=','abserve_restaurants.id');
		}
        return Datatables::of($discount)
            ->addColumn('action', function ($discount) {		
				return '<a href="discount/show/'.$discount->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="discount/update/'.$discount->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }										


}
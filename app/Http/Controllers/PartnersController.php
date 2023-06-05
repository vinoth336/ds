<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Partners;
use App\Models\Restaurant;
use App\Models\Fooditems;
use App\Models\Ondeliveryorder;
use App\Models\Customerorder;
use App\Models\Paymentorder;
use App\Models\Usercart;
use App\Models\Partnertransac;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;



class PartnersController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'partners';
	static $per_page	= '50';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Partners();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'partners',
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
		$pagination->setPath('partners');
		
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
		return view('partners.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_users'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('partners.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_users'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('partners.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$id=$request->input('id');
		$rules = $this->validateForm();
		if($request->input('id') =='')
		{
			$rules['password'] 				= 'required|between:6,12';
			$rules['password_confirmation'] = 'required|between:6,12';
			$rules['email'] 				= 'required|email|unique:tb_users';
			$rules['username'] 				= 'required|alpha_num||min:2|unique:tb_users';
			
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
			$data = $this->validatePost('tb_partners');
			
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
			$data['res_name'] = Input::get('res_name');
			//echo Input::get('res_name'); exit;
			//print_r($id);exit;
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				/*$return = 'partners/update/'.$id.'?return='.self::returnUrl();*/
				$return = 'partners/update';
			} else {
				$return = 'partners?return='.self::returnUrl();
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

	//print_r($id);exit;
			return Redirect::to('partners/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}	

	public function postDelete( Request $request)
	{
		$partner_ids = $request->input('ids');
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			foreach($partner_ids as $pid){
				$restaurent = Restaurant::where('partner_id',$pid)->get();
				if(count($restaurent) > 0){
					foreach($restaurent as $rest){
						$id = $rest->id;
						$boyorderstatus = \DB::table('abserve_boyorderstatus')->where('rid','=',$id)->get();
						if(count($boyorderstatus) > 0)
							\DB::table('abserve_boyorderstatus')->where('rid','=',$id)->delete();
						/*abserve_hotel_items*/
						$fooditems = Fooditems::where('restaurant_id',$id)->get();
						if(count($fooditems) > 0)
							Fooditems::where('restaurant_id',$id)->delete();
						/*abserve_normal_order*/
						$ondelivery = Ondeliveryorder::where('res_id',$id)->get();
						if(count($ondelivery) > 0)
							Ondeliveryorder::where('res_id',$id)->delete();
						/*abserve_orders_customer*/
						$customorder = Customerorder::where('res_id',$id)->get();
						if(count($customorder) > 0)
							Customerorder::where('res_id',$id)->delete();
						/*abserve_order_details*/
						$orderdetail = \DB::table('abserve_order_details')->where('res_id','=',$id)->get();
						if(count($orderdetail) > 0)
							\DB::table('abserve_order_details')->where('res_id','=',$id)->delete();
						/*abserve_payment_order*/
						$paymentorder = Paymentorder::where('res_id',$id)->get();
						if(count($paymentorder) > 0)
							Paymentorder::where('res_id',$id)->delete();
						/*abserve_rating*/
						$absrating = \DB::table('abserve_rating')->where('res_id','=',$id)->get();
						if(count($absrating) > 0 )
							\DB::table('abserve_rating')->where('res_id','=',$id)->delete();
						/*abserve_user_cart*/
						$usercart = Usercart::where('res_id',$id)->get();
						if(count($usercart) > 0)
							Usercart::where('res_id',$id)->delete();
						/*abserve_restaurents*/
						$restaurent =Restaurant::find($id);
						if($restaurent){
							$restaurent->delete();
						}
					}
				}
				/*abserve_partner_balance*/
				$partnerbal = Partnertransac::where('partner_id',$pid)->get();
				if(count($partnerbal) > 0){
					Partnertransac::where('partner_id',$pid)->delete();
				}
			}

			$this->model->destroy($request->input('ids'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('partners')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('partners')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	
	
	public function getPartners(){  
	
	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
		$partners = DB::table('tb_users')->select(['tb_users.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','tb_users.region','=','region.id')->where('group_id','=','3')->where('region',$region);
		} else {
		$partners = DB::table('tb_users')->select(['tb_users.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','tb_users.region','=','region.id')->where('group_id','=','3');
		}
        return Datatables::of($partners)
            ->addColumn('action', function ($partners) {		
				return '<a href="partners/show/'.$partners->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="partners/update/'.$partners->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }							


}
<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Banners;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;

class BannersController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'banners';
	static $per_page	= '50';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Banners();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'banners',
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
		
		if(session()->get('gid') == '1'){
			$this->data['regions'] = Region::all();
		}elseif(session()->get('gid') == '7'){
			$this->data['regions'] = Region::where('id',session()->get('rid'))->get();
		}
	
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('banners');
		
    	//	$this->data['rowData']		= $results['rows'];
		
		// Restaurant Collection 
		$res = \DB::table('abserve_restaurants')->select('id','name')->get();
		$this->data['res']	= $res;
		
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
		return view('banners.index',$this->data);
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
		
			if(session()->get('gid') == '7'){
			
		    $banners = \DB::table('banners')->select('res_id')->whereNotIn('id',[$id])->get();
			foreach($banners as $banner){
				$resId[] = $banner->res_id;
			}
			//print_r($resId);  exit;	
			if(count($resId)>0){
				$res = \DB::table('abserve_restaurants')->select('id','name')->whereNotIn('id',$resId)->where('region',session()->get('rkey'))->get();
			} else {
				$res = \DB::table('abserve_restaurants')->select('id','name')->get();
			}
			//print_r($res);  exit;
		   }
		   
		   
		  	
			// Restaurant Collection
			$banners = \DB::table('banners')->select('res_id')->whereNotIn('id',[$id])->get();
			foreach($banners as $banner){
				$resId[] = $banner->res_id;
			}
			if(count($resId)>0){
				if(session()->get('gid') == '7'){
				     $res = \DB::table('abserve_restaurants')->select('id','name')->whereNotIn('id',$resId)->where('region',session()->get('rkey'))->get();
				}elseif(session()->get('gid') == '1') {
					 $res = \DB::table('abserve_restaurants')->select('id','name')->whereNotIn('id',$resId)->get();
				}
			} else {
				if(session()->get('gid') == '7'){
				$res = \DB::table('abserve_restaurants')->select('id','name')->where('region',session()->get('rkey'))->get();
				}elseif(session()->get('gid') == '1') {
					 $res = \DB::table('abserve_restaurants')->select('id','name')->get();
				}
			}
			$this->data['res']	= $res;
			
		} else {
			$this->data['row'] = $this->model->getColumnTable('banners');
			
			// Restaurant Collection
			//if(session()->get('gid') == '1'){
			$banners = \DB::table('banners')->select('res_id')->get();
			//}elseif(session()->get('gid') == '7') {
			//$banners = \DB::table('banners')->select('res_id')->where('region', session()->get('rid'))->get();	
			//}
			foreach($banners as $banner){
				$resId[] = $banner->res_id;
			}
			if(count($resId)>0){
				if(session()->get('gid') == '7'){
				     $res = \DB::table('abserve_restaurants')->select('id','name')->whereNotIn('id',$resId)->where('region',session()->get('rkey'))->get();
				}elseif(session()->get('gid') == '1') {
					 $res = \DB::table('abserve_restaurants')->select('id','name')->whereNotIn('id',$resId)->get();
				}
			} else {
				if(session()->get('gid') == '7'){
				$res = \DB::table('abserve_restaurants')->select('id','name')->where('region',session()->get('rkey'))->get();
				}elseif(session()->get('gid') == '1') {
					 $res = \DB::table('abserve_restaurants')->select('id','name')->get();
				}
			}
			
			$this->data['res']	= $res;
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('banners.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('banners'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('banners.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
	
	//print_r($request->all());   exit;
	
	if($request->res_id){
		 $resid = $request->res_id; 
	}else {
		 $resid = "0"; 
	}
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_banners');
			
		
		$data['status'] = $request->status;
		$data['res_id'] = $resid;
		$data['from_date'] = $request->from_date;
		$data['to_date'] = $request->to_date;
		if($request->available_days != ''){
		$data['available_days'] = implode(",",$request->available_days);
		}else {
		$data['available_days'] = '';	
		}
		
		
		
			if($request->id){
			
			   $update = \DB::table('banners')->where('id', $request->id)->update($data);
				
			}else {
				
	           $id = $this->model->insertRow($data , $request->input('id'));
				
			}
			
				
			//$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'banners/update';
			} else {
				$return = 'banners?return='.self::returnUrl();
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

			return Redirect::to('banners/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('banners')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('banners')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	

	public function postOfferdetails( Request $request)
	{
		$now = (date('Y-m-d'));
		$res_offer = \DB::table('offers')->select('offer')->where('res_id','=',$_REQUEST['res_id'])->where('offer_name','=','Restaurant Offer')->where('offer_from', '<=', ($now))->where('offer_to', '>=', ($now))->first();
		$coupon_offers = \DB::table('offers')->select('*')->where('res_id','=',$_REQUEST['res_id'])->where('offer_name','=','Coupon')->where('offer_from', '<=', ($now))->where('offer_to', '>=', ($now))->get();
		
		if($res_offer !=''){
			$offer .= '<div class="col-md-3 font-bold">'.$res_offer->offer.' %</div>';
		} else {
			$offer .= '<div class="col-md-3 font-bold"> No Offers Available </div>';
		}
	
		if(count($coupon_offers)>0){	
			foreach($coupon_offers as $coupon_offer){
				$coupon .= '<div class="col-md-3 font-bold">'.$coupon_offer->offer.' %</div>';
			}
		} else {
			$coupon .= '<div class="col-md-3 font-bold"> No Coupons Available </div>';
		}
		
		if($_REQUEST['res_id'] !=''){
	$query = "SELECT `reg`.`id`,`reg`.`region_name`,`reg`.`region_keyword` FROM `region` as `reg` JOIN `abserve_restaurants` as `ar`  ON `reg`.`region_keyword`=`ar`.`region` WHERE `ar`.`id`='".$_REQUEST['res_id']."'";	
	$region = \DB::select($query);	
	
	                $check = "selected";
                    $html = $html.'<option '.$check.' ' ;
					//$html = $html.'<option ' ;	
                    $html = $html.'value="'.$region[0]->id.'">';
                    $html = $html.$region[0]->region_name.'</option>';
		}else{
			
		$query = "SELECT * FROM `region`";
		$region = \DB::select($query);
	
	    foreach($region as $reg){
	
		            $html = $html.'<option ' ;
					//$html = $html.'<option ' ;	
                    $html = $html.'value="'.$reg->id.'">';
                    $html = $html.$reg->region_name.'</option>';
		
		}
		}
		
		return $offer.'@@'.$coupon.'@@'.$html;

	}			
	
		
	public function getBanners(){   
	
		$regionselect = \Session::get('regionselect');    	   
	   	
		DB::statement(DB::raw('set @rownum=0'));
		
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
			$banners = DB::table('banners')->select(['banners.*','region.region_name','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','banners.region','=','region.id')->leftJoin('abserve_restaurants','banners.res_id','=','abserve_restaurants.id')->where('banners.region',$region);
		} else {
			if($regionselect){
				$banners = DB::table('banners')->select(['banners.*','region.region_name','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','banners.region','=','region.id')->leftJoin('abserve_restaurants','banners.res_id','=','abserve_restaurants.id')->where('banners.region',$regionselect);
			} else {
				$banners = DB::table('banners')->select(['banners.*','region.region_name','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','banners.region','=','region.id')->leftJoin('abserve_restaurants','banners.res_id','=','abserve_restaurants.id');
			}
		}
		
        return Datatables::of($banners)
            ->addColumn('action', function ($banners) {		
				return '<a href="banners/show/'.$banners->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="banners/update/'.$banners->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }							
	
		


}
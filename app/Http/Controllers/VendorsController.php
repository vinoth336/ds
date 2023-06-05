<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables; 


class VendorsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'vendors';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Vendors();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'vendors',
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
		$pagination->setPath('vendors');
		
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
		return view('vendors.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('vendor'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('vendors.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('vendor'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('vendors.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_vendors');
			
			$data['node'] = implode(",",$data['node']);
			if($data['sub_node'] !=''){
				$data['sub_node'] = implode(",",$data['sub_node']);
				$data['subcat_id'] = $data['sub_node'];
			} else {
				$data['subcat_id'] = $data['node'];
			}
			
			if($request->start_time !=''){
				$start_time = strtotime($request->start_time);
				$end_time = strtotime($request->end_time);
				$data['start_time'] = date("H:i:s",$start_time);
				$data['end_time'] = date("H:i:s",$end_time);
			} else {
				$data['start_time'] = '';
				$data['end_time'] = '';
			}
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'vendors/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'vendors?return='.self::returnUrl();
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

			return Redirect::to('vendors/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('vendors')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('vendors')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	
	
	public function postRegionselect( Request $request)
	{
	  
		$region_key = $request->regionselect;
		$id = $request->id;
		$delivery_type = $request->delivery_type;
		
		/*for restaurant based on region*/
		/*if($region_key != ''){
			$restregion =\DB::select( "SELECT * FROM region_services WHERE region='".$region_key."'");
		}else{
			$restregion =\DB::select( "SELECT * FROM region_services");	
		}*/
		if($id != ''){
			$vendor = \DB::table('vendor')->where('id',$id)->first();
		} else {
			$vendor = "";
		}
		$region_services = \DB::table('region_services')->where('region',$region_key)->first();
		
		if(count($region_services)>0){
			$tree = explode(",",$region_services->tree);		
			$services_cat = \DB::table('service_categories')->where('level',0)->where('service_type',$delivery_type)->whereIn('id',$tree)->get();
		
			$html = $html.'<select name="subcat_id" rows="5" id="subcat_id" class="form-control" selected="" required="">';
			$html = $html.'<option value="">'.'Select Main Category'.'</option>' ;
		
			foreach($services_cat as $service_cat){
				if($service_cat->id == $vendor->tree){
					$selected = "selected='selected'";
				} else {
					$selected = "";	
				}
					   
				$html = $html.'<option value="'.$service_cat->id.'" '.$selected.'>'.$service_cat->cat_name.'</option>';
			}
			$html = $html.'</select>';
		}
		return $html;
	
	}	
	
	public function postTreeselect( Request $request)
	{	
	
	    $subcat = $request->subcat;
		$region_key = $request->region_key;
		$tree = $request->treeselect;
		$region_services = \DB::table('region_services')->where('region',$region_key)->first();
		
		if(count($region_services)>0){
			$node = explode(",",$region_services->node);		
			$services_cat = \DB::table('service_categories')->where('level',1)->where('cat_id',$tree)->whereIn('id',$node)->get();
			
			$html = $html.'<select name="subcat_id" rows="5" id="subcat_id" class="form-control" selected="" required="">';
			//$html = $html.'<option value="">'.'Select Sub Category'.'</option>' ;
		
		
			foreach($services_cat as $service_cat){
				
				//if($service_cat->id == $subcat){
				if(!empty($subcat)){
					if(in_array($service_cat->id,$subcat,true)){
						$req = "selected";
					}else{
						$req = '';	
					}
				}else{
					$req = '';	
				}
					   
				$html = $html.'<option value="'.$service_cat->id.'"'. $req.' >' .$service_cat->cat_name.'</option>';
			}
			$html = $html.'</select>';
			
		
		}		
		return $html;
	
	}	
	
	public function postNodeselect( Request $request)
	{
	
	    $snode = $request->snode;
		$region_key = $request->region_key;
		$node = $request->nodeselect;
		$region_services = \DB::table('region_services')->where('region',$region_key)->first();
		
		if(count($region_services)>0){
			$subnode = explode(",",$region_services->sub_node);
			$services_cat = \DB::table('service_categories')->where('level',2)->whereIn('cat_id',$node)->whereIn('id',$subnode)->get();
			//$services_cat = \DB::table('service_categories')->where('level',2)->where('cat_id',$node)->whereIn('id',$subnode)->get();
			
			if(count($services_cat)>0){
				$required = "required=''";
			} else {
				$required = "";
			}
			$html = $html.'<select name="subcat_id" rows="5" id="subcat_id" class="form-control" selected="" '.$required.'>';
			//$html = $html.'<option value="">'.'Select Main Category'.'</option>' ;
		
			foreach($services_cat as $service_cat){
				
				//if($service_cat->id == $snode){
				if(!empty($snode)){
					if(in_array($service_cat->id,$snode,true)){
						$req = "selected";
					}else{
						$req = '';	
					}
				}else{
					$req = '';	
				}
		
				$html = $html.'<option value="'.$service_cat->id.'"'. $req.'>'.$service_cat->cat_name.'</option>';
			}
			$html = $html.'</select>';
		}		
		return $html;
	
	}
	
	public function getServicecategory(Request $request){
		
		$sub_cat = explode(",",$request->sub_cat);
		$services_cat = \DB::table('service_categories')->whereIn('id',$sub_cat)->get();
		if(count($services_cat)>0){
			foreach($services_cat as $servicescat){
				$cat_name[] = $servicescat->cat_name;
			}
			$catname = implode(",",$cat_name);
		} else {
			$catname = "";
		}
		
		return $catname;
	}
	
	public function getVendors(){       	   
	   	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
			$vendors = DB::table('vendor')->select(['vendor.*','region.region_name','service_categories.cat_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','vendor.region','=','region.id')->leftJoin('service_categories','vendor.subcat_id','=','service_categories.id')->where('vendor.region',$region);
		}else{
			$vendors = DB::table('vendor')->select(['vendor.*','region.region_name','service_categories.cat_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','vendor.region','=','region.id')->leftJoin('service_categories','vendor.subcat_id','=','service_categories.id');
		}
        return Datatables::of($vendors)
            ->addColumn('action', function ($vendors) {		
				return '<a href="vendors/show/'.$vendors->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="vendors/update/'.$vendors->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }


}
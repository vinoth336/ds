<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Regionservices;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;


class RegionservicesController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'regionservices';
	static $per_page	= '10';

	public function __construct()
	{

		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Regionservices();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'regionservices',
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
		$pagination->setPath('regionservices');
		
		$this->data['rowData']		= $results['rows'];
		
		$this->data['service_cat']	= \DB::table('service_categories')->where('level','=',0)->orderBy('cat_name', 'asc')->get();		
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
		return view('regionservices.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('region_services'); 
		}
		
		$this->data['service_cat']	= \DB::table('service_categories')->where('level','=',0)->orderBy('cat_name', 'asc')->get();
		
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('regionservices.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('region_services'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('regionservices.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_regionservices');
			
			//print_r($_POST['category_level_1']); exit;
			$level0 = $_POST['category_level_0'];
			$level1 = $_POST['category_level_1'];
			$level2 = $_POST['category_level_2'];
			
			$tree = implode(",",$level0);
			//$node = implode(",",$level1);
			foreach($level1 as $cat){
				if($_POST['service_charge'.$cat] == ''){
					$service_charge = 0;
				} else {
					$service_charge = $_POST['service_charge'.$cat];
				}
				$_node[] = $cat.'-'.$_POST['start_time'.$cat].'-'.$_POST['end_time'.$cat].'-'.$service_charge;
			}
			$node = implode(",",$_node);
			
			foreach($level2 as $cat){
				if($_POST['service_charge'.$cat] == ''){
					$service_charge = 0;
				} else {
					$service_charge = $_POST['service_charge'.$cat];
				}
				$subnode[] = $cat.'-'.$_POST['start_time'.$cat].'-'.$_POST['end_time'.$cat].'-'.$service_charge;
			}
			$sub_node = implode(",",$subnode);
			
			/*echo $tree.'<br>';
			echo $node.'<br>';
			echo $sub_node.'<br>';
			exit;*/
			
			$data['tree'] = $tree;
			$data['node'] = $node;
			$data['sub_node'] = $sub_node;
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'regionservices/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'regionservices?return='.self::returnUrl();
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

			return Redirect::to('regionservices/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('regionservices')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('regionservices')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}			
	
	public function getRegionservices(){       	   
	   	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
			$regionservices = DB::table('region_services')->select(['region_services.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','region_services.region','=','region.id')->where('region_services.region',$region);
		}else{
			$regionservices = DB::table('region_services')->select(['region_services.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','region_services.region','=','region.id');
		}
        return Datatables::of($regionservices)
            ->addColumn('action', function ($regionservices) {		
				return '<a href="regionservices/show/'.$regionservices->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="regionservices/update/'.$regionservices->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }


}
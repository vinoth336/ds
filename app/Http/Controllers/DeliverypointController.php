<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Deliverypoint;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;

class DeliverypointController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'deliverypoint';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Deliverypoint();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'deliverypoint',
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
		$pagination->setPath('deliverypoint');
		
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
		return view('deliverypoint.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('delivery_point'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('deliverypoint.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('delivery_point'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('deliverypoint.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		print_r($request->all());   //exit;
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			if($_REQUEST['latitude'] == '' || $_REQUEST['longitude'] == ''){
				$validator->getMessageBag()->add('location', 'Enter valid Address!');
				return Redirect::to('deliverypoint/update/'.$_REQUEST['id'])->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
				->withErrors($validator)->withInput();
			}else {
				
			$data = $this->validatePost('tb_deliverypoint');
			
			
		$data['name'] = $request->name;
		$data['latitude'] = number_format((float)$request->latitude, 6, '.', '');
		$data['longitude'] = number_format((float)$request->longitude, 6, '.', '');
		$data['location'] = $request->location;
		$data['pin_code'] = $request->pin_code;
		$data['region'] = $request->region;
		$data['status'] = $request->status;
		//print_r($data);  exit;
		if($request->id){
			
			   $update = \DB::table('delivery_point')->where('id', $request->id)->update($data);
				
			}else {
				
	          	$ins=\DB::table('delivery_point')->insert($data);
				
			}
			
			
			//$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
				{
					$return = 'deliverypoint/update';
				} else {
					$return = 'deliverypoint?return='.self::returnUrl();
				}

			// Insert logs into database
			if($request->input('id') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		}} else {

			return Redirect::to('deliverypoint/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('deliverypoint')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('deliverypoint')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	
	
	
	
	public function getDeliverypoint(){       	   
	   	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
		$deliverypoint = DB::table('delivery_point')->select(['delivery_point.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','delivery_point.region','=','region.id')->where('delivery_point.region',$region);
		}else{
		$deliverypoint = DB::table('delivery_point')->select(['delivery_point.*','region.region_name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('region','delivery_point.region','=','region.id');
		}
        return Datatables::of($deliverypoint)
            ->addColumn('action', function ($deliverypoint) {		
				return '<a href="deliverypoint/show/'.$deliverypoint->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="deliverypoint/update/'.$deliverypoint->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }										
		


}
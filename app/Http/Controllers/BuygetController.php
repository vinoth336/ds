<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Buyget;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Datatables;

class BuygetController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'buyget';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Buyget();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'buyget',
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
		/*if(session()->get('gid') == '1'){
			$results = $this->model->getRows( $params );
		    $this->data['rowData']		= $results['rows'];
		}elseif(session()->get('gid') == '7'){
			$region_id = session()->get('rkey');
			$users = \DB::table('abserve_restaurants')->select('id')->where('region','=',$region_id)->get();
			foreach($users as $user){
				$user_ids[] = $user->id;
			}
			//print_r($user_ids);
			$ids = implode(",",$user_ids);			
		    $results['rows'] = \DB::select("SELECT * FROM `abserve_hotel_items` WHERE `bogo_item_id`!='0' AND `restaurant_id` IN (".$ids.")");
			$results['total'] = count($results['rows']); 
			$this->data['rowData']	= $results['rows']; 	
	    }*/		
		
		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('buyget');
		
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
		return view('buyget.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_hotel_items'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		
		
		if(session()->get('gid') == '1'){
			$results = \DB::table('abserve_restaurants')->select(['id', 'name','partner_id'])->where('active','=',1)->get();
		}elseif(session()->get('gid') == '7'){
			$results = \DB::table('abserve_restaurants')->select(['id', 'name','partner_id'])->where('active','=',1)->where('region','=',session()->get('rkey'))->get(); 
		}
			//print_r($results);

			 return view('buyget.form',$this->data)->with(compact(['results']));
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
			$this->data['row'] = $this->model->getColumnTable('abserve_hotel_items'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('buyget.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		//print_r($request->all());  exit;
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_buyget');
			
				$value = \DB::table('abserve_hotel_items')->select('*')->where('id','=',$request->bogo_item_id)->get();
				//print_r($value);   exit;
				$val = $value[0];
			  // $bogo_name = $val->food_item;     
				
		$up = \DB::table('abserve_hotel_items')
                    ->where('id', $request->input('food_item_buy'))->where('restaurant_id', $request->input('restaurant_id'))
                    ->update([
                        'buy_qty' => $request->input('buy_qty'),
                        'bogo_item_id' => $request->input('bogo_item_id'),
                        'get_qty' => $request->input('get_qty'),
                        'bogo_start_date' => $request->input('bogo_start_date'),
                        'bogo_end_date' => $request->input('bogo_end_date'),
                        'bogo_name' => $val->food_item,
                        ]);
                
                 
            if(!is_null($request->input('apply')))
			{
				$return = 'buyget/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'buyget?return='.self::returnUrl();
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

			return Redirect::to('buyget/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}	

	public function postDelete( Request $request)
	{
		
		 $val = $request->input('ids');  	//print_r($val); exit;
		
		
		  
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
		$value['buy_qty'] = "0";
		$value['get_qty'] = "0";
		$value['bogo_item_id'] = "0";
		$value['bogo_name'] = "0";
		$value['bogo_start_date'] = "0000-00-00 00:00:00";
		$value['bogo_end_date'] = "0000-00-00 00:00:00";
		$val = $request->input('ids');
			foreach($val as $valq){
			$query=\DB::table('abserve_hotel_items')->where('id','=', $valq)->update($value);
			}
			//print_r($query);   //exit;
			return Redirect::to('buyget')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('buyget')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	
	
	
	
	   public function postFoodsbyres( Request $request )
	{
                $id = $_POST['resid'];
			    $buy = $_POST['buy'];
				
				
				
				$value = \DB::table('abserve_hotel_items')->select('*')->where('id','=',$buy)->get();
				//print_r($value);
				 $val = $value[0];
				 $buy = $val->food_item;  
				 $get = $val->bogo_item_id; 
				
                
				$results = \DB::table('abserve_hotel_items')->select(['id', 'restaurant_id','food_item','price'])->where('restaurant_id','=',$id)->get();
                
                $html = '';
				
				
                
                foreach($results as $res){
					
					if($buy == $res->food_item) { 
					$check = "selected";
                    $html = $html.'<option '.$check.' ' ;
					//$html = $html.'<option ' ;	
                    $html = $html.'value="'.$res->id.'" >';
                    $html = $html.$res->food_item.'</option>';
                }else {
					
				    $html = $html.'<option ' ;
					//$html = $html.'<option ' ;	
                    $html = $html.'value="'.$res->id.'" >';
                    $html = $html.$res->food_item.'</option>';	
					
				}}
		echo $html;
	}		



  public function postFoodsgeters( Request $request )
	{
                $id = $_POST['resid'];
			    $get = $_POST['get'];
				
				
				
				$value = \DB::table('abserve_hotel_items')->select('*')->where('id','=',$get)->get();
				//print_r($value);
				 $val = $value[0];
				  $get = $val->food_item;  
				 
				
                $results = \DB::table('abserve_hotel_items')->select(['id', 'restaurant_id','food_item','price'])->where('restaurant_id','=',$id)->get();
                
                $html = '';
				
				
                
                foreach($results as $res){
					
					if($get == $res->food_item) { 
					$check = "selected";
                    $html = $html.'<option '.$check.' ' ;
					//$html = $html.'<option ' ;	
                    $html = $html.'value="'.$res->id.'" >';
                    $html = $html.$res->food_item.'</option>';
                }else {
					
				    $html = $html.'<option ' ;
					//$html = $html.'<option ' ;	
                    $html = $html.'value="'.$res->id.'" >';
                    $html = $html.$res->food_item.'</option>';	
					
				}}
		echo $html;
	}
	
	
	 public function postFoodbuy( Request $request )
	{
                $id = $_POST['resid'];
                $results = \DB::table('abserve_hotel_items')->select(['id', 'restaurant_id','food_item','price'])->where('restaurant_id','=',$id)->get();
                
                $html = '';
                
                foreach($results as $res){
                    
                    $html = $html.'<option value="'.$res->id.'" >';
                    $html = $html.$res->food_item.'</option>';
                }
		echo $html;
	}
	

			
		public function getBuyget(){ 
		
	
	   	
		DB::statement(DB::raw('set @rownum=0'));
		if(session()->get('gid') == '7'){
			$region = \Session::get('rkey');
			
			$buyget = DB::table('abserve_hotel_items')->select(['abserve_hotel_items.*','abserve_restaurants.*','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('abserve_restaurants','abserve_hotel_items.restaurant_id','=','abserve_restaurants.id')->where('bogo_item_id','!=','0')->where('abserve_restaurants.region',$region);
			
			} else {
				
		$buyget = DB::table('abserve_hotel_items')->select(['abserve_hotel_items.*','abserve_restaurants.name',DB::raw('@rownum := @rownum + 1 AS rownum')])->leftJoin('abserve_restaurants','abserve_hotel_items.restaurant_id','=','abserve_restaurants.id')->where('bogo_item_id','!=','0');
		}      	 
        return Datatables::of($buyget)
            ->addColumn('action', function ($buyget) {		
				return '<a href="buyget/show/'.$buyget->id.'?return=" class="tips btn btn-xs btn-primary"><i class="fa  fa-search "></i></a>  <a  href="buyget/update/'.$buyget->id.'?return=" class="tips btn btn-xs btn-success"><i class="fa fa-edit "></i></a>';
            })
            ->make(true);

    }							
	
	
	
	
	
	
	
}
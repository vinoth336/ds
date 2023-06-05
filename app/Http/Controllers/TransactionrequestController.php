<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Transactionrequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
require_once(app_path('Http/Controllers/stripe/Stripe.php'));

class TransactionrequestController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'transactionrequest';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Transactionrequest();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'transactionrequest',
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
		$pagination->setPath('transactionrequest');
		
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
		return view('transactionrequest.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_host_transfer'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('transactionrequest.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('abserve_host_transfer'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('transactionrequest.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		if($request->input('trans_submit')!='transfer'){
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_transactionrequest');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'transactionrequest/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'transactionrequest?return='.self::returnUrl();
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

			return Redirect::to('transactionrequest/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}
		}else{

			$error_msg='';
			$abserve_stripe_settings 	=  \DB::select("SELECT * FROM `abserve_stripe_settings` ");
			\Stripe\Stripe::setApiKey($abserve_stripe_settings[0]->private_key);
			$host=$_POST['host_id'];
			//$tra_amt=(($_POST['amount'])-($_POST['amount']*(5/100)));
			$tra_amt=$_POST['amount'];
			$tra_amt1=number_format($tra_amt, 2, '.', '');
			$amt=($tra_amt1)*100;
			//$amt=($_POST['amount'])*100;
			$ac=\DB::table('tb_users')->select('ext_acc_id')->where('id',$host)->first();
			$acc_id=$ac->ext_acc_id;
			if($acc_id=='')
				$error_msg.='Bank account details not created. ';
			if($amt<1)
				$error_msg.='Minimum Request amount is &euro; 1. ';
			if($acc_id!='' and $amt>=1)
			{
				$v=\Stripe\Balance::retrieve();
				$bal_amt=0;
				foreach($v->available as $key => $vv)
				{
					$currency=$v->available[$key]['currency'];
					if($currency=="eur")
						$bal_amt=($v->available[$key]['amount']);
				}
				//echo $bal_amt;
				if($bal_amt>=$amt)
				{
					$trans=\Stripe\Transfer::create(array(
					  "amount" => $amt,
					  "currency" => "eur",
					  "destination" => $acc_id,
					  "transfer_group" => "Group_Host_".$host
					));
					//print_r($trans);
					$trans_id=$trans->id;

					$data=Array ( 
					  'status' 				=>'Completed',
					  'trans_id'			=>$trans_id,
					  'transfered_amount'	=>$tra_amt1
					  );
					$id = $this->model->insertRow($data , $request->input('id'));
				}
				else
				{
					$error_msg.='You have only &euro; '.($bal_amt/100).' in your account';
				}
				
			}
			if($trans_id!='')
			{
				//return Redirect::to('transactionrequest?return=')->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			}
			else
			{
				$error_msg.='Your Transaction is failed.';
				//return Redirect::to('transactionrequest/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus',$error_msg);
			}
			if(!is_null($request->input('apply')))
			{
				$return = 'transactionrequest/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'transactionrequest?return='.self::returnUrl();
			}
			return Redirect::to($return)->with('messagetext',$error_msg)->with('msgstatus','success');
			
		
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
			return Redirect::to('transactionrequest')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('transactionrequest')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}			


}
<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Orderlogin;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class OrderloginController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'orderlogin';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Orderlogin();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'orderlogin',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		
		return view('orderlogin.index',$this->data);
	}	


	
		public function postLogin( Request $request) {
		
		$rules = array(
			'email'=>'required|email',
			'password'=>'required',
		);		
		
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	
		
		
			if (\Auth::attempt(array('email'=>$_REQUEST['email'], 'password'=> $_REQUEST['password']))) {
			
					$orderlogin = Orderlogin::find(\Auth::user()->id); 
					\Session::put('orderloginid', $orderlogin->id);
				    return Redirect::to('orderupdateamount');
				
							
			} else{
				
				return Redirect::to('orderlogin')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
				
			}

			
		} else {
		
				return Redirect::to('orderlogin')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
	}			


}
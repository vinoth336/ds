<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Neworders;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class NewordersController extends Controller {

	protected $layout 	= "layouts.main";
	protected $data 	= array();	
	public $module 		= 'neworders';
	static $per_page	= '10';

	public function __construct(){
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Neworders();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'neworders',
			'return'	=> self::returnUrl()
			
		);	
	}

	public function getIndex( Request $request ){

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
		$pagination->setPath('neworders');
		
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
		if(\Auth::user()->group_id == 1){
			// Render into template
			$user_id = \Auth::user()->id; 
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE  `po`.`order_status` = '0' AND  `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC LIMIT 1");
			$this->data['last_id']	= json_encode($lastid[0]->id);

			return view('neworders.index_admin',$this->data);
		} else {
			$user_id = \Auth::user()->id; 
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".$user_id."' AND `po`.`order_status` = '0' ORDER BY `od`.`id` DESC LIMIT 1");
			$partner_details = \DB::table('tb_users')->select('*')->where('id',$results[0]->partner_id)->get();
			$this->data['pphone']	= $partner_details[0]->phone_number;
			// echo json_encode($lastid[0]->id);exit();
			$this->data['last_id']	= json_encode($lastid[0]->id);
			$this->data['tableview']= 'orders.table_view';
			return view('neworders.index_new',$this->data)->with('model',new Neworders);
		} 	
	}	

	public function postAjaxload( Request $request){
		if(isset($_POST['value']) && $_POST['value'] != ''){
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".\Auth::id()."' ORDER BY `od`.`id` DESC LIMIT 1");
			if(!empty($lastid)){
				
				if($_POST['value']	!= $lastid[0]->id){
					$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".\Auth::id()."' ORDER BY `od`.`id` DESC");
					$this->data['results']	= $results;
					$response['msg']	= 'success';
					$response['lastid']	= $lastid[0]->id;					
				} else {
					$response['msg']	= 'failed';
				}
			} else {
				$response['msg']	= 'failed';
				// echo "failed";exit();
			}

			echo json_encode($response);exit();
		}
	}

	public function postTablelist( Request $request){
		
		$results = \DB::select("SELECT * FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` WHERE `partner_id` = '".\Auth::id()."' ORDER BY `od`.`id` DESC LIMIT 1");
		
		
		$res_detail = $this->model->resname($results[0]->orderid);

		$html = '<tr><td width="50"><input type="checkbox" class="ids" name="ids[]" value="'.$results[0]->id.'" /></td><td width="50">5374675346857</td><td width="50">#'.$results[0]->orderid.'</td><td width="50">'.$res_detail[0]->name.'</td><td width="50">'.$results[0]->order_details.'</td><td><i class="fa fa-volume-up buzzer_vol fn_buzzer_off"></i><i class="icon-checkmark-circle2 fn_accept" aria-hidden="true"></i><i class="icon-cancel-circle2 fn_reject" aria-hidden="true"></i><input type="hidden" value="'.$results[0]->partner_id.'" class="partner_id" /><input type="hidden" value="'.$results[0]->orderid.'" class="orderid" /></td></tr>';
		return $html;
	}

	public function postAdminajaxload( Request $request){
		if(isset($_POST['value']) && $_POST['value'] != ''){
			$lastid	= \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE    `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC LIMIT 1");
			if(!empty($lastid)){
				
				if($_POST['value']	!= $lastid[0]->id){
					$results =  \DB::select("SELECT `od`.`id` FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE  `po`.`order_status` = '0' AND  `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC ");
					$this->data['results']	= $results;
					$response['msg']	= 'success';
					$response['lastid']	= $lastid[0]->id;

					
					
				} else {
					$response['msg']	= 'failed';
				}
			} else {
				$response['msg']	= 'failed';
				// echo "failed";exit();
			}

			echo json_encode($response);exit();
		}
	}

	public function postAdmintablelist( Request $request){
		
		$results =  \DB::select("SELECT `po`.*,`rs`.name as hotel_name FROM `abserve_orders_partner` as `po` JOIN `abserve_order_details` as `od` ON `po`.`orderid` = `od`.`id` JOIN `abserve_restaurants` as `rs` ON `po`.`partner_id` = `rs`.`partner_id` WHERE    `rs`.`call_handling` = '1' ORDER BY `od`.`id` DESC LIMIT 1");

		$res_detail = $this->model->partnerphone($results[0]->partner_id);
		$deliveryboys = $this->model->alldeliveryboys();$option = '';
		foreach($deliveryboys as $dboys){
			$option.= '<option value="'.$dboys->id.'">'.$dboys->username.'</option>';
		}
		$html = '<tr class="fn_'.$results[0]->orderid.'"><td width="50"><input type="checkbox" class="ids" name="ids[]" value="'.$results[0]->id.'" /></td><td width="50">5374675346857</td><td width="50">#'.$results[0]->orderid.'</td><td width="50">'.$results[0]->partner_id.'</td><td width="50">'.$results[0]->hotel_name.'</td><td width="50">'.$results[0]->order_details.'</td><td width="50"><div style="float:left;">'.$res_detail[0]->phone_number.'</div><div style="float:right;"><i class="icon-checkmark-circle2 fn_accept" aria-hidden="true"></i>&nbsp;&nbsp;<i class="icon-cancel-circle2 fn_reject" aria-hidden="true"></i></div></td><td><select name="delivery_boy" class="delivery_boy">'.$option.'</select><i class="icon-checkmark-circle2 fn_deliveryboy" aria-hidden="true"></i><i class="icon-cancel-circle2 " aria-hidden="true"></i><input type="hidden" value="'.$results[0]->partner_id.'" class="partner_id" /><input type="hidden" value="'.$results[0]->orderid.'" class="orderid" /></td></tr>';
		return $html;
	}

	public function getUpdate(Request $request, $id = null){
	
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
			$this->data['row'] = $this->model->getColumnTable('abserve_orders'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		return view('neworders.form',$this->data);
	}	

	public function getShow( $id = null){
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('abserve_orders'); 
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('neworders.view',$this->data);	
	}	

	public function postSave( Request $request){
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_neworders');
				
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'neworders/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'neworders?return='.self::returnUrl();
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

			return Redirect::to('neworders/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	}	

	public function postDelete( Request $request){
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('neworders')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('neworders')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}
	}

	//order_status = 1(Partner,Customer) status = 1(Order Detail)Push_notification for customer
	public function getPorderaccept( Request $request){
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>1]);
						$abc = \DB::table('abserve_orders_customer')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>1]);
						$abc1 = \DB::table('abserve_order_details')
						->where('id','=',$_REQUEST['order_id'])
						->update(['status'=>1]);

						if($abp && $abc){
							$cust_id = \DB::table('abserve_order_details')->select('cust_id')->where('id','=',$_REQUEST['order_id'])->get();
							$appapi_details	= $this->appapimethod(1);
							$mobile_token 	= $this->userapimethod($cust_id[0]->cust_id,'abserve_customers');
							$message 		= "Your order Hasbeen Accepted";
							$app_name		= $appapi_details->app_name;
							$app_api 		= $appapi_details->api;
							// print_r($appapi_details->api);exit;
							$this->pushnotification($app_api,$mobile_token,$message,$app_name);
							$response['message'] = "Order Accepted";
							$response['alert'] = "alert-success";
							echo json_encode($response);exit;
						}else{
							$response['message'] = "Order Doesn't Accepted";
							$response['alert'] = "alert-danger";
							echo json_encode($response);exit;
						}
					}else{
						$response['message'] = "It's Not your Order";
						$response['alert'] = "alert-danger";
						echo json_encode($response);exit;		
					}
				}else{
					$response['message'] = "No Such Order found";
					$response['alert'] = "alert-danger";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				$response['alert'] = "alert-danger";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function getPorderdboy( Request $request){
		$delivery_boy = $_GET['delivery_boy'];
		$partner_id = $_GET['partner_id'];
		$order_id = $_GET['order_id'];
		$order_exists = \DB::table('abserve_orders_partner')->select('order_value','order_details','order_status')->where('orderid', $order_id)->first();
		$order_value = $order_exists->order_value;
		$order_details = $order_exists->order_details;
		$order_status = $order_exists->order_status;
		$insert_array = array(
			'boy_id'		=> $delivery_boy,
			'orderid'	=> $order_id,
			'distance'	=> '1',
			'delivery_charges'	=> '50',
			'partner_id'	=> $partner_id,
			'order_status'	=> $order_status,
			'order_value'	=> $order_value,
			'order_details'	=> $order_details,
			'current_order'	=> '0',
			'order_done_status'	=> '0',
			);	

		$p_id = \Db::table('abserve_orders_boy')->insertGetId($insert_array);
		$update_array['boy_status'] = '1';
                    \DB::table('abserve_deliveryboys')
                            ->where('id','=',$delivery_boy)
                            ->update($update_array);
        $deliveryboys = $this->model->alldeliveryboys();
        foreach($deliveryboys as $dboys){
			$option.= '<option value="'.$dboys->id.'">'.$dboys->username.'</option>';
		}
		
		return $option;
	}

	//order_status = 5(Partner,Customer)Push_notification for customer
	public function getPorderreject( Request $request){
		$response = array();
	
		$rules = array(
			'order_id'		=>'required',
			'partner_id'	=>'required'
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$partner_exists = \DB::table('tb_users')->where('id','=',$_REQUEST['partner_id'])->exists();
			if($partner_exists){
				$order_exists = \DB::table('abserve_order_details')->where('id','=',$_REQUEST['order_id'])->exists();
				if($order_exists){
					$acess = \DB::table('abserve_orders_partner')->where('orderid','=',$_REQUEST['order_id'])->where('partner_id','=',$_REQUEST['partner_id'])->exists();
					if($acess){
						$abp = \DB::table('abserve_orders_partner')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>5]);
						$abc = \DB::table('abserve_orders_customer')
						->where('orderid','=',$_REQUEST['order_id'])
						->update(['order_status'=>5]);
						$abc1 = \DB::table('abserve_order_details')
						->where('id','=',$_REQUEST['order_id'])
						->update(['status'=>5]);
						if($abp && $abc){
							$response['message'] = "Order Rejected";
							$response['alert'] = "alert-success";
							echo json_encode($response);exit;
						}else{
							$response['message'] = "Order Doesn't Rejected";
							$response['alert'] = "alert-danger";
							echo json_encode($response);exit;
						}
					}else{
						$response['message'] = "It's Not your Order";
						$response['alert'] = "alert-danger";
						echo json_encode($response);exit;		
					}
				}else{
					$response['message'] = "No Such Order found";
					$response['alert'] = "alert-danger";
					echo json_encode($response);exit;
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				$response['alert'] = "alert-danger";
				echo json_encode($response);exit;		
			}
		}else {		
			$messages 				= $validator->messages();
			$error 					= (array)$messages->getMessages();
			$response["message"] 	= $error;
			echo json_encode($response); exit;
		}
	}

	public function pushnotification($app_api,$mobile_token,$message,$app_name){
		define( 'API_ACCESS_KEY', $app_api );
		/*$token = 'd1cd3p8vjEE:APA91bEtr7auvhwseCs7iyaNv-bMmUgtX09ZOMbWYozk5geQIFTnsVseIN73E7qzU_71a62bi3ga68ohAXjNXzAtQy034_q4plnPlSqb-ZHCh1KCHFYlAqHToaNUEIU4sZrUjzZissqS';*/

		$registrationIds = [$mobile_token];

		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> 'Message from'.$app_name,
			/*'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'=> 'Ticker text here...Ticker text here...Ticker text here',*/
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);

		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );

		//Decoding json from result 
		$res = json_decode($result);
		
		
		if ($res === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Getting value from success 
		$flag = $res->success;	
	}

	public function appapimethod( $value = ''){

		$appapi = \DB::table('abserve_app_apis')->select('*')->where('id','=',$value)->get();
		return $appapi[0];
	}

	public function userapimethod($userid = '',$table){

		$userapi = \DB::table($table)->select('mobile_token')->where('id','=',$userid)->get();
		return $userapi[0]->mobile_token;
	}

}
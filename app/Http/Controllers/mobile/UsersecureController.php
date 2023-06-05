<?php namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use App\Models\Partners;
use App\Models\Deliveryboy;
use App\Models\Customers;
use App\Models\Restaurant;
use Socialize;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use LucaDegasperi\OAuth2Server\Authorizer;
use Validator, Input, Redirect, Response;

use Auth, DB, Crypt, DateTime, Session; 

class UsersecureController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function getProfile( Authorizer $authorizer ) {

		$user_id = $authorizer->getResourceOwnerId(); // the token user_id
       	$user = \App\User::find($user_id);// get the user data from database
       	$aVars['email'] = $user->email;
     	return Response::json($aVars);
	}

	public function getStepvalidation($error){

		foreach ($error as $key => $value) {	
			$val= $value[0];		 	
		}	
			 return $val;
	}

	/**
	Customer profile edit&update function

	Params: $request object
	Response: status message success or failure
	*/
	public function getCedit( Request $request) {

		$response 	= array();
		$rules		= array();
	
		$userid 	= array('user_id'		=>'required');	
		$user 		= array('username'		=>'required|min:2');
		$email 		= array('email'			=>'required|email|unique:tb_users');
		$phno 		= array('phone_number'	=>'required|unique:tb_users|numeric');

		array_unshift($rules, $userid);
		
		if($_REQUEST['username']){
			array_unshift($rules, $user);
		}
		if($_REQUEST['email']){
			array_unshift($rules, $email);
		}
		if($_REQUEST['phone_number']){
			array_unshift($rules, $phno);
		}
		$result = call_user_func_array('array_merge_recursive', $rules);
				
		$validator = Validator::make($_REQUEST, $result);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$user_id 	= $_REQUEST['user_id'];
				$image 		= $_REQUEST['avatar'];
				$file = public_path()."/uploads/customers/$user_id";

				$data 	=	$request->all();
				unset($data['user_id']);
				unset($data['access_token']);

				/*if($image != ''){
					$path = public_path()."/uploads/customers/$user_id.jpg";
					file_put_contents($path,base64_decode($image));
				}*/

				$i=1;
				foreach ($data as $key => $name_value) {
					// if(in_array($key, $aFields)){
						$keys[] = $key;
						/*if($key == 'avatar'){
							\File::Delete($file);
							$vals[] = $user_id.".jpg";
						}
						else{*/
							$vals[] = $name_value;
						// }
					// }
					$i++;
				}

				$values = array_combine($keys, $vals);

				$update = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->update($values);
				if($update){
					$response['message'] = "Updated Successfully";
					echo json_encode($response);exit;
				}else{
					$response['message'] = "Not Updated";
					echo json_encode($response);exit;					
				}
			}else{
				$response['message'] = "UserID Doesn't exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$val=$this->getStepvalidation($error);
			$response["message"] 	= $val;
			echo json_encode($response); exit;
		}
	} 


	public function getCaddressdel( Request $request){

		$response = array();

		$rules = array(
			'user_id'		=>'required',
			'address_id'	=>'required'
			);	

		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){
				$adrrexist = \DB::table('abserve_user_address')->where('user_id','=',$_REQUEST['user_id'])->where('id', '=', $_REQUEST['address_id'])->exists();

				if($adrrexist){
					\DB::table('abserve_user_address')->where("user_id",'=',$_REQUEST['user_id'])->where('id', '=', $_REQUEST['address_id'])->delete();

					$response['message']	= "Address Deleted Successfully";
					echo json_encode($response);exit;
				}else{
					$response['message']	= "Address Doesn't Exists";
					echo json_encode($response);exit;
				}
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$val=$this->getStepvalidation($error);
			$response["message"] 	= $val;
			echo json_encode($response); exit;
		}
	} 

	public function getProfileedit(Request $request)
	{
		$rules = array(
			'user_id'=>'required|numeric',
		);
		$val  = array();
		$validator = Validator::make($_REQUEST, $rules);
		if ($validator->passes()) {

			$check = $this->exists('tb_users','id',$request->user_id);
			if($check){
				if($request->first_name!=""){
					$val['first_name'] = $request->first_name;
				}
				if($request->last_name!=""){
					$val['last_name'] = $request->last_name;
				}
				/*if($request->username!=""){
					$val['username'] = $request->username;
				}*/
				if($request->phone_number!=""){
					$val['phone_number'] = $request->phone_number;
				}

				if($request->email!=""){
					$val['email'] = $request->email;
				}
				
				if(!empty($val)){
		            $up = \DB::table('tb_users')->where('id', $request->user_id)->update($val); 


					if($up){

						$userdetails = \DB::select("SELECT `avatar`,`id`,`first_name`,`last_name` FROM `tb_users` where `id` = '".$_REQUEST['user_id']."'") ;
						
						foreach ($userdetails as $key => $valu) {
					if($valu->avatar != ''){
						$valu->avatar = \URL::to('').'/uploads/customers/'.$valu->avatar;
					}else{
						$valu->avatar = \URL::to('').'/uploads/images/no-image.png';
					}
				}
						$response['userdetails'] = $userdetails;
						$response['message'] = "Updated successfully";
					} else {
						$response['message'] = "Doesn't updated";
					}
				} else {
					$response['message'] = "Doesn't updated";
				}
			} else {
				$response['message'] = "User ID Doesn't exists";
			}
		} else {
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$val=$this->getStepvalidation($error);
			$response["message"] 	= $val;
			echo json_encode($response); exit;
		}
		echo json_encode($response,JSON_NUMERIC_CHECK); exit;

	}

	 public function exists($table,$field,$value){
		return \DB::table($table)->where($field,$value)->exists();
	}


	public function getCaddressedit( Request $request){
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$values = array("address_type"=>$_REQUEST['type'],"building"=>$_REQUEST['building'],"landmark"=>$_REQUEST['landmark'],"address"=>$_REQUEST['address'],"lat"=>$_REQUEST['lat'],'lang'=>$_REQUEST['lang']);
				\DB::table('abserve_user_address')->where('user_id','=',$_REQUEST['user_id'])->where('id','=',$_REQUEST['address_id'])->update($values);

				$response['user_address']	= "Address Saved";
				echo json_encode($response);exit;
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$val=$this->getStepvalidation($error);
			$response["message"] 	= $val;
			echo json_encode($response); exit;
		}
	}


	public function getCaddressadd( Request $request) {
		
		$response = array();
	
		$rules = array(
			'user_id'		=>'required',
			);	
				
		$validator = Validator::make($_REQUEST, $rules);

		if ($validator->passes()) {
			$cexists = \DB::table('tb_users')->where('id','=',$_REQUEST['user_id'])->exists();
			if($cexists){

				$ins = array("user_id"=>$_REQUEST['user_id'],"address"=>$_REQUEST['address'],"building"=>$_REQUEST['building'],"landmark"=>$_REQUEST['landmark'],"address_type"=>$_REQUEST['type'],"lat"=>$_REQUEST['lat'],'lang'=>$_REQUEST['lang']);

				/*$intable = \DB::table('abserve_user_address')
				->where('user_id','=',$_REQUEST['user_id'])
				->where('address_type','=',$_REQUEST['type'])
				->exists();*/
				$if_ins = \DB::table('abserve_user_address')->insert($ins);

					if($if_ins){
						// $if_ins = \DB::table('abserve_user_address')->insert($ins);
						$response['message']	= "Address added Successfully";
					}else{
						$response['message']	= "Address doesn't added";
					}

				echo json_encode($response);exit;				
			}else{
				$response['message']	= "User ID Doesn't Exists";
				echo json_encode($response);exit;
			}
		}else{
			$messages 				= $validator->messages();
			$error 					= $messages->getMessages();
			$val=$this->getStepvalidation($error);
			$response["message"] 	= $val;
			echo json_encode($response); exit;
		}
	}

	public function getRating( Request $request){
		$cust_id 						=$_REQUEST['cust_id'];
		$res_id 						=$_REQUEST['res_id'];
		$rating 						= $_REQUEST['rating'];
		$value= array("cust_id" => $cust_id,"res_id" => $res_id,"rating"=>$rating);
		$insert=\DB::table('abserve_rating')->insert($value);
		if($insert){
		$response['message']		= "Success";
		echo json_encode($response); exit;
		}
		else { 
		$response['message']		= "Failed";
		echo json_encode($response); exit;
		}	
	}


}
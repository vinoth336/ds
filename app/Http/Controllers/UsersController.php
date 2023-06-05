<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Socialize, Hash;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
use Auth, DB, Crypt, DateTime, Session; 

class UsersController extends Controller {

	public function __construct() {
		parent::__construct();

	}

	public function Imageupload( Request $request) {
		$user_id = \Auth::id();
		if(!is_null(Input::file('userImg'))) {
			$file = $request->file('userImg'); 
			/*$types = array('image/jpeg', 'image/gif', 'image/png');
			if (!in_array($_FILES['userImg']['type'], $types)) {
				return Redirect::to('user/profile')->with('messagetext','The following errors occurred')->with('msgstatus','error')
				->withErrors('Only jpeg,gif files allowed')->withInput();
			} else {*/
				$destinationPath 	= './uploads/users/';
				$filename 			= $file->getClientOriginalName();
				$extension 			= $file->getClientOriginalExtension();
				$newfilename 		= $user_id.time().'.'.$extension;
				$uploadSuccess 		= $request->file('userImg')->move($destinationPath, $newfilename); 
				if( $uploadSuccess ) {
					$response['message']	= "success";
					$response['file_name']	= $newfilename;
					$data['avatar']			= $newfilename;

					$userOldImage = \Auth::user()->avatar;
					$imgPath = '/uploads/users/'.$userOldImage;
					if(\File::exists(public_path($imgPath))){
						\File::delete(public_path($imgPath));
					}

					$user	= User::find($user_id);
					$user->avatar  = $newfilename;
					$user->save();

				} else {
					$response['message']	= "failure";
					$response['file_name']	= '';
				}
			// }
		}
		echo json_encode($response);exit();
	}

	public function Changepassword(Request $request){
		$rules = array(
			'old_password'		=> 'required',
			'password'			=>'required|between:6,12|confirmed',
			'password_confirmation'=>'required|between:6,12'
		);	
		
		$validator	= Validator::make($request->all(), $rules);
		$url 		= \URL::to('frontend/myaccount');
		$userid		= Auth::user()->id;

		if ($validator->passes()) {
			if(\Auth::attempt(array('id' => $userid, 'password' => $request->old_password))){
				$update=\DB::table('tb_users')->where('id',Auth::user()->id)->update(array('password'=>\Hash::make($request->password)));
				return Redirect::to($url)->with('message',\SiteHelpers::alert('success','Password  changed sucessfully'))->withInput();
			} else {
				\Session::put('changePass','true');
				return Redirect::to($url)->with('message',\SiteHelpers::alert('error','Your old password was incorrect. Please try again.'))->withErrors($validator)->withInput();
			}
		} else {
			if(\Auth::attempt(array('id' => $userid, 'password' => $request->old_password))){
				\Session::put('changePass','true');
				return Redirect::to($url)->with('message',\SiteHelpers::alert('error','Your new passwords did not match. Please try again.'))->withErrors($validator)->withInput();
			} else {
				\Session::put('changePass','true');
				return Redirect::to($url)->with('message',\SiteHelpers::alert('error','Your old password was incorrect. Please try again.'))->withInput();
			}
		}
	}
}
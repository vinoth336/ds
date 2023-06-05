<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Socialize;
use Hash;
use App\Models\Hosttransfer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
require_once(app_path('Http/Controllers/stripe/Stripe.php'));


use Auth, DB, Crypt, DateTime, Session; 

class UserController extends Controller {

	
	protected $layout = "layouts.main";

	public function __construct() {
		parent::__construct();
	} 

	public function getRegister(){
        
		if(CNF_REGIST =='false') :    
			if(\Auth::check()):
				 return Redirect::to('')->with('message',\SiteHelpers::alert('success','Youre already login'));
			else:
				 return Redirect::to('user/login');
			  endif;
			  
		else :
				
				return view('user.register');  
		 endif ; 
	}
	public function postAbscreate( Request $request) {

		\Auth::loginUsingId(1,false);
		$user				= User::find(1);
		//$user				= new User;
		$user->first_name	= $_POST['abs_f_name'];
		$user->last_name	= $_POST['abs_l_name'];
		$user->email		= trim($_POST['abs_email_address']);
		$user->password		= \Hash::make($_POST['abs_password']);
		$user->save();

		header('Location:'.$_POST['abs_base_url'].'?return=success');exit;
	}

	public function getStepvalidation($error){
		foreach ($error as $key => $value) {
			$val= $value[0];
		}
		return $val;
	}

	public function getPartnerregister(){
        
		if(CNF_REGIST =='false') :    
			if(\Auth::check()):
				 return Redirect::to('')->with('message',\SiteHelpers::alert('success','Youre already login'));
			else:
				 return Redirect::to('user/login');
			  endif;
			  
		else :
				
				return view('user.partner_signup');  
		 endif ; 
	}

	public function postCreate( Request $request){
	
	/*print_r($request->input('group_id'));
	exit;*/
			$rules = array(
				'group_id' 				=> 'required',
				'user_name' 			=> 'required|min:4|unique:tb_users,username',
				'firstname'				=> 'required|alpha_num|min:2',
				'lastname'				=> 'required|alpha_num|min:2',
				'email'					=> 'required|email|unique:tb_users',
				'password'				=> 'required|between:6,12|confirmed',
				'password_confirmation'	=> 'required|between:6,12',
				'phone_number'			=> 'required|regex:/[0-9]{9}/|unique:tb_users',
				'phone_code' 			=> 'required'
			);	

		if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->username 		= $request->input('user_name');
			$authen->first_name 	= $request->input('firstname');
			$authen->last_name 		= $request->input('lastname');
			$authen->email 			= trim($request->input('email'));
			$authen->activation 	= $code;
			$authen->group_id 		= $request->input('group_id');
			$authen->phone_number 	= $request->input('phone_number');
			$authen->phone_code 	= $request->input('phone_code');
			$authen->password 		= \Hash::make($request->input('password'));
			if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			$authen->save();
			$data = array(
				'username'  => $request->input('user_name'),
				'firstname'	=> $request->input('firstname') ,
				'lastname'	=> $request->input('lastname') ,
				'email'		=> $request->input('email') ,
				'password'	=> $request->input('password') ,
				'code'		=> $code
				
			);
			if(CNF_ACTIVATION == 'confirmation')
			{ 
			
				$to = $request->input('email');
				$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
				$message = view('user.emails.registration', $data);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);	
				
				$message = "Thanks for registering! . Please check your inbox and follow activation link";
								
			} elseif(CNF_ACTIVATION=='manual') {
				$message = "Thanks for registering! . We will validate you account before your account active";
			} else {
   			 	$message = "Thanks for registering! . Your account is active now ";         
			
			}	


			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success',$message));
		} else {
			$request->flash();
			return Redirect::to('user/register')->with('message',\SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}
	}

	public function postPartnercreate( Request $request){


		$rules = array(
			'group_id' => 'required',
			'firstname'=>'required|alpha_num|min:2',
			'lastname'=>'required|alpha_num|min:2',
			'email'=>'required|email|unique:tb_users',
			'password'=>'required|between:6,12|confirmed',
			'password_confirmation'=>'required|between:6,12',
			'phone' => 'required',
			'address' => 'required',
			'state' => 'required',
			'country' => 'required'
 			);	


		if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->first_name = $request->input('firstname');
			$authen->last_name = $request->input('lastname');
			$authen->email = trim($request->input('email'));
			$authen->activation = $code;
			$authen->group_id = 3;
			$authen->password = \Hash::make($request->input('password'));
			$authen->phone_number =  $request->input('phone');
			$authen->address =  $request->input('address');
			$authen->state =  $request->input('state');
			$authen->country =  $request->input('country');
			

			$authen->active = '0';


			/*if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }*/

			$authen->save();
			
			$data = array(
				'firstname'	=> $request->input('firstname') ,
				'lastname'	=> $request->input('lastname') ,
				'email'		=> $request->input('email') ,
				'password'	=> $request->input('password') ,
				'code'		=> $code
				
			);

			if(CNF_ACTIVATION == 'confirmation')
			{ 
			
			/*	$to = $request->input('email');
				$subject = "[ " .CNF_APPNAME." ] REGISTRATION "; 			
				$message = view('user.emails.registration', $data);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);	*/
				
				$message = "Thanks for registering! . site administrator will contact you soon";
								
			} elseif(CNF_ACTIVATION=='manual') {
				$message = "Thanks for registering! . site administrator will contact you soon";
			} else {
   			 	$message = "Thanks for registering! . Your account is active now ";         
			
			}	


			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success',$message));
		} else {
			return Redirect::to('user/partnerregister')->with('message',\SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}
	}
	
	public function getActivation( Request $request){
		$num = $request->input('code');
		if($num =='')
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('error','Invalid Code Activation!'));
		
		$user =  User::where('activation','=',$num)->get();
		if (count($user) >=1)
		{
			\DB::table('tb_users')->where('activation', $num )->update(array('active' => 1,'activation'=>''));
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Your account is active now!'));
			
		} else {
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('error','Invalid Code Activation!'));
		}
	}

	public function getLogin() {
	
		if(\Auth::check())
		{
			return Redirect::to('')->with('message',\SiteHelpers::alert('success','Youre already login'));
			// return redirect()->intended();
			// return redirect()->back();
			// return Redirect::to('user/profile');

		} else {
			$this->data['socialize'] =  config('services');
			return View('user.login',$this->data);
			
		}	
	}

	public function getCustomerlogin(){

	    if(\Auth::check())
		{
			return Redirect::to('')->with('message',\SiteHelpers::alert('success','Youre already login'));
			// return redirect()->intended();
			// return redirect()->back();
			// return Redirect::to('user/profile');

		} else {
			$this->data['socialize'] =  config('services');
			return View('user.customer_login',$this->data);
			
		}	
	}

	public function postCustomersignin( Request $request) {

        
   		$rules = array(
			'email'=>'required|email',
			'password'=>'required',
		);		

		if(CNF_RECAPTCHA =='true') $rules['captcha'] = 'required|captcha';
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	

			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );

			
			
			if (\Auth::attempt(array('email'=>$request->input('email'), 'password'=> $request->input('password') ), $remember )) {
				
				if(\Auth::check())
				{
					$row = User::find(\Auth::user()->id); 
					
					if($row->active =='0')
					{
						// inactive 
                        return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is not active'));
                        \Auth::logout();

					} else if($row->active=='2')
					{
						// BLocked users
						\Auth::logout();
						return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is BLocked'));
					} else {
						\DB::table('tb_users')->where('id', '=',$row->id )->update(array('last_login' => date("Y-m-d H:i:s")));
						\Session::put('uid', $row->id);
						\Session::put('gid', $row->group_id);
						\Session::put('eid', $row->email);
						\Session::put('ll', $row->last_login);
						\Session::put('fid', $row->first_name.' '. $row->last_name);	

						 $get_current = \DB::select("SELECT * from `abserve_user_cart` where `user_id` ='".$row->id."'"); 

				            if(empty($get_current)){
                               

				            	 \DB::table('abserve_user_cart')->insert([

				                  'user_id' => $row->id , 
				                  'cookie_id' =>"",
				                  'food_item' =>"",
				                

				                 ]);

				            }

                                      						

						if(!is_null($request->input('language')))
						{
							\Session::put('lang', $request->input('language'));	
						} else {
							\Session::put('lang', 'en');	
						}  
							if(CNF_FRONT =='true') :
							return Redirect::to('dashboard');						
						else :
							if ($redirect = Session::get('redirect')) 
							{
						        Session::forget('redirect');
						        return Redirect::to($redirect);
						    }
						    else
						    {
						    	return Redirect::to('/dashboard');
						    }

						endif;							
											
					}			
					
				}			
				
			} else {
				return Redirect::to('user/login')
					->with('message', \SiteHelpers::alert('error','Your username/password combination was incorrect'))
					->withInput();
			}
		} else {
		
				return Redirect::to('user/login')
					->with('message', \SiteHelpers::alert('error','The following  errors occurred'))
					->withErrors($validator)->withInput();
		}	
	}

	public function postSignin( Request $request) {
		
		$rules = array(
			'email'=>'required|email',
			'password'=>'required',
		);		
		if(CNF_RECAPTCHA =='true') $rules['captcha'] = 'required|captcha';
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	

			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );
			
			if (\Auth::attempt(array('email'=>$request->input('email'), 'password'=> $request->input('password') ), $remember )) {
				if(\Auth::check())
				{
					$row = User::find(\Auth::user()->id); 
	
					if($row->active =='0')
					{
						// inactive 
						\Auth::logout();
						return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is not active'));
	
					} else if($row->active=='2')
					{
						// BLocked users
						\Auth::logout();
						return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is BLocked'));
					} else {
						\DB::table('tb_users')->where('id', '=',$row->id )->update(array('last_login' => date("Y-m-d H:i:s")));
						$region = \DB::table('region')->where('id', '=',$row->region )->first();
						\Session::put('uid', $row->id);
						\Session::put('gid', $row->group_id);
						\Session::put('eid', $row->email);
						\Session::put('ll', $row->last_login);
						\Session::put('fid', $row->first_name.' '. $row->last_name);	
						\Session::put('rid', $row->region);
						\Session::put('rkey', $region->region_keyword);

						/*Cart itmes*/
						$cookie_name = "mycart";
						$cookie_id = $this->getCartCookie();
				        if($cookie_id != ''){
				        	$updated = \SiteHelpers::CartCookieItem($cookie_id);
				        }
				        
				        /*$get_current = \DB::select("SELECT * from `abserve_user_cart` where `user_id` ='".$row->id."'");
				            if(empty($get_current)){
                               

				            	 \DB::table('abserve_user_cart')->insert([

				                  'user_id' => $row->id , 
				                  'cookie_id' =>"",
				                  'food_item' =>"",
				                 ]);
				            }*/

						if(!is_null($request->input('language')))
						{
							\Session::put('lang', $request->input('language'));	
						} else {
							\Session::put('lang', 'en');	
						}  

							if(CNF_FRONT =='true') :
						     if(Auth::user()->group_id == 4){
						     	return Redirect::to('');
						     }
						     elseif(Auth::user()->group_id == 3){
						     	return Redirect::to('user/profile');
						     }
						     else{
						     	return Redirect::to('/dashboard');
						     }		
								

						else :
							
							if ($redirect = Session::get('redirect')) 
							{
						        Session::forget('redirect');
						        return Redirect::to($redirect);
						    }
						    else
						    {
						    	return Redirect::to('/dashboard');
						    }

						endif;							
											
					}			
					
				}			
				
			} else {
				return Redirect::to('user/login')
					->with('message', \SiteHelpers::alert('error','Your username/password combination was incorrect'))
					->withInput();
			}
		} else {
		
				return Redirect::to('user/login')
					->with('message', \SiteHelpers::alert('error','The following  errors occurred'))
					->withErrors($validator)->withInput();
		}	
	}

    public static function setCartCookie(){
        $cookie_name = "mycart";
        $cart_cookie_val = uniqid();
        \Cookie::queue(\Cookie::make($cookie_name, $cart_cookie_val, 45000));
        return $cart_cookie_val;
    }
    public static function getCartCookie(){
        $cookie_name = "mycart";
        if(\Cookie::has($cookie_name) && \Cookie::get($cookie_name) != null)
        {
            return  \Cookie::get($cookie_name);
        }
        return '';
    }

	public function postPlogin( Request $request) {
		
		$rules = array(
			'email'=>'required|email',
			'password'=>'required',
		);		
		if(CNF_RECAPTCHA =='true') $rules['captcha'] = 'required|captcha';
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	

			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );
			
			if (\Auth::attempt(array('email'=>$request->input('email'), 'password'=> $request->input('password') ), $remember )) {

				if(\Auth::check())
				{
					$row = User::find(\Auth::user()->id); 
					
					if($row->active =='0')
					{
						// inactive 
						\Auth::logout();
						return '5';
	
					} else if($row->active=='2')
					{
						// BLocked users
						\Auth::logout();
						return '4';
					} else {
						\DB::table('tb_users')->where('id', '=',$row->id )->update(array('last_login' => date("Y-m-d H:i:s")));
						\Session::put('uid', $row->id);
						\Session::put('gid', $row->group_id);
						\Session::put('eid', $row->email);
						\Session::put('ll', $row->last_login);
						\Session::put('fid', $row->first_name.' '. $row->last_name);	
						if(!is_null($request->input('language')))
						{
							\Session::put('lang', $request->input('language'));	
						} else {
							\Session::put('lang', 'en');	
						}  
						 return '1';						
											
					}			
					
				}			
				
			} else {
				return '2';
			}
		} else {
			return '3';
		}	
	}

	public  function postEmail(Request $request) {
        $user_id = \Auth::user()->id; 
		$user = User::find($user_id);
		$prev_email = 	$user->email;
		$prev_name = $user->first_name;	
		$new_email = trim($request->input('email'));
		$new_name = trim($request->input('username'));
			$check_present = \DB::select("SELECT count(*) as cnt  from `tb_users` where `email` ='".$user->email."'"); 
		/*Email exist*/
		if($new_email != $prev_email){
			$emailval = \DB::select("SELECT * FROM `tb_users` WHERE `id` !=".$user_id."  AND `email` LIKE '".$new_email."' ");
			if(count($emailval) > 0){
				$email_validity = 0;
			} else {
				$email_validity = 1;
			}
		} else {
			$email_validity = 1;
		}
		if($check_present[0]->cnt > 0){
			if($email_validity == 1){
			 	if($new_email != $prev_email && $new_name != $prev_name){
			 		$user->email = trim($request->input('email'));
			 		$user->first_name = trim($request->input('username'));
			 		$user->save();	
			 		echo "1";
			 	} elseif($new_email != $prev_email && $new_name == $prev_name){
			 		$user->email = trim($request->input('email'));
			 		$user->save();	
			 		echo "2";
			 	} elseif($new_name != $prev_name && $new_email == $prev_email){
			 		$user->first_name = trim($request->input('username'));
			 		$user->save();
			 		echo "3";	
			 	}
			 } else {
			 	echo "6";
			 }
		} else {
			if($new_name != $prev_name  && $new_email == $prev_email)
            	echo "4";
            else 
            	echo "5";
		}
	}

	public function getProfile(Request $request) {
		if(!\Auth::check()) return redirect('user/login');
		/*$userid = Auth::user()->id; 
		$this->data['userid'] = $userid;*/

		$info =	User::find(\Auth::user()->id);

			$this->data = array(
				'pageTitle'	=> 'My Profile',
				'pageNote'	=> 'View Detail My Info',
				'info'		=> $info,
				'userid'	=> Auth::user()->id,
			);
			if($request->section == '')
				$this->data['section'] = '';
			else 
				$this->data['section'] = $request->section;
			return view('user.profile',$this->data);
	}

	public function postChangepassword(Request $request){
		$rules = array(
			'new_password'=>'required|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		$userid = \Auth::id();
		if (\Auth::attempt(array('id' => $userid, 'password' => $_POST['old_password'])))
        {
			try 
			{
				$user = \Auth::user();
				$user->password = bcrypt($_POST['new_password']);
				$user->save();
				echo "success";
				/*return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Password has been saved!'));*/
			} 
				catch (Exception $e) 
			{
				echo '';
			}
        }
        else
        {
        	echo "Invalid";
        	/*return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();*/
        }
	}

	public function postUserupdate(Request $request){
		if(!\Auth::check()) return Redirect::to('user/login');
		$userid = Auth::user()->id;
			
			if(!is_null(Input::file('avatar')))
			{
				$file = $request->file('avatar'); 
				$destinationPath = './uploads/users/';
				$filename = $file->getClientOriginalName();

				$extension = $file->getClientOriginalExtension(); //if you need extension of the file
				 $newfilename = \Session::get('uid').'.'.$extension;
				$uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
				    $data['avatar'] = $newfilename; 
				} 
				
			}		
			
			$user = User::find(\Session::get('uid'));
			$user->first_name 	= $request->input('first_name');
			$user->last_name 	= $request->input('last_name');
			$user->email 		= $request->input('email');
			if(isset( $data['avatar']))  $user->avatar  = $newfilename; 			
			$user->save();

		$first_name 	= $_POST['first_name'];
		$last_name 		= $_POST['last_name'];
		$email 			= $_POST['email'];
		$phno 			= $_POST['phno'];
		$address 		= $_POST['address'];
		$city 			= $_POST['city'];
		$state 			= $_POST['state'];
		$pin 			= $_POST['pin'];
		$country 		= $_POST['country'];
		$username 		= $_POST['username'];

		User::where('id', '=',$userid)
		->update(array('first_name' => $first_name,"last_name"=>$last_name,"username"=>$username,"email"=>$email,"phone_number"=>$phno,"address"=>$address,"address"=>$address,"city"=>$city,"state"=>$state,"zip_code"=>$pin,"country"=>$country));

		return Redirect::to('user/profile')->with('messagetext','Profile has been saved!')->with('msgstatus','success');
	}
	
	public function postSaveprofile( Request $request){
		if(!\Auth::check()) return Redirect::to('user/login');
		$rules = array(
			'first_name'=>'required|alpha_num|min:2',
			'last_name'	=>'required|alpha_num|min:2',
		);	
		if($request->input('email')){
			$rules['avatar'] = 'image|mimes:jpg,png,gif,jpeg';
		}
			
		if($request->input('email') != \Session::get('eid'))
		{
			$rules['email'] = 'required|email|unique:tb_users';
		}	
				
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			
			if(!is_null(Input::file('avatar')))
			{
				$file				= $request->file('avatar'); 
				$destinationPath	= './uploads/users/';
				$filename			= $file->getClientOriginalName();
				$extension			= $file->getClientOriginalExtension(); 
				$newfilename		= \Session::get('uid').time().'.'.$extension;
				$uploadSuccess		= $request->file('avatar')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
				    $data['avatar'] = $newfilename; 
					$userOldImage	= \Auth::user()->avatar;
					$imgPath		= '/uploads/users/'.$userOldImage;
					if(\File::exists(public_path($imgPath))){
						\File::delete(public_path($imgPath));
					}
				}
			}	
			
			$user = User::find(\Session::get('uid'));
			$user->first_name 	= $request->input('first_name');
			$user->last_name 	= $request->input('last_name');
			$user->email 		= $request->input('email');
			$user->phone_number = $request->input('phone_number');
			if(isset( $data['avatar']))	$user->avatar  = $newfilename;
			$user->save();

			return Redirect::to('user/profile')->with('messagetext','Profile has been saved!')->with('msgstatus','success');
		} else {
			return Redirect::to('user/profile')->with('messagetext','The following errors occurred')->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	}
	
	public function postSavepassword( Request $request){
		$rules = array(
			'password'=>'required|between:6,12|confirmed',
			'password_confirmation'=>'required|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$user = User::find(\Session::get('uid'));
			
			$user->password = \Hash::make($request->input('password'));
			$user->save();

			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Password has been saved!'));
		} else {
			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	
	}	
	
	public function getReminder(){
	
		return view('user.remind');
	}	

	public function postRequest( Request $request){
		//echo 'hai'; exit;
		$rules = array(
			'credit_email'=>'required|email'
		);	
		
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	
	
			$user =  User::where('email','=',$request->input('credit_email'));
			if($user->count() >=1)
			{
				$user = $user->get();
				$user = $user[0];
				$data = array('token'=>$request->input('_token'));	
				$to 		= $request->input('credit_email');
				$subject 	= "[ " .CNF_APPNAME." ] REQUEST PASSWORD RESET "; 			
				$message 	= view('user.emails.auth.reminder', $data);
				$headers  	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers 	.= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
					mail($to, $subject, $message, $headers);				
			
				
				$affectedRows = User::where('email', '=',$user->email)
								->update(array('reminder' => $request->input('_token')));
								
				return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success','Please check your email'));	
				
			} else {
				return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Cant find email address'));
			}

		}  else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	 
	}	
	
	public function getReset( $token = ''){
		if(\Auth::check()) return Redirect::to('dashboard');

		$user = User::where('reminder','=',$token);
		if($user->count() >=1)
		{
			$data = array('verCode'=>$token);
			return view('user.remind',$data);	
		} else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Cant find your reset code'));
		}
	}	
	
	public function postDoreset( Request $request , $token = ''){
		$rules = array(
			'password'=>'required|alpha_num|between:6,12|confirmed',
			'password_confirmation'=>'required|alpha_num|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			
			$user =  User::where('reminder','=',$token);
			if($user->count() >=1)
			{
				$data = $user->get();
				$user = User::find($data[0]->id);
				$user->reminder = '';
				$user->password = \Hash::make($request->input('password'));
				$user->save();			
			}

			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Password has been saved!'));
		} else {
			return Redirect::to('user/reset/'.$token)->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	
	}	

	public function getLogout() {
		\Auth::logout();
		\Session::flush();
		return Redirect::to('dashboard')->with('message', \SiteHelpers::alert('info','Your are now logged out!'));
	}

	public  function getAddress(Request $request) {
		$id = $request->id;
		if($request->key == 'delete')	{
			\DB::table('abserve_user_address')
						->where("id",'=',$id)
						->delete();
		}else{
			$details = array();
			$query = \DB::table('abserve_user_address')
						->where("id",'=',$id)
						->first();
			if(!empty($query)){
				$details['id'] = $query->id;
				$details['address'] = $query->address;
				$details['lat'] = $query->lat;
				$details['lang'] = $query->lang;
				$details['address_type'] = $query->address_type;
				$details['landmark'] = $query->landmark;
				$details['building'] = $query->building;
			}
			return json_encode($query);
		}
	}

	function getSocialize($social){
		return Socialize::with($social)->redirect();
	}

	function getAutosocial($social) {
		$user = Socialize::with($social)->user();
		$user =  User::where('email',$user->email)->first();
		return self::autoSignin($user);		
	}

	function autoSignin($user){

		if(is_null($user)){
		  return Redirect::to('user/login')
				->with('message', \SiteHelpers::alert('error','You have not registered yet '))
				->withInput();
		} else{

		    Auth::login($user);
			if(Auth::check())
			{
				$row = User::find(\Auth::user()->id); 

				if($row->active =='0')
				{
					// inactive 
					Auth::logout();
					return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is not active'));

				} else if($row->active=='2')
				{
					// BLocked users
					Auth::logout();
					return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is BLocked'));
				} else {
					Session::put('uid', $row->id);
					Session::put('gid', $row->group_id);
					Session::put('eid', $row->group_email);
					Session::put('fid', $row->first_name.' '. $row->last_name);	
					if(CNF_FRONT =='false') :
						return Redirect::to('dashboard');						
					else :
						return Redirect::to('');
					endif;					
				}
			}
		}
	}
	
	public function getMsg() {
		return view('user.message');
	}

	public function getAcntdetails() {
		if(!Auth::check()) 
			return Redirect::to('user/login');

		$part_balance 	= \DB::table('abserve_partner_balance')->where('partner_id',\Auth::id())->first();
		$host_transfer	= \DB::table('abserve_host_transfer')->select(\DB::raw('SUM(amount) as ramount'))->where('host_id',\Auth::id())->where('status',"!=",'Completed')->where('status',"!=",'Declined')->first();
		$host_transfer_withdrawal	= \DB::table('abserve_host_transfer')->select(\DB::raw('SUM(amount) as withdrawal'))->where('host_id',\Auth::id())->where('status','Completed')->first();
		$this->data['balance'] = $part_balance;
		$this->data['host_transfer'] = $host_transfer;
		$this->data['withdraw'] = $host_transfer_withdrawal;
		$this->data['avail_amnt'] = number_format((float)($part_balance->amount - $host_transfer_withdrawal->amount),2,'.','');
		$this->data['countries']=\SiteHelpers::country();
		$this->data['currency']=\SiteHelpers::allcurreny();
		$this->data['section'] = 'acnt';
		$this->data['withdrawal']	= $host_transfer_withdrawal->withdrawal;
		$this->data['ramount']	= $host_transfer->ramount;

		$this->data['total_Amount'] = number_format((float)($part_balance->balance + $host_transfer_withdrawal->withdrawal),2,'.','');
		$this->data['userDatas'] =	User::find(\Auth::user()->id);
		return view('user.acntdetails',$this->data);
	}

	public function postAddextbankdetails() {
		if(isset($_POST['addbank_details_sub'])) {	
			try {
				$abserve_stripe_settings 	=  DB::select("SELECT * FROM `abserve_stripe_settings` ");
				\Stripe\Stripe::setApiKey($abserve_stripe_settings[0]->private_key);
				$account =  \Stripe\Account::create(
				    array(
				        "country" => $_POST['country_code'],
				        "type" => "custom",
				        'email' =>  $_POST['email'],
				        "legal_entity" => array(
				            'address' => array(
				                'city' =>  $_POST['city'],
				                'country' =>  $_POST['country_code'],
				                "line1" => $_POST['line1'],
				                "line2" =>  $_POST['line2'],
				                "postal_code" =>  $_POST['postal_code'],
				                "state" =>  $_POST['state']
				            ),
				            'business_name' =>  $_POST['business_name'],
				            'business_tax_id' =>  $_POST['business_tax_id'],
				            'dob' => array(
				                'day' =>  $_POST['dd'],
				                'month' =>  $_POST['mm'],
				                'year' =>  $_POST['yyyy']
				            ),
				            'first_name' =>  $_POST['fname'],
				            'last_name' =>  $_POST['lname'],
				            'personal_id_number' =>  $_POST['pers_id'],
				           // 'ssn_last_4' => '0000',
				            'type' =>  $_POST['ac_type']
				        ),
				        'tos_acceptance' => array(
				            'date' => time(),
				            'ip' => $_SERVER['REMOTE_ADDR']
				        ),
				        /* Transfer Schedule */
				        'payout_schedule' => array(
				            'delay_days' => 7,
				            'interval' => 'daily' //daily, weekly, or monthly
				        ),

				        'external_account' => array(
				            "object" => "bank_account",
				            "country" =>  $_POST['country_code'],
				            "currency" =>  $_POST['currency'],
				            "account_holder_name" =>  $_POST['fname'],
				            "account_holder_type" =>  $_POST['ac_type'],
				           // "IBAN" => "FR1420041010050500013M02606",
				            //"routing_number" => "DE89370400440532013000",
				            "account_number" =>  $_POST['iban']
				        )
				    )
				);
				//print_r($account);die();
				$acc_id=$account->id;
			} catch (\Stripe\Error\ApiConnection $e) {
			    // Network problem, perhaps try again.
			    $e_json = $e->getJsonBody();
			    $error = $e_json['error'];
			} catch (\Stripe\Error\InvalidRequest $e) {
			    // You screwed up in your programming. Shouldn't happen!
			    $e_json = $e->getJsonBody();
			    $error = $e_json['error'];
			} catch (\Stripe\Error\Api $e) {
				$e_json = $e->getJsonBody();
			    $error = $e_json['error'];
			    // Stripe's servers are down!
			} catch (\Stripe\Error\Card $e) {
				$e_json = $e->getJsonBody();
			    $error = $e_json['error'];
			    // Card was declined.
			}
			if(empty($error))
			{
				$update=\DB::table('tb_users')->where('id',Auth::user()->id)->update(array('ext_acc_id'=>$acc_id));
				return Redirect::to('user/acntdetails')->with('message',\SiteHelpers::alert('success',"success"))->withInput();
			}
			else
			{
				return Redirect::to('user/acntdetails')->with('message',\SiteHelpers::alert('error',$error))->withInput();
			}
		}
	}

	public function sendTransferRequest(Request $request) {

    	$data = $request->all();
		$part_balance 	= \DB::table('abserve_partner_balance')->where('partner_id',\Auth::id())->first();
		$host_transfer	= \DB::table('abserve_host_transfer')->select(\DB::raw('SUM(amount) as ramount'))->where('host_id',\Auth::id())->where('status',"!=",'Completed')->where('status',"!=",'Declined')->first();
		$host_transfer_withdrawal	= \DB::table('abserve_host_transfer')->select(\DB::raw('SUM(amount) as withdrawal'))->where('host_id',\Auth::id())->where('status','Completed')->first();
		$this->data['balance'] = $part_balance;
		$this->data['host_transfer'] = $host_transfer;
		$this->data['withdraw'] = $host_transfer_withdrawal;
		$this->data['avail_amnt'] = number_format((float)($part_balance->balance - $host_transfer_withdrawal->amount),2,'.','');
		$this->data['countries']=\SiteHelpers::country();
		$this->data['currency']=\SiteHelpers::allcurreny();
		$this->data['section'] = 'acnt';

		$withdrawal	= $host_transfer_withdrawal->withdrawal;
		$ramount	= $host_transfer->ramount;

		$total_Amount = number_format((float)($part_balance->balance + $host_transfer_withdrawal->withdrawal),2,'.','');
		$avail_amount=($total_Amount-($ramount+$withdrawal));

		if($data['tot_amt'] <= $avail_amount){
			
			if($data['request_id'] == '')
				$htransfer = new Hosttransfer;
			else
				$htransfer = Hosttransfer::find($data['request_id']);

			$htransfer->host_id = \Auth::id();
			$htransfer->amount	= $data['tot_amt'];
			$htransfer->status	= 'Requested';
			$htransfer->created	= time();
			$htransfer->created_at	= date('Y-m-d H:i:s');
			$htransfer->updated_at	= date('Y-m-d H:i:s');
			//print_r($htransfer);
			$hrequest = $htransfer->save();
			
			$notify = new Notification;
			$notify->userid		= 1;
			$notify->url		= \URL::to('');
			$notify->title		= 'Host amount transfer Request';
			$notify->note		= \Auth::user()->first_name.' '.\Auth::user()->last_name.' requested '.$data['tot_amt'].' $ amount transfer from their wallet into their personal account';
			$notify->created	= date('Y-m-d H:i:s');
			//$notify->created_at	= date('Y-m-d H:i:s');
			//$notify->updated_at	= date('Y-m-d H:i:s');
			$adminnotify = $notify->save();
			
			if($hrequest)
				$response['status'] = 'success';
			else
				$response['status'] = 'failed';
		} else {
			$response['status']		= 'amount_exceed';
		}

		return \Response::json($response);
	}

	public function postCheckmail( Request $request)
	{
		$rules = array(
			'email'			=> 'required|email|unique:tb_users',
			'phone_number'	=> 'required|regex:/[0-9]{9}/|unique:tb_users',
		);
		$validator = Validator::make($request->all(), $rules);
		if($validator->passes()){
			$response["id"]			= 1;
			$response["message"]	= 'success';
		} else {
			$messages	= $validator->messages();
			$error		= $messages->getMessages();
			$val		= $this->getStepvalidation($error);
			if(isset($error['phone_number'])){
				$response["field"]	= 'phone_number';
			} elseif (isset($error['email'])) {
				$response["field"]	= 'email';
			}
			$response["id"]			= 0;
			$response["message"]	= $val;
		}
		return \Response::json($response);
	}
}
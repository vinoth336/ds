<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /*Google Social Login*/
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        $user = Socialite::driver('google')->user();
        if($user->email != '') {
            $authUser = $this->find($user);
            Auth::login($authUser,true);
            return redirect()->to('/user/login');
        } else {
            return redirect()->to('/user/msg'); 
        }
    }

    private function find($emailUser) {
        $authUser = User::where('email',$emailUser->email)->first();
        if(!$authUser) {
            $user_name  = explode(' ', $emailUser->name);
            $code       = rand(10000,10000000);

            $authen                 = new User;
            $authen->username       = $emailUser->name;
            $authen->first_name     = $user_name[0];
            //$authen->last_name      = $user_name[1]; 
            $authen->email          = $emailUser->email;
            $authen->avatar         = $emailUser->avatar;
            $authen->activation     = $code;

            $authen->group_id       = '4';
            $authen->password       = '';
            $authen->active         = '1';
            $authen->save();
       
            $id = \DB::getPdo()->lastInsertId();
            if($emailUser->avatar != ''){

                $url    = $emailUser->avatar;
                $name   = $id.".jpg";
                
                $img    = base_path()."/uploads/users/".$name;
                $suc    = file_put_contents($img, file_get_contents($url));

                if($suc){
                    $image  = ['avatar'=>$name];
                    $query1 = \DB::table('tb_users')->where('id','=', $id)->update($image);
                }
            }
            $authUser = User::where('id', $id)->first(); 
        } 
        return $authUser;
    }

    /*Facebook login*/
    public function redirectToProvider() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback() {
        try{
        $user = Socialite::driver('facebook')->user();
        }catch(Exception $e){
            return redirect()->to('/user/login');
        }
        if($user->email != '') {
            $authUser = $this->findOrCreateUser($user);
            Auth::login($authUser,true);
            return redirect()->to('/user/login');
        } else {
            return redirect()->to('/user/msg');
        }
    }

    private function findOrCreateUser($facebookUser)
    {
        $authUser = User::where('email', $facebookUser->email)->first();
 
        if (!$authUser){

            $user_name  = explode(' ', $facebookUser->name);
            $code       = rand(10000,10000000);

            $authen                 = new User;
            $authen->first_name     = $user_name[0];
            //$authen->last_name      = $user_name[1];
            $authen->email          = trim($facebookUser->email);
            $authen->activation     = $code;

            $authen->group_id       = '4';
            $authen->password       = '';
            $authen->active         = '1';
            $authen->save();

            $id = \DB::getPdo()->lastInsertId();
            if($facebookUser->avatar != ''){

                $url    = $facebookUser->avatar;
                $name   = $id.".jpg";
                
                $img    = base_path()."/uploads/users/".$name;
                $suc    = file_put_contents($img, file_get_contents($url));

                if($suc){
                    $image  = ['avatar'=>$name];
                    $query1 = \DB::table('tb_users')->where('id','=', $id)->update($image);
                }
            }

            $authUser = User::where('id', $id)->first();            
        }
        return $authUser;
    }

    /*Twitter social login*/
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }
     public function handleTwitterCallback()
    {
        $user = Socialite::driver('twitter')->user();
        if($user->email != ''){
            $authUser   = $this->findOrCreateUsers($user);
            Auth::login($authUser, true);
            return redirect()->to('/user/login');
        } else {
           return redirect()->to('/user/msg'); 
        } 
    }
    private function findOrCreateUsers($twitterUser)
    {
        $authUser = User::where('email', $twitterUser->email)->first();
        
 
        if (!$authUser){

            $user_name  = explode(' ', $twitterUser->name);
            $code       = rand(10000,10000000);

            $authen                 = new User;
            $authen->username       = $twitterUser->nickname;
            $authen->first_name     = $user_name[0];
            //$authen->last_name      = $user_name[1];
            $authen->email          = trim($twitterUser->email);
            $authen->activation     = $code;

            $authen->group_id       = '4';
            $authen->password       = '';
            $authen->active         = '1';
            $authen->save();

            $id = \DB::getPdo()->lastInsertId();
            if($twitterUser->avatar != ''){

                $url    = $twitterUser->avatar;
                $name   = $id.".jpg";
                
                $img    = base_path()."/uploads/users/".$name;
                $suc    = file_put_contents($img, file_get_contents($url));

                if($suc){
                    $image  = ['avatar'=>$name];
                    $query1 = \DB::table('tb_users')->where('id','=', $id)->update($image);
                }
            }

            $authUser = User::where('id', $id)->first();            
        }
        return $authUser;
    }
}

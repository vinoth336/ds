<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* Abserve Routers */
Route::get('/', 'HomeController@index');
/* Abserve Routers */ 


//Implicit Controller
//Reference https://laravel.com/docs/5.1/controllers#implicit-controllers
Route::controller('/user', 'UserController');
Route::controller('/frontend', 'FrontEndController');
Route::controller('/payment', 'OmniPaymentController');
Route::controller('/email', 'EmailController');
//Find the nearest restaurant
Route::post('home/nearrest', 'HomeController@Nearrest');
Route::post('home/nearrest_place', 'HomeController@Nearrestplace');
Route::post('home/contact','HomeController@postContact');
Route::get('home/currency/{name}', 'HomeController@getCurrency');
Route::get('home/lang/{name}', 'HomeController@getLang');
// Image upload
Route::post('users/ajax_image_upload', 'UsersController@Imageupload');
// Change password
Route::post('users/changepassword', 'UsersController@Changepassword');

// To get access token
Route::post('oauth/access_token', function() {
	return Response::json(Authorizer::issueAccessToken());
});

// Verify and get user from access token
Route::get('api', ['middleware' => 'oauth', function() {
	// return the protected resource
	//echo “success authentication”;
	$user_id=Authorizer::getResourceOwnerId(); // the token user_id
	$user=\App\User::find($user_id);// get the user data from database
	return Response::json($user);
}]);

Route::group(['prefix' => 'mobile/api','middleware' => 'oauth'], function() {
	//Authorizer::getResourceOwnerId();
	Route::controllers([
		'user'		=> 'mobile\UsersecureController',
	]);
});



//Route::get('hotel/hotelresults', 'HotelFrontController@getView');
include('pageroutes.php');
include('moduleroutes.php');

Route::get('/restric',function(){

	return view('errors.blocked');

});

Route::controllers([
	'mobile/user'		=> 'mobile\UserController',
	'mobile/restaurant'	=> 'mobile\RestaurantController',
	'mobile/orders'		=> 'mobile\OrdersController',
	'mobile/usercart'	=> 'mobile\UsercartController',
]);



Route::controllers([
	'mobile/test/user'		=> 'mobile\test\UserController',
	'mobile/test/restaurant'	=> 'mobile\test\RestaurantController',
	'mobile/test/orders'		=> 'mobile\test\OrdersController',
	'mobile/test/usercart'	=> 'mobile\test\UsercartController',
]);


Route::resource('abserveapi', 'abserveapiController'); 
Route::group(['middleware' => 'auth'], function()
{

	Route::get('core/elfinder', 'Core\ElfinderController@getIndex');
	Route::post('core/elfinder', 'Core\ElfinderController@getIndex'); 
	Route::controller('/dashboard', 'DashboardController');
	Route::controllers([
		'core/users'		=> 'Core\UsersController',
		'notification'		=> 'NotificationController',
		'core/logs'			=> 'Core\LogsController',
		'core/pages' 		=> 'Core\PagesController',
		'core/groups' 		=> 'Core\GroupsController',
		'core/template' 	=> 'Core\TemplateController',
	]);

});	

Route::group(['middleware' => 'auth' , 'middleware'=>'abserveauth'], function()
{

	Route::controllers([
		'abserve/menu'		=> 'abserve\MenuController',
		'abserve/config' 		=> 'abserve\ConfigController',
		'abserve/module' 		=> 'abserve\ModuleController',
		'abserve/tables'		=> 'abserve\TablesController'
	]);			



});
/*Social Login*/
Route::get('auth/google','Auth\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\AuthController@handleGoogleCallback');
/*Facebook*/
Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');
/*Twitter*/
Route::get('auth/twitter', 'Auth\AuthController@redirectToTwitter');
Route::get('auth/twitter/callback', 'Auth\AuthController@handleTwitterCallback');
Route::post('totransfermoney',['as' => 'totransfermoney', 'uses' => 'UserController@sendTransferRequest']);
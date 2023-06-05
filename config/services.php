<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],

	'google' => [
	   	'client_id' 	=> '788586136548-eqjrtshkj2kkf34mg1jpbv8dog48k7l2.apps.googleusercontent.com',
	    'client_secret' => 'tvC2isDWIOAX7yl_f8_mbgx_',
	    'redirect' 		=> 'http://abservetechdemo.com/products/foodstar/auth/google/callback',
	],

	'twitter' => [
	    'client_id' 	=> 'QZHSkGUbRO54bNSouoeGL2f89',
	    'client_secret' => 'qxIjpLGCqCgfjr4LDZ09wZwKznVFo0zhbxMw3sZ4rZAUKGXesm',
	    'redirect' 		=> 'http://abservetechdemo.com/products/foodstar/auth/twitter/callback',
	],

	'facebook' => [
	    'client_id' 	=> '180092999408869',
	    'client_secret' => '53e0ed2ceb992d93163c3b5950e8430d',
	    'redirect' 		=> 'http://localhost/abservetech/foodstar/auth/facebook/callback',
	],		

];

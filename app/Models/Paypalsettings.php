<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class paypalsettings extends Abserve  {
	
	protected $table = 'abserve_paypal_settings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_paypal_settings.* FROM abserve_paypal_settings  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_paypal_settings.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

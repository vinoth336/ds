<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class usercart extends Abserve  {
	
	protected $table = 'abserve_user_cart';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_user_cart.* FROM abserve_user_cart  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_user_cart.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

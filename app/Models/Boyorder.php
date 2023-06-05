<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class boyorder extends Abserve  {
	
	protected $table = 'abserve_orders_boy';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_orders_boy.* FROM abserve_orders_boy  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_orders_boy.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

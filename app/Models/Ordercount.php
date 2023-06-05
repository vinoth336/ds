<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ordercount extends Abserve  {
	
	protected $table = 'abserve_order_details';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_order_details.* FROM abserve_order_details  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_order_details.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

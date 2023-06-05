<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class servicedeliveryboy extends Abserve  {
	
	protected $table = 'service_deliveryboy';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT service_deliveryboy.* FROM service_deliveryboy  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE service_deliveryboy.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class deliveryboydistance extends Abserve  {
	
	protected $table = 'abserve_deliveryboys';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_deliveryboys.* FROM abserve_deliveryboys  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_deliveryboys.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

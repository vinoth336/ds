<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class deliverypoint extends Abserve  {
	
	protected $table = 'delivery_point';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT delivery_point.* FROM delivery_point  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE delivery_point.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

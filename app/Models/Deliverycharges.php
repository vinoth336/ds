<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class deliverycharges extends Abserve  {
	
	protected $table = 'delivery_charges';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT delivery_charges.* FROM delivery_charges  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE delivery_charges.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

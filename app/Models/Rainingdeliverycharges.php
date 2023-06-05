<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class rainingdeliverycharges extends Abserve  {
	
	protected $table = 'raining_delivery_charges';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT raining_delivery_charges.* FROM raining_delivery_charges  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE raining_delivery_charges.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

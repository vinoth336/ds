<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class deliverychargetype extends Abserve  {
	
	protected $table = 'delivery_charge_type';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT delivery_charge_type.* FROM delivery_charge_type  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE delivery_charge_type.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

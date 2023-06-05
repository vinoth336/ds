<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class orderamountlog extends Abserve  {
	
	protected $table = 'order_amount_update';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT order_amount_update.* FROM order_amount_update  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE order_amount_update.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

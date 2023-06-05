<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class deliverytime extends Abserve  {
	
	protected $table = 'delivery_time';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT delivery_time.* FROM delivery_time  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE delivery_time.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class restaurantstatus extends Abserve  {
	
	protected $table = 'abserve_restaurants';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_restaurants.* FROM abserve_restaurants  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_restaurants.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

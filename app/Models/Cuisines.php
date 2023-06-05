<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class cuisines extends Abserve  {
	
	protected $table = 'abserve_food_cuisines';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_food_cuisines.* FROM abserve_food_cuisines  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_food_cuisines.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class foodcustomization extends Abserve  {
	
	protected $table = 'abserve_food_customization';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_food_customization.* FROM abserve_food_customization  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_food_customization.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

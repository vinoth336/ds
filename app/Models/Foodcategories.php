<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class foodcategories extends Abserve  {
	
	protected $table = 'abserve_food_categories';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_food_categories.* FROM abserve_food_categories  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_food_categories.id IS NOT NULL AND root_id =0 ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

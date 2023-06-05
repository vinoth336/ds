<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class toppingcategories extends Abserve  {
	
	protected $table = 'topping_categories';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT topping_categories.* FROM topping_categories  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE topping_categories.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

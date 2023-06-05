<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class foodbanner extends Abserve  {
	
	protected $table = 'food_banner';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT food_banner.* FROM food_banner  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE food_banner.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

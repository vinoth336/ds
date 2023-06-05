<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class servicesubcategories extends Abserve  {
	
	protected $table = 'service_categories';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT service_categories.* FROM service_categories  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE service_categories.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

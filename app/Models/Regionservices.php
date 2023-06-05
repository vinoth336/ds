<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class regionservices extends Abserve  {
	
	protected $table = 'region_services';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT region_services.* FROM region_services  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE region_services.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

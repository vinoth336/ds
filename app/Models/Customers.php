<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class customers extends Abserve  {
	
	protected $table = 'abserve_customers';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_customers.* FROM abserve_customers  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_customers.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

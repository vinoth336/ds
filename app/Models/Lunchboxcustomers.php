<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class lunchboxcustomers extends Abserve  {
	
	protected $table = 'lunch_box_customers';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT lunch_box_customers.* FROM lunch_box_customers  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE lunch_box_customers.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

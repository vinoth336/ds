<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class toppings extends Abserve  {
	
	protected $table = 'toppings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT toppings.* FROM toppings  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE toppings.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

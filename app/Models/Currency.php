<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class currency extends Abserve  {
	
	protected $table = 'abserve_currency';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_currency.* FROM abserve_currency  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_currency.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

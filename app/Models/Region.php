<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class region extends Abserve  {
	
	protected $table = 'region';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT region.* FROM region  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE region.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

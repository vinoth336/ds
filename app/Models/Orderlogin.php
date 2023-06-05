<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class orderlogin extends Abserve  {
	
	protected $table = 'orderlogin';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT orderlogin.* FROM orderlogin  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE orderlogin.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

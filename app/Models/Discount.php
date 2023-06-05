<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class discount extends Abserve  {
	
	protected $table = 'coupon';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT coupon.* FROM coupon  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE coupon.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class partnerorders extends Abserve  {
	
	protected $table = 'abserve_orders_partner';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_orders_partner.* FROM abserve_orders_partner  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_orders_partner.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class orderdetails extends Abserve  {
	
	protected $table = 'abserve_orders_partner';
	protected $primaryKey = 'id';
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_orders_partner.* FROM abserve_orders_partner";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_orders_partner.id IS NOT NULL AND abserve_orders_partner.order_status = '0' ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public function resname($oid = ''){
		return \DB::select("SELECT `od`.`res_id`,`ar`.`name` FROM `abserve_order_details` as `od` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `od`.`res_id` WHERE `od`.`id` = ".$oid);
	}
	

}

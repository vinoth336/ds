<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class neworders extends Abserve  {
	
	protected $table = 'abserve_orders';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_orders.* FROM abserve_orders  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_orders.id IS NOT NULL  AND abserve_orders.order_status = '0' ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	public function resname($oid = ''){
		return \DB::select("SELECT `od`.`res_id`,`ar`.`name` FROM `abserve_order_details` as `od` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `od`.`res_id` WHERE `od`.`id` = ".$oid);
	}
	public function partnerphone($pid = ''){
		
		return \DB::select("SELECT phone_number FROM `abserve_partners`  WHERE `id` = ".$pid);
	}
	

}

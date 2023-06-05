<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class customerorder extends Abserve  {
	
	protected $table = 'abserve_orders_customer';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_orders_customer.* FROM abserve_orders_customer  ";
		//return "  SELECT `oc`.*,`ar`.name FROM abserve_orders_customer as `oc` JOIN `abserve_restaurants` as `ar` ON `ar`.`id` = `oc`.`res_id` ";
	}	

	public static function queryWhere(  ){
		if(session()->get('gid') == '1'){
		return "  WHERE abserve_orders_customer.id IS NOT NULL ";
		//return "  WHERE `oc`.id IS NOT NULL ";
		}elseif(session()->get('gid') == '7'){
			
		    $region_id = session()->get('rid');
			$users = \DB::table('tb_users')->select('id')->where('region','=',$region_id)->where('group_id','=',4)->get();
			foreach($users as $user){
				$user_ids[] = $user->id;
			}
			$ids = implode(",",$user_ids);	
			return "  WHERE abserve_orders_customer.cust_id IN (".$ids.") ";
			
		}
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

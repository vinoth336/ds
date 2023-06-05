<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class cashondeliveryorder extends Abserve  {
	
	protected $table = 'abserve_order_details';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		if((\Auth::user()->group_id == 1) || (\Auth::user()->group_id == 6))
			return "  SELECT abserve_order_details.* FROM abserve_order_details  ";
		else
			return "  SELECT abserve_order_details.* FROM abserve_order_details  INNER JOIN abserve_orders_partner ON `abserve_order_details`.id = `abserve_orders_partner`.orderid ";
	}	

	public static function queryWhere(  ){
		
		$date = date('Y-m-d');
		$dayofweek = date('w', strtotime($date));
		
		if($dayofweek == 0){
			$result = date('Y-m-d', strtotime('-11 day', strtotime($date)));
		} else {
			$last_sunday = date('Y-m-d', strtotime('last Sunday', strtotime($date)));
			$result = date('Y-m-d', strtotime('-11 day', strtotime($last_sunday)));	
		}
		
		if((\Auth::user()->group_id == 1) || (\Auth::user()->group_id == 6))
			return "  WHERE abserve_order_details.id IS NOT NULL AND abserve_order_details.delivery_type = 'cod' AND (status ='4' OR status ='6' OR status ='10' OR status ='11') AND (`abserve_order_details`.`date` BETWEEN '".$result."' AND '".$date."') ";
		else
			return "  WHERE abserve_order_details.id IS NOT NULL AND abserve_order_details.delivery_type = 'cod' AND abserve_orders_partner.partner_id = ".\Auth::user()->id." AND (status ='4' OR status ='6' OR status ='10' OR status ='11') AND (`abserve_order_details`.`date` BETWEEN '".$result."' AND '".$date."') ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

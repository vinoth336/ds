<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class fooditems extends Abserve  {
	
	protected $table = 'abserve_hotel_items';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		return "  SELECT DISTINCT(abserve_hotel_items.`restaurant_id`) FROM abserve_hotel_items ";
	}	

	public static function queryWhere(  ){
		$whr = '';
		if(\Auth::user()->group_id != 1) {$whr = "AND abserve_hotel_items.entry_by = ".\Auth::id();}
		return "  WHERE abserve_hotel_items.id IS NOT NULL ".$whr;
	}
	
	public static function queryGroup(){
		return " ";
	}

	public function getResdetail($res_id){
		return \DB::select("  SELECT abserve_restaurants.`logo`,`name`,`budget` FROM abserve_restaurants WHERE `id` = ".$res_id);
	}
	
	public function getUserrestaurants($user_id,$group_id){
		if($group_id != 1)
			return \DB::select("SELECT abserve_restaurants.* FROM abserve_restaurants WHERE `partner_id` = ".$user_id);
		else
			return \DB::select("SELECT abserve_restaurants.* FROM abserve_restaurants ");
	}

}

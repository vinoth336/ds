<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class buyget extends Abserve  {
	
	protected $table = 'abserve_hotel_items';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  ";
	}	

	public static function queryWhere(  ){
		
		if(session()->get('gid') == '7'){
			$region_key = session()->get('rkey');
			$ids 	= self::getUserids($region_key);			
			return " SELECT * FROM `abserve_hotel_items` WHERE `bogo_item_id`!='0' AND `restaurant_id` IN ('".$ids."') ";
		} else {
			return " SELECT * FROM `abserve_hotel_items` WHERE `bogo_item_id`!='0' ";
		}
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public static function getUserids($region_key){
		
		$users = \DB::table('abserve_restaurants')->select('id')->where('region','=',$region_key)->get();
		foreach($users as $user){
			$user_ids[] = $user->id;
		}
		$ids = implode(",",$user_ids);
		return $ids;
		
	}

}

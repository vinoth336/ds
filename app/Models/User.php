<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class user extends Abserve  {
	
	protected $table = 'abserve_hotels';
	protected $primaryKey = 'hotel_id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_hotels.* FROM abserve_hotels  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_hotels.hotel_id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	public static function getHotelCountbyCityandCountry($city = '', $country = '')
	{
		$query = "SELECT count(`hotel_id`) as `cou` FROM `abserve_hotels`";
		$whropen = 'WHERE 1';
		$whr = '';
		if($city != '' && $country != '')
			$whr .= ' AND `city` ="'.$city.'" AND `country` ="'.$country.'"';
		elseif($city != '')
			$whr .= ' AND `city` ='.$city.'"';
		elseif($country != '')
			$whr .= ' AND `country` ='.$country.'"';

		return array( \DB::select( $query . $whropen . $whr) , $whr );
	}

	public static function getData($query,$cond = '')
	{

		return \DB::select( $query . $cond );
	}

}

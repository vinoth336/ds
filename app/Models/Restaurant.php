<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class restaurant extends Abserve  {
	
	protected $table = 'abserve_restaurants';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_restaurants.* FROM abserve_restaurants  ";
	}	

	public static function queryWhere(  ){
		
		if(session()->get('gid') == '1'){
		
		return "  WHERE abserve_restaurants.id IS NOT NULL ";
	
	   }elseif(session()->get('gid') == '7'){
	
	    return "  WHERE abserve_restaurants.region ='".session()->get('rkey')."' AND abserve_restaurants.id IS NOT NULL";
	 
	   }
	}
	

	public static function queryGroup(){
		return "  ";
	}

	public static function getlogo(){
		return \DB::select("SELECT `id`,`logo` FROM `abserve_restaurants` ");
	}
	
	public function resrating($id=''){
		$overall_rate 	= $this->getRating($id);
		$round_overall	= round($overall_rate);
		return $round_overall;
	}

	public function getRating($id){
		$star_1 = \DB::select("SELECT count(rating)as rating1 FROM `abserve_rating` WHERE `res_id` = ".$id." AND `rating` = 1");
		$star_2 = \DB::select("SELECT count(rating)as rating2 FROM `abserve_rating` WHERE `res_id` = ".$id." AND `rating` = 2");
		$star_3 = \DB::select("SELECT count(rating)as rating3 FROM `abserve_rating` WHERE `res_id` = ".$id." AND `rating` = 3");
		$star_4 = \DB::select("SELECT count(rating)as rating4 FROM `abserve_rating` WHERE `res_id` = ".$id." AND `rating` = 4");
		$star_5 = \DB::select("SELECT count(rating)as rating5 FROM `abserve_rating` WHERE `res_id` = ".$id." AND `rating` = 5");

		$str_1 = $star_1[0]->rating1;
		$str_2 = $star_2[0]->rating2;
		$str_3 = $star_3[0]->rating3;
		$str_4 = $star_4[0]->rating4;
		$str_5 = $star_5[0]->rating5;

		$total_count = $str_5 + $str_4 + $str_3 + $str_2 + $str_1;

		$Rating = (($str_5 * 5) + ($str_4 * 4) + ($str_3 * 3) + ($str_2 * 2) + ($str_1 * 1));
		if($total_count == 0 || $Rating == 0) {
			$tot = 0;
			return $tot;
		}
		else{
		$tot = ($Rating/$total_count);
		return $tot;
		}
	}

}

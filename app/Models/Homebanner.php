<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class homebanner extends Abserve  {
	
	protected $table = 'home_banner';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT home_banner.* FROM home_banner  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE home_banner.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

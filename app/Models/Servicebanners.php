<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class servicebanners extends Abserve  {
	
	protected $table = 'service_banners';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT service_banners.* FROM service_banners  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE service_banners.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

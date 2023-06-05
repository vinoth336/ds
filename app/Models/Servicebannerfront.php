<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class servicebannerfront extends Abserve  {
	
	protected $table = 'service_banner_fr';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT service_banner_fr.* FROM service_banner_fr  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE service_banner_fr.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

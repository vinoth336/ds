<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class banners extends Abserve  {
	
	protected $table = 'banners';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT banners.* FROM banners  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE banners.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class zone extends Abserve  {
	
	protected $table = 'zone';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT zone.* FROM zone  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE zone.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

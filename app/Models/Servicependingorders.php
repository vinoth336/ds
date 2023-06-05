<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class servicependingorders extends Abserve  {
	
	protected $table = 'service_form';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT service_form.* FROM service_form  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE service_form.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

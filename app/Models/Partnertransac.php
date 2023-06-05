<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class partnertransac extends Abserve  {
	
	protected $table = 'abserve_partner_balance';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_partner_balance.* FROM abserve_partner_balance  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_partner_balance.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

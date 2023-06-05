<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class subscriptionplan extends Abserve  {
	
	protected $table = 'subscription_plan';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT subscription_plan.* FROM subscription_plan  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE subscription_plan.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

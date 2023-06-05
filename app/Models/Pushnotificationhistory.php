<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class pushnotificationhistory extends Abserve  {
	
	protected $table = 'push_notification';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT push_notification.* FROM push_notification  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE push_notification.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class lbstudleaves extends Abserve  {
	
	protected $table = 'lunchbox_leave_days';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT lunchbox_leave_days.* FROM lunchbox_leave_days  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE lunchbox_leave_days.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

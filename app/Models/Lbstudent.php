<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class lbstudent extends Abserve  {
	
	protected $table = 'lunch_box_student_info';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT lunch_box_student_info.* FROM lunch_box_student_info  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE lunch_box_student_info.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

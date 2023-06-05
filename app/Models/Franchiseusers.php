<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class franchiseusers extends Abserve  {
	
	protected $table = 'tb_users';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_users.* FROM tb_users  ";
	}	

	public static function queryWhere(  ){
		
		if(session()->get('gid') == '7'){
			$region = \Session::get('rid');
			return "  WHERE tb_users.id IS NOT NULL AND tb_users.group_id=7 and tb_users.region=".$region." ";
		} else {
			return "  WHERE tb_users.id IS NOT NULL AND tb_users.group_id=7 ";
		}
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

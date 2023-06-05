<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class partners extends Abserve  {
	
	protected $table = 'tb_users';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_users.* FROM tb_users  ";
	}	

	public static function queryWhere(  ){
		if(session()->get('gid') == '1'){
			return "  WHERE tb_users.id IS NOT NULL and tb_users.group_id=3 ";
		} elseif(session()->get('gid') == '7'){
			$region = \Session::get('rid');
			return "  WHERE tb_users.id IS NOT NULL and tb_users.group_id=3 and tb_users.region=".$region." ";
		}
	}
	
	public static function queryGroup(){
		return "  ";
	}	

}

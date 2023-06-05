<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class customer extends Abserve  {
	
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
		return "  WHERE tb_users.id IS NOT NULL AND tb_users.group_id = 4 AND tb_users.region !='1' AND tb_users.region !='2' AND tb_users.region !='4'";
	    }elseif(session()->get('gid') == '7'){
		return "  WHERE tb_users.id IS NOT NULL AND tb_users.group_id = 4 AND tb_users.region ='".session()->get('rid')."'";	
		}
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

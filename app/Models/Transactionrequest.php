<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class transactionrequest extends Abserve  {
	
	protected $table = 'abserve_host_transfer';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_host_transfer.* FROM abserve_host_transfer  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_host_transfer.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

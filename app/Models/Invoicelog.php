<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class invoicelog extends Abserve  {
	
	protected $table = 'invoice_log';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT invoice_log.* FROM invoice_log  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE invoice_log.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

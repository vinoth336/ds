<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class transactionhistory extends Abserve  {
	
	protected $table = 'abserve_partner_wallet';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_partner_wallet.* FROM abserve_partner_wallet  ";
	}	

	public static function queryWhere(  ){

		$user_id =\Auth::id(); 

	    $where ="";

	    if($user_id !=1) {
           
           $where .= "AND abserve_partner_wallet.partner_id =".$user_id."";

	    }    
		
		return "  WHERE abserve_partner_wallet.id IS NOT NULL ".$where."";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class paymentorder extends Abserve  {
	
	protected $table = 'abserve_payment_order';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_payment_order.* FROM abserve_payment_order  ";
	}	

	public static function queryWhere(  ){

	    $user_id =\Auth::id(); 

	    $where ="";

	    if($user_id != 1) {
           
           $where .= "AND abserve_payment_order.partner_id =".$user_id."";

	    }    
    		
		return "  WHERE abserve_payment_order.id IS NOT NULL ".$where."";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

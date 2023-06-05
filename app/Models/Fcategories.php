<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class fcategories extends Abserve  {
	
	protected $table = 'abserve_faq_categories';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_faq_categories.* FROM abserve_faq_categories  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_faq_categories.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

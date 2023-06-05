<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class faqcontents extends Abserve  {
	
	protected $table = 'abserve_faq_contents';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT abserve_faq_contents.* FROM abserve_faq_contents  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE abserve_faq_contents.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class frontend extends Abserve  {
	
	protected $table = 'abserve_restaurants';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	

}

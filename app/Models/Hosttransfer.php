<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\User;

class hosttransfer extends Abserve  {

	protected $table = 'abserve_host_transfer';
	protected $primaryKey = 'id';
}
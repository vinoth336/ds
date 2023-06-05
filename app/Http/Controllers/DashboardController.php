<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Auth, DB, Crypt, DateTime, Session; 
use Validator, Input, Redirect ;

class DashboardController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function getIndex( Request $request )
	{
		//print_r(Auth::user());exit;
		if(Auth::user()->group_id == 4 ){
			return Redirect::to('/frontend/myaccount');	
		}
		elseif(Auth::user()->group_id == 3)
		{
			return Redirect::to('/user/profile');
		}
		elseif(Auth::user()->group_id == 6)
		{
			return Redirect::to('/orderdetails');
		}
		elseif(Auth::user()->group_id == 1)
		{

			$this->data['online_users'] = \DB::table('tb_users')->orderBy('last_activity','desc')->limit(10)->get();

			//dashboard datas

			//subscribers
			$today_subscriber = \DB::select("SELECT (`id`) from `abserve_subscription`");
			$today_subscriber_count = count($today_subscriber);
			$week_subscriber = \DB::select("SELECT id FROM `abserve_subscription` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 WEEK)");
			$week_subscriber_count = count($week_subscriber);
			$month_subscriber = \DB::select("SELECT id FROM `abserve_subscription` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 MONTH)");
			$month_subscriber_count = count($month_subscriber);

			//orders
			$today_order = \DB::select("SELECT (`id`) from `abserve_order_details` WHERE `date`= CURDATE()");
			$today_order_count = count($today_order);
			$week_order = \DB::select("SELECT id FROM `abserve_order_details` WHERE `date` > DATE_SUB(now(), INTERVAL 1 WEEK)");
			$week_order_count = count($week_order);
			$month_order = \DB::select("SELECT id FROM `abserve_order_details` WHERE `date` > DATE_SUB(now(), INTERVAL 1 MONTH)");
			$month_order_count = count($month_order);

			//registered users
			$today_register = \DB::select("SELECT (`id`) from `tb_users` WHERE `group_id` = 4");
			$today_register_count = count($today_register);
			$week_register = \DB::select("SELECT id FROM `tb_users` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 WEEK) AND `group_id` = 4");
			$week_register_count = count($week_register);
			$month_register = \DB::select("SELECT id FROM `tb_users` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 MONTH) AND `group_id` = 4");
			$month_register_count = count($month_register);

			//registered partners
			$today_register_partner = \DB::select("SELECT (`id`) from `tb_users` WHERE `group_id` = 3");
			$today_register_count_partner = count($today_register_partner);
			$week_register_partner = \DB::select("SELECT id FROM `tb_users` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 WEEK) AND `group_id` = 3");
			$week_register_count_partner = count($week_register_partner);
			$month_register_partner = \DB::select("SELECT id FROM `tb_users` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 MONTH) AND `group_id` = 3");
			$month_register_count_partner = count($month_register_partner);

			$this->data['today_sub'] = $today_subscriber_count;
			$this->data['today_order'] = $today_order_count;
			$this->data['today_register'] = $today_register_count;
			$this->data['today_register_partner'] = $today_register_count_partner;
			$this->data['week_sub'] = $week_subscriber_count;
			$this->data['week_order'] = $week_order_count;
			$this->data['week_register'] = $week_register_count;
			$this->data['week_register_partner'] = $week_register_count_partner;
			$this->data['month_sub'] = $month_subscriber_count;
			$this->data['month_order'] = $month_order_count;
			$this->data['month_register'] = $month_register_count;
			$this->data['month_register_partner'] = $month_register_count_partner;

			return view('dashboard.index',$this->data);
			
		}
		elseif(Auth::user()->group_id == 7)
		{
			$region_id = session()->get('rid');
			$region_key = session()->get('rkey');

			$this->data['online_users'] = \DB::table('tb_users')->orderBy('last_activity','desc')->limit(10)->get();

			//dashboard datas

			//subscribers
			$today_subscriber = \DB::select("SELECT (`id`) from `abserve_subscription`");
			$today_subscriber_count = count($today_subscriber);
			$week_subscriber = \DB::select("SELECT id FROM `abserve_subscription` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 WEEK)");
			$week_subscriber_count = count($week_subscriber);
			$month_subscriber = \DB::select("SELECT id FROM `abserve_subscription` WHERE `created_at` > DATE_SUB(now(), INTERVAL 1 MONTH)");
			$month_subscriber_count = count($month_subscriber);

			//orders
		
			
			$today_order = \DB::select("SELECT `od`.*,`res`.`name` FROM `abserve_order_details` as `od` INNER JOIN `tb_users` AS `tb` ON `od`.`cust_id` = `tb`.`id` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE `tb`.`region`='".$region_id."' AND `res`.`region`= '".$region_key."' AND `date`= CURDATE()"); 
			//$today_order = \DB::select("SELECT * from `abserve_order_details` WHERE `date`= CURDATE()");
			$today_order_count = count($today_order);
			
			$week_order = \DB::select("SELECT `od`.*,`res`.`name` FROM `abserve_order_details` as `od` INNER JOIN `tb_users` AS `tb` ON `od`.`cust_id` = `tb`.`id` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE `tb`.`region`='".$region_id."' AND `res`.`region`='".$region_key."' AND `date` > DATE_SUB(now(), INTERVAL 1 WEEK)"); 
			//$week_order = \DB::select("SELECT id FROM `abserve_order_details` WHERE `date` > DATE_SUB(now(), INTERVAL 1 WEEK)");
			$week_order_count = count($week_order);
			
			$month_order = \DB::select("SELECT `od`.*,`res`.`name` FROM `abserve_order_details` as `od` INNER JOIN `tb_users` AS `tb` ON `od`.`cust_id` = `tb`.`id` INNER JOIN `abserve_restaurants` AS `res` ON `od`.`res_id` = `res`.`id` WHERE `tb`.`region`='".$region_id."' AND `res`.`region`='".$region_key."' AND `date` > DATE_SUB(now(), INTERVAL 1 MONTH)"); 
			//$month_order = \DB::select("SELECT id FROM `abserve_order_details` WHERE `date` > DATE_SUB(now(), INTERVAL 1 MONTH)");
			$month_order_count = count($month_order);

			//registered users
			$today_register = \DB::select("SELECT (`id`) from `tb_users` WHERE `region`=".$region_id." AND `group_id` = 4");
			$today_register_count = count($today_register);
			$week_register = \DB::select("SELECT id FROM `tb_users` WHERE `region`=".$region_id." AND `created_at` > DATE_SUB(now(), INTERVAL 1 WEEK) AND `group_id` = 4");
			$week_register_count = count($week_register);
			$month_register = \DB::select("SELECT id FROM `tb_users` WHERE `region`=".$region_id." AND `created_at` > DATE_SUB(now(), INTERVAL 1 MONTH) AND `group_id` = 4");
			$month_register_count = count($month_register);

			//registered partners
			$today_register_partner = \DB::select("SELECT (`id`) from `tb_users` WHERE `group_id` = 3 AND `region`=".$region_id."");
			$today_register_count_partner = count($today_register_partner);
			$week_register_partner = \DB::select("SELECT id FROM `tb_users` WHERE `region`=".$region_id." AND `created_at` > DATE_SUB(now(), INTERVAL 1 WEEK) AND `group_id` = 3");
			$week_register_count_partner = count($week_register_partner);
			$month_register_partner = \DB::select("SELECT id FROM `tb_users` WHERE `region`=".$region_id." AND `created_at` > DATE_SUB(now(), INTERVAL 1 MONTH) AND `group_id` = 3");
			$month_register_count_partner = count($month_register_partner);

			$this->data['today_sub'] = $today_subscriber_count;
			$this->data['today_order'] = $today_order_count;
			$this->data['today_register'] = $today_register_count;
			$this->data['today_register_partner'] = $today_register_count_partner;
			$this->data['week_sub'] = $week_subscriber_count;
			$this->data['week_order'] = $week_order_count;
			$this->data['week_register'] = $week_register_count;
			$this->data['week_register_partner'] = $week_register_count_partner;
			$this->data['month_sub'] = $month_subscriber_count;
			$this->data['month_order'] = $month_order_count;
			$this->data['month_register'] = $month_register_count;
			$this->data['month_register_partner'] = $month_register_count_partner;

		return view('dashboard.index',$this->data);
		}
		//return Redirect::to('/frontend/myaccount');
		/*$this->data['online_users'] = \DB::table('tb_users')->orderBy('last_activity','desc')->limit(10)->get(); 
		return view('dashboard.index',$this->data);
		SELECT (`id`) from `tb_users` WHERE DATE(`created_at`) BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW()
		*/
	}	


}
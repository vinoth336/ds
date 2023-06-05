<?php namespace App\Http\Controllers\mobile\test;

use App\Http\Controllers\controller;
use App\Models\Usercart;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class UsercartController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'usercart';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Usercart();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'usercart',
			'return'	=> self::returnUrl()
			
		);
		
	}	

	// public function postAddcart( Request $request)
	// {
	// 	$_REQUEST 		= str_replace('"','', $_REQUEST);

	// 	$values = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$_REQUEST['quantity']);

	// 		$user_res_equal = \DB::table('abserve_user_cart')
	// 		->where("user_id",'=',$_REQUEST['user_id'])
	// 		->where("res_id",'=',$_REQUEST['res_id'])
	// 		->exists();

	// 		if($user_res_equal){
	// 			$user_food_equal = \DB::table('abserve_user_cart')
	// 			->where("user_id",'=',$_REQUEST['user_id'])
	// 			->where("food_id",'=',$_REQUEST['food_id'])
	// 			->exists();

	// 			if($user_food_equal){

	// 				$quantity = \DB::table('abserve_user_cart')
	// 				->select('*')
	// 				->where("user_id",'=',$_REQUEST['user_id'])
	// 				->where("food_id",'=',$_REQUEST['food_id'])
	// 				->get();

	// 				$fid = $quantity[0]->id;
	// 				 $qty = $quantity[0]->quantity + 1;

	// 				if($_REQUEST['quantity'] == 0){

	// 					\DB::table('abserve_user_cart')
	// 					->where("id",'=',$fid)
	// 					->delete();

	// 				}else{
	// 					$vals = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$_REQUEST['quantity']);

	// 					\DB::table('abserve_user_cart')
	// 					->where("id",'=',$fid)
	// 					->update($vals);
	// 				}

	// 				$response['id'] 		= "1";
	// 				$response['message'] 	= "Same Food added";
	// 				echo json_encode($response);exit;

	// 			}else{
	// 				\DB::table('abserve_user_cart')->insert($values);

	// 				$response['id'] 		= "1";
	// 				$response['message'] = "Another Food added";
	// 				echo json_encode($response);exit;
	// 			}
	// 		}else{
	// 			\DB::table('abserve_user_cart')->where('user_id', '=', $_REQUEST['user_id'])->delete();
	// 			\DB::table('abserve_user_cart')->insert($values);

		
	// 			$response['id'] 		= "1";
	// 			$response['message'] 	= "New List";

	// 			echo json_encode($response);exit;
	// 		}
	// }	

	// public function postShowcart( Request $request)
	// {
	// 	$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$_REQUEST['user_id'])->get();
	// 	foreach ($foods_items as $ky => $val) {
	// 		$foods_item[] = get_object_vars($val);
	// 	}

	// 	$sum = 0;
	// 	foreach ($foods_item as $key => &$value) {
	// 		$value['total'] = ($value['quantity'] * $value['price']);
	// 		/*$sum += $values['price'];
	// 		print_r($sum);*/
	// 	}
	// 	$sum = array_sum(array_column($foods_item, 'total'));

	// 	$response['cart_details'] 				= $foods_item;
	// 	$response['cart_total'][0]['total']		= $sum;
	// 	echo json_encode($response);exit;
	// }


	public function postAddcart( Request $request)
	{
		$_REQUEST 		= str_replace('"','', $_REQUEST);

		$values = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$_REQUEST['quantity']);

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$_REQUEST['user_id'])
			->where("res_id",'=',$_REQUEST['res_id'])
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$_REQUEST['user_id'])
				->where("food_id",'=',$_REQUEST['food_id'])
				->exists();

				if($user_food_equal){

					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$_REQUEST['user_id'])
					->where("food_id",'=',$_REQUEST['food_id'])
					->get();

					$fid = $quantity[0]->id;
					 $qty = $quantity[0]->quantity + 1;

					if($_REQUEST['quantity'] == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();

					}else{
						$vals = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$qty);

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($vals);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "Same Food added";
					echo json_encode($response);exit;

				}else{
					\DB::table('abserve_user_cart')->insert($values);

					$response['id'] 		= "1";
					$response['message'] = "Another Food added";
					echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $_REQUEST['user_id'])->delete();
				\DB::table('abserve_user_cart')->insert($values);

			
				$response['id'] 		= "1";
				$response['message'] 	= "New List";

				echo json_encode($response);exit;
			}
	}	

	public function postShowcart( Request $request)
	{
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$_REQUEST['user_id'])->get();
		$foods_item =array();
		foreach ($foods_items as $ky => $val) {
			$foods_item[] = get_object_vars($val);
		}

		$sum = 0;
		
		foreach ($foods_item as $key => &$value) {
			$value['total'] = ($value['quantity'] * $value['price']);
			/*$sum += $values['price'];
			print_r($sum);*/
		}
		$sum = array_sum(array_column($foods_item, 'total'));

		$response['cart_details'] 				= $foods_item;
		$response['cart_total'][0]['total']		= $sum;
		echo json_encode($response);exit;
	}	

	/*public function postRemovecart( Request $request)
	{
		$foods_items = \DB::table('abserve_user_cart')->select('*')->where("user_id",'=',$_REQUEST['user_id'])->delete();
		if(count($foods_items)>0){
			$response['id'] 				= '1';
		$response['message']		= "Your cart is Empty";
		}else{
			$response['id'] 				= '0';
		$response['message']		= "Check your User ID";
		}
		
		echo json_encode($response);exit;
	}*/


	public function postRemovecart( Request $request)
	{
		$_REQUEST 		= str_replace('"','', $_REQUEST);

		$values = array("user_id"=>$_REQUEST['user_id'],
						"res_id"=>$_REQUEST['res_id'],
						"food_id"=>$_REQUEST['food_id'],
						"food_item"=>$_REQUEST['food_item'],
						"price"=>$_REQUEST['price'],
						"quantity"=>$_REQUEST['quantity']);

			$user_res_equal = \DB::table('abserve_user_cart')
			->where("user_id",'=',$_REQUEST['user_id'])
			->where("res_id",'=',$_REQUEST['res_id'])
			->exists();

			if($user_res_equal){
				$user_food_equal = \DB::table('abserve_user_cart')
				->where("user_id",'=',$_REQUEST['user_id'])
				->where("food_id",'=',$_REQUEST['food_id'])
				->exists();

				if($user_food_equal){

					$quantity = \DB::table('abserve_user_cart')
					->select('*')
					->where("user_id",'=',$_REQUEST['user_id'])
					->where("food_id",'=',$_REQUEST['food_id'])
					->get();

					$fid = $quantity[0]->id;
					 $qty = $quantity[0]->quantity - $_REQUEST['quantity'];
					 if($qty==0){
					 	\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();
					 }

					if($_REQUEST['quantity'] == 0){

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->delete();

					}else{
						$vals = array("user_id"=>$_REQUEST['user_id'],"res_id"=>$_REQUEST['res_id'],"food_id"=>$_REQUEST['food_id'],"food_item"=>$_REQUEST['food_item'],"price"=>$_REQUEST['price'],"quantity"=>$qty);

						\DB::table('abserve_user_cart')
						->where("id",'=',$fid)
						->update($vals);
					}

					$response['id'] 		= "1";
					$response['message'] 	= "quantity reduced";
					echo json_encode($response);exit;

				}else{
					\DB::table('abserve_user_cart')->insert($values);

					$response['id'] 		= "1";
					$response['message'] = "Removed food";
					echo json_encode($response);exit;
				}
			}else{
				\DB::table('abserve_user_cart')->where('user_id', '=', $_REQUEST['user_id'])->delete();
				\DB::table('abserve_user_cart')->insert($values);

			
				$response['id'] 		= "1";
				$response['message'] 	= "New List";

				echo json_encode($response);exit;
			}
	}	

	public function postUsercartcount(Request $request)
	{
		$sUser_cart_count=\DB::table('abserve_user_cart')
						->where("user_id",'=',$request->user_id)
						->count();
		if(($sUser_cart_count)>0){
			$response['count'] 	= $sUser_cart_count;
		}else{
			$response['count'] 	= 0;
		}
	  echo json_encode($response);exit;
	}


	public function postCustomercartdelete(Request $request)
	{
		$userid =$request->user_id;
		$aCart =\DB::table('abserve_user_cart')->where('user_id',$userid)->delete();
		$response['message'] = "Your order was canceled Successfully";
		echo json_encode($response); exit;
		
	}


	

	


}
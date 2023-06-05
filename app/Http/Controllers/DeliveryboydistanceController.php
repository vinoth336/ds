<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Deliveryboydistance;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class DeliveryboydistanceController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'deliveryboydistance';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Deliveryboydistance();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'deliveryboydistance',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		
		return view('deliveryboydistance.index',$this->data);
	}

	function postOrdercount( Request $request)
	{

        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));

        $boy_id = $_POST['deliveryboys'];

        if ($_POST['duration'] != '0') {
            if ($_POST['duration'] == '1') {
                $cond .= "AND `date`= CURDATE()";
            }
            if ($_POST['duration'] == '2') {
                $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 WEEK)";
            }
            if ($_POST['duration'] == '3') {
                $cond .= "AND `date` > DATE_SUB(now(), INTERVAL 1 MONTH)";
            }
            if ($_POST['duration'] == '4') {
                if (isset($from_date) && ($to_date != '')) {
                    if (isset($from_date) && ($to_date != '')) {
                        $cond .= "AND `date` >= '" . $from_date . "' AND `date` <= '" . $to_date . "'";
                    }
                }
            }
        }

        $amount .= '<table id="" class="" width="100%" ">
                     
		<tr>
			<th class="weight-600">S.NO</th>
			<th class="weight-600">ORDER DATE</th>
			<th class="weight-600">ORDER ID</th>
			<th class="weight-600">ORDER FROM (Restaurant)</th>
			<th class="weight-600">ORDER TO (Customer)</th>
			<th class="weight-600">TOTAL</th>
			<th class="weight-600">DISTANCE COVERED</th>	   
		</tr>';
		
         $query = "SELECT `od`.`id`,`op`.`boy_id`,`od`.`delivery_type`,`od`.`delivery_charge`,`od`.`address`,`od`.`res_id`,`op`.`order_status`,`op`.`distance`,`od`.`date`,`grand_total`,`od`.`lat`,`od`.`lang`,`re`.`latitude`,`re`.`longitude`,`re`.`name` FROM `abserve_order_details` AS `od` RIGHT JOIN `abserve_orders_boy` AS `op` ON `op`.`orderid` = `od`.`id` JOIN `abserve_restaurants` AS `re` ON `re`.`id` = `od`.`res_id`  WHERE `op`.`boy_id`='" . $boy_id . "' AND (`op`.`order_status`='4' OR `op`.`order_status`='11') " . $cond . " ORDER BY id DESC";
       
        $orders = \DB::select($query);
        $dist_tot = 0;
        $i = 1;
        foreach ($orders as $key => $order) {
            $amount .= '<tr>';
            
            $amount .= '<td>'.$i.'</td>';
            $amount .= '<td>'.date('d-m-Y', strtotime($order->date)).'</td>';
            $amount .= '<td>#'.$order->id.'</td>';
            $amount .= '<td>'.$order->name.'</td>';
            $amount .= '<td>'.$order->address.'</td>';
            $amount .= '<td>Rs. '.$order->grand_total.'</td>';
            $amount .= '<td>'.$order->distance.' kms</td>';
            $dist_tot += $order->distance;
             
            $amount .= '</tr>';
            
            $i++;
        }
        $amount .= '<tr><td colspan=6></td><td><strong>'.round($dist_tot,2).' kms</strong></td></tr>';
        $amount .= '</table>';


        $amount .= '<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		th, td {
			padding: 5px;
			text-align: left;
		}
		</style>';
        echo $amount;
        //return view('deliveryordercount.index');
    }	

			


}
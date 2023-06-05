<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Mail;

use App\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use PDF;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class InvoiceController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'invoice';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Invoice();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'invoice',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		
		$region_id = session()->get('rid');
		$region_key = session()->get('rkey');
		if(\Auth::user()->group_id == 7) {
			$this->data['restaurants'] =\DB::select( "SELECT * FROM abserve_restaurants WHERE region='".$region_key."'"); /** restaurent details **/
			$this->data['region_details'] =\DB::select( "SELECT * FROM region WHERE id='".$region_id."'"); /** region details **/
		} else {
			$this->data['restaurants'] =\DB::select( "SELECT * FROM abserve_restaurants"); /** restaurent details **/
			$this->data['region_details'] =\DB::select( "SELECT * FROM region WHERE id NOT IN (1,2,4,7)"); /** region details **/
		}
		
		return view('invoice.index',$this->data);
	}	



	function getUpdate(Request $request, $id = null)
	{
	
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		$this->data['access']		= $this->access;
		return view('invoice.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		
		$this->data['access']		= $this->access;
		return view('invoice.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
	
	}	

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		
	}
	
	function pdf()
    {
     	$pdf = \App::make('dompdf.wrapper');
     	$pdf->loadHTML($this->convert_customer_data_to_html());
		return $pdf->stream();
    }			
	
	function postOrderinvoice( Request $request)
	{
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			if($_POST['res_all']=="1"){
				if($_POST['region']=="all_region"){
					$restaurants = \DB::table('abserve_restaurants')->select('id')->whereNotIn('region', ['VLR', 'RPT', 'TVM', 'VN8'])->get();
				} else {
					$restaurants = \DB::table('abserve_restaurants')->select('id')->where('region','=',$_POST['region'])->get();
				}
				foreach($restaurants as $restaurant){
					$res_ids[] = $restaurant->id;
				}
			} else { 
				$res_ids = $_POST['res_id'];
			}
			//print_r($res_ids);
			//print_r($restaurants);
			
			foreach($res_ids as $key => $res_id){
			
				$from_date = date('Y-m-d',strtotime($_POST['from_date']));
				$to_date = date('Y-m-d',strtotime($_POST['to_date']));
				
				$rest = \DB::select("SELECT `res`.`name`,`res`.`ds_commission`,`res`.`region`,`user`.`email` from `abserve_restaurants` as `res` JOIN `tb_users` as `user`  ON `res`.`partner_id`=`user`.`id` WHERE `res`.`id`=".$res_id);
				
				$orders = \DB::table('abserve_order_details')->select('*')->where('res_id',$res_id)->whereBetween('date', array($from_date,$to_date))->whereIn('status',array('4','11'))->get();			
				//print_r($orders); exit;
				
				//$rest = \DB::table('abserve_restaurants')->select('ds_commission')->where('id',$res_id)->first();
				$commission = $rest[0]->ds_commission;
				
				$logo = \URL::to('').'/abserve/images/backend-logo.png';
				$grand_total=0;
				
				$output = '
					<html><head>
						<style>
						header {							
							top: 0cm;
							left: 0cm;
							right: 0cm;
							height: 1.5cm;
							text-align: center;
						}						
						footer {							
							//position: fixed; 
							bottom: 0cm; 
							left: 0cm; 
							right: 0cm;
							height: 35px;
							text-align: right;
							line-height: 35px;
						}
						.page-break {
							page-break-after: always;
						}
						</style>
					</head><body>
					<header>
						<h1 align="center">Delivery Star</h1>
					</header>
					<main>
					<h3 align="center">'.$rest[0]->name.' - Invoice '.date('d/m/Y',strtotime($_POST['from_date'])).' - '.date('d/m/Y',strtotime($_POST['to_date'])).'</h3>
					<table width="100%" style="border-collapse: collapse; border: 0px;">
					<tr>
					<th style="border: 1px solid; padding:12px;" width="20%">Order Id</th>
					<th style="border: 1px solid; padding:12px;" width="20%">Date</th>
					<th style="border: 1px solid; padding:12px;" width="20%">Total</th>
					<th style="border: 1px solid; padding:12px;" width="20%">Order Status</th>
					</tr>';  
				
				
				$max_per_page = 15;
				$order_count = count($orders);
				$pages = ceil($order_count / $max_per_page);
				
				$i=1;
				foreach($orders as $order)
				{
					//$grand_total += $order->grand_total;
					if($order->status == '4'){
						$order_status = "Completed";
					}
					if($order->status == '11'){
						$order_status = "Returned";
					}
					if($order->hd_gst == 1){
						$dgst = 0;
					} else {
						$dgst = $order->s_tax;
					}
					if($order->coupon_type == 2){//DS Coupon
						$coupon_price = $order->coupon_price;
					} else {//Restaurant Coupon
						$coupon_price = 0;
					}
					
					$total = ((($order->grand_total - $order->delivery_charge)+$coupon_price)-$dgst);
					$grand_total += $total;
					
					$output .= '<tr><td style="border: 1px solid; padding:12px;">#'.$order->id.'</td><td style="border: 1px solid; padding:12px;">'.date('d/m/Y',strtotime($order->date)).'</td><td style="border: 1px solid; padding:12px;">'.$total.'</td><td style="border: 1px solid; padding:12px;">'.$order_status.'</td></tr>';
					/* if($i == $max_per_page)
     				{
						$output .= '</table><div class="page-break"></div><table width="100%" style="border-collapse: collapse; border: 0px;">';		
					  	$max_per_page = $max_per_page + 15;
					} */
					$i++;
				}
				
				$output .= '<tr><td></td><td style="border: 1px solid; padding:12px;">Grand Total</td><td style="border: 1px solid; padding:12px;">'.$grand_total.'</td><td></td></tr></table>';
				
				if($commission !=0){
					$commission_amount = ($grand_total*($commission/100));
				} else {
					$commission_amount = 0;
				}
				$total_amount = ($grand_total - $commission_amount);
				
				$output .= '<p></p><p>Commisssion ('.$commission.'%) : '.$commission_amount.'</p><p>Total amout after deducting commission : ('.$grand_total.' - '.$commission_amount.') '.round($total_amount).'</p>';
				
				$output .= '<p></p><p>Instruction:</p><p>'.$_POST['instruction'].'</p>';
				
				$output .= '</main><footer><div>This is a computer generated invoice Signature not needed</div></footer></body></html>';				
				

				// Settings
				$name        = $rest[0]->name;//"Buhari";
				$email       = $rest[0]->email;//"graja87@gmail.com";
				$to          = "$name <$email>";
				$from        = "Delivery Star ";
				$subject     = "Delivery Star Invoice ".date('d/m/Y',strtotime($_POST['from_date'])).' - '.date('d/m/Y',strtotime($_POST['to_date']));
				/* $fileatt     = \URL::to('').'/storage/download/'.$pdfname;//"./test.pdf"; //file location
				//$mainMessage = "Hi, here's the attachment file <a href='".$fileatt."' download='Invoice.pdf'>Download it!</a>.";
				$mainMessage = "Dear customer, <br> Please click the below link to get the last week invoice report, do reach us if you have any queries. <br> <a href='".$fileatt."' download='Invoice.pdf'>Download it!</a>.";
				$fileatttype = "application/pdf";
				$fileattname = 'Invoice.pdf'; */ //name that you want to use to send or you can use the same name

				$mainMessage = "Dear customer, <br><br> Please check below the last week invoice report, do reach us if you have any queries. <br>".$output;

				$headers = "From: $from <finance@deliverystar.in>"."\r\n";
				//$headers = "From: $from <bicstest007@gmail.com>"."\r\n";
				
				if($rest[0]->region == "KRR"){
					$headers .= "Cc: $from <finance@deliverystar.in>, <deliverystarkarur@gmail.com>"."\r\n";
				} else {
					$headers .= "Cc: $from <finance@deliverystar.in>"."\r\n";
				}
				
				// File
				/* $file = fopen($fileatt, 'rb');
				$size  = get_headers($fileatt, 1);
				$fsize    = $size['Content-Length'];
				$data = fread($file, $fsize);
				fclose($file); */
				
				// This attaches the file
				$semi_rand     = md5(time());
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
				/* $headers      .= "\nMIME-Version: 1.0\n" .
				  "Content-Type: multipart/mixed;\n" .
				  " boundary=\"{$mime_boundary}\""; */
				$headers .= "Content-Type: multipart/alternative; boundary=\"{$mime_boundary}\""; 
				  $message = "This is a multi-part message in MIME format.\n\n" .
				  "--{$mime_boundary}\n" .
				  "Content-Type: text/html; charset=\"iso-8859-1\n" .
				  "Content-Transfer-Encoding: base64\n\n" .
				  chunk_split(base64_encode($mainMessage))  . "\n\n";
				
				//$data1 = chunk_split(base64_encode($data));
				/* $message .= "--{$mime_boundary}\n" .
				  "Content-Type: {$fileatttype};\n" .
				  " name=\"{$fileattname}\"\n" .
				  "Content-Disposition: attachment;\n" .
				  " filename=\"{$fileattname}\"\n" .
				  "Content-Transfer-Encoding: base64\n\n" .
				$data1 . "\n\n" .
				 "--{$mime_boundary}--\n"; */
								
				$return = 'invoice?return='.self::returnUrl();
				
				// Send the email
                $mailStatus = Mail::raw($message, function($mail) use($to, $subject) {
                   $mail->to($to);
                   $mail->subject($subject);
                });
				if($mailStatus) {
					
					/*$log = ['RestName' => $name,'ToEmail' => $email,'Status' => 'Success'];

					$invoiceLog = new Logger(invoice);
					$invoiceLog->pushHandler(new StreamHandler(storage_path('logs/invoice.log')), Logger::INFO);
					$invoiceLog->info('InvoiceLog', $log);*/
					
					$invoice_log = array("rest_name"=>$name,"rest_email"=>$email,"status"=>"Success","date"=>date('Y-m-d h:i:sA'));
					\DB::table('invoice_log')->insert($invoice_log);
					
					$_msg[] = $name." invoice email was sent";
				
				} else {
					
					/*$log = ['RestName' => $name,'ToEmail' => $email,'Status' => 'Failure'];

					$orderLog = new Logger(order);
					$orderLog->pushHandler(new StreamHandler(storage_path('logs/invoice.log')), Logger::INFO);
					$orderLog->info('InvoiceLog', $log);*/
					
					$invoice_log = array("rest_name"=>$name,"rest_email"=>$email,"status"=>"Failure","date"=>date('Y-m-d h:i:sA'));
					\DB::table('invoice_log')->insert($invoice_log);
					
					$_msgerror[] = $name;//." invoice email was not sent"
			
				}
			
			}
			if($_msg > 0){
				return Redirect::to($return)->with('messagetext',\Lang::get('The invoice email was sent'))->with('msgstatus','success');
			}
			if($_msgerror > 0){
				$msgerror = implode(",",$_msgerror)." invoice email was not sent";
				return Redirect::to('invoice')->with('messagetext',\Lang::get($msgerror))->with('msgstatus','error')->withErrors($validator)->withInput();
			}
			
		} else {

			return Redirect::to('invoice')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}
	}
	
	function postInvoiceview( Request $request)
	{		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			
			if($_POST['res_all']=="1"){
				if($_POST['region']=="all_region"){
					$restaurants = \DB::table('abserve_restaurants')->select('id')->get();
				} else {
					$restaurants = \DB::table('abserve_restaurants')->select('id')->where('region','=',$_POST['region'])->get();
				}
				foreach($restaurants as $restaurant){
					$res_ids[] = $restaurant->id;
				}
			} else { 
				$res_ids = $_POST['res_id'];
			}
			//print_r($res_ids);
			
			$output = '<table class="invoice_table">
			<tr>
				<th width="">S.No</th>
				<th>Order Id</th>
				<th>Date</th>
				<th>Total</th>
				<th>Order Status</th>			  
			</tr>';
			
			foreach($res_ids as $key => $res_id){
			
				$from_date = date('Y-m-d',strtotime($_POST['from_date']));
				$to_date = date('Y-m-d',strtotime($_POST['to_date']));
				
				//$rest = \DB::select("SELECT `res`.`name`,`user`.`email` from `abserve_restaurants` as `res` JOIN `tb_users` as `user`  ON `res`.`partner_id`=`user`.`id` WHERE `res`.`id`=".$res_id);
				
				$rest = \DB::table('abserve_restaurants')->select('ds_commission')->where('id',$res_id)->first();
				$commission = $rest->ds_commission;
				
				
				$orders = \DB::table('abserve_order_details')->select('*')->where('res_id',$res_id)->whereBetween('date', array($from_date,$to_date))->whereIn('status',array('4','11'))->get();			
				//print_r($orders); exit;
				
				$logo = \URL::to('').'/abserve/images/backend-logo.png';
				$grand_total=0;
				
			  	//if(count($orders)>0){
					$tot_record_found=1;									 
					$i = 1;
					foreach($orders as $order)
					{
						if($order->status == '4'){
							$order_status = "Completed";
						}
						if($order->status == '11'){
							$order_status = "Returned";
						}
						if($order->hd_gst == 1){
							$dgst = 0;
						} else {
							$dgst = $order->s_tax;
						}
						if($order->coupon_type == 2){//DS Coupon
							$coupon_price = $order->coupon_price;
						} else {//Restaurant Coupon
							$coupon_price = 0;
						}						
						
						$total = ((($order->grand_total - $order->delivery_charge)+$coupon_price)-$dgst);
						$grand_total += $total;
						/*if($order->ds_commission !=0){
							$order_commission = ($total/$order->ds_commission);
						} else {
							$order_commission = 0;
						}
						$total_commission = ($total - $order_commission);
						$grand_total_commission += $total_commission;*/
			
						$output .= '<tr>';
						$output .= '<td>'.$i.'</td>';
						$output .= '<td >'.'#'.$order->id.'</td>';
						$output .= '<td >'.date('d-m-Y',strtotime($order->date)).'</td>';	
						$output .= '<td>'.$total.'</td>';
						//$output .= '<td>'.$total_commission.'</td>';
						$output .= '<td >'.$order_status.'</td>';						
						$output .= '</tr>';
					
						$i++;
					}
					
				}
		
				$output .= '<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>'.$grand_total.'</td>
							<td></td>
						</tr>';
		
				$output .= '</table>';	
				$output .= '<style>					
					table, th, td {
						border: 1px solid black;
						border-collapse: collapse;
					}
					th, td {	
						padding: 5px;
						text-align: left;
					}
					table {
						display: block;
						overflow-x: auto;
					}
					.invoice_table td {
						width: 1%; //testing purpose
					}
					</style>';
				
				if($commission !=0){
					$commission_amount = ($grand_total*($commission/100));
				} else {
					$commission_amount = 0;
				}
				$total_amount = ($grand_total - $commission_amount);
				
				$output .= '<p>Commisssion ('.$commission.'%) : '.$commission_amount.'</p><p>Total amout after deducting commission : ('.$grand_total.' - '.$commission_amount.') '.round($total_amount).'</p>';		
				
				echo $output;
					
			
			/*} else {
				$amount .= '<style="text-align: center";><b>No records found</b>';	
			}*/		
			
		}	
	}
	
	public function postRegionselect( Request $request)
	{
	
		$region_key = $request->regionselect; 
		/*for restaurant based on region*/
		if($region_key != ''){
			$restregion =\DB::select("SELECT * FROM abserve_restaurants WHERE region='".$region_key."'");
		}else{
			$restregion =\DB::select("SELECT * FROM abserve_restaurants");	
		}
		
		//$html = $html.'<select rows="9" class="" name="res_id" id="res_id">';
		$html = $html.'<option value="">'.'Select Restaurant Name'.'</option>' ;
	
	    foreach($restregion as $restregion1){
			$html = $html.'<option ' ;
			$html = $html.'value="'.$restregion1->id.'">';
			$html = $html.$restregion1->name.'</option>';
		}
		//$html = $html.'</select>';
		
		return $html;
	
	}


}
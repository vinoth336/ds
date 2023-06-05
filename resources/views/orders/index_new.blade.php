@extends('layouts.app')
@section('content')
	<link href="{{ asset('abserve/js/datatable/buttons.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{ asset('abserve/js/datatable/jquery.dataTables.min.css')}}" rel="stylesheet">
    
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<!-- <script type="text/javascript" src="{{ asset('abserve/js/datatable/dataTables.bootstrap.min.js') }}"></script>	 -->
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="{{ asset('abserve/js/datatable/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
  <div class="page-content row">
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> {!! trans('core.m_dashboard') !!} </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>
    </div>
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <!-- <i class="fa fa-table"></i> --> </h5>
	</div>
	<center class="after_ajx"></center>
	<div class="sbox-content">
		<div class="toolbar-line ">
        
                  
                    
                                  
                               
			<!--@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('orders/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif -->
			<!--<a href="{{ URL::to( 'orders/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>-->				
			<!--@if($access['is_excel'] ==1)
			<a href="{{ URL::to('orders/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif-->			
		 
		</div> 	
        
        <label for="Region" class=" control-label col-md-4 text-left" style="text-align:end;"> Region : </label>
		<div class="col-md-3"> 
            <?php  
            if(session()->get('gid') == '1'){
                 $regionall = \DB::select("SELECT *  FROM `region` ");
            }elseif(session()->get('gid') == '7'){
                $regionall = \DB::select("SELECT *  FROM `region` WHERE `id`='".session()->get('rid')."'");
            }	?>
                                
            <select rows='3' class='form-control regionselect' id="regionselect">
                                    
              <?php  
              if(session()->get('gid') == '1'){
                 $regionall = \DB::select("SELECT * FROM `region` ");  ?>
                 <option value="" selected>All region</option>  
              <?php }elseif(session()->get('gid') == '7'){
                 $regionall = \DB::select("SELECT * FROM `region` WHERE `id`='".session()->get('rid')."'");
              }	?>
                            
                <?php foreach($regionall as $region1)  {  ?>
                    <option value="<?php echo $region1->id;  ?>" <?php if($_GET['region'] == $region1->id){echo "selected"; }  ?>><?php echo $region1->region_name;  ?></option><?php }  ?> 
            </select>
                                   
		</div> 
	{!! Form::open(array('url'=>'orders/partnerdelete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <!-- <div class="table-responsive" style="min-height:300px;"> -->
	<?php // echo "<pre>";print_r($results ); exit(); ?>
	<!--<b>{!! trans('core.abs_partner_id_colon') !!}#{{$results[0]->partner_id}}   {!! trans('core.abs_phone_num_colon') !!}{{$pphone}}</b>-->
	<div class="table_div">
		<table class="display nowrap" id="example1" cellspacing="0" width="100%">
			<thead>
				<tr>
					
					<th> <input type="checkbox" class="checkall" /></th>
					<!-- <th> Date </th> -->	
					<th> Order Id </th>
                    <th> Cust Id </th>
                	<!--<th> Res Id </th>-->
					<th> Cust Name </th>
                    <th> Cust Address </th>
                    <th> Cust Phone </th>
					<th> Res Name </th>
                    <th> Item Total </th>
                    <th style="width:50px; white-space:pre-line;"> Delivery Charge </th>
                    <th style="width:50px; white-space:pre-line;"> Packaging Charge </th>
                    <th> Grand Total </th>
					<th> Date and Time </th>
					<th  width="50"> Order details </th>
                    <!--<th> MOP</th>-->
					<th> Delivery Type</th>
                    <!--<th> Boy Id </th>-->
                	<th> Boy Name </th>
					<th width="70" >{!! Lang::get('core.btn_action') !!}</th>
					<th width="70" >{!! trans('core.status') !!}</th>
                    <th width="50"> Rejected Items </th>
                    <th> GST </th>
                    <th style="width:50px; white-space:pre-line;"> DS Offer Price </th>
                    <th style="width:50px; white-space:pre-line;"> Rest Offer Price </th>
                    <th> Delivery Time </th>
                    <th width="70" >Call Restaurant</th>
                    <th width="70" >Call Customer</th>
				</tr>
			</thead>
			<tbody>
			<?php 	//echo "<pre>";print_r($results);exit(); ?>
				@foreach ($results as $key => $row)
				<?php  $res_call = \SiteHelpers::getRestaurantDetails($row->res_id); ?>
            		@if(($res_call == 'true') || ($res_call == 'false'))
						<tr>
							<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" /></td>
							<!-- <td width="50">{{date('jS F Y',$row->time)}}</td> -->
							<td width="50"><?php $region = \SiteHelpers::getRegionKeyword($row->res_id);?>#{{$region.$row->orderid}}</td>
							<td width="50">{{$row->cust_id}}</td>                            
							<!--<td width="50">{{$row->res_id}}</td>-->
							<td width="50"><?php $cname = \SiteHelpers::hostname($row->cust_id);?>{!! $cname !!}</td>
                            <td width="50" style="width:400px; white-space:pre-line;">@if($row->building != '') {{$row->building}}, @endif @if($row->landmark != '') {{$row->landmark}}, @endif {{$row->address}}</td>
                            <td width="50"><?php $custph = \SiteHelpers::getCustomerPhone($row->cust_id); ?>{{$custph}}</td>
							<?php $res_detail = $model->resname($row->orderid);?>
                            <td width="50">{{$res_detail[0]->name}}</td>
                            <td width="50">{{$row->total_price}}<?php //echo ($row->grand_total - $row->delivery_charge); ?></td>
                            <td width="50">{{ $row->delivery_charge }}</td>
                            <td width="50">{{ $row->packaging_charge }}</td>
                            <td width="50">{{ $row->grand_total }}</td>
							<?php  date_default_timezone_set("Asia/Kolkata"); ?>
							<td width="50" style="width:100px; white-space:pre-line;">{{date('Y-m-d h:i:s A',$row->time)}}</td>
							<td width="50" style="width:400px; white-space:pre-line;">{{$row->order_details}}</td>	
							<!--<td width="50">{{ $row->mop }}</td>-->
                            <td width="50">{{ $row->delivery_type }} @if($row->mop != '') - {{ $row->mop }}@endif</td>
                            <!--<td><?php $bid = \SiteHelpers::getBoyid($row->orderid);?>{!! $bid !!}</td>-->
                  			<td><?php $boyname = \SiteHelpers::getBoyname($bid);?>{!! $boyname !!}</td>								
						 <td>
							@if($row->order_status == 0)
								<!-- <i class="fa fa-volume-up"></i> -->
								<i data-toggle="tooltip" title="Accept your order" class="icon-checkmark-circle2 @if($row->status == 0) fn_accept @endif" aria-hidden="true" style="cursor: pointer;"></i>
								<i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 @if($row->status == 0) fn_reject @endif" aria-hidden="true" style="cursor: pointer;"></i> 
								<input type="hidden" value="{{$row->partner_id}}" class="partner_id" /><input type="hidden" value="{{$row->orderid}}" class="orderid" />
                                <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' onclick="stop()" >
									<?php echo $deliveryboys; ?>
                                </select>
							@else
                            	@if($row->order_status == 5)
                                	<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>
                                    <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 @if($row->status == 5) fn_reject @endif" aria-hidden="true" style="cursor: pointer;"></i> 
									<input type="hidden" value="{{$row->partner_id}}" class="partner_id" /><input type="hidden" value="{{$row->orderid}}" class="orderid" />
                                    <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' onclick="stop()" >
										<?php echo $deliveryboys; ?>
                                    </select>
                                @else
									<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>
                                    @if($row->order_status == 1 || $row->order_status == 2)
										<i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i>
                                        <input type="hidden" value="{{$row->partner_id}}" class="partner_id" /><input type="hidden" value="{{$row->orderid}}" class="orderid" />
                                        <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' onclick="stop()" >
                                            <?php echo $deliveryboys; ?>
                                        </select>
                                    @else
                                    	<i data-toggle="tooltip" title="Action disabled" class="icon-cancel-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>
                                     
                                        @if($bid =='')
                                            <input type="hidden" value="{{$row->partner_id}}" class="partner_id" /><input type="hidden" value="{{$row->orderid}}" class="orderid" />
                                            <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' onclick="stop()" >
                                                <?php echo $deliveryboys; ?>
                                            </select>
                                        @else
                                            <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' disabled="disabled" >
                                                
                                            </select>
                                        @endif
                                    @endif
                                @endif
							@endif
						</td>
						<td>
							@if($row->order_status == 1 )
								<span class="label status label-success">Accepted by Restaurant</span>
							@elseif($row->order_status == 2)
								 <span class="label status label-primary">{!! trans('core.abs_accept_by_boy') !!}</span>
							@elseif($row->order_status == 3)
								 <span class="label status label-info">{!! trans('core.abs_order_dispatch') !!}</span>
                            @elseif($row->order_status == 5)
								 <span class="label status label-info">Rejected by Restaurant</span>
							@else
								<span class="label label-warning status">{!! trans('core.pending') !!}</span>
							@endif								
						</td>
                        <td width="50" style="width:200px; white-space:pre-line;">{{ $row->order_reject_desc }}</td>
                        <td width="50">{{ $row->s_tax }}</td>
                        <td width="50">{{ $row->coupon_price }}</td>
                        <td width="50"><?php $offer = \SiteHelpers::getOfferPrice($row->res_id,$row->total_price,$row->date); ?>{{$offer}}</td>
                        <td>                      	
                              <p>DA: 
                              @if($row->delivery_accept != '0000-00-00 00:00:00' )
                        		 {{ $row->delivery_accept }}
                              @endif
                              </p>
                              <p>DD: 
                              @if($row->delivery_dispatch != '0000-00-00 00:00:00' )
                        		 {{ $row->delivery_dispatch }}
                              @endif
                              </p>							                        
						</td>
                        <td>
                        <a style="text-align: center; color: green;" class="call_ivrs" href="#" data-restaurant-phone="<?php $resph = \SiteHelpers::getRestaurantPhone($row->res_id); echo $resph;?>"><i style="font-size: 22px;" class="fa fa-phone-square"></i> Call</a>
                        </td>
                        <td>
                        <a style="text-align: center; color: green;" class="call_ivrs" data-customer-phone="<?php $custph = \SiteHelpers::getCustomerPhone($row->cust_id); echo $custph;?>" id="" href="#"><i style="font-size: 22px;" class="fa fa-phone-square"></i> Call</a>
                        </td>
						</tr>
					@endif
				@endforeach
                  <input type="hidden" value="" name="regionselect" />
			</tbody> 
		</table>
	{!! Form::close() !!}
	</div>
    
  
	<input type="hidden" name="md" value="" />
	<!-- <?php print_r(\Auth::id()); ?> -->
	<input type="hidden" id="lastid" value="<?php echo $last_id;?>" />

	<input type="hidden" id="loadlastid" value="<?php echo $last_id;?>" />
	<!-- </div> -->
	<!-- @include('footer') -->
	</div>
</div>	
	</div>	  
</div>	
<!-- <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script> -->
<style>
.fn_buzzer_off{color:red;}

</style>
<script>

	var url = '<?php echo url('media/buzzer.mp3');?>';
	var audio = new Audio(url);
	function buzzer() {
		audio.currentTime=0;
		audio.play();
	}
	function stop_buzzer() {
		audio.pause();
		audio.currentTime = 0;
	}
	function table_load() {		
		$.ajax({
			url: '<?php echo url(); ?>/orders/tablelist',
			type: "post",
			// dataType: "json",
			// data: {},
			success: function(data) {
				buzzer();
				if(data!=''){
					$("#example1 tbody tr:first").before(data);
				}
				
				var url = '<?php echo url('abserve/images/default.gif');?>'
				 $('.after_ajx').html('<img class="load_image" src="'+url+'">');
				if (confirm("New orders Found..Do You want to view?")) {
					location.reload();
				}
				$('.table_div').html(data.view);
			}
		});
	}
	
	setInterval(function () {
		if($(".buzzer_vol").is(":visible")){
			buzzer();
		}
     }, 10000);
	
	$(document).on("mouseup",'.fn_buzzer_off',function(){ 
		stop_buzzer();  
		$(this).removeClass("buzzer_vol fn_buzzer_off");
	    $(this).addClass('fn_buzzer_on');
	
	});
	$(document).on("mouseup",'.fn_buzzer_on',function(){ 
		buzzer();  
		$(this).removeClass("fn_buzzer_on");
	    $(this).addClass('fn_buzzer_off');
	});
//
//	function get_lastid() {
//		$.ajax({
//			url: '<?php echo url(); ?>/orders/lastid',
//			type: "POST",
//			
//			data: {
//				partner_id : '<?php echo \Auth::id();?>'
//			},
//			success: function(data) {
//				console.log(data);
//				$('#lastid').val(data);
//				//$('#loadlastid').val(data);
//				/*if(data.msg == 'success'){
//					$('#lastid').val(data.lastid);
//					table_load();
//				}*/
//			}
//		});
//	}
//
//	function loadlink(){
//		get_lastid();
//		var last_id = $('#lastid').val();
//		var load_last_id = $('#loadlastid').val();
//		if(last_id != null || last_id != undefined){
//
//			if(load_last_id != last_id){
//
//				$.ajax({
//				url: '<?php echo url(); ?>/orders/ajaxload',
//				type: "post",
//				dataType: "json",
//				data: {
//					value : last_id
//				},
//				success: function(data) {
//					//console.log(data);
//					// alert(data.msg);
//					if(data.msg == 'success'){
//						$('#lastid').val(data.lastid);
//						table_load();
//					}
//				}
//			  });
//
//			}else{
//
//				
//
//			}
//		
//		}
//	}
//
//	$(document).on('click','.load_image',function(){
//		$(window).load();
//	});

	function loadlink(){ 
	
	var regionselect = '<?php echo $_GET['region'] ?>';
		
		$.ajax({
			url: '<?php echo url(); ?>/orderdetails/ajaxtableload',
			type: "get",
			dataType: "json",
			data: {
					regionselect : regionselect
					},
			success: function(data) {
				//console.log(data);
				//alert(data);				
				if(data!=''){
					//$("#example1 tbody tr:first").before(data);
					$("#example1 tbody").html(data.responseText);
				}
			},
			error: function (data) {
				if(data!=''){
					$("#example1 tbody").html(data.responseText);
				}
			}
		});
	}

	var myTimer =	setInterval(loadlink, 10000);
	function stop(){ //alert(myTimer);
		clearInterval(myTimer);
	}
	
	$(document).on("click",'.call_ivrs',function(){		
		var phone = '';				
		var $this = $(this);				
		
		var c_phone = $(this).data('customer-phone');				
		var r_phone = $(this).data('restaurant-phone');
		
		if( c_phone ) {
			phone = c_phone;
		}else if( r_phone ){
			phone = r_phone;
		}else{
			//null block
		}
		
		$.ajax({
			url: 'http://103.207.0.124/aster-dialer/services/manualOriginateapi.php',
			type: "POST",
			dataType: "json",
			data: JSON.stringify({
				user : <?php echo \Auth::user()->agent_number; ?>, customer: phone
			}),
			success: function(data) {
				console.log(data);
			}
		});
	});
        
	$(document).ready(function(){
		
		$('#example1').DataTable({
			//dom: 'Bfrtip',
			lengthMenu: [[100, 150, 200, -1], [100, 150, 200, "All"]],
			dom: 'Blfrtip',
			buttons: [
				'csv', 'excel', 'print'
			]
			/*buttons: [
					{
							extend: 'excelFlash',
							filename: 'Data export'
					},
					{
							extend: 'pdfFlash',
							filename: 'Data export'
					}
			]*/

		});
                
		$('.do-quick-search').click(function(){
			$('#AbserveTable').attr('action','{{ URL::to("orders/multisearch")}}');
			$('#AbserveTable').submit();
		});
		
	//var regionselect = '<?php echo $_GET['region'] ?>';
	//if(regionselect == ''){	
	/* var myTimer =	setInterval(function(){
			loadlink();
		}, 10000); */
	//}
		/*setInterval(function(){
			window.location.reload();
		}, 10000);*/
			
		
		 $('#regionselect').change(function(){
	
     var regionselectt = $(this).val();
   // alert(regionselect);
	
	window.location.href = "<?php echo url(); ?>/orderdetails?region=" + regionselectt;
	
   $.ajax({
				url: '<?php echo url(); ?>/orderdetails/ajaxtableload',
				type: "get",
				dataType: "json",
				data: {
					regionselect : regionselect
					},
				success: function(data) {
				//console.log(data);
				//alert(data);				
				if(data!=''){
					//$("#example1 tbody tr:first").before(data);
					$("#example1 tbody").html(data.responseText);
				}
			},
			error: function (data) {
				if(data!=''){
					$("#example1 tbody").html(data.responseText);
				}
			}
			});
	
});
		

	});
	
$(document).on("click",'.fn_accept',function(){ 

	var partner_id = $(this).closest("tr").find('.partner_id').val();
	var order_id = $(this).closest("tr").find('.orderid').val();
	var $this = $(this);

	$.ajax({
			url: '<?php echo url(); ?>/orders/porderaccept',
			type: "get",
			dataType: "json",
			data: {
				partner_id : partner_id,order_id:order_id
			},

			success: function(data) {
				console.log(data);
				if(data.message != ''){
					
					$(".sbox").before('<div class="alert '+ data.alert +' fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>Success!</strong> '+data.message+'</div>');
					if(data.id==1)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-success').text("Accepted");
					}
					if(data.id==2)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-primary').text("Accepted by Boy");

					}
					if(data.id==3)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-info').text("Order Dispatch");
					}
					if(data.id==4)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-default');
					}
					 if(data.id != 0){						
						// $this.closest("tr").remove();
						$this.closest("td").html('<i class="icon-checkmark-circle2" aria-hidden="true"></i>');
					}
					window.location.reload();
				}				
			
			}
		});
});

$(document).on("click",'.fn_reject',function(){ 

	var partner_id = $(this).closest("tr").find('.partner_id').val();
	var order_id = $(this).closest("tr").find('.orderid').val();


	var $this = $(this);
	$.ajax({
			url: '<?php echo url(); ?>/orders/porderreject',
			type: "get",
			dataType: "json",
			data: {
				partner_id : partner_id,order_id:order_id
			},
			success: function(data) {
				
				if(data.message != ''){
					$(".sbox").before('<div class="alert '+ data.alert +' fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>Success!</strong> '+data.message+'</div>');
					if(data.id ==5){
						get_lastid();
						// $this.closest("tr").remove();
						$this.closest("td").html('<i class="icon-cancel-circle2" aria-hidden="true"></i>');
					}
					window.location.reload();
				}				
			
			}
		});
});

$(document).on("change",'#delivery_boy',function(){

	var partner_id = $(this).closest("tr").find('.partner_id').val();
	var order_id = $(this).closest("tr").find('.orderid').val();
	var boy_id = $(this).val();
	var $this = $(this);

	if(boy_id !=''){
		$.ajax({
			url: '<?php echo url(); ?>/mobile/user/manualorderassigntoboy',
			type: "post",
			dataType: "json",
			data: {
				partner_id : partner_id, order_id:order_id, boy_id:boy_id
			},

			success: function(data) {
				console.log(data);
				if(data.message != ''){
					
					$(".sbox").before('<div class="alert '+ data.alert +' fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>Success!</strong> '+data.message+'</div>');
					if(data.id==1)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-success').text("Accepted");
					}
					if(data.id==2)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-primary').text("Accepted by Boy");

					}
					if(data.id==3)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-info').text("Order Dispatch");
					}
					if(data.id==4)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-default');
					}
					if(data.id != 0){						
						// $this.closest("tr").remove();
						$this.closest("td").html('<i class="icon-checkmark-circle2" aria-hidden="true"></i>');
					}
					if(data.id==5)
					{
						alert(data.message);
					}
					window.location.reload();
				}				
			
			}
		});
	} else {
		alert("Please select any one delivery boy");
		return false;
	}
});	
</script>		
@stop
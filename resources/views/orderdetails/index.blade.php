@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
<link href="{{ asset('abserve/js/datatable/buttons.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{ asset('abserve/js/datatable/jquery.dataTables.min.css')}}" rel="stylesheet">
	<script type="text/javascript" src="{{ asset('abserve/js/datatable/jquery.dataTables.min.js') }}"></script>
	<!-- <script type="text/javascript" src="{{ asset('abserve/js/datatable/dataTables.bootstrap.min.js') }}"></script>	 -->
	<script type="text/javascript" src="{{ asset('abserve/js/datatable/dataTables.buttons.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('abserve/js/datatable/buttons.flash.min.js') }}"></script>
  <div class="page-content row">
    <!-- Page header -->
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
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> {!! trans('core.abs_clr_search') !!} </a>
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			<!-- @if($access['is_add'] ==1)
	   		<a href="{{ URL::to('orderdetails/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif  --> 
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<a href="{{ URL::to( 'orderdetails/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> {!! trans('core.btn_search') !!}</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('orderdetails/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif			
		 
		</div> 		

	<div class="table-responsive" style="min-height:300px;">
    <table class="table table-striped ">
    <table class="display nowrap" id="example1" cellspacing="0" width="100%">
        <thead>
			<tr>
				<th> <input type="checkbox" class="checkall" /></th>
					<!-- <th> Date </th> -->	
					<th> Order Id </th>
                    <th> Cust Id </th>
                	<th> Res Id </th>
					<th> Cust Name </th>
					<th> Res Name </th>
					<th> Date and Time </th>
					<th> Order details </th>
					<th> MOP</th>
                    <th> Boy Id </th>
                	<th> Boy Name </th>
					<th width="70" >{!! Lang::get('core.btn_action') !!}</th>
					<th width="70" >Status</th>
				<!-- <th width="70" >{!! Lang::get('core.btn_action') !!}</th> -->
			  </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
            	<?php $order_det = \SiteHelpers::getOrderDetails($row->orderid);
            		$res_call = \SiteHelpers::getRestaurantDetails($order_det->res_id); ?>
            		@if($res_call == 1)
	                <tr>
	                	<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" /></td>
                        <!-- <td width="50">{{date('jS F Y',$row->time)}}</td> -->
                        <td width="50">{{$row->orderid}}</td>
                        <td width="50">{{$row->res_id}}</td>
                        <?php $res_detail = $model->resname($row->orderid);?>
                        <td width="50">{{$res_detail[0]->name}}</td>
                        <td width="50">{{$res_detail[0]->name}}</td>
                        <?php  date_default_timezone_set("Asia/Kolkata"); ?>
                        <td width="50">{{date('Y-m-d H:i:s',$order_det->time)}}</td>
                        <td width="50">{{$row->order_details}}</td>	
                        <td width="50">{{ $order_det->delivery_type }}</td>	
                        <td><?php $bid = \SiteHelpers::getBoyid($row->orderid);?>{!! $bid !!}</td>
                  		<td><?php $boyname = \SiteHelpers::getBoyname($bid);?>{!! $boyname !!}</td>							
						<td>
							@if($row->order_status == 0)
								<!-- <i class="fa fa-volume-up"></i> -->
								<i data-toggle="tooltip" title="Accept your order" class="icon-checkmark-circle2 @if($row->status == 0) fn_accept @endif" aria-hidden="true" style="cursor: pointer;"></i>
								<i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 @if($row->status == 0) fn_reject @endif" aria-hidden="true" style="cursor: pointer;"></i> 
								<input type="hidden" value="{{$row->partner_id}}" class="partner_id" /><input type="hidden" value="{{$row->orderid}}" class="orderid" />
							@else
								<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>
								<i data-toggle="tooltip" title="Action disabled" class="icon-cancel-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i> 
							@endif
						</td>
						<td>
							@if($row->order_status == 1 )
								<span class="label status label-success">{!! trans('core.accepted') !!}</span>
							@elseif($row->order_status == 2)
								 <span class="label status label-primary">{!! trans('core.abs_accept_by_boy') !!}</span>
							@elseif($row->order_status == 3)
								 <span class="label status label-default"> {!! trans('core.abs_order_dispatch') !!}</span>
							@elseif($row->order_status == 4)
								 <span class="label status label-info">{!! trans('core.abs_order_finished') !!}</span>
							@elseif($row->order_status == 5)
								<span class="label status label-danger">{!! trans('core.abs_rejected') !!}</span>
							@elseif($row->order_status == 6)
                                <span class="label status label-warning">Payment Pending</span>
                            @elseif($row->order_status == 7)
                                <span class="label status label-danger">Cancel</span>
                            @elseif($row->order_status == 8)
                                <span class="label status label-danger">Payment Failure</span>
                            @else
								<span class="label label-warning status">{!! trans('core.pending') !!}</span>
							@endif
								
						</td>
						<!-- <td width="30"> {{ ++$i }} </td>
						<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>									
					 @foreach ($tableGrid as $field)
						 @if($field['view'] =='1')
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
							 <td>					 
							 	@if($field['attribute']['image']['active'] =='1')
									{!! SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) !!}
								@elseif($field['field'] =='order_status')
									@if($row->order_status == 1)
										<span class="label status label-success">Accepted</span>
									@elseif($row->order_status == 2)
										 <span class="label status label-primary">Accepted by Boy</span>
									@elseif($row->order_status == 3)
										 <span class="label status label-info"> Order Dispatch</span>
									@elseif($row->order_status == 4)
										 <span class="label status label-default">Order finished</span>
									@elseif($row->order_status == 5)
										<span class="label status label-danger">Rejected</span>
									@else
										<span class="label label-warning status">Pending</span>
									@endif
								@else	
									{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
									{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}	
								@endif						 
							 </td>
							@endif	
						 @endif					 
					 @endforeach -->			 
	                </tr>
                @endif
			@endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>	@include('footer')
	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("orderdetails/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
});	
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
				
				// var url = '<?php echo url('abserve/images/default.gif');?>'
				// $('.after_ajx').html('<img class="load_image" src="'+url+'">');
				/*if (confirm("New orders Found..Do You want to view?")) {
					location.reload();
				}*/
				// $('.table_div').html(data.view);
			}
		});
	}
	
	setInterval(function () {
		if($(".buzzer_vol").is(":visible")){
			buzzer();
		}
     }, 20000);
	
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

	function get_lastid() {
		$.ajax({
			url: '<?php echo url(); ?>/orders/lastid',
			type: "POST",
			
			data: {
				partner_id : '<?php echo \Auth::id();?>'
			},
			success: function(data) {
				console.log(data);
				$('#lastid').val(data);
				//$('#loadlastid').val(data);
				/*if(data.msg == 'success'){
					$('#lastid').val(data.lastid);
					table_load();
				}*/
			}
		});
	}

	function loadlink(){
		get_lastid();
		var last_id = $('#lastid').val();
		var load_last_id = $('#loadlastid').val();
		if(last_id != null || last_id != undefined){

			if(load_last_id != last_id){

				$.ajax({
				url: '<?php echo url(); ?>/orders/ajaxload',
				type: "post",
				dataType: "json",
				data: {
					value : last_id
				},
				success: function(data) {
					//console.log(data);
					// alert(data.msg);
					if(data.msg == 'success'){
						$('#lastid').val(data.lastid);
						table_load();
					}
				}
			  });

			}else{

				

			}
		
		}
	}

	$(document).on('click','.load_image',function(){
		$(window).load();
	});
$(document).ready(function(){
	$('#example1').DataTable({
		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'excelFlash',
				filename: 'Data export'
			},
			{
				extend: 'pdfFlash',
				filename: 'Data export'
			}
		]
	});
	/*$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("orders/multisearch")}}');
		$('#AbserveTable').submit();
	});
	setInterval(function(){
		loadlink();
	}, 20000);*/
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
				}
				
			
			}
		});
});	
</script>		
@stop
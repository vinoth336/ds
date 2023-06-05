@extends('layouts.app')
@section('content')
<link href="{{ asset('abserve/js/datatable/buttons.dataTables.min.css')}}" rel="stylesheet">
<link href="{{ asset('abserve/js/datatable/jquery.dataTables.min.css')}}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('abserve/js/datatable/jquery.dataTables.min.js') }}"></script>
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
			<div class="sbox-title">&nbsp;</div>
			<div class="sbox-content"> 	
			   <div class="table-responsive" style="min-height:300px;">
			 	<table class="table table-striped " id="example1" cellspacing="0" width="100%">
			        <thead>
						<tr>
							<th> <input type="checkbox" class="checkall" /></th>
							<th> {!! trans('core.abs_time') !!} </th>
							<th> {!! trans('core.abs_order_no') !!} </th>
							<th> {!! trans('core.abs_partner_id') !!} </th>
							<th> {!! trans('core.abs_hotel_name') !!} </th>
							<th> {!! trans('core.abs_order_Detail') !!} </th>
							<th> {!! trans('core.abs_call') !!} </th>
							<th >{!! Lang::get('core.btn_action') !!}</th>
						</tr>
			        </thead>
					<tbody>        						
			          
			        </tbody>
			    </table>
				<input type="hidden" name="md" value="" />
				<input type="hidden" id="lastid" value="<?php echo $last_id;?>" />
				</div>
			</div>
		</div>	
	</div>	  
</div>	
<script>
  
	function loadlink(){
		var last_id = $('#lastid').val();

		$.ajax({
			url: '<?php echo url(); ?>/neworders/adminajaxload',
			type: "post",
			dataType: "json",
			data: {
				value : last_id
			},
			success: function(data) {
				
				if(data.msg == 'success'){
					$('#lastid').val(data.lastid);
					table_load();
				}
			}
		});
	}
	function table_load() {		
		$.ajax({
			url: '<?php echo url(); ?>/neworders/admintablelist',
			type: "post",
			success: function(data) {
				
				if(data!=''){
					if($("#example1 tbody tr td:first").hasClass("dataTables_empty")){
						$("#example1 tbody").html('');
						$("#example1 tbody").append(data);
					}else{
						$("#example1 tbody tr:first").before(data);	
					}

					
				}
				
			
			}
		});
	} 
	$(document).on('click','.load_image',function(){
		$(window).load();
	});
$(document).ready(function(){
	loadlink();
	var table = $('#example1').DataTable({
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
	setInterval(function(){
		loadlink();
	}, 20000);
});	

$(document).on("click",'.fn_accept',function(){ 
	var partner_id = $(this).closest("tr").find('.partner_id').val();
	var order_id = $(this).closest("tr").find('.orderid').val();
	var $this = $(this);
	$.ajax({
			url: '<?php echo url(); ?>/neworders/porderaccept',
			type: "get",
			dataType: "json",
			data: {
				partner_id : partner_id,order_id:order_id
			},
			success: function(data) {
				
				if(data.message != ''){
					
					$(".sbox").before('<div class="alert '+ data.alert +' fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>Success!</strong> '+data.message+'</div>');
					if(data.alert == 'alert-success'){
						 $this.closest("tr").remove();
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
			url: '<?php echo url(); ?>/neworders/porderreject',
			type: "get",
			dataType: "json",
			data: {
				partner_id : partner_id,order_id:order_id
			},
			success: function(data) {
				
				if(data.message != ''){
					$(".sbox").before('<div class="alert '+ data.alert +' fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>Success!</strong> '+data.message+'</div>');
					if(data.alert == 'alert-success'){
						 $this.closest("tr").remove();
					}
				}
				
			
			}
		});
});
$(document).on("click",'.fn_deliveryboy',function(){
	var val = $(this).closest("tr").find(".delivery_boy").val();
	var partner_id = $(this).closest("tr").find('.partner_id').val();
	var order_id = $(this).closest("tr").find('.orderid').val();
	$.ajax({
			url: '<?php echo url(); ?>/neworders/porderdboy',
			type: "get",
			data: {
				partner_id : partner_id,order_id:order_id,delivery_boy:val
			},
			success: function(data) {
				$('.fn_'+order_id).remove();
				if($('.dataTable tbody tr').length == 0 ) location.reload();
				$(".delivery_boy").html(data);
			}
		});
});

</script>
@stop
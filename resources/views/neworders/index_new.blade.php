@extends('layouts.app')
@section('content')
<link href="{{ asset('abserve/js/datatable/buttons.dataTables.min.css')}}" rel="stylesheet">
<link href="{{ asset('abserve/js/datatable/jquery.dataTables.min.css')}}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('abserve/js/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('abserve/js/datatable/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('abserve/js/datatable/buttons.flash.min.js') }}"></script>

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
			<div class="sbox-title"></div>
				<div class="sbox-content"> 	
				   <div class="table-responsive" style="min-height:300px;">
						
							
				 		<table class="table table-striped " id="example1" cellspacing="0" width="100%">
					        <thead>
								<tr>
									<th> <input type="checkbox" class="checkall" /></th>
									<th> {!! trans('core.abs_time') !!} </th>
									<th> {!! trans('core.abs_order_no') !!} </th>
									<th> {!! trans('core.abs_restaurant_name') !!} </th>
									<th> {!! trans('core.abs_order_Detail') !!} </th>
									<th width="70" >{!! Lang::get('core.btn_action') !!}</th>
								</tr>
					        </thead>
							<tbody>        						
					           
					              
					        </tbody>
			      		</table>
			 			
					</div>
				
				<input type="hidden" name="md" value="" />
				<input type="hidden" id="lastid" value="<?php echo $last_id;?>" />
				</div>
		</div>	
	</div>	  
</div>	
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
	function get_lastid() {
		$.ajax({
			url: '<?php echo url(); ?>/orders/lastid',
			type: "POST",
			dataType: "json",
			data: {
				partner_id : '<?php echo \Auth::id();?>'
			},
			success: function(data) {
				console.log(data);
				$('#lastid').val(data);
				/*if(data.msg == 'success'){
					$('#lastid').val(data.lastid);
					table_load();
				}*/
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
	function loadlink(){
		get_lastid();
		var last_id = $('#lastid').val();
		if(last_id != null || last_id != undefined){
			$.ajax({
				url: '<?php echo url(); ?>/neworders/ajaxload',
				type: "post",
				dataType: "json",
				data: {
					value : last_id
				},
				success: function(data) {
					// alert(data.msg);
					if(data.msg == 'success'){
						$('#lastid').val(data.lastid);
						table_load();
					}
				}
			});
		}
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
function table_load() {		
		$.ajax({
			url: '<?php echo url(); ?>/neworders/tablelist',
			type: "post",
			success: function(data) {
				buzzer();
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
						get_lastid();
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
						get_lastid();
						 $this.closest("tr").remove();
					}
				}
				
			
			}
		});
});
</script>		
@stop
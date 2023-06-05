@extends('layouts.app')

@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('orders?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{!! Lang::get('core.addedit') !!} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'orders/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> {!! trans('core.abs_order_details') !!}</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Time" class=" control-label col-md-4 text-left"> {!! trans('core.abs_time') !!} </label>
									<div class="col-md-6">
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('time', $row['time'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Partner Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_partner_id') !!} </label>
									<div class="col-md-6">
									  <select name='partner_id' rows='5' id='partner_id' class='select2 '   ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Boy Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_boy_id') !!} </label>
									<div class="col-md-6">
									  <select name='boy_id' rows='5' id='boy_id' class='select2 '   ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Cust Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_cust_id') !!}</label>
									<div class="col-md-6">
									  <select name='cust_id' rows='5' id='cust_id' class='select2 '   ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Promos" class=" control-label col-md-4 text-left"> {!! trans('core.abs_promos') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('promos', $row['promos'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Actual Order Value" class=" control-label col-md-4 text-left"> {!! trans('core.abs_actual_order_value') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('actual_order_value', $row['actual_order_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Order Value" class=" control-label col-md-4 text-left"> {!! trans('core.abs_order_value') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('order_value', $row['order_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Order Details" class=" control-label col-md-4 text-left"> {!! trans('core.abs_order_details') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('order_details', $row['order_details'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Order Status" class=" control-label col-md-4 text-left"> {!! trans('core.abs_order_status') !!} </label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='order_status' value ='1'  @if($row['order_status'] == '1') checked="checked" @endif > Delivered </label>
					<label class='radio radio-inline'>
					<input type='radio' name='order_status' value ='0'  @if($row['order_status'] == '0') checked="checked" @endif > Not yet delivered </label> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Distance" class=" control-label col-md-4 text-left"> {!! trans('core.abs_distance') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('distance', $row['distance'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Charges" class=" control-label col-md-4 text-left"> {!! trans('core.abs_delivery_charges') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('delivery_charges', $row['delivery_charges'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('orders?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#partner_id").jCombo("{{ URL::to('orders/comboselect?filter=tb_users:id:first_name') }}",
		{   selected_value : '{{ $row["partner_id"] }}' });
		
		$("#boy_id").jCombo("{{ URL::to('orders/comboselect?filter=abserve_deliveryboys:id:username') }}",
		{  selected_value : '{{ $row["boy_id"] }}' });
		
		$("#cust_id").jCombo("{{ URL::to('orders/comboselect?filter=abserve_customers:id:username') }}",
		{  selected_value : '{{ $row["cust_id"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
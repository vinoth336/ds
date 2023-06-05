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
		<li><a href="{{ URL::to('cashondeliveryorder?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'cashondeliveryorder/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> {!! trans('core.abs_deliveryboy_orders') !!}</legend>
									
								  <div class="form-group  " >
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Cust Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_cust_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('cust_id', $row['cust_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Res Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_res_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('res_id', $row['res_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Total Price" class=" control-label col-md-4 text-left"> {!! trans('core.abs_total_price') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('total_price', $row['total_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="S Tax" class=" control-label col-md-4 text-left"> {!! trans('core.abs_s_tax') !!}</label>
									<div class="col-md-6">
									  {!! Form::text('s_tax', $row['s_tax'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Coupon Price" class=" control-label col-md-4 text-left"> {!! trans('core.abs_coupon_price') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('coupon_price', $row['coupon_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Grand Total" class=" control-label col-md-4 text-left"> {!! trans('core.abs_grand_total') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('grand_total', $row['grand_total'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Address" class=" control-label col-md-4 text-left"> {!! trans('core.abs_address') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('address', $row['address'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Building" class=" control-label col-md-4 text-left"> {!! trans('core.abs_building') !!}  </label>
									<div class="col-md-6">
									  {!! Form::text('building', $row['building'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Landmark" class=" control-label col-md-4 text-left"> {!! trans('core.abs_landmark') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('landmark', $row['landmark'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Status" class=" control-label col-md-4 text-left"> {!! trans('core.abs_status') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('status', $row['status'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Coupon Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_coupon_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('coupon_id', $row['coupon_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Time" class=" control-label col-md-4 text-left"> {!! trans('core.abs_time') !!}</label>
									<div class="col-md-6">
									  {!! Form::text('time', $row['time'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Date" class=" control-label col-md-4 text-left"> {!! trans('core.abs_date') !!}</label>
									<div class="col-md-6">
									  {!! Form::text('date', $row['date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery" class=" control-label col-md-4 text-left"> {!! trans('core.abs_date') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('delivery', $row['delivery'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Type" class=" control-label col-md-4 text-left"> {!! trans('core.abs_delivery') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('delivery_type', $row['delivery_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('cashondeliveryorder?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
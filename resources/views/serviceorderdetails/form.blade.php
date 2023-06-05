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
		<li><a href="{{ URL::to('serviceorderdetails?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'serviceorderdetails/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Service Order Details</legend>
									
								  <div class="form-group  " >
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Orderid" class=" control-label col-md-4 text-left"> Orderid </label>
									<div class="col-md-6">
									  {!! Form::text('orderid', $row['orderid'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="User Id" class=" control-label col-md-4 text-left"> User Id </label>
									<div class="col-md-6">
									  {!! Form::text('user_id', $row['user_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Cust Id" class=" control-label col-md-4 text-left"> Cust Id </label>
									<div class="col-md-6">
									  {!! Form::text('cust_id', $row['cust_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="C Phone Number" class=" control-label col-md-4 text-left"> C Phone Number </label>
									<div class="col-md-6">
									  {!! Form::text('c_phone_number', $row['c_phone_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> Email </label>
									<div class="col-md-6">
									  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Location" class=" control-label col-md-4 text-left"> Location </label>
									<div class="col-md-6">
									  <textarea name='location' rows='5' id='location' class='form-control '  
				           >{{ $row['location'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="To Location" class=" control-label col-md-4 text-left"> To Location </label>
									<div class="col-md-6">
									  <textarea name='to_location' rows='5' id='to_location' class='form-control '  
				           >{{ $row['to_location'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Pin Code" class=" control-label col-md-4 text-left"> Pin Code </label>
									<div class="col-md-6">
									  {!! Form::text('pin_code', $row['pin_code'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="To Pin Code" class=" control-label col-md-4 text-left"> To Pin Code </label>
									<div class="col-md-6">
									  {!! Form::text('to_pin_code', $row['to_pin_code'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Comments" class=" control-label col-md-4 text-left"> Comments </label>
									<div class="col-md-6">
									  {!! Form::text('comments', $row['comments'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Description" class=" control-label col-md-4 text-left"> Description </label>
									<div class="col-md-6">
									  {!! Form::text('description', $row['description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="File" class=" control-label col-md-4 text-left"> File </label>
									<div class="col-md-6">
									  {!! Form::text('file', $row['file'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Category" class=" control-label col-md-4 text-left"> Category </label>
									<div class="col-md-6">
									  {!! Form::text('category', $row['category'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Subcategory" class=" control-label col-md-4 text-left"> Subcategory </label>
									<div class="col-md-6">
									  {!! Form::text('subcategory', $row['subcategory'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Subnode" class=" control-label col-md-4 text-left"> Subnode </label>
									<div class="col-md-6">
									  {!! Form::text('subnode', $row['subnode'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Vendor" class=" control-label col-md-4 text-left"> Vendor </label>
									<div class="col-md-6">
									  {!! Form::text('vendor', $row['vendor'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Vendor Name" class=" control-label col-md-4 text-left"> Vendor Name </label>
									<div class="col-md-6">
									  {!! Form::text('vendor_name', $row['vendor_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Type" class=" control-label col-md-4 text-left"> Type </label>
									<div class="col-md-6">
									  {!! Form::text('type', $row['type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Date" class=" control-label col-md-4 text-left"> Date </label>
									<div class="col-md-6">
									  <textarea name='date' rows='5' id='date' class='form-control '  
				           >{{ $row['date'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Order Instruction" class=" control-label col-md-4 text-left"> Order Instruction </label>
									<div class="col-md-6">
									  {!! Form::text('order_instruction', $row['order_instruction'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Service Status" class=" control-label col-md-4 text-left"> Service Status </label>
									<div class="col-md-6">
									  {!! Form::text('service_status', $row['service_status'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Order Amount" class=" control-label col-md-4 text-left"> Order Amount </label>
									<div class="col-md-6">
									  {!! Form::text('order_amount', $row['order_amount'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Start Time" class=" control-label col-md-4 text-left"> Start Time </label>
									<div class="col-md-6">
									  <textarea name='start_time' rows='5' id='start_time' class='form-control '  
				           >{{ $row['start_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="End Time" class=" control-label col-md-4 text-left"> End Time </label>
									<div class="col-md-6">
									  <textarea name='end_time' rows='5' id='end_time' class='form-control '  
				           >{{ $row['end_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Dboy Id" class=" control-label col-md-4 text-left"> Dboy Id </label>
									<div class="col-md-6">
									  {!! Form::text('dboy_id', $row['dboy_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Dboy Name" class=" control-label col-md-4 text-left"> Dboy Name </label>
									<div class="col-md-6">
									  {!! Form::text('dboy_name', $row['dboy_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Payment Status" class=" control-label col-md-4 text-left"> Payment Status </label>
									<div class="col-md-6">
									  {!! Form::text('payment_status', $row['payment_status'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Service Charge" class=" control-label col-md-4 text-left"> Service Charge </label>
									<div class="col-md-6">
									  {!! Form::text('service_charge', $row['service_charge'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Type" class=" control-label col-md-4 text-left"> Delivery Type </label>
									<div class="col-md-6">
									  {!! Form::text('delivery_type', $row['delivery_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Reference No" class=" control-label col-md-4 text-left"> Reference No </label>
									<div class="col-md-6">
									  {!! Form::text('reference_no', $row['reference_no'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Mop" class=" control-label col-md-4 text-left"> Mop </label>
									<div class="col-md-6">
									  {!! Form::text('mop', $row['mop'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Subscription Status" class=" control-label col-md-4 text-left"> Subscription Status </label>
									<div class="col-md-6">
									  {!! Form::text('subscription_status', $row['subscription_status'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Tracking Id" class=" control-label col-md-4 text-left"> Tracking Id </label>
									<div class="col-md-6">
									  {!! Form::text('tracking_id', $row['tracking_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Region" class=" control-label col-md-4 text-left"> Region </label>
									<div class="col-md-6">
									  {!! Form::text('region', $row['region'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('serviceorderdetails?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
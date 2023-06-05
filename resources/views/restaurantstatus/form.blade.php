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
		<li><a href="{{ URL::to('restaurantstatus?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'restaurantstatus/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Restaurantstatus</legend>
									
								  <div class="form-group  " >
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Name" class=" control-label col-md-4 text-left"> Name </label>
									<div class="col-md-6">
									  {!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Location" class=" control-label col-md-4 text-left"> Location </label>
									<div class="col-md-6">
									  {!! Form::text('location', $row['location'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Logo" class=" control-label col-md-4 text-left"> Logo </label>
									<div class="col-md-6">
									  {!! Form::text('logo', $row['logo'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Partner Id" class=" control-label col-md-4 text-left"> Partner Id </label>
									<div class="col-md-6">
									  {!! Form::text('partner_id', $row['partner_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Premium Plan" class=" control-label col-md-4 text-left"> Premium Plan </label>
									<div class="col-md-6">
									  {!! Form::text('premium_plan', $row['premium_plan'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Opening Time" class=" control-label col-md-4 text-left"> Opening Time </label>
									<div class="col-md-6">
									  <textarea name='opening_time' rows='5' id='opening_time' class='form-control '  
				           >{{ $row['opening_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Closing Time" class=" control-label col-md-4 text-left"> Closing Time </label>
									<div class="col-md-6">
									  <textarea name='closing_time' rows='5' id='closing_time' class='form-control '  
				           >{{ $row['closing_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Breakfast Opening Time" class=" control-label col-md-4 text-left"> Breakfast Opening Time </label>
									<div class="col-md-6">
									  <textarea name='breakfast_opening_time' rows='5' id='breakfast_opening_time' class='form-control '  
				           >{{ $row['breakfast_opening_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Breakfast Closing Time" class=" control-label col-md-4 text-left"> Breakfast Closing Time </label>
									<div class="col-md-6">
									  <textarea name='breakfast_closing_time' rows='5' id='breakfast_closing_time' class='form-control '  
				           >{{ $row['breakfast_closing_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Lunch Opening Time" class=" control-label col-md-4 text-left"> Lunch Opening Time </label>
									<div class="col-md-6">
									  <textarea name='lunch_opening_time' rows='5' id='lunch_opening_time' class='form-control '  
				           >{{ $row['lunch_opening_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Lunch Closing Time" class=" control-label col-md-4 text-left"> Lunch Closing Time </label>
									<div class="col-md-6">
									  <textarea name='lunch_closing_time' rows='5' id='lunch_closing_time' class='form-control '  
				           >{{ $row['lunch_closing_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Dinner Opening Time" class=" control-label col-md-4 text-left"> Dinner Opening Time </label>
									<div class="col-md-6">
									  <textarea name='dinner_opening_time' rows='5' id='dinner_opening_time' class='form-control '  
				           >{{ $row['dinner_opening_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Dinner Closing Time" class=" control-label col-md-4 text-left"> Dinner Closing Time </label>
									<div class="col-md-6">
									  <textarea name='dinner_closing_time' rows='5' id='dinner_closing_time' class='form-control '  
				           >{{ $row['dinner_closing_time'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Phone" class=" control-label col-md-4 text-left"> Phone </label>
									<div class="col-md-6">
									  {!! Form::text('phone', $row['phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Secondary Phone Number" class=" control-label col-md-4 text-left"> Secondary Phone Number </label>
									<div class="col-md-6">
									  {!! Form::text('secondary_phone_number', $row['secondary_phone_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Secondary Phone Number2" class=" control-label col-md-4 text-left"> Secondary Phone Number2 </label>
									<div class="col-md-6">
									  {!! Form::text('secondary_phone_number2', $row['secondary_phone_number2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Service Tax" class=" control-label col-md-4 text-left"> Service Tax </label>
									<div class="col-md-6">
									  {!! Form::text('service_tax', $row['service_tax'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Ds Commission" class=" control-label col-md-4 text-left"> Ds Commission </label>
									<div class="col-md-6">
									  {!! Form::text('ds_commission', $row['ds_commission'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Max Packaging Charge" class=" control-label col-md-4 text-left"> Max Packaging Charge </label>
									<div class="col-md-6">
									  {!! Form::text('max_packaging_charge', $row['max_packaging_charge'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Charge" class=" control-label col-md-4 text-left"> Delivery Charge </label>
									<div class="col-md-6">
									  {!! Form::text('delivery_charge', $row['delivery_charge'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Vat" class=" control-label col-md-4 text-left"> Vat </label>
									<div class="col-md-6">
									  {!! Form::text('vat', $row['vat'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Cuisine" class=" control-label col-md-4 text-left"> Cuisine </label>
									<div class="col-md-6">
									  <textarea name='cuisine' rows='5' id='cuisine' class='form-control '  
				           >{{ $row['cuisine'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Call Handling" class=" control-label col-md-4 text-left"> Call Handling </label>
									<div class="col-md-6">
									  {!! Form::text('call_handling', $row['call_handling'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Time" class=" control-label col-md-4 text-left"> Delivery Time </label>
									<div class="col-md-6">
									  {!! Form::text('delivery_time', $row['delivery_time'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Pure Veg" class=" control-label col-md-4 text-left"> Pure Veg </label>
									<div class="col-md-6">
									  {!! Form::text('pure_veg', $row['pure_veg'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer" class=" control-label col-md-4 text-left"> Offer </label>
									<div class="col-md-6">
									  {!! Form::text('offer', $row['offer'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Min Order Value" class=" control-label col-md-4 text-left"> Min Order Value </label>
									<div class="col-md-6">
									  {!! Form::text('min_order_value', $row['min_order_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Max Value" class=" control-label col-md-4 text-left"> Max Value </label>
									<div class="col-md-6">
									  {!! Form::text('max_value', $row['max_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer From" class=" control-label col-md-4 text-left"> Offer From </label>
									<div class="col-md-6">
									  {!! Form::text('offer_from', $row['offer_from'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer To" class=" control-label col-md-4 text-left"> Offer To </label>
									<div class="col-md-6">
									  {!! Form::text('offer_to', $row['offer_to'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Budget" class=" control-label col-md-4 text-left"> Budget </label>
									<div class="col-md-6">
									  {!! Form::text('budget', $row['budget'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Rating" class=" control-label col-md-4 text-left"> Rating </label>
									<div class="col-md-6">
									  {!! Form::text('rating', $row['rating'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Agreement" class=" control-label col-md-4 text-left"> Agreement </label>
									<div class="col-md-6">
									  {!! Form::text('agreement', $row['agreement'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Agreement Status" class=" control-label col-md-4 text-left"> Agreement Status </label>
									<div class="col-md-6">
									  {!! Form::text('agreement_status', $row['agreement_status'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Latitude" class=" control-label col-md-4 text-left"> Latitude </label>
									<div class="col-md-6">
									  {!! Form::text('latitude', $row['latitude'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Longitude" class=" control-label col-md-4 text-left"> Longitude </label>
									<div class="col-md-6">
									  {!! Form::text('longitude', $row['longitude'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
								  </div> 					
								  <div class="form-group  " >
									<label for="Res Desc" class=" control-label col-md-4 text-left"> Res Desc </label>
									<div class="col-md-6">
									  {!! Form::text('res_desc', $row['res_desc'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Active" class=" control-label col-md-4 text-left"> Active </label>
									<div class="col-md-6">
									  {!! Form::text('active', $row['active'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="New Start Date" class=" control-label col-md-4 text-left"> New Start Date </label>
									<div class="col-md-6">
									  {!! Form::text('new_start_date', $row['new_start_date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="New End Date" class=" control-label col-md-4 text-left"> New End Date </label>
									<div class="col-md-6">
									  {!! Form::text('new_end_date', $row['new_end_date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Res Sequence" class=" control-label col-md-4 text-left"> Res Sequence </label>
									<div class="col-md-6">
									  {!! Form::text('res_sequence', $row['res_sequence'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Res Seq Start" class=" control-label col-md-4 text-left"> Res Seq Start </label>
									<div class="col-md-6">
									  {!! Form::text('res_seq_start', $row['res_seq_start'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Res Seq End" class=" control-label col-md-4 text-left"> Res Seq End </label>
									<div class="col-md-6">
									  {!! Form::text('res_seq_end', $row['res_seq_end'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('restaurantstatus?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
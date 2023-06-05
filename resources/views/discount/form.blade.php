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
		<li><a href="{{ URL::to('discount?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'discount/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> DS Coupon</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  <div class="form-group  " >
									<label for="Coupon Use Type" class=" control-label col-md-4 text-left"></label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' id="res_coupon" name='coupon_type' value ='1' required @if(($row['res_id'] != '0') && ($row['res_id'] != '')) checked="checked" @endif > Restaurant Coupon </label>
					<label class='radio radio-inline'>
					<input type='radio' id="ds_coupon" name='coupon_type' value ='2' required @if($row['res_id'] == '0') checked="checked" @endif > DS Coupon </label> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>					
								  <div class="form-group  " id="rest_id" style="display:none;" >
									<label for="Restaurant Id" class=" control-label col-md-4 text-left"> Restaurant Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  <select name='res_id' rows='5' id='res_id' class='select2 '  ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Coupon Name" class=" control-label col-md-4 text-left"> Coupon Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('coupon_name', $row['coupon_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Coupon Code" class=" control-label col-md-4 text-left"> Coupon Code <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('coupon_code', $row['coupon_code'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Coupon Description" class=" control-label col-md-4 text-left"> Coupon Description </label>
									<div class="col-md-6">
									  <textarea name='coupon_desc' rows='5' id='coupon_desc' class='form-control '  
				           >{{ $row['coupon_desc'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  
                                
                                  
                                    <div class="form-group  " >
                                    <label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name='region' rows='5' id='region' class='select2' >
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>
                                  
                                  
                                  					
								  <div class="form-group  " >
									<label for="Coupon Use Type" class=" control-label col-md-4 text-left"> Coupon Use Type <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='coupon_use_type' value ='1' required @if($row['coupon_use_type'] == '1') checked="checked" @endif > Single </label>
					<label class='radio radio-inline'>
					<input type='radio' name='coupon_use_type' value ='2' required @if($row['coupon_use_type'] == '2') checked="checked" @endif > Multiple </label> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer Type" class=" control-label col-md-4 text-left"> Offer Type <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='offer_type' value ='1' required @if($row['offer_type'] == '1') checked="checked" @endif > Free Delivery </label>
					<label class='radio radio-inline'>
					<input type='radio' name='offer_type' value ='2' required @if($row['offer_type'] == '2') checked="checked" @endif > Offer </label> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer" class=" control-label col-md-4 text-left"> Offer <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('offer', $row['offer'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>   					
								  <div class="form-group  " >
									<label for="Min Order Value" class=" control-label col-md-4 text-left"> Min Order Value <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('min_order_value', $row['min_order_value'],array('class'=>'form-control', 'placeholder'=>'',  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Max Discount Apply" class=" control-label col-md-4 text-left"> Max Discount Apply <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('max_value', $row['max_value'],array('class'=>'form-control', 'placeholder'=>'',  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>					
								  <div class="form-group  " >
									<label for="Offer From" class=" control-label col-md-4 text-left"> Offer From <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
                                        <div class="input-group m-b" style="width:150px !important;">
                                        	@if($row['offer_from'] =='0000-00-00') {{--*/ $offer_from = "" /*--}} @else {{--*/ $offer_from = $row['offer_from'] /*--}} @endif
                                            {!! Form::text('offer_from', $offer_from, array('class'=>'form-control date', 'required'=>'true')) !!}
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer To" class=" control-label col-md-4 text-left"> Offer To <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
                                        <div class="input-group m-b" style="width:150px !important;">
                                        	@if($row['offer_to'] =='0000-00-00') {{--*/ $offer_to = "" /*--}} @else {{--*/ $offer_to = $row['offer_to'] /*--}} @endif
                                            {!! Form::text('offer_to', $offer_to, array('class'=>'form-control date', 'required'=>'true')) !!}
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div> 
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
					<button type="button" onclick="location.href='{{ URL::to('discount?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		<?php if(session()->get('gid') == '7'){ ?>
			$("#res_id").jCombo(base_url+"discount/comboselect?filter=abserve_restaurants:id:name&limit=where:region:=:'{!! session()->get('rkey') !!}'",
			{  selected_value : '{{ $row["res_id"] }}' });
		<?php	}else{ ?>	
			$("#res_id").jCombo("{{ URL::to('discount/comboselect?filter=abserve_restaurants:id:name') }}",
	    	{  selected_value : '{{ $row["res_id"] }}' });
		<?php	} ?>		
		
	
		<?php if(session()->get('gid') == '7'){ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
			{  selected_value : '{!! session()->get('rid') !!}' });
			$('#region').attr('readonly', true);
		<?php } else { ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
			{  selected_value : '{!! $row["region"] !!}' });		
		<?php } ?> 
		
		
		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
	});
	
	$('#res_coupon').on('ifClicked', function(event){
	  $("#rest_id").css('display', 'block');
	  $('#res_id').attr('required', true);
	});
	
	$('#ds_coupon').on('ifClicked', function(event){
	  $("#rest_id").css('display', 'none');
	  $('#res_id').removeAttr('required');
	});
	
	$( window ).load(function() {
	  var res_id = '<?php echo $row['res_id'] ?>';
	  
	  if(res_id !=''){
		  if(res_id !=0){
			$("#rest_id").css('display', 'block');
	  		$('#res_id').removeAttr('required');
		  }
	  }
	});
	
	</script>		 
@stop
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
		<li><a href="{{ URL::to('orderupdateamount?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'orderupdateamount/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Orderupdateamount</legend>
									
								  <div class="form-group " >
									<label for="Order Id" class=" control-label col-md-4 text-left"> Order Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Total Price" class=" control-label col-md-4 text-left"> Total Price </label>
									<div class="col-md-6">
									  {!! Form::text('total_price', $row['total_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <!--<div class="form-group  " >
									<label for="Ds Commission" class=" control-label col-md-4 text-left"> Ds Commission </label>
									<div class="col-md-6">
									  {!! Form::text('ds_commission', $row['ds_commission'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>--> 					
								  <div class="form-group  " >
									<label for="S Tax" class=" control-label col-md-4 text-left"> GST </label>
									<div class="col-md-6">
									  {!! Form::text('s_tax', $row['s_tax'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Coupon Price" class=" control-label col-md-4 text-left"> Coupon Price </label>
									<div class="col-md-6">
									  {!! Form::text('coupon_price', $row['coupon_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Offer Price" class=" control-label col-md-4 text-left"> Offer Price </label>
									<div class="col-md-6">
									  {!! Form::text('offer_price', $row['offer_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								   					
								  <div class="form-group  " >
									<label for="Packaging Charge" class=" control-label col-md-4 text-left"> Packaging Charge </label>
									<div class="col-md-6">
									  {!! Form::text('packaging_charge', $row['packaging_charge'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
									<label for="Grand Total" class=" control-label col-md-4 text-left"> Grand Total </label>
									<div class="col-md-6">
									  {!! Form::text('grand_total', $row['grand_total'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
								  <div class="form-group  " style="display:none;" >
									<label for="Grand Total" class=" control-label col-md-4 text-left"> Address </label>
									<div class="col-md-6">
									  {!! Form::textarea('address', $row['address'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  
                                  </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('orderupdateamount?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
		
		$('input[name$="total_price"], input[name$="s_tax"], input[name$="packaging_charge"], input[name$="delivery_charge"], input[name$="coupon_price"], input[name$="offer_price"]').on('change keyup' ,function(){
               var value1 = parseFloat($('input[name$="total_price"]').val()) || 0;
               var value2 = parseFloat($('input[name$="s_tax"]').val()) || 0;
			   var value3 = parseFloat($('input[name$="packaging_charge"]').val()) || 0;
			   var value4 = parseFloat($('input[name$="delivery_charge"]').val()) || 0;
			   var value5 = parseFloat($('input[name$="coupon_price"]').val()) || 0;
			   var value6 = parseFloat($('input[name$="offer_price"]').val()) || 0;
               $('input[name$="grand_total"]').val(value1 + value2 + value3 + value4 - value5 - value6);
            });
	});
	
	</script>		 
@stop
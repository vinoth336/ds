@extends('layouts.app')
@section('content')
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>

    <div class="page-content-wrapper m-t">	
	
				          {!! Form::open(array('class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'ordercount','method'=>'POST')) !!}
		                <div class="col-md-12">    
                        
                        
                         <div class="form-group">
				<?php  
                if(session()->get('gid') == '1'){
               		 $deliveryboy = \DB::select("SELECT *  FROM `abserve_deliveryboys` ");
                }elseif(session()->get('gid') == '7'){
               		 $deliveryboy = \DB::select("SELECT *  FROM `abserve_deliveryboys` WHERE `region`='".session()->get('rid')."'");
                }	          
                ?>
                          <label for="Delivery Boys"  class=" control-label col-md-4 text-left"> Delivery Boys <span class="asterix"> * </span></label>
					       <div class="col-md-6">                            
                         
                               <select name='deliveryboys' rows='5' id='deliveryboys' class='select2' required>
                                <option value="" >-- Please Select --</option>
                                 <?php foreach($deliveryboy as $dsboys)  {  ?>
                                <option name="<?php echo $dsboys->username;  ?>" value="<?php echo $dsboys->id;  ?>"><?php echo $dsboys->username;  ?></option>               <?php }  ?> 
                            </select>
                          <span class="display-error" style="color:red;"> </span>    
                        </div>
					
					<div class="col-md-2"></div>
				</div> 
            
            
                         <div class="form-group  " > 
                        <label for="Duration"  class=" control-label col-md-4 text-left"> Duration <span class="asterix"> * </span></label>
                        <div class="col-md-6">                            
                          
                               <select name='duration' rows='5' id='duration' class='select2' required>
                                <option value="" >-- Please Select --</option>
                                <option value="0" @if($row['all'] == '0') selected="selected" @endif >ALL</option>
                                <option value="1" @if($row['today'] == '1') selected="selected" @endif >TODAY</option>
                                <option value="2" @if($row['week'] == '2') selected="selected" @endif>WEEK</option> 
                                <option value="3" @if($row['month'] == '3') selected="selected" @endif>MONTH</option> 
                                <option value="4" @if($row['custom'] == '4') selected="selected" @endif>CUSTOM</option> 
                            </select>
                              <span class="display-error1" style="color:red;"> </span>  
                        </div>
                        <div class="col-md-2"></div>
                    </div>
            
            
            
            <div class="form-group order_date" >
                        <label for="Offer From" class=" control-label col-md-4 text-left"> Offer From </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['offer_from'] =='0000-00-00') {{--*/ $offer_from = "" /*--}} @else {{--*/ $offer_from = $row['offer_from'] /*--}} @endif
                                {!! Form::text('offer_from', $offer_from ,array('class'=>'form-control date','id'=>'offer_from')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                              <span class="display-error2" style="color:red;"> </span>   
                         </div> 
                         <div class="col-md-2"></div>
                    </div>
                    
                    
                    <div class="form-group order_date">
                        <label for="Offer To" class=" control-label col-md-4 text-left"> Offer To </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['offer_to'] =='0000-00-00') {{--*/ $offer_to = "" /*--}} @else {{--*/ $offer_to = $row['offer_to'] /*--}} @endif
                                {!! Form::text('offer_to', $offer_to ,array('class'=>'form-control date','id'=>'offer_to')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                             <span class="display-error3" style="color:red;"> </span>    
                         </div> 
                         <div class="col-md-2"></div>
                    </div>
            
            
                      <div class="form-group"> 
								<label for="" class=" control-label col-md-4 text-left"></label>
								<div class="col-md-6 ">
								<a name="submit" readonly="readonly" id="submit" class="btn btn-primary btn-sm col-md-2 text-left" >Submit</a>
								</div> 
					</div>
                    </div>
            	{!! Form::close() !!}
           <div id="content-loader">
            
    </div>
       <link href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" />
       
        <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

	<script>
	
$(document).ready(function(e) {
var durationvalue =  $('#duration option:selected').val();
if(durationvalue == '4'){
$('.order_date').show();
}else {
$('.order_date').hide();	
}    


$('#submit').click(function(){
             
	           var  error = "This value is required.";
				
				var deliveryboys =  $('#deliveryboys option:selected').val();
				    if(deliveryboys == ''){
				         $(".display-error").html(error);
						 $(".display-error").show();
				     }else {
				       	
				var durationvalue =  $('#duration option:selected').val();
				    if(durationvalue == ''){
				        $(".display-error1").html(error);
						$(".display-error").hide();
						$(".display-error1").show();	
				    }else {
				      
				if(durationvalue == '4'){
				var offer_from = $("#offer_from").val();
				//alert(offer_from);
				var offer_to = $("#offer_to").val();
				//alert(offer_to);
				     if(offer_from == ''){
						// alert();
				       $(".display-error2").html(error);
					   $(".display-error").hide();
					   $(".display-error1").hide();
					   $(".display-error2").show();			
				   }else {
				      if(offer_to == ''){
						 // alert("1");
				       $(".display-error3").html(error);
					   $(".display-error2").hide();	
					   $(".display-error3").show();
					   $(".display-error1").hide();
					   $(".display-error").hide();		
				   }else{
			   $(".display-error").hide();
			  $(".display-error1").hide();
			   $(".display-error2").hide();
			  $(".display-error3").hide();
				$.ajax({
					url: '<?php echo url(); ?>/deliveryboydistance/ordercount',
					type: "POST",
					data: {deliveryboys:deliveryboys,duration:durationvalue,from_date:offer_from,to_date:offer_to},
					success: function(data){
					 $('#content-loader').html(data);
					}
				});
				
				   }}}else {
				
			  $(".display-error").hide();
			  $(".display-error1").hide();
				
				$.ajax({
					url: '<?php echo url(); ?>/deliveryboydistance/ordercount',
					type: "POST",
					data: {deliveryboys:deliveryboys,duration:durationvalue},
					success: function(data){
						//alert(data);
					 $('#content-loader').html(data);
					}
				});
		        
				}}}
			   
	});

});	
	
	
$( "#duration" ).change(function() {
var durationvalue =  $('#duration option:selected').val();
if(durationvalue == '4'){
$('.order_date').show();
}else {
$('.order_date').hide();	
}

});
   </script>
@stop
@extends('layouts.app')

@section('content')
  
  

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Reports </h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">Reports</li>
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

		 {!! Form::open(array('url'=>'cashondeliveryorder/deliveryview?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset>
                        <legend> Report</legend>
                        <!--legend> {!! trans('core.abs_deliveryboy_orders') !!}</legend-->
                        
                        
                        
                                 <div class="form-group  " >
                                    <label for="Region" class=" control-label col-md-4 text-left"> Region </label>
                                    <div class="col-md-6"> 
                                        <select rows='9' id="regionselect" class="form-control regionselect">
                                        <?php if(\Auth::user()->group_id == 1) {?>
                                        <option value="">All region</option>
                                        <?php }  ?>
                                          <?php foreach ($region_details as $key => $value)
											       { 
                                                   echo '<option value="'.$value->region_keyword.'">'.$value->region_name.'</option>';
                                                   } 
										
										     ?>
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                  </div>
                                  
								
                                  
								  <div class="form-group  " >
									<label for="Id" class=" control-label col-md-4 text-left"> Restaurant Name </label>
                                        <div class="col-md-6">
                                         <select name='restaurant_id' id='restaurant_id' class='form-control restaurant_id' >
                                           <option value =''>Select Restaurant Name </option>
                                            <?php foreach ($partner_hotels as $key => $value)
											       { 
                                                   echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                                   }
										     ?>
                                        </select>                           
									</div>  
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  
                                 <div class="form-group  " >
									<label for="Id" class=" control-label col-md-4 text-left"> Customer Name </label>
                                        <div class="col-md-6">
                                         <select name='customer_id' id='customer_id' class='form-control ' >
                                           <option value =''>Select Customer Name </option>
                                            <?php foreach ($customer_details as $key => $value)
											       { 
                                                   echo '<option value="'.$value->id.'">'.$value->first_name.' '.$value->last_name.'</option>';
                                                   }
										     ?>
                                        </select>                           
									</div>  
									 <div class="col-md-2">
									 	
									 </div>
								  </div>					
								 			
								  <div class="form-group  " >
									<label for="Total Price" class=" control-label col-md-4 text-left"> Delivery Type  </label>
									<div class="col-md-6">
									  <select name='delivery_type[]' id='delivery_type' class='select2 ' multiple>
                                            <!--<option value =''>Select Delivery Type </option>-->
                                      		<option value="cod">CashOnDelivery</option>
                                            <option value="ccavenue">OnlinePayment</option>
                                       </select>
                                       
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="S Tax" class=" control-label col-md-4 text-left"> Delivery Boy</label>
									<div class="col-md-6">
									 <select name='deliveryboy_id' id='deliveryboy_id' class='form-control ' >
                                           <option value =''>Select Delivery Boy </option>
                                            <?php foreach ($deliveryboy_details as $key => $value)
											       { 
                                                   echo '<option value="'.$value->id.'">'.$value->username.'</option>';
                                                   }
										     ?>
                                        </select>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  
                                  
                                  <div class="form-group  " >
									<label for="status" class=" control-label col-md-4 text-left">Order Status </label>
									<div class="col-md-6">
									 <select name='status[]' id='status' class='select2 ' multiple>
                                            <!--<option value =''>Select Order Status</option>-->
                                            <option value ='4'>Order Finished</option>
                                            <option value ='5'>Rejected by Restaurants</option>
                                            <option value ='6'>Rejected by Admin</option>
                                            <option value ='7'>Payment Pending</option>
                                            <option value ='8'>Payment Aborted</option>
                                            <option value ='9'>Payment Failure</option>
                                            <option value ='10'>Order Canceled</option>
                                            <option value ='11'>Order Returned</option>
                                        </select>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  
                                  
                                   <div class="form-group  " >
									<label for="Cust Id" class=" control-label col-md-4 text-left"> From Date *</label>
									 <div class="col-md-6">                                          
                                        <div class="input-group m-b" style="width:150px !important;">
                                            <input type="text" name="report_from_date" id="report_from_date" class="form-control" required='true' />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                         <span class="display-error1" style="color:red;"> </span>  
                                     </div> 
									 <div class="col-md-2">
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Res Id" class=" control-label col-md-4 text-left"> To Date *</label>
									<div class="col-md-6">                                          
                                        <div class="input-group m-b" style="width:150px !important;">
                                           <input type="text" name="report_to_date" id="report_to_date" class="form-control" required='true' />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div> 
                                         <span class="display-error2" style="color:red;"> </span> 
                                     </div> 
									 <div class="col-md-2 res" >
									 	
									 </div>
								  </div> 		 					
								  </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-1">	
					<a name="view" readonly="readonly" id="view" class="btn btn-info btn-sm export" ><i class="fa  fa-check-circle"></i> View</a>
					</div>	 
                    <div class="col-sm-1">
                    <button type="submit" name="apply" class="btn btn-info btn-sm export" ><i class="fa  fa-check-circle"></i> Export</button>	
					
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
           <div id="content-loader">
            
    </div>
	</div>
</div>		 
</div>	
</div>

  
        <link href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

  	 
   <script type="text/javascript">
  
   
   	$(document).ready(function() { 
	
    $('#regionselect').change(function(){
		
		 $('#customer_id').html("");
		 $('#restaurant_id').html("");
		 $('#deliveryboy_id').html("");

    var regionselect = $(this).val();
   // alert(regionselect);
  
	 
   $.ajax({
				url: '<?php echo url(); ?>/cashondeliveryorder/regionselect',
				type: "post",
				data: {
					regionselect : regionselect
				},
				success: function(response) {
					//alert(response);
				
					var discount = response.split("@@");
					$('#customer_id').html(discount[0]);
					$('#restaurant_id').html(discount[1]);
					$('#deliveryboy_id').html(discount[2]);
					
				}
			});
  
});
	
	       $('#report_from_date').datepicker({
			 	 onSelect: function(datesel) {
				$('#report_from_date').trigger('change')
			   }
			});
			
			 $('#report_to_date').datepicker({
			 	 onSelect: function(datesel) {
				$('#report_to_date').trigger('change')
			   }
			});
			
					
			$('#report_from_date').change(
			    function(event) {
				$('#SelectedDate').text("Selected date: " + this.value);
				$('#report_from_date').datepicker('hide'); // if youdon't hide datepicker will be kept open
			    var startDate = new Date($('#report_from_date').val());
				
			  });
			  
			  $('#report_to_date').change(
			    function(event) {
				$('#SelectedDate').text("Selected date: " + this.value);
				$('#report_to_date').datepicker('hide'); // if youdon't hide datepicker will be kept open
			    var startDate = new Date($('#report_from_date').val());
				//alert(startDate);
				var endDate = new Date($('#report_to_date').val());
				//alert(endDate);
				if (startDate <= endDate)
				   {
					   //  $('#error_date').text('Proceed');
						// $('#error_date').css('color', green);
						 $('.export').removeClass('disabled');
					  
					}
					else
					{
						 $('#display-error2').text('End Date is not less than Start Date');
						 $('#display-error2').css('color', red);
						 $('.export').addClass('disabled');
						 
					}
			  });
	       
		 
		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});	
		
		
		
		
		
		
	
		$('#view').click(function(){
			
			   var  error = "This value is required.";
			   
		 var regionid =  $('#regionselect option:selected').val();	
		// alert(regionid);	   
			
		 var resname =  $('#restaurant_id option:selected').val();	
		   // alert(resname);
		 var cusname =  $('#customer_id option:selected').val();	
			//alert(cusname);
		 var deltype =  $('#delivery_type').val();
			//alert(deltype);	
		 var delboy =  $('#deliveryboy_id option:selected').val();	
			//alert(delboy);
	     var status =  $('#status').val();	
		
		 var from_date =  $("#report_from_date").val();
		 if(from_date == ''){
		  $(".display-error1").html(error);
		 }else{
		  $(".display-error1").hide();	 
		 }
		
		var to_date =  $("#report_to_date").val();
		 if(to_date == ''){
		  $(".display-error2").html(error);
		 }else{
		  $(".display-error2").hide();	 
		 }
			
			if(from_date != '' && to_date != ''){
			$.ajax({
					url: '<?php echo url(); ?>/cashondeliveryorder/reportview',
					type: "POST",
					data: {regionid:regionid,resname:resname,cusname:cusname,deltype:deltype,delboy:delboy,from_date:from_date,to_date:to_date,status:status},
					success: function(data){
						//alert(data);
					 $('#content-loader').html(data);
					}
				});
				
			}
			
		});
		
	});
	</script>		 
@stop
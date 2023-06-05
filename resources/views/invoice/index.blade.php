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
      <div class="sbox animated fadeInRight">
      <div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
      <div class="sbox-content">
		{!! Form::open(array('url'=>'invoice/orderinvoice?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'orderinvoice','method'=>'POST')) !!}
			<div class="col-md-12">
                <fieldset>
                	<legend> Restaurant Order Invoice</legend>
                    
                    <div class="form-group ">
                        <label for="Id" class=" control-label col-md-4 text-left"> All </label>
                        <div class="col-md-6">
                         <input type="checkbox" name="res_all" id="res_all" class="form-control" value="1" />
                        </div>  
                        <div class="col-md-2">                            
                        </div>
                    </div>        
                    <div class="form-group  " >
                        <label for="Region" class=" control-label col-md-4 text-left"> Region </label>
                        <div class="col-md-6"> 
                            <select rows='9' name="region" id="regionselect" class="form-control regionselect">
                            <?php if(\Auth::user()->group_id == 1) {?>
                            <option value="all_region">All region</option>
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
                    <div class="form-group ">
                        <label for="Id" class=" control-label col-md-4 text-left"> Restaurant Name </label>
                        <div class="col-md-6">
                         <select name='res_id[]' multiple id="res_id" class='select2 ' >
                           <option value =''>Select Restaurant Name </option>
                            <?php foreach ($restaurants as $key => $value){ 
                                   echo '<option value="'.$value->id.'">'.$value->name.'</option>';
							} ?>
                        </select> 
                        <span class="display-error" style="color:red;"> </span>                          
                        </div>  
                        <div class="col-md-2">                            
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="Cust Id" class=" control-label col-md-4 text-left"> From Date </label>
                         <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                <input type="text" name="from_date" id="from_date" class="form-control" required='true' />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                            <span class="display-error1" style="color:red;"> </span>
                         </div> 
                         <div class="col-md-2">                            
                         </div>
                    </div> 					
                    <div class="form-group  " >
                        <label for="Res Id" class=" control-label col-md-4 text-left"> To Date </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                               	<input type="text" name="to_date" id="to_date" class="form-control" required='true' />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                            <span id="error_date" class="display-error2" style="color:red;"> </span>
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
                <span id="error_view" class="display-error3" style="color:red;"> </span>	
            	<div class="col-sm-1">	
            		<button type="submit" name="apply" class="btn btn-info btn-sm export" ><i class="fa  fa-check-circle"></i> Send</button>
            	</div>            
            </div> 
            
            <div id="invoice-content"></div>
            <br />
            <div id="instruction_div" style="display:none">
            	<span>Instruction:</span><br />
            	<textarea name="instruction" id="instruction" rows="4" cols="50" maxlength="500"></textarea>
            </div>
		 
		 {!! Form::close() !!}
       </div>
    </div>

	<!--<link href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>-->
    
    <script type="text/javascript">
   	$(document).ready(function() { 
	
		$('input').on('ifChecked', function(event){ 
			$('#res_id').attr('disabled','disabled');
			$('#view').css("display", "none");
		});
		
		$('input').on('ifUnchecked', function(event){ 
			$('#res_id').removeAttr('disabled','disabled');
			$('#view').css("display", "block");
		});
		
		
		$('#regionselect').change(function(){
			
			//$('#res_id').html("");
			$("#res_id").select2("val", "");			
			var regionselect = $(this).val();
			//alert(regionselect);			
			
			$.ajax({
				url: '<?php echo url(); ?>/invoice/regionselect',
				type: "post",
				data: {
					regionselect : regionselect
				},
				success: function(response) {
					//alert(response);				
					//var discount = response.split("@@");
					$('#res_id').html(response);					
				}
			});
			
		});
		
	
		$('#from_date').datepicker({
			onSelect: function(datesel) {
				$('#from_date').trigger('change')
		   	}
		});
		
		$('#to_date').datepicker({
			onSelect: function(datesel) {
				$('#to_date').trigger('change')
		   	}
		});
					
		$('#from_date').change(
			function(event) {
			$('#SelectedDate').text("Selected date: " + this.value);
			$('#from_date').datepicker('hide'); // if youdon't hide datepicker will be kept open
			var startDate = new Date($('#from_date').val());
			
		});
			  
		$('#to_date').change(function(event) {
			$('#SelectedDate').text("Selected date: " + this.value);
			$('#to_date').datepicker('hide'); // if youdon't hide datepicker will be kept open
			var startDate = new Date($('#from_date').val());
			var endDate = new Date($('#to_date').val());
			if (startDate <= endDate){
				$('#error_date').text('Proceed');
				$('#error_date').css('color', green);
				$('.export').removeClass('disabled');
			  
			} else {
				$('#error_date').text('End Date is not less than Start Date');
				$('#error_date').css('color', red);
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
			
		 	var resname =  $('#res_id').val();	
		    //alert(resname);
			if ($('#res_all').is(":checked"))
			{
				var res_all = $("#res_all").val();
			} else {
				var res_all = 0;
			}
			
			if(res_all == 0 && resname == null){
				$(".display-error").html(error);
			} else {
				$(".display-error").hide();
			}
			
		 	var from_date =  $("#from_date").val();
			if(from_date == ''){
				$(".display-error1").html(error);
			}else{
				$(".display-error1").hide();	 
			}
		
			var to_date =  $("#to_date").val();
			if(to_date == ''){
				$(".display-error2").html(error);
			}else{
				$(".display-error2").hide();	 
			}
			
			if((resname.length)>=2){
				$(".display-error3").html('Multi restaurants view option is not allowed');				
			} else {
				if(from_date != '' && to_date != ''){
					$(".display-error3").hide();
					$.ajax({
						url: '<?php echo url(); ?>/invoice/invoiceview',
						type: "POST",
						data: {res_all:res_all,res_id:resname,from_date:from_date,to_date:to_date},
						success: function(data){
							//alert(data);
							$('#invoice-content').html(data);
							if(data !=''){
								$("#instruction_div").show();
							}
						}
					});
				}
			}
			
		});
		
	});
	</script>
@stop
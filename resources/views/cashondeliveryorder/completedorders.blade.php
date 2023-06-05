@extends('layouts.app')

@section('content')
  
  

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Completed Orders </h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">Completed Orders</li>
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

		 {!! Form::open(array('url'=>'cashondeliveryorder/completedordersview?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset>
                        <legend> Completed Orders</legend>
                        <!--legend> {!! trans('core.abs_deliveryboy_orders') !!}</legend-->
									
								  <div class="form-group  " >
									<label for="Coupon Name" class=" control-label col-md-4 text-left">Order Id <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  <input class="form-control" id="order_id" name="order_id" type="text" required="required">
                                      <span class="display-error2" style="color:red;"> </span> 
									 </div>
                                        
									 <div class="col-md-2">
									 	
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
		
		$('#view').click(function(){
			var  error = "This value is required.";
			
		
			
		 	var id =  $("#order_id").val();
		 	if(id == ''){
		  		$(".display-error2").html(error);
			}else{
			  	$(".display-error2").hide();	 
			}
			
			if(id != ''){
				$.ajax({
					url: '<?php echo url(); ?>/cashondeliveryorder/completedordersview',
					type: "POST",
					data: {id:id},
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
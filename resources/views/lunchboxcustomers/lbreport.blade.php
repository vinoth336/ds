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

		 {!! Form::open(array('url'=>'lunchboxcustomers/reportcustomers?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
			<fieldset>
                <legend> Lunchbox Report</legend>
                <!--legend> {!! trans('core.abs_deliveryboy_orders') !!}</legend-->
                            
                <div class="form-group  " >
                    <label for="Id" class=" control-label col-md-4 text-left">Select Zone</label>
                        <div class="col-md-6">
                         <select name='zone_id' id='zone_id' class='select2 ' >
                           <option value =''>Select Zone</option>
                            <?php foreach ($zones as $key => $value)
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
                    <label for="Id" class=" control-label col-md-4 text-left">Pickup / No Pickup</label>
                        <div class="col-md-6">                        
                        <select name='no_pickup' id='no_pickup' class='select2 '>
                            <option value ='pickup'>Pickup</option>
                            <option value ='no-pickup'>No Pickup</option>
                        </select>                      
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
	
	   	$('#report_from_date').datepicker({
			onSelect: function(datesel) {
				$('#report_from_date').trigger('change')
		   	}
		});		
		
		$('#view').click(function(){
			
			var  error = "This value is required.";
			
			var zone =  $('#zone_id option:selected').val();
			var no_pickup =  $('#no_pickup option:selected').val();
		
			$.ajax({
				url: '<?php echo url(); ?>/lunchboxcustomers/lunchboxreport',
				type: "POST",
				data: { zone:zone, no_pickup:no_pickup },
				success: function(data){
					//alert(data);
					$('#content-loader').html(data);
				}
			});
			
		});
		
	});
	</script>		 
@stop
@extends('layouts.app')

@section('content')

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.js"></script>

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('banners?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{!! Lang::get('core.addedit') !!} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<!--<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>-->
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'banners/save?return='.$return, 'class'=>'form-horizontal','id'=>'formbanner','files' => true ,'novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Banners</legend>
                        
                        
                        <?php //print_r($results); 
							
								$value = \DB::table('banners')->select('*')->where('id','=',$id)->get();
									//print_r($value); 
							    $value1 = $value[0];
							    $res_id = $value1->res_id;
							 ?>
                             
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  
                          
                              
                              
                              
                              <div class="form-group  " > 
								<label for="status" class="control-label col-md-4 text-left"> Status  <span class="asterix"> * </span> </label>
								<div class="col-md-8">
									<label class=''>
										<input type="radio" name="status" value="1" class="test" @if($row['status'] == '1') checked="checked" @endif >  Clickable 
									</label>
									<label class='' style="margin-left:34px;">
										<input type="radio" name="status" value="0" class="test" @if($row['status'] == '0') checked="checked" @endif >   Non Clickable
									</label> 
								</div> 
								<div class="col-md-2">
								</div>
							</div>
                                  
                                  
                                  <!--<span class="clickable">-->
								  <div class="form-group  " >
									<label for="Res Id" class="control-label col-md-4 text-left"> Restaurant Name <span class="asterix1"> * </span></label>
									<div class="col-md-6">
                                    
									  <select name='res_id' rows='5' id='res_id' class='select2'>
                                     <option value="">Please Select</option>
                                      	<?php foreach($res as $rest){ ?>
											<option value="<?php echo $rest->id; ?>" <?php if($rest->id == $row['res_id']){ echo 'selected="selected"'; } ?>><?php echo $rest->name; ?></option>
										<?php } ?>
                                      </select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
                                  	<label for="Res Offer" class=" control-label col-md-4 text-left"> Restaurant Offer </label>
                                    <div class="col-md-8 offers"></div>
                                  </div> 
                                  <div class="form-group  " >
                                  	<label for="Res Offer" class=" control-label col-md-4 text-left"> Restaurant Coupons </label>                                    <div class="col-md-8 coupons"></div>
                                  </div> 
                                  <!--</span>	-->				
								  <div class="form-group  " >
									<label for="Banner Image" class=" control-label col-md-4 text-left"> Banner Image </label>
									<div class="col-md-6">
									  	<input  type='file' name='banner_image' id='banner_image' style='width:150px !important;'  />
                                        <div >
                                        {!! SiteHelpers::showUploadedFile($row['banner_image'],'/uploads/banners/') !!}
                                        
                                        </div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                              
                                 <div class="form-group  " >
                                    <label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name="region" rows='5' id='region' class="form-control regionval" selected>
                                                                          </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div> 
                                    <!--<span class="nonclickable">-->
                                 <div class="form-group  " >
									<label for="Available From" class=" control-label col-md-4 text-left"> From date <span class="asterix2"> * </span></label>
									<div class="col-md-6">
                                        <div class="input-group m-b" style="width:200px !important;">
                                            <input class="form-control datetime hasDatepicker" id="from_date" name="from_date"  type="text" autocomplete="off" value="<?php echo $row['from_date'] ?>">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Available To" class=" control-label col-md-4 text-left"> To date <span class="asterix3"> * </span> </label>
									<div class="col-md-6">
									<div class="input-group m-b" style="width:200px !important;">
                                        <input class="form-control datetime hasDatepicker" id="to_date" name="to_date" type="text" autocomplete="off" value="<?php echo $row['to_date'] ?>">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>  
                                  
                               <div class="form-group"> 
								<label for="Item Visible Days" class=" control-label col-md-4 text-left"> Days </label>
								<div class="col-md-6">
                                	<?php $available_days = explode(',',$row->available_days);?>
                                	<label class='checkbox checkbox-inline'>
										<input type='checkbox' id='all_days' name='all_days' class="checkAll" @if(count($available_days) == 7) checked="checked" @endif > All Days
									</label><br />
									<label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='1' class="checkSingle" @if(in_array('1', $available_days)) checked="checked" @endif > Mon 
									</label>
									<label class='checkbox checkbox-inline' style="margin-left:34px;">
										<input type='checkbox' name='available_days[]' value ='2' class="checkSingle" @if(in_array('2', $available_days)) checked="checked" @endif > Tue 
									</label>
                                    <label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='3' class="checkSingle" @if(in_array('3', $available_days)) checked="checked" @endif > Wed 
									</label>
									<label class='checkbox checkbox-inline' style="margin-left:34px;">
										<input type='checkbox' name='available_days[]' value ='4' class="checkSingle" @if(in_array('4', $available_days)) checked="checked" @endif > Thu 
									</label>
                                    <label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='5' class="checkSingle" @if(in_array('5', $available_days)) checked="checked" @endif > Fri 
									</label>
									<label class='checkbox checkbox-inline' style="margin-left:34px;">
										<input type='checkbox' name='available_days[]' value ='6' class="checkSingle" @if(in_array('6', $available_days)) checked="checked" @endif > Sat 
									</label>
                                    <label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='7' class="checkSingle" @if(in_array('7', $available_days)) checked="checked" @endif > Sun 
									</label>
								</div> 
								<div class="col-md-2"></div>
							</div>   
                                  
                           <!--  </span> -->    
                                  
                                  
                                  
                                  
                                  </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('banners?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
   
$("#formbanner").validate();
$("#submit").click(function() {
$("#formbanner").valid();

});
	
   
	$(document).ready(function() {
	
	<!--for common required-->
	$('.test').attr('required', 'required');
	$('#region').attr('required', 'required'); 
    $('#from_date').attr('required', 'required');
    $('#to_date').attr('required', 'required');

  	<!--for update required-->
<?php if($id) {  ?>
	   var value = $( 'input[name=status]:checked' ).val();
	if(value == "1"){
		
	    $(".asterix1").show();
	  //  $(".asterix2").hide();
	 //   $(".asterix3").hide();	
	 $("#res_id").attr('required', true);	
	// $('#from_date').removeAttr('required');
	// $('#to_date').removeAttr('required');
	  
	
	 
 }else {
	 
	   $(".asterix1").hide();
	 //  $(".asterix2").show();
	 //  $(".asterix3").show();
	$('#res_id').removeAttr('required');
	//$('#from_date').attr('required', 'required');
	//$('#to_date').attr('required', 'required');

	 
 }
<?php }  ?>

	<!--for create required start-->
$(".test").on('ifChanged', function() {
   var val = $("input[name='status']:checked").val(); 	
 if(val == "1"){
	    $(".asterix1").show();
	  
	 $("#res_id").attr('required', true);
	
	
 }else {
	   $(".asterix1").hide();
	  
	
	 $("#res_id").removeAttr('required');
	
 }
});
	<!--for create required end-->
   
		
		// add multiple select / deselect functionality
		$("#all_days").on('ifClicked', function(event) {
			 var success = event.target.checked;
			 //alert(success);
			 if(success == true){
				$('.checkSingle').iCheck('uncheck');
			 } else {
				$('.checkSingle').iCheck('check');
			 }
		});
		// if all checkbox are selected, check the selectall checkbox and viceversa
		$(".checkSingle").on('ifChanged', function() {
			if(($(".checkSingle").length) == $(".checkSingle:checked").length) {
				$("#all_days").iCheck('check');
			} else {
				$("#all_days").iCheck("uncheck");
			}	
		});
		
		
		/*$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! $row["region"] !!}' });*/
				
		   <?php if(session()->get('gid') == '7'){ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! session()->get('rid') !!}' });
		    $('#region').attr('readonly', true);
			 <?php	}else{ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! $row["region"] !!}' });		
			 <?php	} ?>
			 
		
		
		
		/*$("#res_id").jCombo("{{ URL::to('banners/comboselect?filter=abserve_restaurants:id:name') }}",
		{  selected_value : '{{ $row["res_id"] }}' });*/
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
		
		
		
		$('#res_id').on('click',function(){
			
			var res_id = $(this).val();
			//alert(res_id);
			//if(res_id !=''){
			$.ajax({
				url: '<?php echo url(); ?>/banners/offerdetails',
				type: "post",
				data: {
					res_id : res_id
				},
				success: function(response) {
					//alert(response);
					var discount = response.split("@@");
					$('.offers').html(discount[0]);
					$('.coupons').html(discount[1]);
					$('.regionval').html(discount[2]);
					
				}
			});
			//	}
		});
		
	});
	
	
	
	
	$( window ).on( "load", function() {
		var res_id = $('#res_id').val();
		if(res_id !=''){
			$.ajax({
				url: '<?php echo url(); ?>/banners/offerdetails',
				type: "post",
				data: {
					res_id : res_id
				},
				success: function(response) {
					var discount = response.split("@@");
					$('.offers').html(discount[0]);
					$('.coupons').html(discount[1]);
					$('.regionval').html(discount[2]);
				}
			});
		}
		
		
	});


	
	</script>
    <style>
.error {
 
    color: #cc0000;
    
}

</style>
    
@stop
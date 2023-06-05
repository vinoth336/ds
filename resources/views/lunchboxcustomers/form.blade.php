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
		<li><a href="{{ URL::to('lunchboxcustomers?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'lunchboxcustomers/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> lunchboxcustomers</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Lunch Box Cust Id" class=" control-label col-md-4 text-left"> Lunch Box Cust Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="User Id" class=" control-label col-md-4 text-left"> User Id </label>
									<div class="col-md-6">
									  {!! Form::text('user_id', $row['user_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="First Name" class=" control-label col-md-4 text-left"> First Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> Email <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Primary Number" class=" control-label col-md-4 text-left"> Primary Number <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('primary_number', $row['primary_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Secondary Number" class=" control-label col-md-4 text-left"> Secondary Number <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('secondary_number', $row['secondary_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>					
								  <div class="form-group  " >
									<label for="Region" class=" control-label col-md-4 text-left"> Region </label>
									<div class="col-md-6">
									  <select name='region' rows='5' id='region' class='select2 '   ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>					
								  <div class="form-group  " >
									<label for="Highlight Colour" class=" control-label col-md-4 text-left"> Highlight Colour </label>
									<div class="col-md-6">
									  <select name='phone_change_status' rows='5' id='phone_change_status' class='select2 '>
                                      	<?php if($row['phone_change_status'] == 0){
											echo $selected = "selected='selected'";
										} else {
											echo $selected = "";
										}?>
                                      	<option value="1" <?php echo $selected; ?>>ON</option>
                                        <option value="0" <?php echo $selected; ?>>OFF</option>
                                      </select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Active" class=" control-label col-md-4 text-left"> Active <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
									<?php $active = explode(',',$row['active']);
                                    $active_opt = array( '1' => 'Active' ,  '0' => 'Inactive' , ); ?>
                                    <select name='active' rows='5' required  class='select2 '  > 
                                        <?php 
                                        foreach($active_opt as $key=>$val)
                                        {
                                            echo "<option  value ='$key' ".($row['active'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
                                        }						
                                        ?>
                                     </select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>  </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<!--<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>-->
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('lunchboxcustomers?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#region").jCombo("{{ URL::to('lunchboxcustomers/comboselect?filter=region:id:region_name') }}",
		{  selected_value : '{{ $row["region"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
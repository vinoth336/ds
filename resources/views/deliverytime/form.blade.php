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
		<li><a href="{{ URL::to('deliverytime?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'deliverytime/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Delivery Time</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Start Km" class=" control-label col-md-4 text-left"> Start Km <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
					<?php $start_km = explode(',',$row['start_km']);
					$start_km_opt = array( '0' => '0' , '1' => '1' , '2' => '2' , '3' => '3' , '4' => '4' , '5' => '5' , '6' => '6' , '7' => '7' , '8' => '8' , '9' => '9' , '10' => '10' , '11' => '11' , '12' => '12' , '13' => '13' , '14' => '14' , '15' => '15' , '16' => '16' , '17' => '17' , '18' => '18' , '19' => '19' , '20' => '20' , '21' => '21' , '22' => '22' , '23' => '23' , '24' => '24' , '25' => '25' , ); ?>
					<select name='start_km' rows='5' required  class='select2 '  > 
						<?php 
						foreach($start_km_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['start_km'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="End Km" class=" control-label col-md-4 text-left"> End Km <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
					<?php $end_km = explode(',',$row['end_km']);
					$end_km_opt = array( '0' => '0' , '1' => '1' , '2' => '2' , '3' => '3' , '4' => '4' , '5' => '5' , '6' => '6' , '7' => '7' , '8' => '8' , '9' => '9' , '10' => '10' , '11' => '11' , '12' => '12' , '13' => '13' , '14' => '14' , '15' => '15' , '16' => '16' , '17' => '17' , '18' => '18' , '19' => '19' , '20' => '20' , '21' => '21' , '22' => '22' , '23' => '23' , '24' => '24' , '25' => '25' , ); ?>
					<select name='end_km' rows='5' required  class='select2 '  > 
						<?php 
						foreach($end_km_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['end_km'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Mins" class=" control-label col-md-4 text-left"> Mins <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('mins', $row['mins'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
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
                                 </div> </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('deliverytime?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
			$("#region").jCombo(base_url+"deliverytime/comboselect?filter=region:id:region_name",
			{  selected_value : '{!! session()->get('rid') !!}' });
			$('#region').attr('readonly', true);
		<?php } else { ?>
			$("#region").jCombo(base_url+"deliverytime/comboselect?filter=region:id:region_name",
			{  selected_value : '{!! $row["region"] !!}' });		
		<?php } ?>

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
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
		<li><a href="{{ URL::to('area?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'area/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Area</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Region Id" class=" control-label col-md-4 text-left"> Region Id </label>
									<div class="col-md-6">
									  {!! Form::text('region_id', $row['region_id'],array('class'=>'form-control', 'placeholder'=>''  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Region Name" class=" control-label col-md-4 text-left"> Region Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  <select name='region_name' rows='5' id='region_name' class='select2 ' required  ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>  					
								  <div class="form-group  " >
									<label for="Region Keyword" class=" control-label col-md-4 text-left"> Region Keyword <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('region_keyword', $row['region_keyword'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true','id'=>'region_keyword'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>					
								  <div class="form-group  " >
									<label for="Distance" class=" control-label col-md-4 text-left"> Distance <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('distance', $row['distance'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true','id'=>'distance'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Location" class=" control-label col-md-4 text-left"> Location </label>
									<div class="col-md-6">
									  {!! Form::text('location', $row['location'],array('class'=>'form-control', 'placeholder'=>'','id'=>'location'   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Pin Code" class=" control-label col-md-4 text-left"> Pin Code <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('pin_code', $row['pin_code'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true','id'=>'pin_code'  )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('area?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
   
   $(function() {
    $("#region_name").change(function() {
        var region_name= $('option:selected', this).text(); 
		//alert(region_name);
		
		    $.ajax({
					url: '<?php echo url(); ?>/area/region',
					type: "POST",
					data: {region_name:region_name},
					success: function(response){
						var data = response.split("@@");
					 $('#distance').val(data[0]);
					 $('#region_keyword').val(data[1]);
					}
				});
		
    });
   });
	$(document).ready(function() { 
		
		
		$("#region_name").jCombo("{{ URL::to('area/comboselect?filter=region:region_name:region_name') }}",
		{  selected_value : '{{ $row["region_name"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
		
		
		
		
	});
	</script>		 
@stop
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
		<li><a href="{{ URL::to('servicebanners?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'servicebanners/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Servicebanners</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  
                                  <div class="form-group  " >
                                    <label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name="region" rows='5' id='region' class="form-control regionval" selected required>
                                                                          </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>
                                  
                                  					
								  <div class="form-group  " >
									<label for="Banner Image" class=" control-label col-md-4 text-left"> Banner Image </label>
									<div class="col-md-6">
									  <input  type='file' name='banner_image' id='banner_image' required @if($row['banner_image'] =='') class='required'  @endif style='width:150px !important;'/>
					 	<div >
						{!! SiteHelpers::showUploadedFile($row['banner_image'],'/uploads/servicebanners/') !!}
						
						</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="Status" class=" control-label col-md-4 text-left"> Status <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
										<?php $Status = explode(',',$row['status']);
										$Status_opt = array( '1' => 'Active' ,  '0' => 'Inactive' , ); ?>
										<select name='status' rows='5' required  class='select2 '  > 
											<?php 
                                            foreach($Status_opt as $key=>$val)
                                            {
                                                echo "<option  value ='$key' ".($row['status'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
                                            }						
                                            ?>
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
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('servicebanners?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! session()->get('rid') !!}' });
		    $('#region').attr('readonly', true);
			 <?php	}else{ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! $row["region"] !!}' });		
			 <?php	} ?>

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
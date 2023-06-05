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
		<li><a href="{{ URL::to('deliveryboy?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{!! Lang::get('core.addedit') !!} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'deliveryboy/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> {!! trans('core.abs_Delivery_boys') !!}</legend>
								<ul class="parsley-error-list">
								@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
								@endforeach
								</ul>
									{!! Form::hidden('partner_id', 0,array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
								  <div class="form-group hidethis " style="display:none;">
									<label for="Boy Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_boy_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Username" class=" control-label col-md-4 text-left"> {!! trans('core.username') !!} <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('username', $row['username'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> {!! trans('core.abs_email') !!} <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Phone Number" class=" control-label col-md-4 text-left"> {!! trans('core.phone_number') !!} <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('phone_number', $row['phone_number'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'', 'required'=>'true','min'=>0, 'parsley-type'=>'number'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <!--<div class="form-group  " >
									<label for="Phone Verified" class=" control-label col-md-4 text-left"> {!! trans('core.abs_Phone_verified') !!} </label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='phone_verified' value ='1'  @if($row['phone_verified'] == '1') checked="checked" @endif > {!! trans('core.abs_yes') !!} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='phone_verified' value ='0'  @if($row['phone_verified'] == '0') checked="checked" @endif > {!! trans('core.abs_no') !!} </label> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>--> 					
								  <div class="form-group  " >
									<label for="Avatar" class=" control-label col-md-4 text-left"> {!! trans('core.abs_avatar') !!} </label>
									<div class="col-md-6">
									  <input  type='file' name='avatar' id='avatar' @if($row['avatar'] =='') class='required' @endif style='width:150px !important;'  />
                                      <div >{!! SiteHelpers::showUploadedFile($row['avatar'],'/uploads/delivery_boy/') !!}</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="Aadhar Card" class=" control-label col-md-4 text-left"> Aadhar Card </label>
									<div class="col-md-6">
									  <input  type='file' name='aadhar_card' id='aadhar_card' @if($row['aadhar_card'] =='') class='required' @endif style='width:150px !important;'  />
                                      <div >{!! SiteHelpers::showUploadedFile($row['aadhar_card'],'/uploads/delivery_boy/') !!}</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="Driving Licence" class=" control-label col-md-4 text-left"> Driving Licence </label>
									<div class="col-md-6">
									  <input  type='file' name='driving_licence' id='driving_licence' @if($row['driving_licence'] =='') class='required' @endif style='width:150px !important;'  />
                                      <div >{!! SiteHelpers::showUploadedFile($row['driving_licence'],'/uploads/delivery_boy/') !!}</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="Insurance" class=" control-label col-md-4 text-left"> Insurance </label>
									<div class="col-md-6">
									  <input  type='file' name='insurance' id='insurance' @if($row['insurance'] =='') class='required' @endif style='width:150px !important;'  />
                                      <div >{!! SiteHelpers::showUploadedFile($row['insurance'],'/uploads/delivery_boy/') !!}</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="RC Book" class=" control-label col-md-4 text-left"> RC Book </label>
									<div class="col-md-6">
									  <input  type='file' name='rc_book' id='rc_book' @if($row['rc_book'] =='') class='required' @endif style='width:150px !important;'  />
                                      <div >{!! SiteHelpers::showUploadedFile($row['rc_book'],'/uploads/delivery_boy/') !!}</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="Agreement" class=" control-label col-md-4 text-left"> Agreement </label>
									<div class="col-md-6">
									  <input  type='file' name='agreement' id='agreement' style='width:150px !important;' disabled="disabled"  />
                                      <div >{!! SiteHelpers::showUploadedFile($row['agreement'],'/uploads/delivery_boy/') !!}</div>					
					 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
								  <div class="form-group  " >
									<label for="Active" class=" control-label col-md-4 text-left"> {!! trans('core.abs_active') !!} </label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='1'  @if($row['active'] == '1') checked="checked" @endif > {!! trans('core.abs_active') !!} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='0'  @if($row['active'] == '0') checked="checked" @endif > {!! trans('core.fr_minactive') !!} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='2'  @if($row['active'] == '2') checked="checked" @endif > {!! trans('core.abs_block') !!} </label> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  <div class="form-group  " >
									<label for="Active" class=" control-label col-md-4 text-left"> Online Status </label>
									<div class="col-md-6">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='online_sts' value ='1'  @if($row['online_sts'] == '1') checked="checked" @endif > {!! trans('core.abs_active') !!} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='online_sts' value ='0'  @if($row['online_sts'] == '0') checked="checked" @endif > {!! trans('core.fr_minactive') !!} </label>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  
                                  
                                  
                         <div class="form-group  " >
					<label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
					<div class="col-md-6"> 
						<select name='region' rows='9' id='region' class='select2' required>
                        </select>
					</div> 
					<div class="col-md-2"></div>
	                    </div>
                                  
                                  
								<div class="form-group">
									
									<label for="ipt" class=" control-label col-md-4 text-left" > </label>
									<div class="col-md-8">
										@if($row['id'] !='')
											{!! Lang::get('core.notepassword') !!}
										@else
											Create Password
										@endif	 
									</div>
								</div>	
								<div class="form-group">
									<label for="ipt" class=" control-label col-md-4"> {!! Lang::get('core.newpassword') !!} </label>
									<div class="col-md-8">
									<input name="password" type="password" id="password" class="form-control input-sm" value=""
									@if($row['id'] =='')
										required
									@endif
									/> 
									 </div> 
								</div>  
								<div class="form-group">
									<label for="ipt" class=" control-label col-md-4"> {!! Lang::get('core.conewpassword') !!} </label>
									<div class="col-md-8">
									<input name="password_confirmation" type="password" id="password_confirmation" class="form-control input-sm" value=""
									@if($row['id'] =='')
										required
									@endif		
									 />  
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
					<button type="button" onclick="location.href='{{ URL::to('deliveryboy?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
					  
			<?php }else{ ?>
			
					   $("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	       {  selected_value : '{!! $row["region"] !!}' });		
			
			<?php	} ?>




		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});	
		 $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });	
		
	});
	</script>		 
@stop
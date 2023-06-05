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
		<li><a href="{{ URL::to('commission?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'commission/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> {!! trans('core.abs_commission') !!}</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Group Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_group_id') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('group_id', $row['group_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Username" class=" control-label col-md-4 text-left"> {!! trans('core.abs_username') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('username', $row['username'],array('class'=>'form-control', 'placeholder'=>'', readonly  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Password" class=" control-label col-md-4 text-left">{!! trans('core.abs_password') !!}</label>
									<div class="col-md-6">
									  {!! Form::text('password', $row['password'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> {!! trans('core.abs_email') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'',readonly   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="First Name" class=" control-label col-md-4 text-left"> {!! trans('core.abs_first_name') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Last Name" class=" control-label col-md-4 text-left"> {!! trans('core.abs_last_name') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('last_name', $row['last_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Avatar" class=" control-label col-md-4 text-left"> {!! trans('core.abs_avatar') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('avatar', $row['avatar'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Active" class=" control-label col-md-4 text-left"> {!! trans('core.abs_active') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('active', $row['active'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Login Attempt" class=" control-label col-md-4 text-left">{!! trans('core.abs_login_attempt') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('login_attempt', $row['login_attempt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Last Login" class=" control-label col-md-4 text-left"> {!! trans('core.abs_last_login') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('last_login', $row['last_login'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Created At" class=" control-label col-md-4 text-left"> {!! trans('core.abs_created_at') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('created_at', $row['created_at'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Updated At" class=" control-label col-md-4 text-left"> {!! trans('core.abs_updated_at') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('updated_at', $row['updated_at'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Reminder" class=" control-label col-md-4 text-left"> {!! trans('core.abs_reminder') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('reminder', $row['reminder'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Activation" class=" control-label col-md-4 text-left"> {!! trans('core.abs_activation') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('activation', $row['activation'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Remember Token" class=" control-label col-md-4 text-left"> {!! trans('core.abs_remember_token') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('remember_token', $row['remember_token'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Last Activity" class=" control-label col-md-4 text-left"> {!! trans('core.abs_last_activity') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('last_activity', $row['last_activity'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Phone Number" class=" control-label col-md-4 text-left"> {!! trans('core.abs_phone_number') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('phone_number', $row['phone_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Phone Verified" class=" control-label col-md-4 text-left"> {!! trans('core.abs_Phone_verified') !!}  </label>
									<div class="col-md-6">
									  {!! Form::text('phone_verified', $row['phone_verified'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Address" class=" control-label col-md-4 text-left"> {!! trans('core.abs_Phone_verified') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('address', $row['address'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="City" class=" control-label col-md-4 text-left"> {!! trans('core.abs_city') !!}</label>
									<div class="col-md-6">
									  {!! Form::text('city', $row['city'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="State" class=" control-label col-md-4 text-left"> {!! trans('core.abs_state') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('state', $row['state'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Zip Code" class=" control-label col-md-4 text-left"> {!! trans('core.abs_zipcode') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('zip_code', $row['zip_code'],array('class'=>'form-control', 'placeholder'=>'', readonly  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Country" class=" control-label col-md-4 text-left"> {!! trans('core.abs_zipcode') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('country', $row['country'],array('class'=>'form-control', 'placeholder'=>'', readonly  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Mobile Token" class=" control-label col-md-4 text-left"> {!! trans('core.abs_zipcode') !!} </label>
									<div class="col-md-6">
									  {!! Form::text('mobile_token', $row['mobile_token'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Commission" class=" control-label col-md-4 text-left"> {!! trans('core.abs_commission') !!}(%) </label>
									<div class="col-md-6">
									  {!! Form::number('commission', $row['commission'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'','min'=>0   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('commission?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
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
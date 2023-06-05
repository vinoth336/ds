@extends('layouts.app')
@section('content')
<div class="page-content row">
	<!-- Page header -->
	<div class="page-header">
		<div class="page-title">
			<h3> {!! trans('core.abs_acnt_view_info') !!}</h3>
		</div>
		<ul class="breadcrumb">
			<li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
			<li class="active">{!! trans('core.account') !!}</li>
		</ul>
	</div>  
	<div class="page-content-wrapper m-t">
		@if(Session::has('message'))	  
		{!! Session::get('message') !!}
		@endif	
		<ul>
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>	
		<ul class="nav nav-tabs" >
			<li class="@if($section == '') active @endif"><a href="{{ URL::to('user/profile') }}"><!-- <a href="#info" data-toggle="tab"> --> {!! Lang::get('core.personalinfo') !!} </a></li>
			<li class="@if($section == 'pass') active @endif"><a href="{{ URL::to('user/profile?section=pass') }}"><!-- <a href="#pass" data-toggle="tab"> -->{!! Lang::get('core.changepassword') !!} </a></li>
			<?php if(\Auth::user()->group_id == 3) { ?>
			<li class="@if($section == 'acnt') active @endif"><a href="{{ URL::to('user/acntdetails?section=acnt') }}" >{!! trans('core.abs_acnt_details') !!}</a></li>
			<?php } ?>
		</ul>		
		<div class="tab-content">
		@if($section == '')
			<div class="" id="">
				{!! Form::open(array('url'=>'user/saveprofile/', 'class'=>'form-horizontal ' ,'files' => true,'enctype'=>"multipart/form-data")) !!}  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4"> {!! trans('core.username') !!} </label>
					<div class="col-md-8">
						<input name="username" type="text" id="username" disabled="disabled" class="form-control input-sm" required  value="{{ $info->username }}" />  
					</div> 
				</div>  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.email') !!} </label>
					<div class="col-md-8">
						<input name="email" type="text" id="email"  class="form-control input-sm" value="{{ $info->email }}" /> 
					</div> 
				</div> 	  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.firstname') !!} </label>
					<div class="col-md-8">
						<input name="first_name" type="text" id="first_name" class="form-control input-sm" required value="{{ $info->first_name }}" /> 
					</div> 
				</div>  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.lastname') !!} </label>
					<div class="col-md-8">
						<input name="last_name" type="text" id="last_name" class="form-control input-sm" required value="{{ $info->last_name }}" />  
					</div> 
				</div>  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.phone_number') !!} </label>
					<div class="col-md-8">
						<input name="phone_number" type="text" id="phone_number" class="form-control input-sm allownumericwithoutdecimal" required value="{{ $info->phone_number }}" />  
					</div> 
				</div>   
				<div class="form-group  " >
					<label for="ipt" class=" control-label col-md-4 text-right"> {!! trans('core.abs_avatar') !!} </label>
					<div class="col-md-8">
						<div class="fileinput fileinput-new" data-provides="fileinput">
							<span class="btn btn-primary btn-file">
								<span class="fileinput-new">{!! trans('core.abs_upload_avatar_image') !!}</span><span class="fileinput-exists">{!! trans('core.Abs_change') !!}</span>
								<input tabindex="0" type="file" name="avatar" accept="image/*"  capture="camera">
							</span>
							<span class="fileinput-filename"></span>
							<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
						</div>
						<br />{!! trans('core.abs_img_dime_8080') !!} <br />
						{!! SiteHelpers::avatar() !!}
					</div> 
				</div>  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
					<div class="col-md-8">
						<button class="btn btn-success" type="submit"> {!! Lang::get('core.sb_savechanges') !!}</button>
					</div> 
				</div> 	
				{!! Form::close() !!}	
			</div>
		@elseif($section == 'pass')
			<div class="" id="">
				{!! Form::open(array('url'=>'user/savepassword/', 'class'=>'form-horizontal ')) !!}    
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4"> {!! Lang::get('core.newpassword') !!} </label>
					<div class="col-md-8">
						<input name="password" type="password" id="password" class="form-control input-sm" value="" /> 
					</div> 
				</div>  
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4"> {!! Lang::get('core.conewpassword') !!}  </label>
					<div class="col-md-8">
						<input name="password_confirmation" type="password" id="password_confirmation" class="form-control input-sm" value="" />  
					</div> 
				</div>
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
					<div class="col-md-8">
						<button class="btn btn-danger" type="submit"> {!! Lang::get('core.sb_savechanges') !!} </button>
					</div> 
				</div>   
				{!! Form::close() !!}	
			</div>
		@endif
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
		$(this).val($(this).val().replace(/[^\d].+/, ""));
		if(event.which == 8){

		} else if((event.which < 48 || event.which > 57 )) {
			event.preventDefault();
		}
	});
</script>
@endsection

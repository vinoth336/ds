@extends('layouts.login')

@section('content')
<div class="page-header" style="background-image: url(../../abserve/foodstar/img/banner1.jpg);">
  <div class="container">
    <div class="row">         
      <div class="col-md-12">
        <div class="breadcrumb-wrapper">
          <h2 class="page-title">{!! trans('core.abs_reset_pass') !!}</h2>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Login wrapper -->
<section id="content">
	<div class="container">
		<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
			<div class="page-login-form box">
				{!! Form::open(array('url' => 'user/doreset/'.$verCode, 'class'=>'form-vertical')) !!}
			    	@if(Session::has('message'))
						{!! Session::get('message') !!}
					@endif
					<div class="form-group has-feedback">
						<ul class="parsley-error-list">
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>			
					</div>			
					<div class="form-group has-feedback">
						<label>{!! trans('core.newpassword') !!} </label>
						{!! Form::password('password',  array('class'=>'form-control', 'placeholder'=>'New Password')) !!}
						<i class="icon-lock form-control-feedfront"></i>
					</div>
					<div class="form-group has-feedback">
						<label>{!! trans('core.Abs_retype_pass') !!}</label>
					   {!! Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Confirm Password')) !!}
						<i class="icon-lock form-control-feedfront"></i>
					</div>
				  	<div class="form-group has-feedback">
						  <button type="submit" class="btn btn-common log-btn">{!! trans('core.abs_reset_pass') !!}</button>
				  	</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</section> 
<!-- /login wrapper -->

@endsection
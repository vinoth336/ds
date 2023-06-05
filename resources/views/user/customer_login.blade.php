@extends('layouts.login')

@section('content')

  <link rel="stylesheet" href="http://localhost/food/food_app_back/abserve/foodstar/css/main.css">
 
    <div class="page-header" style="background: url(../abserve/foodstar/img/bg/bg-banner.jpg);">
      <div class="container">
        <div class="row">         
          <div class="col-md-12">
            <div class="breadcrumb-wrapper">
              <h2 class="page-title">{!! trans('core.abs_login_acnt') !!}</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <!-- Page Header End --> 
 <!-- Content section Start --> 
    <section id="content">
      <div class="container">
      <div class="wrapper">
         <!-- TOP AREA -->
          <div style="min-height:400px;">
            	    <!-- Content section End --> 
			<div class="row">

				@if(Session::has('message'))
						{!! Session::get('message') !!}
					@endif
				<ul class="parsley-error-list">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>	
				<div class="tab-content" >
					<div class="tab-pane active m-t" id="tab-sign-in">
					  <div class="col-sm-6 col-sm-offset-4 col-md-4 col-md-offset-4">
						<div class="page-login-form box">
					      <h3>
					        {!! trans('core.abs_login') !!}
					      </h3>
					      <form method="post" action="{{ url('user/customersignin')}}" class="form-vertical">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group">
					          <div class="input-icon">
					            <i class="icon fa fa-user"></i>
					            <input type="text" name="email" placeholder="Phone Number" class="form-control" required="" />
					          </div>
					        </div> 
					        <div class="form-group">
					          <div class="input-icon">
					            <i class="icon fa fa-unlock-alt"></i>
					            <input type="password" name="password" placeholder="Password" class="form-control" required="true" />
					          </div>
					        </div>                  
					        <div class="checkbox">
					        <input type="checkbox" id="remember" name="rememberme" value="1" style="float: left;">
					          <label for="remember">{!! trans('core.abs_rem_me') !!}</label>
					        </div>
					        @if(CNF_RECAPTCHA =='true') 
							<div class="form-group has-feedback">
								<label class="text-left">{!! trans('core.abs_are_u_human') !!} </label>	
								<br />
								{!! captcha_img() !!}
								<input type="text" name="captcha" placeholder="Type Security Code" class="form-control" required/>
								
								<div class="clr"></div>
							</div>	
						 	@endif	

							@if(CNF_MULTILANG =='1') 
							<div class="form-group has-feedback">
								<label class="text-left"> {!! Lang::get('core.language') !!} </label>	
								<select class="form-control" name="language">
									@foreach(SiteHelpers::langOption() as $lang)
									<option value="{{ $lang['folder'] }}" @if(Session::get('lang') ==$lang['folder']) selected @endif>  {{  $lang['name'] }}</option>
									@endforeach

								</select>	
								
								<div class="clr"></div>
							</div>	
						 	@endif
					        <button type="submit" class="btn btn-common log-btn">{!! trans('core.submit') !!}</button>
					      </form>
					      <ul class="form-links">
					        <li class="pull-left"><a class="have_account" href="{{ URL::TO('user/register')}}" >{!! trans('core.abs_dont_have_acnt') !!}</a></li>
					        <li class="pull-right"><a href="#tab-forgot" class="forget" data-toggle="tab">{!! trans('core.abs_lost_your_pass') !!}</a></li>
					      </ul>
					    </div>
					    <div class="animated fadeInUp delayp1">
						<!--<div class="form-group has-feedback text-center">
							@if($socialize['google']['client_id'] !='' || $socialize['twitter']['client_id'] !='' || $socialize['facebook'] ['client_id'] !='') 
							<br />
							<p class="text-muted text-center"><b> {!! Lang::get('core.loginsocial') !!} </b>	  </p>
							@endif
							<div>
								@if($socialize['facebook']['client_id'] !='') 
								<a href="{{ URL::to('user/socialize/facebook')}}" class="btn btn-primary"><i class="icon-facebook"></i> Facebook </a>
								@endif
								@if($socialize['google']['client_id'] !='') 
								<a href="{{ URL::to('user/socialize/google')}}" class="btn btn-danger"><i class="icon-google"></i> Google </a>
								@endif
								@if($socialize['twitter']['client_id'] !='') 
								<a href="{{ URL::to('user/socialize/twitter')}}" class="btn btn-info"><i class="icon-twitter"></i> Twitter </a>
								@endif
							</div>
						</div>-->			
					   	</div>
					  </div>
					</div>
					<div class="tab-pane  m-t" id="tab-forgot">
						<div class="col-sm-6 col-sm-offset-4 col-md-4 col-md-offset-4">
				            <div class="page-login-form box">
				              <h3>
				                {!! trans('core.abs_forgot_pass') !!}
				              </h3>
				              <form method="post" action="{{ url('user/request')}}" class="form-vertical box" id="fr">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
				                <div class="form-group">
				                  <div class="input-icon">
				                    <i class="icon fa fa-user"></i>
				                    <input type="text" name="credit_email" placeholder="{!! Lang::get('core.email') !!}" class="form-control" required/>
				                  </div>
				                </div>     
				                <button type="submit" class="btn btn-common log-btn">{!! trans('core.abs_send_me_pass') !!}</button>
				              </form>
				              <ul class="form-links">
				                <li class="pull-left"><a class="have_account" href="{{ URL::TO('user/register')}}" >{!! trans('core.abs_dont_have_acnt') !!}</a></li>
				                <li class="pull-right"><a href="#tab-sign-in" data-toggle="tab">{!! trans('core.abs_back_to_login') !!}</a></li>
				              </ul>
				            </div>
				          </div>
					</div>
				</div>
			</div>
      </div> 
  </div>
    
  </div>
</section>


	


<script type="text/javascript">
	$(document).ready(function(){
		$('#or').click(function(){
		$('#fr').toggle();
		});
	});
</script>
@stop
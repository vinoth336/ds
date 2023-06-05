@extends('layouts.login')

@section('content')

  <link rel="stylesheet" href="http://localhost/food/food_app_back/abserve/foodstar/css/main.css">

    <div class="page-header admin-page-header" style="background-image: url(../abserve/foodstar/img/bg/bg-banner.jpg);">
      <div class="container">
        <div class="row">         
          <div class="col-md-12">
            <div class="breadcrumb-wrapper">
              <h2 class="page-title">{!! Lang::get('core.login_account') !!}</h2>
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
					        {!! Lang::get('core.login') !!}
					      </h3>
					      <form method="post" action="{{ url('user/signin')}}" class="form-vertical">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group">
					          <div class="input-icon">
					            <i class="fa fa-user"></i>
					            <input type="text" name="email" placeholder="{!! Lang::get('core.email_address') !!}" class="form-control" required="email" />
					          </div>
					        </div> 
					        <div class="form-group">
					          <div class="input-icon">
					            <i class="fa fa-unlock-alt"></i>
					            <input type="password" name="password" placeholder="{!! Lang::get('core.password') !!}" class="form-control" required="true" />
					          </div>
					        </div>                  
					        <div class="checkbox">
					        <input type="checkbox" id="remember" name="rememberme" value="1" style="float: left;">
					          <label for="remember">{!! Lang::get('core.remember_me') !!}</label>
					        </div>
					        @if(CNF_RECAPTCHA =='true') 
							<div class="form-group has-feedback">
								<label class="text-left"> {!! Lang::get('core.are_human') !!}</label>	
								<br />
								{!! captcha_img() !!}
								<input type="text" name="captcha" placeholder="Type Security Code" class="form-control" required/>
								
								<div class="clr"></div>
							</div>	
						 	@endif	

							<?php /*?>@if(CNF_MULTILANG =='1') 
							<div class="form-group has-feedback">
								<label class="text-left"> {!! Lang::get('core.language') !!} </label>	
								<select class="form-control" name="language">
									@foreach(SiteHelpers::langOption() as $lang)
									<option value="{{ $lang['folder'] }}" @if(Session::get('lang') ==$lang['folder']) selected @endif>  {{  $lang['name'] }}</option>
									@endforeach

								</select>	
								
								<div class="clr"></div>
							</div>	
						 	@endif<?php */?>
					        <button type="submit" class="btn btn-common log-btn">{!! Lang::get('core.submit') !!}</button>
					      </form>
					      <div class="form-links">
					        <!--<div class="pull-left"><a class="have_account" href="{{ URL::TO('user/register')}}" >{!! Lang::get('core.dont_account') !!}</a></div>-->
					        <div class="pull-right"><a href="#tab-forgot" class="forget" data-toggle="tab">{!! Lang::get('core.lost_pwd') !!}</a></div>
					      </div>
					    </div>
					    <div class="animated fadeInUp delayp1">
						<!--<div class="form-group has-feedback text-center">
							@if($socialize['google']['client_id'] !='' || $socialize['twitter']['client_id'] !='' || $socialize['facebook'] ['client_id'] !='') 
							<br />
							<p class="text-muted text-center"><b> {!! Lang::get('core.loginsocial') !!} </b>	  </p>
							@endif
							<div>
								@if($socialize['facebook']['client_id'] !='') 
								<a href="{{ URL::to('auth/facebook')}}" class="btn btn-primary"><i class="icon-facebook"></i> Facebook </a>
								@endif
								@if($socialize['google']['client_id'] !='') 
								<a href="{{ URL::to('auth/google')}}" class="btn btn-danger"><i class="icon-google"></i> Google </a>
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
				                {!! Lang::get('core.forgot_pwd') !!}
				              </h3>
				              <form method="post" action="{{ url('user/request')}}" class="form-vertical box" id="fr">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
				                <div class="form-group">
				                  <div class="input-icon">
				                    <i class="fa fa-user"></i>
				                    <input type="text" name="credit_email" placeholder="{!! Lang::get('core.email') !!}" class="form-control" required/>
				                  </div>
				                </div>     
				                <button type="submit" class="btn btn-common log-btn">{!! Lang::get('core.send_pwd') !!}</button>
				              </form>
				              <div class="form-links">
				                <!--<div class="pull-left"><a class="have_account" href="{{ URL::TO('user/register')}}" >{!! Lang::get('core.dont_account') !!}</a></div>-->
				                <div class="pull-right"><a href="#tab-sign-in" data-toggle="tab">{!! Lang::get('core.back_login') !!}</a></div>
				              </div>
				            </div>
				          </div>
					</div>
				</div>
			</div>
		</div> 
        
	  </div>        
      </div>
	</section>
            
    <section id="business" class="business-app roomy-70">
        <div class="container">
            
            <div class="row">
                <div class="main_business">
                    <div class="col-md-6">
                        <div class="business_mobile">
                            <div class="business_item">
                                    <div class="business_img">
                                        <img src="{{ asset('abserve/assets/images/info/mobile-devices.png') }}" alt="" />
                                    </div>
                                </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 business_item_right">
                        <div class="business_item sm-m-top-50">
                            <h2 class="text-uppercase"><strong>Delivery App</strong></h2>
                            
                            <p class="m-top-20">Order your Services from anywhere, anything at anytime with all new Delivery Star</p>
    
                            <div class="business_btn">
                                <a href="https://play.google.com/store/apps/details?id=com.deliverystar.consumer" target="_blank" class="m-top-20">
                                    <img src="{{ asset('abserve/assets/images/info/gplay.png') }}" alt="" />
                                </a>
                                <a href="https://itunes.apple.com/in/app/delivery-star/id1402968309?mt=8" target="_blank" class="m-top-20">
                                    <img src="{{ asset('abserve/assets/images/info/playstore.png') }}" alt="" />
                                </a>
                                
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>            
    <!-- End off Business section -->	


<script type="text/javascript">
	$(document).ready(function(){
		$('#or').click(function(){
		$('#fr').toggle();
		});
	});
</script>
@stop
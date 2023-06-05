@extends('layouts.login')

@section('content')
 <!-- Page Header Start -->
<div class="page-header" style="background-image: url(../abserve/foodstar/img/banner1.jpg);">
  <div class="container">
    <div class="row">         
      <div class="col-md-12">
        <div class="breadcrumb-wrapper">
          <h2 class="page-title">{!! Lang::get('core.join_us') !!}</h2>
        </div>
      </div>
    </div>
  </div>
</div>
  <section id="content">
      <div class="container">
      <div class="wrapper">
         <!-- TOP AREA -->
          <div style="min-height:400px;">
      		 	<div class="row">
      		 	@if(Session::has('message'))
						{!! Session::get('message') !!}
					@endif
				<ul class="parsley-error-list">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				<?php $countries = \SiteHelpers::country(); ?>
		          <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
		            <div class="page-login-form box">
		              <h3>
		                {!! Lang::get('core.register') !!}
		              </h3>
		              {!! Form::open(array('url'=>'user/create', 'class'=>'form-signup')) !!}
					    
					    <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-user"></i>
		                    <select name='group_id' id='group_id' class='select2 register_select'>
	                            <option @if(old('group_id') == '3') selected @endif value="3">{!! Lang::get('core.partner') !!}</option>
	                            <option @if(old('group_id') == '4') selected @endif value="4">{!! Lang::get('core.guest') !!}</option>
                            </select>
		                  </div>
		                </div> 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-user"></i>
		                    {!! Form::text('user_name', null, array('class'=>'form-control ', 'placeholder'=>\Lang::get('core.username') )) !!}
		                  </div>
		                </div> 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-user"></i>
		                    {!! Form::text('firstname', null, array('class'=>'form-control ', 'placeholder'=>\Lang::get('core.firstname')  )) !!}
		                  </div>
		                </div> 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-user"></i>
		                   	{!! Form::text('lastname', null, array('class'=>'form-control ', 'placeholder'=>\Lang::get('core.lastname'))) !!}
		                  </div>
		                </div> 
		                 <div class="form-group">
							<div class="input-icon">
								<i class="icon fa fa-phone"></i>
								<select class="select2 register_select" name='phone_code' id='phone_code'>
									<option value="" selected="disabled">Choose country</option>
									<?php /*?>@foreach($countries as $country)
									<option value="{{$country->phonecode}}">{{$country->name}} (+{{$country->phonecode}})</option>
									@endforeach<?php */?>
                                    <option selected="selected" value="91">INDIA (+91)</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="input-icon">
								<i class="icon fa fa-phone"></i>
								{!! Form::text('phone_number', null, array('class'=>'form-control chekphone ', 'maxlength'=>'10', 'placeholder'=>\Lang::get('core.phone_number'))) !!}
							</div>
						</div>
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-envelope"></i>
		                     {!! Form::text('email', null, array('class'=>'form-control chekemail ', 'placeholder'=>\Lang::get('core.email'))) !!}
		                  </div>
		                </div> 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-unlock-alt"></i>
		                    {!! Form::password('password', array('class'=>'form-control ', 'placeholder'=>\Lang::get('core.password'))) !!}
		                  </div>
		                </div>  
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-unlock-alt"></i>
		                    {!! Form::password('password_confirmation', array('class'=>'form-control ', 'placeholder'=>\Lang::get('core.conewpassword'))) !!}
		                  </div>
		                </div> 
		                @if(CNF_RECAPTCHA =='true') 
					    <div class="form-group has-feedback delayp1">
					        <label class="text-left"> {!! Lang::get('core.are_human') !!} </label>    
					        <br />
					        {!! captcha_img() !!} <br /><br />
					        <input type="text" name="captcha" placeholder="Type Security Code" class="form-control" required/>

					        <div class="clr"></div>
					    </div>
					    @endif                
		               
		                 <button type="submit"  class="btn btn-common log-btn ">{!! Lang::get('core.signup') !!}	</button>
		                 <label style="padding:10px 0">
						  {!! Lang::get('core.have_account') !!}<a class="have_account" href="{{ URL::to('user/login')}}"> {!! Lang::get('core.signin') !!}  </a> <!-- <a href="{{ URL::to('')}}"> {!! Lang::get('core.backtosite') !!}  </a>  -->
					   		</label>
		              </form>
		            </div>
		          </div>
		        </div>
          </div>
        </div>
     </div>
 </section>
<!-- Page Header End --> 
<script type="text/javascript">
	$(document).on('click','.sign-cont',function (e) {
		$( ".form-signup" ).submit();
	});
	$(document).on('keypress','.chekphone',function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			$("#errmsg").html("Digits Only").show().fadeOut("slow");
			return false;
		}
	});
</script>
@stop

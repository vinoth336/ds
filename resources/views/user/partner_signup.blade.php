@extends('layouts.login')

@section('content')
 <!-- Page Header Start -->
<div class="page-header" style="background: url(../abserve/foodstar/img/banner1.jpg);">
  <div class="container">
    <div class="row">         
      <div class="col-md-12">
        <div class="breadcrumb-wrapper">
          <h2 class="page-title">{!! trans('core.join_us') !!}</h2>
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
		          <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
		            <div class="page-login-form box">
		              <h3>
		               {!! trans('core.abs_partner_reg') !!}
		              </h3>
		              {!! Form::open(array('url'=>'user/partnercreate', 'class'=>'form-signup')) !!}
					    	
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-user"></i>
		                    {!! Form::text('firstname', null, array('class'=>'form-control', 'placeholder'=>'First Name' ,'required'=>'' )) !!}
		                  </div>
		                </div> 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-user"></i>
		                   	{!! Form::text('lastname', null, array('class'=>'form-control', 'placeholder'=>'Last Name','required'=>'')) !!}
		                  </div>
		                </div> 
		                 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-envelope"></i>
		                     {!! Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email Address','required'=>'email')) !!}
		                  </div>
		                </div> 
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-unlock-alt"></i>
		                    {!! Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password','required'=>'')) !!}
		                  </div>
		                </div>  
		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-unlock-alt"></i>
		                    {!! Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Confirm Password','required'=>'')) !!}
		                  </div>
		                </div> 

		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-phone"></i>
		                    <input type="text" name="phone" class="form-control"  placeholder="phone" value="">
		                  </div>
		                </div> 

		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-address-card"></i>
		                    <input type="text" name="address" class="form-control"  placeholder="contact address" value="">
		                  </div>
		                </div> 

		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-flag-o"></i>
		                    <input type="text" name="state" class="form-control"  placeholder="state" value="">
		                  </div>
		                </div> 

		                <div class="form-group">
		                  <div class="input-icon">
		                    <i class="icon fa fa-flag"></i>
		                    <input type="text" name="country" class="form-control"  placeholder="country" value="">
		                  </div>
		                </div> 

		                @if(CNF_RECAPTCHA =='true') 
					    <div class="form-group has-feedback delayp1">
					        <label class="text-left"> {!! trans('core.abs_are_u_human') !!} </label>    
					        <br />
					        {!! captcha_img() !!} <br /><br />
					        <input type="text" name="captcha" placeholder="Type Security Code" class="form-control" required/>

					        <div class="clr"></div>
					    </div>
					    @endif                
		               
		                 <button type="submit"  class="btn btn-common log-btn">{!! Lang::get('core.signup') !!}	</button>
		                 <label style="padding:10px 0">
						  {!! trans('core.abs_have_acnt') !!}<a class="have_account" href="{{ URL::to('user/login')}}"> {!! Lang::get('core.signin') !!}  </a> <!-- <a href="{{ URL::to('')}}"> {!! Lang::get('core.backtosite') !!}  </a>  -->
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

@stop

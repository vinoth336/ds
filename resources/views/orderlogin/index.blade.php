@extends('layouts.app')
@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
                   
                    <div class="page-content-wrapper m-t">	
                    <div class="tab-content" >
					<div class="tab-pane active m-t" id="tab-sign-in">
					  <div class="col-sm-6 col-sm-offset-4 col-md-4 col-md-offset-4">
						<div class="page-login-form box">
  <form method="post" action="{{ url('orderlogin/login')}}" class="form-vertical">
					        <div class="form-group">
					          <div class="input-icon">
					            <i class="icon fa fa-user"></i>
					            <input type="text" name="email" placeholder="{!! Lang::get('core.email_address') !!}" class="form-control" required="email" />
					          </div>
					        </div> 
					        <div class="form-group">
					          <div class="input-icon">
					            <i class="icon fa fa-unlock-alt"></i>
					            <input type="password" name="password" placeholder="{!! Lang::get('core.password') !!}" class="form-control" required="true" />
					          </div>
					        </div>                  
					        <button type="submit" class="btn btn-common log-btn btn-info">{!! Lang::get('core.submit') !!}</button>
					      </form>
    </div>
					        </div>
                              </div>
					        </div>
                            </div>

	
@stop
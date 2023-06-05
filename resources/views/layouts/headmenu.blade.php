<?php 
$wallet_details = \SiteHelpers::walletdetails();
?>
<div class="">
        <nav style="margin-bottom: 0;" role="navigation" class="navbar navbar-static-top nav-inside">
        <div class="navbar-header">
           <a class="navbar-brand" href="{{ URL::to('dashboard')}}">
		 	@if(file_exists(public_path().'/abserve/images/'.CNF_LOGO) && CNF_LOGO !='')
		 	<img src="{{ asset('abserve/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}" />
		 	@else
			<img src="{{ asset('abserve/images/logo.png')}}" alt="{{ CNF_APPNAME }}" />
			@endif
		 </a>
            
        </div>
        <ul class="nav navbar-top-links navbar-right">
        @if(Auth::user()->group_id == 1)
	        <li>
	        <a href="#" data-toggle="dropdown" class="dropdown-toggle count-info" aria-expanded="true">
	   			<span data-toggle="tooltip" title="Click to view host wallet request details"> <img style="width:20px;height: 20px" src="{{URL::To('/')}}/abserve/img/icon/wallet.svg">  <span class=" label label-danger">{{count($wallet_details)}}</span></span>
			</a>
	            <ul class="dropdown-menu dropdown-alerts notif-value" code="{{ url()}}">
					@if(count($wallet_details)>0)
						@foreach($wallet_details as $wallet_details1)
							<li id="fproid_{{$wallet_details1->id}}">
								<div class="text-center link-block col-sm-12">
									<span data-toggle="tooltip" title="Click to view this claim details"> 
										<div class="col-sm-3">
											<img src="{{\SiteHelpers::Image('/avatar','user',$wallet_details1->host_id)}}" class="origin round user_image" style="width: 50px;">
										</div>
										<div class="col-sm-7" style="text-align: left;font-size:12px">
										<strong>Request ID :</strong> {{$wallet_details1->id}}<br>
										<strong>Host Name :</strong> {{\SiteHelpers::hostname($wallet_details1->host_id)}}<br>
										<strong>Amount :</strong>                		
											${{$wallet_details1->amount}}                		
										</div>
									</span>
									<div class="col-sm-1" style="text-align: right">
										<a href="{{ url('/transactionrequest/update/') }}/{{$wallet_details1->id}}">
											<button class="btn btn-success btn-xs ads_approvalnormal" data-toggle="tooltip" title="Click to update status" style="display: table-cell;" type="button"><i class="fa fa-edit" style="color: #fff;"></i>
											</button>
										</a>
									</div>
								</div>
							</li>
						@endforeach
					@endif
					<li id="npro_nodet" @if(count(array($claim_details))>0) style="display: none;" @endif><div class="text-center">No details found</div></li>
				</ul>
			</li>
		@endif
         <!-- <li>   
			<a href="#" data-toggle="dropdown" class="dropdown-toggle count-info" aria-expanded="true"
            >
		        <i class="fa fa-envelope"></i>  <span class="notif-alert label label-danger">0</span>
		    </a>
                <ul class="dropdown-menu dropdown-alerts notif-value" code="{{ url()}}">
                	<li><div class="text-center link-block"><a href="{{ url('notification') }}"><strong>View All Notification</strong> <i class="fa fa-angle-right"></i></a></div></li>

                </ul>

        </li> -->            
		@if(CNF_MULTILANG ==1)
		<li  class="user dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag"></i><i class="caret"></i></a>
			 <ul class="dropdown-menu dropdown-menu-right icons-right">
				@foreach(SiteHelpers::langOption() as $lang)
					<li><a href="{{ URL::to('home/lang/'.$lang['folder'])}}"><i class="icon-flag"></i> {{  $lang['name'] }}</a></li>
				@endforeach	
			</ul>
		</li>	
		@endif			
		@if(Auth::user()->group_id == 1)
		<!-- <li class="user dropdown"><a class="dropdown-toggle" href="javascript:void(0)"  data-toggle="dropdown"><i class="fa fa-desktop"></i> <span>{!! Lang::get('core.m_controlpanel') !!}</span><i class="caret"></i></a>
		  <ul class="dropdown-menu dropdown-menu-right icons-right">
		   
		  	<li><a href="{{ URL::to('abserve/config')}}"><i class="fa  fa-wrench"></i> {!! Lang::get('core.m_setting') !!}</a></li>
			<li><a href="{{ URL::to('core/users')}}"><i class="fa fa-user"></i> {!! Lang::get('core.m_users') !!} &  {!! Lang::get('core.m_groups') !!} </a></li>
			<li><a href="{{ URL::to('core/users/blast')}}"><i class="fa fa-envelope"></i> {!! Lang::get('core.m_blastemail') !!} </a></li>	
			<li><a href="{{ URL::to('core/logs')}}"><i class="fa fa-clock-o"></i> {!! Lang::get('core.m_logs') !!}</a></li>	
			<li class="divider"></li>
			<li><a href="{{ URL::to('core/pages')}}"><i class="fa fa-copy"></i> {!! Lang::get('core.m_pagecms')!!}</a></li>
			
			<li class="divider"></li>
			<li><a href="{{ URL::to('abserve/module')}}"><i class="fa fa-cogs"></i> {!! Lang::get('core.m_codebuilder') !!}</a></li>
			<li><a href="{{ URL::to('abserve/tables')}}"><i class="icon-database"></i> Database Tables </a></li>
			<li><a href="{{ URL::to('abserve/menu')}}"><i class="fa fa-sitemap"></i> {!! Lang::get('core.m_menu') !!}</a></li>	
			<li class="divider"></li>
			<li><a href="{{ URL::to('core/template')}}"><i class="fa fa-desktop"></i> Template Guide </a></li>

		  </ul>
		</li> -->
		@endif
		
		<li class="user dropdown"><a class="dropdown-toggle" href="javascript:void(0)"  data-toggle="dropdown"><i class="fa fa-user"></i> <span>{!! Lang::get('core.m_myaccount') !!}</span><i class="caret"></i></a>
		  <ul class="dropdown-menu dropdown-menu-right icons-right">
		  	<!-- <li><a href="{{ URL::to('dashboard')}}"><i class="fa  fa-laptop"></i> {!! Lang::get('core.m_dashboard') !!}</a></li> -->
			<!-- <li><a href="{{ URL::to('')}}" target="_blank"><i class="fa fa-desktop"></i>  Main Site </a></li> -->
			<li><a href="{{ URL::to('user/profile')}}"><i class="fa fa-user"></i> {!! Lang::get('core.m_profile') !!}</a></li>
			<!-- <li><a href="{{ URL::to('core/elfinder')}}"><i class="fa fa-folder"></i>  File Manager </a></li> -->
			<li><a href="{{ URL::to('user/logout')}}"><i class="fa fa-sign-out"></i> {!! Lang::get('core.m_logout') !!}</a></li>
		  </ul>
		</li>			
		
	 				
				
            </ul>

        </nav>
        </div>
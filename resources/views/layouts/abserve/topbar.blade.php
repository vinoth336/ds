

{{--*/ $menus = SiteHelpers::menus('top') /*--}}
 	  <!-- <ul class="nav navbar-nav navbar-collapse collapse navbar-right"  id="topmenu"> -->
     <!-- <ul class="nav navbar-nav footer_link">
      	<a href="https://msg91.com/startups/?utm_source=startup-banner"><img src="https://msg91.com/images/startups/msg91Badge.png" width="120" height="90" title="MSG91 - SMS for Startups" alt="Bulk SMS - MSG91"></a>
      </ul>-->
 	  <!--<ul class="nav navbar-nav footer_link">
		@foreach ($menus as $menu)
			 <li class="@if(Request::is($menu['module'])) active @endif" >
			 	<a 
				@if($menu['menu_type'] =='external')
					href="<?php echo str_replace("{{baseurl}}",url(),($menu['url'])) ?>" 
				@else
					href="{{ url($menu['module'])}}" 
				@endif
			 
				 @if(count($menu['childs']) > 0 ) class="dropdown-toggle" data-toggle="dropdown" @endif>
			 		<i class="{{$menu['menu_icons']}}"></i> <span>
					
					@if(CNF_MULTILANG ==1 && isset($menu['menu_lang']['title'][Session::get('lang')]))
						{{ $menu['menu_lang']['title'][Session::get('lang')] }}
					@else
						{{$menu['menu_name']}}
					@endif	
				
					</span>
					@if(count($menu['childs']) > 0 )
					 <b class="caret"></b> 
					@endif  
				</a> 
				@if(count($menu['childs']) > 0)
					 <ul class="dropdown-menu dropdown-menu-right">
						@foreach ($menu['childs'] as $menu2)
						 <li class=" 
						 @if(count($menu2['childs']) > 0) dropdown-submenu @endif
						 @if(Request::is($menu2['module'])) active @endif">
						 	<a 
								@if($menu2['menu_type'] =='external')
									href="<?php echo str_replace("{{baseurl}}",url(),($menu2['url'])) ?>" 
								@else
									href="{{ url($menu2['module'])}}" 
								@endif
											
							>
								<i class="{{$menu2['menu_icons']}}"></i> 
									@if(CNF_MULTILANG ==1 && isset($menu2['menu_lang']['title'][Session::get('lang')]))
										{{ $menu2['menu_lang']['title'][Session::get('lang')] }}
									@else
										{{$menu2['menu_name']}}
									@endif
								
							</a> 
							@if(count($menu2['childs']) > 0)
							<ul class="dropdown-menu dropdown-menu-right">
								@foreach($menu2['childs'] as $menu3)
									<li @if(Request::is($menu3['module'])) class="active" @endif>
										<a 
											@if($menu3['menu_type'] =='external')
												href="<?php echo str_replace("{{baseurl}}",url(),($menu3['url'])) ?>" 
											@else
												href="{{ url($menu3['module'])}}" 
											@endif										
										
										>
											<span>
											@if(CNF_MULTILANG ==1 && isset($menu3['menu_lang']['title'][Session::get('lang')]))
												{{ $menu3['menu_lang']['title'][Session::get('lang')] }}
											@else
												{{$menu3['menu_name']}}
											@endif
											
											</span>  
										</a>
									</li>	
								@endforeach
							</ul>
							@endif							
							
						</li>							
						@endforeach
					</ul>
				@endif
			</li>
		@endforeach  
  </ul> -->
 
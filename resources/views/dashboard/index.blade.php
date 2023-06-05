@extends('layouts.app')


@section('content')

<script type="text/javascript" src="{{ asset('abserve/js/plugins/chartjs/Chart.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('abserve/themes/abserve/js/icon.js') }}"></script>

<script type="text/javascript">
	
jQuery(document).ready(function()
{


	jQuery('.number, .squarebox h4').each(function () {
	    $(this).prop('Counter',0).animate({
	        Counter: $(this).text()
	    }, {
	        duration: 4000,
	        easing: 'swing',
	        step: function (now) {
	            $(this).text(Math.ceil(now));
	        }
	    });
	});
})
</script>


<div class="page-content row">
	<div class="page-header">
	  <div class="page-title">
		<h3><i class="fa fa-desktop"></i> {!! trans('core.abs_Dashboard') !!} <small> {!! trans('core.abs_summary_info_site') !!}</small></h3>
	  </div>
		  
	</div>
	<div class="page-content-wrapper">  
	
	<div class="">
            <!-- <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
                <div class="lightbluebg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 text-right">
                                    <span>Views Today</span>

                                    <div class="number" id="myTargetElement1">9500</div>
                                </div>
                                <i class="livicon  pull-right" data-name="eye-open" data-l="true" data-c="#fff" data-hc="#fff" data-s="70" id="livicon-48" style="width: 70px; height: 70px;"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Last Week</small>
                                    <h4 id="myTargetElement1.1">98000</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Last Month</small>
                                    <h4 id="myTargetElement1.2">396000</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
                <!-- Trans label pie charts strats here-->
                <div class="redbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>{!! trans('core.abs_today_Sales') !!}</span>

                                    <div class="number" id="myTargetElement2">{{$today_order}}</div>
                                </div>
                                <i class="livicon pull-right" data-name="piggybank" data-l="true" data-c="#fff" data-hc="#fff" data-s="70" id="livicon-49" style="width: 70px; height: 70px;"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">{!! trans('core.abs_Last_Week') !!}</small>
                                    <h4 id="myTargetElement2.1">{{$week_order}}</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">{!! trans('core.abs_Last_Month') !!}</small>
                                    <h4 id="myTargetElement2.2">{{$month_order}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
                <!-- Trans label pie charts strats here-->
                <div class="goldbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>{!! trans('core.abs_subscribers') !!}</span>

                                    <div class="number" id="myTargetElement3">{{$today_sub}}</div>
                                </div>
                                <i class="livicon pull-right" data-name="archive-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70" id="livicon-50" style="width: 70px; height: 70px;"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">{!! trans('core.abs_Last_Week') !!}</small>
                                    <h4 id="myTargetElement3.1">{{$week_sub}}</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">{!! trans('core.abs_Last_Month') !!}</small>
                                    <h4 id="myTargetElement3.2">{{$month_sub}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                <!-- Trans label pie charts strats here-->
                <div class="palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>{!! trans('core.abs_registered_users') !!}</span>

                                    <div class="number" id="myTargetElement4">{{$today_register}}</div>
                                </div>
                                <i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff" data-hc="#fff" data-s="70" id="livicon-51" style="width: 70px; height: 70px;"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">{!! trans('core.abs_Last_Week') !!}</small>
                                    <h4 id="myTargetElement4.1">{{$week_register}}</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">{!! trans('core.abs_Last_Month') !!}</small>
                                    <h4 id="myTargetElement4.2">{{$month_register}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                <!-- Trans label pie charts strats here-->
                <div class="palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>{!! trans('core.abs_registered_partners') !!}</span>

                                    <div class="number" id="myTargetElement4">{{$today_register_partner}}</div>
                                </div>
                                <i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff" data-hc="#fff" data-s="70" id="livicon-52" style="width: 70px; height: 70px;"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">{!! trans('core.abs_Last_Week') !!}</small>
                                    <h4 id="myTargetElement4.1">{{$week_register_partner}}</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">{!! trans('core.abs_Last_Month') !!}</small>
                                    <h4 id="myTargetElement4.2">{{$month_register_partner}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	
	@if(Auth::check() && Auth::user()->group_id == 1)

<!-- <section>
	<div class="row m-l-none m-r-none m-t  white-bg shortcut " >
		<div class="col-sm-6 col-md-3 b-r  p-sm ">
			<span class="pull-left m-r-sm text-navy"><i class="fa fa-plus-circle"></i></span> 
			<a href="{{ URL::to('abserve/module') }}" class="clear">
				<span class="h3 block m-t-xs"><strong>  {!! Lang::get('core.dash_i_module') !!}  </strong>
				</span> <small class="text-muted text-uc">  {!! Lang::get('core.dash_module') !!}</small>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 b-r  p-sm">
			<span class="pull-left m-r-sm text-info">	<i class="fa fa-cogs"></i></span>
			<a href="{{ URL::to('abserve/config') }}" class="clear">
				<span class="h3 block m-t-xs"><strong> {!! Lang::get('core.dash_i_setting') !!}</strong>
				</span> <small class="text-muted text-uc">   {!! Lang::get('core.dash_setting') !!} </small> 
			</a>
		</div>
		<div class="col-sm-6 col-md-3 b-r  p-sm">
			<span class="pull-left m-r-sm text-warning">	<i class="fa fa-sitemap"></i></span>
			<a href="{{ URL::to('abserve/menu') }}" class="clear">
			<span class="h3 block m-t-xs"><strong>  {!! Lang::get('core.dash_i_sitemenu') !!} </strong></span>
			<small class="text-muted text-uc">  {!! Lang::get('core.dash_sitemenu') !!}  </small> </a>
		</div>
		<div class="col-sm-6 col-md-3 b-r  p-sm">
			<span class="pull-left m-r-sm ">	<i class="fa fa-users"></i></span>
			<a href="{{ URL::to('core/users') }}" class="clear">
			<span class="h3 block m-t-xs"><strong> {!! Lang::get('core.dash_i_usergroup') !!}</strong>
			</span> <small class="text-muted text-uc">  {!! Lang::get('core.dash_usergroup') !!} </small> </a>
		</div>
	</div> 
</section>	 -->

	
	<div class="row m-t">
		<div class="col-md-12">
			<div class="sbox">
				<div class="sbox-title"> <h3> Recent Partners <small> ( Last Activity ) </small> </h3> </div>
				<div class="sbox-content">
					<div class="row">
						<div class="col-md-12">
						<div class="table-responsive" >
							<table class="table table-striped">
								<tr>
									<th>  </th>
									<th> Users </th>
									<th> Last Activity </th>
								</tr>
							@foreach($online_users as $user)
								<tr>
									<td>  {!! SiteHelpers::showUploadedFile($user->avatar,'/uploads/users/') !!}</td>
									<td>{{ $user->first_name}} {{ $user->last_name}}</td>
									<td> {{ date("Y-m-d H:i:s", $user->last_activity) }}</td>
								</tr>
							@endforeach	

							</table>
							</div>
						</div>
						
					</div>
				
						
				</div>
			</div>
		</div>
		
		
	
	</div>
	@endif
</div>	
	
</div>



@stop
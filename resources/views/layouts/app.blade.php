<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> {{ CNF_APPNAME }} </title>
<meta name="keywords" content="">
<meta name="description" content=""/>
<link rel="shortcut icon" href="{!! url().'/' !!}favicon.ico" type="image/x-icon">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link href="{{ asset('abserve/js/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet"> 
		<link href="{{ asset('abserve/js/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/fonts/awesome/css/font-awesome.min.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/bootstrap.summernote/summernote.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/datepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/select2/select2.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/iCheck/skins/square/green.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/markitup/skins/simple/style.css') }}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/markitup/sets/default/style.css') }}" rel="stylesheet">
		<link href="{{ asset('abserve/css/animate.css')}}" rel="stylesheet">		
		<link href="{{ asset('abserve/css/icons.min.css')}}" rel="stylesheet">
		<link href="{{ asset('abserve/js/plugins/toastr/toastr.css')}}" rel="stylesheet">
		@if(!Session::get('themes') or Session::get('themes') =='')
		<link href="{{ asset('abserve/css/abserve.css')}}" rel="stylesheet">
		@else
		<link href="{{ asset('abserve/css/abserve.css')}}" rel="stylesheet">
		<!-- <link href="{{ asset('abserve/css/'.Session::get('themes').'.css')}}" rel="stylesheet">	 -->
		@endif
		<link href="{{ asset('abserve/themes/abserve/css/abserve_admin.css')}}" rel="stylesheet">
		
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/jquery.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/jquery.cookie.js') }}"></script>			
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/jquery-ui.min.js') }}"></script>				
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/iCheck/icheck.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/select2/select2.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/prettify.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/parsley.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/datepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/switch.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/bootstrap/js/bootstrap.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/abserve.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/jquery.form.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/jquery.jCombo.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/toastr/toastr.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/bootstrap.summernote/summernote.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/simpleclone.js') }}"></script>
		<script type="text/javascript" src="{{ asset('abserve/js/plugins/markitup/jquery.markitup.js') }}"></script>

		<script type="text/javascript">
			var base_url = '{!! url().'/' !!}';
		</script>

		<!-- Jcrop section -->
		<link href="{{ asset('abserve/css/jquery.Jcrop.min.css')}}" rel="stylesheet">
		<script type="text/javascript" src="{{ asset('abserve/js/jquery.Jcrop.min.js') }}"></script>
		<!-- Jcrop section ends -->
		
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->		
    <!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->

    <!-- Image Cropping -->
    <!-- <script type="text/javascript" src="{{ asset('abserve/js/script.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('abserve/js/jquery.imgareaselect.pack.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('abserve/css/imgareaselect-animated.css') }}">

    <!-- Time picker -->
   <script type="text/javascript" src="{{ asset('abserve/js/jquery.timepicker.js') }}"></script>
   <script type="text/javascript" src="{{ asset('abserve/js/bootstrap-datepicker.js') }}"></script>
   <script type="text/javascript" src="{{ asset('abserve/js/site.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('abserve/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/css/site.css') }}">

	
  	</head>
  	<body class="abserve-init" >
	<div id="wrapper">
		@include('layouts/headmenu')
		@include('layouts/sidemenu')
		<div class="gray-bg " id="page-wrapper">
			

			@yield('content')		
		</div>

		<div class="footer fixed">
		    <div class="pull-right">
		       
		    </div>
		    <div>
		        <strong>Copyright</strong> &copy; 2014-{{ date('Y')}} . {{ CNF_COMNAME }} 		       
		    </div>
		</div>		

	</div>

<div class="modal fade" id="abserve-modal" tabindex="-1" role="dialog">
<div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-header bg-default">
		
		<button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title abserve-modal-title">{!! trans('core.abs_modal_title') !!}</h4>
	</div>
	<div class="modal-body" id="abserve-modal-content">

	</div>

  </div>
</div>
</div>
	<div class="modal fade" id="confirm-delete1" role="dialog">
		<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">{!! trans('core.abs_confirm_delete') !!}</h4>
			</div>
			<div class="modal-body">
				<p>{!! trans('core.abs_are_you_sure') !!}</p>
				<p>{!! trans('core.abs_do_you_want_proceed') !!}</p>
			</div>
			<div class="modal-footer">
				<a class="btn btn-danger btn-ok" href="">{!! trans('core.btn_delete') !!}</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('core.close') !!}</button>
			</div>
		</div>
		</div>
	</div>

<div class="theme-config">
    <div class="theme-config-box">
        <!-- <div class="spin-icon">
            <i class="fa fa-cogs fa-spin"></i>
        </div> -->
        <div class="skin-setttings">
            <div class="title">{!! trans('core.abs_select_color_schema') !!}</div>
            <div class="setings-item">
                    <ul>
	                    <li><a href="{{ url('home/skin/abserve') }}"> {!! trans('core.abs_default_skin') !!}  <span class="pull-right default-skin"> </span></a></li> 
	                    <li><a href="{{ url('home/skin/abserve-dark-blue') }}"> {!! trans('core.abs_dark_blue_skin') !!} <span class="pull-right dark-blue-skin"> </span> </a></li> 
	                    <li><a href="{{ url('home/skin/abserve-light-blue') }}"> {!! trans('core.abs_light_blue_skin') !!} <span class="pull-right light-blue-skin"> </span> </a></li> 
	                   
                    </ul>

                
            </div>
            
        </div>
    </div>
</div>
@if(\Auth::user()->group_id == 1)
		<script language="javascript">
		jQuery(document).ready(function($)	{
		   // $('.markItUp').markItUp(mySettings );
		});
		</script>
@endif
{{ Sitehelpers::showNotification() }} 
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('body').click(function(event){
			$('.restaurant_hide_icon_block').removeClass('active');
		});
	    $('#sidemenu').abserveMenu();
		$('.spin-icon').click(function () {
	        $(".theme-config-box").toggleClass("show");
	    });
		$('.restaurant_hide_icon').click(function(event){
			event.stopPropagation();
			$('.restaurant_hide_icon_block').removeClass('active');
			$(this).next('.restaurant_hide_icon_block').toggleClass('active');
			/*$('.back_layout').show();*/
		});
		/*$('.back_layout').click(function(){
			$('.restaurant_hide_icon_block').removeClass('active');
			$('.back_layout').hide();
		});
		setInterval(function(){ 
			var noteurl = $('.notif-value').attr('code'); 
			$.get( noteurl +'/notification/load',function(data){
				$('.notif-alert').html(data.total);
				var html = '';
				$.each( data.note, function( key, val ) {
					html += '<li><a href="'+val.url+'"> <div> <i class="'+val.icon+' fa-fw"></i> '+ val.title+'  <span class="pull-right text-muted small">'+val.date+'</span></div></li>';
					html += '<li class="divider"></li>';			 
				});
				html += '<li><div class="text-center link-block"><a href="'+noteurl+'/notification"><strong>View All Notification</strong> <i class="fa fa-angle-right"></i></a></div></li>';
				$('.notif-value').html(html);
			});
		}, 60000);*/
	});
</script>
</body> 
</html>
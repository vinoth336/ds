<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> {{ CNF_APPNAME }} | {{ $pageTitle}} </title>
<meta name="keywords" content="{{ $pageMetakey }}">
<meta name="description" content="{{ $pageMetadesc }}"/>
<link rel="shortcut icon" href="" type="image/x-icon">  
<script type="text/javascript">
var base_url = '{!! url().'/' !!}';
</script>

 <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
 <link rel="stylesheet" type="text/css" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">

        <script type="text/javascript" src="{{ asset('abserve/js/plugins/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('abserve/themes/abserve/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('abserve/js/plugins/parsley.js') }}"></script> 
        <!-- <script type="text/javascript" src="{{ asset('abserve/themes/abserve/js/fancybox/source/jquery.fancybox.js') }}"></script>  -->
        <script type="text/javascript" src="{{ asset('abserve/themes/abserve/js/jquery.mixitup.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/icomoon.css') }}">
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/mystyles.css') }}">
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/abserve.css') }}">
        <link rel="stylesheet" href="{{ asset('abserve/themes/abserve/css/icon_font.css') }}">
        <!-- <script src="{{ asset('abserve/themes/abserve/js/modernizr.js') }}"></script> -->

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->        
    </head>
<body>
<div class="pre-header abserve">
<div class="global-wrap">

  <header id="main-header" role="banner" id="top" class="navbar navbar-static-top bs-docs-nav">
    <div class="container">
      <div class="navbar-header">
        <button aria-expanded="false" aria-controls="bs-navbar" data-target="#bs-navbar" data-toggle="collapse" type="button" class="navbar-toggle collapsed">
          <span class="sr-only">{!! trans('core.abs_toggle_navigation') !!}</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url()}}">
         <img src="{{ asset('abserve/images/backend-logo.png') }}" alt="Image Alternative text" title="Image Title" />
        </a>
      </div>
        <nav class="collapse navbar-collapse" id="bs-navbar">
          <div class="dropdown_1">
           <ul class="nav navbar-nav navbar-right">
            @if(CNF_MULTILANG ==1)
            <li  class="user dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i><i class="caret"></i></a>
               <ul class="dropdown-menu dropdown-menu-right icons-right">
                @foreach(SiteHelpers::langOption() as $lang)
                  <li><a href="{{ URL::to('home/lang/'.$lang['folder'])}}"><i class="icon-flag"></i> {{  $lang['name'] }}</a></li>
                @endforeach 
              </ul>
            </li> 
            @endif
          
            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> My Account <span class="caret"></span></a> -->
            <?php
            /*if(Auth::check())
            {
            //do something
                $user_id = Auth::user()->id; 
                $username = Auth::user()->username;
                $this->data['user_id'] = $user_id;
                $this->data['username'] = $username;
                $user = DB::select("SELECT * FROM `tb_users` WHERE `id` =" .$user_id);

                foreach ($user as $key) {
                if($key->avatar != ''){
                    $user_image_path = \URL::to('').'/uploads/users/'.$key->avatar;
                }
                else{
                    $user_image_path = \URL::to('').'/abserve/themes/abserve/img/40x40.png';
                }
                if($key->avatar != ''){
                    $user_img = $key->avatar;
                }
                else{
                    $user_img = \URL::to('').'/abserve/themes/abserve/img/40x40.png';
                }
                }
            }*/
            ?>
            @if(!Auth::check()) 
                <li><a href="{{ URL::to('user/login') }}"><i class="fa fa-sign-in"></i> {!! Lang::get('core.signin') !!}</a></li>
                <li><a href="{{ URL::to('user/register') }}"><i class="fa fa-user"></i> {!! Lang::get('core.signup') !!}</a></li>
             @else
                <?php if(Auth::check())
                    /*{
                        $user_id    = Auth::user()->id; 
                        $username   = Auth::user()->username;
                        $first_name = Auth::user()->first_name;
                    }*/
                ?>
                <li><a class="head_profile" href="{{ URL::to('user/profile') }}">
                <img class="origin round user_image" src="{{$user_image_path}}" alt="Image Alternative text" title="@if($username != ''){{$username}}@else{{$first_name}}@endif" />{!! trans('core.abs_user') !!}</a></li>
                <!-- <li><a href="@if(app('session')->get('gid') != '1'){{ URL::to('user/profile') }}@else{{ URL::to('dashboard') }}@endif"><i class="fa fa-desktop"></i> {!! Lang::get('core.m_dashboard') !!}</a></li>  -->
                <li><a href="{{ URL::to('user/logout') }}"><i class="fa  fa-sign-out"></i> {!! Lang::get('core.m_logout') !!}</a></li>                     
             @endif  
                      
           </ul>
          </div> 
            @include('layouts/abserve/topbar')
        </nav>
    </div>
  </header> 
</div>
        <!-- TOP AREA -->
       <div style="min-height:440px;">
            <div class="bg-holder-content error_page">
                <div class="full-center-d">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <p class="text-hero">404</p>
                                <h1>{!! trans('core.abs_page_is_not_found') !!}</h1>
                                <p>Aptent vulputate gravida curae lacinia imperdiet tempus erat vulputate posuere mollis quisque magna facilisi sagittis ridiculus consequat a nisl tincidunt</p><a class="btn btn-white btn-ghost btn-lg mt5" href="{{ url()}}"><i class="fa fa-long-arrow-left"></i> {!! trans('core.abs_to_home_page') !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
</div>
<div class="abs_footer_code"></div>
        <footer id="main-footer">
            <div class="container">
                <div class="row row-wrap">
                    <?php 
                    /*$blocks = DB::select("SELECT * FROM `blocks` ");
                    foreach($blocks as $block)
                    {
                        $var = '7';
                        if($block->id == $var )
                        {
                            $str = $block->template; 
                            echo $str. "\n";
                        }
                    }*/
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 full_width">
                        <h4>{!! trans('core.abs_to_home_page') !!}</h4>
                        <form id="new_ltr">
                            <label>{!! trans('core.abs_enter_your_email_adrs') !!}</label>
                            <input type="text" id="n_email" class="form-control" style="max-width:240px;">
                            <p class="mt5"><small>{!! trans('core.abs_we_have_send_spam') !!}</small>
                            </p>
                            <p id="n_status"></p>
                            <input type="submit" class="btn btn-primary news_letter" value="Subscribe">
                        </form>
                    </div>
                    <?php
                    /*foreach($blocks as $block)
                    {
                        $var = '5';
                        if($block->id == $var )
                        {
                            $str = $block->template; 
                            echo $str. "\n";
                        }
                        $var = '6';
                        if($block->id == $var )
                        {
                            $str = $block->template; 
                            echo $str. "\n";
                        }
                    }*/
                    ?>
                </div>
            </div>
        </footer> 
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="{{ asset('abserve/themes/abserve/js/custom.js') }}"></script>
</body> 
</html>
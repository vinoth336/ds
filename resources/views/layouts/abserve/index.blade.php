<!DOCTYPE html>

<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! CNF_APPDESC !!}</title>
    <meta name="keywords" content="{{ $pageMetakey }}">
    <meta name="description" content="{{ $pageMetadesc }}"/>
    <link rel="shortcut icon" href="{!! url().'/' !!}favicon.ico" type="image/x-icon">
    <script type="text/javascript">
        var base_url = '{!! url().'/' !!}';
    </script>
    <!--Google Font link-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('abserve/assets/css/slick/slick.css') }}"> 
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/bootsnav.css') }}">

    <!-- xsslider slider css -->
    <!--<link rel="stylesheet" href="assets/css/xsslider.css">-->
    <!--For Plugins external css-->
    <!--<link rel="stylesheet" href="assets/css/plugins.css" />-->

    <!--Theme custom css -->
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/style.css') }}">
    
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/custom-style.css') }}">
    <!--<link rel="stylesheet" href="assets/css/colors/maron.css">-->

    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{ asset('abserve/assets/css/responsive.css') }}" />

    <script src="{{ asset('abserve/assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
        
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5c6d4fdf77e0730ce043e2e3/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    </head>

    <body data-spy="scroll" data-target=".navbar-collapse">
    	
        <!-- Preloader -->
        <div id="loading">
            <div id="loading-center">
                <div id="loading-center-absolute">
                    <div class="object" id="object_one"></div>
                    <div class="object" id="object_two"></div>
                    <div class="object" id="object_three"></div>
                    <div class="object" id="object_four"></div>
                </div>
            </div>
        </div><!--End off Preloader -->
        
        <div class="culmn">
            <!--Home page style-->


            <nav class="navbar navbar-default bootsnav navbar-fixed">
			<!-- Preheader and social link 
                <div class="navbar-top bg-grey fix">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="navbar-callus text-left sm-text-center">
                                    <ul class="list-inline">
                                        <li><a href=""><i class="fa fa-phone"></i> Call us: 1234 5678 90</a></li>
                                        <li><a href=""><i class="fa fa-envelope-o"></i> Contact us: your@email.com</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="navbar-socail text-right sm-text-center">
                                    <ul class="list-inline">
                                        <li><a href=""><i class="fa fa-facebook"></i></a></li>
                                        <li><a href=""><i class="fa fa-twitter"></i></a></li>
                                        <li><a href=""><i class="fa fa-linkedin"></i></a></li>
                                        <li><a href=""><i class="fa fa-google-plus"></i></a></li>
                                        <li><a href=""><i class="fa fa-behance"></i></a></li>
                                        <li><a href=""><i class="fa fa-dribbble"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  --->

                <!-- Start Top Search -->
                <div class="top-search">
                    <div class="container">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                        </div>
                    </div>
                </div>
                <!-- End Top Search -->


                <div class="container"> 
                    <div class="attr-nav">
                        <ul>
                           <!-- <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>  -->
                           	@if(!Auth::check())
								<li class="login-icon"><a href="{{ URL::to('user/login') }}"><i class="fa fa-sign-in"></i> Login </a> </li>
							@else
                            	<li class="login-icon"><a href="{{ URL::to('user/logout') }}"><i class="fa fa-sign-out"></i> Logout </a> </li>
                            @endif
                        </ul>
                    </div> 

                    <!-- Start Header Navigation -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="<?php echo url(); ?>">
                            <img src="{{ asset('abserve/assets/images/ds-logo.gif') }}" class="logo" alt="">
                            <!--<img src="assets/images/footer-logo.png" class="logo logo-scrolled" alt="">-->
                        </a>

                    </div>
                    <!-- End Header Navigation -->

                    <!-- navbar menu -->
                    <div class="collapse navbar-collapse" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?php echo url(); ?>/">Home</a></li>                    
                            <li><a href="<?php echo url(); ?>/about-us">About Us</a></li>
                            <li><a href="<?php echo url(); ?>/food">Food</a></li>
                            <li><a href="<?php echo url(); ?>/services">Services</a></li>
                          <!--  <li><a href="gallery.html">Gallery</a></li>  -->
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div> 

            </nav>

            @include($pages)

            <footer id="contact" class="footer action-lage p-top-80">
                <!--<div class="action-lage"></div>-->
                <div class="container">
                    <div class="row">
                        <div class="widget_area">
							<div class="col-md-7 col-sm-12 deliverystar-addr">
                            <div class="col-md-4 col-sm-4 addr-office1">
                                <div class="widget_item widget_office">
                                    <h5 class="text-white">Our Head Office - Vellore</h5>

                                    <div class="widget_ab_item m-top-20">
                                        <div class="">
                                            <p>
											   No.5, 2nd Floor, <br> 
											   Banglore Chennai service Road, <br>
											   Vellore 632004 <br>
											   Tamilnadu, India <br>
											   Phone no. - 0416-6650201 <br>
											   Contact us - admin@deliverystar.in</p>
                                        </div>
                                    </div>
                                    
                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 -->
							
							<div class="col-md-4 col-sm-4 addr-office2">
                                <div class="widget_item widget_office">
                                    <h5 class="text-white"> Karur</h5>

                                    <div class="widget_ab_item m-top-20">
                                        <div class="">
                                            <p>
											   No: 7, 2nd Floor, <br> 
											   TAM Complex,  <br>
											   NRMP Street, Covai Road, <br>
											   Karur-639002,  Tamilnadu, India <br>
											   Phone no. - +91-74483-33222 <br>
											   Contact us - admin@deliverystar.in</p>
                                        </div>
                                    </div>
                                    
                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 -->
							
							<div class="col-md-4 col-sm-4 addr-office3">
                                <div class="widget_item widget_office">
                                    <h5 class="text-white"> Tiruvannamalai </h5>

                                    <div class="widget_ab_item m-top-20">
                                        <div class="">
                                            <p>
											   154, Mathalankulam Street, <br> 
											   Opp to Joyalukkas, <br>
											   Tiruvannamalai - 606601 <br>
											   Tamilnadu, India <br>
											   Phone no. - +91-86674-94357 <br>
											   Contact us - admin@deliverystar.in</p>
                                        </div>
                                    </div>
                                    
                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 -->
							
							</div>
							
							

                            <div class="col-md-5 col-sm-12 partner-logo">
							
							<div class="col-md-4 col-sm-4">
                                <div class="widget_item widget_links sm-m-top-50">
                                    <h5 class="text-white">Contact & Legal Information</h5>
                                    <ul class="m-top-20">
                                        <li class="m-top-10"><a href="<?php echo url(); ?>/faq">Help & Support</a></li>
                                        <li class="m-top-10"><a href="<?php echo url(); ?>/terms-conditions">Terms & Condition</a></li>
                                        <li class="m-top-10"><a href="<?php echo url(); ?>/privacy-policy">Privacy Policy</a></li>
                                    </ul>
                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 --> 
							
							<div class="col-md-4 col-sm-4 tech-partner">
                                <div class="widget_item widget_contact sm-m-top-50">
                                    <h5 class="text-white">Our Technology Partner</h5>
                                    <div class="bg_logo m-top-20">
                                    	<a href="https://www.bicsglobal.com/" target="_blank"> <img src="<?php echo url(); ?>/abserve/assets/images/info/bg-logo.png" alt="" /></a>
                                    </div>
                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 -->                            

                            <div class="col-md-4 col-sm-4">
                                <div class="widget_item widget_message sm-m-top-50">
                                    <h5 class="text-white">Our Message Partner </h5>
                                    <div class="msg_logo m-top-30">
                                    	<a href="https://msg91.com/startups/?utm_source=startup-banner"> <img src="https://msg91.com/images/startups/msg91Badge.png" title="MSG91 - SMS for Startups" alt="Bulk SMS - MSG91" style="width:120px; margin:20px 0px 0px -20px;"> </a>
                                        <!--<img class="" src="assets/images/info/msg-logo.png" alt="" />-->
                                    </div>                                    
                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 -->
							</div>
							
                        </div>
                    </div>
                </div>
                <div class="main_footer fix text-center p-top-40 p-bottom-30">
                    <div class="col-md-12">
                        <p class="wow fadeInRight" data-wow-duration="1s">
                            © 2019 Delivery Star
                        </p>
                    </div>
                </div>
            </footer>

        </div>

        <!-- JS includes -->
        <script src="{{ asset('abserve/assets/js/vendor/jquery-1.11.2.min.js') }}"></script>
        <script src="{{ asset('abserve/assets/js/vendor/bootstrap.min.js') }}"></script>

        <script src="{{ asset('abserve/assets/js/jquery.magnific-popup.js') }}"></script>
        <script src="{{ asset('abserve/assets/js/jquery.easing.1.3.js') }}"></script>
        <script src="{{ asset('abserve/assets/css/slick/slick.js') }}"></script>
        <script src="{{ asset('abserve/assets/css/slick/slick.min.js') }}"></script>
        <script src="{{ asset('abserve/assets/js/jquery.collapse.js') }}"></script>
        <script src="{{ asset('abserve/assets/js/bootsnav.js') }}"></script>

        <script src="{{ asset('abserve/assets/js/plugins.js') }}"></script>
        <script src="{{ asset('abserve/assets/js/main.js') }}"></script>
        
	</body>
</html>
<!--Home Sections-->
<section id="home" class="home bg-black fix">
    <div class="overlay"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="main_home text-center">
                <div class="col-md-12">
                    <div class="hello_slid">
                      @foreach ($home_banners as $home_banner)
                        <div class="slid_item">
                            <div class="home_text ">
                                <img src="{{ asset('uploads/home_banner/'.$home_banner->banner_image) }}" >
                                <div class="banner-content">
                                    <h2 class="text-white">{{ $home_banner->content1 }}</h2>
                                    <h1 class="text-white">{{ $home_banner->content2 }}</h1>
                                    <h3 class="text-white">{{ $home_banner->content3 }}</h3>
                                </div>
                            </div>                           
                        </div><!-- End off slid item -->
                      @endforeach
                    </div>
                </div>

            </div>

        </div><!--End off row-->
    </div><!--End off container -->
</section> <!--End off Home Sections-->



<!--Featured Section-->
<section id="features" class="features">
    <div class="container">
        <div class="row">
            <div class="main_features fix roomy-70">
                <div class="col-md-6">
                    <div class="features_item sm-m-top-30">
                        
                        <div class="img-home">
                            <img src="{{ asset('abserve/assets/images/info/who-we-are.png') }}" alt="" >
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="features_item sm-m-top-30">
                        
                        <div class="who-content">
                            <h3 class="title-head">Who We Are</h3>
                            <p>The all new “Delivery Star”. First in Vellore, Ranipet, Tiruvannamalai & Karur.</p>
                            <p>Delivery Star is an online and mobile platform that helps customers to get things done. From simple to complicated tasks, Delivery Star can help you complete your everyday tasks, including cleaning, appliance services, parcels, delivery, beauty/spa, handyman work, car/bike services, pets & pest, duplicate keys, purohits & funeral services.</p>
                            <p>No matter how long your to-do list, we can tackle it for you.</p>
                            <p>We deliver food, bakery, groceries, vegetables, fruits, meat, flowers and medicines etc., Also we pick and deliver home food to students onto their schools. In addition, we deliver your packages, charges, keys etc.,</p>
                            <p>Place your orders with an option of online payment or cash on delivery. We offer our services through mobile apps for iPhone & Android</p>
                            <p>Love to Join? Looking for Flexibility? Join with us and our community will help.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div><!-- End off row -->
    </div><!-- End off container -->
</section><!-- End off Featured Section-->


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


<!--Map and Region section-->
<section id="test" class="test bg-grey roomy-60 fix">
    <div class="container">
        <div class="row">                        
            <div class="main_test fix">
            
                <div class="col-md-6 map-region">
                    <div class="col-md-10 region-inner">
                        <div class="">
							<h3 class="title-head">Where We Are</h3>
                            <h5>First delivery app in</h5>
                            <h6>Vellore, Ranipet, Tiruvannamalai & Karur</h6>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6 map-animation">
                        <div class="map_img">
                            <img class="" src="{{ asset('abserve/assets/images/info/map-animation.gif') }}" alt="" />
                        </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- End off section -->
<script type="text/javascript" src="{{ asset('abserve/js/foodstar.js') }}"></script> 
<div class="search-page">
    <div class="container">
        <div class="search_header">
            <div class="container_new">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <a class="logo" href="{{ URL::to('/') }}">
                            @if(file_exists(public_path().'/abserve/images/'.CNF_LOGO) && CNF_LOGO !='')
                            <img src="{{ asset('abserve/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}" />
                            @else
                            <img src="{{ asset('abserve/images/logo.png')}}" alt="{{ CNF_APPNAME }}" />
                            @endif
                        </a>
                    </div>
                    <div class="col-md-10 col-sm-12 col-xs-12">
                        <div class="search-box">
                            <div class="col-md-3 col-sm-12 col-xs-12 no_pad">
                                <!-- <div class="delivery_location">
                                    <label>Delivery Location</label>
                                    <h5 class="text-ellipsis">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        Park Town, Chennai
                                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    </h5>
                                </div> -->
                                <div class="location-drop-down">
                                    <h6>{!! Lang::get('core.search_location') !!}</h6>
                                    <div class="input-box">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <input id="search-new-location" autocomplete="off" spellcheck="false" type="text" placeholder="{!! Lang::get('core.enter_location') !!}">
                                        <div class="use-current-location">
                                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i> <span class="use-current-location-text">{!! Lang::get('core.current_location') !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-9 col-xs-12 no_pad mid-search">
                                <form class="search-form" method="get" action="{{ URL::to('/frontend/search')}}">
                                    <div class="search-box-v2">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <input type="text" value="{{ app('request')->input('keyword') }}" id="fn_keyword" name="keyword" placeholder="{!! Lang::get('core.deliver_location') !!}">
                                        <input type="hidden" value="{{ app('request')->input('lat') }}" id="lat" name="lat">
                                        <input type="hidden" value="{{ app('request')->input('lang') }}" id="lang" name="lang">
                                        <input type="submit" name="search_btn" id="search_btn_head" value="Search"  class="hidden"/>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2 col-sm-3 your_cart_block">
                                <div class="hidden-xs" style="position:relative;">
                                   <div class="you-cart-btn"><div>{!! Lang::get('core.your_cart') !!}</div><span></span></div>
                                    <input type="hidden" value="{{$search_cart_res_id}}" name="res_id" id="res_id">
                                </div>
                                <div class="cart_block menu_cart">
                                    <div>
                                        <?php echo $cart_items_html; ?>
                                    </div>
                                    <!-- <div class="restaurent_name">Restaurant: Ratna Cafe</div>
                                    <div class="add_product border_btm">
                                        <div class="each_product">
                                            <div class="col-xs-5 no_pad">
                                                Bonda (3 Pcs)
                                            </div>
                                            <div class="col-xs-3 text-center no_pad">
                                                <span class="plus-icon icon-swgy-circle-minus"></span>
                                                <span class="item-count">1</span>
                                                <span class="minus-icon icon-swgy-plus-circle"></span>
                                            </div>
                                            <div class="col-xs-4 no_pad text-right">
                                                <span class="item-price">$70.00</span>
                                            </div>
                                        </div>
                                        <div class="each_product">
                                            <div class="col-xs-5 no_pad">
                                                Bonda (3 Pcs)
                                            </div>
                                            <div class="col-xs-3 text-center no_pad">
                                                <span class="plus-icon icon-swgy-circle-minus"></span>
                                                <span class="item-count">1</span>
                                                <span class="minus-icon icon-swgy-plus-circle"></span>
                                            </div>
                                            <div class="col-xs-4 no_pad text-right">
                                                <span class="item-price">$70.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add_product">
                                        <div class="each_product">
                                            <div class="col-xs-6 no_pad">Item Total :</div>
                                            <div class="col-xs-6 no_pad text-right"><span class="item-price">$155.00</span></div>
                                        </div>
                                        <div class="each_product">
                                            <div class="col-xs-6 no_pad">Packing Charges :</div>
                                            <div class="col-xs-6 no_pad text-right"><span class="item-price">$15.00</span></div>
                                        </div>
                                        <div class="each_product">
                                            <div class="col-xs-6 no_pad">GST :</div>
                                            <div class="col-xs-6 no_pad text-right"><span class="item-price">$27.00</span></div>
                                        </div>
                                        <div class="each_product">
                                            <div class="col-xs-6 no_pad">Delivery Charges :</div>
                                            <div class="col-xs-6 no_pad text-right"><span class="item-price">$30.00</span></div>
                                        </div>
                                    </div>
                                    <div class="to_pay">
                                        <div class="col-xs-6 no_pad">To Pay :</div>
                                        <div class="col-xs-6 no_pad text-right">$228.00</div>
                                    </div>
                                    <button class="btn btn-checkout">Checkout</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="search-full" class="owl-carousel pad_b_20">
            <div class="item"><img src="{{ asset('abserve/images/search_1.jpg')}}" alt=""></div>
            <div class="item"><img src="{{ asset('abserve/images/search_2.jpg')}}" alt=""></div>
            <div class="item"><img src="{{ asset('abserve/images/search_3.jpg')}}" alt=""></div>
            <div class="item"><img src="{{ asset('abserve/images/search_4.jpg')}}" alt=""></div>
        </div>
        <div class="slider_block">
            <div id="search-three">
                <div class="item"><img src="{{ asset('abserve/images/search_cat_1.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.what_new') !!}</div>
                        <span class="result-count">4 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_2.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.trend_now') !!}</div>
                        <span class="result-count">11 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_3.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.offer_near') !!}</div>
                        <span class="result-count">7 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_4.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.super_fast') !!}</div>
                        <span class="result-count">3 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_1.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.trend_now') !!}</div>
                        <span class="result-count">4 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_2.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.offer_near') !!}</div>
                        <span class="result-count">8 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_3.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.trend_now') !!}</div>
                        <span class="result-count">11 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
                <div class="item"><img src="{{ asset('abserve/images/search_cat_4.jpg')}}" alt="">
                    <div class="caption">
                        <div class="title1">{!! Lang::get('core.trend_now') !!}</div>
                        <span class="result-count">6 {!! Lang::get('core.restaurants') !!}</span>
                    </div>
                </div>
            </div>
            <img class="slider_loader" src="{{ asset('abserve/images/loading.gif')}}" alt="">
        </div>
        <div class="row">
            @if(Session::has('message'))
            {!! Session::get('message') !!}
            @endif
            <ul class="parsley-error-list">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <div class="col-md-12 col-sm-12 col-xs-12 page-sidebar">
                <div class="container_new">
                    @include('frontend/filter')
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 page-content">
                @include('frontend/resultlist')
            </div>
        </div>
    </div>
</div>
<div class="modal fade zoom in" role="dialog" aria-labelledby="signInModal" id="login-modal" tabindex="-1" modal-backdrop="" style="display: none; padding-right: 15px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!----><button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-if="!isUnavoidable"><span aria-hidden="true">×</span></button><!---->
            </div>
            <div class="modal-body no-pad">
                <div class="alert_fn"></div>
                <div class="popup-header">
                    <h4 class="m0">{!! Lang::get('core.login_account') !!}</h4>
                </div>
                <form method="post" action="{{ url('user/signin')}}" class="form-vertical" id="login-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <div class="input-icon">
                            <i class="icon fa fa-user"></i>
                            <input type="text" name="email" placeholder="{!! Lang::get('core.email') !!}" class="form-control" required="email" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="input-icon">
                            <i class="icon fa fa-unlock-alt"></i>
                            <input type="password" name="password" placeholder="{!! Lang::get('core.password') !!}" class="form-control" required="true" />
                        </div>
                    </div> 
                    <div class="checkbox">
                        <label for="remember"> <input type="checkbox" id="remember" name="remember" value="1" >
                            {!! Lang::get('core.remember_me') !!}</label>
                    </div>
                    <button type="submit" class="btn btn-common log-btn">{!! Lang::get('core.submit') !!}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyDIHoHvIosxw4wz4bDEKzfcPzCPFmPA5rw"></script>
<script type="text/javascript">
    var IsplaceChange = true;
    $(document).ready(function(){
        var input = document.getElementById('fn_keyword');
        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('fn_keyword'));
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place   = autocomplete.getPlace();
            var latitude  = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            $('#lat').val(latitude);
            $('#lang').val(longitude);
            IsplaceChange = true;
            if(latitude != '' && latitude != undefined &&  longitude != '' && longitude != undefined ){
                $("#search_btn_head").trigger('click');
            }
        });
        $("#fn_keyword").keydown(function () {
            IsplaceChange = false;
        });
        $("#fn_keyword").focusout(function () {      
            if (IsplaceChange) {
            } else {
                $('#lat').val('');
                $('#lang').val('');
                $("#fn_keyword").val('');
            }
        });
    })
    $(document).on("click",'.add_cart_item',function(){
        var count = $(this).closest("div").find('.item-count').text();
        $(this).closest("div").find('.item-count').text(parseInt(count) + 1);
        var item = $(this).closest("div.menu-cart-items").attr("id");
        var item_array = item.split("_");
        add_to_cart(item_array[1],parseInt(count) + 1);
    });
    $(document).on("click",'.remove_cart_item',function(){
        var count = $(this).closest("div").find('.item-count').text();
        if(count > 0){
            $(this).closest("div").find('.item-count').text(parseInt(count) - 1);
            var item = $(this).closest("div.menu-cart-items").attr("id");
            var item_array = item.split("_");
            remove_from_cart(item_array[1],parseInt(count) - 1);
        }
    });
    function add_to_cart(item,qty){
        var res_id = $("#res_id").val();
        $.ajax({
            url: '<?php echo url(); ?>/frontend/checkcart',
            type: "GET",
            data: {'res_id':res_id},
            success: function(data){

                var res_id = $("#res_id").val();
                if(data == 0){
                    $.ajax({
                        url: '<?php echo url(); ?>/frontend/addtotcart',
                        type: "GET",
                        data: {'item':item,'res_id':res_id,qty:qty,key:"searchcart"},
                        success: function(data){
                            $('.menu_cart > div').html(data);
                        }
                    });
                }else{
                    $("#switch_cart").find('#cart_res').val(res_id);
                    $("#switch_cart").find('#cart_item').val(item);
                    $("#switch_cart").find('#cart_qty').val(qty);
                    $("#switch_cart").modal("show");
                }
            }
        });
    }
    function remove_from_cart(item,qty){
        var res_id = $("#res_id").val();
        $.ajax({
            url: '<?php echo url(); ?>/frontend/removefromcart',
            type: "GET",
            data: {'item':item,'res_id':res_id,'qty':qty,key:"searchcart"},
            success: function(data){
                 var content=data.html;
                $('.menu_cart > div').html(content);
            }
        });
    }
    function login_popup(){
        $('#login-modal').modal('show');
    }
</script>
<style type="text/css">
.header_top {display: none;}
	
	
/* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
.you-cart-btn, .you-cart-btn::before, .you-cart-btn::after {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}

.you-cart-btn {
  width: 156px;
  height: 74px;
  margin: 0px 10px;
  color: #df2027;
  box-shadow: inset 0 0 0 1px rgba(223, 32, 39, 0.5);
}

.search-box .you-cart-btn { border:none !important; }
.you-cart-btn::before, .you-cart-btn::after {
  content: '';
  z-index: -1;
  margin: -5%;
  box-shadow: inset 0 0 0 2px;
  animation: clipMe 8s linear infinite;
}
.you-cart-btn::before {
  animation-delay: -4s;
}


@keyframes clipMe {
  0%, 100% {
    clip: rect(0px, 100px, 2px, 0px);
  }
  25% {
    clip: rect(0px, 5px, 75px, 0px);
  }
  50% {
    clip: rect(75px, 100px, 100px, 0px);
  }
  75% {
    clip: rect(0px, 200px, 75px, 100px);
  }
}

/*@keyframes clipMe {
  0%, 100% {
    clip: rect(0px, 220px, 2px, 0px);
  }
  25% {
    clip: rect(0px, 2px, 220px, 0px);
  }
  50% {
    clip: rect(218px, 220px, 220px, 0px);
  }
  75% {
    clip: rect(0px, 220px, 220px, 218px);
  }
}
*/

*,
*::before,
*::after {
  box-sizing: border-box;
}
</style>

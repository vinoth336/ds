
<?php
if($restaurant->opening_time != ''){
    $day_session = substr($restaurant->opening_time, -2);

    if (substr($restaurant->opening_time,0,1) != '0' && substr($restaurant->opening_time,0,2) < 9)
        $restaurant->opening_time = '0'.$restaurant->opening_time; 

    $open_time = substr($restaurant->opening_time,0,8);

    $open_time = $open_time." ".$day_session;

    $chunks = explode(':', $open_time);
    if (strpos( $open_time, 'am') === false && $chunks[0] !== '12') {
        $chunks[0] = $chunks[0] + 12;
    } else if (strpos( $open_time, 'pm') === false && $chunks[0] == '12') {
        $chunks[0] = '00';
    }
    $openformat_time =  preg_replace('/\s[a-z]+/s', '', implode(':', $chunks));

    $currentTime = time() + 3600;
    if ($currentTime > strtotime($openformat_time)) {
        $open = '1';
    }else{
        $open = '0';
    }
}
if($restaurant->closing_time != ''){
    $day_session = substr($restaurant->closing_time, -2);

    if (substr($restaurant->closing_time,0,1) != '0')
        $restaurant->closing_time = '0'.$restaurant->closing_time; 

    $close_time = substr($restaurant->closing_time,0,8);

    $close_time = $close_time." ".$day_session;

    $chunks = explode(':', $close_time);
    if (strpos( $close_time, 'am') === false && $chunks[0] !== '12') {
        $chunks[0] = $chunks[0] + 12;
    } else if (strpos( $close_time, 'pm') === false && $chunks[0] == '12') {
        $chunks[0] = '00';
    }
    $closeformat_time =  preg_replace('/\s[a-z]+/s', '', implode(':', $chunks));
    $currentTime = time() + 3600;

    if ($currentTime > strtotime($closeformat_time)) {
        $close = '1';
    }else{
        $close = '0';

    }
}
?>
<div class="restaurent_details">
<?php $rating = \SiteHelpers::getOverallRating($restaurant->id);?>
<div id="search-row-wrapper1" class=" top_details fixed_div">
<input type="hidden" value="{{$categories_count}}" id="res_cat_count">
<input type="hidden" value="{{$Cookie_Id}}" id="cur_cookie_id" >
    <div class="bg-overlay1"></div>
    <div class="inner-box container ads-details-wrapper restaurent_top" style="margin-bottom:0px;">
        <!-- <div class="add-image">
            <a href="#"><img width="120px" height="120px" src="{{$restaurant->logo}}" alt=""></a>
        </div> -->
        <div class="col-xs-12 nopadding">
        <div class="">
        <h4>{{$restaurant->name}}</h4>
        <p class="item-intro"><span class="poster">{{$cuisine_name}} </</span></p>
        <!-- <p><span class="ui-bubble is-member">Rating</span> <span class="date"> 
            @for($k=0; $k<$restaurant->rating; $k++)
            <i class="fa fa-star"></i>
            @endfor
        </span></p> -->
        <div class="col-xs-12 nopadding">
            <div class="col-xs-12 col-md-3 col-sm-5 nopadding check_details">
                <div class="col-xs-4 pad_left_zero">{!! Lang::get('core.time') !!}</div>
                 @if($rating > 0)
                <div class="col-xs-4 rating pad_left_zero">{{$rating}}</div>
                @endif
                <div class="col-xs-4 pad_left_zero">
                    <span>{{$restaurant->delivery_time}}</span> <span class="grey">{!! Lang::get('core.mins') !!}</span>
                </div>
            </div>
            <div class="col-md-6 col-sm-7 col-xs-12 search_dishes nopadding">
                <!-- <div class="col-xs-7 input_search nopadding">
                    <input type="text" placeholder="Search dishes..." id="filter" />
                    <label><span><i class="fa fa-search" aria-hidden="true"></i></span></label>
                </div>
                <div class="col-xs-5" style="padding-right:0px;">
                    <div class="veg_check">
                        <input id="veg" type="checkbox">
                        <label for="veg">Vegetarian</label>
                    </div>
                </div> -->
            </div>
            <!-- <p class="food_offer col-xs-12 nopadding">20% off on all orders</p> -->
        </div>
        <div class="col-xs-12 nopadding restaurant_info">
        <strong>Description : </strong>{!! $restaurant->res_desc !!}
        </div>
        </div>
        </div>
    </div>
</div>
<!-- Start Content -->
<div id="content" class="all_food_details" >

    <div class="container no-pad">

        <p class="offers_food">
            <?php
            if($close == '1' ){
                //echo 'Opens next at '.$open_time.', tomorrow';
            }else if($open == '0'){
                //echo 'Opens next at '.$open_time.', today';
            }
            ?>
            <i class="fa fa-gift" aria-hidden="true"></i>{!! Lang::get('core.offer_order') !!}
        </p>
        <div class="col-xs-12 no-pad">
            <!-- Product Info Start -->
            <div class="product-info menu_item">
                <div class="col-sm-3 left_details">
                    <aside class="panel-body panel-details sjob">
                        <ul class="cat_list">
                            @foreach($categories as $key => $cat)
                            <li id="{{$cat->id}}" class="category_list @if($key == '0') active @endif">
                                <a class=" no-margin ">{{$cat->name}}<br></a>
                            </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
                <div class="col-md-6 col-sm-9 no_pad">
                    <div class="food_order_details">
                        <div class="ads-details-info col-md-12 recomm_list">
                            <div class="title recomm_title">
                                <h5 class="text-center">{!! Lang::get('core.restaurant_recommend') !!}</h5>
                                <span class="text-center">{!! Lang::get('core.best_restaurant') !!}</span>
                            </div>
                            @foreach($recomm_items as $ritems)
                            <?php $item_time_valid = \SiteHelpers::getItemTimeValid($ritems->id); ?>
                            <div class="col-lg-6 col-md-4 col-sm-6 all_recomm_food restaurant-items-unit" data-id="0">
                                <div class="item">
                                    <div class="header">
                                        <img src="{{$ritems->image}}" onerror="this.onerror=null;this.src='../../uploads/images/no-image.png';" style="opacity: 1;">
                                    </div>
                                    <div class="footer">
                                    <div class="restaurant-item" >
                                        <div class="food-item" id="item_<?php echo $ritems->id; ?>">
                                            <div class="container-fluid">
                                            <div class="row no-pad" ng-if="vm.isRecommended">
                                            <div class="col-xs-12 food_title_text">
                                                <h5 class="recommended text-ellipsis food_names" ng-class="{'has-discription' : item.description}">
                                                    {{$ritems->item_name}}
                                                </h5>
                                                <span>{{$ritems->description}}</span>
                                            </div>
                                            <?php $currPrice = number_format((float)\SiteHelpers::CurrencyValue($ritems->price),2,'.','');
                                                    $currsymbol = (\Session::has('currency_symbol')) ? \Session::get('currency_symbol') : '$' ;
                                            ?>
                                            <div class="col-xs-12 food_item_count_price">
                                                <div class="col-xs-6 no-pad text-left">
                                                    <span class="item-price"><!-- <i class="fa fa-inr" aria-hidden="true"></i> -->{!! $currsymbol !!} {!! $currPrice !!}</span>
                                                </div>
                                                @if($res_time_valid == 1)
                                                    @if($item_time_valid == 1)
                                                        <div class="col-xs-6 no-pad pull-right text-right items_count" id="fnitem_<?php echo $ritems->id; ?>">
                                                            <i class="fa fa-minus remove_item" aria-hidden="true" style="cursor:pointer;"></i>
                                                            <span class="item-count"  id="afid_{{$ritems->id}}" >{!! \SiteHelpers::foodcheck($ritems->id) !!}</span>
                                                            <i data-fid="{{$ritems->id}}" class="fa fa-plus add_item" aria-hidden="true" style="cursor:pointer;"></i>
                                                        </div>
                                                    @else
                                                        <div class="col-xs-6 no-pad pull-right text-right ">
                                                            <span><font color="red">{!! Lang::get('core.out_of_stock') !!}</font></span>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="clearfix"></div>
                        <div class="">
                            <div class="restaurant-items-wrapper">
                                <?php $main_cat = ''; $sub_cat='';?>
                                @foreach($hotel_items as $h_items)
                                <?php $item_time_valid = \SiteHelpers::getItemTimeValid($h_items->id); ?>
                                <div class="restaurant-items-unit" id="cat_{{$h_items->main_cat}}" data-id= "{{$h_items->main_cat}}" >
                                    @if(($main_cat != $h_items->Main_cat && $sub_cat != $h_items->Sub_cat) || ($main_cat == $h_items->Main_cat && $sub_cat != $h_items->Sub_cat))
                                    <div class="restaurant-items-title">
                                        @if($h_items->Main_cat == $h_items->Sub_cat)
                                        {{$h_items->Main_cat}} @else
                                        {{$h_items->Main_cat}} <i class="fa fa-angle-double-right" aria-hidden="true"></i>  {{$h_items->Sub_cat}}
                                        @endif
                                    </div>
                                    @endif
                                    <div  class="restaurant-items-body">
                                        <div class="repeat-wrapper">
                                            <div class="restaurant-item no_pad" >
                                                <div class="food-item col-xs-12" id="item_<?php echo $h_items->id; ?>">
                                                    <div class="col-xs-6 col-md-7">
                                                        <span class="item-type veg-item" ></span>
                                                        <h5 class="item_name food_names">{{$h_items->item_name}}</h5>
                                                    </div>
                                                    @if($res_time_valid == 1)
                                                        @if($item_time_valid == 1)
                                                            <div class="col-xs-4 col-md-3 no-right-pad text-center items_count" id="fnitem_<?php echo $h_items->id; ?>">
                                                                <i class="fa fa-minus remove_item" aria-hidden="true" style="cursor:pointer;"></i>
                                                                <span class="item-count"  id="afid_{{$h_items->id}}">{!! \SiteHelpers::foodcheck($h_items->id) !!}</span>
                                                                <i data-fid="{{$h_items->id}}" class="fa fa-plus add_item " aria-hidden="true" style="cursor:pointer;"></i>
                                                            </div>
                                                        @else
                                                            <div class="col-xs-4 col-md-3 no-right-pad text-center ">
                                                                <span><font color="red">{!! Lang::get('core.out_of_stock') !!}</font></span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <?php $currPrice = number_format((float)\SiteHelpers::CurrencyValue($h_items->price),2,'.','');
                                                            $currsymbol = (\Session::has('currency_symbol')) ? \Session::get('currency_symbol') : '$' ;
                                                    ?>
                                                    <div class="col-xs-2 no-left-pad text-right">
                                                        <div >
                                                            <span class="item-price">&nbsp;{!! $currsymbol !!} {!! $currPrice !!}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $main_cat = $h_items->Main_cat; $sub_cat = $h_items->Sub_cat; ?>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-sm-3">
                    <aside class="panel-body panel-details sjob">
                        Estimated Delivery Time <strong class="pull-right">{{$restaurant->delivery_time}} minutes</strong>
                    </aside>
                </div> -->
                <div class="col-md-3 col-sm-12 menu_cart right_details">
                    <div>
                        <?php echo $cart_items_html; ?>
                    </div>
                </div>
            </div>
            <!-- Product Info End -->
        </div>
    </div>         
</div>
</div>
<input type="hidden" value="{{$restaurant->id}}" id="res_id" name="res_id" />
<div class="modal fade zoom in" role="dialog" aria-labelledby="signInModal" id="login-modal" tabindex="-1" modal-backdrop="" style="display: none; padding-right: 15px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-if="!isUnavoidable"><span aria-hidden="true">×</span></button>
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
    <div class="modal fade clear-cart " tabindex="-1" role="dialog" id="switch_cart" >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-danger" id="myModalLabel">{!! Lang::get('core.clear_cart') !!}?</h4>
                </div>
                <div class="modal-body text-center">
                    <p>{!! Lang::get('core.start_refresh') !!}</p>
                    <input type="hidden" name="cart_item" id="cart_item" value="">
                    <input type="hidden" name="cart_qty" id="cart_qty" value="">
                    <input type="hidden" name="cart_res" id="cart_res" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  btn red" data-dismiss="modal">{!! Lang::get('core.take_back') !!}</button>
                    <button type="button" class="btn  btn-primary add_new_cart_item" >{!! Lang::get('core.start_refresh_yes') !!}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
    
    <script type="text/javascript" src="{{ asset('abserve/js/jquery.sticky-sidebar-scroll.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function()
    {
        var h = $(".header_top").height();
        var f = $(".restaurent_top").height();
        var o = $(".offers_food").height();
        var t = $(".top_details").height();
        var fho = f + h + o + 10;
        $.stickysidebarscroll(".right_details > div",{offset: {top: fho, bottom: 350}});
        $.stickysidebarscroll(".left_details .panel-details",{offset: {top: fho, bottom: 350}});

        $(window).scroll(function()
        {
            var s = $(window).scrollTop();
            var th = $(".sub-header").offset().top;
            var sh = $(".sub-header").height() + 1;
            if(s > th)
            {
                $(".header_top").addClass("fixed_div").css({"top":"auto","transform":"translateY(-"+sh+"px)","-webkit-transform":"translateY(-"+sh+"px)"});
                $(".top_details").css({"top":h,"box-shadow":"0 2px 1px rgba(0,0,0,.1)"});
            }
            else
            {
                $(".header_top").removeClass("fixed_div").css({"top":"auto","transform":"translateY(0px)","-webkit-transform":"translateY(0px)"});
                $(".top_details").css({"top":"auto","box-shadow":"none"});
                $(".right_details > div").css({"position":"relative","top":"auto","left":"auto"});
                $(".left_details .panel-details").css({"position":"relative","top":"auto","left":"auto"});
            }

            $('.restaurant-items-unit').each(function() {
                if(s >= $(this).offset().top - t - h) {
                    var id = $(this).attr('data-id');
                    $('.cat_list li').removeClass('active');
                    $('.cat_list li#'+ id).addClass('active');
                }
            });
        })
    })

    $(document).on("click",'.add_item',function(){
        $("#btn-checkout").attr("disabled","disabled");
        var count = $(this).closest("div").find('.item-count').text();
        $(this).closest("div").find('.item-count').text(parseInt(count) + 1);
        var item = $(this).closest("div").attr("id");
        var item_array = item.split("_"); 
        add_to_cart(item_array[1],parseInt(count) + 1); 
        // console.log($(this).data('fid'));
    });

    $(document).on("click",'.remove_item',function(){
        var count = $(this).closest("div").find('.item-count').text();
        if(count > 0){
            $("#btn-checkout").attr("disabled","disabled");
            $(this).closest("div").find('.item-count').text(parseInt(count) - 1);
            var item = $(this).closest("div").attr("id");
            var item_array = item.split("_");
            remove_from_cart(item_array[1],parseInt(count) - 1);
        }
    });

    $(document).on("click",'.add_cart_item',function(){
        $("#btn-checkout").attr("disabled","disabled");
        var count = $(this).closest("div").find('.item-count').text();
        $(this).closest("div").find('.item-count').text(parseInt(count) + 1);
        var item = $(this).closest("div.menu-cart-items").attr("id");
        var item_array = item.split("_");
        add_to_cart(item_array[1],parseInt(count) + 1);
        var fid = $(this).data('faid');
        $('#afid_'+fid).text((parseInt(count) + 1));
        // console.log();
    });

    $(document).on("click",'.remove_cart_item',function(){
        var count = $(this).closest("div").find('.item-count').text();

        if(count > 0){
            $("#btn-checkout").attr("disabled","disabled");
            $(this).closest("div").find('.item-count').text(parseInt(count) - 1);
            var sp_count = parseInt(count)-1;
            var item = $(this).closest("div.menu-cart-items").attr("id");
            var item_array = item.split("_");
            remove_from_cart(item_array[1],sp_count);        
            var fid = $(this).data('faid');
            $('#afid_'+fid).text((parseInt(count) - 1));
        }
    });

    function add_to_cart(item,qty){
        var res_id = $("#res_id").val();
        $.ajax({
            url: '<?php echo url(); ?>/frontend/checkcart',
            type: "GET",
            data: {'res_id':res_id},
            success: function(data){
                $("#btn-checkout").attr("disabled","");
                var res_id = $("#res_id").val();
                if(data == 0){
                    $.ajax({
                        url: '<?php echo url(); ?>/frontend/addtotcart',
                        type: "GET",
                        data: {'item':item,'res_id':res_id,qty:qty},
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
    $(".add_new_cart_item").click(function(){
        var res_id = $("#cart_res").val();
        var item = $("#cart_item").val();
        var qty = $("#cart_qty").val();

        $.ajax({
            url: '<?php echo url(); ?>/frontend/addtotcart',
            type: "GET",
            data: {'item':item,'res_id':res_id,'qty':qty},
            success: function(data){
                $("#switch_cart").modal("hide");
                $('.menu_cart > div').html(data);
            }
        });
    });

    function remove_from_cart(item,qty){

        var res_id = $("#res_id").val();
        $.ajax({
            url: '<?php echo url(); ?>/frontend/removefromcart',
            type: "GET",
            data: {'item':item,'res_id':res_id,'qty':qty},
            success: function(data){
                var result = data.html;
                var Cart = data.cart;
                $("#btn-checkout").attr("disabled","");
                $('.menu_cart > div').html(result);
                
            }
        });
    }

    $(document).on("click",'.category_list',function(){
        var catCount = $("#res_cat_count").val()
        var id = $(this).attr("id");
        var h = $(".header_top").height();
        var t = $(".top_details").height();
        var disco = $(".offers_food").height();
        var t_menu = $(".sub-header").height();
        //alert($(".recomm_list").offset().top);
        if(catCount > 1){
            if(id == '0'){
                $('html,body').animate({
                    scrollTop: $(".recomm_list").offset().top - t_menu - disco - t - h - 20},
                    'slow');
            }else{
                $('html,body').animate({
                    scrollTop: $("#cat_"+id).offset().top - t - h},
                    'slow');
            }

            $(".cat_list li.category_list").removeClass('active');
            $(this).addClass("active");
        }
    });

    function login_popup(){
        $('#login-modal').modal('show');
    }

    $("#login-form").validate({
        // Rules for form validation
        rules:
        {
            email:
            {
                required: true,
                email: true
            },
            password:
            {
                required: true,
                minlength: 3,
                maxlength: 20
            }
        },

        // Messages for form validation
        messages:
        {
            email:
            {
                required: '{!! Lang::get("core.email_error") !!}',
                email: '{!! Lang::get("core.valid_email") !!}'
            },
            password:
            {
                required: '{!! Lang::get("core.password_error") !!}'
            }
        },                  
        submitHandler: function(form) {
            var purl = "{{ url('/')}}/user/plogin";
            var cookie_id = $("#cur_cookie_id").val();

            $.ajax({
                url: purl,
                type: 'post',
                data:  $('#login-form').serialize(),
                success: function(data) {

                    
                    if(data != ''){
                        if(data == 2){
                            $("#login-modal").find('.alert_fn').html("<div class='alert alert-danger'><strong>{!! Lang::get('core.combination_error') !!} </strong></div>");
                        }else if(data == 3){
                            $("#login-modal").find('.alert_fn').html('<div class="alert alert-danger"><strong>{!! Lang::get("core.no_email") !!}</strong></div>');

                        }else if(data == 4){
                            $("#login-modal").find('.alert_fn').html('<div class="alert alert-danger"><strong>{!! Lang::get("core.block_error") !!}</strong></div>');

                        }else if(data == 5){
                            $("#login-modal").find('.alert_fn').html('<div class="alert alert-danger"><strong>{!! Lang::get("core.inactive_error") !!}</strong></div>');

                        }else if(data == 1){
                            $.ajax({
                                url : base_url+"frontend/setcartitmes",
                                type : "post",
                                data : {
                                    cookieid : cookie_id,
                                },
                                dataType : "json",
                                success :function(){
                                    
                                }
                            })
                            $("#login-modal").find('.alert_fn').html('<div class="alert alert-success"><strong>{!! Lang::get("core.success_login") !!}..</strong></div>');
                            setTimeout(function(){ location.reload(); }, 3000);
                        }
                    }

                }            
            });
        },
        // Do not change code below
        errorPlacement: function(error, element)
        {
            error.insertAfter(element.parent());
        }
    });
</script>

<style type="text/css">
    li.active a{
        color: #f5861f;
    }
    .cat_list li a{cursor:pointer;}
    .menu-cart-title {
        background: #fbfafa;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        padding: 1em;
        color: #1a1a1a;
        font-size: 11px;
    }

    .menu-cart-title  h1 {
        margin-top: 0;
        margin-bottom: 3px;
        font-size: 1.25em;
        text-transform: uppercase;
        font-weight:normal;
    }
    .menu-cart-block .menu-cart-body, .menu-cart-block-md .menu-cart-body {
        position: relative;
        background-color: #fff;
        padding: 1em 0 1em 1em;
        border-bottom: 1px solid #cbcbcb;
        min-height: 50px;
        overflow-y: auto;
        -webkit-transition: min-height ease-in-out .75s;
        transition: min-height ease-in-out .75s;
    }
    .menu-cart-body.empty {
        min-height: 250px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 3em;
        overflow: hidden;
        font-size: 1.2em;
        line-height: 1.5;
        text-align: center;
        color: #d4d4d4;
    }
    .menu-cart-body.empty{
        min-height: 250px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 3em;
        overflow: hidden;
        font-size: 1.2em;
        line-height: 1.5;
        text-align: center;
        color: #d4d4d4;
        position: relative;
        background-color: #fff;
    }
    .btn-checkout:disabled {
        background-color: #d4d4d4;
    }
    .btn-checkout {
        position: relative;
        background-color: #f5861f;
        color: #fff;
        width: 100%;
        text-transform: uppercase;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
        padding: 1em;
    }
    .item {
        max-height: 240px;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 2em;
        box-shadow: 0 1px 3px 0 rgba(0,0,0,.12), 0 1px 2px 0 rgba(0,0,0,.24);
    }
    .item > .header {
        height: 152px;
        overflow: hidden;
    }
    .item > .header img {
        max-width: 100%;
        height: 100%;
    }
    .restaurant-item {
        padding: 0px;
        position: relative;
        border-bottom: 0 solid #f6f6f6;
    }
    .restaurant-item .food-item {
        padding: 0em 0 1em;
    }
    .restaurant-item .food-item span.item-type {
        position: absolute;
        font-family: swgy-icon;
        height: 14px;
        width: 14px;
        top: 0;
        left: -4px;
        text-align: center;
    }
    .repeat-wrapper .restaurant-item .food-item h5.recommended {
        position: relative;
        margin: 0 0 12px 15px;
        padding-bottom: 12px;
        display: inline-flex;
        font-size: 13px;
    }
    .restaurant-item .food-item .item-count {
        position: relative;
        top: 0px;
    }
    .no-pad{padding:0;}
    .title {
        border-bottom: 1px solid #f6f6f6;
        background-color: #fff;
        text-align: center;
        padding: 0 1.25em 1.25em 0;

    }
    .title h5 {
        margin: 0 0 2px;
        font-weight: 700;
    }
    .title span {
        color: #585858;
    }
    .restaurant-items-unit .restaurant-items-title {
        border-bottom: 1px solid #f6f6f6;
        padding: 1.25em;
        background-color: #fbfafa;
        font-size: 12px;
    }

    .repeat-wrapper .restaurant-item {
        padding: 0 1em;
    }
    .repeat-wrapper .restaurant-item {
        border-bottom: 1px solid #f6f6f6;
    }
    .repeat-wrapper .restaurant-item .food-item {
        padding: 1em 0;
    }
    .repeat-wrapper .restaurant-item .food-item span.item-type {
        position: absolute;
        font-family: swgy-icon;
        height: 14px;
        width: 14px;
        top: 0;
        left: -4px;
        text-align: center;
    }
    .repeat-wrapper .restaurant-item .food-item h5 {
        margin: 0 0 3px;
        position: relative;
        font-weight: 700;
        font-size: 14px;
    }
    #login-form .state-error + em {
        display: block;
        margin-top: 6px;
        padding: 0 1px;
        font-style: normal;
        font-size: 12px;
        line-height: 15px;
        color: #f00;
    }
    .restaurant_info {
    width: 300px;
    border: 1px #f5861f ;
    padding: 25px;
}
</style>
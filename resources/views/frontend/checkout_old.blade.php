<!-- Start Content -->
<div id="content">
  <div class="container checkout-page">
    <div class="row ">
      <!-- Product Info Start -->
      <div class="col-xs-12 col-md-12 checkout-item-block">
        <header>
            <div class="holder">
                <div class="col-md-12 no-left-pad">
                    <div class="title">
                        <h6 class="text-muted text-ellipsis">{!! Lang::get("core.express_checkout") !!}</h6>
                        <h1 class="text-ellipsis">{!! Lang::get("core.restaurant") !!}: {{$restaurant->name}}</h1>
                        <input type="hidden" id="restaurant_id" value="{{$restaurant->id}}">
                    </div>
                </div>
            </div>
        </header>
        <div class="product-info ">
          <div class="col-sm-8 nopadding">
              <div class="container-fluid">
                  <div class="row no-pad">
                      <div class="col-xs-12 no-pad">
                          <section class="block-unit block-error detailed">
                              <header>
                                  <div class="container-fluid">
                                      <div class="row">
                                          <div class="col-sm-1 col-xs-2">
                                              <span class="number">1</span>
                                          </div>
                                          <div class="no-left-pad col-sm-7 col-xs-10" >
                                              <h5>{!! Lang::get("core.delivery_details") !!}</h5>
                                              <h6 class="text-ellipsis">{!! Lang::get("core.order_coupon") !!}</h6>
                                          </div>
                                          <div class="pull-right edit_delivery" style="display:none;">
                                            <button class="btn btn_edit_delivery"><i class="fa fa-pencil" aria-hidden="true"></i> {!! Lang::get("core.btn_edit") !!}</button>
                                          </div>
                                      </div>
                                  </div>
                              </header>
                              <div class="content">
                                <div class="content-detailed">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-offset-1 col-md-9 no-pad xs-pad" style="">
                                            <div class="address-list clearfix">
                                                <div class="col-xs-12 no-pad link-bar clearfix">
                                                    <h5 class="pull-left">!!<span ng-if="vm.deliveryAddressEditable">{!! Lang::get("core.select") !!} </span>{!! Lang::get("core.delivery_address") !!}</h5>
                                                    <h5 class="pull-right fn_map_modal" ><a href="javascript:void(0)">+ {!! Lang::get("core.add_new_address") !!}</a></h5>
                                                </div>
                                               
                                            </div>
                                            <div class="address-list col-xs-12 nopadding clearfix">
                                            <div class="col-xs-12 no-address"style="">
                                                <div class="content">
                                                    <div class="text">
                                                        <span>{!! Lang::get("core.existing_address_service") !!}.</span>
                                                        <span>
                                                        <u class="fn_map_modal">{!! Lang::get("core.add_new_continue") !!}.</u></span>
                                                        <div class="sad-mouse">
                                                            <img src="https://res.cloudinary.com/swiggy/image/upload/f_auto,fl_lossy,q_auto/v1462170280/Group_9-1_m13j7t">
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="add-notes col-xs-12 nopadding" ng-if="vm.orderCommentsEnabled">
                                                <h5>{!! Lang::get("core.order_notes") !!}:</h5>
                                                <h6>{!! Lang::get("core.wish_share") !!}?</h6>
                                                <div class="comment-box-wrapper">
                                                    <textarea rows="3" maxlength="100" class="form-control" placeholder="{!! Lang::get('core.more_sugar') !!}."></textarea>
                                                    <span class="char-count" id="char_note">100 {!! Lang::get("core.chars") !!}</span>
                                                </div>
                                            </div>
                                            <div class="user_address col-xs-12 nopadding">
                                            @if(count($address) > 0)
                                              @foreach($address as $i=>$addr)
                                             <?php if($uaddr->address_type == '1'){
                                                $add_type= "Home";
                                                //$icon = '<i class="fa fa-home"></i>';
                                              }else if($uaddr->address_type == '2'){
                                                $add_type= "Work";
                                                //$icon = '<i class="fa fa-briefcase"></i>';
                                              }
                                              else{
                                                $add_type= "Others";
                                                //$icon = '<i class="fa fa-book"></i>';
                                              }  ?>
                                                <div class="desktop clearfix">
                                                  <div class="left">
                                                      <?php echo $icon; ?>
                                                      <h6 class="text-ellipsis">{{$add_type}}</h6>
                                                  </div>
                                                  <div class="middle">
                                                      <address>
                                                          <span class="addr-line">{{$addr->building}}{{$addr->landmark}}{{$addr->address}} </span>
                                                      </address>
                                                  </div>
                                                  <div class="right">
                                                      <div class="checkbox">
                                                          <label>
                                                              <input type="radio" name="address" id="address"  value="{{$addr->id}}">
                                                          </label>
                                                      </div>
                                                  </div>
                                              </div>
                                              @endforeach
                                              @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-offset-1 col-md-9 xs-pad continue_payment">
                                          <button class="btn btn-primary btn-lg btn_show_payment">{!! Lang::get("core.continue_payment") !!}</button>
                                          <em class="invalid" style="display:none;">{!! Lang::get("core.delivery_error") !!}.</em>
                                        </div>



                                    </div>
                                </div>

                            </div>
                                
                              </div>
                          </section>
                      </div>

                      <div class="col-xs-12 no-pad">
                        <section class="block-unit block-summary detailed">
                            <header>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-sm-1 col-xs-2">
                                            <span class="number">2</span>
                                        </div>
                                        <div class="no-left-pad col-sm-7 col-xs-10">
                                            <h5>{!! Lang::get("core.payment_method") !!}</h5>
                                            <h6 class="text-ellipsis">{!! Lang::get("core.wish_pay") !!}?</h6>
                                        </div>
                                    </div>
                                </div>
                            </header>

                             <div class="payment_option" style="display:none;">


                                  <div class="col-md-4 pay_section">
                                  <input id="paypal" class="pay_via" type="radio" name="pay_via" value="paypal" checked>

                                  <label for="paypal" class="input_text_equl">{!! Lang::get("core.paypal") !!}
                                  <!-- <img src="<?php echo url();?>/paypal.png"> -->
                                  </label>
                                  </div>

                                  <div class="col-md-4 pay_section">
                                  <input id="creditcard" class="pay_via" type="radio" name="pay_via" value="credit" >
                                  <label for="creditcard" class="input_text_equl">{!! Lang::get("core.credit_payment") !!}
                                  <!-- <img src="<?php echo url();?>/creditcard.jpg">   -->
                                  </label>
                                  </div> 

                                  <div class="col-md-4 pay_section">
                                  <input id="cashon" class="pay_via" type="radio" name="pay_via" value="cashon" >
                                  <label for="cashon" class="input_text_equl">{!! Lang::get("core.cash_delivery") !!}
                                
                                  </label>
                                  </div> 


                                <div class="paypal_selection col-xs-12 text-center">


                                <form name='frm_paypal' id="frm_paypal" action="<?php echo url(); ?>/payment/payment" method="post">
                                <input type="hidden" name="hidden_paypal_order_price" id="hidden_paypal_order_price" value="">

                                <input class="address_id" name="address_id" type="hidden" >

                                <input class="btn-orange " class="order_place" type="submit" value="{!! Lang::get('core.confirm_order') !!}">

                                </form>

                                </div>


                                <div class="credit_selection col-xs-12 text-center" style="display:none" >
                                <script src="https://www.2checkout.com/static/checkout/javascript/direct.min.js"></script>
                                <form action='https://sandbox.2checkout.com/checkout/purchase' name="great" id="frm_credit" method='post'>

                                <input type='hidden' name='sid' value='901350888' />
                                <input type='hidden' name='mode' value='2CO' />
                                <input type='hidden' name='li_0_type' value='product' />
                                <input type='hidden' name='li_0_name' class='li_0_name' value='' />
                                <input type='hidden' name='li_0_price' id='checkout_amount' value='' />
                                <input type='hidden' name='li_0_tangible' value='Y' />
                                <input type='hidden' name='li_1_type' value='shipping' />
                                <input type='hidden' name='li_1_name' value='Express Shipping' />

                                <input type='hidden' name='li_1_price'  value='0' />
                                <input type='hidden' name='checkout_total_dues' id='checkout_total_dues' value='' />
                                <input type='hidden' name='checkout_total_price' id='checkout_total_price' value='100' />
                                <input type="hidden" name="order_id" id="orderid" value="">
                                <input type="hidden" name="currency_code"  value="USD">
                                <input class="btn-orange " class="order_place" type="submit" value="Confirm Order">

                                <input class="address_id" name="address_id" type="hidden" >

                                <!--  <input type="button" id="temp_credit_submit" value="Place Your Order"> -->

                                </form>
                                </div>

                                <div class="cash_on_deliv col-xs-12 text-center" style="display:none">
                                   <form name='frm_cash_delivery' id="frm_cash_delivery fgdf" action="<?php echo url(); ?>/payment/delivery" method="post">

                                     <input class="address_id" name="address_id" type="hidden" >

                                     <input class="btn-orange " class="order_place" type="submit" value="{!! Lang::get('core.confirm_order') !!}">
                          

                                   </form>

                                </div>

                            
                           </div>
                        </section>
                      </div>

                  </div>
              </div>
          </div>
          <div class="col-sm-4 right_content_payment">
            <div class="right_column">
              <aside class="panel panel-body panel-details sjob inner-box">
              <h6>{!! Lang::get("core.your_cart") !!}</h6>
              <span><i class="fa fa-clock-o" aria-hidden="true"></i>{!! Lang::get("core.estimate_time") !!}:- {{$restaurant->delivery_time}} {!! Lang::get("core.minutes") !!}</span>
              </aside>
              <div class="panel panel-body panel-details sjob  menu_cart">
                 <?php echo $cart_items_html; ?>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      <!-- Product Info End -->
    </div>
  </div>         
</div>
<input type="hidden" name="latitude" id="lat" value="{{$restaurant->latitude}}">
<input type="hidden" name="longitude" id="lang" value="{{$restaurant->longitude}}">
<input type="hidden" name="addr" id="addr" value="{{$restaurant->location}}">

<input type="hidden" value="{{$restaurant->id}}" id="res_id" name="res_id" />


<!-- End Content -->
<script type="text/javascript">
$(document).on('keyup','textarea',function() {
  textarearestrict($(this));
});
function textarearestrict(arg) {
  var maxLength = 100;
  var tlength   = arg.val().length;
  if(tlength < maxLength){
    $('#char_note').text(tlength+'/'+maxLength+' Max').css("color","#D5473B;");
  } else {
    $('#char_note').text(tlength+'/'+maxLength+' Max').css("color","red");
  }
}

$(document).ready(function()
{
$(document).on("change",'.user_address .right .checkbox input',function(){
      if ($(this).is(":checked")) 
      {
        $(".user_address .desktop").removeClass("active_mode")
        $(this).closest('.user_address .desktop').addClass("active_mode");
      }
    })
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
        url: '<?php echo url(); ?>/frontend/addtotcart',
        type: "GET",
        data: {'item':item,'res_id':res_id,qty:qty,key:"checkout"},
        success: function(data){
          $('.menu_cart').html(data);
        }
      });
}


function remove_from_cart(item,qty){
  var res_id = $("#res_id").val();

  $.ajax({
        url: '<?php echo url(); ?>/frontend/removefromcart',
        type: "GET",
        data: {'item':item,'res_id':res_id,qty:qty,key:"checkout"},
        success: function(data){
          var result = data.html;
          var Cart = data.cart;
          if(Cart == 'empty'){
            $(".btn_show_payment").attr("disabled","disabled");
            $(".menu_cart").html('<div class="empty_check_cart"><div>Your cart is empty</div><div><input type="button" class="btn btn-checkout" name="checkout_cart_empty_btn" id="checkout_cart_empty_btn" value="Go to Restautant page"></div></div>');
            //window.location.href = base_url+"frontend/details/"+res_id;
          } else {
            $('.menu_cart').html(result);
          }
        }
      });
}
$(document).on('click',"#checkout_cart_empty_btn",function(){
  var res_id = $("#res_id").val();
 window.location.href = base_url+"frontend/details/"+res_id;
})

$(document).on("click",'.btn_show_payment',function(){

    var address = document.getElementById('address');

    if(address === null)
    {
      alert('{!! Lang::get("core.address_first") !!}');
      //$(this).next().show();
      return false;

    }else{
      var address_id = $("input[name='address']:checked").val();  
      if(address_id==undefined){

      //alert('Please Choose Address');
      $(this).next().show();
      return false;

      }else{
        var res_id = $('#restaurant_id').val(); 
        var check = $('.checkout_payment').html();
        var add_finder = false;
        $.ajax({
          url: '<?php echo url(); ?>/frontend/checkneareraddress',
          type: "GET",
          async:false,
          dataType:"json",
          data: {'address_id':address_id,'res_id':res_id},
          success: function(data){
            if(data == 2){
              window.location.href = base_url+"user/login";
            } else if(data==1) {
               add_finder=true;
            }               
          }
        });
        
        if(add_finder==true){
          $(".checkout-page .checkout-item-block .block-unit .content").slideToggle(500);

          $(".checkout-page .checkout-item-block .block-summary").addClass('block-error');

          $(".edit_delivery").show();

          $(this).next().hide();
          
          $('#hidden_paypal_order_price').val(check);

          $('#checkout_amount').val(check);

          $('#checkout_total_price').val(check);
          
          $('.payment_option').slideToggle(500);

          $('.address_id').val(address_id);
        }else{
           alert("{!! Lang::get('core.far_address') !!}");
        }

      }

  
    } 
      

});

$(".edit_delivery").click(function()
{
  $(this).hide();
  $(".checkout-page .checkout-item-block .block-unit .content").slideToggle(500);
  $('.payment_option').slideToggle(500);
})


$('#frm_credit').submit(function(){

   var address_id =$('.address_id').val();

    var ret = false;

      $.ajax({
        url: '<?php echo url(); ?>/payment/twocheckout',
        type: "POST",
        async:false,
        data: {'address_id':address_id},
        dataType:'json',
        success: function(data){
      
           $('#order_id').val(data.order_id);
           $('#checkout_amount').val(data.total);
           $('#checkout_total_price').val(data.total);

           ret =true;
        
        }
      });

      return ret;    

});

$(document).ready(function() {
    $('input[type=radio][name=pay_via]').change(function() {

        if (this.value == 'paypal') {
             
             $('.paypal_selection').show();
             $('.credit_selection').hide();
             $('.cash_on_deliv').hide();
             
           
        }
        else if (this.value == 'credit') {

            $('.paypal_selection').hide();
            $('.credit_selection').show();
            $('.cash_on_deliv').hide();
           
        }else{
            
            $('.cash_on_deliv').show();
            $('.paypal_selection').hide();
            $('.credit_selection').hide(); 


        }

    });

    $(window).scroll(function(){
      $.browser.chrome = $.browser.webkit && !!window.chrome;  
      $.browser.safari = $.browser.webkit && !window.chrome;  
      if ($.browser.chrome || $.browser.safari)
      {var window_size = $(window).width() + 17;}
      else
      {var window_size = $(window).width();}

      if (window_size >= 991)
      {
        var start_host_top = $('.right_content_payment').offset().top;
          var scroll = $(window).scrollTop();
          if (scroll >= start_host_top){ $('.right_column').addClass('fixe');}
          else{$('.right_column').removeClass('fixe');}
      }
    });

});

</script>

<script src="http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key=AIzaSyDIHoHvIosxw4wz4bDEKzfcPzCPFmPA5rw"></script>
<!-- Modal -->
<div id="map_modal" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <style>#myaddrMap {  height: 350px;width: 100%;z-index:999999;}</style>
    <form role="form" action="" method="post" id="address_form">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title pull-left">{!! Lang::get("core.add_new_address") !!}</h4>

             <span class="step pull-right">{!! Lang::get("core.step") !!} 1 {!! Lang::get("core.of") !!} 2</span>
              <h6 class="disblock">{!! Lang::get("core.help_map") !!}.

</h6>

          </div>
          <div class="modal-body nopadding">
            <div class="alert_fn"></div>
            <div class="step1">
                <div id="myaddrMap"></div><br/>
            </div>
             <div class="step2" style="display:none;">
                <div class="no-pad" ng-show="vm.newAddressStep == '2'" style="">
                    <div class="col-xs-12 col-sm-7 no-pad static-map">
                        
                    </div>
                    <div class="col-xs-12 col-sm-5">
                      <div class="address_values">
                        <div class="group has-value" >
                            <h6>{!! Lang::get("core.address_details") !!}</h6>
                            <input disabled  id="location" name="location"  value="">
                        </div>
                        <div class="group">
                            <input name="building" required value="" id="building" >
                            <label>{!! Lang::get("core.build_flat") !!}(*)</label>
                        </div>
                        <div class="group" >
                            <input name="landmark" id="landmark" required >
                            <label>{!! Lang::get("core.add_landmark") !!} (*)</label>
                        </div>
                        </div>
                        <div class="group save_adrs" >
                        <h6 class="">{!! Lang::get("core.save_address_as") !!}: </h6>
                        <div class="annotation" >
                            <div class="checkbox">
                                <label>
                                    <i class="fa fa-home"></i>{!! Lang::get("core.home") !!}
                                    <input type="radio" name="address_type" id="address_type" required value="1">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                         <div class="annotation" >
                            <div class="checkbox">
                                <label>
                                    <i class="fa fa-briefcase "></i>{!! Lang::get("core.work") !!}
                                    <input type="radio" name="address_type" id="address_type" required value="2">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                         <div class="annotation" >
                            <div class="checkbox">
                                <label>
                                    <i class="fa fa-book"></i>{!! Lang::get("core.others") !!}
                                    <input type="radio" name="address_type" id="address_type" required value="3">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                       </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="a_lat" id="a_lat" value="{{$restaurant->latitude}}">
            <input type="hidden" name="a_lang" id="a_lang" value="{{$restaurant->longitude}}">
            <input type="hidden" name="a_addr" id="a_addr" value="{{$restaurant->location}}">
        </div>
          <div class="modal-footer" style="overflow:hidden;padding:15px;">
          <button type="button" class="btn btn-orange back_to_step flleft" style="display:none;" >{!! Lang::get("core.btn_back") !!}</button>
            <button type="button" class="btn btn-orange go_to_step" disabled >{!! Lang::get("core.continue") !!}</button>
            <button type="submit" class="btn btn-orange save_address" style="display:none;" >{!! Lang::get("core.save_address") !!}</button>
          </div>
      </div>
      </form>
  </div>
</div>
<script type="text/javascript">
  $(".fn_map_modal").click(function(){
    initialize();
     setTimeout(function(){ resizingMap() }, 1000);
    $("#map_modal").modal("show");

  });

  $(".address_values input").keyup(function()
  {
    var v = $(this).val();
    if(v != '')
    {
      $(this).next().addClass('still');
    }
    else
    {
      $(this).next().removeClass('still');
    }
  })
  
    var map;
    var marker;
    var myLatlng = new google.maps.LatLng($('#lat').val(),$('#lang').val());
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();
    function initialize(){
        var mapOptions = {
            zoom: 15,
            center: new google.maps.LatLng($('#lat').val(),$('#lang').val()),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
       
        map = new google.maps.Map(document.getElementById("myaddrMap"), mapOptions);
        
        marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng($('#lat').val(),$('#lang').val()),
            draggable: true 
        });     
        
        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#a_addr').val(results[0].formatted_address);
                    $('#a_lat').val(marker.getPosition().lat());
                    $('#a_lang').val(marker.getPosition().lng());
                   infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });

                       
        google.maps.event.addListener(marker, 'dragend', function() {

        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#a_addr').val(results[0].formatted_address);
                    $('#a_lat').val(marker.getPosition().lat());
                    $('#a_lang').val(marker.getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                    address_check();
                }
            }
        });
    });
        google.maps.event.addListener(map, 'click', function (event) {
           
            // console.log(event);
            placeMarker(event.latLng);
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#a_addr').val(results[0].formatted_address);
                        $('#a_lat').val(marker.getPosition().lat());
                        $('#a_lang').val(marker.getPosition().lng());
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                        address_check();
                    }
                }
            });
         });
    
    }
    google.maps.event.addDomListener(window, "resize", resizingMap());

  $('#map_modal').on('show.bs.modal', function() {
     resizeMap();
  })
  
  function resizeMap() {
     if(typeof map =="undefined") return;
     setTimeout( function(){resizingMap();} , 400);
  }

  function resizingMap() {
     if(typeof map =="undefined") return;
     var center = new google.maps.LatLng($('#lat').val(),$('#lang').val());
     google.maps.event.trigger(map, "resize");
     map.setCenter(center); 
  }
    
    function placeMarker(location) {
        if (marker == undefined){
            marker = new google.maps.Marker({
                position: location,
                map: map, 
                animation: google.maps.Animation.DROP,
            });
        } else {
            marker.setPosition(location);
        }
        map.setCenter(location);
    }

    function address_check(){
      var addr = $('#a_addr').val();
      var from = $('#addr').val();
      var lat = $('#a_lat').val();
      var lang = $('#a_lang').val();
      $.ajax({
        url: '<?php echo url(); ?>/frontend/checkaddress',
        type: "GET",
        data: {addr:addr,lat:lat,lang:lang,from:from},
        success: function(data){
          if(data == 1){
            $(".go_to_step").removeAttr("disabled");
            $("#location").val(addr);
            $("alert_fn").html('');
            saveMapToDataUrl(addr,lat,lang);
           // mapeado(lat,lang);
          }else{
            $(".go_to_step").attr("disabled",'disabled');
            $("#location").val(addr);
            var alert = '<div class="clearfix"></div><div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>  {!! Lang::get("core.far_address_reataurent") !!}</div>';
            $('.alert_fn').html(alert);
          }
          
        }
      });
    }
    function saveMapToDataUrl(addr,lat,lang) {



      var dataUrl = " https://maps.googleapis.com/maps/api/staticmap?center="+lat+","+lang+"&zoom=13&size=400x400&markers=color:blue%7Clabel:S%7C11211%7C11206%7C11222&key=AIzaSyABZOr8aXydg0HXJ4zHyEElSjlWDFcXMnA";
          $(".static-map").html('<img src="' + dataUrl + '"/>');
      }
    $('.go_to_step').click(function(){
        $('.step1,.go_to_step').hide();
        $('.step2,.back_to_step,.save_address').show();
        $(".step").text('{!! Lang::get("core.step") !!} 2 {!! Lang::get("core.of") !!} 2');
    });
     $('.back_to_step').click(function(){
        $('.step1,.go_to_step').show();
        $('.step2,.back_to_step,.save_address').hide();
        $(".step").text('{!! Lang::get("core.step") !!} 1 {!! Lang::get("core.of") !!} 2');
    });



  $("#address_form").validate({
    // Rules for form validation
    rules:
    {
        building:
        {
            required: true,
           
        },
        landmark:
        {
            required: true,
           
        },
         address_type:
        {
            required: true,
           
        }
    },
                        
    // Messages for form validation
    messages:
    {
        building:
        {
            required: '{!! Lang::get("core.enter_building") !!}',
        },
        landmark:
        {
            required: '{!! Lang::get("core.enter_landmark") !!}'
        },
        address_type:
        {
            required: '{!! Lang::get("core.enter_address") !!}'
        }
    },                  
    submitHandler: function(form) {
        var purl = "{{ url('/')}}/frontend/addaddress";
        
        $.ajax({
            url: purl,
            type: 'post',
            data:  $('#address_form').serialize(),
            success: function(data) {
                if(data != ''){
                  $("#map_modal").modal("hide");
                    $(".user_address").html(data);
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

  /*$(document).on('change',"#address",function(){
    var address_id = $("input[name='address']:checked").val();
  })*/
</script>
   
    <style>
    body
    {
      background-color: #f6f6f6;
    }
    </style>
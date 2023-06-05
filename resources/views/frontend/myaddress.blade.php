<div class="account_details col-md-8 col-xs-12 nopadding">
<div class="smallHeading swiggyGray">{!! Lang::get('core.your_saved_address') !!}</div>
<div class="user_address">
@if(count($address) > 0)
  @foreach($address as $i=>$addr)
 <?php if($addr->address_type == '1'){
    $add_type= "{!! Lang::get('core.home') !!}";
    $icon = '<i class="fa fa-home"></i>';
  }else if($addr->address_type == '2'){
    $add_type= "{!! Lang::get('core.work') !!}";
    $icon = '<i class="fa fa-briefcase"></i>';
  }
  else{
    $add_type= "Others";
    $icon = '<i class="fa fa-book"></i>';
  }  ?>
    <div class="desktop clearfix fn_<?php echo $addr->id; ?>" >
      <div class="left">
          <span class="annotation"><?php echo $icon; ?></span>
          <h6 class="text-ellipsis">{{$add_type}}</h6>
      </div>

      <div class="actions">
          <a href="javascript:edit(<?php echo $addr->id; ?>);" class="bootstrap-link edit_address" ><i class="fa fa-pencil"></i>&nbsp; {!! Lang::get('core.btn_edit') !!}</a>
          <a  class="bootstrap-link  del_address" href="javascript:remove(<?php echo $addr->id; ?>);" ><i class="fa fa-trash"></i>&nbsp; {!! Lang::get('core.btn_delete') !!}</a>
      </div>
      <div class="middle">
            <span class="addr-line addressBlock">{{$addr->building}}{{$addr->landmark}}{{$addr->address}} </span>
      </div>
  </div>
  <div class="clearfix"></div>
  @endforeach
  @else 
    <div class="text-center">
      {!! trans('core.pro_you_dont_have_adrs') !!}
    </div>
  @endif
  <div class="clearfix"></div>
</div>
</div>
<script type="text/javascript">
	function remove(id){

    	 if(confirm("{!! Lang::get('core.sure_delete') !!}")){
			var url = "{{ url('/')}}/user/address";
			$.ajax({
		          url: url,
		          type: "get",
		          data: {id:id,key:"delete"},
		          success: function(data){
		          	var alert = '<div class="clearfix"></div><div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>{!! Lang::get("core.success") !!}!</strong>  {!! Lang::get("core.delete_address") !!}.</div>';
		          	 $(".alert_fn").append(alert);
		          			 $(".alert-dismissable").fadeTo(2000, 500).slideUp(500, function(){
				                $(".alert-dismissable").alert('{!! Lang::get("core.close") !!}');
				            });		
		          	$(".fn_"+id).remove();
		          }
		                  
		    });
		}
		
	}
</script>
<script src="http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key=AIzaSyDIHoHvIosxw4wz4bDEKzfcPzCPFmPA5rw"></script>
<!-- Modal -->
<div id="map_modal" class="modal fade user_dash_address" role="dialog" >
  <div class="modal-dialog">
    <style>#myaddrMap {  height: 340px;z-index:999999;margin-bottom:7px;}</style>
    <form role="form" action="" method="post" id="address_form">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{!! Lang::get("core.edit_address") !!}</h4>
          </div>
          <div class="modal-body nopadding">
            <div class="alert_fn"></div>
           
             <div class="step2" >
                <div class="no-pad" >
                    <div class="col-xs-12 col-sm-7 no-pad ">
                        <div id="myaddrMap"></div>
                    </div>
                    <div class="col-xs-12 col-sm-5">
                        <div class="address_values">
                          <div class="group has-value" >
                              <input disabled  id="location" name="location"  value="">
                              <label>{!! Lang::get("core.address") !!}</label>
                          </div>
                          <div class="group">
                              <input name="building" required value="" id="building" >
                              <label>{!! Lang::get("core.build_flat") !!}</label>
                          </div>
                          <div class="group" >
                              <input name="landmark" id="landmark" required >
                              <label>{!! Lang::get("core.landmark") !!}</label>
                          </div>
                        </div>
                        <div class="group save_adrs" >
                        <h6>{!! Lang::get("core.save_address_as") !!}: </h6>
                        <div class="annotation" >
                            <div class="checkbox">
                                <label>
                                    <i class="fa fa-home"></i>
                                    <span class="choice-text">{!! Lang::get("core.home") !!}</span>
                                    <input type="radio" name="address_type" id="address_type" required value="1">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                         <div class="annotation" >
                            <div class="checkbox">
                                <label>
                                    <i class="fa fa-briefcase "></i>
                                    <span class="choice-text">{!! Lang::get("core.work") !!}</span>
                                    <input type="radio" name="address_type" id="address_type" required value="2">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                         <div class="annotation" >
                            <div class="checkbox">
                                <label>
                                    <i class="fa fa-book"></i>
                                    <span class="choice-text">{!! Lang::get("core.others") !!}</span>
                                    <input type="radio" name="address_type" id="address_type" required value="3">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                       </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="a_lat" id="a_lat" value="">
            <input type="hidden" name="a_lang" id="a_lang" value="">
            <input type="hidden" name="a_addr" id="a_addr" value="">
        </div>
          <div class="modal-footer" style="overflow:hidden;padding:15px;">
           <a href="javascript:void(0)" class="pull-left linkCancel" data-dismiss="modal">{!! Lang::get("core.sb_cancel") !!}</a>
           <button type="submit" class="btn btnUpdate save_address" >{!! Lang::get("core.save_address") !!}</button>
          </div>
      </div>
      </form>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function()
{

})

 	function edit(id){
 		var url = "{{ url('/')}}/user/address";
 		$.ajax({
          url: url,
          type: "get",
          dataType: "json",
          data: {id:id,key:"edit"},
          success: function(data){
          	$('#id').val(data.id);
          	$('#a_lat').val(data.lat);
          	$('#a_lang').val(data.lang);
          	$('#location').val(data.address);
          	$('#a_addr').val(data.address);
          	$('#building').val(data.building);
          	$('#landmark').val(data.landmark);
          	$('input:radio[name="address_type"]').filter('[value="'+data.address_type+'"]').attr('checked', true);
      		initialize();
	     	setTimeout(function(){ resizingMap() }, 1000);
	    	$("#map_modal").modal("show");

            $(".address_values input").each(function()
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
          }
                  
    });
	 
	
 	}
   
 
  
    var map;
    var marker;
    var myLatlng = new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val());
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();
    function initialize(){
        var mapOptions = {
            zoom: 15,
            center: new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val()),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
       
        map = new google.maps.Map(document.getElementById("myaddrMap"), mapOptions);
        
        marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val()),
            draggable: true 
        });     
        
        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#a_addr').val(results[0].formatted_address);
                    $('#location').val(results[0].formatted_address);
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
                    $('#location').val(results[0].formatted_address);
                    $('#a_lat').val(marker.getPosition().lat());
                    $('#a_lang').val(marker.getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                   
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
                        $('#location').val(results[0].formatted_address);
                        $('#a_lat').val(marker.getPosition().lat());
                        $('#a_lang').val(marker.getPosition().lng());
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                        
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
     var center = new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val());
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

    
    function saveMapToDataUrl(addr,lat,lang) {



      var dataUrl = " https://maps.googleapis.com/maps/api/staticmap?center="+lat+","+lang+"&zoom=13&size=400x400&markers=color:blue%7Clabel:S%7C11211%7C11206%7C11222&key=AIzaSyABZOr8aXydg0HXJ4zHyEElSjlWDFcXMnA";
          $(".static-map").html('<img src="' + dataUrl + '"/>');
      }
   

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
        var purl = "{{ url('/')}}/frontend/updateaddress";
        var id = $('#address_form').find("#id").val();
        $.ajax({
            url: purl,
            type: 'post',
            data:  $('#address_form').serialize(),
            success: function(data) {
                if(data != ''){

                  	$("#map_modal").modal("hide");
                    $(".fn_"+id).html(data);
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
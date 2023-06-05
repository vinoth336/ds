@extends('layouts.app')

@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('deliverypoint?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{!! Lang::get('core.addedit') !!} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'deliverypoint/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Deliverypoint</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  
                              
								  <div class="form-group  " >
									<label for="Name" class=" control-label col-md-4 text-left"> Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'','required'=>'true'    )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  
                                  <input type="hidden" name="latitude" id="lat" value="{{$row['latitude']}}">
			                      <input type="hidden" name="longitude" id="lang" value="{{$row['longitude']}}">
								  <div style="clear:both"></div>	
                                  
                                  <div class="form-group  " >
                                    <label for="Location" class=" control-label col-md-4 text-left"> Location <span class="asterix"> * </span></label>
                                    <div class="col-md-6">
                                        {!! Form::text('location', $row['location'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true' ,'id'=>'txtPlaces' )) !!} 
                                        <div id="fn_map" style="display: none;"><a href="javascript:" class="fn_map_modal">Click Here to view Exact location</a></div>
                                    </div> 
                                    <div class="col-md-2"></div>
                                </div>
                                  					
								  <div class="form-group  " >
                                    <label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name='region' rows='9' id='region' class='select2 region' required >
                                          <option value="">Select</option>
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                  </div> 
                                  
                                  <div class="form-group  " >
									<label for="Pincode" class=" control-label col-md-4 text-left"> Pincode <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('pin_code', $row['pin_code'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>                                   
                                  <div class="form-group  " >
                                        <label for="Active" class=" control-label col-md-4 text-left"> Active </label>
                                        <div class="col-md-6">                                          
                                            <label class='radio radio-inline'>
                                            <input type='radio' name='status' value ='1'  @if($row['status'] == '1') checked="checked" @endif > {!! trans('core.abs_active') !!} </label>
                                            <label class='radio radio-inline'>
                                            <input type='radio' name='status' value ='0'  @if($row['status'] == '0') checked="checked" @endif > {!! trans('core.fr_minactive') !!} </label>
                                     	</div> 
                                        <div class="col-md-2"></div>
									</div>
                                  
                                  </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<a href="{{url('deliverypoint')}}"><button type="button" class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i> {!! Lang::get('core.sb_cancel') !!} </button></a>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>
			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		<?php if(session()->get('gid') == '7'){ ?>	
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! session()->get('rid') !!}' });
				 $('#region').attr('readonly', true);
		<?php }else{ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! $row["region"] !!}' });		
		<?php } ?>
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	
	</script>	 
	<script src="https://maps.google.com/maps/api/js?libraries=places&region=in&language=en&sensor=true&key=AIzaSyD81fsN0Uc2eEJ1cCrSeWjZEYI81XbvIFU"></script>

	<!-- Modal -->
	<div id="map_modal" class="modal fade" role="dialog" >
		<div class="modal-dialog">
			<style>#myMap {max-width:100%;height: 350px;width: 520px;z-index:999999;}</style>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Map</h4>
				</div>
				<div class="modal-body">
					<div id="myMap"></div><br/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    	<script>	
	       var input = document.getElementById('txtPlaces');
		   var autocomplete = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
			 // alert(autocomplete);

			google.maps.event.addListener(autocomplete, 'place_changed', function () {
				var place		= autocomplete.getPlace();
				var latitude	= place.geometry.location.lat();
				var longitude	= place.geometry.location.lng();
	           // alert(latitude);
	            $('#lat').val(latitude);
	            $('#lang').val(longitude);
	            IsplaceChange	= true;
	            if($('#lat').val() != '' || $('#lang').val() != '' ){
	            	$('#fn_map').show();
	            } else {
	            	$('#fn_map').hide();
	            }
	        });
			
			

			$("#txtPlaces").keydown(function () {
				IsplaceChange	= false;
			});
		    // alert(IsplaceChange);
		    $("#txtPlaces").focusout(function () {	    
		    	if (IsplaceChange) {
		    		$('#fn_map').show();
		        	// alert("fdsg"+$('#lat').val()+$('#lang').val());
		        } else {
		        	$('#lat').val('');
		        	$('#lang').val('');
		        	$("#txtPlaces").val('');
		        	$('#fn_map').hide();
		            // alert("please Enter valid location");
		        }

		    });
		</script>	
		
    <script>
	
	$(".fn_map_modal").click(function(){
		//alert();
			initialize();
			$("#map_modal").modal("show");
		});

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

			map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

			marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng($('#lat').val(),$('#lang').val()),
				draggable: true 
			});     

			geocoder.geocode({'latLng': myLatlng }, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#txtPlaces').val(results[0].formatted_address);
						$('#lat').val(marker.getPosition().lat());
						$('#lang').val(marker.getPosition().lng());
                   // infowindow.setContent(results[0].formatted_address);
                    //infowindow.open(map, marker);
                }
            }
        });


			google.maps.event.addListener(marker, 'dragend', function() {

				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$('#txtPlaces').val(results[0].formatted_address);
							$('#lat').val(marker.getPosition().lat());
							$('#lang').val(marker.getPosition().lng());
                   // infowindow.setContent(results[0].formatted_address);
                   // infowindow.open(map, marker);
               }
           }
       });
			});
			google.maps.event.addListener(map, 'click', function (event) {
				$('#mlatitude').val(event.latLng.lat());
				$('#mlongitude').val(event.latLng.lng());
            // console.log(event);
            placeMarker(event.latLng);
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            	if (status == google.maps.GeocoderStatus.OK) {
            		if (results[0]) {
            			$('#txtPlaces').val(results[0].formatted_address);
            			$('#lat').val(marker.getPosition().lat());
            			$('#lang').val(marker.getPosition().lng());
                       // infowindow.setContent(results[0].formatted_address);
                       // infowindow.open(map, marker);
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
		google.maps.event.addDomListener(window, 'load', initialize);
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
	</script>		 
@stop
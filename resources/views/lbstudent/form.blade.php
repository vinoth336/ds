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
		<li><a href="{{ URL::to('lbstudent?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'lbstudent/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> lbstudent</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Cust Id" class=" control-label col-md-4 text-left"> Cust Id </label>
									<div class="col-md-6">
									  {!! Form::text('cust_id', $row['cust_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="User Id" class=" control-label col-md-4 text-left"> User Id </label>
									<div class="col-md-6">
									  {!! Form::text('user_id', $row['user_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Stud Name" class=" control-label col-md-4 text-left"> Stud Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('stud_name', $row['stud_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Standard" class=" control-label col-md-4 text-left"> Standard <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('standard', $row['standard'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Section" class=" control-label col-md-4 text-left"> Section <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('section', $row['section'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Dob" class=" control-label col-md-4 text-left"> Dob <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('dob', $row['dob'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 	
                                  <div id="datepairExample" >				
								  <div class="form-group  " >
									<label for="Pickup Time" class=" control-label col-md-4 text-left"> Pickup Time <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('pickup_time', $row['pickup_time'],array('class'=>'form-control time', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Return Time" class=" control-label col-md-4 text-left"> Return Time </label>
									<div class="col-md-6">
									  {!! Form::text('return_time', $row['return_time'],array('class'=>'form-control time', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Permanent Address" class=" control-label col-md-4 text-left"> Permanent Address <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('permanent_address', $row['permanent_address'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'id'=>'txtPlaces'  )) !!} 
                                      <div id="fn_map" style="display: none;"><a href="javascript:" class="fn_map_modal">Click Here to view Exact location</a></div>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                               
                                  					
								  <div class="form-group  " >
									<label for="Pickup Address" class=" control-label col-md-4 text-left"> Pickup Address <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('pickup_address', $row['pickup_address'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true','id'=>'txtPlaces1'  )) !!} 
                                      <div id="fn_map1" style="display: none;"><a href="javascript:" class="fn_map_modal1">Click Here to view Exact location</a></div>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis  " style="display:none;">
									<label for="Permanent Lat" class=" control-label col-md-4 text-left"> Permanent Lat </label>
									<div class="col-md-6">
									  {!! Form::text('permanent_lat', $row['permanent_lat'],array('class'=>'form-control', 'placeholder'=>'', 'id'=>'permanent_lat',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis" style="display:none;">
									<label for="Permanent Lang" class=" control-label col-md-4 text-left"> Permanent Lang </label>
									<div class="col-md-6">
									  {!! Form::text('permanent_lang', $row['permanent_lang'],array('class'=>'form-control', 'placeholder'=>'', 'id'=>'permanent_lang',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Pickup Lat" class=" control-label col-md-4 text-left"> Pickup Lat </label>
									<div class="col-md-6">
									  {!! Form::text('pickup_lat', $row['pickup_lat'],array('class'=>'form-control', 'placeholder'=>'', 'id'=>'pickup_lat',    )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>
                                  
                                 
								  <div class="form-group hidethis " style="display:none;">
									<label for="Pickup Lang" class=" control-label col-md-4 text-left"> Pickup Lang </label>
									<div class="col-md-6">
									  {!! Form::text('pickup_lang', $row['pickup_lang'],array('class'=>'form-control', 'placeholder'=>'', 'id'=>'pickup_lang',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Permanent Pin Code" class=" control-label col-md-4 text-left"> Permanent Pin Code </label>
									<div class="col-md-6">
									  {!! Form::text('permanent_pin_code', $row['permanent_pin_code'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Pickup Pin Code" class=" control-label col-md-4 text-left"> Pickup Pin Code </label>
									<div class="col-md-6">
									  {!! Form::text('pickup_pin_code', $row['pickup_pin_code'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="School Name" class=" control-label col-md-4 text-left"> School Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  <select name='school_id' rows='5' id='school_id' class='select2 ' required  ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Subscription Planid" class=" control-label col-md-4 text-left"> Subscription Planid </label>
									<div class="col-md-6">
									  {!! Form::text('subscription_planid', $row['subscription_planid'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Subscription Plan" class=" control-label col-md-4 text-left"> Subscription Plan <span class="asterix"> * </span></label>
									<div class="col-md-6">
                                      <select name='subscription_plan' rows='5' id='subscription_plan' class='select2 ' required>
                                        <option value="" >-- Please Select --</option>
                                        <option value="weekly" @if($row['subscription_plan'] == 'weekly') selected="selected" @endif >Weekly</option>
                                        <option value="monthly" @if($row['subscription_plan'] == 'monthly') selected="selected" @endif>Monthly</option>                                     
                                     </select>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Plan From" class=" control-label col-md-4 text-left"> Plan From <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('plan_from', $row['plan_from'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Plan To" class=" control-label col-md-4 text-left"> Plan To <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('plan_to', $row['plan_to'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Type" class=" control-label col-md-4 text-left"> Delivery Type <span class="asterix"> * </span></label>
									<div class="col-md-6">
                                      <select name='delivery_type' rows='5' id='delivery_type' class='select2 ' required>
                                        <option value="" >-- Please Select --</option>
                                        <option value="pickup" @if($row['delivery_type'] == 'pickup') selected="selected" @endif >Pickup</option>
                                        <option value="pickupdrop" @if($row['delivery_type'] == 'pickupdrop') selected="selected" @endif>Pickup & Return</option>                                     
                                     </select>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Charge" class=" control-label col-md-4 text-left"> Delivery Charge <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('delivery_charge', $row['delivery_charge'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Total Price" class=" control-label col-md-4 text-left"> Total Price <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('total_price', $row['total_price'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>     
                                  
                                  <div class="form-group  " >
                                    <label for="Zone" class=" control-label col-md-4 text-left"> Zone <span class="asterix"> * </span></label>
                                    <div class="col-md-6">
                                      <select name='zone' rows='5' id='zone' class='select2 ' required  ></select>
                                     </div>
                                     <div class="col-md-2">
                                        
                                     </div>
                                  </div>
                                                               
								  <div class="form-group  " >
									<label for="No Pickup From" class=" control-label col-md-4 text-left"> No Pickup From </label>
									<div class="col-md-6">									  
                                        <div class="input-group m-b" style="width:150px !important;">
                                            <input class="form-control date" name="leave_date_from" type="text" value="<?php echo $stud_leave_date->leave_date_from; ?>">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div> 
									 </div> 
									 <div class="col-md-2">									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="No Pickup To" class=" control-label col-md-4 text-left"> No Pickup To </label>
									<div class="col-md-6">									  
                                        <div class="input-group m-b" style="width:150px !important;">
                                            <input class="form-control date" name="leave_date_to" type="text" value="<?php echo $stud_leave_date->leave_date_to; ?>">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div> 
									 </div> 
									 <div class="col-md-2">									 	
									 </div>
								  </div>					
								  <!--<div class="form-group  " >
									<label for="Highlight Colour For Address" class=" control-label col-md-4 text-left"> Highlight Colour For Address </label>
									<div class="col-md-6">
									  <select name='address_change_status' rows='5' id='address_change_status' class='select2 '>
                                      	<?php if($row['address_change_status'] == 0){
											echo $selected = "selected='selected'";
										} else {
											echo $selected = "";
										}?>
                                      	<option value="1" <?php echo $selected; ?>>ON</option>
                                        <option value="0" <?php echo $selected; ?>>OFF</option>
                                      </select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>-->
                                  
                                  
                                  </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<!--<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>-->
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('lbstudent?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
	<script type="text/javascript">
	var IsplaceChange = true;
	
	$(document).ready(function() { 
		
		$("#school_id").jCombo("{{ URL::to('lbstudent/comboselect?filter=delivery_point:id:name') }}",
		{  selected_value : '{{ $row["school_id"] }}' });
		
		$("#zone").jCombo("{{ URL::to('lbstudent/comboselect?filter=zone:id:name') }}",
        {  selected_value : '{{ $row["zone"] }}' }); 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
		
		if($('#permanent_lat').val() != '' || $('#permanent_lang').val() != '' ){
			$('#fn_map').show();
		} else {
			$('#fn_map').hide();
		}
		
		if($('#pickup_lat').val() != '' || $('#pickup_lang').val() != '' ){
			$('#fn_map1').show();
		} else {
			$('#fn_map1').hide();
		}
		/*var service = new google.maps.places.AutocompleteService();
		var result = service.getQueryPredictions({ input: 'madurai' });*/
		
		var input = document.getElementById('txtPlaces');
		//alert(input.value);
		var autocomplete1 = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
		//console.log(autocomplete);

		google.maps.event.addListener(autocomplete1, 'place_changed', function () {
			var place		= autocomplete1.getPlace();
			var latitude	= place.geometry.location.lat();
			var longitude	= place.geometry.location.lng();
			// alert(latitude);
			$('#permanent_lat').val(latitude);
			$('#permanent_lang').val(longitude);
			IsplaceChange	= true;
			if($('#permanent_lat').val() != '' || $('#permanent_lang').val() != '' ){
				$('#fn_map').show();
			} else {
				$('#fn_map').hide();
			}
		});
		
		var input1 = document.getElementById('txtPlaces1');
		//alert(input1.value);
		var autocomplete = new google.maps.places.Autocomplete(document.getElementById('txtPlaces1'));

		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var place		= autocomplete.getPlace();
			var latitude	= place.geometry.location.lat();
			var longitude	= place.geometry.location.lng();
			// alert(latitude);
			$('#pickup_lat').val(latitude);
			$('#pickup_lang').val(longitude);
			IsplaceChange	= true;
			if($('#pickup_lat').val() != '' || $('#pickup_lang').val() != '' ){
				$('#fn_map1').show();
			} else {
				$('#fn_map1').hide();
			}
		});

		$("#txtPlaces").keydown(function () {
			IsplaceChange	= false;
		});
		$("#txtPlaces1").keydown(function () {
			IsplaceChange	= false;
		});
		// alert(IsplaceChange);
		$("#txtPlaces").focusout(function () {
			//alert();
			if (IsplaceChange) {
				//alert("5");
				$('#fn_map').show();
				// alert("fdsg"+$('#lat').val()+$('#lang').val());
			} else {
				//alert("6");
				$('#permanent_lat').val('');
				$('#permanent_lang').val('');
				$("#txtPlaces").val('');
				$('#fn_map').hide();
				// alert("please Enter valid location");
			}

		});
		
		$('#permanent_address').click(function(){
		//alert("8");
		});
		$("#txtPlaces1").focusout(function () {	    
			if (IsplaceChange) {
				$('#fn_map1').show();
				//alert("fdsg"+$('#lat').val()+$('#lang').val());
			} else {
				$('#pickup_lat').val('');
				$('#pickup_lang').val('');
				$("#txtPlaces1").val('');
				$('#fn_map1').hide();
				// alert("please Enter valid location");
			}

		});
		
	});
	</script>	
	<script src="http://jonthornton.github.io/Datepair.js/dist/datepair.js"></script>
	<script src="http://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>
	<script>
		$('#datepairExample .time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:i:sa'
		});

		$('#datepairExample').datepair();
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
    
    
    <div id="map_modal1" class="modal fade" role="dialog" >
		<div class="modal-dialog">
			<style>#myMap1 {max-width:100%;height: 350px;width: 520px;z-index:999999;}</style>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Map</h4>
				</div>
				<div class="modal-body">
					<div id="myMap1"></div><br/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    <script>
		$(".fn_map_modal").click(function(){
				initialize();
				$("#map_modal").modal("show");
		});
	
		var map;
		var marker;
		var myLatlng1 = new google.maps.LatLng($('#permanent_lat').val(),$('#permanent_lang').val());
		//alert(myLatlng1);
		var geocoder = new google.maps.Geocoder();
		var infowindow = new google.maps.InfoWindow();
		function initialize(){
			//alert();
	
			var mapOptions = {
				zoom: 15,
				center: new google.maps.LatLng($('#permanent_lat').val(),$('#permanent_lang').val()),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
	
			map1 = new google.maps.Map(document.getElementById("myMap"), mapOptions);
	     
			marker = new google.maps.Marker({
				map: map1,
				position: new google.maps.LatLng($('#permanent_lat').val(),$('#permanent_lang').val()),
				draggable: true 
			});     
	
			geocoder.geocode({'latLng1': myLatlng1 }, function(results, status) {
				//alert("3");
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#txtPlaces').val(results[0].formatted_address);
						$('#permanent_lat').val(marker.getPosition().lat());
						$('#permanent_lang').val(marker.getPosition().lng());
						// infowindow.setContent(results[0].formatted_address);
						//infowindow.open(map, marker);
					}
				}
			});
	
			google.maps.event.addListener(marker, 'dragend', function() {
		//alert("1");
				geocoder1.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$('#txtPlaces').val(results[0].formatted_address);
							$('#permanent_lat').val(marker.getPosition().lat());
							$('#permanent_lang').val(marker.getPosition().lng());
							// infowindow.setContent(results[0].formatted_address);
							// infowindow.open(map, marker);
						}
					}
				});
			});
			google.maps.event.addListener(map, 'click', function (event) {
				//alert("2");
				$('#mlatitude').val(event.latLng.lat());
				$('#mlongitude').val(event.latLng.lng());
				 
				placeMarker(event.latLng);
				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$('#txtPlaces').val(results[0].formatted_address);
							$('#permanent_lat').val(marker.getPosition().lat());
							$('#permanent_lang').val(marker.getPosition().lng());
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
			var center1 = new google.maps.LatLng($('#permanent_lat').val(),$('#permanent_lang').val());
			google.maps.event.trigger(map, "resize");
			map.setCenter(center1); 
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		function placeMarker(permanent_address) {
			if (marker == undefined){
				marker = new google.maps.Marker({
					position: permanent_address,
					map: map, 
					animation: google.maps.Animation.DROP,
				});
			} else {
				marker.setPosition(permanent_address);
			}
			map.setCenter(permanent_address);
		}
		
		
		
		$(".fn_map_modal1").click(function(){
				initialize1();
				$("#map_modal1").modal("show");
		});
	
		var map;
		var marker;
		var myLatlng = new google.maps.LatLng($('#pickup_lat').val(),$('#pickup_lang').val());
		var geocoder = new google.maps.Geocoder();
		var infowindow = new google.maps.InfoWindow();
		function initialize1(){
	
			var mapOptions = {
				zoom: 15,
				center: new google.maps.LatLng($('#pickup_lat').val(),$('#pickup_lang').val()),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
	
			map = new google.maps.Map(document.getElementById("myMap1"), mapOptions);
	
			marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng($('#pickup_lat').val(),$('#pickup_lang').val()),
				draggable: true 
			});     
	
			geocoder.geocode({'latLng': myLatlng }, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#txtPlaces1').val(results[0].formatted_address);
						$('#pickup_lat').val(marker.getPosition().lat());
						$('#pickup_lang').val(marker.getPosition().lng());
						// infowindow.setContent(results[0].formatted_address);
						//infowindow.open(map, marker);
					}
				}
			});
	
			google.maps.event.addListener(marker, 'dragend', function() {
		
				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$('#txtPlaces1').val(results[0].formatted_address);
							$('#pickup_lat').val(marker.getPosition().lat());
							$('#pickup_lang').val(marker.getPosition().lng());
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
							$('#txtPlaces1').val(results[0].formatted_address);
							$('#pickup_lat').val(marker.getPosition().lat());
							$('#pickup_lang').val(marker.getPosition().lng());
						   // infowindow.setContent(results[0].formatted_address);
						   // infowindow.open(map, marker);
					   }
				   }
				});
			});
	
		}
		google.maps.event.addDomListener(window, "resize", resizingMap());
	
		$('#map_modal1').on('show.bs.modal', function() {
			resizeMap();
		})
	
		function resizeMap() {
			if(typeof map =="undefined") return;
			setTimeout( function(){resizingMap();} , 400);
		}
	
		function resizingMap() {
			if(typeof map =="undefined") return;
			var center = new google.maps.LatLng($('#permanent_lat').val(),$('#permanent_lang').val());
			google.maps.event.trigger(map, "resize");
			map.setCenter(center); 
		}
		google.maps.event.addDomListener(window, 'load', initialize1);
		function placeMarker(pickup_address) {
			if (marker == undefined){
				marker = new google.maps.Marker({
					position: pickup_address,
					map: map, 
					animation: google.maps.Animation.DROP,
				});
			} else {
				marker.setPosition(pickup_address);
			}
			map.setCenter(pickup_address);
		}	
	
	</script>
@stop
@extends('layouts.app')
<!--<meta http-equiv="refresh" content="10" />-->
@section('content')
<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
      height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
 </style>
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Delivery Boys Location </h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('deliveryboy?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> Delivery Boys Location </li>
      </ul>
	 </div>  
	
     <body style="margin:0px; padding:0px;" onLoad="initMap()">
   
 <div id="map" style="width: 97%; height: 80%; position:absolute;"></div>	
	<div class="sbox-content" style="background:#fff;"> 	

	</div>
</div>	

	
<script>

     var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(10.9585289,78.0735489),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('<?php echo \URL::to('') ?>/deliveryboy/mapdetails/', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label,
				
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
			  
	         var infowindow = new google.maps.InfoWindow({
             content: name,
            //  position: originalMapCenter
                 });
           infowindow.open(map,marker);
            });
          });
        }

      var myTimer =	setInterval(initMap, 30000);
      

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
     </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD81fsN0Uc2eEJ1cCrSeWjZEYI81XbvIFU&callback=initMap"></script>	  
@stop
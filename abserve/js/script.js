/**
 *
 * Crop Image While Uploading With jQuery
 * 
 * Copyright 2013, Resalat Haque
 * http://www.w3bees.com/
 *
 */
/*----- Old ----*/
// set info for cropping image using hidden fields
function setInfo(i, e) {
	$('#x').val(e.x1);
	$('#y').val(e.y1);
	$('#w').val(e.width);
	$('#h').val(e.height);
}

$(document).ready(function() {
	var p = $("#uploadPreview");

	// prepare instant preview
	$("#logo").change(function(){
		// fadeOut or hide preview
		p.fadeOut();

		// prepare HTML5 FileReader
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("logo").files[0]);

		oFReader.onload = function (oFREvent) {
			var pad_bottom_default_val = "2px";
	   		p.attr('src', oFREvent.target.result).fadeIn();
	   		p.css("padding-bottom", pad_bottom_default_val);
		};
	});

	// implement imgAreaSelect plug in (http://odyniec.net/projects/imgareaselect/)
	$('img#uploadPreview').imgAreaSelect({
		// set crop ratio (optional)
		aspectRatio: '1:1',
		onSelectEnd: setInfo
	});
});

/*----- New ----*/

	$('#imageUploadForm').on('submit',(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type:'POST',
			// url: $(this).attr('action'),
			url: base_url+"/restaurant/uploadphoto",
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			success:function(data){
				// console.log("success");
				// console.log(data);
				$('.upload_frame').html(data);
			},
			error: function(data){
				// console.log("error");
				// console.log(data);
				$('.upload_frame').html('there is an error');
			}
		});
	}));

// the target size
var TARGET_W = 400;
var TARGET_H = 400;

// show loader while uploading photo
function submit_photo() {
	// display the loading texte
	$('#loading_progress').html('<img src="'+base_url+'/loader.gif"> Processing');
}

// show_popup_crop : show the crop popup
function show_popup_crop(url) {
	// change the photo source
	$('#cropbox').attr('src', url);
	// destroy the Jcrop object to create a new one
	try {
		jcrop_api.destroy();
	} catch (e) {
		// object not defined
	}
	// Initialize the Jcrop using the TARGET_W and TARGET_H that initialized before
	$('#cropbox').Jcrop({
		aspectRatio	: TARGET_W / TARGET_H,
		setSelect	: [ 100, 100, TARGET_W, TARGET_H ],
		onSelect	: updateCoords
	},function(){
		jcrop_api = this;
	});

	// store the current uploaded photo url in a hidden input to use it later
	$('#photo_url').val(url);
	// hide and reset the upload popup
	$('#popup_upload').hide();
	$('#loading_progress').html('');
	$('#photo').val('');

	// show the crop popup
	$('#popup_crop').show();
}

// show_popup : show the popup
function show_popup(id) {
	// show the popup
	$('#'+id).show();
}

// close_popup : close the popup
function close_popup(id) {
	// hide the popup
	$('#'+id).hide();
}

// crop_photo : 
function crop_photo() {
	var x_ = $('#x').val();
	var y_ = $('#y').val();
	var w_ = $('#w').val();
	var h_ = $('#h').val();
	var photo_url_ = $('#photo_url').val();

	// hide thecrop  popup
	$('#popup_crop').hide();

	// display the loading texte
	$('#photo_container').html('<img src="'+base_url+'/loader.gif"> Processing...');
	// crop photo with a php file using ajax call
	$.ajax({
		url: base_url+'/restaurant/cropimage',
		type: 'POST',
		data: {x:x_, y:y_, w:w_, h:h_, photo_url:photo_url_, targ_w:TARGET_W, targ_h:TARGET_H},
		success:function(data){
			// display the croped photo
			$('#photo_container').html(data);
		}
	});
}

// updateCoords : updates hidden input values after every crop selection
function updateCoords(c) {
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
}
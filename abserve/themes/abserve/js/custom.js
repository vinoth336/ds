"use strict";
$(document).on('keydown','#subscribe_text',function(e){
    $('#validate-subscribe').html('');
    var currentVal = $(this).val();
    if(currentVal.length > 1){
        if(e.which == 13) {
            e.preventDefault();
            email_subscription(currentVal);
        }
    }
})

function email_subscription(email) {
    if(!(validateEmail(email))) {
        $('#validate-subscribe').html('Please add a valid email.');
    } else {
        sendSubscription(email,'emailSubscription');
    }
}

$(document).on('click','#subscribemail',function(e){
    alert();
    $('#validate-subscribe').html('');
    var currentVal = $('#subscribe_text').val();
    if(currentVal.length > 1){
        email_subscription(currentVal);
    }
})

function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
}

function sendSubscription(email,type) {
    $.ajax({
        url     : base_url+'/email/emailsubscription',
        data    : {email:email,type:type},
        type    : 'POST',
        dataType: 'json',
        success:function(data){
            if(data.message == 'success'){
               $('#validate-subscribe').html("Successfully Subscribed.");
            } else {
                $('#validate-subscribe').html("Oops!.. Something went wrong");
            }
            $('#subscribe_text').val('');
            setTimeout(function(){ $('#validate-subscribe').html(''); }, 3000); 
        }
    });
}

/* Profile Image upload */
$(document).ready(function (argument) {
    $("#user_profileChange").change(function(e) {
        e.preventDefault();
        var file_data   = $(this).prop("files")[0];
        var formData    = new FormData();
        formData.append("userImg", file_data);
        $('.profile_pic_container').addClass('loading');
        $.ajax({
            type        :'POST',
            url         : base_url+"/users/ajax_image_upload",
            data        : formData,
            dataType    : 'json',
            enctype     : "multipart/form-data",
            cache       : false,
            contentType : false,
            processData : false,
            success:function(data){
                $('.profile_pic_container').removeClass('loading');
                console.log(data.message);
                if(data.message == 'success'){
                    if(data.file_name != ''){
                        $('#profile-pic,#userHeaderImg').attr('src',base_url+'/uploads/users/'+data.file_name);
                        $('.profile_pic_container').html(data.coverimg);
                        $('.picture-tiles').html(data.user_images);
                    } else {
                        $('#profile-pic,#userHeaderImg').attr('src',base_url+'/uploads/images/40x40.png');
                        
                    }
                }
            },
            error: function(data){
                $('.profile_pic_container').removeClass('loading');
            }
        });
    })
})


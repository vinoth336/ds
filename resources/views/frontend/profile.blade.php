<div class="col-md-8 account_details col-sm-8 col-xs-12 nopadding">
<div class="smallHeading swiggyGray">{!! Lang::get('core.your_account') !!}</div>
<div class="largeHeading swiggyOrange" ng-bind="::vm.user.name" id="first_name_val" value="{{Auth::user()->first_name}}">{{Auth::user()->first_name}}</div>

<div class="editProfile">
    <dl class="profileField">
        <dt class="swiggyGray">{!! Lang::get('core.user_mail') !!}</dt>
        <dd>
        <?php $authname = Auth::user()->first_name;
        $authemail = Auth::user()->email; ?>
            <span class="fieldValue fn_email" id="fn_email_val">{{Auth::user()->email}}</span>
            <input type="hidden" value="{{$authname}}" id="authusername">
            <input type="hidden" value="{{$authemail}}" id="authuseremail">
            <a  href="javascript:openModal();" class="editLink">{!! Lang::get('core.btn_edit') !!}</a>
        </dd>
        <div class="success_message" style="display:none;">{!! Lang::get('core.email_change') !!}</div>
    </dl>
    <dl class="profileField">
        <dt class="swiggyGray">{!! Lang::get('core.photo') !!}</dt>
        <dd class="text-center">
            <span class="fieldValue fn_email1" ><img src="@if($userImg != ''){{url('uploads/users/'.$userImg)}}@else{{url('uploads/images/no-image.png')}}@endif" alt="Image Alternative text" id="profile-pic" border="0" width="50" class="img-circle profile_img"></span>
              <form accept-charset="UTF-8" action="{{url('users/ajax_image_upload')}}" enctype="multipart/form-data" id="ajax_upload_form" method="post" name="ajax_upload_form" target="upload_frame">
                 <div class="fileinput fileinput-new col-xs-12 text-center" data-provides="fileinput">
                      <label class=" btn btn-primary upload_pro_pic" for="user_profile_pic" role="button">{!! Lang::get('core.upload_photo') !!}... <input tabindex="0" type="file" name="avatar"  capture="camera" id="user_profileChange" ></label>
                      <!-- <input tabindex="0" type="file" name="avatar" accept="image/*" capture="camera" id="user_profileChange" > -->
                  </div>
              </form>
        </dd>
        <span class="success_message" ></span>
    </dl>
</div>
</div>
<div id="editEmailModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form role="form" action="" method="post" id="email_form">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title strong">{!! Lang::get('core.edit_profile') !!}</h5>
            <div class="swiggyGray">{!! Lang::get('core.update_email') !!}</div>
          </div>
          <div class="modal-body">
              <div class="alert_fn name_space_error"></div>
              <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                  <input type="text"  id="username" name="username" class="form-control"  value="{{Auth::user()->first_name}}" onkeypress="return AvoidSpace(event)" placeholder="{!! Lang::get('core.name') !!}">
              </div>
          </div>
          <div class="modal-body">
              <div class="alert_fn"></div>
              <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                  <input type="email"  id="email" name="email" class="form-control"  value="{{Auth::user()->email}}" placeholder="{!! Lang::get('core.email') !!}">
              </div>
              
          </div>

          <div class="error_message"></div>
          <div class="modal-footer">
            <a href="javascript:void(0)" class="pull-left linkCancel" data-dismiss="modal">{!! Lang::get('core.sb_cancel') !!}</a>
            <button type="submit" class="btn btn-primary save_address btnUpdate"  >{!! Lang::get('core.sb_update') !!}</button>
            <div id="mail_same_error"></div>
          </div>
      </div>
      </form>
  </div>
</div>
<style type="text/css">.modal-backdrop{opacity:0!important;position:relative!important;}</style>
<script type="text/javascript">
  function openModal(){
    var mailid = $("#fn_email_val").text();
    var nameval = $("#first_name_val").text();
    $("#username").val(nameval); 
    $("#email").val(mailid);
    $('.error_message').html('');
   $("#editEmailModal").modal("show");

   }
  $("#email_form").validate({
    // Rules for form validation
    rules:
    {
        email:
        {
            required: true,
            email: true
        },
        username:
        {
            required: true,
            minlength: 2,
        }
    },
                        
    // Messages for form validation
    messages:
    {
        email:
        {
            required: '{!! Lang::get("core.email_error") !!}',
            email: '{!! Lang::get("core.valid_email") !!}',
        },
        username:
        {
            required: '{!! Lang::get("core.name_error") !!}',
            alphanumeric: '{!! Lang::get("core.name_format_error") !!}',
        }
    },                  
    submitHandler: function(form) {
      var suc_msg = '';
      var orginal_email = $("#fn_email_val").text();
      var original_name = $("#first_name_val").text();
      var new_mail = $("#email").val();
      var new_name = $("#username").val();
      var purl ='<?php echo url();?>/user/email';
      var eurl = '<?php echo url();?>/users/checkemail';   
      if(new_mail != orginal_email || original_name != new_name){
        $.ajax({
            url: purl,
            type: 'post',
            data:  $('#email_form').serialize(),
            success: function(data) {
                if(data == 6){
                  $('.error_message').html('<font color="red">{!! Lang::get("core.email_exist_error") !!}</font>');
                      setTimeout(function(){ $('.error_message').html(''); }, 3000);
                } else if(data ==1 || data ==2 || data ==3){
                  if(data == 1) {
                    $('.error_message').html("{!! Lang::get('core.email_name_change') !!}");
                    suc_msg = "{!! Lang::get('core.email_name_change_success') !!}";
                  } else if(data == 2) {
                    $('.error_message').html("{!! Lang::get('core.email_change') !!}");
                    suc_msg = "{!! Lang::get('core.email_change_success')!!}";
                  } else if(data == 3) {
                    $('.error_message').html("{!! Lang::get('core.name_change') !!}");
                    suc_msg = "{!! Lang::get('core.name_change_success') !!}";
                  }
                  $(".fn_email").text($("#email_form").find('#email').val());
                  $("#username").val(new_name); 
                  $("#email").val(new_mail);
                  $("#fn_email_val").text(new_mail);
                  $("#first_name_val").text(new_name);
                  $("#editEmailModal").modal("hide");
                 $(".success_message").text(suc_msg);
                  setTimeout(function(){ $('.success_message').text(''); }, 3000);
                } else if(data ==4 || data ==5 ) {
                  if(data ==4 ) {
                     $('.error_message').html('<font color="red">{!! Lang::get("core.email_name_change_not") !!}</font>');
                     setTimeout(function(){ $('.error_message').html(''); }, 3000);
                  } else if(data ==5 ) {
                      $('.error_message').html('<font color="red">{!! Lang::get("core.email_exist_error") !!}</font>');
                      setTimeout(function(){ $('.error_message').html(''); }, 3000);
                  }

                }
            }            
        });
    } else {
       $('.error_message').html('<font color="red">{!! Lang::get("core.change_email_name") !!}</font>');
       setTimeout(function(){ $('.error_message').html(''); }, 3000);
    }
  },
  // Do not change code below
  errorPlacement: function(error, element)
  {
      error.insertAfter(element.parent());
  }
});
/*$(document).on('click',".upload_pro_pic",function(e){
  e.preventDefault();
  var image = $("#user_profileChange").val();
  if(image == ''){
     $(".empty_image_error").show();
     setTimeout(function() { $(".empty_image_error").hide(); }, 2000);
     return false;
  } else {
    $(".empty_image_error").hide();
    return true;
  }
})*/
function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) {
      $(".name_space_error").html('<font color="red">{!! Lang::get("core.name_alpha") !!}</font');
      setTimeout(function(){ $('.name_space_error').html(''); }, 2000);
      return false;
    }
}
</script>

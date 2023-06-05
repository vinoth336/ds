<div class="col-md-8 account_details col-sm-8 col-xs-12 nopadding">
    <div class="smallHeading swiggyGray">{!! Lang::get("core.changepassword") !!}</div>
    <div class="editProfile change_pwd">
        <form id="change_pass" method="post" action="{{ URL::to('users/changepassword')}}">
            <dl class="profileField bdr_none">
                <dt class="swiggyGray col-xs-12 nopadding nowidth">{!! Lang::get("core.currentpassword") !!}</dt>
                <dd class="col-xs-12 nopadding nomargin">
                    <span class="fieldValue fn_email1" >
                        <input class="form-control old_password" name="old_password" id="password" type="password" required="">
                    </span>
                </dd>
            </dl>
            <dl class="profileField">
                <dt class="swiggyGray col-xs-12 nopadding nowidth">{!! Lang::get("core.newpassword") !!}</dt>
                <dd class="col-xs-12 nopadding nomargin">
                    <span class="fieldValue fn_email1" >
                        <input class="form-control new_password" name="password" id="password_confirmation" type="password" required="">
                    </span>
                </dd>
            </dl>
            <dl class="profileField">
                <dt class="swiggyGray col-xs-12 nopadding nowidth">{!! Lang::get("core.conewpassword") !!}</dt>
                <dd class="col-xs-12 nopadding nomargin">
                    <span class="fieldValue fn_email1" >
                        <input class="form-control old_password"  name="password_confirmation" type="password" id="password" required=""/>
                    </span>
                </dd>
            </dl>
            <div class="password_action">
                <input class="btn btn-primary but_sucss pull-left" type="submit" value="{!! Lang::get('core.changepassword') !!}">
                <!-- <a onclick="window.location.reload(true)" class="linkCancel pull-left">{{ Lang!!:get("core.sb_cancel") !!}</a> -->
            </div>
        </form>
    </div>
</div>    
 <div class="user_dashboard">
    <div class="container">
    @if(Session::has('message'))
        {!! Session::get('message') !!}
    @endif
    <ul class="parsley-error-list">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <div class="alert_fn"></div>
      <div class="row">
        <div class="bhoechie-tab-container col-xs-12">
          <div class="col-sm-3 col-xs-12 page-sidebar bhoechie-tab-menu">
              <div class="list-group">
                @if(\Session::has('changePass'))
                    <?php 
                        \Session::forget('changePass');
                        $profileactive    = '';
                        $pwdactive        = 'active';
                    ?>
                @else
                    <?php 
                        \Session::forget('changePass');
                        $profileactive    = 'active';
                        $pwdactive        = '';
                    ?>
                @endif
                  <a href="#" class="list-group-item {{$profileactive}}">
                    <!-- <h4 class="glyphicon glyphicon-user"></h4><br/> -->{!! Lang::get('core.my_profile') !!}
                  </a>
                  <a href="#" class="list-group-item {{$pwdactive}}">
                    <!-- <h4 class="glyphicon glyphicon-lock"></h4><br/> -->{!! Lang::get('core.change_pwd') !!}
                  </a>
                  <!--<a href="#" class="list-group-item">
                     <h4 class="glyphicon glyphicon-shopping-cart"></h4><br/> {!! Lang::get('core.my_orders') !!}
                  </a>-->
                  <a href="#" class="list-group-item">
                    <!-- <h4 class="glyphicon glyphicon-home"></h4><br/> -->{!! Lang::get('core.saved_address') !!}
                  </a>
                  <!--<a href="#" class="list-group-item">
                     <h4 class="glyphicon glyphicon-gbp"></h4><br/> {!! Lang::get('core.payment') !!}
                  </a>-->
                </div>
          </div>
          <div class="col-sm-9 col-xs-12 page-content bhoechie-tab">
              <div class="bhoechie-tab-content {{$profileactive}} nopadding">
                  <!-- <h3 >My Profile</h3> -->
                   @include('frontend/profile')
              </div>
              <div class="bhoechie-tab-content {{$pwdactive}} nopadding">
                  <!-- <h3>Change Password</h3> -->
                 @include('frontend/security')
              </div>
              <!--<div class="bhoechie-tab-content nopadding">
                   <h3>My Orders</h3> 
                 @include('frontend/myorders')
              </div>-->
              <div class="bhoechie-tab-content nopadding">
                 <!-- <h3>Saved Address</h3> -->
                 @include('frontend/myaddress')
              </div>
              <!--<div class="bhoechie-tab-content nopadding">
                   <h3>Payment</h3> 
                  @include('frontend/payment')
              </div>-->
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
      $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
          e.preventDefault();
          $(this).siblings('a.active').removeClass("active");
          $(this).addClass("active");
          var index = $(this).index();
          $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
          $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
      });
  });
</script>
<style type="text/css">
.bhoechie-tab-container {
    padding: 20px;
}
.bhoechie-tab {
    background: #fff;
    padding: 20px;
}
div.bhoechie-tab-menu{
  padding-right: 0;
  padding-left: 0;
  padding-bottom: 0;
}
div.bhoechie-tab-menu div.list-group{
  margin-bottom: 0;
}
div.bhoechie-tab-menu div.list-group>a{
  margin-bottom: 0;
}
div.bhoechie-tab-menu div.list-group>a .glyphicon,
div.bhoechie-tab-menu div.list-group>a .fa {
  color: rgb(234,91,49);
}
div.bhoechie-tab-menu div.list-group>a:first-child{
  border-top-right-radius: 0;
  -moz-border-top-right-radius: 0;
}
div.bhoechie-tab-menu div.list-group>a:last-child{
  border-bottom-right-radius: 0;
  -moz-border-bottom-right-radius: 0;
}
div.bhoechie-tab-menu div.list-group>a.active,
div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
div.bhoechie-tab-menu div.list-group>a.active .fa{
  background: linear-gradient(to right,rgba(254,249,241,1) 0,rgba(255,255,255,1) 100%);
  color: #f5861f;
  border-color: #ddd;
  background-color: rgba(254,249,241,1);
  background: -webkit-gradient(left top,right top,color-stop(0,rgba(254,249,241,1)),color-stop(100%,rgba(255,255,255,1)));
}
div.bhoechie-tab-content{
  background-color: #ffffff;
  /* border: 1px solid #eeeeee; */
  padding-left: 20px;
  padding-top: 10px;
}
.list-group-item
{
  background: transparent;
  border-width: 0 0 1px;
  font-size: 12px;
}

div.bhoechie-tab div.bhoechie-tab-content:not(.active){
  display: none;
}
</style>
<!-- Product filter Start -->
<!-- <div class="product-filter">
  <div class="grid-list-count hidden">
    <a class="list switchToGrid" href="#"><i class="fa fa-list"></i></a>
    <a class="grid switchToList" href="#"><i class="fa fa-th-large"></i></a>
  </div> -->
<!--   <div class="short-name pull-right">
    <span>Short By</span>
  
      <label>
        <select  class="sort_by">
          <option  value="">Short by</option>
          <option value="delivery_time" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'delivery_time') selected @endif @endif>Delivery Time</option>
          <option value="rating" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'rating') selected @endif @endif>Rating</option>
          <option value="budget" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'budget') selected @endif @endif>Budget</option>
          <option value="name" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'name') selected @endif @endif>name</option>
        </select>
      </label>
   
  </div> -->
  <div class="Show-item hidden">
    <span>{!! Lang::get('core.show_items') !!}</span>
    <form class="woocommerce-ordering" method="post">
      <label>
        <select name="order" class="orderby">
          <option selected="selected" value="menu-order">49 {!! Lang::get('core.show_items') !!}</option>
          <option value="popularity">{!! Lang::get('core.popularity') !!}</option>
          <option value="popularity">{!! Lang::get('core.avg_ration') !!}</option>
          <option value="popularity">{!! Lang::get('core.newness') !!}</option>
          <option value="popularity">{!! Lang::get('core.price') !!}</option>
        </select>
      </label>
    </form>
  </div>
</div>
<!-- Product filter End -->

<!-- Adds wrapper Start -->
<div class="adds-wrapper">
@if(count($res_restaurnts) > 0)
@foreach($res_restaurnts as $rest)
<?php $rating = \SiteHelpers::getOverallRating($rest->restaurant_id);
$timevalid = \SiteHelpers::gettimeval($rest->restaurant_id);?>
<div class="col-sm-6 col-xs-6 full-width">
  <div class="search_product more_product">
  @if($timevalid == 1)
    <a class="item-list" href="{{ URL::to('frontend/details/'.$rest->restaurant_id) }}">
    @endif
      <div class="col-md-6 col-sm-12 col-xs-12 no-padding photobox">
        <div class="add-image">
          <img src="{{$rest->logo}}" alt="">
        </div>
      </div>
      <div class="col-md-6 col-sm-12 col-xs-12 add-desc-box1">
          @if($timevalid == 0)
            <div class="info">
              <span class="label label-warning">{!! Lang::get('core.next_available') !!} {!! $rest->opening_time !!}</span>
            </div>
          @endif
        <div class="add-details">
          <h5 class="add-title">{{$rest->name}}</h5>
          <div class="info">
            <span class="add-company">{{$rest->cuisine}}</span>
            <!-- <span class="item-location"><i class="fa fa-map-marker"> </i>&nbsp;{{$rest->location}}</span><br> -->
          </div>
        </div>
        <div class="card-bottom">
          <div class="col-xs-4 no_pad">
            @for($a = 0; $a < $rest->budget; $a ++)
              <span class="active">$</span>
            @endfor
            @for($a = 0; $a < (4-($rest->budget)); $a++)
              <span>$</span> 
            @endfor
          </div>
          @if($rating > 0)
          <div class="col-xs-4 text-center rating">{{$rating}}</div>
          @endif
          <div class="col-sm-4 text-right price-box no_pad"><span class="active">{{$rest->delivery_time}}</span> {!! Lang::get('core.mins') !!}</div>
        </div>
      </div>
      @if($timevalid == 1)
       </a>
      @endif
    <div class="has-chain">
        <div class="card-layout first">
            <div class="card-text">+2 {!! Lang::get('core.more_outlet') !!}<span ng-show="::vm.rest.chain.length>1" class="">s</span></div>
        </div>
        <div class="card-layout second"></div>
    </div>
  </div>
</div>
@endforeach
@else
<div class="item-list">
    <div class="col-sm-12 no-padding text-center">
    {!! Lang::get('core.no_restaurnts') !!}
    </div>
</div>
@endif

  
<!-- Adds wrapper End -->

<!-- Start Pagination -->
<div class="col-xs-12">
  <div class="pagination-bar">
  {!! $res_restaurnts->appends($_REQUEST)->render() !!}
  </div>
</div>
<!-- End Pagination -->

<!-- <div class="post-promo text-center">
  <h2> Get best matched jobs on your email! </h2>
  <h5>Apply to all jobs that you like in just one click !</h5>
  <a href="post-ads.html" class="btn btn-post btn-danger">Create a Job Alert! </a>
</div> -->
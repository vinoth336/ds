
  <div class="container">
          <div class="row">
            <div class="col-md-12 wow fadeIn" data-wow-delay="0.5s">
              <h3 class="section-title">{!! trans('core.abs_check_more_res') !!}</h3>

              <div id="new-products" class="owl-carousel abs_slider">
              @foreach($offer as $res)
                <div class="item">
                  <div class="product-item">
                    <div class="carousel-thumb">
                    @if($res->logo != '')
                      <img src="<?php echo url().'/uploads/restaurants/'.$res->logo; ?>"  alt=""> 
                    @else
                      <img src="<?php echo url().'/uploads/restaurants/Default_food.jpg'; ?>"  alt=""> 
                    @endif
                      <div class="overlay">
                        <a href="javascript:void(0);"><i class="fa fa-link"></i></a>
                      </div> 
                    </div>    
                    <a href="javascript:void(0);" class="item-name">{{$res->name}}</a>  
                    <span class="price">{{$res->rating}}</span>  
                  </div>
                </div>
                @endforeach
              </div>
            </div>  
          </div>
        </div>  
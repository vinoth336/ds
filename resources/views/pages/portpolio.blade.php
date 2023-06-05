<script>
$(function(){
	$('.mix-grid').mixitup();

	$('.fancybox-thumbs').fancybox({
		prevEffect : 'none',
		nextEffect : 'none',

		closeBtn  : false,
		arrows    : false,
		nextClick : true,

		helpers : {
			thumbs : {
				width  : 50,
				height : 50
			}
		}
	});

});
</script>

<div class="wrapper-header ">
    <div class="container">
		<div class="col-sm-6 col-xs-6">
		  <div class="page-title">
			<h3> {!! trans('core.abs_portpolio_sub') !!}</h3>
		  </div>
		</div>
		<div class="col-sm-6 col-xs-6 ">
		  <ul class="breadcrumb pull-right">
			<li><a href="{{ URL::to('') }}">{!! trans('core.home') !!}</a></li>
			<li class="active">{!! trans('core.abs_service_page') !!}</li>
		  </ul>		
		</div>
		  
    </div>
</div>

<div class="container">


<ul class="mix-filter">
		<li class="filter active" data-filter="all">{!! trans('core.abs_all') !!}</li>
				<li class="filter" data-filter="1-img">{!! trans('core.abs_ui_design') !!}</li>
				<li class="filter" data-filter="2-img">{!! trans('core.abs_web_develop_ment') !!}</li>
				<li class="filter" data-filter="3-img">{!! trans('core.abs_photographys') !!}</li>
				<li class="filter" data-filter="4-img">{!! trans('core.abs_apln') !!}</li>
				<li class="filter" data-filter="5-img">{!! trans('core.abs_extension') !!}</li>
		
	</ul>
	
	<div class="row mix-grid thumbnails" >
					<div class="col-md-3 col-sm-3 mix 1-img">
					<img src="{{asset('abserve/themes/abservene/portpolio/app/3.jpg')}}" class="img-responsive" />	
					<div class="mix-details">
					<h4>{!! trans('core.abs_image_6') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/app/3.jpg')}}" title="Image 6" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 1-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/app/2.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_1') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/app/2.jpg')}}" title="Image 1" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 1-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/app/1.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_2') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/app/1.jpg')}}" title="Image 2" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 2-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/card/1.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_3') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!} </p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/1.jpg')}}" 
					title="Image 3" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 2-img">
					<img src="{{asset('abserve/themes/abservene/portpolio/card/2.jpg')}}" class="img-responsive" />
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_4') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!} </p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/2.jpg')}}" title="Image 4" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 2-img">
					<img src="{{asset('abserve/themes/abservene/portpolio/card/3.jpg')}}" class="img-responsive" />
					<div class="mix-details">
					<h4>{!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!} </p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/3.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>

					<div class="col-md-3 col-sm-3 mix 2-img">
					<img src="{{asset('abserve/themes/abservene/portpolio/card/4.jpg')}}" class="img-responsive" />
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!} </p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/4.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>			
			
					<div class="col-md-3 col-sm-3 mix 3-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/icon/1.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/1.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 3-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/icon/2.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/2.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 3-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/icon/3.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/3.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 3-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/icon/4.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/4.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
			
					<div class="col-md-3 col-sm-3 mix 3-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/icon/5.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/card/5.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>			
					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/1.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/1.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/2.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/2.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/3.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/3.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/4.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/4.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
			

					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/5.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/5.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/6.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/6.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>
					<div class="col-md-3 col-sm-3 mix 4-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/logo/7.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/logo/7.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>						

					<div class="col-md-3 col-sm-3 mix 5-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/web/1.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/web/1.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>

					<div class="col-md-3 col-sm-3 mix 5-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/web/2.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/abservene/portpolio/web/2.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>			
					<div class="col-md-3 col-sm-3 mix 5-img">
				
					<img src="{{asset('abserve/themes/abservene/portpolio/web/3.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/mango/portpolio/web/3.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>	
					<div class="col-md-3 col-sm-3 mix 5-img">
				
					<img src="{{asset('abserve/themes/mango/portpolio/web/4.jpg')}}" class="img-responsive" />
				
					<div class="mix-details">
					<h4> {!! trans('core.abs_image_5') !!}</h4>
					<p>{!! trans('core.abs_lorem_note') !!}</p>
					<a class="mix-link"><i class="fa fa-link"></i></a>
					<a class="mix-preview fancybox-thumbs" href="{{ URL::to('abserve/themes/mango/portpolio/web/4.jpg')}}" title="Image 5" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
							
			</div>					
			
</div>
</div>


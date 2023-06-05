$('document').ready(function(){
	$('.delivery_location h5').click(function(){
        $('.delivery_location').toggleClass('open');
    });
    $('.you-cart-btn').click(function(){
        $('.your_cart_block').toggleClass('active');
    });
    $("#search-full").owlCarousel({
      navigation : false, // Show next and prev buttons
      slideSpeed : 300,
      autoPlay: true,
      paginationSpeed : 400,
      singleItem:true
    });
    $("#search-three").owlCarousel({
        autoPlay: 3000,
        items : 3,
        navigation : true,
        margin:10,
        pagination : false,
        itemsDesktop : [1199,3],
        itemsDesktopSmall : [979,3]
    });
    $('.panel-topic').click(function(){
        $('.panel-sort').not($(this).closest('.panel-sort')).removeClass('open');
        $(this).closest('.panel-sort').toggleClass('open');
    });
});

$(window).scroll(function(){
	$.browser.chrome = $.browser.webkit && !!window.chrome;  
	$.browser.safari = $.browser.webkit && !window.chrome;  
	if ($.browser.chrome || $.browser.safari)
	{var window_size = $(window).width() + 17;}
	else
	{var window_size = $(window).width();}

	if (window_size >= 991)
	{
		var start_host_top = $('.search_header').offset().top;
	    var scroll = $(window).scrollTop();
	    if (scroll >= start_host_top){ $('.search_header').addClass('fixe');}
	    if (scroll <= $('.search-page').offset().top){$('.search_header').removeClass('fixe');}

	    var bookitright = ($('.slider_block').outerHeight() + $('#search-full').outerHeight()) - $('.search_header').outerHeight();
	    if (scroll >= bookitright){$('.page-sidebar').addClass('fixe');}
	    else{$('.page-sidebar').removeClass('fixe');}
	}
});
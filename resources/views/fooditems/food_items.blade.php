@extends('layouts.app')

@section('content')



 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
                       

	<div class="page-content row">
	<div class="page-header">
		<div class="page-title">
		<h3> {{ $restaurant->name }} <small>{{ $pageTitle }}</small></h3>
	</div>
	<ul class="breadcrumb">
		<li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
		<li><a href="{{ URL::to('fooditems') }}">{{ $pageTitle }}</a></li>
		<!-- <li class="active" >{{ $restaurant->name }}</li> -->
	</ul>
	</div>
	<div class="page-content-wrapper m-t">
	<div class="sbox animated fadeInRight">
	<div class="sbox-content">
		<div class="title">
			<div class="white_bg"><a href="{{url('fooditems/update')}}">Add Food Item</a>
            <a href="#" data-toggle="modal" data-target="#drag-drop" class="catseq">Category Sequence</a>
          
            <a style="background-color:brown; cursor:pointer;" class="truncate" href="{{url('fooditems/foodtruncate/?resid='.$restaurant->id)}}" onclick="return confirm('Are you sure you want to truncate food item?');">Truncate Food Item</a>   
			
            @if(\Auth::user()->group_id == 1)
			<a class="pull-right" href="{{url('foodcategories/update')}}">Add Category/Sub category</a>
			@endif
			</div>
			<h3 class="text-left page_title"><strong>{{$restaurant->name}}</strong></h3>
		</div>
       
		
        <div class="row">
        
		@if(!empty($recomm_items))
			<div class="restaurant-items-title accordion">Recommended Items</div>
			@foreach($recomm_items as $ritems)
             <div class="mcat">
				<div class="panel col-lg-3 col-md-4 col-sm-6 col-xs-12">
                
					<div class="each_itm">
						  <div class="restaurant_hide_icon" id="res12<?php echo $ritems->id; ?>">
							<i class="fa fa-circle"></i>
							<i class="fa fa-circle"></i>
							<i class="fa fa-circle"></i>
						</div>
						  <div class="restaurant_hide_icon_block1<?php echo $ritems->id; ?> newres">
							<a href="{{url('fooditems/update/'.$ritems->id)}}">Edit</a>
							<!-- <a>Out of stock</a> -->
							<a class="cdelete" data-href="{{url('fooditems/fooddelete/'.$ritems->id.'/'.$res_id)}}" data-toggle="modal" data-target="#confirm-delete1">Delete</a>
						</div>
						
							<div class="col-md-5 col-sm-5 col-xs-12 text-center pad_lr_5">
							@if($ritems->image != '')
							<a href="{{url('/uploads/res_items/'.$res_id.'/'.$ritems->image)}}" target="_blank" class="previewImage">
							@endif
							{!! \SiteHelpers::showuploadedfile($ritems->image,'/uploads/res_items/'.$res_id.'/') !!}
							@if($ritems->image != '')</a>@endif
							</div>
						<div class="col-md-7 col-sm-7 col-xs-12 pad_lr_5">
							<h5><a style="text-decoration: none !important;" href="{{url('fooditems/update/'.$ritems->id)}}">{{$ritems->item_name}}</a></h5>
							<div class="sku_num">Status:@if($ritems->item_status == '1') Instock @else Out of Stock @endif</div>
							<div class="sku_num">Type:@if($ritems->status == 'Veg') Veg @else Non veg @endif</div>
							<span class="pro_price"><i class="fa fa-inr"></i> {{$ritems->price}}</span>
						</div>
					</div>
				</div>
             </div>
             <script>   
					$(document).ready(function(){
						$(".newres").removeClass("active"); 
					 });
									
					 $("#res12<?php echo $ritems->id; ?>").click(function(){
					 $(".restaurant_hide_icon_block1<?php echo $ritems->id; ?>").addClass("active"); 
					 });	
						</script>
			@endforeach
			@else
			<div class="restaurant-items-title">No Items Found </div>
		@endif
		</div>
        
     <!---------------- for drag and drop start product------------------------->
		<div class="clearfix"></div>
		<div class="row">
        <div class="clearfix">
        <div class="clearfix">
			<?php $main_cat = ''; $sub_cat=''; ?> 
			
            </div>
            @foreach($hotel_item_single as $h_items)
           
                <div class="restaurant-items-title accordion">						
                        {{$h_items->cat_name}} <!--<i class="fa fa-angle-double-right" aria-hidden="true"></i>  {{$h_items->sub_cat}}-->
                </div>
             
                <div class="mcat_{{$h_items->main_cat}}">
                    <?php $resproducts = \SiteHelpers::getResproducts($res_id,$h_items->main_cat);   ?>
					
					
					 <span id="sortable<?php echo $h_items->main_cat; ?>" class="connectedSortable<?php echo $h_items->main_cat; ?>">
                  <?php  foreach($resproducts as $_hotel_items){ ?>
                       
                            <div class="panel restaurant-items-unit col-lg-3 col-md-4 col-sm-6 col-xs-12" id="cat_{{$_hotel_items->main_cat}}" data-id="<?php echo $_hotel_items->id; ?>">
                            
                              <div class="each_itm">
                                <div class="restaurant_hide_icon" id="res1<?php echo $_hotel_items->id; ?>">
                                    <i class="fa fa-circle"></i>
                                    <i class="fa fa-circle"></i>
                                    <i class="fa fa-circle"></i>
                                </div>
                               
                               
                                 <div class="restaurant_hide_icon_block<?php echo $_hotel_items->id; ?> newres">
                                    <a href="{{url('fooditems/update/'.$_hotel_items->id)}}">Edit</a>
                                    <!-- <a>Out of stock</a> -->
                                    <a class="cdelete" data-href="{{url('fooditems/fooddelete/'.$_hotel_items->id.'/'.$res_id)}}" data-toggle="modal" data-target="#confirm-delete1">Delete</a>
                                </div>
                              
                            
                                <div class="col-md-5 col-sm-5 col-xs-12 text-center pad_lr_5">
                                    @if($_hotel_items->image != '')
                                        <a href="{{url('/uploads/res_items/'.$res_id.'/'.$_hotel_items->image)}}" target="_blank" class="previewImage">
                                    @endif
                                    {!! \SiteHelpers::showuploadedfile($_hotel_items->image,'/uploads/res_items/'.$res_id.'/') !!}
                                    @if($_hotel_items->image != '')</a>@endif
                                </div>
                                
                                <div class="col-md-7 col-sm-7 col-xs-12 pad_lr_5">
                                    <h5 class="item_name"><a style="text-decoration: none !important;" href="{{url('fooditems/update/'.$_hotel_items->id)}}">{{$_hotel_items->food_item}}</a></h5>
                                    <div class="sku_num">Status:@if($_hotel_items->item_status == '1') Instock @else Out of Stock @endif</div>
                                    <div class="sku_num">Type:@if($_hotel_items->status == 'Veg') Veg @else Non veg @endif</div>
                                    <span class="item-price"><i class="fa fa-inr" aria-hidden="true"></i> {{$_hotel_items->price}}</span>
                                </div>
                              </div>
                       </div>
						
		<script>   
  
   $("#res1<?php echo $_hotel_items->id; ?>").click(function(){
   $(".restaurant_hide_icon_block<?php echo $_hotel_items->id; ?>").addClass("active"); 
   });	

  
    $( function() {
      $( "#sortable<?php echo $h_items->main_cat; ?>" ).sortable({
        connectWith: ".connectedSortable<?php echo $h_items->main_cat; ?>"
      }).disableSelection();
    });
  
    $( ".connectedSortable<?php echo $h_items->main_cat; ?>" ).sortable({
                  delay: 150,
                  stop: function() {
                      var data1 = new Array();
                      var main = <?php echo $_hotel_items->main_cat; ?>;
                  $('.connectedSortable<?php echo $_hotel_items->main_cat; ?>>div').each(function() {
                      data1.push($(this).attr("data-id"));
                  });
                      console.log(data1);
                      updateOrderproducts(data1,main);
                 }
      });
  
    
    function updateOrderproducts(data1,main) {
        var res_id = '<?php echo $res_id; ?>';
      $.ajax({
                      url: '<?php echo url(); ?>/fooditems/orderproducts',
                      type: "POST",
                      data:'position='+ data1+'&res_id='+res_id+'&main_cat='+main,
                      success: function(data){
                          //alert(data);
                      
                      }
             });
              
    }                          
  </script>
	     		       <?php }  ?> 
				</span>                
            </div>
			@endforeach
            
    </div>
    </div>
	</div>
	</div>
    <!---------------- for drag and drop end product------------------------->
    <!---------------- for drag and drop start category---------------->
    <div class="drag-drop">
				<div  class="panel-body">
										<!-- sample modal content -->
										<div id="drag-drop" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h5 class="modal-title" id="myModalLabel">Category Sequence</h5>
													</div>
													<div class="modal-body">
														<div class="pa-20">
										
										<div class="form-wrap">
										
              
         

               <div id="sortablenew" class="connectedSortablebew">
                @foreach($hotel_item_single as $hi_items)
                     <div class="restaurant-items-title accordion" new-id="{{$hi_items->main_cat}}">
							{{$hi_items->cat_name}}
                             </div>
				
			 @endforeach
            </div>
            </div>
                                   
           <script>
  $( function() {
    $( "#sortablenew" ).sortable({
      connectWith: ".connectedSortablebew"
    }).disableSelection();
  } );
  
   $( ".connectedSortablebew" ).sortable({
			delay: 150,
			stop: function() {
				var selectedData = new Array();
				$('.connectedSortablebew>div').each(function() {
					selectedData.push($(this).attr("new-id"));
				});
				console.log(selectedData);
					updateOrder(selectedData,'stp');
			}
		});


    function updateOrder(dataorder,status) {
      var res_id = '<?php echo $res_id; ?>';
    $.ajax({
                    url: '<?php echo url(); ?>/fooditems/ordercategory',
                    type: "POST",
                    data:'position='+ dataorder+'&status='+status+'&res_id='+res_id,
                    success: function(data){
						//alert(data);
                    
                    }
            });
			
	  }
  
  
   </script>
  
  
									              	</div>
									                 </div>	
													</div>
													
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
										
									</div>
				
				
			</div>
  <!--------------------for drag and drop end category----------------------->
  
<div class="back_layout" style="display: none;"></div>
  
<script type="text/javascript">
 
$(".close").click(function(){
 window.location.reload();
});	
  

$('body').click(function(e) {
    if (!$(e.target).closest('.each_itm').length){
		 $('.newres').removeClass('active');
    
    }
});

$(document).on("click",'.cdelete',function(e){
	$('.restaurant_hide_icon_block').removeClass('active');
	$('#confirm-delete1').toggleClass('in').toggle();
	$('#confirm-delete1').find(".btn-ok").attr("href",$(this).data("href"));
});
$(document).on("click",'#confirm-delete1 #cls_popup',function(e){
	$('#confirm-delete1').find(".btn-ok").removeAttr("href");
	$('.restaurant_hide_icon_block').removeClass('active');
	$('#confirm-delete1').toggleClass('in').toggle();
});


var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
	
	
    var panel = this.nextElementSibling;
	var cl_name = $(panel).attr('class');

    if (panel.style.maxHeight){
		 panel.style.maxHeight = null;
      $('.'+cl_name +' .panel').css('maxHeight', 0);  
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
	
	  $('.'+cl_name +' .panel').css('maxHeight', 165);
    } 
  });
}

/* $( ".connectedSortable" ).sortable({
			delay: 150,
			start: function() {
				var selectedDataStart = new Array();
				$('.connectedSortable>div').each(function() {
					selectedDataStart.push($(this).attr("data-id"));
				});
				//alert(selectedDataStart);
				updateOrder(selectedDataStart,'str');
			},
			stop: function() {
				var selectedData = new Array();
				$('.connectedSortable>div').each(function() {
					selectedData.push($(this).attr("data-id"));
				});
				//console.log(selectedData);
				updateOrder(selectedData,'stp');
			}
		});
*/
  
     
   /*function updateOrder(data,status) {
      var res_id = '<?php echo $res_id; ?>';
    $.ajax({
                    url: '<?php echo url(); ?>/fooditems/reorder',
                    type: "POST",
                    data:'position='+ data+'&status='+status+'&res_id='+res_id,
                    success: function(data){
						//alert(data);
                    
                    }
            });
			
	  }
	  */
	  
	  
	
		  
</script>


<style>
.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

.active, .accordion:hover {
    background-color: #ccc;
}

.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
	margin-bottom: 0px;
}

.restaurant-items-title.accordion {

   margin: 10px 0px;

}

.newres {
    position: absolute;
    right: 20px;
    box-shadow: 1px 1px 4px;
    background-color: #fff;
    z-index: 9;
    min-width: 110px;
    top: 6px;
    display: none;
}
.newres.active {
  display:block;
}
.newres a {
    clear: both;
    width: 100%;
    float: left;
    padding: 5px 20px;
    color: #333;
    text-decoration: none;
    cursor: pointer;
}
.newres a:hover {
    background-color: #5e7a96;
    text-decoration: none;
    color: #fff;
}
.newres:before, .newres:after {
    left: 100%;
    top: 20px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}
.newres:after {
    border-color: rgba(255, 255, 255, 0);
    border-left-color: #FFFFFF;
    border-width: 7px;
    margin-top: -10px;
}
.newres:before {
    border-color: rgba(223, 223, 223, 0);
    border-left-color: #c6c6c6;
    border-width: 8px;
    margin-top: -11px;
}
.newres a {
    border-bottom: 2px solid #ddd;
}
.newres a:last-child {
    border-bottom: 0px none;
}

</style>

@stop
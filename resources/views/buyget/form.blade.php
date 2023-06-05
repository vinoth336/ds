@extends('layouts.app')

@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('buyget?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{!! Lang::get('core.addedit') !!} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'buyget/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Offers</legend>
							<?php //print_r($results); 
							
								$value = \DB::table('abserve_hotel_items')->select('*')->where('id','=',$id)->get();
									//print_r($value); 
							    $value1 = $value[0];
							    $res_id = $value1->restaurant_id;
								$food_item = $value1->food_item;
								$food_get = $value1->bogo_item_id;
							 ?>
                            
                            <input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $res_id ?>">
												
								  <div class="form-group  " >
									<label for="Restaurant Id" class=" control-label col-md-4 text-left"> Restaurants </label>
									<div class="col-md-6">              <?php if($id){   ?>
                                                                            <select name="resturants" class="form-control" id="restaurants" disabled>
                                                                                 <option value="">Select</option>
                                                                                @foreach($results as $res)
                                                                                <option <?php if($res->id == $res_id) { echo "selected";  } ?> value="{{ $res->id }}">{{ $res->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <?php } else {  ?>
                                                                            <select name="resturants" class="form-control" id="restaurants1" required>
                                                                                 <option value="">Select</option>
                                                                                @foreach($results as $res)
                                                                                <option value="{{ $res->id }}">{{ $res->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <?php }  ?>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 	
                                                    
								  <div class="form-group  " >
                                                                      
									<label for="Food Item" class=" control-label col-md-4 text-left"> Food Item (To Buy) </label>
									<div class="col-md-4">
                                       <?php if($id){   ?>
                                       <input type="hidden" name="food_item_buy" id="food_item_buy" value="<?php echo $id; ?>">
									  <select name="food_item_buy" class="form-control foodsres" disabled="disabled">
                                                                              <option value="">Select</option>
                                                                          </select>
                                                                          <?php }  else {  ?>
                                                                           <select name="food_item_buy" class="form-control foodsres">
                                                                              <option value="">Select</option>
                                                                          </select>
                                                                          <?php }  ?>
									 </div> 
									 <div class="col-md-2">
                                                                             <select name="buy_qty" class="form-control ">
                                                                                 @for ($i = 1; $i < 15; $i++)
                                                                       <option <?php if($value1->buy_qty == $i) { echo "selected";  }?>  value="{{ $i }}">{{ $i }}</option>
                                                                                 @endfor
                                                                              
                                                                          </select>
									 	
									 </div>
								  </div>
                                                    
                                                                    <div class="form-group  " >
                                                                        <label for="Food Item" class=" control-label col-md-4 text-left "> Food Item (To Get) </label>
                                                                        <div class="col-md-4">
                                                                           <select name="bogo_item_id" id="bogo_item_id" class="form-control foodsres_get">
                                                                               <option value="">Select</option>
                                                                          </select>
                                                                        </div> 
                                                                        <div class="col-md-2">
                                                                           <select name="get_qty" class="form-control ">
                                                                                 @for ($i = 1; $i < 15; $i++)
                                                                                <option <?php if($value1->get_qty == $i) { echo "selected";  }?> value="{{ $i }}">{{ $i }}</option>
                                                                                 @endfor
                                                                              
                                                                          </select>
                                                                        </div>
                                                                    </div>                                                     
								  					
								  <div class="form-group  " >
									<label for="Available From" class=" control-label col-md-4 text-left"> Offer Start Date  & Time </label>
									<div class="col-md-6">
                                                                            <?php $offer_to = date('d-m-Y'); ?>
                                                                            <div class="input-group m-b" style="width:200px !important;">
                                                                                <input class="form-control datetime hasDatepicker" id="offer_from" name="bogo_start_date"  type="text" autocomplete="off" value="<?php echo $value1->bogo_start_date; ?>" required="required">
                                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                            </div>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Available To" class=" control-label col-md-4 text-left"> Offer End Date  & Time </label>
									<div class="col-md-6">
									<div class="input-group m-b" style="width:200px !important;">
                                                                            <input class="form-control datetime hasDatepicker" id="offer_to" name="bogo_end_date" type="text" autocomplete="off" value="<?php echo $value1->bogo_end_date; ?>" required="required">
                                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                            </div>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 
                                  
                                  
                                  <input type="hidden" name="bogo_name" id="bogo_name" value="">				
								  		
                                                    <!--<div id="datepairExample">
                                                        <div class="form-group  " >
                                                            <label for="Opening Time" class=" control-label col-md-4 text-left"> Offer Time </label>
                                                            <div class="col-md-3">

                                                                <input class="form-control time start" name="bogo_start_time" type="text" placeholder="start">
                                                            </div> 
                                                            <div class="col-md-3"> <input class="form-control time end" name="bogo_end_time" type="text" placeholder="end" ></div>
                                                        </div> 					

                                                    </div>--> 	
								 </fieldset>
			</div>
			
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('buyget?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		//alert();
		 <?php if($id)  {   ?>
		
		 // $('#restaurants').change(function(){
           var resid = <?php echo $res_id;  ?>;
		   var buy = <?php echo $id; ?>;
		  	// alert(buy);
           console.log(resid);
           $.ajax({
                    url: '<?php echo url(); ?>/buyget/foodsbyres',
                    type: "POST",
                    data: {resid:resid,buy:buy},
                    success: function(data){
						//alert(data);
                      $('.foodsres').html(data);
                    }
            });
			
			
		    var resid = <?php echo $res_id;  ?>;
			var get = <?php echo $food_get; ?>;
			//alert(get);
			
			$.ajax({
                    url: '<?php echo url(); ?>/buyget/foodsgeters',
                    type: "POST",
                    data: {resid:resid,get:get},
                    success: function(data){
					//	alert(data);
                      $('.foodsres_get').html(data);
                    }
            });
     //  });
           <?php }  ?>
		   
		   
		   $('#restaurants1').change(function(){
           var resid = $(this).val();
           $("#restaurant_id").val(resid);
           $.ajax({
                    url: '<?php echo url(); ?>/buyget/foodbuy',
                    type: "POST",
                    data: {resid:resid},
                    success: function(data){
						//alert(data);
                      $('.foodsres').html(data);
					  $('.foodsres_get').html(data);
                    }
            });
       });
	   
	   
		  
		   
		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("offer/multisearch")}}');
		$('#AbserveTable').submit();
	});
        
        
      
$('#datepairExample .time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:i:sa'
		});	

	
	
	
	
	
	
	
	
	});
	
	
	
	
	
	</script>		 
@stop
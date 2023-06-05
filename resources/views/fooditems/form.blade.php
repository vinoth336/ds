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
			<li><a href="{{ URL::to('fooditems?return='.$return) }}">{{ $pageTitle }}</a></li>
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
			<div class="sbox-title">
				<h4> <i class="fa fa-table"></i> </h4>
			</div>
			<div class="sbox-content"> 	
				{!! Form::open(array('url'=>'fooditems/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'Addfood')) !!}
					<div class="col-md-6">
						<fieldset>  
							<legend> Food Item Details </legend>
							<div class="form-group hidethis " style="display:none;">
								<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
								<div class="col-md-6">
									{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " > 
								<label for="Restaurant Id" class=" control-label col-md-4 text-left"> {!! trans('core.abs_restaurant_name') !!} <span class="asterix"> * </span></label>
								<div class="col-md-6">
									@if(\Auth::user()->group_id == 1)
									<input type="hidden" class="group_id" value="{{\Auth::user()->group_id}}">
									<select name='restaurant_id' rows='5' id='restaurant_id' class='select2 ' required  ></select> 
                                    @elseif(\Auth::user()->group_id == 7)
                                    <input type="hidden" class="group_id" value="{{\Auth::user()->group_id}}">
									<select name='restaurant_id' rows='5' id='restaurant_id' class='select2 ' readonly  ></select>  
									@else
									<input type="hidden" class="group_id" value="{{\Auth::user()->group_id}}">
									<!--  <select name='restaurant_id' rows='5' id='restaurant_id' class='select2 ' required  > -->
									<select name='restaurant_id' class='select2 ' required>
										<?php  foreach ($partner_hotels as $key => $value) { 
											echo '<option value="'.$value->id.'">'.$value->name.'</option>';
										} ?>
									</select>
									<!-- </select> -->
									@endif
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " > 
								<label for="Food Item" class=" control-label col-md-4 text-left"> {!! trans('core.abs_food_item') !!} <span class="asterix"> * </span></label>
								<div class="col-md-6">
									{!! Form::text('food_item', $row['food_item'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " > 
								<label for="Description" class=" control-label col-md-4 text-left"> {!! trans('core.abs_desc') !!} </label>
								<div class="col-md-6">
									{!! Form::text('description', $row['description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " >  
								<label for="Price" class=" control-label col-md-4 text-left"> {!! trans('core.abs_price') !!} <span class="asterix"> * </span></label>
								<div class="col-md-6">
									{!! Form::text('price', $row['price'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " >  
								<label for="Special Price" class=" control-label col-md-4 text-left"> Special Price <span class="asterix"> * </span></label>
								<div class="col-md-6">
									{!! Form::text('special_price', $row['special_price'],array('class'=>'form-control', 'placeholder'=>'', 'parsley-type'=>'number'   )) !!} 
								</div> 
								<div class="col-md-2">
								</div>
							</div>
                            <div class="form-group  " >
                                <label for="Special Price From" class=" control-label col-md-4 text-left"> Special Price From </label>
                                <div class="col-md-6">                                          
                                    <div class="input-group m-b" style="width:150px !important;">
                                        @if($row['special_from'] =='0000-00-00') {{--*/ $special_from = "" /*--}} @else {{--*/ $special_from = $row['special_from'] /*--}} @endif
                                        {!! Form::text('special_from', $special_from ,array('class'=>'form-control date')) !!}
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div> 
                                 </div> 
                                 <div class="col-md-2"></div>
                            </div> 					
                            <div class="form-group  " >
                                <label for="Special Price To" class=" control-label col-md-4 text-left"> Special Price To </label>
                                <div class="col-md-6">                                          
                                    <div class="input-group m-b" style="width:150px !important;">
                                        @if($row['special_to'] =='0000-00-00') {{--*/ $special_to = "" /*--}} @else {{--*/ $special_to = $row['special_to'] /*--}} @endif
                                        {!! Form::text('special_to', $special_to ,array('class'=>'form-control date')) !!}
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div> 
                                 </div> 
                                 <div class="col-md-2"></div>
                            </div> 
							<div class="form-group  " > 
								<label for="Image" class=" control-label col-md-4 text-left"> {!! trans('core.abs_image_main') !!} </label>
								<div class="col-md-6">
									<input  type='file' name='image' id='image' @if($row['image'] =='') class='required' @endif style='width:150px !important;'  />
									<div >
										{!! SiteHelpers::showUploadedFile($row['image'],'/uploads/res_items/'.$row['restaurant_id'].'/') !!}
									</div>					
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " > 
								<label for="Veg/NonVeg" class=" control-label col-md-4 text-left"> {!! trans('core.abs_veg_nonveg') !!} <span class="asterix"> * </span>
								</label>
								<div class="col-md-6">
									<label class='radio radio-inline'>
										<input type='radio' name='status' value ='Veg' required @if($row['status'] == 'Veg') checked="checked" @endif > Veg 
									</label>
									<label class='radio radio-inline'>
										<input type='radio' name='status' value ='Non_veg' required @if($row['status'] == 'Non_veg') checked="checked" @endif > Non veg 
									</label> 
								</div> 
								<div class="col-md-2">
								</div>
							</div>
                            <div class="form-group  " >
                              <label for="Toppings" class=" control-label col-md-4 text-left"> Toppings </label>
                              <div class="col-md-6">
                              
                              	<?php 
								$topping_id = explode(",", $row->topping_category); 
								?>
                                <select name='topping_category[]' multiple rows='5' id='topping_category' class='select2 ' >
                                  @foreach($toppings as $_toppings)
                                  
                                      @if(in_array("$_toppings->id", $topping_id))
                                  
                                      	<option value="{{ $_toppings->id }}" selected>{{ $_toppings->category}}</option>
                                      @else
                                      	<option value="{{ $_toppings->id }}">{{ $_toppings->category}}</option>
                                      @endif
                                  @endforeach
                                </select> 
                              </div> 
                              <div class="col-md-2"></div>                                     
                            </div>
                                  
                            <div class="form-group  ">                              
                              <div class="col-md-12" id="topping-price" style="display:none">
                                 
                              </div> 
                              <div class="col-md-2"></div>                                     
                            </div>
							<!-- <div class="form-group  " >
						 		<label for="Veg/NonVeg" class=" control-label col-md-4 text-left"> {!! trans('core.abs_customize') !!} <span class="asterix"> * </span></label>
						 		<div class="col-md-6">
						 			<label class='radio cust radio-inline'>
						 				<input type='radio' name='customize' value ='Yes' required @if($row['customize'] == 'Yes') checked="checked" @endif > {!! trans('core.abs_yes') !!}
						 			</label>
						 			<label class='radio cust radio-inline'>
						 				<input type='radio' name='customize' value ='No' required @if($row['customize'] == 'No') checked="checked" @endif > {!! trans('core.abs_no') !!}
						 			</label> 
						 		</div> 
						 		<div class="col-md-2"></div>
						 	</div> -->					
						</fieldset>
					</div>
					<div class="col-md-6">
						<fieldset>
							<legend>{!! trans('core.abs_other_details') !!} </legend> 
							<div class="form-group  " > 
								<label for="Main Category" class=" control-label col-md-4 text-left"> {!! trans('core.abs_main_category') !!} <span class="asterix"> * </span></label>
								<div class="col-md-6">
									<select name='main_cat' rows='5' id='main_cat' class='select2 '   style="display:none;"></select>
									<?php if($row->main_cat != ''){?>
									<select name='main_cat' rows='5' id='main_cat1' class='select2 ' required>
										<option value="" >-- Please Select --</option>
										@foreach($main_cat as $cat)
										@if($row->main_cat == $cat->id)
										<option value="{{ $cat->id }}" selected>{{ $cat->cat_name}}</option>
										@else
										<option value="{{ $cat->id }}">{{ $cat->cat_name}}</option>
										@endif
										@endforeach
									</select>
									<?php }else{?>
									<select name='main_cat' rows='5' id='main_cat1' class='select2 ' required>
										<option value="">-- Please Select --</option>
										@foreach($main_cat as $cat)
										<option value="{{ $cat->id }}">{{ $cat->cat_name }}</option>
										@endforeach
									</select>
									<?php }?>  
								</div> 
								<div class="col-md-2">
								</div>
							</div> 
							<div class="form-group  " >
								<label for="Sub Category" class=" control-label col-md-4 text-left">{!! trans('core.abs_sub_category') !!}  </label>
								<div class="col-md-6">
									<select name='sub_cat' rows='5' id='sub_cat' class='select2 ' style="display:none;" ></select>
									<?php if($row->sub_cat != ''){?>
									<select name='sub_cat' rows='5' id='sub_cat1' class='select2 '>
										<option value="" >-- Please Select --</option>
										@foreach($subcategories as $cat)
										@if($row->sub_cat == $cat->id)
										<option value="{{ $cat->id }}" selected>{{ $cat->cat_name}}</option>
										@else
										<option value="{{ $cat->id }}">{{ $cat->cat_name}}</option>
										@endif
										@endforeach
									</select>
									<?php }else{?>
									<select name='sub_cat' rows='5' id='sub_cat1' class='select2 '></select>
									<?php }?> 
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
                            <div class="form-group  " > 
                                <label for="Packaging Charge" class=" control-label col-md-4 text-left"> Packaging Charge <span class="asterix"> * </span></label>
                                <div class="col-md-6 time_ex">
                                    {!! Form::text('packaging_charge', $row['packaging_charge'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', )) !!}
                                </div> 
                                <div class="col-md-2">
                                </div>
                            </div>
                            
                            <div class="form-group  " > 
                                <label for="Max Packaging Charge" class=" control-label col-md-4 text-left">Maximum Packaging Charge <span class="asterix"> * </span></label>
                                <div class="col-md-6 time_ex">
                                    {!! Form::text('max_packaging_charge', $row['max_packaging_charge'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', )) !!}
                                </div> 
                                <div class="col-md-2">
                                </div>
                            </div>
                            
							<div class="form-group  " > 
								<label for="Item Status" class=" control-label col-md-4 text-left"> {!! trans('core.abs_item_status') !!} <span class="asterix"> * </span>
								</label>
								<div class="col-md-6"> 
									<label class='radio radio-inline'>
										<input type='radio' name='item_status' value ='1' required @if($row['item_status'] == '1') checked="checked" @endif > {!! trans('core.abs_in_stack') !!} 
									</label>
									<label class='radio radio-inline' style="margin-left:0px;">
										<input type='radio' name='item_status' value ='0' required @if($row['item_status'] == '0') checked="checked" @endif > {!! trans('core.out_of_stock') !!}  
									</label> 
								</div> 
								<div class="col-md-2">
								</div>
							</div> 					
							<div class="form-group  " > 
								<label for="Recommended item" class=" control-label col-md-4 text-left"> {!! trans('core.abs_recommended_item') !!}  </label>
								<div class="col-md-6">
									<label class='radio radio-inline'>
										<input type='radio' name='recommended' value ='1'  @if($row['recommended'] == '1') checked="checked" @endif > Yes 
									</label>
									<label class='radio radio-inline' style="margin-left:34px;">
										<input type='radio' name='recommended' value ='0'  @if($row['recommended'] == '0') checked="checked" @endif > No 
									</label> 
								</div> 
								<div class="col-md-2">
								</div>
							</div>
                            <div class="form-group"> 
								<label for="Item Visible Days" class=" control-label col-md-4 text-left"> Item Visible Days </label>
								<div class="col-md-6">
                                	<?php $available_days = explode(',',$row->available_days);?>
                                	<label class='checkbox checkbox-inline'>
										<input type='checkbox' id='all_days' name='all_days' class="checkAll" @if(count($available_days) == 7) checked="checked" @endif > All Days
									</label><br />
									<label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='1' class="checkSingle" @if(in_array('1', $available_days)) checked="checked" @endif > Mon 
									</label>
									<label class='checkbox checkbox-inline' style="margin-left:34px;">
										<input type='checkbox' name='available_days[]' value ='2' class="checkSingle" @if(in_array('2', $available_days)) checked="checked" @endif > Tue 
									</label>
                                    <label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='3' class="checkSingle" @if(in_array('3', $available_days)) checked="checked" @endif > Wed 
									</label>
									<label class='checkbox checkbox-inline' style="margin-left:34px;">
										<input type='checkbox' name='available_days[]' value ='4' class="checkSingle" @if(in_array('4', $available_days)) checked="checked" @endif > Thu 
									</label>
                                    <label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='5' class="checkSingle" @if(in_array('5', $available_days)) checked="checked" @endif > Fri 
									</label>
									<label class='checkbox checkbox-inline' style="margin-left:34px;">
										<input type='checkbox' name='available_days[]' value ='6' class="checkSingle" @if(in_array('6', $available_days)) checked="checked" @endif > Sat 
									</label>
                                    <label class='checkbox checkbox-inline'>
										<input type='checkbox' name='available_days[]' value ='7' class="checkSingle" @if(in_array('7', $available_days)) checked="checked" @endif > Sun 
									</label>
								</div> 
								<div class="col-md-2"></div>
							</div>
							<div id="datepairExample">
								<div class="form-group  " > 
									<label for="Full Day Available From" class=" control-label col-md-4 text-left"> Full Day Available From </label>
									<div class="col-md-6 time_ex">
										<input class="time start form-control" name="available_from" type="text" value="@if($row['available_from'] != '') <?php echo date("h:i:sa",strtotime($row['available_from'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div> 	 				
								<div class="form-group  " >
									<label for="Full Day Available To" class=" control-label col-md-4 text-left">  Full Day Available To  </label>
									<div class="col-md-6 time_ex">
										<input class="time end form-control" name="available_to" type="text" value="@if($row['available_to'] != '') <?php echo date("h:i:sa",strtotime($row['available_to'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div>
								<div class="form-group  " > 
									<label for="Breakfast Available From" class=" control-label col-md-4 text-left"> Breakfast Available From </label>
									<div class="col-md-6 time_ex">
										<input class="time start form-control" name="breakfast_available_from" type="text" value="@if($row['breakfast_available_from'] != '') <?php echo date("h:i:sa",strtotime($row['breakfast_available_from'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div> 	 				
								<div class="form-group  " >
									<label for="Breakfast Available To" class=" control-label col-md-4 text-left">  Breakfast Available To  </label>
									<div class="col-md-6 time_ex">
										<input class="time end form-control" name="breakfast_available_to" type="text" value="@if($row['breakfast_available_to'] != '') <?php echo date("h:i:sa",strtotime($row['breakfast_available_to'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div>
								<div class="form-group  " > 
									<label for="Lunch Available From" class=" control-label col-md-4 text-left"> Lunch Available From </label>
									<div class="col-md-6 time_ex">
										<input class="time start form-control" name="lunch_available_from" type="text" value="@if($row['lunch_available_from'] != '') <?php echo date("h:i:sa",strtotime($row['lunch_available_from'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div> 	 				
								<div class="form-group  " >
									<label for="Lunch Available To" class=" control-label col-md-4 text-left">  Lunch Available To  </label>
									<div class="col-md-6 time_ex">
										<input class="time end form-control" name="lunch_available_to" type="text" value="@if($row['lunch_available_to'] != '') <?php echo date("h:i:sa",strtotime($row['lunch_available_to'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div>
								<div class="form-group  " > 
									<label for="Dinner Available From" class=" control-label col-md-4 text-left"> Dinner Available From </label>
									<div class="col-md-6 time_ex">
										<input class="time start form-control" name="dinner_available_from" type="text" value="@if($row['dinner_available_from'] != '') <?php echo date("h:i:sa",strtotime($row['dinner_available_from'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div> 	 				
								<div class="form-group  " >
									<label for="Dinner Available To" class=" control-label col-md-4 text-left">  Dinner Available To  </label>
									<div class="col-md-6 time_ex">
										<input class="time end form-control" name="dinner_available_to" type="text" value="@if($row['dinner_available_to'] != '') <?php echo date("h:i:sa",strtotime($row['dinner_available_to'])); ?> @endif">
									</div> 
									<div class="col-md-2">
									</div>
								</div>
								<div class="form-group  " >  
									<label for="Price" class=" control-label col-md-4 text-left"> Ingredients </label>
									<div class="col-md-6">
										{!! Form::text('ingredients', $row['ingredients'],array('class'=>'form-control', 'placeholder'=>''   )) !!} 
									</div> 
									<div class="col-md-2">
									</div>
								</div> 
							</div> 
						</fieldset>
					</div>
					<div style="clear:both"></div>	
					<div class="form-group">
						<label class="col-sm-4 text-right">&nbsp;</label>
						<div class="col-sm-8">	
							<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
							<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
							<?php //$res  =$row["restaurant_id"]; ?>
							<!--<button type="button" onclick="location.href='{{ URL::to('fooditems/resdatas/'.$res.'?return='.$return)}}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>-->
							@if(\Request::segment(3) != '')
							<button type="button" onclick="location.href='{{ URL::to('fooditems/resdatas/'.$row['restaurant_id'].'?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
							@else
							<button type="button" onclick="location.href='{{ URL::to('fooditems/resdatas/'.$sresId.'?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
							@endif
						</div>	  
					</div> 
				{!! Form::close() !!}
			</div>
		</div>		 
	</div>	
</div>			 
<script type="text/javascript">
	$(document).ready(function() {
		
		// add multiple select / deselect functionality
		$("#all_days").on('ifClicked', function(event) {
			 var success = event.target.checked;
			 //alert(success);
			 if(success == true){
				$('.checkSingle').iCheck('uncheck');
			 } else {
				$('.checkSingle').iCheck('check');
			 }
		});
		// if all checkbox are selected, check the selectall checkbox and viceversa
		$(".checkSingle").on('ifChanged', function() {
			if(($(".checkSingle").length) == $(".checkSingle:checked").length) {
				$("#all_days").iCheck('check');
			} else {
				$("#all_days").iCheck("uncheck");
			}	
		});
		 
		var group_id = $('.group_id').val();
		
		if(group_id != 1){
			if(group_id != 7){
			$('#restaurant_id').select2({width:"605px"});
			} else {
			$("#restaurant_id").jCombo("{{ URL::to('fooditems/comboselect?filter=abserve_restaurants:id:name') }}",
				{  selected_value : '{{ $row["restaurant_id"] }}' });
				$('#restaurant_id').attr('readonly', true);
		    }} else {
			$("#restaurant_id").jCombo("{{ URL::to('fooditems/comboselect?filter=abserve_restaurants:id:name') }}",
				{  selected_value : '{{ $row["restaurant_id"] }}' });
		}
		/*$("#main_cat").jCombo("{{ URL::to('fooditems/comboselect?filter=abserve_food_categories:id:cat_name') }}",
		{  selected_value : '{{ $row["main_cat"] }}' });
		$("#sub_cat").jCombo("{{ URL::to('fooditems/comboselect?filter=abserve_food_categories:id:cat_name') }}",
		{  selected_value : '{{ $row["sub_cat"] }}' });*/
		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		$('#main_cat1').change(function(){
			$("#select2-chosen-5").hide();
			$.ajax({
				url: '<?php echo url(); ?>/fooditems/subcat',
				type: "GET",
				data: {'cat_id':$(this).val()},
				success: function(data){
					$('#sub_cat1').html(data);
				}
			});
		});
		$('#sub_cat1').change(function(){
			$("#select2-chosen-5").show();
		});
		
		var prod_id = $("input[name=id]").val();
		
		$('#topping_category').change(function(){
			var category = [];
			$('option:selected', $(this)).each(function() {
					
			category .push($(this).text());
			
			});
			
			if(category.length == ''){
				category .push('null');
				}
			
			
			$.ajax({ 
				url: '<?php echo url(); ?>/fooditems/toppingprice',
				type: "GET",
				data: {'topping_category':category, 'prod_id': prod_id},
				success: function(data){ //alert(data);
					$('#topping-price').html(data);
					$('#topping-price').css("display", "block");
				}
			});
		});
		
		var topping_category = [];
		$('#topping_category option:selected', $(this)).each(function() {
			topping_category .push($(this).text());
		});
		//alert(topping_typpe);
		
		if(topping_category !=''){
			$.ajax({ 
				url: '<?php echo url(); ?>/fooditems/toppingprice',
				type: "GET",
				data: {'topping_category':topping_category, 'prod_id': prod_id },
				success: function(data){ //alert(data);
					$('#topping-price').html(data);
					$('#topping-price').css("display", "block");
				}
			});
		}
		
	});
</script>
<script src="http://jonthornton.github.io/Datepair.js/dist/datepair.js"></script>
<script src="http://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>
<script>
	$('#datepairExample .time').timepicker({
		'showDuration': true,
		'timeFormat': 'g:i:sa'
	});
	$('#datepairExample').datepair();
</script>
<style type="text/css">
	.cust{position:relative;z-index:2}
	.cust .iradio_square-green{position: relative;z-index:-1;}
</style> 		 
@stop
@extends('layouts.app')

@section('content')

<link href="{{ asset('abserve/css/jquery.Jcrop.css')}}" rel="stylesheet">
<style type="text/css">
	/*.cropit-image-preview {background-color: #f8f8f8;background-size: cover;border: 1px solid #ccc;border-radius: 3px;margin-top: 7px;width: 135px;height: 172px;cursor: move;}*/
	.cropit-image-preview {background-color: #f8f8f8;background-size: cover;border: 1px solid #ccc;border-radius: 3px;margin-top: 7px;width: 250px;height: 250px;cursor: move;}
	.cropit-image-preview1 {background-color: #f8f8f8;background-size: cover;border: 1px solid #ccc;border-radius: 3px;margin-top: 7px;width: 450px;height: 450px;cursor: move;}
	.cropit-image-background {opacity: .2;cursor: auto;}
	.image-size-label {margin-top: 10px;}
</style>
<div class="page-content row">
	<!-- Page header -->
	<div class="page-header">
		<div class="page-title">
			<h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
		</div>
		<ul class="breadcrumb">
			<li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
			<li><a href="{{ URL::to('restaurant?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		<!-- <img id="uploadPreview" style="display:block;"/>
		<form action="<?php //echo url().'/restaurant/upload'?>" method="post" enctype="multipart/form-data">
			<input id="logo" type="file" name="image" />
			<input type="submit" value="Upload">

			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
		</form>  -->

		{!! Form::open(array('url'=>'restaurant/save?return='.$return, 'class'=>'form-horizontal','id'=>'res_sub','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
		<div class="col-md-6">
			<fieldset>
				<input type="hidden" value="{{$row['logo']}}" id="previous_image">
				<legend> Restaurant</legend>
				<div class="form-group hidethis " style="display:none;">
					<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
					<div class="col-md-6">
						{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					</div> 
					<div class="col-md-2"></div>
				</div> 	
				@if(Session::get('gid') ==1 || Session::get('gid') ==7)				
				<div class="form-group  " >
					<label for="Partner Id" class=" control-label col-md-4 text-left"> Partner Name <span class="asterix"> * </span></label>
					<div class="col-md-6"> 
						<select name='partner_id' rows='5' id='partner_id' class='select2 ' >
						</select>
					</div> 
					<div class="col-md-2"></div>
				</div> 
				@else
				<input type="hidden" id="partner_id" name="partner_id" value="{{Session::get('uid')}}" />
				@endif 					
				<div class="form-group  " >
					<label for="Name" class=" control-label col-md-4 text-left"> Restaurant Name <span class="asterix"> * </span></label>
					<div class="col-md-6">
						{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'id'=>'res_name'  )) !!} 
					</div> 
					<div class="col-md-2"></div>
				</div> 					
				<div class="form-group  " >
					<label for="Location" class=" control-label col-md-4 text-left"> Location <span class="asterix"> * </span></label>
					<div class="col-md-6">
						{!! Form::text('location', $row['location'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true' ,'id'=>'txtPlaces' )) !!} 
						<div id="fn_map" style="display: none;"><a href="javascript:" class="fn_map_modal">Click Here to view Exact location</a></div>
					</div> 
					<div class="col-md-2"></div>
				</div>	
              
               		
                <div class="form-group  " >
					<label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
					<div class="col-md-6"> 
                    
						<select name='region' rows='9' id='region' class='select2' >
                         
                        </select>
                        
						
                      
					</div> 
					<div class="col-md-2"></div>
				</div>
              
                
				<div class="form-group  " >
					<label for="Cuisine" class=" control-label col-md-4 text-left"> Cuisine <span class="asterix"> * </span></label>
					<div class="col-md-6">
                    	<?php 
						if($row->cuisine != '') {
							$cuisine_ids = explode(",", $row->cuisine);
						} else {
							$cuisine_ids = array();
						}
						$cuisine = array();
						foreach($cuisines as $_cuisines){
							$cuisine[] = $_cuisines->id;
							$cuisine1[$_cuisines->id] = $_cuisines->name;
						}						
						$result=array_merge($cuisine_ids,$cuisine);
						$result1= array_unique($result);
						//print_r($cuisine1);
						?>
						<select name='cuisine[]' multiple rows='5' id='cuisine' class='select2 '  required='true' >
                        	<?php
							foreach($result1 as $_cuisine){
								if(in_array($_cuisine, $cuisine_ids)){ ?>
									<option value="<?php echo $_cuisine ?>" selected><?php echo $cuisine1[$_cuisine] ?></option>
								<?php } else { ?>
									<option value="<?php echo $_cuisine ?>"><?php echo $cuisine1[$_cuisine] ?></option>
                                <?php }
							}
							?>
                        </select>
                        <input type="hidden" id="cuisine_val" name="cuisine_val" value="" />
					</div> 
					<div class="col-md-2"></div>
				</div>
				<div class="form-group  " >
					<label for="res_desc" class=" control-label col-md-4 text-left"> Description </label>
					<div class="col-md-6">
						<textarea rows="3" name="res_desc"  value="" class="form-control" style="resize: none;height: auto;">@if($row['res_desc'] != ''){!! $row['res_desc'] !!}@elseif(old('res_desc')) {{old('res_desc')}} @endif</textarea>
					</div> 
					<div class="col-md-2"></div>
				</div>                 
                <div class="form-group  " >
                    <label for="Premium Plan" class=" control-label col-md-4 text-left"> Premium Plan <span class="asterix"> * </span></label>
                    <div class="col-md-6">
                        <label class='radio radio-inline'>
                        <input type='radio' name='premium_plan' value ='No Plan' required @if($row['premium_plan'] == 'No Plan') checked="checked" @endif > No Plan </label>
                        <label class='radio radio-inline'>
                        <input type='radio' name='premium_plan' value ='Bronze' required @if($row['premium_plan'] == 'Bronze') checked="checked" @endif > Bronze </label>
                        <label class='radio radio-inline' style="margin-left: 0px;">
                        <input type='radio' name='premium_plan' value ='Silver' required @if($row['premium_plan'] == 'Silver') checked="checked" @endif > Silver </label>
                        <label class='radio radio-inline' style="margin-left: 26px;">
                        <input type='radio' name='premium_plan' value ='Gold' required @if($row['premium_plan'] == 'Gold') checked="checked" @endif > Gold </label>
                    </div> 
					<div class="col-md-2"></div>
                </div>
				<div class="form-group  " >
					<label for="Call Handaling" class=" control-label col-md-4 text-left"> Call Handling </label>
					<label class='radio radio-inline'>
						<input type='checkbox' name='call_handling' value ='1' @if($row['id'] == '') checked="checked" @endif @if($row['call_handling'] == '1') checked="checked" @endif > Yes</label>
					</div> 
					<!-- Image Cropping -->
					<div class="col-md-offset-4 col-md-8">
						<div data-toggle="modal" href="#image" id="up_image" class="btn btn-success"><b id="chang_name">Click to Upload Image</b></div>
					</div>

					<div class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header" style="background-color:#1ABC9C">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" style="color:#FFF;font-weight:bold">Edit Image</h4>
								</div>
								<div class="modal-body">

									<div class="image-editor" align="center">
										<input type="file" class="cropit-image-input btn btn-default" id="image_file">
										<div class="cropit-image-preview"></div>
										<div class="image-size-label">
											Resize image
										</div>
										<input type="range" class="cropit-image-zoom-input">
										<button class="btn btn-success" type="button" onclick="return get_image()">Export</button>
										<div id="empty_image_note" value=""></div>
									</div>


								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12" style="padding-top: 10px">
						<img id="imageid" name="imageid" class="center-block" alt="" style="border: 1px solid #ddd;width:250px;height:250px;margin-bottom:10px;"/>
						<input type="hidden" name="user_image"  id="user_image" >
					</div>			<!-- Image Cropping End -->
                    <div class="form-group  " >
                        <label for="Agreement" class=" control-label col-md-4 text-left"> Agreement </label>
                        <div class="col-md-6">
                          <input  type='file' name='agreement' id='agreement' style='width:150px !important;' disabled="disabled"  />
                          <div >{!! SiteHelpers::showUploadedFile($row['agreement'],'/uploads/restaurants/') !!}</div>					
         
                         </div> 
                         <div class="col-md-2">
                            
                         </div>
                    </div>
					<div class="form-group  " >
						<label for="Service Tax" class=" control-label col-md-4 text-left"> GST <span class="asterix"> * </span></label>
						<div class="col-md-6">
							<label class='radio radio-inline'>
							<input type='radio' name='hd_gst' value ='1' required @if($row['hd_gst'] == '1') checked="checked" @endif > HGST</label>
							<label class='radio radio-inline'>
							<input type='radio' name='hd_gst' value ='2' required @if($row['hd_gst'] == '2') checked="checked" @endif > DGST</label>
						</div> 
						<div class="col-md-2"></div>
					</div>
					<div class="form-group  " >
						<label for="Service Tax" class=" control-label col-md-4 text-left"> <!--GST <span class="asterix"> * </span>--></label>
						<div class="col-md-6">
							{!! Form::text('service_tax', $row['service_tax'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
						</div> 
						<div class="col-md-2"></div>
					</div>  
					<div class="form-group  " >
						<label for="Commission" class=" control-label col-md-4 text-left"> Commission (%) <span class="asterix"> * </span></label>
						<div class="col-md-6">
							{!! Form::text('ds_commission', $row['ds_commission'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
						</div> 
						<div class="col-md-2"></div>
					</div>                    					
                    <div class="form-group  " style="display:none;">
                      	<label for="Maximum Packaging Charge" class=" control-label col-md-4 text-left"> Maximum Packaging Charge </label>
                      	<div class="col-md-6">
                        	{!! Form::text('max_packaging_charge', $row['max_packaging_charge'],array('class'=>'form-control', 'placeholder'=>''  )) !!} 
                       	</div> 
                       	<div class="col-md-2"></div>
                    </div> 					
					<div class="form-group  " >
						<label for="Preparation Time" class=" control-label col-md-4 text-left"> Preparation Time <span class="asterix"> * </span></label>
						<div class="col-md-6">
							{!! Form::text('delivery_time', $row['delivery_time'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'mins', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
						</div> 
						<div class="col-md-2"></div>
					</div>
					<div class="form-group  " >
						<label for="Budget" class=" control-label col-md-4 text-left"> Budget <span class="asterix"> * </span></label>
						<div class="col-md-6">
							<label class='radio radio-inline'>
							<input type='radio' name='budget' value ='1' required @if($row['budget'] == '1') checked="checked" @endif > Low</label>
							<label class='radio radio-inline'>
							<input type='radio' name='budget' value ='2' required @if($row['budget'] == '2') checked="checked" @endif > Medium </label>
							<label class='radio radio-inline' style="margin-left: 0px;">
							<input type='radio' name='budget' value ='3' required @if($row['budget'] == '3') checked="checked" @endif > High </label>
							<label class='radio radio-inline' style="margin-left: 7px;">
							<input type='radio' name='budget' value ='4' required @if($row['budget'] == '4') checked="checked" @endif > Very High </label> 
						</div> 
						<div class="col-md-2"></div>
					</div> 
				</fieldset>
			</div>
			<div class="col-md-6">
				<fieldset>
					<legend> Details</legend>
                    <div class="form-group  " > 
                        <label for="Main Category" class=" control-label col-md-4 text-left"> Pure Veg <span class="asterix"> * </span></label>
                        <div class="col-md-6">                            
                            <select name='pure_veg' rows='5' id='pure_veg' class='select2 ' required>
                                <option value="" >-- Please Select --</option>
                                <option value="1" @if($row['pure_veg'] == '1') selected="selected" @endif >Yes</option>
                                <option value="0" @if($row['pure_veg'] == '0') selected="selected" @endif>No</option>                               
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="form-group  " >
						<label for="Phone" class=" control-label col-md-4 text-left"> Phone <span class="asterix"> * </span></label>
						<div class="col-md-6">
							{!! Form::text('phone', $row['phone'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number', 'id'=>'phone'  )) !!} 
						</div> 
						<div class="col-md-2"></div>
					</div> 					
                    <div class="form-group  " >
                      	<label for="Secondary Phone Number" class=" control-label col-md-4 text-left"> Secondary Phone Number1 </label>
                      	<div class="col-md-6">
                        	{!! Form::text('secondary_phone_number', $row['secondary_phone_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
                       	</div> 
                       	<div class="col-md-2"></div>
                    </div>
                    <div class="form-group  " >
                      	<label for="Secondary Phone Number" class=" control-label col-md-4 text-left"> Secondary Phone Number2 </label>
                      	<div class="col-md-6">
                        	{!! Form::text('secondary_phone_number2', $row['secondary_phone_number2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
                       	</div> 
                       	<div class="col-md-2"></div>
                    </div>
					<!--<div class="form-group  " >
						<label for="Delivery Charge" class=" control-label col-md-4 text-left"> Delivery Charge <span class="asterix"> * </span></label>
						<div class="col-md-6">
							{!! Form::text('delivery_charge', $row['delivery_charge'],array('class'=>'form-control ', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
						</div> 
						<div class="col-md-2"></div>
					</div>-->					
                    <div id="datepairExample">
                        <div class="form-group  " >
                            <label for="Opening Time" class=" control-label col-md-4 text-left"> Full Day Opening Time </label>
                            <div class="col-md-6">
                                <input class="form-control time start" name="opening_time" type="text" value="@if($row['opening_time'] != '') <?php echo $row['opening_time'];?> @endif" >
                            </div> 
                            <div class="col-md-2"></div>
                        </div> 					
                        <div class="form-group  " >
                            <label for="Closing Time" class=" control-label col-md-4 text-left"> Full Day Closing Time </label>
                            <div class="col-md-6">
                                <input class="form-control time end" name="closing_time" type="text" value="@if($row['closing_time'] != '') <?php echo $row['closing_time'];?> @endif" >
                            </div> 
                            <div class="col-md-2"></div>
                        </div>
                    </div> 
                    <div id="datepairExample">
                        <div class="form-group  " >
                            <label for="Breakfast Opening Time" class=" control-label col-md-4 text-left"> Breakfast Opening Time </label>
                            <div class="col-md-6">
                                <input class="form-control time start" name="breakfast_opening_time" type="text" value="@if($row['breakfast_opening_time'] != '') <?php echo $row['breakfast_opening_time'];?> @endif" >
                            </div> 
                            <div class="col-md-2"></div>
                        </div> 					
                        <div class="form-group  " >
                            <label for="Breakfast Closing Time" class=" control-label col-md-4 text-left"> Breakfast Closing Time </label>
                            <div class="col-md-6">
                                <input class="form-control time end" name="breakfast_closing_time" type="text" value="@if($row['breakfast_closing_time'] != '') <?php echo $row['breakfast_closing_time'];?> @endif" >
                            </div> 
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <div id="datepairExample">
                        <div class="form-group  " >
                            <label for="Lunch Opening Time" class=" control-label col-md-4 text-left"> Lunch Opening Time </label>
                            <div class="col-md-6">
                                <input class="form-control time start" name="lunch_opening_time" type="text" value="@if($row['lunch_opening_time'] != '') <?php echo $row['lunch_opening_time'];?> @endif">
                            </div> 
                            <div class="col-md-2"></div>
                        </div> 					
                        <div class="form-group  " >
                            <label for="Lunch Closing Time" class=" control-label col-md-4 text-left"> Lunch Closing Time </label>
                            <div class="col-md-6">
                                <input class="form-control time end" name="lunch_closing_time" type="text" value="@if($row['lunch_closing_time'] != '') <?php echo $row['lunch_closing_time'];?> @endif">
                            </div> 
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <div id="datepairExample">
                        <div class="form-group  " >
                            <label for="Opening Time" class=" control-label col-md-4 text-left"> Dinner Opening Time </label>
                            <div class="col-md-6">
                                <input class="form-control time start" name="dinner_opening_time" type="text" value="@if($row['dinner_opening_time'] != '') <?php echo $row['dinner_opening_time'];?> @endif">
                            </div> 
                            <div class="col-md-2"></div>
                        </div> 					
                        <div class="form-group  " >
                            <label for="Dinner Closing Time" class=" control-label col-md-4 text-left"> Dinner Closing Time</label>
                            <div class="col-md-6">
                                <input class="form-control time end" name="dinner_closing_time" type="text" value="@if($row['dinner_closing_time'] != '') <?php echo $row['dinner_closing_time'];?> @endif">
                            </div> 
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="Offer" class=" control-label col-md-4 text-left"> Offer (%) </label>
                        <div class="col-md-6">
                            {!! Form::text('offer', $row['offer'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'',   )) !!} 
                        </div> 
                        <div class="col-md-2"></div>
                    </div>					
                    <div class="form-group  " >
                        <label for="Minimum Order Value" class=" control-label col-md-4 text-left"> Minimum Order Value </label>
                        <div class="col-md-6">
                            {!! Form::text('min_order_value', $row['min_order_value'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'',   )) !!} 
                        </div> 
                        <div class="col-md-2"></div>
                    </div>					
                    <div class="form-group  " >
                        <label for="Maximum Value Apply" class=" control-label col-md-4 text-left"> Maximum Value Apply </label>
                        <div class="col-md-6">
                            {!! Form::text('max_value', $row['max_value'],array('class'=>'form-control allownumericwithoutdecimal', 'placeholder'=>'',   )) !!} 
                        </div> 
                        <div class="col-md-2"></div>
                    </div>
                    <div class="form-group  " >
                        <label for="Offer From" class=" control-label col-md-4 text-left"> Offer From </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['offer_from'] =='0000-00-00') {{--*/ $offer_from = "" /*--}} @else {{--*/ $offer_from = $row['offer_from'] /*--}} @endif
                                {!! Form::text('offer_from', $offer_from ,array('class'=>'form-control date')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                         </div> 
                         <div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group  " >
                        <label for="Offer To" class=" control-label col-md-4 text-left"> Offer To </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['offer_to'] =='0000-00-00') {{--*/ $offer_to = "" /*--}} @else {{--*/ $offer_to = $row['offer_to'] /*--}} @endif
                                {!! Form::text('offer_to', $offer_to ,array('class'=>'form-control date')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                         </div> 
                         <div class="col-md-2"></div>
                    </div>                                    
                    <div class="form-group  " >
                        <label for="Active" class=" control-label col-md-4 text-left"> Active *</label>
                        <div class="col-md-6">                                          
                            <label class='radio radio-inline'>
                            <input type='radio' name='active' value ='1' required="required"  @if($row['active'] == '1') checked="checked" @endif > {!! trans('core.abs_active') !!} </label>
                            <label class='radio radio-inline'>
                            <input type='radio' name='active' value ='0' required="required"  @if($row['active'] == '0') checked="checked" @endif > Closed </label>
                            <label class='radio radio-inline'>
                            <input type='radio' name='active' value ='2' required="required"  @if($row['active'] == '2') checked="checked" @endif > {!! trans('core.fr_minactive') !!} </label>
                        </div> 
                        <div class="col-md-2"></div>
                    </div>
                    
                    <div class="form-group  " >
                        <label for="New Start Date" class=" control-label col-md-4 text-left">New label Start date </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['new_start_date'] =='0000-00-00') {{--*/ $new_start_date = "" /*--}} @else {{--*/ $new_start_date = $row['new_start_date'] /*--}} @endif
                                {!! Form::text('new_start_date', $new_start_date ,array('class'=>'form-control date')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                         </div> 
                         <div class="col-md-2"></div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="New End date" class=" control-label col-md-4 text-left">New label End date </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['new_end_date'] =='0000-00-00') {{--*/ $new_end_date = "" /*--}} @else {{--*/ $new_end_date = $row['new_end_date'] /*--}} @endif
                                {!! Form::text('new_end_date', $new_end_date ,array('class'=>'form-control date')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                         </div> 
                         <div class="col-md-2"></div>
                    </div>
                    
                    
                   <div class="form-group  " >
					<label for="Sequence" class=" control-label col-md-4 text-left"> Restaurant Sequence </label>
					<div class="col-md-6"> 
                    <select name='res_sequence' rows='5' id='res_sequence' class='select2 ' >
                     <option value="" >-- Please Select --</option>
                   
                    <?php for($i=1; $i<=15; $i++) {   
                        if($row['res_sequence'] == $i){  ?>
						 <option value="<?php echo $row['res_sequence']; ?>" selected><?php echo $row['res_sequence']; ?></option>
						<?php  }else{   ?> 
                        <option value="<?php echo $i ?>"><?php echo $i; ?></option>
                        <?php  } } ?>
						</select>
					</div> 
					<div class="col-md-2"></div>
				</div>
                   
                  
                   
                    <div class="form-group  " >
                        <label for="Restaurant Sequence Start Date" class=" control-label col-md-4 text-left">Restaurant Sequence Start date </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['res_seq_start'] =='0000-00-00') {{--*/ $res_seq_start = "" /*--}} @else {{--*/ $res_seq_start = $row['res_seq_start'] /*--}} @endif
                                {!! Form::text('res_seq_start', $res_seq_start ,array('class'=>'form-control date')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                         </div> 
                         <div class="col-md-2"></div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="Restaurant Sequence End date" class=" control-label col-md-4 text-left">Restaurant Sequence End date </label>
                        <div class="col-md-6">                                          
                            <div class="input-group m-b" style="width:150px !important;">
                                @if($row['res_seq_end'] =='0000-00-00') {{--*/ $res_seq_end = "" /*--}} @else {{--*/ $res_seq_end = $row['res_seq_end'] /*--}} @endif
                                {!! Form::text('res_seq_end', $res_seq_end ,array('class'=>'form-control date')) !!}
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                         </div> 
                         <div class="col-md-2"></div>
                    </div>
                    
                    
                </fieldset>
            </div>

			<!-- <div class="form-group  " >
				<label for="Logo" class=" control-label col-md-1 text-left"> Logo </label>
				<div class="col-md-6">
					//<div style="width:200px;height:200px;">
					<img id="uploadPreview" style="display:block; /*width:100%;*/ "/>
					//</div>
					//<input id="uploadImage" type="file" name="image" />
					<input  type='file' name='logo' id='logo' @if($row['logo'] =='') class='required' @endif style='width:207px !important;'  />
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />

					@if($row['logo'] == '')
						<div>
							{!! SiteHelpers::showUploadedFile($row['logo'],'') !!}
						</div>
					@else
						<div>
						<p>
							<a href="<?php echo url('').'/uploads/restaurants/'.$row['logo'];?>" target="_blank" class="previewImage">
								<img src="<?php echo url('').'/uploads/restaurants/'.$row['logo'];?>" border="0" width="50" class="img-circle">
							</a>
						</p>				
						</div>
					@endif
				</div> 
				<div class="col-md-2"></div>
			</div> -->

			<input type="hidden" name="latitude" id="lat" value="{{$row['latitude']}}">
			<input type="hidden" name="longitude" id="lang" value="{{$row['longitude']}}">

			<div style="clear:both"></div>	
			<!-- <input type="text" name="placeSelect" id="placeSelect"> -->

			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm res_submit" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<input type="submit" name="submit" class="btn btn-primary btn-sm res_submit" value="{!! Lang::get('core.sb_save') !!}" ><!-- <i class="fa  fa-save "></i> -->
					<a href="{{url('restaurant')}}"><button type="button" class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i> {!! Lang::get('core.sb_cancel') !!} </button></a>
				</div>
			</div>
		 {!! Form::close() !!}
    
	</div>
</div>		 
</div>	
</div>
<script type="text/javascript" src="{{ asset('abserve/js/jquery.cropit.js') }}"></script>
<script type="text/javascript">
	function get_image(){
		var imageData = $('.image-editor').cropit('export');
		if(imageData != null){
			document.getElementById("imageid").src=imageData;
			$("#user_image").val(imageData);
			document.getElementById('chang_name').innerHTML="Change Image";
			$("#image").modal('hide');
		} else {
			$("#empty_image_note").html('<font color="red">Please choose photo</font>');
			setTimeout(function(){
				$('#empty_image_note').html('');
			}, 2000);
		}
	}
</script>
<script type="text/javascript">
	<?php if(Request::segment(3)!=''){?>
		$(document).ready(function(){
			function toDataURL(url, callback) {
				var xhr = new XMLHttpRequest();
				xhr.onload = function() {
					var reader = new FileReader();
					reader.onloadend = function() {
						callback(reader.result);
					}
					reader.readAsDataURL(xhr.response);
				};
				xhr.open('GET', url);
				xhr.responseType = 'blob';
				xhr.send();
			}

			toDataURL(base_url+'<?php echo '/uploads/restaurants/'.$row['logo']; ?>', function(dataUrl) {
	//alert(dataUrl);
	document.getElementById("imageid").src=dataUrl;
	$("#user_image").val(dataUrl);
	$(".cropit-image-preview").css('background-image','url("'+dataUrl+'")');
})

		})
		<?php } ?>
		$(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
			$(this).val($(this).val().replace(/[^\d].+/, ""));
			if(event.which == 8){

			} else if((event.which < 48 || event.which > 57 )) {
				event.preventDefault();
			}
		});	
	</script>
	<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyDIHoHvIosxw4wz4bDEKzfcPzCPFmPA5rw"></script> -->
	<script type="text/javascript">
		var IsplaceChange = true;
		$(document).ready(function () {
			
			var cuisine = $('#cuisine').val();	
			$('#cuisine_val').val(cuisine);
			
			if($('#lat').val() != '' || $('#lang').val() != '' ){
				$('#fn_map').show();
			} else {
				$('#fn_map').hide();
			}
			/*var service = new google.maps.places.AutocompleteService();
			var result = service.getQueryPredictions({ input: 'madurai' });*/
			
			var input = document.getElementById('txtPlaces');
			var autocomplete = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));

			google.maps.event.addListener(autocomplete, 'place_changed', function () {
				var place		= autocomplete.getPlace();
				var latitude	= place.geometry.location.lat();
				var longitude	= place.geometry.location.lng();
	            // alert(latitude);
	            $('#lat').val(latitude);
	            $('#lang').val(longitude);
	            IsplaceChange	= true;
	            if($('#lat').val() != '' || $('#lang').val() != '' ){
	            	$('#fn_map').show();
	            } else {
	            	$('#fn_map').hide();
	            }
	        });

			$("#txtPlaces").keydown(function () {
				IsplaceChange	= false;
			});
		    // alert(IsplaceChange);
		    $("#txtPlaces").focusout(function () {	    
		    	if (IsplaceChange) {
		    		$('#fn_map').show();
		        	// alert("fdsg"+$('#lat').val()+$('#lang').val());
		        } else {
		        	$('#lat').val('');
		        	$('#lang').val('');
		        	$("#txtPlaces").val('');
		        	$('#fn_map').hide();
		            // alert("please Enter valid location");
		        }

		    });
           
		   
		    <?php if(session()->get('gid') == '7'){ ?>
		    $("#partner_id").jCombo(base_url+"restaurant/comboselect?filter=tb_users:id:username&limit=where:group_id:=:'3'&parent=region:<?php echo session()->get('rid'); ?>",
		    {  selected_value : '{!! $row["partner_id"] !!}' });
			 <?php	}else{ ?>	
			$("#partner_id").jCombo(base_url+"restaurant/comboselect?filter=tb_users:id:username&limit=where:group_id:=:'3'",
		    	{  selected_value : '{!! $row["partner_id"] !!}' });
			 <?php	} ?>		
				
		 
		    /*$("#cuisine").jCombo("{{ URL::to('restaurant/comboselect?filter=abserve_food_cuisines:id:name') }}",
		    	{  selected_value : '{{ $row["cuisine"] }}' });*/
		    
			$('#cuisine').change(function(){
				
				var cuisine = '';
		   		var selectedDayPorts = $('#cuisine');
           		var dayPorts = selectedDayPorts.select2("data");
          		var id = '<?php echo $id;  ?>';
		   		for(var i=0; i<dayPorts.length; i++){
			 		if (typeof dayPorts[i]['id'] === "undefined") {
			 		}else{ 
				  		cuisine = cuisine+','+dayPorts[i]['id'];  
			 		}			
		   		}
				cuisine = cuisine.replace(/^,/, '');
		   		//console.log(cuisine);
				
				$('#cuisine_val').val(cuisine);		   		

			});
			
			var cuisine = [];
			$('#cuisine option:selected', $(this)).each(function() {
				cuisine .push($(this).text());
			});
			
			$('.removeCurrentFiles').on('click',function(){
		    	var removeUrl = $(this).attr('href');
		    	$.get(removeUrl,function(response){});
		    	$(this).parent('div').empty();	
		    	return false;
		    });			
		
			
		    <?php if(session()->get('gid') == '7'){ ?>
			       $("#region").jCombo(base_url+"restaurant/comboselect?filter=region:region_keyword:region_name",
		       	   {  selected_value : '{!! session()->get('rkey') !!}' });
			       $('#region').attr('readonly', true);
			 <?php	}else{ ?>
			       $("#region").jCombo(base_url+"restaurant/comboselect?filter=region:region_keyword:region_name",
		    	   {  selected_value : '{!! $row["region"] !!}' });		
			 <?php	} ?>
			 
			//$('#region').attr('readonly', true);
			$('#partner_id').click(function(){
				var partner_id = $(this).val();
				//alert(partner_id);
				$.ajax({
					url: '<?php echo url(); ?>/restaurant/resdetails',
					type: "GET",
					data: {'partner_id':$(this).val()},
					success: function(datas){
						//alert(datas);
						var data = datas.split("@@");
						$('#res_name').val(data[0]);
						$('#phone').val(data[1]);
						$('#txtPlaces').val(data[2]);
						//$('#region').val(data[3]);
						//$('.region').html(data[3]);
						}
				});
			});
			
	
			
		});
	</script>	
	<script src="https://jonthornton.github.io/Datepair.js/dist/datepair.js"></script>
	<script src="https://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>
	<script>
		$('#datepairExample .time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:i:sa'
		});

		$('#datepairExample').datepair();
	</script>	 


	<script src="https://maps.google.com/maps/api/js?libraries=places&region=in&language=en&sensor=true&key=AIzaSyD81fsN0Uc2eEJ1cCrSeWjZEYI81XbvIFU"></script>

	<!-- Modal -->
	<div id="map_modal" class="modal fade" role="dialog" >
		<div class="modal-dialog">
			<style>#myMap {max-width:100%;height: 350px;width: 520px;z-index:999999;}</style>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Map</h4>
				</div>
				<div class="modal-body">
					<div id="myMap"></div><br/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$('.image-editor').cropit();
		});

		$(".fn_map_modal").click(function(){
			initialize();
			$("#map_modal").modal("show");
		});

		var map;
		var marker;
		var myLatlng = new google.maps.LatLng($('#lat').val(),$('#lang').val());
		var geocoder = new google.maps.Geocoder();
		var infowindow = new google.maps.InfoWindow();
		function initialize(){

			var mapOptions = {
				zoom: 15,
				center: new google.maps.LatLng($('#lat').val(),$('#lang').val()),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

			marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng($('#lat').val(),$('#lang').val()),
				draggable: true 
			});     

			geocoder.geocode({'latLng': myLatlng }, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#txtPlaces').val(results[0].formatted_address);
						$('#lat').val(marker.getPosition().lat());
						$('#lang').val(marker.getPosition().lng());
                   // infowindow.setContent(results[0].formatted_address);
                    //infowindow.open(map, marker);
                }
            }
        });


			google.maps.event.addListener(marker, 'dragend', function() {

				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$('#txtPlaces').val(results[0].formatted_address);
							$('#lat').val(marker.getPosition().lat());
							$('#lang').val(marker.getPosition().lng());
                   // infowindow.setContent(results[0].formatted_address);
                   // infowindow.open(map, marker);
               }
           }
       });
			});
			google.maps.event.addListener(map, 'click', function (event) {
				$('#mlatitude').val(event.latLng.lat());
				$('#mlongitude').val(event.latLng.lng());
            // console.log(event);
            placeMarker(event.latLng);
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            	if (status == google.maps.GeocoderStatus.OK) {
            		if (results[0]) {
            			$('#txtPlaces').val(results[0].formatted_address);
            			$('#lat').val(marker.getPosition().lat());
            			$('#lang').val(marker.getPosition().lng());
                       // infowindow.setContent(results[0].formatted_address);
                       // infowindow.open(map, marker);
                   }
               }
           });
        });

		}
		google.maps.event.addDomListener(window, "resize", resizingMap());

		$('#map_modal').on('show.bs.modal', function() {
			resizeMap();
		})

		function resizeMap() {
			if(typeof map =="undefined") return;
			setTimeout( function(){resizingMap();} , 400);
		}

		function resizingMap() {
			if(typeof map =="undefined") return;
			var center = new google.maps.LatLng($('#lat').val(),$('#lang').val());
			google.maps.event.trigger(map, "resize");
			map.setCenter(center); 
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		function placeMarker(location) {
			if (marker == undefined){
				marker = new google.maps.Marker({
					position: location,
					map: map, 
					animation: google.maps.Animation.DROP,
				});
			} else {
				marker.setPosition(location);
			}
			map.setCenter(location);
		}
	</script>
	@stop
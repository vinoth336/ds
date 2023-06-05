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
		<li><a href="{{ URL::to('vendors?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'vendors/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> vendors</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  <select name='region' rows='5' id='region' class='select2 ' required  ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Delivery Type" class=" control-label col-md-4 text-left"> Delivery Type <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
					<?php $delivery_type = explode(',',$row['delivery_type']);
					$delivery_type_opt = array( 'Delivery' => 'Delivery' ,  'Relocation' => 'Relocation' , ); ?>
					<select name='delivery_type' id='delivery_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($delivery_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['delivery_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Owner Name" class=" control-label col-md-4 text-left"> Owner Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('owner_name', $row['owner_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Primary Number" class=" control-label col-md-4 text-left"> Primary Number <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('primary_number', $row['primary_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Secondary Number" class=" control-label col-md-4 text-left"> Secondary Number <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('secondary_number', $row['secondary_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> Email <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'email'   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Store Name" class=" control-label col-md-4 text-left"> Store Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('store_name', $row['store_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Address" class=" control-label col-md-4 text-left"> Address <span class="asterix"> * </span></label>
									<div class="col-md-6">									  
                         				{!! Form::text('address', $row['address'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
                                    <label for="Main Category" class=" control-label col-md-4 text-left"> Main Category <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name="tree" rows='5' id='tree' class="form-control treenew" selected required>
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>
                                 
                                 <div class="form-group  " >
                                    <label for="Sub Category" class=" control-label col-md-4 text-left"> Sub Category <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name="node[]" rows='5' id='node' class="form-control" multiple="multiple" selected required style="min-height:150px">
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>
                                 
                                 <div class="form-group  " >
                                    <label for="Sub Node Category" class=" control-label col-md-4 text-left"> Sub Node Category </label>
                                    <div class="col-md-6"> 
                                        <select name="sub_node[]" rows='5' id='sub_node' class="form-control" multiple="multiple" selected style="min-height:150px" >
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div> 						                                 				
								 <div class="form-group  " style="display:none" >
									<label for="subcat" class=" control-label col-md-4 text-left"> subcat <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('subcat_id', $row['subcat_id'],array('class'=>'form-control', 'placeholder'=>''  )) !!} 
									 </div> 
									 <div class="col-md-2">
dd									 </div>
								 </div>
								  <div class="form-group  " >
									<label for="Description" class=" control-label col-md-4 text-left"> Description </label>
									<div class="col-md-6">
									  <textarea name='description' rows='5' id='description' class='form-control '  
				           >{{ $row['description'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
                                  <div id="datepairExample">
                                      <div class="form-group  " >
                                          <label for="Start Time" class=" control-label col-md-4 text-left"> Start Time <span class="asterix"> * </span></label>
                                          <div class="col-md-6">
                                              <input class="form-control time start" name="start_time" type="text" value="@if($row['start_time'] != '') <?php echo date("h:i:sa",strtotime($row['start_time']));?> @endif" required >
                                          </div> 
                                          <div class="col-md-2"></div>
                                      </div> 					
                                      <div class="form-group  " >
                                          <label for="End Time" class=" control-label col-md-4 text-left"> End Time <span class="asterix"> * </span></label>
                                          <div class="col-md-6">
                                              <input class="form-control time end" name="end_time" type="text" value="@if($row['end_time'] != '') <?php echo date("h:i:sa",strtotime($row['end_time']));?> @endif" required >
                                          </div> 
                                          <div class="col-md-2"></div>
                                      </div>
                                  </div>					
								  <div class="form-group  " >
									<label for="Status" class=" control-label col-md-4 text-left"> Status <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  
					<?php $Status = explode(',',$row['status']);
					$Status_opt = array( '1' => 'Active' ,  '0' => 'Inactive' , ); ?>
					<select name='status' rows='5' required  class='select2 '  > 
						<?php 
						foreach($Status_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['status'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('vendors?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		   <input type="hidden" name="treeval" id="treeval" value="{{$row['tree']}}">
           <input type="hidden" name="nodeval[]" id="nodeval" value="{{$row['node']}}">
           <input type="hidden" name="subnodeval[]" id="subnodeval" value="{{$row['sub_node']}}">
           <input type="hidden" name="region1" id="region1" value="{{$row['region']}}"> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
				
		<?php if(session()->get('gid') == '7'){ ?>
			$("#region").jCombo(base_url+"vendors/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! session()->get('rid') !!}' });
		    $('#region').attr('readonly', true);
		<?php	}else{ ?>
			$("#region").jCombo(base_url+"vendors/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! $row["region"] !!}' });		
		<?php	} ?>
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
		
		/*for category start*/	
		/*for main category*/
		var region1 = $('#region1').val();
		var id = $("input[name=id]").val();
		
		if(region1 !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/regionselect',
				type: "post",
				data: {
					regionselect : region1, id : id
				},
				success: function(response) {
					$('#tree').html(response);					
				}
			});
		
		}
	  
	  	/*for sub category*/
		var region1 = $('#region1').val();
		var tree = $('#treeval').val();
	  	var sub_cat = $('#nodeval').val();
		var subcat = sub_cat.split(',');
		
		if(tree !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/treeselect',
				type: "post",
				data: {
					treeselect : tree, region_key : region1, subcat : subcat
				},
				success: function(response) {
					//alert(response);					
					$('#node').html(response);					
				}
			});
		
		}
		
		
		/*for subnode category*/
	 	var region1 = $('#region1').val();
		var node = $('#nodeval').val();
		var nodeval = node.split(',');
		var s_node = $('#subnodeval').val();
		var snode = s_node.split(',');
		//alert(snode);
		if(nodeval !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/nodeselect',
				type: "post",
				data: {
					nodeselect : nodeval, region_key : region1, snode:snode
				},
				success: function(response) {
					$('#sub_node').html(response);					
				}
			});
		
		}
		
	    /*for category end*/	
		
		
	});
	

	$('#region').change(function(){
	
		$('#tree').html("");
		//$('#node').html("");
		//$('#sub_node').html("");
	
		var regionselect = $(this).val();
		var id = $("input[name=id]").val();
		var delivery_type = $('#delivery_type').val();
		//alert(delivery_type);
		if(regionselect !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/regionselect',
				type: "post",
				data: {
					regionselect : regionselect, id : id, delivery_type : delivery_type
				},
				success: function(response) {
				//alert(response);				
					$('#tree').html(response);					
				}
			});
		
		}
	  
	});
	
	$('#delivery_type').change(function(){
	
		$('#tree').html("");
		$('#node').html("");
		$('#sub_node').html("");
	
		var regionselect = $('#region').val();
		var id = $("input[name=id]").val();
		var delivery_type = $(this).val();
		//alert(delivery_type);
		if(regionselect !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/regionselect',
				type: "post",
				data: {
					regionselect : regionselect, id : id, delivery_type : delivery_type
				},
				success: function(response) {
				//alert(response);				
					$('#tree').html(response);					
				}
			});
		
		}
	  
	});
	
	$('#tree').change(function(){
		
		$('#node').html("");
		$('#sub_node').html("");
		
		var region_key = $('#region').val();
		var treeselect = $(this).val();
		//alert(treeselect);
		if(treeselect !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/treeselect',
				type: "post",
				data: {
					treeselect : treeselect, region_key : region_key
				},
				success: function(response) {
					//alert(response);					
					$('#node').html(response);					
				}
			});
		
		}
	  
	});	
	
	$('#node').change(function(){
		
		$('#sub_node').html("");
		
		var region_key = $('#region').val();
		var nodeselect = $(this).val();
		//alert(nodeselect);
		if(nodeselect !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/vendors/nodeselect',
				type: "post",
				data: {
					nodeselect : nodeselect, region_key : region_key
				},
				success: function(response) {
					//alert(response);					
					$('#sub_node').html(response);					
				}
			});
		
		}
	  
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
@stop
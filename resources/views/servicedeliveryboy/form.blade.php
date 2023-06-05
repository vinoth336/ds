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
		<li><a href="{{ URL::to('servicedeliveryboy?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'servicedeliveryboy/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Servicedeliveryboy</legend>
									
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
                                        <select name="region" rows='5' id='region' class="form-control regionval" selected required>
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
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
                                    <?php // echo $row['node']; ?>
                                        <select name="node[]" rows='5' id='node' class="form-control" multiple="multiple" selected="selected" required style="min-height:150px">
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>
                                 
                                 <div class="form-group  " >
                                    <label for="Sub Node Category" class=" control-label col-md-4 text-left"> Sub Node Category </label>
                                    <div class="col-md-6"> 
                                        <select name="sub_node[]" rows='5' id='sub_node' class="form-control" multiple="multiple" selected="selected" style="min-height:150px" >
                                        </select>
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>	
                                 				
								 <div class="form-group  " style="display:none" >
									<label for="Phone Number" class=" control-label col-md-4 text-left"> Phone Number <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('subcat_id', $row['subcat_id'],array('class'=>'form-control', 'placeholder'=>''  )) !!} 
									 </div> 
									 <div class="col-md-2">
dd									 </div>
								 </div> 
                                 
                                 
                                 <?php /*?><div class="form-group  " >
                                   <?php //echo  $row['subcat_id' ];  ?>
								    <label for="Subcategory" class=" control-label col-md-4 text-left"> Category <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                        <select name="subcat_id" rows='5' id='subcat_id' class="form-control" selected required>
                                        <option value="">Please Select</option>
                                      <?php  foreach ($subcategory as $key => $value) {    ?>
											<option value="<?php echo $value->id; ?>" <?php if($value->id == $row['subcat_id']){echo "selected"; } ?>><?php echo $value->main_cat_name;  ?></option>
											
										<?php } ?>
                                        </select>
                                    </div> 
								   
								
                                    <div class="col-md-2"></div>
                                 </div><?php */?>
                                  					
								  <div class="form-group  " >
									<label for="Username" class=" control-label col-md-4 text-left"> Username <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('username', $row['username'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> Email <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Phone Number" class=" control-label col-md-4 text-left"> Phone Number <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('phone_number', $row['phone_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
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
                                            ?>
										</select> 
									 </div> 
									 <div class="col-md-2">
									 	
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
					<button type="button" onclick="location.href='{{ URL::to('servicedeliveryboy?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
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
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! session()->get('rid') !!}' });
		    $('#region').attr('readonly', true);
		<?php	}else{ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
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
				url: '<?php echo url(); ?>/servicedeliveryboy/regionselect',
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
		//alert(sub_cat);
		var subcat = sub_cat.split(',');
		
		if(tree !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/servicedeliveryboy/treeselect',
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
				url: '<?php echo url(); ?>/servicedeliveryboy/nodeselect',
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
		//alert(id);
		if(regionselect !=''){ 
		
			$.ajax({
				url: '<?php echo url(); ?>/servicedeliveryboy/regionselect',
				type: "post",
				data: {
					regionselect : regionselect, id : id
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
				url: '<?php echo url(); ?>/servicedeliveryboy/treeselect',
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
				url: '<?php echo url(); ?>/servicedeliveryboy/nodeselect',
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
@stop
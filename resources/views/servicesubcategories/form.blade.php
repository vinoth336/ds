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
		<li><a href="{{ URL::to('servicesubcategories?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'servicesubcategories/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Service Sub Categories</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Main Category" class=" control-label col-md-4 text-left"> Main Category <span class="asterix"> * </span></label>
									<div class="col-md-6">
                                      <select name='cat_id' rows='5' id='cat_id' class='select2 ' required>
                                      	<option value="">--Please Select--</option>
                                      	<?php 
										foreach($main_cat as $maincat){
											$selected ="";
											if($maincat->id == $row['cat_id']){
												$selected = "selected='selected'";
											}?>
											<option value="<?php echo $maincat->id; ?>" <?php echo $selected; ?>><?php echo $maincat->cat_name; ?></option>
										<?php }?>
                                      </select>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Sub Category" class=" control-label col-md-4 text-left"> Sub Category <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('cat_name', $row['cat_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Main Cat Name" class=" control-label col-md-4 text-left"> Main Cat Name </label>
									<div class="col-md-6">
									  {!! Form::text('main_cat_name', $row['main_cat_name'],array('class'=>'form-control main_cat_name', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Level" class=" control-label col-md-4 text-left"> Level </label>
									<div class="col-md-6">
									  <!--{!! Form::text('level', $row['level'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} -->
                                      <input type="text" name="level" class="form-control placeholder" value="1"  />
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
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
								  <div class="form-group  " >
									<label for="Buffer Time" class=" control-label col-md-4 text-left"> Buffer Time </label>
									<div class="col-md-6">
									  
					<?php $buffer_time = explode(',',$row['buffer_time']);
					$buffer_time_opt = array( '0' => '0 Hour' ,  '1' => '1 Hour' ,  '2' => '2 Hours' ,  '3' => '3 Hours' ,  '4' => '4 Hours' ,  '5' => '5 Hours' , ); ?>
					<select name='buffer_time' rows='5'   class='select2 '  > 
						<?php 
						foreach($buffer_time_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['buffer_time'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
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
					<button type="button" onclick="location.href='{{ URL::to('servicesubcategories?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		/*$("#cat_id").jCombo("{{ URL::to('servicesubcategories/comboselect?filter=service_categories:id:cat_name') }}",
		{  selected_value : '{{ $row["cat_id"] }}' });*/
		
		
		$("#cat_id").on('change', function () {
			var main_cat = $("#cat_id :selected").text();
			$(".main_cat_name").val(main_cat);
		});
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
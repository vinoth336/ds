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
		<li><a href="{{ URL::to('lbstudleaves?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'lbstudleaves/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> LB Student Leaves</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-6">
									  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group ">
									<label for="Customer Name" class=" control-label col-md-4 text-left"> Customer Name </label>
									<div class="col-md-6">
                                    	<select name='cust_id' rows='5' id='cust_id' class='select2 '   ></select>
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Student Name" class=" control-label col-md-4 text-left"> Student Name </label>
									<div class="col-md-6">
									  <select name='stud_id' rows='5' id='stud_id' class='select2 '   ></select> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Leave Date From" class=" control-label col-md-4 text-left"> Leave Date From </label>
									<div class="col-md-6">
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('leave_date_from', $row['leave_date_from'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Leave Date To" class=" control-label col-md-4 text-left"> Leave Date To </label>
									<div class="col-md-6">
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('leave_date_to', $row['leave_date_to'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Status" class=" control-label col-md-4 text-left"> Status </label>
									<div class="col-md-6">
									  
					<?php $status = explode(',',$row['status']);
					$status_opt = array( '1' => 'Active' ,  '0' => 'Inactive' , ); ?>
					<select name='status' rows='5'   class='select2 '  > 
						<?php 
						foreach($status_opt as $key=>$val)
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
					<!--<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>-->
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('lbstudleaves?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#cust_id").jCombo("{{ URL::to('lbstudleaves/comboselect?filter=lunch_box_customers:id:first_name') }}",
		{  selected_value : '{{ $row["cust_id"] }}' });
		
		<?php if($row['id'] != ''){ ?>
		$("#stud_id").jCombo("{{ URL::to('lbstudleaves/comboselect?filter=lunch_box_student_info:id:stud_name') }}",
		{  selected_value : '{{ $row["stud_id"] }}' });
		<?php } ?>
		
		$('#cust_id').click(function(){
				
			$.ajax({
				url: '<?php echo url(); ?>/lbstudleaves/studdetails',
				type: "GET",
				data: {'cust_id':$(this).val()},
				success: function(datas){
					//alert(datas);
					$('#stud_id').html(datas);
				}
			});
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
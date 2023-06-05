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
		<li><a href="{{ URL::to('foodsubcategories?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'foodsubcategories/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> foodsubcategories</legend>
									
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
									  <select name='root_id' rows='5' id='root_id' class='select2 ' style="display:none;"  ></select>
									  <?php if($row->root_id !=''){?> 
                                          <select name='root_id' rows='5' id='root_id' class='select2 ' required >
                                            <option value="" >-- Please Select --</option>
                                            @foreach($main_categories as $main_cat)
                                            @if($row->root_id == $main_cat->id)
                                            <option value="{{$main_cat->id}}" selected>{{ $main_cat->cat_name }}</option>
                                            @else
                                            <option value="{{$main_cat->id}}">{{ $main_cat->cat_name }}</option>
                                            @endif
                                            @endforeach
                                          </select>
									  <?php } elseif($row->root_id == 0){?>
									  	<select name='root_id' rows='5' id='root_id' class='select2 ' required >
									  	<option value="" >-- Please Select --</option>
									  	@foreach($main_categories as $main_cat)
									  	
									  	<option value="{{$main_cat->id}}">{{ $main_cat->cat_name }}</option>
									  	
									  	@endforeach
									  	</select>
									  <?php }?> 
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
								  </div> </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
					<button type="button" onclick="location.href='{{ URL::to('foodsubcategories?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#root_id").jCombo("{{ URL::to('foodsubcategories/comboselect?filter=abserve_food_categories::cat_name') }}",
		{  selected_value : '{{ $row["root_id"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop
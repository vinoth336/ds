@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> {!! trans('core.abs_dashboard') !!} </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight restaurant_page">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> {!! trans('core.abs_clr_search') !!} </a>
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('restaurant/update') }}" class="tips btn btn-sm btn-white hidden"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white hidden" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<!--<a href="{{ URL::to( 'restaurant/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>-->				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('restaurant/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif            
        
        
        	<label for="Region" class=" control-label col-md-1 text-left" style="text-align:end;"> Region : </label>
		 	<div class="col-md-3">
            	<?php $get_region = explode(":",$_GET['search']); ?>
                <select rows='3' class='form-control regionselect' id="regionselect">
                  	<?php if(session()->get('gid') == '1'){ ?>
                     	<option value="" selected>All region</option>  
                  	<?php } ?>
                                
                    <?php foreach($regions as $region) {  ?>
                        <option value="<?php echo $region->region_keyword;  ?>" <?php if($get_region[2] == $region->region_keyword){ echo "selected"; }  ?>><?php echo $region->region_name;  ?></option>
					<?php }  ?> 
                </select>
			</div>
		
		 
		</div>
	
	
	 {!! Form::open(array('url'=>'restaurant/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <div class="table-responsive1" style="min-height:300px;">
     <?php //echo print_r($rowData); ?>
		@foreach ($rowData as $row)
       
        <?php  $current_date =  strtotime(date("Y-m-d")); 
	  	$start_date = strtotime($row->new_start_date);
	 	$end_date = strtotime($row->new_end_date);
		 ?>
        
			<div class="restaurant_block">
        <?php   if($row->new_start_date && $row->new_end_date != 0){
		        if($current_date >= $start_date && $current_date <= $end_date){   ?>
		         <span class="label new">New</span>
	    <?php 	}}  ?>
		
				<div class="restaurant_hide_icon">
					<i class="fa fa-circle"></i>
					<i class="fa fa-circle"></i>
					<i class="fa fa-circle"></i>
				</div>
				<div class="restaurant_hide_icon_block">
					<a href="{{URL::to('restaurant/update/'.$row->id.'?return='.$return)}}"  class="previewImage">{!! trans('core.btn_edit') !!}</a>
					<!-- <a href="{{URL::to('restaurant/update/'.$row->id.'?return='.$return)}}"  class="previewImage">Delete</a> -->
					<a class="cdelete" data-href="{{url('restaurant/resdelete/'.$row->id.'?return='.$return)}}" data-toggle="modal" data-target="#confirm-delete1">{!! trans('core.btn_delete') !!}</a>
				</div>
				<div class="image_blk">
					@if($row->logo != '')
					<a href="<?php echo url('').'/uploads/restaurants/'.$row->logo;?>" target="_blank" class="previewImage">
						<img src="<?php echo url('').'/uploads/restaurants/'.$row->logo;?>">
					</a>
					@else
					<a>
						<img src="<?php echo url('uploads/images/no-image.png')?>">
					</a>
					@endif
				</div>
				<a class="rest_name_link" href="{{ URL::to('restaurant/show/'.$row->id.'?return='.$return)}}">{{$row->name}}</a>
				<div class="star_rat">
					<?php $over_all = $model->resrating($row->id);?>
					@for($a = 0; $a < $over_all; $a ++)
						<i class="fa fa-star"></i>
					@endfor
					@for($a = 0; $a < (5-($over_all)); $a++)
						<i class="fa fa-star-o"></i>
					@endfor
					<span>
						@for($a = 0; $a < $row->budget; $a ++)
							<i class="fa fa-rupee active"></i>
						@endfor
						@for($a = 0; $a < (4-($row->budget)); $a++)
							<i class="fa fa-rupee"></i>
						@endfor
					</span>
				</div>
                 <div class="shine"></div>
			</div>
		@endforeach
		@if($access['is_add'] ==1)
			<div class="create_restaurant"><a href="{{ URL::to('restaurant/update') }}"> <i class="fa fa-plus-circle" aria-hidden="true"></i><div>{!! trans('core.abs_add_res') !!}</div></a></div>
		@endif
		<!--<table class="table table-striped " style="display: none;">
	        <thead>
				<tr>
					<th class="number"> {!! trans('core.abs_no') !!} </th>
					<th> <input type="checkbox" class="checkall" /></th>
					
					@foreach ($tableGrid as $t)
						@if($t['view'] =='1')				
							<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
							@if(SiteHelpers::filterColumn($limited ))
							
								<th>{{ $t['label'] }}</th>			
							@endif 
						@endif
					@endforeach
					<th width="70" >{!! Lang::get('core.btn_action') !!}</th>
				  </tr>
	        </thead>

	        <tbody>        						
	            @foreach ($rowData as $row)
	                <tr>
						<td width="30"> {{ ++$i }} </td>
						<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>									
					 @foreach ($tableGrid as $field)
						 @if($field['view'] =='1')
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
							 <td>	
							 	@if($field['attribute']['image']['active'] =='1')
									{!! SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) !!}
								@else	
									{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
									{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}	
								@endif						 
							 </td>
							@endif	
						 @endif					 
					 @endforeach
					 <td>
						 	@if($access['is_detail'] ==1)
							<a href="{{ URL::to('restaurant/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{!! Lang::get('core.btn_view') !!}"><i class="fa  fa-search "></i></a>
							@endif
							@if($access['is_edit'] ==1)
							<a  href="{{ URL::to('restaurant/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit "></i></a>
							@endif
					</td>				 
	                </tr>
					
	            @endforeach
	              
	        </tbody>
		</table>-->
		<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>	
	</div>	  
</div>
<script>
$(document).on("click",'.cdelete',function(e){
	$('.restaurant_hide_icon_block').removeClass('active');
    $('#confirm-delete1').toggleClass('in').toggle();
    $('#confirm-delete1').find(".btn-ok").attr("href",$(this).data("href"));
});
$(document).on("click",'#confirm-delete1 #cls_popup',function(e){
	$('.restaurant_hide_icon_block').removeClass('active');
    $('#confirm-delete1').toggleClass('in').toggle();
});
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("restaurant/multisearch")}}');
		$('#AbserveTable').submit();
	});
	$('.restaurant_hide_icon').click(function(){
		$('.restaurant_hide_icon_block').removeClass('active');
		$(this).next('.restaurant_hide_icon_block').toggleClass('active');
	});
	
	$('#regionselect').change(function(){	
    	var regionselect = $(this).val();
		if(regionselect !=''){
			window.location.href = "<?php echo url(); ?>/restaurant?search=region:equal:" + regionselect;	
		} else {
			window.location.href = "<?php echo url(); ?>/restaurant";
		}
    });
});	
</script>
<style>
.label {
    color: white;
}

.new {background-color: red; position:absolute;} /* Green */

</style>		
@stop
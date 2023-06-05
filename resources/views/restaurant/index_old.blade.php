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
        <li><a href="{{ URL::to('dashboard') }}"> {!! trans('core.m_dashboard') !!} </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> {!! trans('core.abs_clr_search') !!} </a>
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white hidden" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<a href="{{ URL::to( 'restaurant/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('restaurant/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif			
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'restaurant/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
	 <?php $group_id = \Auth::user()->group_id; ?>
	 @if($group_id == "1" )
	  @foreach ($rowData as $row)
	  	<div class="">
			<p>
				<a href="{{ URL::to('restaurant/update/'.$row->id.'?return='.$return) }}"  class="previewImage">
					<img src="<?php echo url('').'/uploads/restaurants/'.$row->logo;?>" border="0" width="50" class="img-circle">
				</a>
			</p>
			<p>{{$row->name}}</p>
			<div><i class="fa fa-star" ></i><i class="fa fa-star" ></i><i class="fa fa-star" ></i></div>
		</div>
	@endforeach
	@if($access['is_add'] ==1)
		<div class="create_restaurant"><a href="{{ URL::to('restaurant/update') }}" <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Restaurant</a></div>
	@endif  
	
	    <table class="table table-striped " style="display: none;">
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
	    </table>
	 @else
	 <?php 
		$user_id = \Auth::user()->id; 
		$results = \DB::select("SELECT * FROM `abserve_restaurants` WHERE `partner_id` = '".$user_id."'ORDER BY id desc");
		// echo "<pre>";print_r($results ); exit();
	 ?>	
	   @foreach ($results as $row)
	 <p>
		<a href="{{ URL::to('restaurant/update/'.$row->id.'?return='.$return) }}"  class="previewImage">
			<img src="<?php echo url('').'/uploads/restaurants/'.$row->logo;?>" border="0" width="50" class="img-circle">
		</a>
	</p>
	<p>{{$row->name}}</p>
	<div><i class="fa fa-star" ></i><i class="fa fa-star" ></i><i class="fa fa-star" ></i></div>
	@endforeach
	@if($access['is_add'] ==1)
		<div class="create_restaurant"><a href="{{ URL::to('restaurant/update') }}" <i class="fa fa-plus-circle" aria-hidden="true"></i> {!! trans('core.abs_add_res') !!}</a></div>
	@endif  
	 	<table class="table table-striped " style="display:none;">
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
	            @foreach ($results as $row)
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

<script src ="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src ="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
        $(document).ready(function() {
            var audioElement = document.createElement('audio');
            audioElement.setAttribute('src', 'audio.mp3');
            audioElement.setAttribute('autoplay', 'autoplay');
            //audioElement.load()

            $.get();

            audioElement.addEventListener("load", function() {
                audioElement.play();
            }, true);

            $('.play').click(function() {
                audioElement.play();
            });

            $('.pause').click(function() {
                audioElement.pause();
            });
        });
    </script>
    



					
	            @endforeach
	              
	        </tbody>
	 	</table>
     @endif
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>	
	</div>	  
</div>
<script src ="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src ="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
        $(document).ready(function() {
            var audioElement = document.createElement('audio');
            audioElement.setAttribute('src', 'audio.mp3');
            audioElement.setAttribute('autoplay', 'autoplay');
            //audioElement.load()

            $.get();

            audioElement.addEventListener("load", function() {
                audioElement.play();
            }, true);

            $('.play').click(function() {
                audioElement.play();
            });

            $('.pause').click(function() {
                audioElement.pause();
            });
        });
    </script>
    



<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("restaurant/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
});	
</script>




@stop
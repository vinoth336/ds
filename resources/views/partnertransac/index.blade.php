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
	</div>
	<div class="sbox-content"> 	
	
	 {!! Form::open(array('url'=>'partnertransac/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table table-striped ">
        <thead>
			<tr>
				<th class="number"> {!! trans('core.abs_no') !!} </th>
			<!-- 	<th> <input type="checkbox" class="checkall" /></th> -->
				
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
				<!-- 	<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>		 -->							
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
						 <td>					 
						 	@if($field['attribute']['image']['active'] =='1')
								{!! SiteHelpers::showUploadedFile($row->{$field['field']},$field['attribute']['image']['path']) !!}
							@else	
								{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
								{!! SiteHelpers::gridDisplay($row->{$field['field']},$field['field'],$conn) !!}	
							@endif						 
						 </td>
						@endif	
					 @endif					 
				 @endforeach
				 <td>

					 	@if($access['is_detail'] ==1)
						<a href="{{ URL::to('partnertransac/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="Transfer">{!! trans('core.abs_transfer') !!}</a>
						@endif									
					
				</td>				 
                </tr>
				
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("partnertransac/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
});	
</script>		
@stop
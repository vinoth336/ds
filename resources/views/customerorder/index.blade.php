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
        <li><a href="{{ URL::to('dashboard') }}"> {!! trans('core.abs_Dashboard') !!} </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> {!! trans('core.abs_Clear_Search') !!} </a>
		@if(Session::get('gid') ==1)
			 <a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a> 
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			<!-- @if($access['is_add'] ==1)
	   		<a href="{{ URL::to('customerorder/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif -->  
			<!--@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif--> 
			<a href="{{ URL::to( 'customerorder/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('customerorder/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif			
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'customerorder/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table table-striped ">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')				
						<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))						
							@if($t['label'] !='Id')
								@if($t['label'] =='Restaurant Id')
                                	<th>Restaurant Name</th>
                                @else
									<th>{{ $t['label'] }}</th>
                                @endif
                            @endif
						@endif 
					@endif
				@endforeach
				<!--<th width="70" >{!! Lang::get('core.btn_action') !!}</th>-->
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
						 	@if($field['field'] !='id')
                             <td>					 
                                @if($field['attribute']['image']['active'] =='1')
                                    {!! SiteHelpers::showUploadedFile($row->{$field['field']},$field['attribute']['image']['path']) !!}
                                @elseif($field['field'] =='res_id')
                                	<?php $resname = \SiteHelpers::getRestaurantName($row->res_id);?>{{$resname}}
                                @elseif($field['field'] =='orderid')
                                	<?php $region = \SiteHelpers::getRegionKeyword($row->res_id);?>
                                    #{{$region.$row->orderid}}                           
                                @elseif($field['field'] =='order_value')
                                    {{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
                                    <?php $order_values = explode('+', $row->{$field['field']});
                                    $order_value = array_sum($order_values); ?>
                                    {!! SiteHelpers::gridDisplay($order_value,$field['field'],$conn) !!}
                                @elseif($field['field'] =='order_status')
                                    @if($row->order_status == 1 )
                                    <span class="label status label-success">Accepted by Restaurant</span>
                                    @elseif($row->order_status == 2)
                                         <span class="label status label-primary">Accepted by Boy</span>
                                    @elseif($row->order_status == 3)
                                         <span class="label status label-default">Order Dispatch</span>
                                    @elseif($row->order_status == 4)
                                         <span class="label status label-info">Order Finished</span>
                                    @elseif($row->order_status == 5)
                                        <span class="label status label-danger">Rejected by Restaurant</span>
                                    @elseif($row->order_status == 6)
										<span class="label status label-danger">Rejected by Admin</span>
                                    @elseif($row->order_status == 7)
                                        <span class="label status label-warning">Payment Pending</span>
                                    @elseif($row->order_status == 8)
                                        <span class="label status label-danger">Payment Aborted</span>
                                    @elseif($row->order_status == 9)
                                        <span class="label status label-danger">Payment Failure</span>
                                    @elseif($row->order_status == 10)
                                        <span class="label status label-danger">Order Canceled</span>                                    
                                    @else
                                        <span class="label label-warning status">Pending</span>
                                    @endif
                                @else
                                    {!! SiteHelpers::gridDisplay($row->{$field['field']},$field['field'],$conn) !!}                               
                                @endif						 
                             </td>
                        	@endif
						@endif	
					 @endif					 
				 @endforeach
				 <!--<td>
					 	@if($access['is_detail'] ==1)
						<a href="{{ URL::to('customerorder/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{!! Lang::get('core.btn_view') !!}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ URL::to('customerorder/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit "></i></a>
						@endif
												
					
				</td>-->				 
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
		$('#AbserveTable').attr('action','{{ URL::to("customerorder/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	/*setTimeout(function() {
	  location.reload();
	}, 30000);*/
	
});	
</script>		
@stop
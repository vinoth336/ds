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
		<li><a href="{{ URL::to('fooditems?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('fooditems?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('fooditems/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! La!!g::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_id') !!}</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>  
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_restaurant_id') !!}</td>
						<td>{{ $row->restaurant_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_food_item') !!}</td>
						<td>{{ $row->food_item }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_desc') !!}</td>
						<td>{{ $row->description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_price') !!}</td>
						<td>{{ $row->price }} </td>
						
					</tr>
				
					<tr> 
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_status') !!}</td>
						<td>{{ $row->status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_available_from') !!}</td>
						<td>{{ $row->available_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_available_to') !!}</td>
						<td>{{ $row->available_to }} </td>
						
					</tr>
				
					<tr> 
						<td width='30%' class='label-view text-right'> {!! trans('core.abs_item_count') !!}</td>
						<td>{{ $row->item_count }} </td>
						
					</tr>
				
					<tr>  
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_main_category') !!}</td>
						<td>{{ $row->main_cat }} </td>
						
					</tr>
				
					<tr>  
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_sub_category') !!}</td>
						<td>{{ $row->sub_cat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_recommended_item') !!}</td>
						<td>{{ $row->recommended }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
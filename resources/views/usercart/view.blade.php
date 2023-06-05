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
		<li><a href="{{ URL::to('usercart?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('usercart?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('usercart/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_user_id') !!}</td>
						<td>{{ $row->user_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_res_id') !!}</td>
						<td>{{ $row->res_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_food_id') !!}</td>
						<td>{{ $row->food_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_food_item') !!}</td>
						<td>{{ $row->food_item }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_price') !!}</td>
						<td>{{ $row->price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_quantity') !!}</td>
						<td>{{ $row->quantity }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
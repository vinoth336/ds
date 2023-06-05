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
		<li><a href="{{ URL::to('notification?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('notification?return='.$return) }}" class="tips btn btn-xs btn-default" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('notification/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_id') !!}</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_user_id') !!}</td>
						<td>{{ $row->userid }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_url') !!}</td>
						<td>{{ $row->url }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_note') !!}</td>
						<td>{{ $row->note }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_created') !!}</td>
						<td>{{ $row->created }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_icon') !!}</td>
						<td>{{ $row->icon }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_is_read') !!}</td>
						<td>{{ $row->is_read }} </td>
						
					</tr>
				
		</tbody>	
	</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
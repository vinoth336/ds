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
		<li><a href="{{ URL::to('paypalsettings?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('paypalsettings?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('paypalsettings/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>{!! trans('core.username') !!}</td>
						<td>{{ $row->username }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.password') !!}</td>
						<td>{{ $row->password }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_signature') !!}</td>
						<td>{{ $row->signature }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_url_setting') !!}</td>
						<td>{{ $row->url_setting }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
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
		<li><a href="{{ URL::to('vendors?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('vendors?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('vendors/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>Id</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Owner Name</td>
						<td>{{ $row->owner_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Primary Number</td>
						<td>{{ $row->primary_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Number</td>
						<td>{{ $row->secondary_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Email</td>
						<td>{{ $row->email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Store Name</td>
						<td>{{ $row->store_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Address</td>
						<td>{{ $row->address }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Tree</td>
						<td>{{ $row->tree }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Node</td>
						<td>{{ $row->node }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Sub Node</td>
						<td>{{ $row->sub_node }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subcat Id</td>
						<td>{{ $row->subcat_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Description</td>
						<td>{{ $row->description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Start Time</td>
						<td>{{ $row->start_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>End Time</td>
						<td>{{ $row->end_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Type</td>
						<td>{{ $row->delivery_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Region</td>
						<td>{{ $row->region }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Status</td>
						<td>{{ $row->status }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
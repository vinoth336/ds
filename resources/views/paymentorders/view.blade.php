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
		<li><a href="{{ URL::to('paymentorders?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('paymentorders?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('paymentorders/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>Order Id</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Cust Name</td>
						<td>{!! SiteHelpers::gridDisplayView($row->cust_id,'cust_id','1:tb_users:id:username') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Name</td>
						<td>{!! SiteHelpers::gridDisplayView($row->res_id,'res_id','1:abserve_restaurants:id:name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Grand Total</td>
						<td>{{ $row->grand_total }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Order Status</td>
						<td>{{ $row->status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Time</td>
						<td>{{ $row->time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Date</td>
						<td>{{ $row->date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Payment Status</td>
						<td>{{ $row->delivery }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Via</td>
						<td>{{ $row->delivery_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Lat</td>
						<td>{{ $row->lat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Lang</td>
						<td>{{ $row->lang }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
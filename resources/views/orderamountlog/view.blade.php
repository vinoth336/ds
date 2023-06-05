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
		<li><a href="{{ URL::to('orderamountlog?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('orderamountlog?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('orderamountlog/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>Orderid</td>
						<td>{{ $row->orderid }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Total Price</td>
						<td>{{ $row->total_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Ds Commission</td>
						<td>{{ $row->ds_commission }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>S Tax</td>
						<td>{{ $row->s_tax }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Price</td>
						<td>{{ $row->coupon_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer Price</td>
						<td>{{ $row->offer_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Packaging Charge</td>
						<td>{{ $row->packaging_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Charge</td>
						<td>{{ $row->delivery_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Grand Total</td>
						<td>{{ $row->grand_total }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Id</td>
						<td>{{ $row->res_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Date Time</td>
						<td>{{ $row->date_time }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
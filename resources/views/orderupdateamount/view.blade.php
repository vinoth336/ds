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
		<li><a href="{{ URL::to('orderupdateamount?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('orderupdateamount?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('orderupdateamount/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>Cust Id</td>
						<td>{{ $row->cust_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Id</td>
						<td>{{ $row->res_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Reference No</td>
						<td>{{ $row->reference_no }} </td>
						
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
						<td width='30%' class='label-view text-right'>Hd Gst</td>
						<td>{{ $row->hd_gst }} </td>
						
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
						<td width='30%' class='label-view text-right'>Grand Total</td>
						<td>{{ $row->grand_total }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Updated Grand Total</td>
						<td>{{ $row->updated_grand_total }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Address</td>
						<td>{{ $row->address }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Building</td>
						<td>{{ $row->building }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Landmark</td>
						<td>{{ $row->landmark }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Status</td>
						<td>{{ $row->status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Id</td>
						<td>{{ $row->coupon_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Type</td>
						<td>{{ $row->coupon_type }} </td>
						
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
						<td width='30%' class='label-view text-right'>Packaging Charge</td>
						<td>{{ $row->packaging_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Charge</td>
						<td>{{ $row->delivery_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery</td>
						<td>{{ $row->delivery }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Type</td>
						<td>{{ $row->delivery_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Mop</td>
						<td>{{ $row->mop }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Order Reject Desc</td>
						<td>{{ $row->order_reject_desc }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Instructions</td>
						<td>{{ $row->instructions }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Lat</td>
						<td>{{ $row->lat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Lang</td>
						<td>{{ $row->lang }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Flag</td>
						<td>{{ $row->flag }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Rating Flag</td>
						<td>{{ $row->rating_flag }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
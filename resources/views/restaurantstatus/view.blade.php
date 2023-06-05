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
		<li><a href="{{ URL::to('restaurantstatus?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('restaurantstatus?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('restaurantstatus/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>Name</td>
						<td>{{ $row->name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Location</td>
						<td>{{ $row->location }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Logo</td>
						<td>{{ $row->logo }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Partner Id</td>
						<td>{{ $row->partner_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Premium Plan</td>
						<td>{{ $row->premium_plan }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Opening Time</td>
						<td>{{ $row->opening_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Closing Time</td>
						<td>{{ $row->closing_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Breakfast Opening Time</td>
						<td>{{ $row->breakfast_opening_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Breakfast Closing Time</td>
						<td>{{ $row->breakfast_closing_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Lunch Opening Time</td>
						<td>{{ $row->lunch_opening_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Lunch Closing Time</td>
						<td>{{ $row->lunch_closing_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Dinner Opening Time</td>
						<td>{{ $row->dinner_opening_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Dinner Closing Time</td>
						<td>{{ $row->dinner_closing_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Phone</td>
						<td>{{ $row->phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Phone Number</td>
						<td>{{ $row->secondary_phone_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Phone Number2</td>
						<td>{{ $row->secondary_phone_number2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Service Tax</td>
						<td>{{ $row->service_tax }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Ds Commission</td>
						<td>{{ $row->ds_commission }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Max Packaging Charge</td>
						<td>{{ $row->max_packaging_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Charge</td>
						<td>{{ $row->delivery_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Vat</td>
						<td>{{ $row->vat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Cuisine</td>
						<td>{{ $row->cuisine }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Call Handling</td>
						<td>{{ $row->call_handling }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Time</td>
						<td>{{ $row->delivery_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pure Veg</td>
						<td>{{ $row->pure_veg }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer</td>
						<td>{{ $row->offer }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Min Order Value</td>
						<td>{{ $row->min_order_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Max Value</td>
						<td>{{ $row->max_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer From</td>
						<td>{{ $row->offer_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer To</td>
						<td>{{ $row->offer_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Budget</td>
						<td>{{ $row->budget }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Rating</td>
						<td>{{ $row->rating }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Agreement</td>
						<td>{{ $row->agreement }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Agreement Status</td>
						<td>{{ $row->agreement_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Entry By</td>
						<td>{{ $row->entry_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Latitude</td>
						<td>{{ $row->latitude }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Longitude</td>
						<td>{{ $row->longitude }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Region</td>
						<td>{{ $row->region }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Desc</td>
						<td>{{ $row->res_desc }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Active</td>
						<td>{{ $row->active }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>New Start Date</td>
						<td>{{ $row->new_start_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>New End Date</td>
						<td>{{ $row->new_end_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Sequence</td>
						<td>{{ $row->res_sequence }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Seq Start</td>
						<td>{{ $row->res_seq_start }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Seq End</td>
						<td>{{ $row->res_seq_end }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
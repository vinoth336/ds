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
		<li><a href="{{ URL::to('lbstudent?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('lbstudent?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('lbstudent/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>User Id</td>
						<td>{{ $row->user_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Stud Name</td>
						<td>{{ $row->stud_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Standard</td>
						<td>{{ $row->standard }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Section</td>
						<td>{{ $row->section }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Dob</td>
						<td>{{ $row->dob }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pickup Time</td>
						<td>{{ $row->pickup_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Return Time</td>
						<td>{{ $row->return_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Permanent Address</td>
						<td>{{ $row->permanent_address }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pickup Address</td>
						<td>{{ $row->pickup_address }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Address Change Status</td>
						<td>{{ $row->address_change_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Permanent Lat</td>
						<td>{{ $row->permanent_lat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Permanent Lang</td>
						<td>{{ $row->permanent_lang }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pickup Lat</td>
						<td>{{ $row->pickup_lat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pickup Lang</td>
						<td>{{ $row->pickup_lang }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Permanent Pin Code</td>
						<td>{{ $row->permanent_pin_code }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pickup Pin Code</td>
						<td>{{ $row->pickup_pin_code }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>School Id</td>
						<td>{{ $row->school_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subscription Plan</td>
						<td>{{ $row->subscription_plan }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Plan From</td>
						<td>{{ $row->plan_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Plan To</td>
						<td>{{ $row->plan_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Type</td>
						<td>{{ $row->delivery_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Charge</td>
						<td>{{ $row->delivery_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offers Id</td>
						<td>{{ $row->offers_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer Price</td>
						<td>{{ $row->offer_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Total Price</td>
						<td>{{ $row->total_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subscription Planid</td>
						<td>{{ $row->subscription_planid }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Plan Id</td>
						<td>{{ $row->plan_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Payment Status</td>
						<td>{{ $row->payment_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Duration</td>
						<td>{{ $row->duration }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pickuptime Checked</td>
						<td>{{ $row->pickuptime_checked }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Notification Time</td>
						<td>{{ $row->notification_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Zone</td>
						<td>{{ $row->zone }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
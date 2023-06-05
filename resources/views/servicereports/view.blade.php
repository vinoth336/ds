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
		<li><a href="{{ URL::to('servicereports?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('servicereports?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('servicereports/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>User Id</td>
						<td>{{ $row->user_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Cust Id</td>
						<td>{{ $row->cust_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>C Phone Number</td>
						<td>{{ $row->c_phone_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Email</td>
						<td>{{ $row->email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Location</td>
						<td>{{ $row->location }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>To Location</td>
						<td>{{ $row->to_location }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pin Code</td>
						<td>{{ $row->pin_code }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>To Pin Code</td>
						<td>{{ $row->to_pin_code }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Comments</td>
						<td>{{ $row->comments }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Description</td>
						<td>{{ $row->description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>File</td>
						<td>{{ $row->file }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Category</td>
						<td>{{ $row->category }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subcategory</td>
						<td>{{ $row->subcategory }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subnode</td>
						<td>{{ $row->subnode }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Vendor</td>
						<td>{{ $row->vendor }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Vendor Name</td>
						<td>{{ $row->vendor_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Type</td>
						<td>{{ $row->type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Date</td>
						<td>{{ $row->date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Order Instruction</td>
						<td>{{ $row->order_instruction }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Service Status</td>
						<td>{{ $row->service_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Order Amount</td>
						<td>{{ $row->order_amount }} </td>
						
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
						<td width='30%' class='label-view text-right'>Dboy Id</td>
						<td>{{ $row->dboy_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Dboy Name</td>
						<td>{{ $row->dboy_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Payment Status</td>
						<td>{{ $row->payment_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Service Charge</td>
						<td>{{ $row->service_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Delivery Type</td>
						<td>{{ $row->delivery_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Reference No</td>
						<td>{{ $row->reference_no }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Mop</td>
						<td>{{ $row->mop }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subscription Status</td>
						<td>{{ $row->subscription_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Tracking Id</td>
						<td>{{ $row->tracking_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Region</td>
						<td>{{ $row->region }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
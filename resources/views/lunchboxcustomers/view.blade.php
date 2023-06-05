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
		<li><a href="{{ URL::to('lunchboxcustomers?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('lunchboxcustomers?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('lunchboxcustomers/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
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
						<td width='30%' class='label-view text-right'>User Id</td>
						<td>{{ $row->user_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>First Name</td>
						<td>{{ $row->first_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Email</td>
						<td>{{ $row->email }} </td>
						
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
						<td width='30%' class='label-view text-right'>Phone Change Status</td>
						<td>{{ $row->phone_change_status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Primary Otp</td>
						<td>{{ $row->primary_otp }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Otp</td>
						<td>{{ $row->secondary_otp }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Primary Otp Verify</td>
						<td>{{ $row->primary_otp_verify }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Otp Verify</td>
						<td>{{ $row->secondary_otp_verify }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Subscription Plan</td>
						<td>{{ $row->subscription_plan }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Zone</td>
						<td>{{ $row->zone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Region</td>
						<td>{{ $row->region }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Active</td>
						<td>{{ $row->active }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
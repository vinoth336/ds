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
		<li><a href="{{ URL::to('partneractivation?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('partneractivation?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('partneractivation/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
				<!-- 	<tr>
						<td width='30%' class='label-view text-right'>Id</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Group Id</td>
						<td>{{ $row->group_id }} </td>
						
					</tr> -->
				
					<!-- <tr>
						<td width='30%' class='label-view text-right'>Username</td>
						<td>{{ $row->username }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Password</td>
						<td>{{ $row->password }} </td>
						
					</tr>
				 -->
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_email') !!}</td>
						<td>{{ $row->email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.firstname') !!}</td>
						<td>{{ $row->first_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.lastname') !!}</td>
						<td>{{ $row->last_name }} </td>
						
					</tr>
				
				<!-- 	<tr>
						<td width='30%' class='label-view text-right'>Avatar</td>
						<td>{{ $row->avatar }} </td>
						
					</tr>
				 -->
					<!-- <tr>
						<td width='30%' class='label-view text-right'>Active</td>
						<td>{{ $row->active }} </td>
						
					</tr> -->
				
					<!-- <tr>
						<td width='30%' class='label-view text-right'>Login Attempt</td>
						<td>{{ $row->login_attempt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Last Login</td>
						<td>{{ $row->last_login }} </td>
						
					</tr> -->
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_created_at') !!}</td>
						<td>{{ $row->created_at }} </td>
						
					</tr>
				
				<!-- 	<tr>
						<td width='30%' class='label-view text-right'>Updated At</td>
						<td>{{ $row->updated_at }} </td>
						
					</tr> -->
				<!-- 
					<tr>
						<td width='30%' class='label-view text-right'>Reminder</td>
						<td>{{ $row->reminder }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Activation</td>
						<td>{{ $row->activation }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Remember Token</td>
						<td>{{ $row->remember_token }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Last Activity</td>
						<td>{{ $row->last_activity }} </td>
						
					</tr> -->
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_phone_number') !!}</td>
						<td>{{ $row->phone_number }} </td>
						
					</tr>
				
					<!-- <tr>
						<td width='30%' class='label-view text-right'>Phone Verified</td>
						<td>{{ $row->phone_verified }} </td>
						
					</tr> -->
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.address') !!}</td>
						<td>{{ $row->address }} </td>
						
					</tr>
				
					<!-- <tr>
						<td width='30%' class='label-view text-right'>City</td>
						<td>{{ $row->city }} </td>
						
					</tr> -->
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_state') !!}</td>
						<td>{{ $row->state }} </td>
						
					</tr>
				
				<!-- 	<tr>
						<td width='30%' class='label-view text-right'>Zip Code</td>
						<td>{{ $row->zip_code }} </td>
						
					</tr> -->
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_country') !!}</td>
						<td>{{ $row->country }} </td>
						
					</tr>
				
				<!-- 	<tr>
						<td width='30%' class='label-view text-right'>Entry By</td>
						<td>{{ $row->entry_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Mobile Token</td>
						<td>{{ $row->mobile_token }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Commission</td>
						<td>{{ $row->commission }} </td>
						
					</tr> -->
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
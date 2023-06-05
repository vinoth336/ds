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
		<li><a href="{{ URL::to('discount?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('discount?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('discount/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>Discount Id</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Id</td>
						<td>{{ $row->res_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Name</td>
						<td>{{ $row->coupon_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Code</td>
						<td>{{ $row->coupon_code }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Description</td>
						<td>{{ $row->coupon_desc }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Coupon Type</td>
						<td>{{ $row->coupon_use_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer Type</td>
						<td>{{ $row->offer_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer (%)</td>
						<td>{{ $row->offer }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer From</td>
						<td>{{ $row->offer_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer To</td>
						<td>{{ $row->offer_to }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
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
		<li><a href="{{ URL::to('restaurant?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('restaurant?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('restaurant/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<!--<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_id') !!}</td>
						<td>{{ $row->id }} </td>
						
					</tr>-->
				
					<tr>
						<td width='30%' class='label-view text-right'>Restaurant Name</td>
						<td>{{ $row->name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_location') !!}</td>
						<td>{{ $row->location }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_logo') !!}</td>
						<td>
							<p>
								@if($row->logo != '')
								<a href="<?php echo url('').'/uploads/restaurants/'.$row->logo;?>" target="_blank" class="previewImage">
									<img src="<?php echo url('').'/uploads/restaurants/'.$row->logo;?>" border="0" width="50" class="img-circle">
								</a>
								@else
								<a>
									<img src="<?php echo url('uploads/images/no-image.png');?>" border="0" width="50" class="img-circle">
								</a>
								@endif
							</p>
						</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Partner Name</td>
						<td>{!! SiteHelpers::hostname($row->partner_id) !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_cuisine') !!}</td>
						<td>{{ $row->cuisine }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Res Desc</td>
						<td>{{ $row->res_desc }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Premium Plan</td>
						<td>{{ $row->premium_plan }} </td>
						
					</tr>
                    
                    <tr>
						<td width='30%' class='label-view text-right'>Call Handling</td>
						<td>@if($row->call_handling == 1) Yes @else No @endif </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Pure Veg</td>
						<td>@if($row->pure_veg == 1) Yes @else No @endif</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>GST</td>
						<td>{{ $row->service_tax }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Packaging Charge</td>
						<td>{{ $row->max_packaging_charge }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Preparation Time</td>
						<td>{{ $row->delivery_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_phone') !!}</td>
						<td>{{ $row->phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Phone Number1</td>
						<td>{{ $row->secondary_phone_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Secondary Phone Number2</td>
						<td>{{ $row->secondary_phone_number2 }} </td>
						
					</tr>
                    
                    <tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_budjet') !!}</td>
						<td>@if($row->budget == 1) Low @elseif($row->budget == 2) Medium @elseif($row->budget == 3) High @elseif($row->budget == 4) Very High @endif </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_opening_time') !!}</td>
						<td>{{ $row->opening_time }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_closing_time') !!}</td>
						<td>{{ $row->closing_time }} </td>
						
					</tr>
				
					<!--<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_vat') !!}</td>
						<td>{{ $row->vat }} </td>
						
					</tr>-->
				
					<!--<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.Abs_Del_time') !!}</td>
						<td>{{ $row->delivery_time }} </td>
						
					</tr>-->
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_offer') !!}</td>
						<td>{{ $row->offer }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Minimum Order Value</td>
						<td>{{ $row->min_order_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Maximum Value Apply</td>
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
				
					<!--<tr>
						<td width='30%' class='label-view text-right'>Latitude</td>
						<td>{{ $row->latitude }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Longitude</td>
						<td>{{ $row->longitude }} </td>
						
					</tr>-->
				
					<!--<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_rating') !!}</td>
						<td>{{ $row->rating }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{!! trans('core.abs_entry_by') !!}</td>
						<td>{{ $row->entry_by }} </td>
						
					</tr>-->
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
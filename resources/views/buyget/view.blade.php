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
		<li><a href="{{ URL::to('buyget?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('buyget?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('buyget/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit"></i>&nbsp;{!! Lang::get('core.btn_edit') !!}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>Restaurant Name</td>
						<td>{!! SiteHelpers::gridDisplayView($row->restaurant_id,'restaurant_id','1:abserve_restaurants:id:name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Food Item (To Buy)</td>
						<td>{{ $row->food_item }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Buy Qty</td>
						<td>{{ $row->buy_qty }} </td>
						
					</tr>
				
					
				
					<tr>
						<td width='30%' class='label-view text-right'>Food Item (To Get)</td>
						<td>{!! SiteHelpers::gridDisplayView($row->bogo_item_id,'bogo_item_id','1:abserve_hotel_items:bogo_item_id:food_item') !!} </td>
						
					</tr>
                    
                    <tr>
						<td width='30%' class='label-view text-right'>Get Qty</td>
						<td>{{ $row->get_qty }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer Start Date</td>
						<td>{{ $row->bogo_start_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Offer End Date</td>
						<td>{{ $row->bogo_end_date }} </td>
						
					</tr>
				
					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop
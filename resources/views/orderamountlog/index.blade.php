@extends('layouts.app')
@if(Session::get('orderloginid') !=1)
 <?php header('Location: orderlogin'); ?>
@endif 
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Order Amount Log </h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<!--<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title="{!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>-->
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			<!--		@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('orderamountlog/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;!!{ Lang::get('core.btn_create') !!}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
	<a href="{{ URL::to( 'orderamountlog/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('orderamountlog/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif			-->
		 
		</div> 		
                 <div class="form-group  " >
                    <label for="Id" class="control-label col-md-5 text-right">Region</label>
                        <div class="col-md-3">
                         <select id='regionval' class='form-control regionval' >
                           <option value =''>All region</option>
                            <?php foreach ($region as $key => $value)
                                   { ?>
                                  <option value="<?php echo $value->id;  ?>" <?php if($_GET['region'] == $value->id){echo "selected"; }  ?>><?php echo $value->region_name;  ?></option>  
                                 <?php  }
                             ?>
                        </select>                      
                    </div>  
                    <div class="col-md-2">
                    </div>
                </div>
	
	
	 {!! Form::open(array('url'=>'orderamountlog/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
		<div class="">
            <table class="table table-hover table-bordered table-striped datatable" style="width:100%">
                <thead>
                    <tr>
                        <th width="7%">S.No</th>
                        <th>Order ID</th>
						<th>Total Price</th>
                        <!--<th>Ds Commission</th>-->
                        <th>GST</th>
						<th>Coupon Price</th>
                        <th>Offer Price</th>
                        <th>Packaging Charge</th>
                        <th>Delivery Charge</th>
						<th>Grand Total</th>
                        <th>Date & Time</th>
				 
			  </tr>
        </thead>
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
    
    
     <?php if($_GET['region'] != ''){
	 Session::put('regionval', $_GET['region']);  
	}else{
	 Session::put('regionval', '');  
	} ?>
    
	<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    
	<!--@include('footer')-->
	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("orderamountlog/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	 $('#regionval').change(function(){
	
     var regionval = $(this).val();
	 //alert(regionval);
	window.location.href = "<?php echo url(); ?>/orderamountlog?region=" + regionval;
	
    });
	
	
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("orderamountlog/orderamountlog")}}',
		dom: 'Blfrtip',
    	buttons: {
			buttons: [
				{ extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Order Amount Log', className: 'tips btn btn-sm btn-white' }
			]
		},
		
        columns: [
			{ data: 'rownum', searchable: false },
			{data: 'orderid', name: 'orderid'},
			{ data: 'total_price', name: 'total_price' },
			//{ data: 'ds_commission', name: 'ds_commission' },
			{data: 's_tax', s_tax: 'id'},
			{ data: 'coupon_price', name: 'coupon_price' },
			{ data: 'offer_price', name: 'offer_price' },
			{ data: 'packaging_charge', name: 'packaging_charge' },
			{ data: 'delivery_charge', name: 'delivery_charge' },
			{ data: 'grand_total', name: 'grand_total' },
			{ data: 'date_time', name: 'date_time' },
			//{data: 'action', name: 'action', orderable: false, searchable: false}
        ],
		
		order: [[ 1, "asc" ]],
    });
	
	
});	
</script>		
@stop
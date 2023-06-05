@extends('layouts.app')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<style type="text/css">
.data_table .dt-buttons {
    top: 0px !important;
    position: unset !important;
    left: 0px;
}
</style>

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Pending Orders<small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">Pending Orders</li>
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
    	
    <div class="col-md-12" style="margin:10px; line-height:28px;">
        <label for="Region" class=" control-label text-left col-md-4" style="text-align:end;"> Region : </label> 	
        <div class="col-md-3">
            <select rows='3' class='form-control regionselect' id="regionselect">
                <?php if(session()->get('gid') == '1'){ ?>
                    <option value="" selected>All region</option>  
                <?php } ?>
                            
                <?php foreach($regions as $region) {  ?>
                    <option value="<?php echo $region->region_keyword;  ?>" <?php if($_GET['region'] == $region->region_keyword){ echo "selected"; }  ?>><?php echo $region->region_name;  ?></option>
                <?php }  ?> 
            </select>
        </div>
    </div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
		<!--	@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('cumulativeorders/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;!!{ Lang::get('core.btn_create') !!}</a>
			@endif
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<a href="{{ URL::to( 'cumulativeorders/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('cumulativeorders/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif		-->
		</div> 		

	
	
	 {!! Form::open(array('url'=>'cumulativeorders/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	<div class="data_table" style="overflow-x:auto">
   <table class="table table-hover table-bordered table-striped datatable" style="width:100%">
                <thead>
                    <tr>
                        <th width="4%">S.No</th>
                        <th width="4%"><input type="checkbox" class="checkall"></th>
                        <th width="3%">Orderid</th>
                        <th width="3%">Cust id</th>
                        <th width="3%">Customer Name</th>
                        <th width="4%">Customer Address</th>
                        <th width="4%">Customer Phone</th>
                        <th width="3%">Restaurant Name</th>
                        <th width="4%">Item total</th>
                        <th width="4%">Coupon offer price</th>
                        <th width="4%">Res offer price</th>
                        <th width="3%">HGST / DGST</th>
                        <th width="3%">GST</th>
                        <th width="4%">Delivery charge</th>
                        <th width="9%">Packaging charge</th>
                        <th width="9%">Grand total</th>
                        <th width="9%">Date</th>
                        <th width="15%">Order details</th>
                        <th width="9%">MOP</th>
                        <th width="9%">Delivery Type</th>
                        <th width="4%">Status</th>
                        <!--@foreach ($tableGrid as $t)
                            @if($t['view'] =='1')				
                                <?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
                                @if(SiteHelpers::filterColumn($limited ))
                                
                                    <th>{{ $t['label'] }}</th>			
                                @endif 
                            @endif
                        @endforeach-->
                       <!-- <th width="7%">Action</th>-->
                    </tr>
                </thead>
            </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
    <?php if($_GET['region'] != ''){
	 	Session::put('regionselect', $_GET['region']);  
	}else{
	 	Session::put('regionselect', '');  
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
		$('#AbserveTable').attr('action','{{ URL::to("cumulativeorders/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	$('#regionselect').change(function(){
	
     	var regionselect = $(this).val();
		window.location.href = "<?php echo url(); ?>/cumulativeorders?region=" + regionselect;
	
    });
	
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("cumulativeorders/cumulativeorders")}}',
		dom: 'Blfrtip',
    	buttons: {
			buttons: [
				{ extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Cumulative Orders', className: 'tips btn btn-sm btn-white' }
			]
		},
         columns: [
		    { data: 'rownum', searchable: false },
            {data: 'id', name: 'id'},
			{data: 'id', name: 'id'},
            {data: 'cust_id', name: 'cust_id'},
            {data: 'username', name: 'tb_users.username'},
            {data: 'address', name: 'address'},
            {data: 'phone_number', name: 'tb_users.phone_number'},
            {data: 'name', name: 'abserve_restaurants.name'},
            {data: 'total_price', name: 'total_price'},  
            {data: 'coupon_price', name: 'coupon_price'}, 
			{data: 'offer_price', name: 'offer_price'}, 
            {data: 'hd_gst', name: 'hd_gst'},
            {data: 's_tax', name: 's_tax'}, 
            {data: 'delivery_charge', name: 'delivery_charge'},
			{data: 'packaging_charge', name: 'packaging_charge'},   
            {data: 'grand_total', name: 'grand_total'},
            {data: 'date', name: 'date'},
			{data: 'order_details', name: 'abserve_orders_customer.order_details'},
			{data: 'mop', name: 'mop'},
			{data: 'delivery_type', name: 'delivery_type'},
			{data: 'status', name: 'status'},
			/*{data: 'action', name: 'action', orderable: false, searchable: false}*/
        ],
	
		columnDefs: [{
			targets: 1,
			searchable: false,
			orderable: false,
			className: 'dt-body-center',
			render: function (data, type, full, meta){
				return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(data).html() + '">';
			}
		},
		{
			targets: 11,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                var status = '';
                if(data == 1){
                    gst = 'HGST';
                }else {
					 gst = 'DGST';
				}
                return gst;
            }
        },
		{
			targets: 20,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                var status = '';
                if(data == 7){
                    status = 'Payment Pending';
                }else if(data == 8){
					 status = 'Payment Aborted';
				}else if(data == 9){
					 status = 'Payment Failure';
				}
                return status;
            }
        },
		{
			targets: 18,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                if(data == "null"){
                    mop = "";
                }else{
					mop = data;
				}
                return mop;
            }
        },
		
		],
		order: [[ 15, "desc" ]]
    });
	
});	
</script>		
@stop
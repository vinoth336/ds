@extends('layouts.app')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
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
		<!--	@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('servicereports/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;!!{ Lang::get('core.btn_create') !!}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<a href="{{ URL::to( 'servicereports/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('servicereports/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif			
		 -->
		

	 <label for="Region" class=" control-label col-md-4 text-left" style="text-align:end;"> Region : </label>
		 <div class="col-md-3"> 
            <?php  
            if(session()->get('gid') == '1'){
                 $regionall = \DB::select("SELECT *  FROM `region` ");
            }elseif(session()->get('gid') == '7'){
                $regionall = \DB::select("SELECT *  FROM `region` WHERE `id`='".session()->get('rid')."'");
            }	?>
                                
            <select rows='3' class='form-control regionvalue' id="regionvalue">
              <?php  
              if(session()->get('gid') == '1'){
                 $regionall = \DB::select("SELECT * FROM `region` ");  ?>
                 <option value="" selected>All region</option>  
              <?php }elseif(session()->get('gid') == '7'){
                 $regionall = \DB::select("SELECT * FROM `region` WHERE `id`='".session()->get('rid')."'");
              }	?>
                            
                <?php foreach($regionall as $region1)  {  ?>
                    <option value="<?php echo $region1->id;  ?>" <?php if($_GET['region'] == $region1->id){echo "selected"; }  ?>><?php echo $region1->region_name;  ?></option><?php }  ?> 
            </select>
		</div> 		

		</div> 	
	
	 {!! Form::open(array('url'=>'servicereports/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	<div class="">
            <table class="table table-hover table-bordered table-striped datatable" style="width:100%">
                <thead>
                    <tr>
                        <th width="7%">S.No</th>
							<th>OrderId</th>
                            <th>Cust Name</th>
                            <th>From Location</th>
                            <th>To Location</th>	
                            <th>Cust Phone</th>
                            <th>Cust Email</th>	
                            <th>Comments</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Sub Node Category</th>	
                            <th>Type</th>
                            <th>Service Charge</th>	
                            <th>Boy name</th>
                            <th>Payment Status</th>
                            <th>Service Status</th>
                            <th>Order Amount</th>
                            <th>Order Instruction</th>
					
			  </tr>
        </thead>
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
    
     <?php if($_GET['region'] != ''){
	 Session::put('regionvalue', $_GET['region']);  
	}else{
	 Session::put('regionvalue', '');  
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
		$('#AbserveTable').attr('action','{{ URL::to("servicereports/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	 $('#regionvalue').change(function(){
	
     var regionvalue = $(this).val();
	window.location.href = "<?php echo url(); ?>/servicereports?region=" + regionvalue;
	
    });
	
	
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("servicereports/servicereports")}}',
		dom: 'Blfrtip',
    	buttons: {
            buttons: [
                { extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Download', title: 'Service Reports', className: 'tips btn btn-sm btn-white' }
            ]
        },
        columns: [
		
			{ data: 'rownum', searchable: false },
            { data: 'orderid', name: 'orderid' },
			{ data: 'first_name', name: 'lunch_box_customers.first_name' },
			{ data: 'to_location', name: 'to_location' },
			{ data: 'location', name: 'location' },
			{ data: 'c_phone_number', name: 'c_phone_number' },
            { data: 'email', name: 'email' },
			{ data: 'comments', name: 'comments' },
			{ data: 'description', name: 'description' },
			{ data: 'cat1', name: 'cat.cat_name' },
			{ data: 'cat2', name: 'scat.cat_name'},
			{ data: 'cat3', name: 'sscat.cat_name'},
            { data: 'type', name: 'type' },
			{ data: 'service_charge', name: 'service_charge' },
			{ data: 'dboy_name', name: 'dboy_name' },
			{ data: 'payment_status', name: 'payment_status' },
			{ data: 'service_status', name: 'service_status' },
			{ data: 'order_amount', name: 'order_amount' },
			{ data: 'order_instruction', name: 'order_instruction' },
        ],
	
		columnDefs: [{
            targets: 15,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
				//alert(data);
                var payment_status = '';
                if(data == 1){
                    payment_status = 'Paid';
                } else {
                    payment_status = 'Unpaid';
                }
                return payment_status;
            }
        },
		
		{
            targets: 16,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
				//alert(data);
                var service_status = '';
                if(data == 2){
                    service_status = '<span class="label status label-danger">Rejected Service</span>';
                } else {
                    service_status = '<span class="label status label-info">Finished Service</span>';
                }
                return service_status;
            }
     
			
		},
	
		],
		order: [[ 1, "desc" ]]
    });
	
});	
</script>		
@stop
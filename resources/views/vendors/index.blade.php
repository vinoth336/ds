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
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('vendors/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<!--<a href="{{ URL::to( 'vendors/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('vendors/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif-->			
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'vendors/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	<div class="">
    <table class="table table-hover table-bordered table-striped datatable" style="width:100%">
        <thead>
            <tr>
                <th width="7%">S.No</th>
                <th width="4%"><input type="checkbox" class="checkall" /></th>
        
                <th>Name</th>
                <th>Mobile Number</th>	
                <th>Secondary number</th>
                <th>Email</th>
                <th>Store Name</th>
                <th>Address</th>
                <th>Sub Category Name</th>
                <th>Description</th>
                <th>Star Time</th>
                <th>End Time</th>
                <th>Delivery Type</th>
                <th>Region</th>
                <th>Status</th>
					
				 <th width="7%">Action</th>
			  </tr>
        </thead>
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
    
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
		$('#AbserveTable').attr('action','{{ URL::to("vendors/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("vendors/vendors")}}',
		dom: 'Blfrtip',
    	buttons: {
            buttons: [
                { extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Download', title: 'Vendors', className: 'tips btn btn-sm btn-white' }
            ]
        },
        columns: [
			{ data: 'rownum', searchable: false },
			{ data: 'id', name: 'id' },
            { data: 'owner_name', name: 'owner_name' },
			{ data: 'primary_number', name: 'primary_number' },
			{ data: 'secondary_number', name: 'secondary_number' },
			{ data: 'email', name: 'email' },
			{ data: 'store_name', name: 'store_name' },
			{ data: 'address', name: 'address' },			
			{ data: 'cat_name', name: 'service_categories.cat_name' },
			{ data: 'description', name: 'description' },
			{ data: 'start_time', name: 'start_time' },
			{ data: 'end_time', name: 'end_time' },
			{ data: 'delivery_type', name: 'delivery_type' },
			{ data: 'region_name', name: 'region.region_name' },
			{ data: 'status', name: 'status' },
			{ data: 'action', name: 'action', orderable: false, searchable: false }
        ],
		columnDefs: [{
			targets: 1,
			searchable: false,
			orderable: false,
			className: 'dt-body-center',
			render: function (data, type, full, meta){
				return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(data).html() + '">';
			}
		}],
		order: [[ 1, "asc" ]]
    });
	
});	
</script>		
@stop
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
        <li><a href="{{ URL::to('dashboard') }}"> {!! trans('core.m_dashboard') !!} </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<!--<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> {!! trans('core.abs_clr_search') !!} </a>
		</div>-->
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<!--<a href="{{ URL::to( 'partners/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> {!! trans('core.btn_search') !!}</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('partners/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}"><i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif	-->		
		 
		</div> 		

	 {!! Form::open(array('url'=>'partners/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	<div class="data_table">
            <table class="table table-hover table-bordered table-striped datatable" style="width:100%">
                <thead>
                    <tr>
                        <th width="7%">S.No</th>
                        <th width="4%"><input type="checkbox" class="checkall" /></th>
				
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')				
						<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
						
							<th>{{ $t['label'] }}</th>			
						@endif 
					@endif
				@endforeach
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
		$('#AbserveTable').attr('action','{{ URL::to("partners/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("partners/partners")}}',
		dom: 'Blfrtip',
    	buttons: {
            buttons: [
                { extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Download', title: 'Restaurant Details', className: 'tips btn btn-sm btn-white download' }
            ]
        },
        columns: [
			{ data: 'rownum', searchable: false },
            { data: 'id', name: 'id' },
			{ data: 'id', name: 'id' },
            { data: 'username', name: 'username' },
            { data: 'email', name: 'email' },
			{ data: 'res_name', name: 'res_name' },
			{ data: 'phone_number', name: 'phone_number' },
			{ data: 'active', name: 'active' },
			{ data: 'region_name', name: 'region.region_name' },
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
		}]
    });
	
});	
</script>		
@stop
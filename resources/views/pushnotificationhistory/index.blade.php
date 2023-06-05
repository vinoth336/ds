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
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>-->
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_excel'] ==1)
		<!--	<a href="{{ URL::to('pushnotificationhistory/download') }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif		-->	
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'pushnotificationhistory/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <div class="" >
            <table class="table table-hover table-bordered table-striped datatable" style="width:100%">
                <thead>
                    <tr>
                           <th>S.No</th>
                           <th>Device</th>
                           <th>Message</th>
                           <th>Region</th>
                           <th>Date</th>
                           <th>Time</th>
				
				
				
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
		$('#AbserveTable').attr('action','{{ URL::to("pushnotificationhistory/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("pushnotificationhistory/pushnotificationhistory")}}',
		dom: 'Blfrtip',
    	buttons: {
            buttons: [
                { extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Pushnotification History', title: 'Region', className: 'tips btn btn-sm btn-white' }
            ]
        },
        columns: [
			{ data: 'rownum', searchable: false },
			{ data: 'device', name: 'device' },
            { data: 'message', name: 'message' },
			{ data: 'region_name', name: 'region.id' },
            { data: 'date', name: 'date' },
            { data: 'time', name: 'time' },
			
        ],
		columnDefs: [{
            targets: 3,
            searchable: false,
            orderable: false,
          //  className: 'dt-body-center',
            render: function (data, type, full, meta){
				//alert(data);
                if(data == null){
					//alert("1");
                    region_name = "ALL";
                }else{
					//alert("0");
					region_name = data;
				}
                return region_name;
            }
        }
		],
		
		order: [[ 4, "desc" ]],
		
    });
	
});	
</script>		
@stop
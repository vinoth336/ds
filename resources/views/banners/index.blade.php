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
    <div class="col-md-12" style="margin:10px; line-height:28px;">
        <label for="Region" class=" control-label col-md-4 text-left" style="text-align:end;"> Region : </label>
        <div class="col-md-3">
            <select rows='3' class='form-control regionselect' id="regionselect">
                <?php if(session()->get('gid') == '1'){ ?>
                    <option value="" selected>All region</option>  
                <?php } ?>
                            
                <?php foreach($regions as $region) {  ?>
                    <option value="<?php echo $region->id;  ?>" <?php if($_GET['region'] == $region->id){ echo "selected"; }  ?>><?php echo $region->region_name;  ?></option>
                <?php }  ?> 
            </select>
        </div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('banners/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<!--<a href="{{ URL::to( 'banners/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('banners/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif		-->	
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'banners/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
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
		$('#AbserveTable').attr('action','{{ URL::to("banners/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	$('#regionselect').change(function(){
	
     	var regionselect = $(this).val();
		window.location.href = "<?php echo url(); ?>/banners?region=" + regionselect;
	
    });
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("banners/banners")}}',
		dom: 'Blfrtip',
    	buttons: {
            buttons: [
                { extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Download', title: 'Banners', className: 'tips btn btn-sm btn-white' }
            ]
        },
        columns: [
			{ data: 'rownum', searchable: false },
			{ data: 'id', name: 'id' },
			{ data: 'id', name: 'id' },
			{ data: 'name', name: 'abserve_restaurants.name' },
            { data: 'banner_image', name: 'banner_image' },
            { data: 'status', name: 'status' },
			{ data: 'region_name', name: 'region.region_name' },
			{ data: 'from_date', name: 'from_date' },
			{ data: 'to_date', name: 'to_date' },
			{ data: 'available_days', name: 'available_days' },
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
		}, {
            targets: 5,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                var status = '';
                if(data == 1){
                    status = 'Clickable';
                } else {
                    status = 'Non Clickable';
                }
                return status;
            }
        }],
		order: [[ 2, "asc" ]]
    });
	
});	
</script>		
@stop
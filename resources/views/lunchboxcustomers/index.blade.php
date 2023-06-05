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
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title="{!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<!--<a href="{{ URL::to('lunchboxcustomers/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>-->
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif 
			<!--<a href="{{ URL::to( 'lunchboxcustomers/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('lunchboxcustomers/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif-->
            <div>	
          <label for="Region" class=" control-label col-md-4 text-left" style="text-align:end;"> Region : </label>
		 <div class="col-md-3"> 
            <?php  
            if(session()->get('gid') == '1'){
                 $regionall = \DB::select("SELECT *  FROM `region` ");
            }elseif(session()->get('gid') == '7'){
                $regionall = \DB::select("SELECT *  FROM `region` WHERE `id`='".session()->get('rid')."'");
            }	?>
                                
            <select rows='3' class='form-control regionselect' id="regionselect">
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
	
	 {!! Form::open(array('url'=>'lunchboxcustomers/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
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
		$('#AbserveTable').attr('action','{{ URL::to("lunchboxcustomers/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	
	 $('#regionselect').change(function(){
	
     var regionselect = $(this).val();
	window.location.href = "<?php echo url(); ?>/lunchboxcustomers?region=" + regionselect;
	
    });
	
	
	$('.datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
        ajax: '{{ URL::to("lunchboxcustomers/lunchboxcustomers")}}',
		//type: "POST",
		//data: {
					//regionselect : '1'
		//},
		dom: 'Blfrtip',
    	buttons: {
			buttons: [
				{ extend: 'excel', text: '<i class="fa fa-download"></i> Download', titleAttr: 'Download', className: 'tips btn btn-sm btn-white' }
			]
		},
		createdRow: function( row, data, dataIndex ) {
            if ( data['phone_change_status'] == 1 ) {        
         		$(row).addClass('lbred');
       		}
			//console.log(data['phone_change_status']);
    	},
        columns: [
			{ data: 'rownum', searchable: false },
            {data: 'id', name: 'id'},
			{data: 'id', name: 'id'},
			{data: 'first_name', name: 'first_name'},
            {data: 'email', name: 'email'},
            {data: 'primary_number', name: 'primary_number'},
            {data: 'secondary_number', name: 'secondary_number'},
          	{data: 'phone_change_status', name: 'phone_change_status'},
			/*{data: 'pickup_address', name: 'pickup_address'},
			{data: 'zone_name', name: 'zone_name'},*/
			{ data: 'region_name', name: 'region.region_name' },
            {data: 'active', name: 'active'},
			{data: 'action', name: 'action', orderable: false, searchable: false}
        ],
		columnDefs: [{
			targets: 1,
			searchable: false,
			orderable: false,
			className: 'dt-body-center',
			render: function (data, type, full, meta){
				return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(data).html() + '">';
			},
		},
		{
			targets: 7,
			visible: false,
		},
	 	],
		order: [[ 2, "asc" ]],
    });
	
});	
</script>		
@stop
@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
<!--<link href="https://cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" rel="stylesheet">-->
<link href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css" rel="stylesheet">
    
    
 	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.min.js"></script> 
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.bootstrap.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.0/js/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.0/js/buttons.colVis.min.js">
	</script>  
  	<script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js"></script>

	<!-- Include Required Prerequisites -->
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <!-- Include Date Range Picker -->
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="{{ asset('abserve/js/datatable/date_range.js') }}"></script>
    
    

	
    
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Cash On Delivery Order</h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <!--<i class="fa fa-table"></i>--> </h5>
<div class="sbox-tools" >
		<!--<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>-->
		@if((Session::get('gid') ==1) || (Session::get('gid') ==6))
			<a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			<!--@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('cashondeliveryorder/update') }}" class="tips btn btn-sm btn-white"  title="{!! Lang::get('core.btn_create') !!}">
			<i class="fa fa-plus-circle "></i>&nbsp;{!! Lang::get('core.btn_create') !!}</a>
			@endif-->  
			<!--@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="AbserveDelete();" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_remove') !!}">
			<i class="fa fa-minus-circle "></i>&nbsp;{!! Lang::get('core.btn_remove') !!}</a>
			@endif -->
			<!--<a href="{{ URL::to( 'cashondeliveryorder/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>				
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('cashondeliveryorder/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif	-->		
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'cashondeliveryorder/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; border:0;">
    <!--<table id="example1" class="table table-striped " style="border: 1px solid #e7eaec; padding: 0;">-->
    <table id="mytable" class="table display table-striped responsive-utilities jambo_table" style="border: 1px solid #e7eaec; padding: 0;">
        <thead>
			<tr>
				<!--<th class="number"> No </th>-->
				<th> <input type="checkbox" class="checkall" /></th>
				<th> Order Id </th>
                <th> Cust Id </th>
                <th> Res Id </th>
				@foreach ($tableGrid as $t)
                	<!--@if($t['label'] == 'Delivery Charge')
                        <th>Total Price</th>
                        <th>Delivery Charge</th>
                    @endif-->
					@if($t['view'] =='1')				
						<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))						  
							<th>{{ $t['label'] }}</th>                         
						@endif 
					@endif
				@endforeach
				<th> Order Details </th>
                <th> Boy Id </th>
                <th> Boy Name </th>
				<!-- <th width="70" >{!! Lang::get('core.btn_action') !!}</th> -->
			  </tr>
              <tr class="filters">
              	<!--<th></th>-->
                <th></th>
              	<th></th>
                <th></th>
                <th></th>
                <th class="input-filter">Cust Name</th>
                <th class="input-filter">Res Name</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              	<th class="date-filter">
                    Date
                </th>              	
                <th></th>
                <th class="input-filter">Payment Status</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="input-filter">Boy Name</th>
              </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
                <tr>
					<!--<td width="30"> {{ ++$i }} </td>-->
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>	
                    <td width="30"><?php $region = \SiteHelpers::getRegionKeyword($row->res_id);?> #{{ $region.$row->id }} </td>
                    <td width="30"> {{ $row->cust_id }} </td>
                    <td width="30"> {{ $row->res_id }} </td>								
				 @foreach ($tableGrid as $field)					
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
						 <td>					 
						 	@if($field['attribute']['image']['active'] =='1')
								{!! SiteHelpers::showUploadedFile($row->{$field['field']},$field['attribute']['image']['path']) !!}
							@elseif($field['field'] =='status')								
								@if($row->status == 4)
									 <span class="label status label-info">Order finished</span>
								@elseif($row->status == 6)
									<span class="label status label-danger">Rejected by Admin</span>
								@elseif($row->status == 10)
									<span class="label label-warning status">Order Canceled</span>
								@elseif($row->status == 11)
									<span class="label label-warning status">Order Returned</span>
								@endif							
                            @elseif($field['field'] =='time')
                            	{{date('h:i:s A',($row->time))}}
                            @elseif($field['field'] =='delivery')
								{!! ($row->delivery =='paid' ? '<lable class="label label-success">Paid</label>' : '<lable class="label label-danger">Unpaid</label>')  !!}
							@else	
								{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
								{!! SiteHelpers::gridDisplay($row->{$field['field']},$field['field'],$conn) !!}	
							@endif						 
						 </td>
						@endif	
					 @endif					 
				 @endforeach
				  <td><?php $orderdetails = \SiteHelpers::getOrderValues($row->id);?>{!! $orderdetails !!}</td>
                  <td><?php $bid = \SiteHelpers::getBoyid($row->id);?>{!! $bid !!}</td>
                  <td><?php $boyname = \SiteHelpers::getBoyname($bid);?>{!! $boyname !!}</td>
				 <!-- <td>
					 	@if($access['is_detail'] ==1)
						<a href="{{ URL::to('cashondeliveryorder/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{!! Lang::get('core.btn_view') !!}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ URL::to('cashondeliveryorder/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{!! Lang::get('core.btn_edit') !!}"><i class="fa fa-edit "></i></a>
						@endif
												
					
				</td> -->				 
                </tr>
				
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	<!--@include('footer')-->
	</div>
</div>	
	</div>	  
</div>
<script>
$(document).ready(function(){
	
	/*$('#mytable').DataTable( {
		lengthMenu: [[50,100,150,200, -1], [50, 100,150,200, "All"]],
        dom: 'Blfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ]
    });*/
	/*var table = $('#mytable').DataTable();
 
    $("#mytable thead th").each( function ( i ) {
        var select = $('<select><option value=""></option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();
            } );
 
        table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    } );*/
	
	

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("cashondeliveryorder/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
});	

</script>		
@stop
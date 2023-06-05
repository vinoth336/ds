@extends('layouts.app')
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
			
			<!--<a href="{{ URL::to( 'discount/search') }}" class="btn btn-sm btn-white" onclick="AbserveModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>			
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('discount/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{!! Lang::get('core.btn_download') !!}">
			<i class="fa fa-download"></i>&nbsp;{!! Lang::get('core.btn_download') !!} </a>
			@endif-->				
		</div>
         <label for="Region" class=" control-label col-md-4 text-left" style="text-align:end;"> Region : </label>
		<div class="col-md-3"> 
          <?php $current_time = date("H:i:s");
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
         		
	 {!! Form::open(array('url'=>'serviceorderdetails/delete/', 'class'=>'form-horizontal' ,'id' =>'AbserveTable' )) !!}
	<div class="">
            <table class="table table-hover table-bordered table-striped datatable" id="example1" style="width:500px">
                <thead>
				<tr>
					
					<th> <input type="checkbox" class="checkall" /></th>
					<!-- <th> Date </th> -->	
					<th> Order Id </th>
                    <th style="width:50px; white-space:pre-line;"> Cust Id </th>
                	<!--<th> Res Id </th>-->
					<th> Cust Name </th>
                    <th> Cust Phone </th>
                    <th width="100px">From Location </th>
                     <th width="100px">To Location </th>
                    <th> Cust Comments </th>
                    <th> Attachment </th>
                    <th> Date & Time </th>
					<th> Subcategory </th>
                    <th> Sub Node category </th>
                    <th> Select Vendor </th>
                    <th> Vendor Name </th>
					<th width="100">{!! Lang::get('core.btn_action') !!}</th>
                    <th width="70" >{!! trans('core.status') !!}</th>
                    <th> Delivery Boy </th>
                    <th> Category </th>
                    <th> Order Instruction  </th>
					<th> Service Charge</th>
                	<th> Delivey Type </th>
					<th> Service Type </th>
					<th> Cust email </th>
                    <th> Service Description </th>
                    <th> Subscription Status </th>
					</tr>
			</thead>
			<tbody>
			<?php 	//echo "<pre>";print_r($results);exit(); ?>
				@foreach ($results as $key => $row)
       <?php $catname = \DB::table('service_categories')->select('cat_name')->where('id','=',$row->category)->first();  ?>
       <?php $subcatname = \DB::table('service_categories')->select('cat_name')->where('id','=',$row->subcategory)->first(); ?>         <?php $subnode = \DB::table('service_categories')->select('cat_name')->where('id','=',$row->subnode)->first();?>  
						<tr>
							<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" /></td>
							<!-- <td width="50">{{date('jS F Y',$row->time)}}</td> -->
							<td width="50">#{{ $row->orderid}}</td>
							<td width="50">{{ $row->cust_id}}</td>                            
							<td width="50">{{ $row->first_name}}</td>
                            <td width="50">{{ $row->c_phone_number}}</td>
                            <td width="50" style="width:100px; white-space:pre-line;">{{ $row->location}}</td>
                            <td width="50" style="width:100px; white-space:pre-line;">{{ $row->to_location}}</td>
                            <td width="50">{{ $row->comments }}</td>
                            <td width="50"><a href="{{URL::to($row->file)}}" download>@if($row->file != ''){{'Download' }}@endif</a></td>
                            <td width="50">{{ $row->date }}</td>
                            <td width="50">{{ $subcatname->cat_name }}</td>
                            <td width="50">{{ $subnode->cat_name }}</td>
                            
                            <td width="50">
                            	<?php
								if($row->subcategory != '' && $row->subnode != ''){
									$subcat_id = $row->subnode;
								} else {
									$subcat_id = $row->subcategory;
								}
								$type = strtolower($row->type);
								if($type == "delivery") {
								  if($_GET['region'] !=''){										
									$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Delivery')->where('region','=',$_GET['region'])->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
									
								  } else {
									$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Delivery')->where('region','=',$row->region)->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();								  								  }
                                }elseif($type == "relocation") {
								  if($_GET['region'] !=''){
									$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Relocation')->where('region','=',$_GET['region'])->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
								  } else {
									$vendors = \DB::table('vendor')->select('id','store_name')->where('delivery_type','=','Relocation')->where('region','=',$row->region)->where('status','=',1)->where('start_time','<=',$current_time)->where('end_time','>=',$current_time)->whereRaw("find_in_set(".$subcat_id.",subcat_id)")->get();
								  }
                                } else {
									$vendors = array();	
								}
								
								if(count($vendors)>0){?>
								  
                                    <select name='vendors' rows='5' id='vendors' class='select1 ' >
                                    <option value="" ></option>
                                    <?php foreach($vendors as $vendor)  {  ?>
                                    	<option value="<?php echo $vendor->id;  ?>"><?php echo $vendor->store_name;  ?></option>                           			<?php }  ?> 
                                    </select>                                
                                    
                                <?php }  ?>
                            </td>
                            <td width="50">{{ $row->vendor_name}}</td>
                         
						 	<td width="50">
							
								@if($row->service_status == 0)
								<!-- <i class="fa fa-volume-up"></i> -->
								<i data-toggle="tooltip" title="Accept your order" class="icon-checkmark-circle2 fn_accept" aria-hidden="true" style="cursor: pointer;"></i>
								<i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i> 
							<input type="hidden" value="{{$row->id}}" class="id" />
                          
							@elseif($row->service_status == 1)
                                	<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>
                                    <i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i> 
									<input type="hidden" value="{{$row->id}}" class="id" />
                                
                             @elseif($row->service_status == 3)
                               	<i data-toggle="tooltip" title="Action disabled" class="icon-checkmark-circle2 " aria-hidden="true" style="opacity: 0.4;cursor: pointer;"></i>
								<i data-toggle="tooltip" title="Reject your order" class="icon-cancel-circle2 fn_reject" aria-hidden="true" style="cursor: pointer;"></i> 
							<input type="hidden" value="{{$row->id}}" class="id" />
                                 @endif
                                  <?php
								  $type = strtolower($row->type);
								  if($type == 'service') {
                                  if($row->subcategory != '' && $row->subnode != ''){
									if($_GET['region'] !=''){
									   	$delivery_boys_form = \DB::table('service_deliveryboy')->select('id','username')->where('region','=',$_GET['region'])->where('status','=',1)->whereRaw("find_in_set(".$row->subnode.",subcat_id)")->get();
									} else {
										$delivery_boys_form = \DB::table('service_deliveryboy')->select('id','username')->where('region','=',$row->region)->where('status','=',1)->whereRaw("find_in_set(".$row->subnode.",subcat_id)")->get();
									}
                                  }else{
									if($_GET['region'] !=''){
										$delivery_boys_form = \DB::table('service_deliveryboy')->select('id','username')->where('region','=',$_GET['region'])->where('status','=',1)->whereRaw("find_in_set(".$row->subcategory.",subcat_id)")->get();
									} else {
										$delivery_boys_form = \DB::table('service_deliveryboy')->select('id','username')->where('region','=',$row->region)->where('status','=',1)->whereRaw("find_in_set(".$row->subcategory.",subcat_id)")->get();
									}
                                  }   ?>
								  
                                  <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' >
								  <option value="" ></option>
								  <?php foreach($delivery_boys_form as $delivery_boys_forms)  {  ?>
                                  <option value="<?php echo $delivery_boys_forms->id;  ?>"><?php echo $delivery_boys_forms->username;  ?></option>                           <?php }  ?> 
                                  </select>
								
								  <?php }else{ 
								  if($row->vendor ==''){
									$disabled ='disabled=""';
								  } else {
									 $disabled ='';
								  }?>
                                  <select name='delivery_boy' rows='5' id='delivery_boy' class='select1 ' <?php echo $disabled; ?> >
                                  <?php echo $deliveryboys; ?>
                                  </select>  
                                  <?php }  ?>
                                  
                                  @if($row->dboy_id != "") 
                            		<a href="" id="orderamt" data-toggle="modal"  data-dismiss="modal" data-target="#order-update" ><span class="txt-primary">Order Finished</span></a>
                                  @endif
                            
							</td>
                            
                            <td  width="50">
                                @if($row->service_status == 1 )
                                    <span class="label status label-success">Accepted Service</span>
                                @elseif($row->service_status == 2)
                                     <span class="label status label-primary">Rejected Service</span>
                                @elseif($row->service_status == 3)
                                     <span class="label status label-info">Assigned</span>
                                @else
                                    <span class="label label-warning status">{!! trans('core.pending') !!}</span>
                                @endif										
                            </td>
							<td width="50">{{ $row->dboy_name}}</td>
                            <td width="50">{{ $catname->cat_name }}</td>
                         	<td width="50">
                          		{{$row->order_instruction}}<br>
                            	<a href="" id="orderinst" data-toggle="modal"  data-dismiss="modal" data-target="#order-inst" ><span class="txt-primary">Order Innstruction</span></a>
                            </td>
                            
							<td width="50">{{ $row->service_charge}}</td>
                            <td width="50">{{ $row->delivery_type }}</td>
                            <td width="50">{{ $row->type }}</td>
                            <td width="50">{{ $row->email}}</td>
                            <td width="50">{{ $row->description }}</td>
                            <td width="50">@if($row->subscription_status == 1){{ 'Paid' }} @else {{ 'Unpaid' }} @endif</td>
                            <input type="hidden" value="{{$row->type}}" class="type" />
                            <input type="hidden" value="{{$row->c_phone_number}}" class="phone" />
                            <input type="hidden" value="{{$row->to_location}}" class="to_location" />
                            <input type="hidden" value="{{$row->subcategory}}" class="subcategory" />
                            <input type="hidden" value="{{$row->subnode}}" class="subnode" />
                            <input type="hidden" value="{{$row->location}}" class="location" />
                            <input type="hidden" value="{{$row->pin_code}}" class="pin_code" />
                            <input type="hidden" value="{{$row->to_pin_code}}" class="to_pin_code" />
                            <input type="hidden" value="{{$catname->cat_name}}" class="cat_name" />
                            <input type="hidden" value="{{$subcatname->cat_name}}" class="subcat_name" />                            
                            <input type="hidden" value="{{$subnode->cat_name}}" class="subnodecat_name" />
                            <input type="hidden" value="{{$row->id}}" class="id" />
						
						</tr>
				
				@endforeach
			</tbody> 
		</table>  <input type="hidden" name="md" value="" />
	{!! Form::close() !!}
    
     
     <div class="client-login">
				<div  class="panel-body">
										<!-- sample modal content -->
			<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modal-title" id="myModalLabel"></h5>
                            </div>
                  <div class="modal-body">
                                <div class="pa-20">
                    <div class="form-wrap">
                       
              {!! Form::open(array('url'=>'serviceorderdetails/sendmail/')) !!}
                            <div class="form-group">
                                <label class="control-label col-md-3" for="">OrderId :</label>
                                <span id="orderid" class="col-md-9"></span>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3" for="">Phone number :</label>
                                 <span id="phone" class="col-md-9"></span>
                            </div>
                            <div class="form-group from_location">
                                <label class="control-label col-md-3" for="">From Location :</label>
                                  <span id="location" class="col-md-9"></span>
                            </div>
                            <div class="form-group from_pincode">
                                <label class="control-label col-md-3" for="">From Pin code :</label>
                                 <span id="pin_code" class="col-md-9"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3" for="">To Location :</label>
                                  <span id="to_location" class="col-md-9"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3" for="">To Pin code :</label>
                                 <span id="to_pin_code" class="col-md-9"></span>
                            </div>
                                 <span id="boy_id"></span>
                            <div class="form-group">
                                <label class="control-label col-md-3" for="exampleInputEmail_2">Instruction :</label>
                                <span id="to_pin_code" class="col-md-9">
                                <textarea rows="4" cols="50" name="instruction" id="instruction" placeholder="Enter instruction"></textarea>
                                </span>
                            </div>
                            <div class="form-group">
                                 <span id="cat_name" class="col-md-9"></span>
                                 <span id="subcat_name" class="col-md-9"></span>
                                 <span id="subnodecat_name" class="col-md-9"></span>
                            </div>
                           <span id="scat"></span>
                           <span id="snode"></span>
                           <span id="type"></span>
                            <div class="form-group text-center">
                                <button type="submit" name="sendmail" id="sendmail" class="btn mt-10 mb-10 mr-10 btn-success btn-rounded">Send SMS</button>
                            </div>
                      {!! Form::close() !!}
                    </div>
                </div>	
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
			   </div>
     
    <!----------------- for order update amount popup start-------------------->
     <div class="order-update">
				<div  class="panel-body">
										<!-- sample modal content -->
			<div id="order-update" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modal-title" id="myModalLabel"></h5>
                            </div>
                  <div class="modal-body">
                                <div class="pa-20">
                    <div class="form-wrap">
                       
              {!! Form::open(array('url'=>'serviceorderdetails/orderupdate/')) !!}
                          
                            <div class="form-group">
                                <label class="control-label mb-10" for="exampleInputEmail_2">Order Amount : *</label>
                                <input type="text" name="order_amount" id="order_amount" placeholder="Enter order amount" required="required">
                            </div>
                           <span id="orderid1"></span>
                           <span id="phone1"></span>
                           <span id="type1"></span>
                            <div class="form-group text-center">
                                <button type="submit" name="orderupdate" id="orderupdate" class="btn mt-10 mb-10 mr-10 btn-success btn-rounded">Order Finished</button>
                            </div>
                      {!! Form::close() !!}
                    </div>
                </div>	
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
			   </div>
         <!----------------- for order update amount popup end-------------------->  
        
        
         <!----------------- for order instruction popup start-------------------->        
               
               <div class="order-inst">
				<div  class="panel-body">
										<!-- sample modal content -->
			<div id="order-inst" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                  <div class="modal-body">
                                <div class="pa-20">
                    <div class="form-wrap">
                       
              {!! Form::open(array('url'=>'serviceorderdetails/orderinstruction/')) !!}
                  
                        
                        <div class="form-group">
                                <label class="control-label mb-10" for="exampleInputEmail_2">Order Instruction : *</label>
                                <textarea name="order_inst" rows="4" cols="50" id="order_inst" placeholder="Enter order Instruction" required="required"></textarea>
                            </div>
                        
                        
                           <span id="orderid2"></span>
                         
                            <div class="form-group text-center">
                                <button type="submit" name="orderinstruction" id="orderinstruction" class="btn mt-10 mb-10 mr-10 btn-success btn-rounded">SAVE</button>
                            </div>
                      {!! Form::close() !!}
                    </div>
                </div>	
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
			   </div>
               
               
    <link href="{{ asset('abserve/js/datatable/buttons.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{ asset('abserve/js/datatable/jquery.dataTables.min.css')}}" rel="stylesheet">
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="{{ asset('abserve/js/datatable/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
 
	</div>
	 </div>
      </div>	
	   </div>	  
</div>	
<script>

function loadlink(){ 
	
	var regionselect = '<?php echo $_GET['region'] ?>';
	
	$.ajax({
		url: '<?php echo url(); ?>/serviceorderdetails/ajaxload',
		type: "get",
		dataType: "json",
		data: {
				regionselect : regionselect
				},
		success: function(data) {
			//console.log(data);
			//alert(data);				
			if(data!=''){
				$("#example1 tbody").html(data.responseText);
			}
		},
		error: function (data) {
			if(data!=''){
				$("#example1 tbody").html(data.responseText);
			}
		}
	});
}		
		
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("serviceorderdetails/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	
	var myTimer =	setInterval(function(){
	  loadlink();
	}, 10000);
	
	$('.datatable').DataTable({
		//processing: true,
        dom: 'Blfrtip',
		//scrollX: true,
        lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
          buttons: [
				'csv', 'excel', 'print'
			],
		
    	
	});
		
	$('#regionselect').change(function(){
	
		var regionselectt = $(this).val();
		// alert(regionselect);
	
		window.location.href = "<?php echo url(); ?>/serviceorderdetails?region=" + regionselectt;
	
		$.ajax({
			url: '<?php echo url(); ?>/serviceorderdetails/ajaxload',
			type: "get",
			dataType: "json",
			data: {
				regionselect : regionselect
				},
			success: function(data) {
				//console.log(data);
				//alert(data);				
				if(data!=''){
					$("#example1 tbody").html(data.responseText);
				}
			},
			error: function (data) {
				if(data!=''){
					$("#example1 tbody").html(data.responseText);
				}
			}
		});
	
	});
	
});

$(document).on("click",'.fn_accept',function(){ 
	var id = $(this).closest("tr").find('.id').val();
	//alert(id);
	var $this = $(this);
   
	$.ajax({
		url: '<?php echo url(); ?>/serviceorderdetails/serviceaccept',
		type: "get",
		dataType: "json",
		data: {
			id:id
		},
		success: function(data) {
			//alert(data);
		 	if(data != ''){
				if(data ==1)
				{
					$this.closest("tr").find(".label").removeClass().addClass('label label-success').text("Accepted Service");
				}
				window.location.reload();
			}
		}
	});
});	

$(document).on("click",'.fn_reject',function(){ 
	var id = $(this).closest("tr").find('.id').val();
	var $this = $(this);
   
	$.ajax({
		url: '<?php echo url(); ?>/serviceorderdetails/servicereject',
		type: "get",
		dataType: "json",
		data: {
			id:id
		},
		success: function(data) {
			//alert(data);
			 if(data != ''){
				if(data ==2)
				{
					$this.closest("tr").find(".label").removeClass().addClass('label label-primary').text("Rejected Service");

				}
				window.location.reload();
			}
		}
	});
});


$(document).on("click",'#orderinst',function(){
  //  $('#myModal').modal('show');
	
	var id1 = $(this).closest("tr").find('.id').val();
	var $this = $(this);
	
	  $('#orderid2').html('<input type="hidden" name="id" value="'+id1+'" readonly/>');
});	


$(document).on("click",'#orderamt',function(){
  //  $('#myModal').modal('show');
	
	var id = $(this).closest("tr").find('.id').val();
	var phone = $(this).closest("tr").find('.phone').val();
	var type = $(this).closest("tr").find('.type').val();
	var $this = $(this);
	
	  $('#orderid1').html('<input type="hidden" name="id" value="'+id+'" readonly/>');
      $('#phone1').html('<input type="hidden" name="phone" value="'+phone+'" readonly />');
	  $('#type1').html('<input type="hidden" name="type" value="'+type+'" />');
	
});	


$(document).on("change",'#delivery_boy',function(){
    $('#myModal').modal('show');
	
	var id = $(this).closest("tr").find('.id').val();
	var phone = $(this).closest("tr").find('.phone').val();
	var to_location = $(this).closest("tr").find('.to_location').val();
	var location = $(this).closest("tr").find('.location').val();
	var scat = $(this).closest("tr").find('.subcategory').val();
	var snode = $(this).closest("tr").find('.subnode').val();
	var to_pin_code = $(this).closest("tr").find('.to_pin_code').val();
	var pin_code = $(this).closest("tr").find('.pin_code').val();
	var cat_name = $(this).closest("tr").find('.cat_name').val();
	var subcat_name = $(this).closest("tr").find('.subcat_name').val();
	var subnodecat_name = $(this).closest("tr").find('.subnodecat_name').val();
	
	//alert(to_pin_code);
	var boy_id = $(this).val();
	//alert(boy_id);
	var type = $(this).closest("tr").find('.type').val();
	if(type == "Relocation"){
	$('.from_location').show();
	$('.from_pincode').show();
	}else{
	$('.from_location').hide();
	$('.from_pincode').hide();
	}
	 var $this = $(this);
	
	  $('#orderid').html('<input type="text" name="id" value="'+id+'" readonly/>');
      $('#phone').html('<input type="text" name="phone" value="'+phone+'" readonly />');
	  $('#to_location').html('<textarea rows="4" cols="50" readonly name="to_location">'+to_location+'</textarea>');
	  $('#scat').html('<input type="hidden" name="subcategory" value="'+scat+'" />');
	  $('#type').html('<input type="hidden" name="type" value="'+type+'" />');
      $('#snode').html('<input type="hidden" name="subnode" value="'+snode+'" />');	
	  $('#location').html('<textarea rows="4" cols="50" readonly name="location">'+location+'</textarea>');
	  $('#to_pin_code').html('<input type="text" name="to_pin_code" value="'+to_pin_code+'" readonly/>');
      $('#pin_code').html('<input type="text" name="pin_code" value="'+pin_code+'" readonly />');
      $('#cat_name').html('<input type="hidden" name="cat_name" value="'+cat_name+'" />');
      $('#subcat_name').html('<input type="hidden" name="subcat_name" value="'+subcat_name+'" />');
      $('#subnodecat_name').html('<input type="hidden" name="subnodecat_name" value="'+subnodecat_name+'" />');
	  $('#boy_id').html('<input type="hidden" name="boy_id" value="'+boy_id+'" />');
	
	/*if(boy_id !=''){
		$.ajax({
			url: '<?php echo url(); ?>/serviceorderdetails/manualserviceassigntoboy',
			type: "post",
			dataType: "json",
			data: {
				id:id, boy_id:boy_id, type:type
			},

			success: function(data) {
				if(data != ''){
					if(data==3)
					{
						$this.closest("tr").find(".label").removeClass().addClass('label label-success').text("Assigned");
					}
				}				
			
			}
		});
	} */
});

$(document).on("change",'#vendors',function(){	
//$('#vendors').change(function(){

	var vendorid = $(this).val();
	var orderid = $(this).closest("tr").find('.id').val();
	var cat_name = $(this).closest("tr").find('.cat_name').val();
	var subcat_name = $(this).closest("tr").find('.subcat_name').val();
	var subnodecat_name = $(this).closest("tr").find('.subnodecat_name').val();
	//alert(vendorid);

	//window.location.href = "<?php echo url(); ?>/serviceorderdetails?vendor=" + vendor;

	$.ajax({
		url: '<?php echo url(); ?>/serviceorderdetails/vendorassign',
		type: "post",
		dataType: "json",
		data: {
			orderid : orderid, vendorid : vendorid, cat_name : cat_name, subcat_name : subcat_name, subnodecat_name : subnodecat_name
		},
		success: function(data) {
			//console.log(data);
			//alert(data);				
			if(data!=''){
				//$("#example1 tbody").html(data.responseText);
			}
		},
		error: function (data) {
			if(data!=''){
				$("#example1 tbody").html(data.responseText);
			}
		}
	});

});

</script>


@stop
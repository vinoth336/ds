@extends('layouts.app')

@section('content')
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('partnertransac?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {!! Lang::get('core.detail') !!} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('partnertransac?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{!! Lang::get('core.btn_back') !!}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{!! Lang::get('core.btn_back') !!}</a>
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

	 <?php if($row->balance !=0){
	    ?>

	    <form name="paypal" id="paypal_trans" action="<?php echo url(); ?>/payment/paypaltransaction" method="post">
	    <input type="hidden" value="{{$id}}" name="ptransid">
	       <div class="form-group  " >
			<label for="Host Balance " class=" control-label col-md-4 text-left"> {!! trans('core.abs_partner_bal') !!} </label>
			<div class="col-md-6">
			<label> $<?php echo $row->balance;?></label>
			 </div> 
			 <div class="col-md-2">
			 	
			 </div>
			</div> 
             
			<div class="form-group  " >
			<label for="" class=" control-label col-md-4 text-left"> {!! trans('core.abs_amount_to_transfer') !!} </label>
			<div class="col-md-6">
			 <input type="text" name="req_amount" class="req_amount allownumericwithoutdecimal" id="inp_amnt"><br>
			 </div> 
			 <div class="col-md-2">
			 	
			 </div>
			</div> 

			<!-- <div class="form-group  " >
			<label for="your pay account " class=" control-label col-md-4 text-left"> your pay account </label>
			<div class="col-md-6">
			<input type="text" name="account_email" class="account_email"><br>  
			 </div> 
			 <div class="col-md-2">
			 	
			 </div>
			</div>  -->

			<div style="clear:both"></div>	
		
			<input type="hidden" name="hidden_id" value="<?php echo $row->partner_id;?>">
		

			   <div class="form-group"><br>
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					
					<button type="submit" id="trans_submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! trans('core.abs_transfer') !!}</button>

					<button type="button" onclick="location.href='{{ URL::to('hostbalance?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
					</div>	  
			
				  </div> 

		  

	   </form>  

	    <?php } else{
               
               echo "There is no Amount";

	    	  } ?>


<!-- 		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>Id</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Partner Id</td>
						<td>{{ $row->partner_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Balance</td>
						<td>{{ $row->balance }} </td>
						
					</tr>
				
			</tbody>	
		</table>   
 -->
	 
	
	</div>
</div>	

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#trans_submit").attr("disabled","disabled");
	})
	$(document).on('keyup',"#inp_amnt",function(){
		var amount_val = $(this).val();
		if(amount_val != '' && $.isNumeric(amount_val)){
			$("#trans_submit").prop("disabled", false);
		} else {
			$("#trans_submit").attr("disabled","disabled");
		}
	})
</script>
	  
@stop
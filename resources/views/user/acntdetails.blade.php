@extends('layouts.app')
@section('content')
<div class="page-content row">
	<div class="page-header">
		<div class="page-title">
			<h3>{!! trans('core.abs_acnt_view_info') !!}</h3>
		</div>
		<ul class="breadcrumb">
			<li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
			<li class="active">Account</li>
		</ul>
	</div>  
	<div class="page-content-wrapper m-t">
		@if(Session::has('message'))	  
		{!! Session::get('message') !!}
		@endif	
		<ul>
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>	
		<ul class="nav nav-tabs" >
			<li class="@if($section == '')active @endif"><a href="{{URL::to('user/profile')}}"> {!! Lang::get('core.personalinfo') !!} </a></li>
			<li class="@if($section == 'pass')active @endif"><a href="{{URL::to('user/profile?section=pass')}}">{!! Lang::get('core.changepassword') !!} </a></li>
			<li class="@if($section == 'acnt')active @endif"><a href="{{URL::to('user/acntdetails?section=acnt')}}">Account Details</a></li>
		</ul>
		
			<div class="" id="">
				<?php
					$already_acc=(trim($userDatas->ext_acc_id)!='') ? "yes" : "no";
					?>
				@if($already_acc=="yes")
					<div class="tab_bl panel space-4">
						<div class="panel-header">{{trans('core.my_wallet')}}</div>
						<div class="table-responsive">
							

							<table class="table panel-body panel-light">
			<tbody>
				<!-- <tr>
					<th colspan="2"><small style="color: #bf3c2e">Note : For each transaction, the admin will get 5%</small></th>
				</tr> -->
				<tr>
					<th>{!! trans('core.abs_total_amnt') !!}</th>
					<td>$ {!! number_format($total_Amount,2) !!}</td>
				</tr>
				<tr>
					<th>Requested amount</th>
					<td>$ {!! number_format($ramount,2) !!} (-)</td>
				</tr>
				<tr>
					<th>Withdrawal amount</th>
					<td>$ {!! number_format($withdrawal,2) !!} (-)</td>
				</tr>
				<tr>
					<th>{!! trans('core.abs_avail_amnt') !!}</th>
					<td>$ {!! number_format(($total_Amount-($ramount+$withdrawal)),2) !!}</td>
				</tr>
				<tr>
					<th>Click here to send transfer request to Admin</th>
					<td><button type="button" class="btn btn-success btn-sm transfer" onclick="xxxx(<?php echo ($total_Amount-($ramount+$withdrawal));?>)">Send request</button></td>
				</tr>
			</tbody>
		</table>
		<table class="table panel-body panel-light divtranfer" style="display: none;">
			<tbody>
				<tr>
					<th>Amount</th>
					<td><div class="pos_relatve"><input type="text" placeholder="Enter amount" name="tot_amt" id="tot_amt"><div class="symbol">$</div></div></td>
				</tr>
				<tr>
					<th></th>
					<th id="errmsg" class="hide"></th>
				</tr>
				<tr>
					<td><button type="button" class="btn btn-success btn-sm totransfermoney" style="margin-right: 10px;">Submit</button><button type="button" class="btn btn-primary btn-sm" onclick="xxxx(<?php echo ($total_Amount-($ramount+$withdrawal));?>)">Cancel</button>
					</td>
				</tr>
			</tbody>
		</table>
						</div>
					</div>
				@else
					<div class="bank-details col-md-10">
						<form method="post" action="{{URL::to('user/addextbankdetails')}}">
							<h3>Before requesting payment, please fill up these all required bank details</h3>
							<!-- <div class="col-md-12"> -->
							<div class="row row-condensed margin-lr-min" >
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_first_name_Star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="fname" value="{{ $userDatas->first_name }}" placeholder="first name" autocomplete="off">
								</div><br>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_last_name_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="lname" value="{{ $userDatas->last_name }}" placeholder="last name" autocomplete="off">
								</div><br>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_email_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="email" value="{{ $userDatas->email }}" placeholder="last name" autocomplete="off">
								</div><br>
							</div>
							<!-- </div> -->
							<div class="row row-condensed margin-lr-min" >
								<span style="margin-left: 30px;">{!! trans('core.abs_dob_star') !!}</span>
								<div class="col-md-12 col-sm-12 margin_btm" style="margin-left:-15px;">
									<div class="col-md-3 dob-margin"> <input type="text" value="{{$dob[2]}}" required name="dd" maxlength="2" placeholder="dd" autocomplete="off"> </div>
									<div class="col-md-3 dob-margin"> <input type="text" value="{{$dob[1]}}" required name="mm" maxlength="2" placeholder="mm" autocomplete="off"> </div>
									<div class="col-md-3 dob-margin"> <input type="text" value="{{$dob[0]}}" required name="yyyy" maxlength="4" placeholder="yyyy" autocomplete="off"> </div>
								</div>
							</div>
							<div class="row row-condensed margin-lr-min" >
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6" >{!! trans('core.abs_adrs_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="line1" value="" placeholder="Address line1" autocomplete="off">
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_Adrs_line_opt') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" name="line2" value="" placeholder="Address line2" autocomplete="off">
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_city_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="city" value="" placeholder="City name: Paris" autocomplete="off">
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_state_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="state" value="" placeholder="State ISO name: 75 or CA" autocomplete="off">
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_post_code_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="postal_code" value="" placeholder="postal code : 75008" autocomplete="off">
								</div>
							</div>
							<div class="row row-condensed margin-lr-min" >
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6" >{!! trans('core.abs_select_currency_star') !!}</span>
									<select name="country_code" class="allselect nobg col-md-8 col-sm-6 col-xs-6">
										<option value="">{!! trans('core.abs_select') !!}</option>
										@foreach($countries as $val)
											<option value="{{$val->iso}}">{{$val->nicename}}</option>
										@endforeach
										<!-- <option value="us">US</option>
										<option value="fr">France</option> -->
									</select>
								</div>
							</div>
							<div class="row row-condensed margin-lr-min" >
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_select_currency_star') !!}</span>
									<select name="currency" class="allselect nobg col-md-8 col-sm-6 col-xs-6">
										<option value="">{!! trans('core.abs_select') !!}</option>
										@foreach($currency as $val)
											<option value="{{$val->currency_name}}">{{$val->currency_name}}</option>
										@endforeach
										<!-- <option value="us">US</option>
										<option value="fr">France</option> -->
									</select>
								</div>
							</div>
							<div class="row row-condensed margin-lr-min" >
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6" >{!! trans('core.abs_bus_name_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="business_name" value="" placeholder="Your business name" autocomplete="off">
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6" >{!! trans('core.abs_bus_tax_id_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="business_tax_id" value="" placeholder="Your business tax id" autocomplete="off">
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_pers_id_num_star') !!}</span>
									<input class="col-md-8 col-sm-6 col-xs-6" type="text" class="" required name="pers_id" value="" placeholder="Your Personal id" autocomplete="off">
								</div>
							</div>
							<div class="row row-condensed margin-lr-min" >
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6">{!! trans('core.abs_select_acnt_type_star') !!}</span>
									<select name="ac_type" class="allselect nobg col-md-8 col-sm-6 col-xs-6">
										<option value="">{!! trans('core.abs_select') !!}</option>
										<option value="individual">{!! trans('core.abs_individual') !!}</option>
										<option value="company">Company</option>
									</select>
								</div>
								<div class="col-md-12 col-sm-12 margin_btm">
									<span class="col-md-4 col-sm-3 col-xs-6" >{!! trans('core.abs_iban_num_star') !!}</span>
									<input type="text" class="col-md-8 col-sm-6 col-xs-6" required name="iban" value="" placeholder="Your IBAN" autocomplete="off">
									<input type="hidden" value="{{ $userDatas->id }}" required name="hid" />
								</div>
							</div>
							<div class="sub-btn">
							<input  type="submit" name="addbank_details_sub" value="Submit" /></div><br><br>
						</form>
					</div>
				@endif
			</div>
	</div>
</div>
<script type="text/javascript">
	$(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
		$(this).val($(this).val().replace(/[^\d].+/, ""));
		if(event.which == 8){

		} else if((event.which < 48 || event.which > 57 )) {
			event.preventDefault();
		}
	});
	function xxxx(val){
		if(val>0){
			$(".divtranfer").toggle();
		}else{
			alert('Your available balance is zero.You can\'t send request');
		}
	}
	$(document).on('click','.totransfermoney',function(){
		send_adminRequest();
	})
	function send_adminRequest() {
		var tot_amt=$("#tot_amt").val();
		$.ajax({
			url		: base_url+'totransfermoney',
			data	: {'tot_amt':tot_amt},
			type	: 'POST',
			dataType: 'json',
			success	: function(res){
				if(res.status == 'success'){
					$("#errmsg").removeClass('hide').html('<label>Request send successfully</label>').css('color','green');
					setTimeout(function() {location.reload(); }, 2000);
				}else if(res.status == 'amount_exceed'){
					$("#errmsg").removeClass('hide').html('<label>Your request amount exceeds your available balance</label>').css('color','red');
				} else {
					$("#errmsg").removeClass('hide').html('<label>Oops!...Something went wrong</label>').css('color','red');
				}
			}
		});
	}
	$(document).on('keydown','#tot_amt',function(e){
		var currentVal = $(this).val();
		if(e.which == 13) {
			if($.isNumeric(currentVal)){
				e.preventDefault();
				send_adminRequest();
			} else {
				$("#errmsg").removeClass('hide').html('<label>Please enter valid amount</label>').css('color','red');
			}
		}
	})
</script>
@endsection
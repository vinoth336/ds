
@if(count($customize_info) > 0)
	@foreach($customize_info as $key=> $customize)
		{{--*/
		$cus_name = \SiteHelpers::getCustomizeName($customize->cat_id);
		$cus_item = \SiteHelpers::getCustItems($food_id,$customize->cat_id);
		/*--}}

		<!-- <div class=""><h7>{{$cus_name}}</h7></div> -->
		@if(count($cus_item) > 0)
		<div class="row">
		<input type="hidden" id="cus_update_ucart_id" value="{!! $ucartId !!}">
		<input type="hidden" id="base_price" value="{{$per_price}}">
		<input type="hidden" id="total_count" value="{{$total_count}}">
		@if($cus_item[0]->type == 'optional')

			<div class="cust-check bg_tict">
			<div class="bor_btm">
			<h7>{{$cus_name}}</h7> 

			@foreach($cus_item as $citems => $cvals)

                <div class="col-md-6 col-xs-12">  
				<label class="col-xs-12" for="check_{{$cvals->id}}">
					{{--*/ $chck = \SiteHelpers::FoodCustItemCheck($uid,$food_id,$cvals->id,$ucartId); /*--}}
					{{--*/ $amout = number_format((float)\SiteHelpers::CurrencyValue($cvals->price),2,'.',''); /*--}}
					<input onchange="custPrice('check',this);" id="check_{{$cvals->id}}" type="checkbox" @if($chck) checked @endif class="cust_checked_check " value="{{$cvals->price}}" data-item-id="{{$cvals->id}}">
					<span>{{$cvals->name}} @if($cvals->price > 0) ( {!! $currsymbol !!} {{$amout}} )</span> @endif
				</label>
				</div>
			@endforeach
			</div>
			</div>
		@else 
		<div class="col-md-6 col-xs-12 text-style"> 
         <h7>{{$cus_name}}</h7>
             <div class="cust-select">
				<select style="font-family: 'FontAwesome', 'Open Sans';" class="cust_checked_select custSelectprice form-control col-md-6 col-xs-12" onclick="custPrice('select',this);">
					@foreach($cus_item as $citems => $cvals)
						{{--*/ $chck = \SiteHelpers::FoodCustItemCheck($uid,$food_id,$cvals->id,$ucartId) /*--}}
						{{--*/ $amout = number_format((float)\SiteHelpers::CurrencyValue($cvals->price),2,'.',''); /*--}}
						<option  @if($chck) selected @elseif($citems == '0') selected @endif data-item-id="{{$cvals->id}}" value="{{$cvals->price}}">{{$cvals->name}} @if($cvals->price != '0.00') ( {!! $currsymbol !!} {{ $amout }} ) @endif </option>
					@endforeach
				</select>
			</div>
			</div>
		@endif
		</div>
		@endif
	@endforeach
@endif


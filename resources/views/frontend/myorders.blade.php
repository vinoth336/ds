<style type="text/css">
    @media screen and (max-width: 900px) {
        .res_table td:nth-of-type(1):before { content: "{!! Lang::get('core.orderno') !!}"; }
        .res_table td:nth-of-type(2):before { content: "{!! Lang::get('core.datetime') !!}" ; }
        .res_table td:nth-of-type(3):before { content: "{!! Lang::get('core.items') !!} "; }
        .res_table td:nth-of-type(4):before { content: "{!! Lang::get('core.service_tax') !!}"; }
        .res_table td:nth-of-type(5):before { content: "{!! Lang::get('core.total') !!}" ; }
        .res_table td:nth-of-type(6):before { content: "{!! Lang::get('core.delivery_type') !!} "; }
        .res_table td:nth-of-type(7):before { content: "{!! Lang::get('core.payment') !!} "; }
        .res_table td:nth-of-type(8):before { content: "{!! Lang::get('core.status') !!}"; }
    }

</style>
@if(count($orders) != 0)
<div class="account_details col-xs-12 nopadding">
<div class="smallHeading swiggyGray">My Orders</div>
<table class="display nowrap res_table" id="example2" cellspacing="0" width="100%">
    <thead> 
        <th>{!! Lang::get('core.orderno') !!}</th>
        <th>{!! Lang::get('core.datetime') !!}</th>
        <th>{!! Lang::get('core.items') !!}</th>
        <?php $cursymbol = (\Session::has('currency_symbol')) ? \Session::get('currency_symbol') : '$'; ?>
        <th>{!! Lang::get('core.service_tax') !!} (in {!! $cursymbol !!} ) </th>
        <th>{!! Lang::get('core.total') !!} (in {!! $cursymbol !!} )</span> </th>
        <th>{!! Lang::get('core.delivery_type') !!}</th>
        <th>{!! Lang::get('core.payment') !!} </th>
        <!--  <td>Delivery Address</td> -->
        <th>{!! Lang::get('core.status') !!}</th>
    </thead>
    <tbody>
        
        @foreach($orders as $order)
        <?php $old_rating = SiteHelpers::getOrderRating($order->res_id); ?>
        <tr>
            <td> {{ $order->orderid }} </td>
            <td> {{ $order->date }} </td>
            <td> {{ $order->order_details }} </td>
            <?php $ser_tax = \SiteHelpers::CurrencyValue($order->s_tax);
              $gtotal = \SiteHelpers::CurrencyValue($order->grand_total); ?>
            <td> {{ $ser_tax }}</td>
            <td> {{ $gtotal }}</td>
            <td> {{ $order->delivery_type}}</td>
            <td>
            @if($order->delivery =='on_delivery')
               {{$order->delivery}} 
            @elseif($order->delivery_type == 'paypal' || $order->delivery_type == '2checkout')
                @if($order->delivery == 'unpaid')
                    <?php $oid = base64_encode($order->orderid); ?>
                    <a href="{{url('frontend/pendingcart/?oid='.$oid)}}"><font color="red">{{$order->delivery}}</font></a>
                @else
                    {{$order->delivery}}
                @endif
            @endif
            </td>
            <td>    
            <input type="hidden" value="{{$old_rating}}" id="old_rating_{{$order->id}}">
            @if($order->status ==0)
                {!! Lang::get('core.pending') !!}
            @elseif ($order->status ==1)
                {!! Lang::get('core.order_cust_sta_one') !!}
            @elseif($order->status == 2)
                {!! Lang::get('core.order_cust_sta_two') !!}
            @elseif($order->status == 3)
                {!! Lang::get('core.dispatch') !!}
            @elseif($order->status == 4)
                {!! Lang::get('core.delivered') !!}
                <a href="javascript:void(0);" data-orderid="{{$order->id}}" data-oldrating="{{$old_rating}}" data-resid="{{$order->res_id}}" id="give_rating"><font color="red">Rating</font></a> 
            @elseif ($order->status == 5)
                {!! Lang::get('core.cancelled') !!}
            @endif
            </td>
            <!--  <td> {{ $order->address }} </td> -->
        </tr>
        @endforeach

    </tbody> 
</table>
</div>
@else
<div class="noOrders">
    <h5 class="strong text-center"> {!! Lang::get('core.made_order') !!}</h5>
    <div class="text-center">
        <img src="../abserve/foodstar/img/empty-orders-image.png">
        <div class="explore">
            <a class="btn btnUpdate" href="search?keyword=&lat=&lang=" target="_blank">{!! Lang::get('core.explore') !!}</a>
        </div>
    </div>
</div>
@endif
<div id="rating_popup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content col-xs-12 nopadding">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Rating</h4>
            </div>
            <div class="modal-body col-xs-12">
                <div class="col-md-9 col-sm-8 col-xs-7 msg_section">
                <div id="empty_message" style="display: none;"><font color="red">{!! trans('core.abs_select_rating') !!}</font></div>
                    <div class="star-rating pull-left rating_content">
                    </div>
                    <div class="pull-right">                      
                        <input type="hidden" id="rat_res_id"  value="">
                        <input type="hidden" id="rat_order_id" value="">
                        <input type="button" id="rating_submit" class="btn btn-primary label-success pull-left"  placeholder="{{trans('core.msg_type_here')}}" value="Submit" onclick="sendrating();">
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click',"#give_rating",function(){
        var res_id = $(this).data('resid');
        var content = '';var i; var checked;
        var oid = $(this).data('orderid');
        var oldRating = $("#old_rating_"+oid).val();
        for(i=5;i>=1;i--) {
            if(oldRating == i)
                checked = "checked";
            else
                checked = " ";
            content += '<input name="rating" type="radio" value="'+i+'" id="condition_'+i+'" class="star-rating-input"'+checked+'><label for="condition_'+i+'" class="star-rating-star js-star-rating"><i class="icon icon-star icon-size-2"></i>&nbsp;</label>';
        }
        $("#rat_res_id").val(res_id);
        $("#rat_order_id").val(oid);
        $(".rating_content").html(content);
        $("#rating_popup").modal('show');
    })
    function sendrating(){
        var rating_val = 0;
        var res_id = $("#rat_res_id").val();
        var oid = $("#rat_order_id").val();
        if ($("input[name='rating']:checked").length > 0) {
            rating_val = $('input:radio[name=rating]:checked').val();
        }
        if(res_id != '' && rating_val != 0) {
            $.ajax({
                url : base_url+"frontend/saverating",
                type : "POST",
                data : { rid : res_id, rating : rating_val },
                dataType : "json",
                success :function (data) {
                    if(data.message == 'success'){
                        $("#old_rating_"+oid).val(rating_val);
                        $("#rating_popup").modal('hide');
                    }
                }
            })
        } else {
            if(rating_val == 0){
                $("#empty_message").show();
                setTimeout(function(){ $("#empty_message").hide() },5000);
            }
        }
    }
</script>
<style type="text/css">
    .star-rating-input:checked ~ .star-rating-star, .like_input:checked + label {
    color: #49CB92;
}
.star-rating-star {
    float: right;
    color: #82888a;
    cursor: pointer;
}
.star-rating input[type="radio"]{display:none;}
</style>
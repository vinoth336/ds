<div id="accordion" class="panel-group1 absfilter">
    
    <form role="form" action="{{ URL::to('/frontend/search')}}" method="get" id="search_form">
        <div class="col-md-8 col-sm-12 col-xs-12 no_pad">
            <h6>{!! Lang::get('core.filter_by') !!}</h6>
            <div class="panel-sort">
                <div class="panel-topic">{!! Lang::get('core.budget') !!}</div>
                <div class="panel-option">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php $budget1 = ''; $budget2 = '';  $budget3 = ''; $budget4 = '';
                            if(isset($_REQUEST['budget'])){
                                $bd = explode(",",$_REQUEST['budget']);
                                if(in_array("1",$bd)) $budget1 = "checked";  
                                if(in_array("2",$bd)) $budget2 = "checked";  
                                if(in_array("3",$bd)) $budget3 = "checked"; 
                                if(in_array("4",$bd)) $budget4 = "checked";  
                            } 
                            ?>
                            <div class="checkbox">
                                <label><input type="checkbox" class=" budget" {{$budget1}} value="1"><label>$</label></label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" class=" budget"   {{$budget2}}  value="2"><label>$</label><label>$</label></label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" class=" budget"   {{$budget3}}   value="3"><label>$</label><label>$</label><label>$</label></label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" class=" budget"   {{$budget4}}  value="4"><label>$</label><label>$</label><label>$</label><label>$</label></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-sort">
                <div class="panel-topic">{!! Lang::get('core.show_restaurents') !!}</div>
                <div class="panel-option">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="checkbox">
                                <label><input type="checkbox" class="checkbox_input" name="offer" value="1" @if(isset($_REQUEST['offer'])) @if($_REQUEST['offer'] == '1') checked @endif @endif>{!! Lang::get('core.with_offers') !!}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-sort">
               <div class="panel-topic">{!! Lang::get('core.popular_cuisines') !!}</div>
                <div class="panel-option">
                    <div class="form-group">
                        <div class="col-sm-12">
                            @foreach($cuisine as $cus)
                            <div class="checkbox">
                                <?php 
                                $choosed_cuisines = trim($_REQUEST['cuisines']);
                                $choosed_array = explode(",", $choosed_cuisines);  
                                if(!empty($choosed_array)) { 
                                    if (in_array($cus->id, $choosed_array)){
                                        $checked = "checked";
                                    } else {
                                        $checked = "";
                                    }
                                }
                                ?>
                                <label><input type="checkbox" class="cuisines" {{ $checked }}  value="{{$cus->id}}">{{$cus->name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 no_pad">
            <div class="pull_right">
                <h6>{!! Lang::get('core.sort_by') !!}</h6>
                <?php $_REQUEST['sort_by']=trim($_REQUEST['sort_by']); ?>
                <select name="sort_by" class="sort_by">
                    <option  value="">{!! Lang::get('core.sort_by') !!}</option>
                    <option  value="delivery_time" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'delivery_time') selected @endif @endif>{!! Lang::get('core.delivery_time') !!}</option>
                    <!--   <option value="rating" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'rating') selected @endif @endif>Rating</option> -->
                    <option value="budget" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'budget') selected @endif @endif>{!! Lang::get('core.budget') !!}</option>
                    <option value="name" @if(isset($_REQUEST['sort_by'])) @if($_REQUEST['sort_by'] == 'name') selected @endif @endif>{!! Lang::get('core.name') !!}</option>
                </select>
            </div>
        </div>
        <input type="hidden" name="keyword" value="@if(isset($_REQUEST['keyword'])){{$_REQUEST['keyword']}} @endif">
        <input type="hidden" name="lang" value="@if(isset($_REQUEST['lang'])){{$_REQUEST['lang']}}@endif">
        <input type="hidden" name="lat" value="@if(isset($_REQUEST['lat'])){{$_REQUEST['lat']}}@endif">
        <input type="hidden" name="budget" id="budget" value="@if(isset($_REQUEST['budget'])){{$_REQUEST['budget']}} @endif">
        <input type="hidden" name="cuisines" id="cuisines" value="@if(isset($_REQUEST['cuisines'])){{$_REQUEST['cuisines']}} @endif">
        <input type="hidden" name="sort_by" id="sort_by" value="@if(isset($_REQUEST['sort_by'])){{trim($_REQUEST['sort_by'])}} @endif">
        <input type="submit" name="search_btn" id="search_btn" value="Search"  class="hidden"/>
    </form>
</div>
<script type="text/javascript">
    $(document).on("click",'.checkbox_input',function(){
        $("#search_btn").trigger('click');
    });
    $(document).on("click",'.budget',function(){
        var values = $('.budget:checked')
        .map(function(){return $(this).val();}).get();
        $('#budget').val(values);
        $("#search_btn").trigger('click');
    });
    $(document).on("click",'.cuisines',function(){


        var values = $('.cuisines:checked')
        .map(function(){return $(this).val();}).get();
        $('#cuisines').val(values);
        $("#search_btn").trigger('click');
    });
    $(document).on("change",'.sort_by',function(){
        var val = $(this).val();
        $("#sort_by").val(val);
        $("#search_btn").trigger('click');
    });
    $('form#search_form').submit(function() {
        $(':input', this).each(function() {
            this.disabled = !($(this).val());
        });
    });
</script>

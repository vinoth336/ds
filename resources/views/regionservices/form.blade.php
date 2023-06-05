@extends('layouts.app')

@section('content')
<style>
.check_cat{ margin:18px; }
/*.padd_level_1{ padding-left: 36px; }
.padd_level_2{ padding-left: 72px; }*/
.size{ width:90px; }
#datepairExample { margin: 15px 15px 0 30px; }
ul li { list-style-type:none; }
ul a:hover { text-decoration:none; }
</style>

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('regionservices?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{!! Lang::get('core.addedit') !!} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		{!! Form::open(array('url'=>'regionservices/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
		<div class="col-md-12">
        <fieldset><legend> Region Services</legend>
                    
            <div class="form-group hidethis " style="display:none;">
                <label for="Id" class=" control-label col-md-4 text-left"> Id </label>
                <div class="col-md-6">
                    {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
                </div> 
                <div class="col-md-2">
                </div>
            </div> 					                                   					
            <div class="form-group  " >
                <label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
                <div class="col-md-6">
                    <select name='region' rows='5' id='region' class='select2 ' required  ></select> 
                </div> 
                <div class="col-md-2">
                </div>
            </div> 					
            <div class="form-group  " >
                <label for="Status" class=" control-label col-md-4 text-left"> Status <span class="asterix"> * </span></label>
                <div class="col-md-6">
                          
                <label class='radio radio-inline'>
                <input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > Active </label>
                <label class='radio radio-inline'>
                <input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > Inactive </label> 
                </div> 
                <div class="col-md-2">
                </div>
                
                <!--<button type="button" onclick="toggleChevron(this)" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                <div id="row41" class="button"><span class="glyphicon glyphicon-plus"></span></div>
                <div id="row42" class="button"><span class="glyphicon glyphicon-plus"></span></div>
                <div id="row43" class="button"><span class="glyphicon glyphicon-plus"></span></div>
                
                <div class="row41">sample demo txt one 1</div>
                <div class="row42">sample demo txt two 2</div>
                <div class="row43">sample demo txt three 3</div>-->
                
                <!--<ul id="category-tabs" class="nav">
                    <li>
                        <a href="#"><i class="glyphicon glyphicon-plus">Test</i></a>
                        <ul class="nav">
                            <li>Test under</li>
                            <li>Test under 2</li>
                        </ul>
                    </li>
                    <li>
                    <a href="#"><i class="glyphicon glyphicon-plus">Test2</i></a>    
                        <ul class="nav">
                            <li>Test under 3</li>
                            <li>Test under 4</li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="glyphicon glyphicon-plus">Test3</i></a></li>
                </ul>-->
                <?php /*?><ul id="category-tabs" class="nav">
                	<?php 
					$tree = explode(",",$row['tree']);
					$node = explode(",",$row['node']);
					$sub_node = explode(",",$row['sub_node']);
					//print_r($sub_node);					
					$i=0; 
					foreach($service_cat as $item){ ?>
                    	<li><a href="javascript:void"><i class="glyphicon glyphicon-plus"><?php echo ucfirst($item->cat_name); ?></i>
                        	<input type="checkbox" name="category_level_0[]" <?php if(in_array($item->id,$tree)){ ?> checked="checked" <?php } ?> value="<?php echo $item->id; ?>" />
                            <span style="padding: 5px;"><?php echo ucfirst($item->cat_name); ?></span>
                        </a>
                        <ul class="nav">
                        <?php $sub_cat = \SiteHelpers::cate_serv_list($item->id, 1); ?>
                                                
                        <?php  $j=0; 
						foreach($sub_cat as $mydata){ 
							$_node = explode("-",$node[$i]); ?>
                            <li><a href="javascript:void"><i class="glyphicon glyphicon-plus"><?php echo ucfirst($mydata->cat_name); ?></i>
                            <input type="checkbox" name="category_level_0[]" <?php if(in_array($mydata->id,$node)){ ?> checked="checked" <?php } ?> value="<?php echo $mydata->id; ?>" />
                            <span style="padding: 5px;"><?php echo ucfirst($mydata->cat_name); ?></span>
                            </a></li>
                        <?php } ?>
                        </ul>
                        </li>
                    <?php } ?>
                </ul><?php */?>
                
            </div>
                                  
            <div class="form-group  " style="background:#fff;" >
				<div class="col-md-12">
					<ul id="category-tabs" class="">			
				  	<?php 
					$tree = explode(",",$row['tree']);
					$node = explode(",",$row['node']);
					$sub_node = explode(",",$row['sub_node']);
					//print_r($sub_node);					
					$i=0;   $j=0;
					foreach($service_cat as $item){ ?>
                    
                        <li class="padd_level_0 check_cat">
                        	<!--<button type="button" onclick="toggleChevron(this)" class="btn btn-default">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>-->
                            <a href="javascript:void"><i class="glyphicon glyphicon-plus"></i>
                            <input type="checkbox" name="category_level_0[]" <?php if(in_array($item->id,$tree)){ ?> checked="checked" <?php } ?> value="<?php echo $item->id; ?>" />
                            <span style="padding: 5px;"><?php echo ucfirst($item->cat_name); ?></span>
                        	</a>
                        <ul>
                        <?php $sub_cat = \SiteHelpers::cate_serv_list($item->id, 1); ?>
                                                
                        <?php 
						foreach($sub_cat as $mydata){ 
							$_node = explode("-",$node[$i]); ?>
                            <li class="padd_level_1 check_cat">
                            	<a href="javascript:void"><i class="glyphicon glyphicon-plus"></i>
                                <input type="checkbox" name="category_level_1[]" <?php if($mydata->id==$_node[0]){ ?> checked="checked" <?php $i++; } ?> value="<?php echo $mydata->id; ?>" />
                                <span style="padding: 5px;"><?php echo ucfirst($mydata->cat_name); ?></span>
                                </a>
                            	
                        	<ul>
                            <?php $subsubcat = \SiteHelpers::cate_serv_list($mydata->id, 2); ?>
                                <div id="datepairExample" <?php if(count($subsubcat) >0){ ?> style="display:none" <?php } ?>>
                                    <label for="Id" class=" control-label text-left"> Start Time </label>
                                    <input type="text" class="size time start" name="start_time<?php echo $mydata->id; ?>" value="<?php if($mydata->id==$_node[0]){ echo $_node[1]; } ?>" />
                                    <label for="Id" class=" control-label text-left"> End Time </label>
                                    <input type="text" class="size time end" name="end_time<?php echo $mydata->id; ?>" value="<?php if($mydata->id==$_node[0]){ echo $_node[2]; } ?>" /><br /><br />
                                    <label for="Id" class=" control-label text-left"> Service Charge </label>
                                    <input type="text" class="size" name="service_charge<?php echo $mydata->id; ?>" value="<?php if($mydata->id==$_node[0]){ echo $_node[3]; } ?>" />
                                </div>
                                                
                            <?php foreach($subsubcat as $subsub_cat){ 
								$sub_nodes = explode("-",$sub_node[$j]); ?>
                                
                                <li class="padd_level_2 check_cat">                                	
                                    <input type="checkbox" name="category_level_2[]" <?php if($subsub_cat->id==$sub_nodes[0]){ ?> checked="checked" <?php $j++; } ?> value="<?php echo $subsub_cat->id; ?>" />
                                    <span style="padding:5px;" ><?php echo ucfirst($subsub_cat->cat_name); ?></span>
                                    <div id="datepairExample">
                                        <label for="Id" class=" control-label text-left"> Start Time </label>
                                        <input type="text" class="size time start" name="start_time<?php echo $subsub_cat->id; ?>" value="<?php if($subsub_cat->id==$sub_nodes[0]){ echo $sub_nodes[1]; } ?>" />
                                        <label for="Id" class=" control-label text-left"> End Time </label>
                                        <input type="text" class="size time end" name="end_time<?php echo $subsub_cat->id; ?>" value="<?php if($subsub_cat->id==$sub_nodes[0]){ echo $sub_nodes[2]; } ?>" /><br /><br />
                                        <label for="Id" class=" control-label text-left"> Service Charge </label>
                                        <input type="text" class="size" name="service_charge<?php echo $subsub_cat->id; ?>" value="<?php if($subsub_cat->id==$sub_nodes[0]){ echo $sub_nodes[3]; } ?>" />
                                    </div>
                                </li>
                                
                            <?php //echo $sub_nodes[$i]; if($subsub_cat->id==$sub_nodes[$i]){ print_r($sub_nodes); $i++;}
							} ?>
                    		</ul>
                            </li>
                    	<?php } ?>
                    	</ul>
                        </li>
                    <?php } ?>
                    </ul>
				</div>
				<div class="col-md-2">
                </div>
			</div>
                                  
            <!--<div class="form-group  " >
            <label for="Tree" class=" control-label col-md-4 text-left"> Tree </label>
            <div class="col-md-6">
              {!! Form::text('tree', $row['tree'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
             </div> 
             <div class="col-md-2">
                
             </div>
            </div> 					
            <div class="form-group  " >
            <label for="Node" class=" control-label col-md-4 text-left"> Node </label>
            <div class="col-md-6">
              {!! Form::text('node', $row['node'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
             </div> 
             <div class="col-md-2">
                
             </div>
            </div> 					
            <div class="form-group  " >
            <label for="Sub Node" class=" control-label col-md-4 text-left"> Sub Node </label>
            <div class="col-md-6">
              {!! Form::text('sub_node', $row['sub_node'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
             </div> 
             <div class="col-md-2">
                
             </div>
            </div> 					
            <div class="form-group  " >
            <label for="Level" class=" control-label col-md-4 text-left"> Level </label>
            <div class="col-md-6">
              {!! Form::text('level', $row['level'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
             </div> 
             <div class="col-md-2">
                
             </div>
            </div>--> 
		</fieldset>
		</div>
		<div style="clear:both"></div>	
					
        <div class="form-group">
        	<label class="col-sm-4 text-right">&nbsp;</label>
            <div class="col-sm-8">	
            <button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
            <button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
            <button type="button" onclick="location.href='{{ URL::to('regionservices?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
            </div>	  
        
        </div> 
		 
		{!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 	
	<script src="http://jonthornton.github.io/Datepair.js/dist/datepair.js"></script>
	<script src="http://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>
	<script>
		$('#datepairExample .time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:i:sa'
		});

		$('#datepairExample').datepair();
	</script>
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#region").jCombo("{{ URL::to('regionservices/comboselect?filter=region:id:region_name') }}",
		{  selected_value : '{{ $row["region"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
		/*window.toggleChevron = function(button) {
			$(button).find('span').toggleClass('glyphicon-plus glyphicon-minus');
		}*/
		/*$(".button").click(function (e) {
	       e.preventDefault();
           
           $('[class^=row]').not($('.'+this.id)).hide();          
              
			$(button).find('span').toggleClass('glyphicon-plus glyphicon-minus'); 
           $('.'+this.id).slideToggle(500);
	   	});*/
	   	
		/*$(function() {
			// Hide submenus
			$(".nav ul").hide();
			
			// Toggle
			$(".nav > li > a").click(function(e) {
				$(this).siblings("ul").slideToggle();
				$(this).find('i').toggleClass('glyphicon-plus glyphicon-minus');
				e.preventDefault();
			});
		});*/
		$("#category-tabs ul").hide();
		$('#category-tabs li a').click(function(){
			$(this).next('ul').slideToggle('500');
			$(this).find('i').toggleClass('glyphicon-plus glyphicon-minus')
		});
		
	});
	</script>		 
@stop
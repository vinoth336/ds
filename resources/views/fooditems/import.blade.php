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
        <li><a href="{{ URL::to('dashboard') }}"> {!! trans('core.m_dashboard') !!} </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>  
	
	
	<div class="page-content-wrapper m-t">	 	 

<div class="sbox animated fadeInRight restaurant_page"> 
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> {!! trans('core.abs_clr_search') !!} </a>
		@if(Session::get('gid') ==1)
			<!-- <a href="{{ URL::to('abserve/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {!! Lang::get('core.btn_config') !!}" ><i class="fa fa-cog"></i></a> -->
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
    <div class="row">
    <div class="food-upload col-md-6">
    <h3>Import Food Items</h3>
    
    				{!! Form::open(array('url'=>'fooditems/saveimport?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'Addfood')) !!}
                
                <input style="margin: 20px -10px;" type="file" name="upload" class="btn" required/>
                
                <input type="submit" value="Import" class="btn btn-info btn-sm res_submit" name="sub"/>
                    
{!! Form::close() !!}
		</div>
        <div class="cat-upload col-md-6" style="padding: 0 40px;">
    <h3>Import Categories</h3>
    
    				{!! Form::open(array('url'=>'fooditems/savecatimport?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'Addfood')) !!}
                
                <input style="margin: 20px -10px;" type="file" name="catupload" class="btn" required/>
               <input type="submit" value="Import" class="btn btn-info btn-sm res_submit" name="subm"/>
                    
{!! Form::close() !!}
		</div>
        </div>
    	
	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("fooditems/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
});	
</script>		
@stop
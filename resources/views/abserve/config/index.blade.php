@extends('layouts.app')


@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3>General Settings</h3>
      </div>
	  
	 
	  <ul class="breadcrumb">
		<li><a href="{{ URL::to('dashboard') }}">{!! Lang::get('core.home') !!}</a></li>
		<li><a href="{{ URL::to('config') }}">{!! Lang::get('core.t_generalsetting') !!}</a></li>
	  </ul>	  
	 
    </div>
 	<div class="page-content-wrapper">   
	@if(Session::has('message'))
	  
		   {{ Session::get('message') }}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		
<div class="block-content">
	<!--@include('abserve.config.tab')-->	
<div class="tab-content m-t">
  <div class="tab-pane active use-padding" id="info">	
  <div class="sbox  "> 
  <div class="sbox-title"></div>
  <div class="sbox-content"> 
		 {!! Form::open(array('url'=>'abserve/config/save/', 'class'=>'form-horizontal row', 'files' => true)) !!}

		<div class="col-sm-6 animated fadeInRight ">
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.fr_appname') !!} </label>
			<div class="col-md-8">
			<input name="cnf_appname" type="text" id="cnf_appname" class="form-control input-sm" required  value="{{ CNF_APPNAME }}" />  
			 </div> 
		  </div>  
		  
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.fr_appdesc') !!} </label>
			<div class="col-md-8">
			<input name="cnf_appdesc" type="text" id="cnf_appdesc" class="form-control input-sm" value="{{ CNF_APPDESC }}" /> 
			 </div> 
		  </div>  
		  
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.fr_comname') !!} </label>
			<div class="col-md-8">
			<input name="cnf_comname" type="text" id="cnf_comname" class="form-control input-sm" value="{{ CNF_COMNAME }}" />  
			 </div> 
		  </div>      

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.fr_emailsys') !!} </label>
			<div class="col-md-8">
			<input name="cnf_email" type="text" id="cnf_email" class="form-control input-sm" value="{{ CNF_EMAIL }}" /> 
			 </div> 
		  </div>      

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">Android Version </label>
			<div class="col-md-8">
			<input name="android_version" type="text" id="android_version" class="form-control input-sm" value="{{ ANDROID_VERSION }}" /> 
			 </div> 
		  </div>      

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">IOS Version </label>
			<div class="col-md-8">
			<input name="ios_version" type="text" id="ios_version" class="form-control input-sm" value="{{ IOS_VERSION }}" /> 
			 </div> 
		  </div>      

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">CCAvenue Access Code </label>
			<div class="col-md-8">
			<input name="ccavenue_accesscode" type="text" id="ccavenue_accesscode" class="form-control input-sm" value="{{ CCAVENUE_ACCESSCODE }}" /> 
			 </div> 
		  </div>      

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">CCAvenue Merchant Id </label>
			<div class="col-md-8">
			<input name="ccavenue_merchantid" type="text" id="ccavenue_merchantid" class="form-control input-sm" value="{{ CCAVENUE_MERCHANTID }}" /> 
			 </div> 
		  </div>      

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">CCAvenue Working Code </label>
			<div class="col-md-8">
			<input name="ccavenue_workingcode" type="text" id="ccavenue_workingcode" class="form-control input-sm" value="{{ CCAVENUE_WORKINGCODE }}" /> 
			 </div> 
		  </div>

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">CCAvenue Online Payment Status </label>
			<div class="col-md-8">
				<select name="ccavenue_payment_staus" id="ccavenue_payment_staus">
					<option value="true" @if(CCAVENUE_PAYMENT_STATUS == 'true') selected @endif>Active</option>
					<option value="false"  @if(CCAVENUE_PAYMENT_STATUS == 'false') selected @endif>Inactive</option>
				</select>
			 </div> 
		  </div>
             
		  <div class="form-group" style="display:none">
		    <label for="ipt" class=" control-label col-md-4"> Muliti language <br /> <small> Only Layout Interface </small> </label>
			<div class="col-md-8">
				<div class="checkbox">
					<input name="cnf_multilang" type="checkbox" id="cnf_multilang" value="1"
					@if(CNF_MULTILANG ==1) checked @endif
					  />  {!! Lang::get('core.fr_enable') !!} 
				</div>	
			 </div> 
		  </div> 
		  <!-- <div class="form-group">
		  	<label for="ipt" class="control-label col-md-4">Minimum Order Value </br><small> ( in {!! $cur_symbol !!} )</small></label>
		  	<div class="col-md-8">
				<div class="checkbox">
					<input name="min_order_val" class="numeric" type="text" id="min_order_val" value="@if($min_ord_val != '' && $min_ord_val != 0){!! $min_ord_val !!}@endif"> 
				</div>	
			</div> 
		  </div> -->
		   <div class="form-group" style="display:none">
		    <label for="ipt" class=" control-label col-md-4">{!! Lang::get('core.fr_mainlanguage') !!} </label>
			<div class="col-md-8">

					<select class="form-control" name="cnf_lang">

					@foreach(SiteHelpers::langOption() as $lang)
						<option value="{{  $lang['folder'] }}"
						@if(CNF_LANG ==$lang['folder']) selected @endif
						>{{  $lang['name'] }}</option>
					@endforeach
				</select>
			 </div> 
		  </div>   
		      

		   <div class="form-group" style="display:none">
		    <label for="ipt" class=" control-label col-md-4"> Frontend Template </label>
			<div class="col-md-8">

					<select class="form-control" name="cnf_theme">
					<!-- @foreach(SiteHelpers::themeOption() as $t)
						<option value="{{  $t['folder'] }}"
						@if(CNF_THEME ==$t['folder']) selected @endif
						>{{  $t['name'] }}</option>
					@endforeach -->
					<option value="abserve" selected>Abserve</option>
				</select>
			 </div> 
		  </div> 

		  <div class="form-group hide">
		    <label for="ipt" class=" control-label col-md-4"> Development Mode ?   </label>
			<div class="col-md-8">
				<div class="checkbox">
					<input name="cnf_mode" type="checkbox" id="cnf_mode" value="1"
					@if (defined('CNF_MODE') &&  CNF_MODE =='production') checked @endif
					  />  Production
				</div>
				<small> If you need to debug mode , please unchecked this option </small>	
			 </div> 
		  </div> 		  
		  
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">&nbsp;</label>
			<div class="col-md-8">
				<button class="btn btn-primary" type="submit">{!! Lang::get('core.sb_savechanges') !!} </button>
			 </div> 
		  </div> 
		</div>

		<div class="col-sm-6 animated fadeInRight ">

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">Metakey </label>
			<div class="col-md-8">
				<textarea class="form-control input-sm" name="cnf_metakey">{{ CNF_METAKEY }}</textarea>
			 </div> 
		  </div> 

		   <div class="form-group">
		    <label  class=" control-label col-md-4">Meta Description</label>
			<div class="col-md-8">
				<textarea class="form-control input-sm"  name="cnf_metadesc">{{ CNF_METADESC }}</textarea>
			 </div> 
		  </div>  

		   <div class="form-group">
		    <label  class=" control-label col-md-4">Backend Logo</label>
			<div class="col-md-8">
				<input type="file" name="logo">
				<p> <i>Please use image dimension 155px * 30px </i> </p>
				<div style="padding:5px; border:solid 1px #ddd; background:#f5f5f5; width:auto;">
				 	@if(file_exists(public_path().'/abserve/images/'.CNF_LOGO) && CNF_LOGO !='')
				 	<img src="{{ asset('abserve/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}" />
				 	@else
					<img src="{{ asset('abserve/images/logo.png')}}" alt="{{ CNF_APPNAME }}" />
					@endif	
				</div>				
			 </div> 
		  </div>  		  

		</div>  
		 {!! Form::close() !!}
	</div>
	</div>	 
</div>
</div>
</div>
</div>
<script type="text/javascript">
	var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(46); //dot
        $(function () {
            $(".numeric").bind("keypress", function (e) {
                var keyCode = e.which ? e.which : e.keyCode
                var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
                $(".error").css("display", ret ? "none" : "inline");
                return ret;
            });
            $(".numeric").bind("paste", function (e) {
                return false;
            });
            $(".numeric").bind("drop", function (e) {
                return false;
            });
        });
</script>
@stop
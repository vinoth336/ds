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
		<li><a href="{{ URL::to('customersupport?return='.$return) }}">{{ $pageTitle }}</a></li>
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
        
                {!! Form::open(array('url'=>'customersupport/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
                <div class="col-md-12">
                  <fieldset><legend> Customer Support</legend>
                    
                    <div class="form-group hidethis " style="display:none;">
                    	<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
                    	<div class="col-md-6">
                      		{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
                     	</div> 
                    	<div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group hidethis " style="display:none;">
                    	<label for="Group Id" class=" control-label col-md-4 text-left"> Group Id </label>
                    	<div class="col-md-6">
                      		<!--{!! Form::text('group_id', $row['group_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}-->
                            <input name="group_id" type="hidden" id="group_id" class="form-control" value="6" />
                     	</div> 
                     	<div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group  " >
                    	<label for="Username" class=" control-label col-md-4 text-left"> Username <span class="asterix"> * </span></label>
                    	<div class="col-md-6">
                      		{!! Form::text('username', $row['username'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
                     	</div> 
                     	<div class="col-md-2"></div>
                   	</div>					
                    <div class="form-group  " >
                    	<label for="Email" class=" control-label col-md-4 text-left"> Email <span class="asterix"> * </span></label>
                    	<div class="col-md-6">
                      		{!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
                     	</div> 
                     	<div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group  " >
                    	<label for="First Name" class=" control-label col-md-4 text-left"> First Name <span class="asterix"> * </span></label>
                    	<div class="col-md-6">
                      		{!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
                     	</div> 
                    	<div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group  " >
                    	<label for="Active" class=" control-label col-md-4 text-left"> Active <span class="asterix"> * </span></label>
                    	<div class="col-md-6">
                      
                        <label class='radio radio-inline'>
                        <input type='radio' name='active' value ='1' required @if($row['active'] == '1') checked="checked" @endif > Active </label>
                        <label class='radio radio-inline'>
                        <input type='radio' name='active' value ='0' required @if($row['active'] == '0') checked="checked" @endif > Inactive </label> 
                     	</div> 
                     	<div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group  " >
                    	<label for="Phone Number" class=" control-label col-md-4 text-left"> Phone Number <span class="asterix"> * </span></label>
                    	<div class="col-md-6">
                      		{!! Form::text('phone_number', $row['phone_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
                     	</div> 
                     	<div class="col-md-2"></div>
                    </div> 					
                    <div class="form-group  " >
                    	<label for="Agent Number" class=" control-label col-md-4 text-left"> Agent Number <span class="asterix"> * </span></label>
                    	<div class="col-md-6">
                      		{!! Form::text('agent_number', $row['agent_number'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
                     	</div> 
                     	<div class="col-md-2"></div>
                    </div>
                    <!--<div class="form-group  " >
                    <label for="Password" class=" control-label col-md-4 text-left"> Password <span class="asterix"> * </span></label>
                    <div class="col-md-6">
                      {!! Form::text('password', $row['password'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
                     </div> 
                     <div class="col-md-2">
                        
                     </div>
                    </div>-->                    
                    <div class="form-group">
                    	<label for="ipt" class=" control-label col-md-4 text-left" > </label>
                    	<div class="col-md-8">
                            @if($row['id'] !='')
                            {!! Lang::get('core.notepassword') !!}
                            @endif	 
                    	</div>
                    </div>	
                    <div class="form-group">
                    	<label for="ipt" class=" control-label col-md-4"> {!! Lang::get('core.newpassword') !!} @if($row['id'] =='') * @endif</label>
                        <div class="col-md-8">
                            <input name="password" type="password" id="password" class="form-control input-sm" value="" @if($row['id'] =='')  required @endif /> 
                        </div> 
                    </div>  
                    <div class="form-group">
                        <label for="ipt" class=" control-label col-md-4"> {!! Lang::get('core.conewpassword') !!} @if($row['id'] =='') * @endif</label>
                        <div class="col-md-8">
                            <input name="password_confirmation" type="password" id="password_confirmation" class="form-control input-sm" value=""  @if($row['id'] =='') required @endif />  
                        </div> 
                    </div>                                  
                  
				  </fieldset>
				</div>
		
				<div style="clear:both"></div>	
					
                <div class="form-group">
                    <label class="col-sm-4 text-right">&nbsp;</label>
                    <div class="col-sm-8">	
                    	<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {!! Lang::get('core.sb_apply') !!}</button>
                    	<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {!! Lang::get('core.sb_save') !!}</button>
                    	<button type="button" onclick="location.href='{{ URL::to('customersupport?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {!! Lang::get('core.sb_cancel') !!} </button>
                    </div>                
                </div> 
		 
		 	{!! Form::close() !!}
		</div>
		</div>		 
	</div>	
</div>			 
<script type="text/javascript">
$(document).ready(function() { 

	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});		
	
});
</script>
@stop
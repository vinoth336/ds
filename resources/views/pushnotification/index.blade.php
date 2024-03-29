@extends('layouts.app')
@section('content')
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> Notification </h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active"> Pushnotification</li>
      </ul>	  
	  
    </div>

    <div class="page-content-wrapper m-t">	
	
				          {!! Form::open(array('url'=>'pushnotification/message?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'pushnotification')) !!}
		                <div class="col-md-12">    
                         <div class="form-group"> 
		   						<label for="Notification" class=" control-label col-md-4 text-left"> Notification Device <span class="asterix"> * </span>
								</label>
								<div class="col-md-6">
								<label class='radio radio-inline'>
									<input type='radio' name='status' value ='android' required @if($row['status'] == 'android') checked="checked" @endif > ANDROID 
								</label>
								<label class='radio radio-inline'>
									<input type='radio' name='status' value ='ios' required @if($row['status'] == 'ios') checked="checked" @endif > IOS
								</label> 
								</div> 
						</div> 
            
                     
            
                 
                        <div class="form-group"> 
								<label for="Image" class=" control-label col-md-4 text-left"> Image Upload </label>
								<div class="col-md-6">
									<input  type='file' name='image' id='image' @if($row['image'] =='') class='required' @endif style='width:150px !important;'  />
									<!--<div >
										{!! SiteHelpers::showUploadedFile($row['image'],'/uploads/res_items/'.$row['restaurant_id'].'/') !!}
									</div>	-->				
								</div> 
								<div class="col-md-2">
								</div>
							</div>  
                            
                            
                            
            
                         <div class="form-group"> 
								<label for="Message" class=" control-label col-md-4 text-left"> Notification Message <span class="asterix"> * </span></label>
								<div class="col-md-6">
								{!! Form::textarea('message', $row['message'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true','rows'=>'3'  )) !!} 
								</div> 
						</div>
                        
                       
                        
                        <div class="form-group  " >
                                    <label for="Region" class=" control-label col-md-4 text-left"> Region <span class="asterix"> * </span></label>
                                    <div class="col-md-6"> 
                                      <?php  
				if(session()->get('gid') == '1'){
					 $regionall = \DB::select("SELECT *  FROM `region` ");
				}elseif(session()->get('gid') == '7'){
					$regionall = \DB::select("SELECT *  FROM `region` WHERE `id`='".session()->get('rid')."'");
				}	?>
                                    
                                         <select rows='5' class='select2' name="region" required>
                                          <option value="" >-- Please select --</option>
				  <?php  
                  if(session()->get('gid') == '1'){
                 	 $regionall = \DB::select("SELECT *  FROM `region` ");  ?>
                 	 <option value="all">All region</option>  
                  <?php  	}elseif(session()->get('gid') == '7'){
                 	 $regionall = \DB::select("SELECT *  FROM `region` WHERE `id`='".session()->get('rid')."'");
                  }	?>
								
								 <?php foreach($regionall as $region1)  {  ?>
                                <option value="<?php echo $region1->id;  ?>"><?php echo $region1->region_name;  ?></option>                                     <?php }  ?> 
                                        </select>
                                       
                                    </div> 
                                    <div class="col-md-2"></div>
                                 </div>
            
            
            
            
                      <div class="form-group"> 
								<label for="" class=" control-label col-md-4 text-left"></label>
								<div class="col-md-6">
								<button type="submit" name="submit" class="btn btn-primary btn-sm col-md-2 text-left" ><i class="fa fa-save "></i> Submit</button>	
								</div> 
					</div>
                    </div>
            	{!! Form::close() !!}
         
            
    </div>
  <script type="text/javascript">
  $(document).ready(function() {
	  
	  <?php if(session()->get('gid') == '7'){ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! session()->get('rid') !!}' });
		    $('#region').attr('readonly', true);
			 <?php	}else{ ?>
			$("#region").jCombo(base_url+"restaurant/comboselect?filter=region:id:region_name",
		    	{  selected_value : '{!! $row["region"] !!}' });		
			 <?php	} ?> 
	  
  });
  </script>
@stop
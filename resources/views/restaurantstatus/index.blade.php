 @extends('layouts.app')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
		</div> 
        
        <div class="form-group  " >
                    <label for="Id" class="control-label col-md-5 text-right">Region</label>
                        <div class="col-md-3">
                         <select id='regionselect1' class='form-control regionselect1' >
                         
                        <?php  if(session()->get('gid') == '1'){   ?>
                           <option value =''>All region</option>
                           <?php } ?>
                            <?php foreach ($region as $key => $value)
                                   { ?>
                                  <option value="<?php echo $value->region_keyword;  ?>" <?php if($_GET['region'] == $value->region_keyword){echo "selected"; }  ?>><?php echo $value->region_name;  ?></option>  
                                 <?php  }
                             ?>
                        </select>                      
                    </div>  
                    <div class="col-md-2">
                    </div>
                </div>		
   <div></div>
	<div id="msg" style="color:#228B22; text-align:left;"></div>
	
	 {!! Form::open(array('url'=>'restaurantstatus/delete/', 'class'=>'form-horizontal' ,'ids' =>'AbserveTable' )) !!}
	 <div class="" style="min-height:300px;">
	
	
    <table class="table table-striped ">
        <thead>
			 <tr> 
                        <th width="7%">S.No</th>
							<th>Restaurant Name</th>
							<th>Phone Number</th>
                            <th>Region</th>			
                            <th>Status</th>			
					
				 <!--<th width="7%">Action</th>-->
			  </tr>

        </thead>

        <tbody>    
           				
            @foreach ($rowData as $row)
         
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td>{{ $row->name }}    </td>									
					<td>{{ $row->phone }}    </td>									
					<td>{{ $row->region }}     </td>	
					<td>       
                    	
                        <div id="checkboxes">
					<input type="radio" name="active{{ $row->id }}"  class="active{{ $row->id }}" id="active{{ $row->id }}" value="0"<?php if($row->active==0){?> checked="checked" <?php }?> > Closed
							<input type="radio"  name="active{{ $row->id }}"  class="active{{ $row->id }}" id="active{{ $row->id }}"   value="1" <?php  if($row->active==1){?> checked="checked" <?php }?>  > Active
							<input type="radio" name="active{{ $row->id }}"  class="active{{ $row->id }}" id="active{{ $row->id }}" value="2"  <?php if($row->active==2){?> checked="checked" <?php }?> > Inactive
						</div>  
                 	</td>
                </tr>
                
             <script>
  
 $('.active<?php echo $row->id; ?>').on('ifChecked', function(event){
   var checkval = $(this).val(); 
   var resid = '<?php echo $row->id; ?>';
  
  $.ajax({
			url: '<?php echo url(); ?>/restaurantstatus/updatestatus',
			type: "post",
			data: {
				resid:resid ,checkval:checkval
			},
			success: function(data) {
				if(data ==1){
				 $('#msg').html('Updated successfully');
				 $('#msg').show();	
				  setTimeout(function(){
              $('#msg').fadeOut();
            },3000);
				}
           	}
		});
  
  
  
  
  
});
	
      </script>
                
                
             
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
    
      <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
	<!--@include('footer')-->
	</div>
</div>	
	</div>	  
</div>	
 
<script>
$(document).ready(function(){  

	$('.do-quick-search').click(function(){
		$('#AbserveTable').attr('action','{{ URL::to("restaurantstatus/multisearch")}}');
		$('#AbserveTable').submit();
	});
	
	 $('#regionselect1').change(function(){
	
     var regionselect1 = $(this).val();
	 //alert(regionselect1);
	window.location.href = "<?php echo url(); ?>/restaurantstatus?region=" + regionselect1;
	
    });
});
	

 
</script>		
@stop
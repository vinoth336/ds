<div class="header_gap"></div>
<div class="contact_bg">
<div id="map" style="height:400px;"></div>
<div class="gap"></div>
<div class="container margin_60">
    <div class="row">
        <div class="col-md-8 col-sm-8 margin-tb-20">
        <div class="form_title">
        <h3><strong><i class="fa fa-pencil"></i></strong>{!! trans('core.abs_contact_us') !!}</h3>
            <p>Inceptos hac sagittis sit elit primis iaculis arcu quam justo per primis tempus ad iaculis cursus condimentum nullam pretium dui id sit lacus duis dignissim primis potenti aliquam malesuada ullamcorper</p>
            <p>Euismod volutpat risus luctus id varius volutpat adipiscing porttitor egestas nisl nunc luctus phasellus nibh tristique lacinia penatibus justo urna</p>
            </div>
            <div class="contact-form" >                            
                    @if(Session::has('message'))      
                           {!! Session::get('message') !!}
                    @endif
                    <ul class="parsley-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                   {!! Form::open(array('url'=>'home/contact', 'class'=>'form','parsley-validate'=>'','novalidate'=>' ')) !!}             
                   

                   <div class="row">     

                    <div class="col-md-6 col-sm-6">
                    <div class="form-group name">
                            <label for="name" class="sr-only">{!! trans('core.abs_name_caps') !!}</label>
                              {!! Form::text('name', null,array('class'=>'form-control', 'placeholder'=>'Full Name:', 'required'=>'true'  )) !!} 
                        </div><!--//form-group-->
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="form-group email">
                            <label for="email" class="sr-only">{!! trans('core.abs_email') !!}</label>
                             {!! Form::email('sender', null,array('class'=>'form-control', 'placeholder'=>'Email:', 'required'=>'true'  )) !!} 
                        </div><!--//form-group-->
                    </div>
                    </div>



                        <div class="row">

                        <div class="col-md-6 col-sm-6">
                        <div class="form-group email">
                            <label for="subject" class="sr-only">{!! trans('core.fr_emailsubject') !!}</label> 
                              {!! Form::text('subject', null,array('class'=>'form-control', 'placeholder'=>'Subject:', 'required'=>'true email'   )) !!} 
                        </div><!--//form-group-->
                        </div>
                        
                        <div class="col-md-6 col-sm-6">
                        <div class="form-group message">
                            <label for="message" class="sr-only">{!! trans('core.abs_messgae') !!}</label>
                            {!! Form::textarea('message',null,array('class'=>'form-control', 'placeholder'=>'Message:', 'required'=>'true'   )) !!}                                
                        </div><!--//form-group-->
                        </div>

                        </div>

                        <button class="btn btn-common" type="submit">{!! trans('core.abs_send_us_email') !!}</button>
                        <input type="hidden" name="redirect" value="contact-us">
                    {!! Form::close() !!}<!--//form-->                 
                </div><!--//contact-form-->
        </div>
        
        <div class="col-md-4 col-sm-4 margin-tb-20">
            <aside class="sidebar-right">
                <ul class="address-list list">
                    <li>
                        <h5>{!! trans('core.abs_email') !!}</h5><a href="mailto:support@abservetech.com">support@abservetech.com</a>
                    </li>
                    <hr>
                    <li>
                        <h5>{!! trans('core.phone_number') !!}</h5><a href="#">+(0452) 436 6678</a>
                    </li>
                    <hr>
                    <li>
                        <h5>{!! trans('core.abs_skype') !!}</h5><a href="skype:Abservetech">{!! trans('core.abs_abservetech') !!}</a>
                    </li>
                    <hr>
                    <li>
                        <h5>{!! trans('core.address') !!}</h5><address>{!! trans('core.abs_abserve_adrs') !!}</address>
                    </li>
                </ul>
            </aside>
        </div>
    </div>
    <div class="gap"></div>
</div>
</div>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyBmakwIjdQn-ZZi9VdDpG9Bc17AJvppfSc" type="text/javascript"></script>
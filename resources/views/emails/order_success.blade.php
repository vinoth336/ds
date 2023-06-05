<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{$header}}, </h2>
        <p> {{$username}}</p>
        <p> {{$hotel_name}} </p>
        <p> {{$msg}} </p>
        <p>
            {{$arrival}} <br />
            {{$departure}}<br />
            {{$nights}}<br />
            {{$room_type}}<br />
            {{$rate}}<br />
            {{$ref_no}}<br />
        </p>
    <!--     <p> Please follow link activation  <a href="{{ URL::to('user/activation?code='.$code) }}"> Active my account now</a></p>
        <p> If the link now working , copy and paste link bellow </p>
        <p> {{ URL::to('user/activation?code='.$code) }} </p>  -->
        <br /><br /><p> {!! trans('core.abs_thank_you') !!} </p><br /><br />
        
        {{ CNF_APPNAME }} 
    </body>
</html>
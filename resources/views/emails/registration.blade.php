<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{!! trans('core.abs_hello') !!}{{ $firstname }} , </h2>
		<p> {!! trans('core.abs_thanks_join_withus') !!} </p>
		<p> {!! trans('core.abs_below_isyour_acnt') !!} </p>
		<p>
			{!! trans('core.abs_con_email') !!}{{ $email }} <br />
			{!! trans('core.abs_con_pass') !!}{{ $password }}<br />
		</p>
		<p> {!! trans('core.abs_pls_flw_link_act') !!}  <a href="{{ URL::to('user/activation?code='.$code) }}"> {!! trans('core.abs_act_my_acnt_now') !!}</a></p>
		<p> {!! trans('core.abs_if_link_notwork_copy') !!} </p>
		<p> {{ URL::to('user/activation?code='.$code) }} </p> 
		<br /><br /><p>{!! trans('core.abs_thank_you') !!}</p><br /><br />
		
		{{ CNF_APPNAME }} 
	</body>
</html>
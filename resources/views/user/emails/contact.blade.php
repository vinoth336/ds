<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{!! trans('core.abs_hello_admin') !!}</h2>
		<p>{!! trans('core.abs_uhave_new_mail') !!} </p>
		<p>
			{!! trans('core.abs_con_email') !!}{{ $sender }} <br />
			{!! trans('core.abs_con_name') !!}{{ $name }}<br />
			{!! trans('core.abs_con_pass') !!}{{ $subject }}<br />
		</p>
		<p> {!! trans('core.abs_con_msg') !!}</p>
		<div>
			{{ $notes }}
		</div>
		
		<p> {!! trans('core.abs_thank_you') !!} </p><br /><br />
		
		{{ CNF_APPNAME }} 
	</body>
</html>
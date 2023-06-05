<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{!! trans('core.abs_pass_reset') !!}</h2>

		<div>
			{!! trans('core.abs_to_reset_pass') !!}{{ URL::to('user/reset', array($token)) }}.
		</div>
	</body>
</html>
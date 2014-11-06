<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
            Сообщение: {{ $name }} &lt;{{ $email }}&gt;
            <hr/>
			{{ Helper::nl2br($text) }}
            <hr/>
		</p>
	</div>
</body>
</html>
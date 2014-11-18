<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
            Отправитель: {{ $name }} &lt;{{ $email }}&gt;
        </p>
        <p>
			Текст сообщения: {{ Helper::nl2br($text) }}
		</p>
	</div>
</body>
</html>
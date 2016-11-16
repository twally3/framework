<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Recieved Files</title>
</head>
<body>
	<h1>Multiple Files</h1>
	@foreach ($files as $file)
		<img src="/files/{$file->getName()}" alt="" width="300" style="transform: rotate({$file->rotation}deg);">
	@endforeach
</body>
</html>
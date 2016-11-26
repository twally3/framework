<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Form</title>
</head>
<body>
	<h1>This is a form</h1>
	<form method="POST" action="{Route::path('middleware')}">
		
		<input type="text" name="text">
		<input type="submit" name="submit">
		{!csrf_field()}

	</form>
</body>
</html>
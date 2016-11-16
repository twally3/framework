<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
  <h1>Upload your file(s)!</h1>
  <form action="{Route::path('PostOneFile')}" Method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="submit" >
  </form>

  <form action="{Route::path('PostManyFiles')}" Method="post" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>
    <input type="submit" name="submit" >
  </form>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  @output('title')
  <link rel="stylesheet" href="../css/style.css" media="screen" title="no title">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="../js/app.js" charset="utf-8"></script>
</head>
<body>
	<header>
		<h1>@output('head')</h1>
	</header>

  @include('home.nav')

	<section>
		@output('content')
		@output('loop')
	</section>

</body>
</html>

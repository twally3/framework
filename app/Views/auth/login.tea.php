<!DOCTYPE html>
<html lang="en">
<head>
  <title>login Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/login.css" media="screen" title="no title">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="bg-contained">
    <img>
  </div>

  <div class="container">
    <div class="row vertical-offset-100">
      <div class="col-md-6 col-md-offset-3">
      	<div class="panel panel-default">

  			  <div class="panel-heading">
  			    <h3 class="panel-title">PLEASE SIGN IN</h3>
  			 	</div>

  			  <div class="panel-body">
            @if(!empty($errors))
              <div class="alert alert-danger" role="alert">{$errors[0]}</div>
            @endif
  			    <form accept-charset="UTF-8" role="form" method="post" action="{Route::path('login')}">
              <fieldset>
  			    	  <div class="form-group">
  			    		    <input class="form-control" placeholder="E-mail" name="email" type="text" value="{$oldEmail}">
  			    		</div>
  			    		<div class="form-group">
  			    			<input class="form-control" placeholder="Password" name="password" type="password" value="">
  			    		</div>
  			    		<div class="checkbox">
			    	    	<label>
			    	    		<input name="remember" type="checkbox" value="true"> Remember Me
			    	    	</label>
			    	    </div>
                {!csrf_field()}
  			    		<input class="btn btn-lg btn-block button" type="submit" value="Login">
  			    	</fieldset>
  			    </form>
            <div class="extra">
              <a href="#">Forgot your password?</a><br>
              <a href="{Route::path('register')}">Sign up</a>
            </div>
  			  </div>

  			</div>
  		</div>
  	</div>
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>login Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/login.css" media="screen" title="no title">
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
  			    <h3 class="panel-title">REGISTER YOUR ACCOUNT</h3>
  			 	</div>

          <!--
          - Username
          - First Name
          - Last Name
          - Email
          - Confirm Email
          - Password
          - Confirm Password
          -->

  			  <div class="panel-body">
            <!-- <div class="alert alert-success" role="alert">...</div> -->
  			    <form accept-charset="UTF-8" role="form" method="post" action="{Route::path('register')}">
              <fieldset>
                <div class="form-group">
  			    		    <input class="form-control" placeholder="Username" name="uname" type="text">
  			    		</div>
                <div class="form-group">
  			    		    <input class="form-control" placeholder="First Name" name="fname" type="text">
  			    		</div>
                <div class="form-group">
  			    		    <input class="form-control" placeholder="Last Name" name="lname" type="text">
  			    		</div>
                <div class="form-group">
  			    		    <input class="form-control" placeholder="E-mail" name="email" type="text">
  			    		</div>
  			    	  <div class="form-group">
  			    		    <input class="form-control" placeholder="Confirm E-mail" name="cemail" type="text">
  			    		</div>
  			    		<div class="form-group">
  			    			<input class="form-control" placeholder="Password" name="password" type="password" value="">
  			    		</div>
                <div class="form-group">
  			    			<input class="form-control" placeholder="Password" name="cpassword" type="password" value="">
  			    		</div>
  			    		<input class="btn btn-lg btn-block button" type="submit" value="Login">
                {!csrf_field()}
  			    	</fieldset>
  			    </form>
            <div class="extra">
              <a href="#">Forgot your password?</a><br>
              <a href="#">Sign in</a>
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

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Index Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/template.css" media="screen" title="no title">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
</head>
<body id="myPage">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#myPage">KwikBooks</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#aboutSection">ABOUT</a></li>
        <li><a href="#contactSection">CONTACT</a></li>
        <li><a href="#">HOW IT WORKS</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="jumbotron text-center">
  <h1>KwikBooks</h1>
  <p>The easiest way to handle your money</p>
  <form class="form-inline">
    <input type="email" name="email" class="form-control" size="50" placeholder="Email Address" required>
    <a role="button" href="/login" class="btn btn-danger">Log In</a>
  </form>
</div>

<!-- Container (About Section) -->
<div id="aboutSection" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>About Us</h2><br>
      <h4>We know you went into business to pursue your passion and serve your customers - not to learn accounting. This is why we believe in executing extraordinary product and service experiences that helps save you time and get paid faster.</h4><br>
      <p>KwikBooks is a simple to use accounting system. It allows you to manage payments, create and track invoices, keep track of clients, create standing orders and organise them all by customisable categories. It allows you to cut out all the complexity and focus on the things you want to do. This allows you to spend more time bettering your buisness and save money on accountants at the same time. </p>
      <br><a role="button" class="btn btn-default btn-lg" href="{Route::path('register')}" >Sign up now</a>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-signal logo"></span>
    </div>
  </div>
</div>

<div class="container-fluid bg-grey">
  <div class="row">
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-globe logo slideanim"></span>
    </div>
    <div class="col-sm-8">
      <h2>Our Values</h2><br>
      <h4><strong>MISSION:</strong> Our mission is to allow you to spend less time focusing on paperwork and more time on bettering your buisness. We aim to provide an easy and secure way to reliably manage your expenses from anywhere.</h4><br>
      <p><strong>VISION:</strong> We envision a world where the hastle of book keeping is a thing of the past. A world where the fear of outstanding payments and keeping track of budgets is no longer an issue. We genuinly believe that, you should spend less time managing expenses or paying accountants, and more time making your buisness better, and improving your clients experiences.</p>
    </div>
  </div>
</div>

<!-- Container (Services Section) -->
<div class="container-fluid text-center">
  <h2>SERVICES</h2>
  <h4>What we offer</h4>
  <br>
  <div class="row slideanim">
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-cloud logo-small"></span>
      <h4>DASHBOARD</h4>
      <p>See over everything at a glance...</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-gbp logo-small"></span>
      <h4>INVOICING</h4>
      <p>Never miss being paid again...</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-user logo-small"></span>
      <h4>CLIENTS</h4>
      <p>Quick access to all your clients...</p>
    </div>
  </div>
  <br><br>
  <div class="row slideanim">
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-time logo-small"></span>
      <h4>STANDING ORDERS</h4>
      <p>Manage automatic payments...</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-book logo-small"></span>
      <h4>EXPENCES</h4>
      <p>Manage all your transactions</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-list-alt logo-small"></span>
      <h4>CATEGORIES</h4>
      <p>Differenciate between payments</p>
    </div>
  </div>
</div>

<!-- Container (Portfolio Section) -->
<div class="container-fluid text-center bg-grey">
  <h2>Portfolio</h2><br>
  <h4>What we have created</h4>
  <div class="row text-center slideanim">
    <div class="col-sm-4">
      <div class="thumbnail">
        <img src="../img/image1.png" alt="easy" width="400" height="300">
        <p><strong>Extremely Easy To Use</strong></p>
        <p>So simple and intuitive, so you’ll spend less time on paperwork. To wow your clients with how professional you look.</p>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="thumbnail">
        <img src="../img/image2.png" alt="poweful" width="400" height="300">
        <p><strong>Powerful Features</strong></p>
        <p>Automate time consuming tasks like organizing expenses, tracking your time, and managing clients with just a few clicks.</p>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="thumbnail">
        <img src="../img/image3.png" alt="cloud" width="400" height="300">
        <p><strong>Organized in the Cloud</strong></p>
        <p>KwikBooks lives in the cloud meaning you can get quick and secure access easily, from any device, no matter where you are.</p>
      </div>
    </div>
  </div><br>

  <h2>What our users say</h2>
  <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <h4>"This product is the best. I am so happy with the result!"<br><span style="font-style:normal;">Christopher Taylor, CEO, TitleSolv</span></h4>
      </div>
      <div class="item">
        <h4>"One word... WOW!!"<br><span style="font-style:normal;">Zoe Taylor, PT, FitBird Personal Training</span></h4>
      </div>
      <div class="item">
        <h4>"Could I... BE any more happy with this product?"<br><span style="font-style:normal;">Max Taylor, Web Designer, Freelance</span></h4>
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

<!-- Container (Contact Section) -->
<div id="contactSection" class="container-fluid bg-grey">
  <h2 class="text-center">CONTACT</h2>
  <div class="row">
    <div class="col-sm-5">
      <p>Need support? Contact us here and we'll get back to you within 24 hours.</p>
      <p><span class="glyphicon glyphicon-map-marker"></span> Alton, Hants</p>
      <p><span class="glyphicon glyphicon-phone"></span> +44 7795 485478</p>
      <p><span class="glyphicon glyphicon-envelope"></span> max@cakerstream.com</p>
    </div>
    <div class="col-sm-7 slideanim">
      <div class="row">
        <div class="col-sm-6 form-group">
          <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
        </div>
        <div class="col-sm-6 form-group">
          <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
        </div>
      </div>
      <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5"></textarea><br>
      <div class="row">
        <div class="col-sm-12 form-group">
          <button class="btn btn-default pull-right" type="submit">Send</button>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="container-fluid text-center">
  <a href="#myPage" title="To Top">
    <span class="glyphicon glyphicon-chevron-up"></span>
  </a>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/template.js" charset="utf-8"></script>

</body>
</html>

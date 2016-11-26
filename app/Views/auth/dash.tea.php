<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sidebar Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/dash.css" media="screen" title="no title">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
</head>

<body>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">
                    KwikBooks
                </a>
            </li>
            <li>
              <div class="image" href="#">
                <div class="img-circular"></div>
                <div class="img-text">Max Taylor</div>
                <div class="logout"><a href="/logout">Log Out</a></div>
              </div>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-cloud"></span>
                DASHBOARD
              </a>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-gbp"></span>
                INVOICES
              </a>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-user"></span>
                CLIENTS
              </a>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-book"></span>
                TRANSACTIONS
              </a>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-list-alt"></span>
                CATEGORIES
              </a>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-time"></span>
                STANDING ORDERS
              </a>
            </li>
            <li>
              <a class="link" href="#">
                <span class="glyphicon glyphicon-wrench"></span>
                SETTINGS
              </a>
            </li>
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
          <div class="page-header">

            <!-- <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Menu</a> -->
            <button id="menu-toggle" class="btn btn-default header-btn" type="button">
              <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
            </button>
            <h2 class="inline">
              Dashboard
            </h2>
            <a href="#" class="btn btn-default header-btn btn-static right">
              Useless button
            </a>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <h4>This template has a responsive menu toggling system. The menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will appear/disappear. On small screens, the page content will be pushed off canvas.</h4>
              <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>.</p>
            </div>
          </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="../js/dash.js" charset="utf-8"></script>

</body>

</html>

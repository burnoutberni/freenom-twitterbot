<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
require_once('functions.inc.php');

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Freenom Twitter Alert</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/jumbotron.css" rel="stylesheet">
  </head>
  <body>

    <div class="container-narrow">
      <div class="header">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#signup">Sign Up</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <h3 class="text-muted">Freenom Twitter Alert</h3>
      </div>

      <div class="jumbotron">
        <h1>Freenom Twitter Alert</h1>
        <p class="lead">Share your food consumption at the Melons & Meatloaf stand at <a href="http://www.ohm2013.org">OHM2013</a> with all of your friends.</p>
        <p><a class="btn btn-large btn-success" href="#signup">Sign up now!</a></p>
      </div>

      <div class="row marketing" id="signup">
        <div class="col-lg-6">
          <h4>What is this?</h4>
          <p>We love melons and meatloafs. Thank god you can get both of this at <a href="http://www.ohm2013.org">OHM2013</a> for free at the Freenom stand. Therefore you receive a RFID card, which is used to save your consumption data on a personalized webpage. You can also access this data via a public API. We are using this data to give you the ability to share your consumption data live with all of your friends on Twitter. Just sign on and have a melon and/or a meatloaf.</p>
        </div>

        <div class="col-lg-6">
          <form action="register.php" method="post">
            <h4>Register now!</h4>
<?php
    if (isset($_GET['error'])) {
	echo '<div class="alert alert-danger">';
	if ($_GET['error'] === 'alreadyRegistered') echo 'Your domain is already registered!';
	if ($_GET['error'] === 'userAlreadyRegistered') echo 'Your Twitter account is already registered!';
	if ($_GET['error'] === 'domainnameNotInt') echo 'You have to enter a valid number.';
	echo '</div>';
    }
    if (isset($_GET['info'])) {
	echo '<div class="alert alert-success">';
	if ($_GET['info'] === 'registered') echo 'You are now registered!';
	if ($_GET['info'] === 'userRemoved') echo 'Your user is removed!';
	echo '</div>';
    }
?>
            <p>Please insert your Melons & Meatloafs number:<br>
            (You can find it on your Melons & Meatloafs RFID card, it's part of your personal web address.)</p>
            <div class="input-group">
              <span class="input-group-addon">FREENOM</span>
              <input type="number" class="form-control" name="domainname" required="required" max="9999" />
              <span class="input-group-addon">.ML</span>
            </div><br>
            <div>
              <input type="hidden" value="true" name="submit" />
              <input class="btn btn-primary" type="submit" value="Connect with Twitter" />
            </div>
          </form>
          <form action="delete.php" method="post">
            <h4>You don't want to use this service anymore?</h4>
            <h5>Just use this button, authenticate yourself, and it will stop.</h5>
            <input type="hidden" value="true" name="submit" />
            <input class="btn btn-danger" type="submit" value="Delete your data!" />
          </form>
        </div>
      </div>

      <div class="footer" id="contact">
        <p>Created by <a href="https://twitter.com/PeterTheOne">@PeterTheOne</a> and <a href="https://twitter.com/burnoutberni">@burnoutberni</a>. Everyone can find the source code at <a href="https://github.com/burnoutberni/freenom-twitterbot">Github</a>. It's licensed under the <a href="https://github.com/burnoutberni/freenom-twitterbot/blob/master/LICENSE">GNU GPLv2</a>.</p>
      </div>

    </div> <!-- /container -->

    <!-- JavaScript plugins (requires jQuery) -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

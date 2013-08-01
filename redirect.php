<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

/* Start session and load library. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
require_once('functions.inc.php');

if (!isset($_POST['domainname'])) {
  header('Location: ./index.php');
  exit;
}

if (!is_int(intval($_POST['domainname'])) || $_POST['domainname'] > 9999 || $_POST['domainname'] < 0) {
    header('Location: index.php?error=domainnameNotInt');
    exit;
}
if (isDomainnameRegistered($_POST['domainname'])) {
    header('Location: index.php?error=alreadyRegistered');
    exit;
}
$_SESSION['domainname'] = $_POST['domainname'];

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
}

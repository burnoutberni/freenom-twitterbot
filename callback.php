<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
require_once('functions.inc.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./logoutonly.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

$user_data = $connection->get('account/verify_credentials');
$screen_name = $user_data->screen_name;

if (isset($_SESSION['delete']) && $_SESSION['delete'] === 'true') {
    unset($_SESSION['delete']);
    
    /* Save the access tokens. Normally these would be saved in a database for future use. */
    $_SESSION['access_token'] = $access_token;
    
    header('Location: delete.php');
    exit;
}

if (isUserRegistered($access_token)) {
    header('Location: index.php?error=userAlreadyRegistered');
    exit;
}

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

addUser($_SESSION['domainname'], $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret'], $screen_name);

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);


session_destroy();

header('Location: ./index.php?info=registered');

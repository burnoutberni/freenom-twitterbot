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

if (!ctype_digit($_POST['domainname']) || $_POST['domainname'] > 9999 || $_POST['domainname'] < 0) {
    header('Location: index.php?error=domainnameNotInt');
    exit;
}
if (isDomainnameRegistered($_POST['domainname'])) {
    header('Location: index.php?error=alreadyRegistered');
    exit;
}
$_SESSION['domainname'] = $_POST['domainname'];

header('Location: redirect.php');
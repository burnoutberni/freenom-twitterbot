<?php

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

if (isLoggedIntoTwitter()) {
    //todo: save to file?
} else {
    header('Location: ./redirect.php');
}
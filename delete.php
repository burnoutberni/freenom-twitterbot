<?php

session_start();

require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
include_once('functions.inc.php');

if (isLoggedInTwitter()) {
    removeUser();
    
    session_destroy();
    
    header('Location: index.php?info=userRemoved');
} else {
    $_SESSION['delete'] = 'true';
    header('Location: redirect.php');
}
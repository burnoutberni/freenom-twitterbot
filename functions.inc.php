<?php

function isLoggedInTwitter() {
    return !empty($_SESSION['access_token']) && 
	!empty($_SESSION['access_token']['oauth_token']) && 
	!empty($_SESSION['access_token']['oauth_token_secret']);
}

function isDomainnameRegistered($domainname) {
    $usersFile = file_get_contents('users.json');
    if ($usersFile === false) {
	echo 'cannot read file.';
	exit;
    }
    $users = json_decode($usersFile);
    foreach ($users as $user) {
	if ($user->domainname === $domainname) {
	    return true;
	}
    }
    return false;
}

function isUserRegistered($access_token) {
    $usersFile = file_get_contents('users.json');
    if ($usersFile === false) {
	echo 'cannot read file.';
	exit;
    }
    $users = json_decode($usersFile);
    foreach ($users as $user) {
	if ($user->twitter_token === $access_token['oauth_token'] && 
		$user->twitter_token_secret === $access_token['oauth_token_secret']) {
	    return true;
	}
    }
    return false;
}
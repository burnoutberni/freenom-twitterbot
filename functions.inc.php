<?php

function isLoggedInTwitter() {
    return isset($_SESSION['access_token']) && 
	isset($_SESSION['access_token']['oauth_token']) && 
	isset($_SESSION['access_token']['oauth_token_secret']) && 
	!empty($_SESSION['access_token']) && 
	!empty($_SESSION['access_token']['oauth_token']) && 
	!empty($_SESSION['access_token']['oauth_token_secret']);
}

function isDomainnameRegistered($domainname) {
    $usersFile = file_get_contents(USERS_FILE);
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
    $usersFile = file_get_contents(USERS_FILE);
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

function removeUser() {
    $usersFile = file_get_contents(USERS_FILE);
    if ($usersFile === false) {
	echo 'cannot read file.';
	exit;
    }
    $users = json_decode($usersFile);
    
    $i = 0;
    foreach ($users as $user) {
	if ($user->twitter_token === $_SESSION['access_token']['oauth_token'] && 
		$user->twitter_token_secret === $_SESSION['access_token']['oauth_token_secret']) {
	    echo 'i: ' . $i;
	    array_splice($users, $i, 1);
	    echo '<pre>'; print_r($users); echo '</pre>';
	    break;
	}
	$i++;
    }
    
    $result = file_put_contents(USERS_FILE, json_encode($users));
    if ($result === false) {
	echo 'cannot write file.';
	exit;
    }
}

function addUser($domainname, $oauth_token, $oauth_token_secret, $screen_name) {
    $usersFile = file_get_contents(USERS_FILE);
    if ($usersFile === false) {
	echo 'cannot read file.';
	exit;
    }
    $users = json_decode($usersFile);
    $newUser = array(
	'domainname' => $domainname, 
	'twitter_token' => $oauth_token, 
	'twitter_token_secret' => $oauth_token_secret,
	'screen_name' => $screen_name
    );
    $users[] = $newUser;
    $result = file_put_contents(USERS_FILE, json_encode($users));
    if ($result === false) {
	echo 'cannot write file.';
	exit;
    }
}

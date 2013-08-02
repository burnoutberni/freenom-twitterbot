<?php

require_once('config.php');

if(file_exists('lastfetch.txt')) {
	$lastfetch = file_get_contents('lastfetch.txt');
} else {
	$lastfetch = "";
}

$users = json_decode(file_get_contents(USERS_FILE));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://melon.meatloaf.ml/api/all?after=".$lastfetch);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = json_decode(curl_exec($ch));

if (isset($result[0])) {
	foreach ($result as $row) {
		$item = null;
		foreach($users as $struct) {
		    if ($row->domainname == "FREENOM".$struct->domainname.".ML") {
			$temp['text'] = "#Ohmnomnomz, another ".$row->item." for me!!! http://".strtolower($row->domainname). " #ohm2013 #ppoe+";
			$temp['twitter_token'] = $struct->twitter_token;
			$temp['twitter_token_secret'] = $struct->twitter_token_secret;
			$tweets[] = $temp;
		        break;
		    }
		}
	}

	file_put_contents('lastfetch.txt', intval($result[0]->unix_ts) + 1);
}

curl_close($ch);

if(isset($tweets)) {
	// send tweets
	require_once('twitteroauth/twitteroauth.php');
	require_once('config.php');

	foreach ($tweets as $tweet) {
	    /* Create a TwitterOauth object with consumer/user tokens. */
	    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $tweet['twitter_token'], $tweet['twitter_token_secret']);

	    /* Some example calls */
	    $connection->post('statuses/update', array('status' => $tweet['text']));
	}
}
?>

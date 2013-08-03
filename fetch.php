<?php

require_once('config.php');

if(file_exists(LASTFETCH_FILE)) {
	$lastfetch = file_get_contents(LASTFETCH_FILE);
} else {
	$lastfetch = "";
}

$users = json_decode(file_get_contents(USERS_FILE));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://melon.meatloaf.ml/api/all?after=".$lastfetch);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = json_decode(curl_exec($ch));

if ($result[0]->unix_ts != "") {
	foreach ($result as $row) {
		$item = null;
		foreach($users as $struct) {
		    if ($row->domainname == "FREENOM".$struct->domainname.".ML") {
			$newrand = rand(0, 7);
			if ($newrand == 0) $temp['text'] = "#Ohmnomnomz, another ".$row->item." for me!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohm2013 #ppoe+";
			if ($newrand == 1) $temp['text'] = "Another ".$row->item." for me, #ohmnomnomz!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohm2013 #ppoe+";
			if ($newrand == 2) $temp['text'] = "I love ".$row->item."s!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohmnomnomz #ohm2013 #ppoe+";
			if ($newrand == 3) $temp['text'] = "#Ohmnomnomz, all ".$row->item."s are mine!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohm2013 #ppoe+";
			if ($newrand == 4) $temp['text'] = "All ".$row->item."s are mine!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohmnomnomz #ohm2013 #ppoe+";
			if ($newrand == 5) $temp['text'] = $row->item."s are magic!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohmnomnomz #ohm2013 #ppoe+";
			if ($newrand == 6) $temp['text'] = $row->item."s are teh best!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohmnomnomz #ohm2013 #ppoe+";
			if ($newrand == 7) $temp['text'] = $row->item."s are delicious!!! http://".strtolower($row->domainname). " http://ohmnomnomz.ml #ohmnomnomz #ohm2013 #ppoe+";
			$temp['twitter_token'] = $struct->twitter_token;
			$temp['twitter_token_secret'] = $struct->twitter_token_secret;
			$temp['screen_name'] = $struct->screen_name;
			$tweets[] = $temp;
		        break;
		    }
		}
	}
	file_put_contents(LASTFETCH_FILE, intval($result[0]->unix_ts));
}

curl_close($ch);
if(isset($tweets)) {
	foreach($tweets as $tweet) {
		$logline = date('c')." by ".$tweet['screen_name'].": ".$tweet['text']."\n";
		if(isset($log)) {
			$log = $log.$logline;
		} else {
			$log = $logline;
		}
	}
	file_put_contents(LOG_FILE, $log);
}

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

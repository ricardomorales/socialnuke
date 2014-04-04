<?php

/* Load lib */
require_once('twitteroauth.php');
require_once('config.php');
require_once('databaseClasses.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,
	$_REQUEST['oauth_token'], $_REQUEST['oauth_token_secret']);
$target = $_REQUEST['target'];
$destroyUser = $connection->post('friendships/destroy', array('screen_name' => $target));

echo "You have just nuked " . $target . ". Move on with your life. <br /> Just fill in the form above to nuke again.";

?>
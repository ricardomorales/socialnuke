<?php

/* Load library. */
require_once('twitteroauth.php');
require_once('config.php');
require_once('databaseClasses.php');

// Build TwitterOAuth object with client credentials.
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
// Receive temporary credentials from Twitter.
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

// If last connection failed, don't display authorization link.
switch ($connection->http_code) {
  case 200:

	// Store temporary credentials into the database using databaseClasses.php
	$registerUser = new User();
	$registerUser->initialize($_REQUEST['email'], $request_token['oauth_token'], $request_token['oauth_token_secret']);
	$registerUser->register();

	// Build and return our authorize URL with received data
  	$token = $request_token['oauth_token'];
    $url = $connection->getAuthorizeURL($token);
    echo $url;

    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
}

?>
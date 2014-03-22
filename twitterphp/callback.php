<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials.
 */

/* Load lib */
require_once('twitteroauth.php');
require_once('config.php');
require_once('databaseClasses.php');

$checkUser = new User();
$checkUser->authorizeRequest($_REQUEST['email'], $_REQUEST['password']);
$keys = $checkUser->getData();

$oauth_token = $keys[0]['oauth_token'];
$oauth_token_secret = $keys[0]['oauth_token_secret'];

// Create TwitteroAuth object with app key/secret and token key/secret from default phase 
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);

// Request access tokens from twitter 
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier'], $oauth_token, $oauth_token_secret);

/*============ Haven't gotten to this code yet
If the oauth_token is old redirect to the connect page. 
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  phpRequest('clearsessions', false);
}*/

// Save the access token and long-lasting credentials in a database for future use.
$checkUser->logAccessKey($_REQUEST['email'], $_REQUEST['password'], $access_token['oauth_token'], $access_token['oauth_token_secret']);


// If HTTP response is 200 continue otherwise send to connect page to retry
if (200 == $connection->http_code) {
  // The user has been verified and the access tokens can be saved for future use
  echo "Success.";
} else {
  // Save HTTP status for error dialog on connect page.
  // echo "<script type='text/javascript'>phpRequest('clearsessions', false);";
  echo '/index.html';
}

?>
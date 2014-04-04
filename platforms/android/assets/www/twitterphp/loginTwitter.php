<?php

/* Load library. */
require_once('twitteroauth.php');
require_once('config.php');
require_once('databaseClasses.php');

	// Store temporary credentials into the database using databaseClasses.php
	$loginUser = new User();
	$loginUser->initialize($_REQUEST['email'], $_REQUEST['password'], "", "");
  $loginUser->login();

  $database_info = $loginUser->getData();

  // If the username already exists, echo out a 'false'
  if ( !$loginUser->login() ) {
    echo "false";
    return;
  }

	// Build and return our authorize URL with received data
  echo "dashboard.html" . "?oauth_token=" . $database_info[0]['oauth_token'] . "?loggedin=true";

?>
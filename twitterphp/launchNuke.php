<?php

/* Load lib */
require_once('twitteroauth.php');
require_once('config.php');
require_once('databaseClasses.php');

$credentials = new User();
$credentials->getCredentials($_REQUEST['email'], $_REQUEST['password']);
$data = $credentials->getData();
echo json_encode($data);

?>
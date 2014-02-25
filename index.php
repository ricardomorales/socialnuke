<!DOCTYPE html>

<head>
	<title>The Social Nuke - Twitter Edition</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>

	<div id="block">
		<?php

			require_once('twitteroauth/twitteroauth.php');
			require_once('config.php');
			
			session_start();
			// echo ($_SESSION['access_token']['oauth_token']);
			// echo ($_SESSION['access_token']['oauth_token_secret']);

			$oauth_token = $_SESSION['access_token']['oauth_token'];
			$oauth_token_secret = $_SESSION['access_token']['oauth_token_secret'];

			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token,
$oauth_token_secret);

			$account = $connection->post('friendships/destroy', array('screen_name' => 'jonmorales22'));

			var_dump($account);

			foreach($account as $key) {
				echo $key . "<br />";
			}

//			echo gettype($account);
			// $status = $connection->post('statuses/update', array('status' => 'Text of status here', 'in_reply_to_status_id' => 123456));
			// $status = $connection->delete('statuses/destroy/12345');
		?>
	</div>

</body>

</html>
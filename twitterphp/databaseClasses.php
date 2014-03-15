<?php

class User {
	private $email;
	private $oauth_token;
	private $oauth_token_secret;
	private $data;

	public function initialize($email, $oauth_token, $oauth_token_secret) {
		$this->email = $email;
		$this->oauth_token = $oauth_token;
		$this->oauth_token_secret = $oauth_token_secret;
	}

	public function connect() {
		$con = new mysqli("localhost","root","","socialnuke");
		// Check connection
		if ($con->connect_errno)
		{
		  echo "Failed to connect to MySQL: " . $con->connect_error();
		}
		return $con;
	}

	public function register() {
		$con = $this->connect();
		$con->query("INSERT INTO users (email, oauth_token, oauth_token_secret) VALUES
				('" . $this->email . "', '" . $this->oauth_token . "', '" . $this->oauth_token_secret . "')");
		$con->close();
	}

	public function logAccessKey($userEmail, $oauth_token, $oauth_token_secret) {
		$con = $this->connect();
		$con->query("UPDATE users SET oauth_token = '" . $oauth_token . "', oauth_token_secret = '" . $oauth_token_secret . "' WHERE email = '" . $userEmail . "'");
		$con->close();
	}

	public function getCredentials($userEmail) {
		$info = array();
		$con = $this->connect();
		$result = $con->query("SELECT * FROM users WHERE email='" . $userEmail . "'");
		if($result->num_rows >= 1) {
			while($row = $result->fetch_assoc()) {
    			array_push($info, $row);
    		}
    		$this->data = $info;
    	}
    	else {
    		$this->data = false;
    	}
		$con->close();
	}

	public function authorizeRequest($userEmail) {
		$keys = array();
		$con = $this->connect();
		$result = $con->query("SELECT * FROM users WHERE email='" . $userEmail . "'");
		if($result->num_rows >= 1) {
			while($row = $result->fetch_assoc()) {
    			array_push($keys, $row);
    		}
    		$data = array();
    		array_push($data, $keys[0]['oauth_token']);
    		array_push($data, $keys[0]['oauth_token_secret']);
    		$this->data = $data;
		}
		else {
			$this->data = false;
		}
		$con->close();
	}

	public function getData() {
		return $this->data;
	}

	/* Incomplete method
	public function login() {
		$con = $this->connect();
		$email = $this->email;
		$password = $this->password;
		$result = $con->query("SELECT email, password FROM Users WHERE email='" . $email . "' and password='" . $password . "'");
		if($result->num_rows >= 1) {
			header("Location: dashboard.php");
		}
		else {
			header("Location: index.php");
		}
		$result->close();
		$con->close();
	}
	*/
}

?>
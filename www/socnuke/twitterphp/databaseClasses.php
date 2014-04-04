<?php

class User {
	private $email;
	private $password;
	private $oauth_token;
	private $oauth_token_secret;
	private $data;

	public function initialize($email, $password, $oauth_token, $oauth_token_secret) {
		$this->email = $email;
		$this->password = $password;
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
		$result = $con->query("SELECT * FROM users WHERE email='" . $this->email . "'");
		if($result->num_rows >= 1) {
			return false;
		}
		else {
			$con->query("INSERT INTO users (email, password, oauth_token, oauth_token_secret) VALUES
					('" . $this->email . "', '" . $this->password . "', '" . $this->oauth_token . "', '" . $this->oauth_token_secret . "')");
			$con->close();
			return $this->oauth_token;
		}
		$result->close();
	}

	public function login() {
		$keys = array();
		$con = $this->connect();
		$result = $con->query("SELECT oauth_token FROM users WHERE email='" . $this->email . "' AND password='" . $this->password . "'");
		if($result->num_rows >= 1) {
			while($row = $result->fetch_assoc()) {
    			array_push($keys, $row);
    		}
    		$this->data = $keys;
    		return true;
		}
		else {
			return false;
		}
		$result->close();
		$con->close();
	}

	public function logAccessKey($userEmail, $userPassword, $oauth_token, $oauth_token_secret) {
		$con = $this->connect();
		$con->query("UPDATE users SET oauth_token = '" . $oauth_token . "', oauth_token_secret = '" . $oauth_token_secret . "' WHERE email = '" . $userEmail . "' AND password = '" . $userPassword . "'");
		$con->close();
	}

	public function getCredentials($userEmail, $userPassword) {
		$info = array();
		$con = $this->connect();
		$result = $con->query("SELECT * FROM users WHERE email='" . $userEmail . "' AND password='" . $userPassword . "'");
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

	public function authorizeRequest($userEmail, $userPassword) {
		$keys = array();
		$con = $this->connect();
		$result = $con->query("SELECT * FROM users WHERE email='" . $userEmail . "' AND password='" . $userPassword . "'");
		if($result->num_rows >= 1) {
			while($row = $result->fetch_assoc()) {
    			array_push($keys, $row);
    		}
    		/* Not in use for now
    		   $data = array();
    		   array_push($data, $keys[0]['oauth_token']);
    		   array_push($data, $keys[0]['oauth_token_secret']);
    		*/
    		$this->data = $keys;
		}
		else {
			$this->data = false;
		}
		$con->close();
	}

	public function getData() {
		return $this->data;
	}

	public function getPassword() {
		return $this->password;
	}
	
}

?>
<?php

class googleAuth extends modules {

	function __construct() {
		User::addToLogin(array($this, "auth"));
	}

	public static $version = "1.0";

	function auth($user, $pass, $row) {
		HTTP::set_cookie(COOK_USER, "", true);
		HTTP::set_cookie(COOK_PASS, "", true);
		HTTP::set_cookie(COOK_ADMIN_USER, "", true);
		HTTP::set_cookie(COOK_ADMIN_PASS, "", true);
		HTTP::set_cookie("userForAuth", $user);
		HTTP::set_cookie("passForAuth", $pass);
		HTTP::set_cookie("ref", rawurlencode($_POST['ref']));
		if(!isset($row['ga_code'])) {
			$ga = $this->loader("GoogleAuthenticator");
			User::update(array("username" => $user), array("ga_code" => $ga->generateSecret()), 0);
			//var_dump($row);die();
		}
		$_POST['ref'] = "./?pages=code";
		//var_dump(func_get_args(), $_POST);die();
	}

}
<?php

class Code extends Core {

	function __construct() {
		if(Arr::get($_COOKIE, "userForAuth", false)===false) {
			location(Arr::get($_COOKIE, "ref", "{C_default_http_host}{D_ADMINCP_DIRECTORY}/"));
		}
		$ga = modules::loader("GoogleAuthenticator");
		$user = User::getInfo(Arr::get($_COOKIE, "userForAuth"));
		if(isset($_GET['path'])) {
			header("Content-Type: image/jpg");
			echo file_get_contents($ga->getUrl($user['username'], config::Select("default_http_hostname"), $user['ga_code']));
			die();
		}
		if(sizeof($_POST)>0) {
			$code = $ga->getCode($user['ga_code']);
			$resp = array('accessGranted' => false, 'errors' => '');
			if($code != Arr::get($_POST, 'passwd', "")) {
				$resp['errors'] = 'You have entered wrong code or code yet not set in system, please try again.';
			} else {
				HTTP::set_cookie("userForAuth", "", true);
				HTTP::set_cookie("passForAuth", "", true);
				HTTP::set_cookie("ref", "", true);
				$resp['accessGranted'] = true;
				$resp['ref'] = Arr::get($_POST, 'ref', "./?pages=main");
			}
			Debug::activShow(false);
			templates::$gzip=false;
			if(ajax_check()=="ajax") {
				HTTP::echos(json_encode($resp));
			} else {
				location(Arr::get($_COOKIE, "ref", "{C_default_http_host}{D_ADMINCP_DIRECTORY}/"));
			}
			die();
		}
		templates::assign_var("qrcode", '');
		if(!isset($user['ga_showed'])) {
			User::update(array("username" => $user['username']), array("ga_showed" => true), 0);
			templates::assign_var("qrcode", '<img src="./?pages=code&path" style="display:table;margin:0px auto;"><br>');
		}
		$echos = templates::view(templates::completed_assign_vars("code", null));
		$echos = str_replace("{js_list}", "", $echos);
		$echos = str_replace("{css_list}", "", $echos);
		HTTP::echos($echos);
	}

}
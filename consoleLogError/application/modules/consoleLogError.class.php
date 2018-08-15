<?php
/*
Name: Отслеживание ошибок скриптов с сайта
Version: 1.0
Author: killserver
 */
if(!defined("IS_CORE")) {
die();
}

class consoleLogError extends modules {

	public static $version = "1.0";

	function __construct() {
		addEvent("printed_admin", array($this, "print_admin"));
		addEvent("templates::display", array($this, "print_site"));
		addEvent("loadUserLevels", array($this, "userlevels"));
		Route::set("consoleDebug", "consoleDebug.php")->defaults(array(
			"class" => __CLASS__,
			"method" => "handler",
		));
		userlevel::setAll("errorjs", "no");
	}

	function userlevels($levels) {
		$levels[LEVEL_CREATOR]['access_errorjs'] = "yes";
		return $levels;
	}

	function handler() {
		$data = file_get_contents("php://input");
		$data = json_decode($data, true);
		$data['time'] = time();
		$data['ip'] = HTTP::getip();
		$data['file'] = HTTP::getServer("HTTP_REFERER");

		$file = array();
		if(file_exists(PATH_CACHE_SYSTEM."errorJS.txt")) {
			$file = file_get_contents(PATH_CACHE_SYSTEM."errorJS.txt");
			$file = json_decode($file, true);
		}
		$isExists = false;
		for($i=0;$i<sizeof($file);$i++) {
			if(isset($file[$i]['trace']) && $file[$i]['trace']==$data['trace']) {
				$isExists = true;
			}
		}

		if($isExists===false) {
			$file[] = $data;
		}
		file_put_contents(PATH_CACHE_SYSTEM."errorJS.txt", json_encode($file));
	}

	function print_site($tpl) {
		$tmp = "<script type=\"text/javascript\" charset=\"utf-8\">".str_Replace("{home_url}", config::Select("default_http_local"), file_get_contents(ROOT_PATH."js".DS."consoleDebug.min.js"))."</script>";
		$tpl = str_replace("</head>", $tmp."</head>", $tpl);
		return $tpl;
	}

	function print_admin($tpl) {
		$tmp = "<script type=\"text/javascript\" charset=\"utf-8\">".str_Replace("{home_url}", config::Select("default_http_local"), file_get_contents(ROOT_PATH."js".DS."consoleDebug.min.js"))."</script>";
		$tpl = str_replace("</head>", $tmp."</head>", $tpl);
		return $tpl;
	}

}
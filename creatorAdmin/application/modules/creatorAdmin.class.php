<?php
/*
Name: Быстрое создание разделов
Version: 1.8
Author: killserver
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class creatorAdmin extends modules {

	function __construct() {
		addEvent("loadUserLevels", array($this, "addLevel"));
	}

	function addLevel($levels) {
		$levels[LEVEL_CREATOR]['access_creator'] = "yes";
		$levels[LEVEL_CUSTOMER]['access_creator'] = "yes";
		$levels[LEVEL_ADMIN]['access_creator'] = "yes";
		return $levels;
	}

	public static $version = "1.8";

}

?>
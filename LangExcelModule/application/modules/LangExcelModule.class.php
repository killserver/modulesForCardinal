<?php
/*
Name: Работа с языками через Excel
Version: 1.2
Author: killserver
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class LangExcelModule extends modules {

	function __construct() {
		addEvent("loadUserLevels", array($this, "langExcelLevel"));
	}

	function langExcelLevel($levels) {
		$levels[LEVEL_CREATOR]['access_langexcel'] = "yes";
		return $levels;
	}

	public static $version = "1.2";

}

?>
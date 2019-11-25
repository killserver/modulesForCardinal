<?php
/*
Name: Быстрое создание разделов
Version: 2.0
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

	public static $version = "2.0";

}

if(defined("IS_ADMINCP")) {
	if(!function_exists("createInstaller")) {
		function createInstaller($name) {
			if(!class_exists("Core")) {
				throw new Exception("Please use this function inside event 'admin_ready'", 1);
				die();
			}
			if(!class_exists("Creator", false)) {
				require(ADMIN_VIEWER."Creator".DS."Creator.class.".ROOT_EX);
			}
			return Creator::Install($name);
		}
	}
	if(!function_exists("checkInstaller")) {
		function checkInstaller($name) {
			if(!class_exists("Core")) {
				throw new Exception("Please use this function inside event 'admin_ready'", 1);
				die();
			}
			if(!class_exists("Creator", false)) {
				require(ADMIN_VIEWER."Creator".DS."Creator.class.".ROOT_EX);
			}
			return Creator::Check($name);
		}
	}
	if(!function_exists("removeInstaller")) {
		function removeInstaller($name) {
			if(!class_exists("Core")) {
				throw new Exception("Please use this function inside event 'admin_ready'", 1);
				die();
			}
			if(!class_exists("Creator", false)) {
				require(ADMIN_VIEWER."Creator".DS."Creator.class.".ROOT_EX);
			}
			return Creator::Remove($name);
		}
	}
	if(!function_exists("checkInstalledInstaller")) {
		function checkInstalledInstaller($name) {
			if(!class_exists("Core")) {
				throw new Exception("Please use this function inside event 'admin_ready'", 1);
				die();
			}
			if(!class_exists("Creator", false)) {
				require(ADMIN_VIEWER."Creator".DS."Creator.class.".ROOT_EX);
			}
			return Creator::Installed($name);
		}
	}
}
?>
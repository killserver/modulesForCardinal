<?php
/*
Name: Установка модулей, плагинов, тем и разделов для движка CE
Version: 1.6
Author: killserver
OnlyUse: true
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class installerAdmin extends modules {

	function __construct() {}

	public static $version = "1.6";

}

?>
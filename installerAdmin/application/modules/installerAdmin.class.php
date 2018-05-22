<?php
/*
Name: Установка модулей, плагинов, тем и разделов для движка CE
Version: 1.4
Author: killserver
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class installerAdmin extends modules {

	function __construct() {}

	public static $version = "1.4";

}

?>
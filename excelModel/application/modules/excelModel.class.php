<?php
/*
Name: Выгрузка данных на основании таблиц бд/моделей в Эксель
Version: 1.0.0
Author: max
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class excelModel extends modules {

	public static $version = "1.0.0";
	
	function __construct() {
		userlevel::set(LEVEL_CREATOR, "excelModelAdmin", "yes");
		userlevel::set(LEVEL_CUSTOMER, "excelModelAdmin", "yes");
	}
	
}

?>
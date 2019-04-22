<?php
/*
Name: Редактор меню для Cardinal Engine
Version: 1.10
Author: killserver
OnlyUse: true
 */
class menu extends modules {

	public static $version = "1.9";

	function __construct() {
		if(defined("IS_ADMIN")) {
			addEvent("admin_core_prints_info", array($this, "show"));
			addEvent("loadUserLevels", array($this, "addLevel"));
		}
	}

	function addLevel($levels) {
		$levels[LEVEL_CREATOR]['access_creator'] = "yes";
		$levels[LEVEL_CUSTOMER]['access_creator'] = "yes";
		$levels[LEVEL_ADMIN]['access_creator'] = "yes";
		return $levels;
	}

	function show($ret) {
		$mess = "";
		if(!db::connected()) {
			$mess = "Для корректной работы меню - установите подключение к базе данных";
		} else if($this->actived()===false) {
			$mess = "Для корректной работы меню - включите данную модификацию";
		} else if(!db::getTable("menu")) {
			$mess = "Что-то пошло не так. Попробуйте переустановить модификацию \"Меню\". Если это сообщение останется - сообщите разработчику в телеграм <a href=\"https://t.me/killserver\" target=\"_blank\">killserver</a>";
		}
		if($mess==="") {
			return $ret;
		}
		$ret[$mess] = array("echo" => $mess, "time" => time()+1, "block" => true);
		return $ret;
	}


	public static function installation() {
		self::create_table("menu", "`mId` int not null auto_increment,".
									"`mUId` int(11) not null,".
									"`mName` varchar(255) not null,".
									"`mMenu` varchar(255) not null,".
									"`mLevel` int(11) not null,".
									"`mIcon` varchar(255) not null,".
									"`mOpened` varchar(255) not null,".
									"`mClass` varchar(255) not null,".
									"`mContent` varchar(255) not null,".
									"`mPage` varchar(255) not null,".
									"`mParentId` int(11) not null,".
									"primary key `id`(`mId`)");
	}

	public static function updater($version) {
		self::add_fields("menu", array("mUId" => "int(11) not null"));
		self::add_fields("menu", array("mName" => "varchar(255) not null"));
	}

}
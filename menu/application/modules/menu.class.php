<?php

class menu extends modules {

	public static $version = "1.5";

	function __construct() {
		if(defined("IS_ADMIN")) {
			if($this->actived()===false || db::connected() || db::getTable("menu")) {
				addEvent("admin_core_prints_info", array($this, "show"), $this->actived(), db::connected(), db::getTable("menu"));
			}
		}
	}

	function show($data, $ret) {
		$mess = "";
		if(db::connected() || db::getTable("menu")) {
			$mess = "Для корректной работы меню - установите подключение к базе данных";
		} else if($this->actived()===false) {
			$mess = "Для корректной работы меню - включите данную модификацию";
		}
		if($mess==="") {
			return $ret;
		}
		$ret[$mess] = array("echo" => $mess, "time" => time()+365*24*60*60, "block" => true);
		return $ret;
	}


	public static function installation() {
		self::create_table("menu", "`mId` int not null auto_increment,".
									"`mUId` int(11) not null,".
									"`mName` int(11) not null,".
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
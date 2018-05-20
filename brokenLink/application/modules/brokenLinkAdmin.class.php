<?php
/*
Name: Модуль поиска битых(не рабочих) ссылок на сайте
*/

class brokenLinkAdmin extends modules {
	
	function __construct() {}

	public static function installation() {
		self::create_table("brokenLink", " `cId` int not null auto_increment,".
									"`linkNow` varchar(255) not null,".
									"`htmlOriginal` longtext not null,".
									"`linkOriginal` varchar(255) not null,".
									"`lastCheck` int(11) not null,".
									"`statusCode` int(11) not null,".
									"`status` varchar(255) not null,".
									"`warning` enum('yes','no') not null default 'no',".
									"`broken` enum('yes','no') not null default 'no',".
									"`timeResp` float(11) not null,".
									"primary key `id`(`cId`)");
	}

	public static $version = "1.0";

	public static function updater() {}
	
}
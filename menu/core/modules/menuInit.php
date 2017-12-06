<?php

class menuInit extends modules {

	function __construct() {}


	public static function installation() {
		self::create_table("menu", "`mId` int not null auto_increment,".
									"`mMenu` int(11) not null,".
									"`mLevel` int(11) not null,".
									"`mIcon` varchar(255) not null,".
									"`mOpened` varchar(255) not null,".
									"`mClass` varchar(255) not null,".
									"`mContent` varchar(255) not null,".
									"`mPage` varchar(255) not null,".
									"`mParentId` int(11) not null,".
									"primary key `id`(`mId`)");
	}

	public static $version = "1.0.1";

}
<?php

class stats extends modules {

	function __construct() {
		$this->saveInfo();
	}

	public static function installation() {
		self::create_table("visitors", " `vId` int(11) NOT NULL AUTO_INCREMENT,".
										"`vIp` varchar(255) not null,".
										"`vCountry` varchar(255) not null,".
										"`vRegion` varchar(255) not null,".
										"`vCity` varchar(255) not null,".
										"`vLongitude` varchar(255) not null,".
										"`vLatitude` varchar(255) not null,".
										"`vAnswer` longtext not null,".
										"`vFirstEnter` int(11) not null,".
										"`vLastEnter` int(11) not null,".
										"`vEnters` int(11) not null,".
										" PRIMARY KEY `id` (`vId`)");
		self::create_table("visitorsHits", " `vId` int(11) NOT NULL AUTO_INCREMENT,".
											"`vIp` varchar(255) not null,".
											"`vUrl` varchar(255) not null,".
											"`vStatus` varchar(255) not null,".
											"`vDevice` varchar(255) not null,".
											"`vUseragent` varchar(255) not null,".
											"`vUpdatedAt` int(11) not null,".
											"`vCreatedAt`int(11) not null,".
											"`vCounter` int(11) not null,".
											" PRIMARY KEY `id` (`vId`)");
		self::create_table("visitorsReferers", " `vId` int(11) NOT NULL AUTO_INCREMENT,".
											"`vIp` varchar(255) not null,".
											"`vUrl` varchar(255) not null,".
											" PRIMARY KEY `id` (`vId`)");
	}

	public static $version = "1.2";

	function saveInfo() {
		$db = $this->init_db();
		if(!$db->connected()) {
			return false;
		}
		$req = Arr::get($_SERVER, 'REQUEST_URI');
		$arr = explode('/', $req);
		$arr = end($arr);
		$ext = explode('.', $arr);
		$ip = HTTP::getip();
		if(strpos($req, ADMINCP_DIRECTORY)===false) {
			$SxGeo = $this->loader("SxGeo", array("db_file" => "SxGeoCity.dat", "type" => 2 | 1));
			$city = $SxGeo->getCityFull("193.151.240.61");
			// Save hit
			// Create/update visitor information
			$visitor = $db->doquery("SELECT `vId` FROM {{visitors}} WHERE `vIp` LIKE ".$db->escape($ip));
			if($visitor) {
				// Update
				$db->doquery("UPDATE {{visitors}} SET `vLastEnter` = UNIX_TIMESTAMP(), `vEnters` = `vEnters` + 1 WHERE `vId` = ".$visitor['vId']);
			} else {
				// Create
				$db->doquery("INSERT INTO {{visitors}} SET `vIp` = ".$db->escape($ip).", `vCountry` = ".$db->escape($city['country']['name_ru']).", `vRegion` = ".$db->escape($city['region']['name_ru']).", `vCity` = ".$db->escape($city['city']['name_ru']).", `vLongitude` = ".$db->escape($city['city']['lon']).", `vLatitude` = ".$db->escape($city['city']['lat']).", `vAnswer` = ".$db->escape(($city ? json_encode($city) : NULL)).", `vFirstEnter` = UNIX_TIMESTAMP(), `vLastEnter` = UNIX_TIMESTAMP(), `vEnters` = 1");
			}

			$detect = $this->init_mobileDetect();
			$device = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Phone') : 'Computer');

			$rel = $db->doquery("SELECT `vId`, `vUrl`, `vUpdatedAt`, UNIX_TIMESTAMP() AS `nowView`, `vCounter` FROM {{visitorsHits}} WHERE `vIp` LIKE ".$db->escape($ip)." AND `vDevice` LIKE ".$db->escape($device)." AND `vUseragent` LIKE ".$db->escape(Arr::get($_SERVER, 'HTTP_USER_AGENT'))." ORDER BY `vId` DESC LIMIT 1");
			if(!isset($rel['vUrl']) || $rel['vUrl'] != Arr::get($_SERVER, 'REQUEST_URI') || $rel['vUpdatedAt'] - $rel['nowView'] > 300) {
				$db->doquery("INSERT INTO {{visitorsHits}} SET `vIp` = ".$db->escape($ip).", `vUrl` = ".$db->escape(Arr::get($_SERVER, 'REQUEST_URI')).", `vStatus` = ".$db->escape(function_exists('apache_response_headers') ? Arr::get(apache_response_headers(), 'Status', '200 OK') : '200 OK').", `vDevice` = ".$db->escape($device).", `vUseragent` = ".$db->escape(Arr::get($_SERVER, 'HTTP_USER_AGENT')).", `vUpdatedAt` = UNIX_TIMESTAMP(), `vCreatedAt` = UNIX_TIMESTAMP(), `vCounter` = 1");
			} else if(isset($rel['vUrl']) &&  $rel['vUrl'] == Arr::get($_SERVER, 'REQUEST_URI') && $rel['vUpdatedAt'] - $rel['nowView'] < 300) {
				$db->doquery("UPDATE {{visitorsHits}} SET `vCounter` = `vCounter` + 1 WHERE `vId` = ".$rel['vId']);
			}
		}
		// Save referer
		$referer = Arr::get($_SERVER, 'HTTP_REFERER');
		if($referer && strpos($referer, Arr::get($_SERVER, 'HTTP_HOST')) === false) {
			$db->doquery("INSERT INTO {{visitorsReferers}} SET `vIp` = ".$db->escape($ip).", `vUrl` = ".Arr::get($_SERVER, 'HTTP_REFERER'));
		}
	}

}
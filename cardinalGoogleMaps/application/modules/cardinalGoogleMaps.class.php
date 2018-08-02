<?php

class cardinalGoogleMaps extends modules {
	
	function __construct() {
		addEventRef("settinguser_main", array($this, "settings"));
		$this->regCssJs("var addressMapForCardinal = \"{C_cardinalGoogleMaps}\";", "js", false);
		$this->regCssJs("https://maps.googleapis.com/maps/api/js?key=AIzaSyChZ1lL58ijesJoIPgkd-KsovKezdMa8N0&extension=.js", "js");
		$this->regCssJs("{C_default_http_local}js/cardinalGoogleMaps.min.js", "js");
	}

	public static $version = "1.4";

	public static function installation() {
		config::Update("cardinalGoogleMaps", " ");
	}

	function settings(&$settings) {
		$settings .= "{include templates=\"cardinalGoogleMaps.tpl,SettingUser\"}";
	}
	
}
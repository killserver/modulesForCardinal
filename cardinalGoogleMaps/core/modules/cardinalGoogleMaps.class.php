<?php

class cardinalGoogleMaps extends modules {
	
	function __construct() {
		$this->regCssJs("var addressMapForCardinal = \"{C_cardinalGoogleMaps}\";", "js", false);
		$this->regCssJs("https://maps.googleapis.com/maps/api/js?key=AIzaSyChZ1lL58ijesJoIPgkd-KsovKezdMa8N0&extension=.js", "js");
		$this->regCssJs("{C_default_http_local}js/cardinalGoogleMaps.min.js", "js");
	}

	public static $version = "1.0";

	public static function installation() {
		config::Update("GoogleMaps", " ");
	}
	
}
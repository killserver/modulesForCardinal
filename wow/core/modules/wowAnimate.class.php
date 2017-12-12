<?php

class wowAnimate extends modules {
	
	function __construct() {
		$this->regCssJs("{C_default_http_local}js/wow.min.js", "js");
		$this->regCssJs("{C_default_http_local}js/animate.min.css", "css");
	}

	public static $version = "1.1";
	
}
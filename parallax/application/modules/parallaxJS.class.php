<?php

class parallaxJS extends modules {
	
	function __construct() {
		$this->regCssJs("{C_default_http_local}js/parallax.min.js", "js");
	}

	public static $version = "1.5";
	
}
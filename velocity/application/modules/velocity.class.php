<?php

class velocity extends modules {

	function __construct() {
		if(defined("DEBUG_ACTIVATED")) {
			$this->regCssJs(array("{C_default_http_local}js/velocity.js", "{C_default_http_local}js/velocity.ui.js"), "js");
		} else {
			$this->regCssJs(array("{C_default_http_local}js/velocity.min.js", "{C_default_http_local}js/velocity.ui.min.js"), "js");
		}
	}

	public static $version = "1.0";

}
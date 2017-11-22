<?php

class wowAnimate extends modules {
	
	function __construct() {
		$this->manifest_set(array("create_js", "full"), array("{C_default_http_local}js/wow.min.js"));
		$this->manifest_set(array("create_css", "full"), array("{C_default_http_local}js/animate.min.css"));
	}
	
}
<?php

class jqueryTmpl extends modules {

	function __construct() {
		if(defined("DEBUG_ACTIVATED")) {
			$this->regCssJs(array("{C_default_http_local}js/jquery.tmpl.js", "{C_default_http_local}js/jquery.tmplPlus.js"), "js");
		} else {
			$this->regCssJs(array("{C_default_http_local}js/jquery.tmpl.min.js", "{C_default_http_local}js/jquery.tmplPlus.min.js"), "js");
		}
	}

	public static $version = "1.0";

}
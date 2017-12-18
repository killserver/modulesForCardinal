<?php

class seoFinder extends modules {
	
	function __construct() {
		$this->regCssJs("{C_default_http_local}js/seoFinder/seodebugbar.min.css", "css");
		$this->regCssJs("{C_default_http_local}js/seoFinder/seodebugbar.min.js", "js");
		cardinalEvent::addListener("templates::display", array(&$this, "add"));
	}

	public static $version = "1.2";
	
	function add($send, $page) {
		$tmp = $this->init_templates();
		$tpl = $tmp->completed_assign_vars("seoFinder", "");
		$tpl = $tmp->view($tpl);
		$page = str_replace("</body>", $tpl."</body>", $page);
		return $page;
	}
	
}
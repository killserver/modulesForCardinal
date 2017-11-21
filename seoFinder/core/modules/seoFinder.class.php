<?php

class seoFinder extends modules {
	
	function __construct() {
		$this->manifest_set(array("create_css", "full"), array("{C_default_http_local}js/seoFinder/seodebugbar.min.css"));
		$this->manifest_set(array("create_js", "full"), array("{C_default_http_local}js/seoFinder/seodebugbar.min.js"));
		cardinalEvent::addListener("templates::display", array(&$this, "add"));
	}
	
	function add($send, $page) {
		$tmp = $this->init_templates();
		$tpl = $tmp->completed_assign_vars("seoFinder", "");
		$tpl = $tmp->view($tpl);
		$page = str_replace("</body>", $tpl."</body>", $page);
		return $page;
	}
	
}
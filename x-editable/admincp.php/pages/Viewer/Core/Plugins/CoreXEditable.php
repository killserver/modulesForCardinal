<?php

class CoreXEditable extends Core {
	
	function __construct() {
		$this->InsertList("x-editable1", config::Select("default_http_local").(defined("ADMINCP_DIRECTORY") ? ADMINCP_DIRECTORY : "admincp.php")."/assets/".config::Select('skins','admincp')."/css/x-editable/x-editable.min.css?4", "css");
		$this->InsertList("x-editable2", config::Select("default_http_local").(defined("ADMINCP_DIRECTORY") ? ADMINCP_DIRECTORY : "admincp.php")."/assets/".config::Select('skins','admincp')."/js/x-editable/x-editable.min.js?5", "js");
	}
	
}

?>
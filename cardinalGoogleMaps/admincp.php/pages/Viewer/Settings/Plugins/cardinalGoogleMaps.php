<?php

class Settings_cardinalGoogleMaps extends Settings {
	
	function __construct() {
		Settings::AddFunc(array("name" => "cardinalGoogleMaps", "func" => array(&$this, "PluginSave")));
		Settings::AddNav(array(array(
			"subname" => "cardinalGoogleMaps",
			"name" => "{L_\"Google Maps\"}",
			"options" => "{include templates=\"cardinalGoogleMaps.tpl,\"}",
		)));
	}
	
	public function PluginSave($args) {
		if(isset($args['cardinalGoogleMaps']) && config::Select("cardinalGoogleMaps")!=$args['cardinalGoogleMaps']) {
			$return = "\t'cardinalGoogleMaps' => '".$args['cardinalGoogleMaps']."',\n";
		} else {
			$return = "\t'cardinalGoogleMaps' => '".config::Select("cardinalGoogleMaps")."',\n";
		}
		return $return;
	}
	
}

?>
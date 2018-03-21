<?php

class Settings_cardinalTelegram extends Settings {
	
	function __construct() {
		Settings::AddFunc(array("name" => "cardinalTelegram", "func" => array(&$this, "PluginSave")));
		Settings::AddNav(array(array(
			"subname" => "cardinalTelegram",
			"name" => "{L_\"Telegram\"}",
			"options" => "{include templates=\"cardinalTelegram.tpl,\"}",
		)));
	}
	
	public function PluginSave($args) {
		if(isset($args['telegramToken']) && config::Select("telegramToken")!=$args['telegramToken']) {
			$return = "\t'telegramToken' => '".$args['telegramToken']."',\n";
		} else {
			$return = "\t'telegramToken' => '".config::Select("telegramToken")."',\n";
		}
		return $return;
	}
	
}

?>
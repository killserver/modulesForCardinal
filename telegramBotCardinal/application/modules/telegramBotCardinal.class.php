<?php
/*
Name: Push-уведомления в Telegram
Version: 1.0.4
Author: killserver
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class telegramBotCardinal extends modules {

	public static $version = "1.0.4";
	
	function __construct() {
		Route::Set('tgUnsubscribe', "tgUnsubscribe.php")->defaults(array(
			'class' => __CLASS__,
			'method'     => 'change',
		));
		Route::Set('tg', "tg.php")->defaults(array(
			'class' => __CLASS__,
			'method'     => 'change',
		));
		if(function_exists("addEvent")) {
			addEvent("notyTelegram", array($this, "noty"));
		}
	}
	
	function change() {
		include(ROOT_PATH."tgLib.php");
	}

	function noty($mess, $type = "txt") {
		$tg = $this->loader("tgNoty");
		return $tg->noty($mess, $type);
	}
	
}

?>
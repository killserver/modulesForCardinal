<?php
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class telegramBotCardinal extends modules {
	
	function __construct() {
		Route::Set('tgUnsubscribe', "tgUnsubscribe.php")->defaults(array(
			'class' => __CLASS__,
			'method'     => 'change',
		));
		Route::Set('tg', "tg.php")->defaults(array(
			'class' => __CLASS__,
			'method'     => 'change',
		));
	}
	
	function change() {
		include(ROOT_PATH."tgLib.php");
	}
	
}

?>
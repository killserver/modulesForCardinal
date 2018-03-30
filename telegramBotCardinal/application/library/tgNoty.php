<?php

class tgNoty {

	public static function noty($text, $type = "text", $config = array()) {
		if(!defined("DS")) {
			define("DS", DIRECTORY_SEPARATOR);
		}
		define("ROOT_TG", dirname(__FILE__).DS);
		if(class_exists("config", false) && method_exists("config", "Select")) {
			$token = config::Select("telegramToken");
		} else {
			global $config;
			if(!isset($config) && !is_array($config)) {
				$config = array();
			}
			if(file_exists(ROOT_TG."configTg.php")) {
				require_once(ROOT_TG."configTg.php");
			}
			$token = (isset($config['telegramToken']) && !empty($config['telegramToken']) ? $config['telegramToken'] : "");
		}
		if(empty($token)) {
			throw new Exception("Token for Telegram is not set", 1);
			die();
		}
		$load = false;
		if(class_exists("modules", false) && method_exists("modules", "loader")) {
			$tg = modules::loader("telegramBot", array("token" => $token));
			$load = true;
		} else {
			$lib = ROOT_TG."telegramBot.php";
			if(file_exists($lib)) {
				require_once($lib);
				$tg = new telegramBot($token);
				$load = true;
			}
		}
		if(!$load) {
			throw new Exception("TelegramBot is not load", 1);
			die();
		}
		$count = 0;
		$list = 0;
		$file = (defined("PATH_CACHE_USERDATA") ? PATH_CACHE_USERDATA : (isset($config['telegramPath']) && !empty($config['telegramPath']) ? $config['telegramPath'] : (defined("PATH_CACHE") ? PATH_CACHE : ROOT_TG)))."tgNoty_chatId.txt";
		if($type=="text") {
			$arr = array();
			if(file_exists($file)) {
				$arr = file($file);
				$arr = array_map("trim", $arr);
				$count = sizeof($arr);
				for($i=0;$i<$count;$i++) {
					// sends an action 'typing'
					$tg->sendChatAction($arr[$i], 'typing');
					// send message with a custom reply markup
					$tg->sendMessage($arr[$i], $text);
					$list++;
				}
			}
		} elseif($type=="html") {
			$arr = array();
			if(file_exists($file)) {
				$arr = file($file);
				$arr = array_map("trim", $arr);
				$count = sizeof($arr);
				for($i=0;$i<$count;$i++) {
					// sends an action 'typing'
					$tg->sendChatAction($arr[$i], 'typing');
					// send message with a custom reply markup
					$tg->sendMessage($arr[$i], $text, "HTML");
					$list++;
				}
			}
		} else {
			throw new Exception("Type data for Telegram is not valid", 1);
			die();
		}
		if($count==0 || $count!=$list) {
			return false;
		} else {
			return true;
		}
	}

}
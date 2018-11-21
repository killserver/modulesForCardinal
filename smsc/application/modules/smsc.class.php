<?php
/*
Name: СМС-уведомления от SMSC.UA для Cardinal Engine
Version: 1.0
Author: killserver
 */
if(!defined("IS_CORE")) {
	echo "403 ERROR";
	die();
}

class smsc extends modules {

	function __construct() {
		if(defined("IS_ADMINCP")) {
			addEvent("admin_core_prints_info", array($this, "smsc_balance"));
			addEventRef("settinguser_main", array($this, "addConfig"));
		} else {
			addEvent("pay_smsc", array($this, "smsc_notice"));
		}
	}

	public static $version = "1.0";

	function addConfig(&$data) {
		$data .= "{include templates=\"smsc.tpl,SettingUser\"}";
	}

	function smsc_balance($arr) {
		$echo = "СМС отправка отключена. Доступы для отправки - не прописаны. <a href=\"./?pages=SettingUser\">Настроить</a>";
		if(($login = config::Select("smsc", "login"))!==false && ($pass = config::Select("smsc", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$prs = new Parser();
				$data = $prs->get("https://smsc.ua/sys/balance.php?login=".config::Select("smsc", "login")."&psw=".config::Select("smsc", "psw")."&fmt=3");
				$data = json_decode($data, true);
				$data = $data['balance'];
				$echo = "Ваш баланс по отправке смс составляет: ".$data;
			}
		}
		$arr[$echo] = array("type" => "info", "block" => true, "echo" => $echo, "time" => time()+1);
		return $arr;
	}

	function smsc_notice($sender, $to, $mess) {
		$prs = file_get_contents("https://smsc.ua/sys/senders.php?add=1&login=".config::Select("smsc", "login")."&psw=".config::Select("smsc", "psw")."&sender=".ToTranslit($sender)."&cmt=1");
		$data = file_get_contents("https://smsc.ua/sys/send.php?login=".config::Select("smsc", "login")."&psw=".config::Select("smsc", "psw")."&phones=".$to."&mes=".htmlspecialchars(rawurlencode($mess), ENT_COMPAT, 'UTF-8')."&charset=utf-8&sender=".ToTranslit($sender)."&cost=3&fmt=3");
		return $data;
	}

}

?>
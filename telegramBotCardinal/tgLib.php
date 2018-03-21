<?php
if(!defined("DS")) {
	define("DS", DIRECTORY_SEPARATOR);
}
define("ROOT_TG", dirname(__FILE__).DS);
if(!defined("ROOT_PATH") && file_exists(ROOT_TG."core.php")) {
	if(!defined("IS_CORE")) {
		define("IS_CORE", true);
	}
	require_once(ROOT_TG."core.php");
}
if(class_exists("config", false) && method_exists("config", "Select")) {
	$token = config::Select("telegramToken");
	$host = config::Select("default_http_host");
	$hostname = config::Select("default_http_hostname");
} else {
	global $config;
	if(!isset($config) && !is_array($config)) {
		$config = array();
	}
	if(file_exists(ROOT_TG."configTg.php")) {
		require_once(ROOT_TG."configTg.php");
	}
	$token = (isset($config['telegramToken']) && !empty($config['telegramToken']) ? $config['telegramToken'] : "");
	$host = "http://".(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv("HTTP_HOST"));
	$hostname = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv("HTTP_HOST"));
}

$tgUnsubscribe = "tgLib.php";
if(defined("ROOT_PATH")) {
	$route = Route::get("tgUnsubscribe");
	if(!is_bool($route)) {
		$params = array();
		$tgUnsubscribe = $route->uri($params);
	}
} else {
	$requests = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv("REQUEST_URI"));
	$requests = str_replace(array("tg.php", "tgLib.php"), "", $requests);
	$host .= $requests;
}

function templateTG($host, $hostname, $echo = "", $type = "all") {
	if($type=="all" || $type=="head") {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>TelegramBot for Cardinal Engine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="//telegram.org/favicon.ico?3" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css">
    <!--link href="/css/myriad.css" rel="stylesheet"-->
    <link href="//telegram.org/css/bootstrap.min.css?2" rel="stylesheet">
    <link href="//telegram.org/css/telegram.css?146" rel="stylesheet" media="screen">
    <style type="text/css" media="screen">
    	.tgme_page_wrap { background: #fff; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; }
    </style>
  </head>
  <body>
    <div class="tgme_page_wrap">
      <div class="tgme_head_wrap">
        <div class="tgme_head">
          <a href="//telegram.org/" class="tgme_head_brand" target="_blank">
            <i class="tgme_logo"></i>
          </a>
        </div>
      </div>
      <a class="tgme_head_dl_button" href="//telegram.org/dl" target="_blank">
        Don't have <strong>Telegram</strong> yet? Try it now!<i class="tgme_icon_arrow"></i>
      </a>
      <div class="tgme_page">
        
<div class="tgme_page_title">TelegramBot for <?php echo $hostname; ?></div>
<div class="tgme_page_extra">
	<br>
<?php
	}
	echo $echo;
	if($type=="all" || $type=="foot") {
?>
</div>
      </div>
    </div>

    <div id="tgme_frame_cont"></div>
  </body>
</html>
<?php
	}
}

function rebuildArrTG($arr) {
	$newArr = array();
	for($i=0;$i<sizeof($arr);$i++) {
		$newArr[$arr[$i]] = $arr[$i];
	}
	return $newArr;
}

if(empty($token)) {
	templateTG($host, $hostname, "<div style='color:red;font-weight:900;'>Token for Telegram is not set</div>");
	die();
}
$username = "";
$load = false;
$localFile = true;
if(class_exists("modules", false) && method_exists("modules", "loader")) {
	$tg = modules::loader("telegramBot", array("token" => $token));
	$localFile = false;
	$load = true;
} else {
	$libLoad = "";
	if(file_exists(ROOT_TG."application".DS."autoload".DS."telegramBot.php")) {
		$libLoad = ROOT_TG."application".DS."autoload".DS."telegramBot.php";
		$localFile = false;
	} else if(file_exists(ROOT_TG."core".DS."class".DS."telegramBot.php")) {
		$libLoad = ROOT_TG."core".DS."class".DS."telegramBot.php";
		$localFile = false;
	} else if(file_exists(ROOT_TG."application".DS."library".DS."telegramBot.php")) {
		$libLoad = ROOT_TG."application".DS."library".DS."telegramBot.php";
		$localFile = false;
	} else if(file_exists(ROOT_TG."telegramBot.php")) {
		$libLoad = ROOT_TG."telegramBot.php";
		$localFile = false;
	}
	$lib = $libLoad;
	if(file_exists($lib)) {
		require_once($lib);
		$tg = new telegramBot($token);
		$load = true;
	}
}
if(!$load) {
	templateTG($host, $hostname, "<div style='color:red;font-weight:900;'>TelegramBot is not load</div>");
	die();
}
$file = (defined("PATH_CACHE_USERDATA") ? PATH_CACHE_USERDATA : ($localFile ? ROOT_TG : ROOT_TG."application".DS."cache".DS))."tgNoty_chatId.txt";
ob_end_flush();
ob_implicit_flush();
if(function_exists("callAjax")) {
	callAjax();
}
if(file_exists($file) && isset($_GET['id'])) {
	$arr = array();
	$save = false;
	if(file_exists($file)) {
		$arr = file($file);
		$arr = array_map("trim", $arr);
		$arr = rebuildArrTG($arr);
		if(isset($arr[$_GET['id']])) {
			unlink($arr[$_GET['id']]);
		}
	}
	if($save) {
		file_put_contents($file, implode(PHP_EOL, $arr).PHP_EOL);
		templateTG($host, $hostname, "Вы успешно отписаны от уведомлений с сайта <b>".$hostname."</b>");
	} else {
		templateTG($host, $hostname, "Вы не были подписаны или уже отписались от уведомлений с сайта <b>".$hostname."</b>");
	}
	die();
}
templateTG($host, $hostname, "Информация о роботе:<br>", "head");
$updates = $tg->getMe();
templateTG($host, $hostname, "Имя робота: <b>".$updates['result']['first_name']."</b><br><a href=\"https://t.me/".$updates['result']['username']."\" target=\"_blank\">Написать боту</a><br><br>", "info");

do {
	// Get updates the bot has received
	// Offset to confirm previous updates
	$updates = $tg->pollUpdates($offset);
	if($updates['ok'] && count($updates['result']) > 0) {
		foreach($updates['result'] as $data) {
			// Get updates the bot has received
			// Offset to confirm previous updates
			$updates = $tg->pollUpdates($offset);
			if($updates['ok'] && count($updates['result']) > 0) {
				foreach($updates['result'] as $data) {
					if(is_null($chat_id)) {
						$username = $data['message']['chat']['first_name']." ".$data['message']['chat']['last_name'];
						$chat_id = $data['message']['chat']['id'];
					}
					$arr = array();
					$save = false;
					if(file_exists($file)) {
						$arr = file($file);
						$arr = array_map("trim", $arr);
						$arr = rebuildArrTG($arr);
						if(!in_array($chat_id, $arr)) {
							$arr[] = $chat_id;
							$save = true;
							// sends an action 'typing'
							$tg->sendChatAction($chat_id, 'typing');
							// send message with a custom reply markup
							$tg->sendMessage($chat_id, "Вы успешно подписались на уведомления с сайта <b>".$hostname."</b>\nДля отписки - перейдите по <a href=\"".$host.$tgUnsubscribe."?id=".$chat_id."\">ссылке</a>", "HTML");
						} else {
							// sends an action 'typing'
							$tg->sendChatAction($chat_id, 'typing');
							// send message with a custom reply markup
							$tg->sendMessage($chat_id, "Вы уже подписанны на уведомления с сайта <b>".$hostname."</b>\nДля отписки - перейдите по <a href=\"".$host.$tgUnsubscribe."?id=".$chat_id."\">ссылке</a>", "HTML");
						}
					} else {
						$arr[] = $chat_id;
						$save = true;
						// sends an action 'typing'
						$tg->sendChatAction($chat_id, 'typing');
						// send message with a custom reply markup
						$tg->sendMessage($chat_id, "Вы успешно подписались на уведомления с сайта <b>".$hostname."</b>\nДля отписки - перейдите по <a href=\"".$host.$tgUnsubscribe."?id=".$chat_id."\">ссылке</a>", "HTML");
					}
					if($save) {
						file_put_contents($file, implode(PHP_EOL, $arr).PHP_EOL);
					}
					$guessed = true;
				}
			}
		}
		$offset = $updates['result'][count($updates['result']) - 1]['update_id'] + 1;
	}
} while(!$guessed);
$offset  = $updates['result'][count($updates['result']) - 1]['update_id'] + 1;
$updates = $tg->pollUpdates($offset);
templateTG($host, $hostname, "<hr><br>Данные от пользователя успешно получены.<br>Пользователь: <b>".$username."</b>", "foot");
die();
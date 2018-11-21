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

$tgUnsubscribe = (isset($_GET['u']) ? $_GET['u'] : "tgLib.php");
if(defined("ROOT_PATH")) {
	$route = Route::get("tgUnsubscribe");
	if(!is_bool($route)) {
		$params = array();
		$tgUnsubscribe = $route->uri($params);
	}
} else {
	$requests = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv("REQUEST_URI"));
	$requests = str_replace(array("tg.php", "tgLib.php", "?start", "&u="), "", $requests);
	$host .= $requests;
}

function templateTG($host, $hostname, $tgUnsubscribe, $echo = "", $type = "all") {
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    	var tests = "";
    	function loadData() {
    		jQuery.post("./tgLib.php?wait&u=<?php echo $tgUnsubscribe; ?>", function(d) {}).done(function(data) {
    			if(data.length==0) {
    				loadData();
    			} else {
	    			jQuery(".result").html(data);
	    			tests = data;
	    		}
    		}).fail(function() {
    			if(tests.length>0) {
    				loadData();
    			}
    		})
    	}
    	<?php if(!isset($_GET['id'])) { ?>
    	jQuery(document).ready(function() {
			loadData();
    	});
    	<?php } ?>
    </script>
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
	templateTG($host, $hostname, $tgUnsubscribe, "<div style='color:red;font-weight:900;'>Token for Telegram is not set</div>");
	die();
}
$username = "";
$load = false;
$localFile = true;
if(class_exists("modules", false) && method_exists("modules", "loader")) {
	$tg = modules::loader("telegramBot", array("token" => $token));
	$localFile = false;
	$load = true;
	$ssl = "https://".HTTP::getServer("HTTP_HOST").HTTP::getServer('REQUEST_URI');
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
	}
	$lib = $libLoad;
	if(file_exists($lib)) {
		require_once($lib);
		$tg = new telegramBot($token);
		$load = true;
	}
	$ssl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
if(!$load) {
	templateTG($host, $hostname, "<div style='color:red;font-weight:900;'>TelegramBot is not load</div>");
	die();
}
$file = (defined("PATH_CACHE_USERDATA") ? PATH_CACHE_USERDATA : (isset($config['telegramPath']) && !empty($config['telegramPath']) ? $config['telegramPath'] : ($localFile ? ROOT_TG : ROOT_TG."application".DS."cache".DS)))."tgNoty_chatId.txt";
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
			unset($arr[$_GET['id']]);
			$save = true;
		}
	}
	if($save) {
		file_put_contents($file, implode(PHP_EOL, $arr).PHP_EOL);
		templateTG($host, $hostname, $tgUnsubscribe, "Вы успешно отписаны от уведомлений с сайта <b>".$hostname."</b>");
	} else {
		templateTG($host, $hostname, $tgUnsubscribe, "Вы не были подписаны или уже отписались от уведомлений с сайта <b>".$hostname."</b>");
	}
	die();
}
if(isset($_GET['start'])) {
	$data = file_get_contents("php://input");
	if(empty($data)) {
		return;
	}
	$data = json_decode($data, true);
	$username = $data['message']['chat']['first_name']." ".$data['message']['chat']['last_name'];
	$chat_id = $data['message']['chat']['id'];
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
			file_put_contents($file."_wait", "Данные от пользователя успешно получены.<br>Пользователь: <b>".$username."</b>");
		} else {
			// sends an action 'typing'
			$tg->sendChatAction($chat_id, 'typing');
			// send message with a custom reply markup
			$tg->sendMessage($chat_id, "Вы уже подписанны на уведомления с сайта <b>".$hostname."</b>\nДля отписки - перейдите по <a href=\"".$host.$tgUnsubscribe."?id=".$chat_id."\">ссылке</a>", "HTML");
			file_put_contents($file."_wait", "Вы уже подписанны на уведомления с сайта <b>".$hostname."</b>\nДля отписки - перейдите по <a href=\"".$host.$tgUnsubscribe."?id=".$chat_id."\">ссылке</a>");
		}
	} else if(!in_array($chat_id, $arr)) {
		$arr[] = $chat_id;
		$save = true;
		// sends an action 'typing'
		$tg->sendChatAction($chat_id, 'typing');
		// send message with a custom reply markup
		$tg->sendMessage($chat_id, "Вы успешно подписались на уведомления с сайта <b>".$hostname."</b>\nДля отписки - перейдите по <a href=\"".$host.$tgUnsubscribe."?id=".$chat_id."\">ссылке</a>", "HTML");
		file_put_contents($file."_wait", "Данные от пользователя успешно получены.<br>Пользователь: <b>".$username."</b>");
	}
	if($save) {
		file_put_contents($file, trim(implode(PHP_EOL, $arr)).PHP_EOL);
	}
die();
}
if(isset($_GET['wait'])) {
	if(file_exists($file."_wait")) {
		echo file_get_contents($file."_wait");
		unlink($file."_wait");
	}
	die();
}
$tg->setWebhook($ssl.(strpos($ssl, "&")===false ? "?":"&")."start");
templateTG($host, $hostname, $tgUnsubscribe, "Информация о роботе:<br>", "head");
$updates = $tg->getMe();
templateTG($host, $hostname, $tgUnsubscribe, "Имя робота: <b>".$updates['result']['first_name']."</b><br><a href=\"https://t.me/".$updates['result']['username']."\" target=\"_blank\">Написать боту</a><br><br>", "info");
templateTG($host, $hostname, $tgUnsubscribe, "<hr><br><div class='result'></div>", "foot");
die();
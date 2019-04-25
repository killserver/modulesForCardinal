<?php
/*
Name: Конструктор любых форм для сайта
Version: 1.0
Author: killserver
 */
class feedback_module extends modules {

	public static $version = "1.0";

	function __construct() {
		$route = config::Select("feedback_route");
		if(empty($route)) {
			$route = "feedback_page.html";
			config::Set("feedback_route", $route);
		}
		addEvent("init_core", function() {
			add_setting_tab('<div class="form-group"> <label class="col-sm-3 control-label" for="feedback_email">Почта для получения писем</label> <div class="col-sm-9"> <div class="form-block"> <input type="text" name="feedback_email" id="feedback_email" class="form-control" value="'.config::Select("feedback_email").'"><small class="col-sm-12">Несколько получателей разделяются запятыми</small> </div> </div> </div> <div class="form-group"> <label class="col-sm-3 control-label" for="feedback_is_page">Отдельная страница для сообщения о успешной отправке</label> <div class="col-sm-9"> <div class="form-block"> <input type="checkbox" name="feedback_is_page" id="feedback_is_page" class="iswitch iswitch-primary" '.(config::Select("feedback_is_page") ? ' checked="checked"' : '').' value="1"> </div> </div> </div> <div class="form-group"> <label class="col-sm-3 control-label" for="feedback_route">Ссылка для сообщения о успешной отправке</label> <div class="col-sm-9"> <div class="form-block"> <input type="text" name="feedback_route" id="feedback_route" class="form-control" value="'.config::Select("feedback_route").'"> </div> </div> </div><script>document.getElementById("feedback_is_page").addEventListener("change",function(e){if(e.target.checked===false){e.target.removeAttribute("checked")}else{e.target.setAttribute("checked", "checked")}});</script>', '');
		});
		addEvent("loadUserLevels", array($this, "feedbackModuleLevel"));
		addEvent("pre_save_settings", function($arr) {
			if(!isset($arr['feedback_is_page'])) {
				$arr['feedback_is_page'] = "";
			}
			return $arr;
		});
		Route::set("get_feedback", "(<lang>/)".$route)->defaults(array(
			"class" => __CLASS__,
			"method" => "page",
		));
	}

	function feedbackModuleLevel($levels) {
		$levels[LEVEL_CREATOR]['access_feedback_form'] = "yes";
		$levels[LEVEL_ADMIN]['access_feedback_form'] = "yes";
		return $levels;
	}

	function page($lang, $langDB) {
		$len = strlen('<?php die(); ?>');
		if(ajax_check()=="ajax" && sizeof($_POST)>0) {
			$show = "";
			$file = Arr::get($_GET, "file", false);
			if(!empty($file) && file_exists(PATH_CACHE_USERDATA."feedback".DS.$file.".".ROOT_EX)) {
				$f = file_get_contents(PATH_CACHE_USERDATA."feedback".DS.$file.".".ROOT_EX);
				$f = substr($f, $len);
				$f = json_decode($f, true);
				$fields = $f['fields'];
				$send = $f['send_mess'];
				$all = sizeof($fields);
				$data = array();
				for($i=0;$i<$all;$i++) {
					if(($field = Arr::get($_POST, $fields[$i], false))!==false) {
						preg_match('#\[(.+?)name=[\'"]'.$fields[$i].'[\'"].*?\]#is', $send, $find);
						$data[$fields[$i]] = $field;
						$send = str_replace($find[0], $field, $send);
						$all--;
					}
				}
				execEventRef("feedback_save_data", $data, $all);
				if($all==0) {
					$conf = config::Select("feedback_email");
					if(!empty($f['address'])) {
						$mails = explode(",", $f['address']);
					} else if(!empty($conf)) {
						$mails = explode(",", $conf);
					}
					if(!empty($send) && !empty($mails)) {
						for($i=0;$i<sizeof($mails);$i++) {
							nmail($mails[$i], $send, "Сообщение с формы \"".$f['title']."\"");
						}
					}
					HTTP::ajax(array("success" => true));
				}
			}
			HTTP::ajax(array("success" => false));
			die();
		}
		$tmp = $this->init_templates();
		$tpl = execEvent("feedback_view_success_before", "");
		if(empty($tpl)) {
			if($tmp->check_exists("feedback.default", "")) {
				$tpl = $tmp->completed_assign_vars("feedback.default", "");
			} else {
				$tpl = $tmp->completed_assign_vars("feedback", "");
			}
		}
		$tpl = execEvent("feedback_view_success_after", $tpl);
		$tmp->completed($tpl);
		$tmp->display();
	}

}
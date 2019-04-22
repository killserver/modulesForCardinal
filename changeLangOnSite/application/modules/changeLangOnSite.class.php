<?php
/*
Name: Быстрый редактор переводов
Version: 1.1
Author: killserver
 */
class changeLangOnSite extends modules {

	public static $version = "1.1";

	function __construct() {
		if(defined("IS_ADMIN")) {
			return;
		}
		addEvent("compileTPL", array($this, "langChange"));
		addEvent("before_jscss_print_css", array($this, "applyStyle"));
		addEvent("templates::display", array($this, "applyChange"), "", 9999999);
		addEventRef("admin_menu_sorted", array($this, "changeAdmin"));
	}

	function changeAdmin(&$menu) {
		$item1 = array(
			array(
				"link" => "#",
				"title" => "{L_\"Редактировать текст\"}",
				"type" => "cat",
				"access" => true,
				"icon" => "editLang",
				"class" => "editLang",
			)
		);
		$item2 = array(
			array(
				"link" => "#",
				"title" => "{L_\"Редактировать текст\"}",
				"type" => "item",
				"access" => true,
				"icon" => "editLang",
				"class" => "editLang",
			)
		);
		$nMenu = array();
		$nMenu["A0Lang"] = array(
			"cat" => $item1,
			"item" => $item2,
		);
		foreach($menu as $k => $v) {
			$nMenu[$k] = $v;
		}
		$menu = $nMenu;
	}

	function langChange($tpl) {
		$tpl = $this->callback_array("#\{CL_([\"|']|)([a-zA-Z0-9\-_]+)(\\1)\[([a-zA-Z0-9\-_]*?)\]\}#", array($this, "getMyLang"), $tpl);
		$tpl = $this->callback_array("#\{CL_()([a-zA-Z0-9\-_]+)()\[([a-zA-Z0-9\-_]*?)\]\}#", array($this, "getMyLang"), $tpl);
		$tpl = $this->callback_array("#\{CL_()([a-zA-Z0-9\-_]+)()\[(.*?)\]\}#", array($this, "getMyLang"), $tpl);
		$tpl = $this->callback_array("#\{CL_([\"|']|)(.+?)(\\1)\}#", array($this, "getMyLang"), $tpl);
		$tpl = $this->callback_array("#\{CL_()(.+?)()\}#", array($this, "getMyLang"), $tpl);
		return $tpl;
	}

	function getMyLang($array) {
		if(isset($array[4])) {
			$array[2] = preg_replace("/\s{2,}/", ' ', $array[2]);
			$array[4] = preg_replace("/\s{2,}/", ' ', $array[4]);
			$isset = $this->get_lang($array[2], $array[4]);
		} else {
			$array[2] = preg_replace("/\s{2,}/", ' ', $array[2]);
			$isset = $this->get_lang($array[2]);
		}
		$user = userlevel::get("languages");
		if(!empty($isset) && $isset!='""') {
			return ($user ? '<custom-lang data-orText="'.htmlspecialchars($array[2]).'"><custom-text>' : '').$isset.($user ? '</custom-text><custom-tag class="done-lang"></custom-tag><custom-tag class="close-lang"></custom-tag></custom-lang>' : '');
		} else if($array[2]!='""') {
			return ($user ? '<custom-lang data-orText="'.htmlspecialchars($array[2]).'"><custom-text>' : '').$array[2].($user ? '</custom-text><custom-tag class="done-lang"></custom-tag><custom-tag class="close-lang"></custom-tag></custom-lang>' : '');
		} else {
			return "";
		}
	}

	private function callback_array($pattern, $func, $data) {
		if(function_exists("preg_replace_callback_array")) {
			return preg_replace_callback_array(array($pattern => $func), $data);
		} else {
			return preg_replace_callback($pattern, $func, $data);
		}
	}

	function applyStyle($tpl) {
		$tpl['full'][0] = array("url" => file_get_contents(ROOT_PATH."js".DS."adminChangeStyle.css"));
		return $tpl;
	}

	function applyChange($tpl) {
		$success = "Успешно сохранили перевод";
		$isset = $this->get_lang($success);
		if(!empty($isset) && $isset!='""') {
			$success = $isset;
		}
		$error = "Ошибка при сохранении перевода";
		$isset = $this->get_lang($error);
		if(!empty($isset) && $isset!='""') {
			$error = $isset;
		}
		$tpl = str_replace("</body>", '<script>'.file_get_contents(ROOT_PATH."js".DS."adminChangeLang.js").'</script><script>var adminLangPage = "'.config::Select("default_http_local").ADMINCP_DIRECTORY.'/?pages=Languages&lang='.lang::get_lg().'&saveLang=true";var lang_save_success = "'.$success.'";var lang_save_error = "'.$error.'";</script></body>', $tpl);
		return $tpl;
	}

}
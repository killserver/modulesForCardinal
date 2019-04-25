<?php

class feedback extends modules {

	function start($file) {
		$len = strlen('<?php die(); ?>');
		$show = "";
		$route = config::Select("feedback_route");
		if(empty($route)) {
			$route = "feedback_page.html";
			config::Set("feedback_route", $route);
		}
		if(file_exists(PATH_CACHE_USERDATA."feedback".DS.$file.".".ROOT_EX)) {
			$f = file_get_contents(PATH_CACHE_USERDATA."feedback".DS.$file.".".ROOT_EX);
			$f = substr($f, $len);
			$f = json_decode($f, true);
			$show = $f['form'];
			preg_match_all('#\[(.+?)\]#is', $show, $all);
			for($i=0;$i<sizeof($all[0]);$i++) {
				$ret = $all[0][$i];
				if(strpos($all[0][$i], "text")!==false) {
					$ret = str_replace("[text ", "<input type=\"text\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "email")!==false) {
					$ret = str_replace("[email ", "<input type=\"email\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "url")!==false) {
					$ret = str_replace("[url ", "<input type=\"url\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "tel")!==false) {
					$ret = str_replace("[tel ", "<input type=\"tel\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "number")!==false) {
					$ret = str_replace("[number ", "<input type=\"number\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "date")!==false) {
					$ret = str_replace("[date ", "<input type=\"date\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "textarea")!==false) {
					$ret = preg_replace('#\[textarea(.+?)]#is', "<textarea$1></textarea>", $ret);
				}
				if(strpos($all[0][$i], "file")!==false) {
					$ret = str_replace("[file ", "<input type=\"file\" ", $ret);
					$ret = str_replace("]", ">", $ret);
				}
				if(strpos($all[0][$i], "submit")!==false) {
					$ret = str_replace("[submit]", "<input type=\"submit\" value=\"{L_\"Отправить\"}\">", $ret);
				}
				$ret = str_replace("*", " required=\"required\"", $ret);
				$show = str_replace($all[0][$i], $ret, $show);
			}
			$show = "<form method=\"post\" action=\"{C_default_http_local}".$route."?file=".$file."\" enctype=\"multipart/form-data\" class=\"feedback_module\" data-type=\"".(($ajax = config::Select("feedback_is_page"))===false || empty($ajax) ? "" : "ajax")."\">".$show."</form>";
			$show .= "<script>".file_get_contents(ROOT_PATH."js".DS."feedback.js")."</script><script>var feedback_route = \"".config::Select("feedback_route")."\";</script>";
		}
		return $show;
	}

}

?>
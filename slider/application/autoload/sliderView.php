<?php

class sliderView {

	function start() {
		$db = modules::init_db();
		$tmp = modules::init_templates();
		$db->doquery("SELECT * FROM {{slider}} ORDER BY `slide_id` DESC", true);
		while($row = $db->fetch_assoc()) {
			$tmp->assign_vars($row, "sliderz", $row['slide_id']);
		}
		$template = config::Select("templateSlider");
		modules::regCssJs('jQuery(document).ready(function(){ jQuery(".sliderz").cardinalBxSlider(); });', "js", false);
		if(file_exists(PATH_SKINS.$template.".default.".$tmp->changeTypeTpl())) {
			$template .= ".default";
		}
		$tpl = $tmp->completed_assign_vars($template, null);
		return $tmp->view($tpl);
	}

}
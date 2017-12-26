<?php

class swiperView {

	function start() {
		$db = modules::init_db();
		$tmp = modules::init_templates();
		$db->doquery("SELECT * FROM {{swiper}} ORDER BY `slide_id` DESC", true);
		while($row = $db->fetch_assoc()) {
			$tmp->assign_vars($row, "swiperz", $row['slide_id']);
		}
		$template = config::Select("templateSwiper");
		modules::regCssJs('jQuery(document).ready(function(){ var swiper = new Swiper({ el: '.swiperz', initialSlide: 2, spaceBetween: 50, slidesPerView: 2, centeredSlides: true, slideToClickedSlide: true, grabCursor: true, scrollbar: { el: '.swiperz-scrollbar', }, mousewheel: { enabled: true, }, keyboard: { enabled: true, }, pagination: { el: '.swiperz-pagination', }, navigation: { nextEl: '.swiperz-button-next', prevEl: '.swiperz-button-prev', }, }); });', "js", false);
		if(file_exists(PATH_SKINS.$template.".default.".$tmp->changeTypeTpl())) {
			$template .= ".default";
		}
		$tpl = $tmp->completed_assign_vars($template, null);
		return $tmp->view($tpl);
	}

}
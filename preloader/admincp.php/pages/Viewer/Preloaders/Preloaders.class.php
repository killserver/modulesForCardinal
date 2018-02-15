<?php

class Preloaders extends Core {

	function __construct() {
		if(Arr::get($_GET, "save", false)) {
			$save = Arr::get($_GET, "save");
			callAjax();
			if($save=="-1") {
				config::Del("preloader");
				cardinal::RegAction("Отключен прелоадер для сайта");
			} else {
				config::Update("preloader", $save);
				cardinal::RegAction("Установлен прелоадер для сайта под номером ".$save);
			}
			return false;
		}
		templates::assign_var("maxPreloaders", "8");
		$this->Prints("Preloaders");
	}

}
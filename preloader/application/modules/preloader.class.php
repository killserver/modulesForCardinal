<?php

class preloader extends modules {

	function __construct() {
		$config = $this->init_config();
		$config->init();
		$preloader = $config->Select("preloader");
		if($preloader!==false) {
			cardinalEvent::addListener("templates::display", array($this, "addPreloader"), $preloader);
		}
	}

	public static $version = "1.0";

	function addPreloader($id, $html) {
		$default = $this->get_config("default_http_local");
		$path = get_site_path(PATH_SKINS);
		$createHead = '<link rel="stylesheet" href="'.$default.$path.'preloader/loaders.css">';
		$createHead .= '<link rel="stylesheet" href="'.$default.$path.'preloader/loader-'.$id.'.css">';
		$createHead .= '<script src="'.$default.$path.'preloader/loaders.min.js" type="text/javascript"></script>';
		$createBody = '<div id="loader"><div class="load preloader-'.$id.'"></div></div>';
		$html = str_replace("</head>", $createHead."</head>", $html);
		$html = preg_replace("#<body(.*?)>#is", "<body$1>".$createBody, $html);
		return $html;
	}

}

?>
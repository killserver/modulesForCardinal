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

	public static $version = "1.1";

	function addPreloader($id, $html) {
		$path = get_site_path(PATH_SKINS);
		$createHead = '<style>'.file_get_contents(ROOT_PATH.$path.'preloader/loaders.css')."</style>";
		$createHead .= '<style>'.file_get_contents(ROOT_PATH.$path.'preloader/loader-'.$id.'.css')."</style>";
		$createHead .= '<script src="'.$default.$path.'preloader/loaders.min.js?'.time().'" type="text/javascript"></script>';
		$createBody = '<div id="loader"><div class="load preloader-'.$id.'"><div class="cssload-cube cssload-c1"></div><div class="cssload-cube cssload-c2"></div><div class="cssload-cube cssload-c3"></div><div class="cssload-cube cssload-c4"></div></div></div>';
		$html = str_replace("</head>", $createHead."</head>", $html);
		$html = preg_replace("#<body(.*?)>#is", "<body$1>".$createBody, $html);
		return $html;
	}

}

?>
<?php

class browserSync extends modules {

	function __construct() {
		register_shutdown_function(array($this, "reloadScript"));
	}

	public static $version = "1.0";

	function reloadScript() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			return false;
		}
		if(getenv('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
			return false;
		}
		echo '<script id="__bs_script__">//<![CDATA['."\n".'document.write("<script async src=\'/browser-sync/browser-sync-client.js?v=2.23.6\'><\/script>".replace("HOST", location.hostname));'."\n".'//]]></script>';
	}

}

?>
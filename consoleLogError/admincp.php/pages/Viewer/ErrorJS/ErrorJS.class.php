<?php
/*
*
* Version Engine: 1.25.5b1
* Version File: 3
*
* 3.1
* fix admin templates
* 3.2
* fix view errors
*
*/
class ErrorJS extends Core {
	
	function Delete() {
		cardinal::RegAction("Очистка логов ошибок");
		if(file_exists(PATH_CACHE_SYSTEM."errorJS.txt")) {
			unlink(PATH_CACHE_SYSTEM."errorJS.txt");
		}
		if(!defined("WITHOUT_DB") && db::connected()) {
			$list = db::doquery("SELECT `id` FROM {{error_log}}", true);
			while($l = db::fetch_assoc($list)) {
				db::doquery("DELETE FROM {{error_log}} WHERE id = ".$l['id']);
			}
		}
	}

	function __construct() {
		if(isset($_GET['delete'])) {
			$this->Delete();
			cardinal::RegAction("Очистка логов ошибок скриптов");
			location("./?pages=ErrorJS");
			die();
		}
		if(file_exists(PATH_CACHE_SYSTEM."errorJS.txt")) {
			$file = array();
			if(file_exists(PATH_CACHE_SYSTEM."errorJS.txt")) {
				$file = file_get_contents(PATH_CACHE_SYSTEM."errorJS.txt");
				$file = json_decode($file, true);
			}
			$isExists = false;
			for($i=0;$i<sizeof($file);$i++) {
				if(isset($file[$i]['trace'])) {
					$first = $file[$i]['trace'][0];
					templates::assign_vars(array(
						"time" => date("d-m-Y H:i:s", $file[$i]['time']),
						"errorno" => ($file[$i]['type']),
						"error" => nl2br(htmlspecialchars($first)),
						"path" => $file[$i]['file'],
						"ip" => $file[$i]['ip'],
						"descr" => nl2br(var_export((isset($file[$i]['trace']) ? $file[$i]['trace'] : ""), true)),
					), "logs", $file[$i]['file'].$i);
				}
			}
		}
		$this->ParseLang();
		$tmp = templates::completed_assign_vars("errorJS", null);
		templates::clean();
		$this->Prints($tmp, true);
	}

}

?>
<?php

class Creator extends Core {

	function __construct() {
		templates::assign_var("db_connected", db::connected());
		if(isset($_GET['list'])) {
			$file = file_get_contents(ROOT_PATH.ADMINCP_DIRECTORY.DS."assets".DS.config::Select("skins", "admincp").DS."css".DS."fonts".DS."fontawesome".DS."css".DS."font-awesome.css");
			preg_match_all("#\.fa-(.+?)\:before#", $file, $arr);
			$arr = $arr[1];
			$ret = "<a href=\"#\" class=\"selectIcon pull-left\" data-icon=\"\"><i class=\"fa fa-stack fa-fw fa-2x\" style=\"font-size:2em!important;border:0.01em solid #333;width:1em;height:1em;margin:0px 0.5em;\"></i></a>";
			for($i=0;$i<sizeof($arr);$i++) {
				$ret .= "<a href=\"#\" class=\"selectIcon pull-left\" data-icon=\"".$arr[$i]."\"><i class=\"fa fa-stack fa-fw fa-2x fa-".$arr[$i]."\" style=\"font-size:2em!important\"></i></a>";
			}
			callAjax();
			echo $ret;
			return false;
		}
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		$pathForReady = dirname(__FILE__).DS;
		if(!file_exists($pathForThisModule)) {
			@mkdir($pathForThisModule, 0777);
		}
		if(!is_writeable($pathForThisModule)) {
			@chmod($pathForThisModule, 0777);
		}
		$req = new Request();
		if(($mod = $req->get->get("mod", false)) !== false) {
			$ret = "";
			if($mod=="Add") {
				self::Editor();
				$ret = "1";
			} else if($mod=="Edit" && ($name = $req->get->get("name", false)) !== false) {
				self::Editor($name);
				$ret = "1";
			} else if($mod=="Delete" && ($name = $req->get->get("name", false)) !== false) {
				self::Deletes($name);
				$ret = "1";
			} else if($mod=="MultiAction") {
				self::MultiAction();
				$ret = "1";
			}
			if(!empty($ret)) {
				return false;
			}
		}
		if($req->get->get("loadTables", false)) {
			callAjax();
			$dbs = db::getTables(true, true);
			$arr = array();
			foreach($dbs as $table => $attr) {
				$arr[$table] = array("name" => $table, "fields" => array_keys($attr));
			}
			HTTP::echos(json_encode($arr));
			die();
		}
		$dbs = db::getTables(true, true);
		$dbs = array_keys($dbs);
		$prefix = array();
		if(defined("PREFIX_DB")) {
			$pr = PREFIX_DB;
			if(!empty($pr)) {
				$prefix = array(PREFIX_DB);
			}
		}
		for($i=1;$i<sizeof($dbs);$i++) {
			$prefix[] = $prefix[0];
		}
		$showMe = array();
		$dbs = array_map(("self::removePrefix"), $dbs, $prefix);
		for($i=0;$i<sizeof($dbs);$i++) {
			if(file_exists($pathForThisModule."file_".$dbs[$i].".txt")) {
				$showMe[] = "file_".$dbs[$i].".txt";
				$name = $names = $dbs[$i];
				if(is_readable($pathForThisModule."file_".$dbs[$i].".txt")) {
					try {
						$file = file_get_contents($pathForThisModule."file_".$dbs[$i].".txt");
						$file = json_decode($file);
						$name = $file->data->title."<br><small>".$names."</small>";
					} catch(Exception $ex) {}
				}
				templates::assign_vars(array("name" => $name, "table" => $names, "created" => "true"), "creator");
			}
		}
		$dir = read_dir($pathForThisModule, ".txt");
		for($i=0;$i<sizeof($dir);$i++) {
			if(!in_array($dir[$i], $showMe)) {
				$name = $names = str_replace(array("file_", ".txt"), "", $dir[$i]);
				if(is_readable($pathForThisModule.$dir[$i])) {
					try {
						$file = file_get_contents($pathForThisModule.$dir[$i]);
						$file = json_decode($file);
						$name = $file->data->title."<br><small>".$names."</small>";
					} catch(Exception $ex) {}
				}
				templates::assign_vars(array("name" => $name, "table" => $names, "created" => "false"), "creator");
			}
		}
		(new Core)->Prints("Creator/CreatorMain");
	}

	private static function convertToHierarchy($results, $parentIdField = 'parent_id', $childrenField = 'children') {
		$results = array_reverse($results, true);
		foreach($results as $id => &$item) {
			$parentId = $item[$parentIdField];
			if($parentId>0 && isset($results[$parentId])) { // -- parent DOES exist
				if(!isset($results[$parentId][$childrenField])) {
					$results[$parentId][$childrenField] = array();
				}
				$results[$parentId][$childrenField][] = $item; // -- assign it to the parent's list of children
				unset($results[$id]); // -- remove it from the root of the hierarchy
			}
		}
		$results = array_reverse($results, true);
		return $results;
	}

	private static function Deletes($name, $ret = true, $install = false) {
		$altTitleUp = nucfirst($name);
		if(file_exists(PATH_MODULES."loader.".ROOT_EX)) {
			if(!is_writeable(PATH_MODULES)) {
				@chmod(PATH_MODULES, 077);
			}
			if(!is_writeable(PATH_MODULES."loader.".ROOT_EX)) {
				@chmod(PATH_MODULES."loader.".ROOT_EX, 077);
			}
			$file = PATH_MODULES."loader.".ROOT_EX;
			$files = file_get_contents($file);
			$add = '"application".DS."modules".DS."'.$altTitleUp.'Archer.class.".ROOT_EX => true,';
			if(strpos($files, $add)!==false) {
				$files = str_replace($add, '', $files);
				file_put_contents($file, $files);
			}
		}
		if(file_exists(PATH_MODULES."loader.default.".ROOT_EX)) {
			if(!is_writeable(PATH_MODULES)) {
				@chmod(PATH_MODULES, 077);
			}
			if(!is_writeable(PATH_MODULES."loader.default.".ROOT_EX)) {
				@chmod(PATH_MODULES."loader.default.".ROOT_EX, 077);
			}
			$file = PATH_MODULES."loader.default.".ROOT_EX;
			$files = file_get_contents($file);
			$add = '"application".DS."modules".DS."'.$altTitleUp.'Archer.class.".ROOT_EX => true,';
			if(strpos($files, $add)!==false) {
				$files = str_replace($add, '', $files);
				file_put_contents($file, $files);
			}
		}
		modules::drop_table($name);
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		$pathForReady = dirname(__FILE__).DS;
		if(!file_exists($pathForThisModule)) {
			@mkdir($pathForThisModule, 0777);
		}
		if(!is_writeable($pathForThisModule)) {
			@chmod($pathForThisModule, 0777);
		}
		if(file_exists($pathForThisModule."file_".$name.".txt") && $install===false) { unlink($pathForThisModule."file_".$name.".txt"); }
		if(file_exists(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX)) { unlink(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX); }
		if(file_exists(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX)) { unlink(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX); }
		if(file_exists(ADMIN_MENU.$name.".main.".ROOT_EX)) { unlink(ADMIN_MENU.$name.".main.".ROOT_EX); }
		if(defined("TEMPLATEPATH")) {
			$file = TEMPLATEPATH."list_".$name.".default.".templates::changeTypeTpl();
			if(file_exists($file)) { unlink($file); }
			$file = TEMPLATEPATH."details_".$altTitle."_detail.default.".templates::changeTypeTpl();
			if(file_exists($file)) { unlink($file); }
		}
		if($ret) {
			location("./?pages=Creator");
			return false;
		}
		return true;
	}

	private static function MultiAction() {
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		$req = new Request();
		if(($deletes = $req->post->get("delete", false))!==false) {
			for($i=0;$i<sizeof($deletes);$i++) {
				self::Deletes($deletes[$i], false);
			}
		}
		location("./?pages=Creator");
		return false;
	}

	/*private static function combineFields($data1, $data2) {
		$lang = lang::support(true);
		$lang = array_map("nucfirst", $lang);
		sortByValue($lang);
		$res = array();
		$data1 = array_values($data1);
		$data2 = array_values($data2);
		$all = 0;
		for($i=0;$i<sizeof($data1);$i++) {
			$title1 = str_replace($lang, "", $data1[$i]['altName']);
			$res[$all] = $data1[$i];
			for($z=0;$z<sizeof($data2);$z++) {
				$title2 = str_replace($lang, "", $data2[$z]['altName']);
				if($title2==$title1) {
					$all++;
					$res[$all] = $data2[$z];
					unset($data2[$z]);
					break;
				}
			}
			$data2 = array_values($data2);
			$all++;
		}
		if(sizeof($data2)>0) {
			$res = array_merge($res, $data2);
		}
		$resC = array();
		for($i=0;$i<sizeof($res);$i++) {
			$resC[$res[$i]['altName']] = $res[$i];
		}
		$res = array_values($resC);
		return $res;
	}*/

	private static function combineFields($data1, $data2) {
		$data = array();
		$data1 = array_values($data1);
		for($i=0;$i<sizeof($data1);$i++) {
			$data[$data1[$i]['altName']] = $data1[$i];
		}
		$data2 = array_values($data2);
		for($i=0;$i<sizeof($data2);$i++) {
			$data[$data2[$i]['altName']] = $data2[$i];
		}
		$data = array_values($data);
		usort($data, function($item1, $item2) {
			return ($item1['sort']<$item2['sort'] ? -1 : 1);
		});
		return $data;
	}

	private static function workInField(&$data, &$listShild, &$universalAttributesTakeAdd, &$universalAttributesTakeEdit, &$universalAttributes, &$universalAttributesShow, &$createAutoField, &$exclude, &$excludeLang, &$fieldsForTranslate, &$altTranslateField, &$altLinkField, $first, $i, $langSupport, $sort, $sufix = "", $ignoreNotFirst = true, $supportLang = false, $z = 0) {
		if(isset($data[$i]['altName']) && !empty($data[$i]['altName'])) {
			$altNameField = nucfirst($data[$i]['altName']);
		} else {
			$altNameField = nucfirst(ToTranslit($data[$i]['name'], false, false, true));
		}
		if(isset($data[$i]['supportLang'])) {
			$fieldsForTranslate[] = $first.$altNameField.$sufix;
			$upper = array_map("nucfirst", $langSupport);
			$supportLang = true;
			unset($data[$i]['supportLang']);
			for($z=0;$z<sizeof($upper);$z++) {
				self::workInField($data, $listShild, $universalAttributesTakeAdd, $universalAttributesTakeEdit, $universalAttributes, $universalAttributesShow, $createAutoField, $exclude, $excludeLang, $fieldsForTranslate, $altTranslateField, $altLinkField, $first, $i, $langSupport, $sort, $upper[$z], ($z===0), $supportLang, $z);
			}
			unset($data[$i]);
			return;
		}
		if(!isset($data[$i]['orAltName'])) {
			$data[$i]['orAltName'] = $data[$i]['altName'];
		} else {
			$data[$i]['altName'] = $data[$i]['orAltName'];
		}
		if(!(isset($data[$i]['altName']) && !empty($data[$i]['altName']))) {
			$data[$i]['altName'] = $first.$altNameField;
		}
		$data[$i]['sort'] = $sort;
		$data[$i]['altName'] .= $sufix;
		$altNamer = $data[$i]['altName'];
		$forAutoField = ($i);
		if(!empty($sufix) && $z>0) {
			$forAutoField += $z;
		}
		execEventRef("creator_work_in_field", $data, $listShild, $universalAttributesTakeAdd, $universalAttributesTakeEdit, $universalAttributes, $universalAttributesShow, $createAutoField, $exclude, $fieldsForTranslate, $altTranslateField, $altLinkField, $first, $i, $langSupport, $sufix, $ignoreNotFirst, $supportLang);
		if(!isset($data[$i]['type'])) {
			$data[$i]['type'] = "varchar";
		}
		if($data[$i]['type']=="image" || $data[$i]['type']=="imageAccess") {
			$listShild .= 'if(isset($row[\''.$altNamer.'\'])) { $row[\''.$altNamer.'\'] = "<img src=\"{C_default_http_local}".$row[\''.$altNamer.'\']."\" style=\"max-width:200px\">"; }'.PHP_EOL;
		}
		$altName = "";
		if(isset($data[$i]['name'])) {
			$altName = $data[$i]['name'];
			$altTranslateField = nucfirst(ToTranslit($data[$i]['name'], false, false, true));
		}
		if(isset($data[$i]['alttitle'])) {
			$altName = $data[$i]['alttitle'];
			$altLinkField = $altTranslateField = ToTranslit($altName, false, false, true);
			$universalAttributes .= '$model->setAttribute(\''.$altTranslateField.'\', \'Type\', \'hidden\');'.PHP_EOL;
			$universalAttributesTakeAdd .= 'if(property_exists($model, "'.$altTranslateField.'")) { $model->'.$altTranslateField.' = ToTranslit($model->'.$altNamer.'); }'.PHP_EOL;
			$universalAttributesTakeEdit .= 'if(property_exists($model, "'.$altTranslateField.'") && empty($model->'.$altTranslateField.')) { $model->'.$altTranslateField.' = ToTranslit($model->'.$altNamer.'); }'.PHP_EOL;
			$createAutoField[$forAutoField] = array("altName" => $altTranslateField, "name" => $altName, "type" => "hidden");//$data[$i]['type']
			$forAutoField++;
			$exclude[$altTranslateField] = "\"".$altTranslateField."\"";
			unset($data[$i]['alttitle']);
		}
		$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'default\', \''.htmlspecialchars($data[$i]['default']).'\');'.PHP_EOL;
		if($supportLang === true) {
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'Attr\', \'supportLang\');'.PHP_EOL;
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'Lang\', \''.$sufix.'\');'.PHP_EOL;
		}
		if(isset($data[$i]['placeholder']) && !empty($data[$i]['placeholder'])) {
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'placeholder\', \''.$data[$i]['placeholder'].'\');'.PHP_EOL;
		}
		if(isset($data[$i]['required']) && !empty($data[$i]['required'])) {
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'required\', \''.$data[$i]['required'].'\');'.PHP_EOL;
		}
		if($data[$i]['type']=="linkToAdmin") {
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \'linkToAdmin\');'.PHP_EOL;
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'linkLink\', \''.$data[$i]['field']['link'].'\');'.PHP_EOL;
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'titleLink\', \''.$data[$i]['field']['title'].'\');'.PHP_EOL;
		} else if($data[$i]['type']=="systime") {
			$universalAttributesTakeAdd .= 'if(isset($model->'.$altNamer.')) { $model->'.$altNamer.' = $model->Time(); }'.PHP_EOL;
			$universalAttributesTakeEdit .= 'if(isset($model->'.$altNamer.') && $model->'.$altNamer.'==0) { $model->'.$altNamer.' = $model->Time(); }'.PHP_EOL;
			$universalAttributesShow .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \'datetime\');'.PHP_EOL;
			$data[$i]['type'] = "hidden";
		} else if($data[$i]['type']=="multiple-array" && $data[$i]['selectedData']=="dataOnInput") {
			$name = $data[$i]['loadDB']['name'];
			if(defined("PREFIX_DB") && PREFIX_DB!=="") {
				$len = strlen(PREFIX_DB);
				$name = substr($name, $len);
			}
			$listShild .= 'if(isset($row[\''.$altNamer.'\'])) { $row[\''.$altNamer.'\'] = explode(",", $row[\''.$altNamer.'\']); $arr = array(); $finder = explode(";=-=;", "'.implode(";=-=;", $data[$i]['field']).'"); for($i=0;$i<sizeof($row[\''.$altNamer.'\']);$i++) { if(in_array($row[\''.$altNamer.'\'][$i], $finder)) { $find = true; $arr[] = $row[\''.$altNamer.'\'][$i]; } } if($find===false) { $row[\''.$altNamer.'\'] = "{L_\"Не найдено\"}"; } else { $row[\''.$altNamer.'\'] = implode(",", $arr); } }'.PHP_EOL;

			$universalAttributes .= 'if(property_exists($model, "'.$altNamer.'")) { $model->setAttribute("'.$altNamer.'", "Type", "multiple-array"); $model->'.$altNamer.' = explode(",", $model->'.$altNamer.'); $default = $cats = array(); $finder = explode(";=-=;", "'.implode(";=-=;", $data[$i]['field']).'"); for($i=0;$i<sizeof($finder);$i++) { if(isset($model->'.$altNamer.'[$i]) && in_array($model->'.$altNamer.'[$i], $finder)) { $default[] = $model->'.$altNamer.'[$i]; } $cats[] = array("".$finder[$i] => $finder[$i]); } $category = $cats; $category[\'default\'] = $default; $model->'.$altNamer.' = $category; }'.PHP_EOL;
			$d = 'if(property_exists($model, "'.$altNamer.'") && isset($_POST[\''.$altNamer.'\'])) { $model->'.$altNamer.' = explode(",", $_POST[\''.$altNamer.'\']); $cats = array(); $finder = explode(";=-=;", "'.implode(";=-=;", $data[$i]['field']).'"); for($i=0;$i<sizeof($model->'.$altNamer.');$i++) { if(in_array($model->'.$altNamer.'[$i], $finder)) { $cats[] = $model->'.$altNamer.'[$i]; } } $model->'.$altNamer.' = implode(",", $cats); }';
			$universalAttributesTakeAdd .= $d.PHP_EOL;
			$universalAttributesTakeEdit .= $d.PHP_EOL;
			$universalAttributesShow .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
		} else if($data[$i]['type']=="multiple-array" && $data[$i]['selectedData']=="dataOnTable") {
			$name = $data[$i]['loadDB']['name'];
			if(defined("PREFIX_DB") && PREFIX_DB!=="") {
				$len = strlen(PREFIX_DB);
				$name = substr($name, $len);
			}
			$listShild .= 'if(isset($row[\''.$altNamer.'\'])) { $db = self::init_db(); $find = false; if(!isset(self::$cache[\''.$name.'\']) || !is_array(self::$cache[\''.$name.'\']) || sizeof(self::$cache[\''.$name.'\'])==0) { self::$cache[\''.$name.'\'] = array(); $db->doquery("SELECT `'.$data[$i]['loadDB']['key'].'`, `'.$data[$i]['loadDB']['value'].'` FROM {{'.$name.'}}", true); while($rows = $db->fetch_assoc()) { self::$cache[\''.$name.'\'][$rows[\''.$data[$i]['loadDB']['key'].'\']] = $rows[\''.$data[$i]['loadDB']['value'].'\']; } } if(isset(self::$cache[\''.$name.'\'])) { $row[\''.$altNamer.'\'] = explode(",", $row[\''.$altNamer.'\']); $arr = array(); for($i=0;$i<sizeof($row[\''.$altNamer.'\']);$i++) { if(isset(self::$cache[\''.$name.'\'][$row[\''.$altNamer.'\'][$i]])) { $find = true; $arr[] = self::$cache[\''.$name.'\'][$row[\''.$altNamer.'\'][$i]]; } } } if($find===false) { $row[\''.$altNamer.'\'] = "{L_\"Не найдено\"}"; } else { $row[\''.$altNamer.'\'] = implode(",", $arr); } }'.PHP_EOL;


			$universalAttributes .= 'if(property_exists($model, "'.$altNamer.'")) { $model->setAttribute("'.$altNamer.'", "Type", "multiple-array"); $model->'.$altNamer.' = explode(",", $model->'.$altNamer.'); $default = array(); $category = array(); $db = self::init_db(); $db->doquery("SELECT * FROM {{'.$name.'}}", true); while($row = $db->fetch_assoc()) { if(in_array($row[\''.$data[$i]['loadDB']['key'].'\'], $model->'.$altNamer.')) { $default[] = $row[\''.$data[$i]['loadDB']['key'].'\']; } $category[$row[\''.$data[$i]['loadDB']['key'].'\']] = array("".$row[\''.$data[$i]['loadDB']['key'].'\'] => $row[\''.$data[$i]['loadDB']['value'].'\']); } $category[\'default\'] = $default; $model->'.$altNamer.' = $category; }'.PHP_EOL;
			$d = 'if(property_exists($model, "'.$altNamer.'") && isset($_POST[\''.$altNamer.'\'])) { $model->'.$altNamer.' = $_POST[\''.$altNamer.'\']; }';
			$universalAttributesTakeAdd .= $d.PHP_EOL;
			$universalAttributesTakeEdit .= $d.PHP_EOL;
			$universalAttributesShow .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
		} else if($data[$i]['type']=="array" && $data[$i]['selectedData']=="dataOnTable") {
			$name = $data[$i]['loadDB']['name'];
			if(defined("PREFIX_DB") && PREFIX_DB!=="") {
				$len = strlen(PREFIX_DB);
				$name = substr($name, $len);
			}
			$universalAttributesShow .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
			$listShild .= 'if(isset($row[\''.$altNamer.'\'])) { $db = self::init_db(); $find = false; if(!isset(self::$cache[\''.$name.'\']) || !is_array(self::$cache[\''.$name.'\']) || sizeof(self::$cache[\''.$name.'\'])==0) { self::$cache[\''.$name.'\'] = array(); $db->doquery("SELECT `'.$data[$i]['loadDB']['key'].'`, `'.$data[$i]['loadDB']['value'].'` FROM {{'.$name.'}}", true); while($rows = $db->fetch_assoc()) { self::$cache[\''.$name.'\'][$rows[\''.$data[$i]['loadDB']['key'].'\']] = $rows[\''.$data[$i]['loadDB']['value'].'\']; } } if(isset(self::$cache[\''.$name.'\']) && isset(self::$cache[\''.$name.'\'][$row[\''.$altNamer.'\']])) { $find = true; $row[\''.$altNamer.'\'] = self::$cache[\''.$name.'\'][$row[\''.$altNamer.'\']]; } if($find===false) { $row[\''.$altNamer.'\'] = "{L_\"Не найдено\"}"; } }'.PHP_EOL;
			$universalAttributes .= 'if(property_exists($model, "'.$altNamer.'")) { $model->setAttribute("'.$altNamer.'", "Type", "array"); $category = array(); $db = self::init_db(); $db->doquery("SELECT * FROM {{'.$name.'}}", true); $default = ""; while($row = $db->fetch_assoc()) { if($model->'.$altNamer.' == $row[\''.$data[$i]['loadDB']['key'].'\']) { $default = $row[\''.$data[$i]['loadDB']['value'].'\']; } $category[$row[\''.$data[$i]['loadDB']['key'].'\']] = $row[\''.$data[$i]['loadDB']['value'].'\']; } $category[\'default\'] = $default; $model->'.$altNamer.' = $category; }'.PHP_EOL;
			$d = 'if(property_exists($model, "'.$altNamer.'") && isset($_POST[\''.$altNamer.'\'])) { $model->'.$altNamer.' = $_POST[\''.$altNamer.'\']; $db = self::init_db(); $db->doquery("SELECT * FROM {{'.$name.'}}", true); while($row = $db->fetch_assoc()) { if($model->'.$altNamer.' == $row[\''.$data[$i]['loadDB']['value'].'\']) { $model->'.$altNamer.' = $row[\''.$data[$i]['loadDB']['key'].'\']; } } }';
			$universalAttributesTakeAdd .= $d.PHP_EOL;
			$universalAttributesTakeEdit .= $d.PHP_EOL;
		} else if($data[$i]['type']=="array" || $data[$i]['type']=="enum") {
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \'enum\');'.PHP_EOL;
			$universalAttributesTakeAdd .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \'enum\');'.PHP_EOL;
			$universalAttributesTakeEdit .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \'enum\');'.PHP_EOL;
		} else {
			$universalAttributesTakeAdd .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
			$universalAttributesTakeEdit .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
			$universalAttributesShow .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
		}
		if($data[$i]['type']!="array" && $data[$i]['type']!="enum") {
			$universalAttributes .= '$model->setAttribute(\''.$altNamer.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
		}
		if(isset($data[$i]['translate'])) {
			$universalAttributes .= '$model->setAttribute(\''.$altTranslateField.'\', \'Type\', \'hidden\');'.PHP_EOL;
			$universalAttributesTakeAdd .= 'if(property_exists($model, "'.$altTranslateField.'")) { $model->'.$altTranslateField.' = ToTranslit($model->'.$altNamer.'); }'.PHP_EOL;
			$universalAttributesTakeEdit .= 'if(property_exists($model, "'.$altTranslateField.'") && empty($model->'.$altTranslateField.')) { $model->'.$altTranslateField.' = ToTranslit($model->'.$altNamer.'); }'.PHP_EOL;
			$createAutoField[$forAutoField] = array("altName" => $altTranslateField, "name" => $altName, "type" => "hidden");//$data[$i]['type']
			$forAutoField++;
			$exclude[$altTranslateField] = "\"".$altTranslateField."\"";
		} else if(isset($data[$i]['hideOnMain'])) {
			$exclude[$first.$altNameField] = "\"".$altNamer."\"";
		}
		if($sufix!=="") {
			if(isset($data[$i]['hideOnMain']) || $ignoreNotFirst!==true) {
				$exclude[$altNamer] = "\"".$altNamer."\"";
			} else {
				$excludeLang[$altNamer] = "\"".$data[$i]['orAltName'].'".lang::get_lg()';
			}
			if(isset($data[$i]['loadDB'])) {
				$createAutoField[] = array("altName" => $altNamer, "name" => $altName.$sufix, "type" => $data[$i]['type'], "loadDB" => $data[$i]['loadDB'], "selectedData" => "dataOnTable", "sort" => $sort);
			} else {
				$createAutoField[] = array("altName" => $altNamer, "name" => $altName.$sufix, "type" => $data[$i]['type'], "sort" => $sort);
			}
		}
		for($l=0;$l<sizeof($langSupport);$l++) {
			lang::Update($langSupport[$l], $altNamer, $data[$i]['name']."&nbsp;".$sufix);
		}
	}

	public static function Check($name) {
		if(empty($name)) {
			throw new Exception("Name module must be set", 1);
			die();
		}
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		if(!file_exists($pathForThisModule)) {
			@mkdir($pathForThisModule, 0777);
		}
		if(!is_writeable($pathForThisModule)) {
			@chmod($pathForThisModule, 0777);
		}
		return file_exists($pathForThisModule."file_".$name.".txt");
	}

	public static function Install($name) {
		if(empty($name)) {
			throw new Exception("Name module must be set", 1);
			die();
		}
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		if(!file_exists($pathForThisModule)) {
			@mkdir($pathForThisModule, 0777);
		}
		if(!is_writeable($pathForThisModule)) {
			@chmod($pathForThisModule, 0777);
		}
		if(!file_exists($pathForThisModule."file_".$name.".txt")) {
			throw new Exception("Module not found", 1);
			die();
		}
		$file = file_get_contents($pathForThisModule."file_".$name.".txt");
		if(!Validate::json($file)) {
			throw new Exception("Module is not correct", 1);
			die();
		}
		$_POST = json_decode($file, true);
		return self::Editor($name, true);
	}

	public static function Remove($name) {
		if(empty($name)) {
			throw new Exception("Name module must be set", 1);
			die();
		}
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		if(!file_exists($pathForThisModule)) {
			@mkdir($pathForThisModule, 0777);
		}
		if(!is_writeable($pathForThisModule)) {
			@chmod($pathForThisModule, 0777);
		}
		if(!file_exists($pathForThisModule."file_".$name.".txt")) {
			throw new Exception("Module not found", 1);
			die();
		}
		$file = file_get_contents($pathForThisModule."file_".$name.".txt");
		if(!Validate::json($file)) {
			throw new Exception("Module is not correct", 1);
			die();
		}
		$_POST = json_decode($file, true);
		return self::Deletes($name, false, true);
	}

	public static function Installed($name) {
		if(empty($name)) {
			throw new Exception("Name module must be set", 1);
			die();
		}
		$altTitleUp = nucfirst($name);
		return file_exists(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX);
	}

	private static function Editor($name = "", $install = false) {
		$dev = false;
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		$pathForReady = dirname(__FILE__).DS;
		if(!file_exists($pathForThisModule)) {
			@mkdir($pathForThisModule, 0777);
		}
		if(!is_writeable($pathForThisModule)) {
			@chmod($pathForThisModule, 0777);
		}
		if(sizeof($_POST)>0) {
			$type = "";
			if(isset($_POST['mode']) && $_POST['mode']=="add") {
				$type = "add";
			} else if(isset($_POST['mode']) && $_POST['mode']=="edit") {
				$type = "edit";
			}
			$langSupport = lang::support(true);
			$archer = file_get_contents($pathForReady."structArcher.txt");
			$model = file_get_contents($pathForReady."structModel.txt");
			$menu = file_get_contents($pathForReady."structMenu.txt");
			$title = $_POST['data']['title'];
			$icon = (isset($_POST['data']['icon']) && !empty($_POST['data']['icon']) ? $_POST['data']['icon'] : "");
			$altTitle = (!empty($name) ? $name : ($_POST['data']['altTitle']));
			for($l=0;$l<sizeof($langSupport);$l++) {
				lang::Update($langSupport[$l], $altTitle, $title);
			}
			$altTitleUp = nucfirst($altTitle);
			$menu = str_replace("{altName}", $altTitleUp, $menu);
			$menu = str_replace("{altLink}", $altTitle, $menu);
			$menu = str_replace("{name}", $title, $menu);
			$menu = str_replace("{icon}", $icon, $menu);
			$first = nsubstr($altTitle, 0, 1);
			if(!is_writeable(PATH_MODULES)) {
				@chmod(PATH_MODULES, 0777);
			}
			$title = $alttitle = $icon = $type_module = $router = $router_method = $route_link = $route_main = $route_sub = "";
			if(isset($_POST['data']['title'])) {
				$title = $_POST['data']['title'];
				unset($_POST['data']['title']);
			}
			if(isset($_POST['data']['altTitle'])) {
				$altTitle = $_POST['data']['altTitle'];
				unset($_POST['data']['altTitle']);
			}
			if(isset($_POST['data']['icon'])) {
				$icon = $_POST['data']['icon'];
				unset($_POST['data']['icon']);
			}
			if(isset($_POST['data']['type_module'])) {
				$type_module = $_POST['data']['type_module'];
				unset($_POST['data']['type_module']);
			}
			if(isset($_POST['data']['route_link'])) {
				$route_link = $_POST['data']['route_link'];
				if(isset($_POST['data'][$route_link])) {
					$_POST['data'][$route_link]['translate'] = "1";
				}
				unset($_POST['data']['route_link']);
			}
			if(isset($_POST['data']['route_main'])) {
				$route_main = $_POST['data']['route_main'];
				unset($_POST['data']['route_main']);
			}
			if(isset($_POST['data']['route_sub'])) {
				$route_sub = $_POST['data']['route_sub'];
				unset($_POST['data']['route_sub']);
			}
			$_POST['data'] = array_values($_POST['data']);
			$_POST['data'] = array_map(function($id, $data) {
				$data['id'] = $id;
				if($data['parent_id']>0) {
					$data['parent_id']--;
				}
				return $data;
			}, array_keys($_POST['data']), $_POST['data']);
			if(file_exists($pathForThisModule."file_".$altTitle.".txt")) {
				$f = file_get_contents($pathForThisModule."file_".$altTitle.".txt");
				$d = json_decode($f, true);
				foreach($d['data'] as $k => $v) {
					foreach($_POST['data'] as $k2 => $v2) {
						/*$arr = array_diff($v, $v2);
						$disabled = $notRemove = false;
						if(isset($arr['disabled'])) {
							unset($arr['disabled']);
							$disabled = true;
						}
						if(isset($arr['notRemove'])) {
							unset($arr['notRemove']);
							$notRemove = true;
						}
						if(sizeof($arr)==0 && $disabled===true) {
							$_POST['data'][$k2]['disabled'] = "disabled";
						} else if(sizeof($arr)==0 && $notRemove===true) {
							$_POST['data'][$k2]['notRemove'] = "notRemove";
						}*/
					}
				}
				unset($d, $f);
			}
			$prefixFile = (defined("PREFIX_DB") && PREFIX_DB!=='' ? PREFIX_DB : "");
			$_POST['data'] = self::convertToHierarchy($_POST['data'], "parent_id");
			$_POST['data'] = array_merge($_POST['data'], array("title" => $title, "altTitle" => $altTitle, "icon" => $icon, "type_module" => $type_module, "route_link" => $route_link, "route_main" => $route_main, "route_sub" => $route_sub));
			if(!$dev) {
				if(file_exists($pathForThisModule."file_".$altTitle.".txt")) { unlink($pathForThisModule."file_".$altTitle.".txt"); }
				file_put_contents($pathForThisModule."file_".$altTitle.".txt", json_encode($_POST));
			}
			$routeAltName = ($_POST['data']['altTitle']);
			unset($_POST['data']['title']);
			unset($_POST['data']['altTitle']);
			unset($_POST['data']['icon']);
			unset($_POST['data']['type_module']);
			unset($_POST['data']['route_link']);
			unset($_POST['data']['route_main']);
			unset($_POST['data']['route_sub']);

			// создание управляющего модуля для указанного раздела
			$data = $_POST['data'];
			$data = array_values($data);
			$listShild = $universalAttributes = $universalAttributesShow = $universalAttributesTakeAdd = $universalAttributesTakeEdit = "";
			$exclude = array();
			$excludeLang = array();
			$createAutoField = array();
			$fieldsForTranslate = array();
			$altLinkField = $altTranslateField = "";
			$data = array_values($data);
			$count=0;
			while(true) {
				if(!isset($data[$count])) {
					break;
				}
				self::workInField($data, $listShild, $universalAttributesTakeAdd, $universalAttributesTakeEdit, $universalAttributes, $universalAttributesShow, $createAutoField, $exclude, $excludeLang, $fieldsForTranslate, $altTranslateField, $altLinkField, $first, $count, $langSupport, $count);
				$count++;
			}
			if(empty($altLinkField)) {
				$altLinkField = $first."Id";
			}
			$prefix = "";
			if(defined("PREFIX_DB")) {
				$pr = PREFIX_DB;
				if(!empty($pr)) {
					$prefix = PREFIX_DB;
				}
			}
			if(!empty($route_main)) {
				$routers = file_get_contents($pathForReady."structRoute.txt");
				$routers = str_replace("{name}", "main_".$altTitle, $routers);
				$route_main = str_replace("%category%", strtolowers($routeAltName), $route_main);
				$routers = str_replace("{route}", $route_main, $routers);
				$routers = str_replace("{method}", "list_".$altTitle, $routers);
				$routers = str_replace("{model}", $altTitle, $routers);
				$router .= $routers;
				$routers = file_get_contents($pathForReady."structMethodMain.txt");
				$routers = str_replace("{method}", "list_".$altTitle, $routers);
				$routers = str_replace("{model}", $altTitle, $routers);
				$router_method .= $routers;
				if(defined("TEMPLATEPATH")) {
					@chmod(TEMPLATEPATH, 0777);
					if(is_writable(TEMPLATEPATH)) {
						$templates = file_get_contents($pathForReady."structMainTemplate.txt");
						$mainFields = $subFields = "";
						if(!empty($route_sub)) {
							$mainFields .= '<a href="{C_default_http_local}{R_[{sub_route}][item={{method}.alt_zagolovok_akcii}]}">'.PHP_EOL;
						}
						for($i=0;$i<sizeof($data);$i++) {
							$dataForField = $data[$i]['name'].' - {{method}.'.$data[$i]['altName'].'}<br>'.PHP_EOL;
			            	$mainFields .= $dataForField;
			            	$subFields .= $dataForField;
			            }
						if(!empty($route_sub)) {
					        $mainFields .= '</a>';
					    }
					    $templates = str_replace("{content}", $mainFields, $templates);
						$templates = str_replace("{method}", "list_".$altTitle, $templates);
					    $templates = str_replace("{sub_route}", "sub_".$altTitle, $templates);
					    $file = TEMPLATEPATH."list_".$altTitle.".default.".templates::changeTypeTpl();
						@chmod($file, 0777);
					    @file_put_contents($file, $templates);
					}
				}
			} else {
				if(defined("TEMPLATEPATH")) {
					@chmod(TEMPLATEPATH, 0777);
					if(is_writable(TEMPLATEPATH)) {
					    $file = TEMPLATEPATH."list_".$altTitle.".default.".templates::changeTypeTpl();
						@chmod($file, 0777);
						@unlink($file);
					}
				}
			}
			if(!empty($route_sub)) {
				$routers = file_get_contents($pathForReady."structRoute.txt");
				$routers = str_replace("{name}", "sub_".$altTitle, $routers);
				$route_sub = str_replace("%category%", strtolower($routeAltName), $route_sub);
				$route_sub = str_replace("%item%", "<item>", $route_sub);
				$routers = str_replace("{route}", $route_sub, $routers);
				$routers = str_replace("{method}", "details_".$altTitle, $routers);
				$routers = str_replace("{model}", $altTitle, $routers);
				$router .= $routers;
				$routers = file_get_contents($pathForReady."structMethodDetail.txt");
				$routers = str_replace("{id}", $altLinkField, $routers);
				$routers = str_replace("{method}", "details_".$altTitle, $routers);
				$routers = str_replace("{model}", $altTitle, $routers);
				$router_method .= $routers;
				if(defined("TEMPLATEPATH")) {
					@chmod(TEMPLATEPATH, 0777);
					if(is_writable(TEMPLATEPATH)) {
						$templates = file_get_contents($pathForReady."structSubTemplate.txt");
					    $templates = str_replace("{content}", $subFields, $templates);
						$templates = str_replace("{method}", "list_".$altTitle, $templates);
					    $templates = str_replace("{sub_route}", "sub_".$altTitle, $templates);
					    $file = TEMPLATEPATH."details_".$altTitle."_detail.default.".templates::changeTypeTpl();
						@chmod($file, 0777);
					    @file_put_contents($file, $templates);
					}
				}
			} else {
				if(defined("TEMPLATEPATH")) {
					@chmod(TEMPLATEPATH, 0777);
					if(is_writable(TEMPLATEPATH)) {
					    $file = TEMPLATEPATH."details_".$altTitle."_detail.default.".templates::changeTypeTpl();
						@chmod($file, 0777);
						@unlink($file);
					}
				}
			}

			$exclude = array_unique($exclude);
			$exclude = array_values($exclude);
			$excludeLang = array_unique($excludeLang);
			$excludeLang = array_values($excludeLang);
			$exclude = array_merge($exclude, $excludeLang);
			$exclude[] = '"createdTime"';
			$exclude[] = '"editedTime"';
			$archer = str_replace("{router}", trim($router), $archer);
			$archer = str_replace("{router_method}", trim($router_method), $archer);
			$archer = str_replace("{universalAttributes}", trim($universalAttributes), $archer);
			$archer = str_replace("{universalAttributesShow}", trim($universalAttributesShow), $archer);
			$archer = str_replace("{universalAttributesTakeAdd}", trim($universalAttributesTakeAdd), $archer);
			$archer = str_replace("{universalAttributesTakeEdit}", trim($universalAttributesTakeEdit), $archer);
			$archer = str_replace("{listShild}", trim($listShild), $archer);
			$archer = str_replace("{exclude}", implode(", ", $exclude), $archer);
			$archer = str_replace("{altTitle}", $altTitleUp, $archer);
			$archer = str_replace("{title}", (!empty($prefix) ? $prefix : "").$altTitle, $archer);
			if(!is_writeable(PATH_MODULES)) {
				@chmod(PATH_MODULES, 077);
			}
			if(!$dev) {
				if(file_exists(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX)) { unlink(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX); }
				file_put_contents(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX, $archer);

				// добавление модуля созданного ранее в загрузчик
				$file = "";
				if(file_exists(PATH_MODULES."loader.".ROOT_EX)) {
					if(!is_writeable(PATH_MODULES)) {
						@chmod(PATH_MODULES, 077);
					}
					if(!is_writeable(PATH_MODULES."loader.".ROOT_EX)) {
						@chmod(PATH_MODULES."loader.".ROOT_EX, 077);
					}
					$file = PATH_MODULES."loader.".ROOT_EX;
					$files = file_get_contents($file);
					$add = '"application".DS."modules".DS."'.$altTitleUp.'Archer.class.".ROOT_EX';
					if(strpos($files, $add)===false) {
						$files = str_replace('$modulesLoad = array_merge($modulesLoad, array(', '$modulesLoad = array_merge($modulesLoad, array('.PHP_EOL.$add.' => true,', $files);
						file_put_contents($file, $files);
					}
				}
				if(file_exists(PATH_MODULES."loader.default.".ROOT_EX)) {
					if(!is_writeable(PATH_MODULES)) {
						@chmod(PATH_MODULES, 077);
					}
					if(!is_writeable(PATH_MODULES."loader.default.".ROOT_EX)) {
						@chmod(PATH_MODULES."loader.default.".ROOT_EX, 077);
					}
					$file = PATH_MODULES."loader.default.".ROOT_EX;
					$files = file_get_contents($file);
					$add = '"application".DS."modules".DS."'.$altTitleUp.'Archer.class.".ROOT_EX';
					if(strpos($files, $add)===false) {
						$files = str_replace('$modulesLoad = array_merge($modulesLoad, array(', '$modulesLoad = array_merge($modulesLoad, array('.PHP_EOL.$add.' => true,', $files);
						file_put_contents($file, $files);
					}
				}
			}

			// построение модели данных
			$model = str_replace("{altTitle}", $altTitleUp, $model);
			$dataFirst = $first."Id";
			$data[-1]['altName'] = $dataFirst;
			$data[-1]['type'] = "int";
			$data[-1]['name'] = "id";
			$data[-1]['auto_increment'] = true;
			$data[-1]['sort'] = -1;
			$data[$count]['altName'] = 'createdTime';
			$data[$count]['type'] = 'createdTime';
			$data[$count]['name'] = 'createdTime';
			$data[$count]['sort'] = $count;
			$data[($count+1)]['altName'] = 'editedTime';
			$data[($count+1)]['type'] = 'editedTime';
			$data[($count+1)]['name'] = 'editedTime';
			$data[($count+1)]['sort'] = ($count+1);
			$data = self::combineFields($data, $createAutoField);
			//$data = array_merge($data, $createAutoField);
			$datas = array_map(("self::createFields"), $data);
			//sortByValue($data);
			$model = str_replace("{fields}", implode(PHP_EOL, $datas), $model);
			if(!is_writeable(PATH_MODELS)) {
				@chmod(PATH_MODELS, 077);
			}
			if(!$dev) {
				if(file_exists(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX)) { unlink(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX); }
				file_put_contents(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX, $model);
			}
			$edit = db::getTables(true, true);
			$dbName = $prefix.$altTitle;
			if(isset($edit[$dbName])) {
				$fall = self::getB($data, false);
				$struct = array_map("self::createFieldsDB", $data, $fall);
				$langSupport = array_map("nucfirst", $langSupport);
				$structClear = $structClearForRemove = array();
				for($i=0;$i<sizeof($struct);$i++) {
					$k = key($struct[$i]);
					$v = current($struct[$i]);
					$ks = str_replace($langSupport, "", $k);
					$structClear[$k] = array("v" => $v, "orName" => $ks, "name" => $data[$i]['name'], "default" => (isset($data[$i]['default']) ? $data[$i]['default']: ""));
					if(isset($data[$i]['field'])) {
						$structClear[$k]['field'] = $data[$i]['field'];
					}
					$structClearForRemove[$k] = true;
					$structClearForRemove[$ks] = true;
				}
				execEvent("creator_get_clear_struct", $dbName, $structClear);
				$forUpdate = array("add" => array(), "edit" => array(), "remove" => array());

				$firstLang = lang::get_lg();
				$firstLang = nucfirst($firstLang);
				$autoIncrement = array_map(function($item) {
					if(isset($item['auto_increment'])) {
						return $item['altName'];
					}
				}, $data);
				$autoIncrement = array_filter($autoIncrement);

				$groupFieldsForTranslate = array();
				foreach($structClear as $k => $v) {
					if(!isset($edit[$dbName][$k]) && isset($edit[$dbName][$v['orName']])) {
						if(strrpos($k, $firstLang)!==false) {
							if(!isset($groupFieldsForTranslate[$v['orName']])) {
								$groupFieldsForTranslate[$v['orName']] = array();
							}
							$groupFieldsForTranslate[$v['orName']]["first"] = $k;
							$forUpdate['edit'][$k] = array("altName" => $k, "orName" => $v['orName'], "name" => $v['name'], "type" => $v['v'], "default" => $v['default']);
							$structClearForRemove[$k] = true;
							$structClearForRemove[$v['orName']] = true;
						} else {
							$forUpdate['add'][$k] = array("altName" => $k, "orName" => $k, "name" => $v['name'], "type" => $v['v'], "default" => $v['default']);
							if(!isset($groupFieldsForTranslate[$v['orName']])) {
								$groupFieldsForTranslate[$v['orName']] = array();
							}
							if(!isset($groupFieldsForTranslate[$v['orName']]["children"])) {
								$groupFieldsForTranslate[$v['orName']]["children"] = array();
							}
							$groupFieldsForTranslate[$v['orName']]["children"][] = $k;
						}
					} else if(isset($edit[$dbName][$k]) && $edit[$dbName][$k]!=$v['v']) {
						$forUpdate['edit'][$k] = array("altName" => $k, "orName" => $v['orName'], "name" => $v['name'], "type" => $v['v'], "default" => $v['default']);
						if(isset($v['field'])) {
							$forUpdate['edit'][$k]['field'] = $v['field'];
						}
						if(in_array($k, $autoIncrement)) { $forUpdate['edit'][$k]['auto_increment'] = true; }
					} else if(!isset($edit[$dbName][$k])) {
						$forUpdate['add'][$k] = array("altName" => $k, "orName" => $k, "name" => $v['name'], "type" => $v['v'], "default" => $v['default']);
						if(isset($v['field'])) {
							$forUpdate['add'][$k]['field'] = $v['field'];
						}
						if(in_array($k, $autoIncrement)) { $forUpdate['add'][$k]['auto_increment'] = true; }
					}
				}
				foreach($edit[$dbName] as $k => $v) {
					$withoutLang = str_replace($langSupport, "", $k);
					if(!isset($structClearForRemove[$k])) {
						if(strpos($k, $firstLang)!==false) {
							if(isset($forUpdate['add'][$withoutLang])) {
								$tmp = $forUpdate['add'][$withoutLang];
								unset($forUpdate['add'][$withoutLang]);
								$forUpdate['edit'][$k] = array("altName" => $withoutLang, "orName" => $k, "type" => $v);
								if(in_array($k, $autoIncrement)) { $forUpdate['edit'][$k]['auto_increment'] = true; }
							}
						} else {
							$forUpdate['remove'][$k] = array("altName" => $k, "orName" => $k, "type" => $v);
							if(in_array($k, $autoIncrement)) { $forUpdate['remove'][$k]['auto_increment'] = true; }
						}
					}
				}
				$rename = self::changeFieldByName($data);
				foreach($forUpdate['add'] as $k => $v) {
					if(isset($rename[$k])) {
						$v['orName'] = $rename[$k];
						$forUpdate['edit'][$rename[$k]] = $v;
						unset($forUpdate['add'][$k]);
					}
				}
				$rename = array_flip($rename);
				foreach($forUpdate['remove'] as $k => $v) {
					if(isset($rename[$v['altName']])) {
						unset($forUpdate['remove'][$v['altName']]);
					}
				}
				if(sizeof($forUpdate['add'])>0) {
					$fall = self::getB($forUpdate['add'], false);
					$true = self::getB($forUpdate['add'], true);
					$forUpdate['add'] = array_map("self::createFieldsDB", $forUpdate['add'], $true, $fall, $true);
				}
				if(sizeof($forUpdate['edit'])>0) {
					$fall = self::getB($forUpdate['edit'], false);
					$true = self::getB($forUpdate['edit'], true);
					$forUpdate['edit'] = array_map("self::createFieldsDB", $forUpdate['edit'], $true, $fall);
				}
				if(sizeof($forUpdate['remove'])>0) {
					$fall = self::getB($forUpdate['remove'], false);
					$forUpdate['remove'] = array_map("self::createFieldsDB", $forUpdate['remove'], $fall);
				}
				$afterSQL = array();
				$keys = array_keys($groupFieldsForTranslate);
				for($i=0;$i<sizeof($keys);$i++) {
					if(isset($groupFieldsForTranslate[$keys[$i]]) && isset($groupFieldsForTranslate[$keys[$i]]['children']) && isset($groupFieldsForTranslate[$keys[$i]]['first']) && is_array($groupFieldsForTranslate[$keys[$i]]['children']) && sizeof($groupFieldsForTranslate[$keys[$i]]['children'])>0) {
						for($z=0;$z<sizeof($groupFieldsForTranslate[$keys[$i]]['children']);$z++) {
							$afterSQL[] = "UPDATE {{".$altTitle."}} SET `".$groupFieldsForTranslate[$keys[$i]]['children'][$z]."` = `".$groupFieldsForTranslate[$keys[$i]]['first']."`";
						}
					}
				}
				foreach($forUpdate['add'] as $key => $value) {
					modules::add_fields($altTitle, $value);
				}
				foreach($forUpdate['edit'] as $key => $value) {
					modules::modify_fields($altTitle, $value);
				}
				foreach($forUpdate['remove'] as $key => $value) {
					modules::remove_fields($altTitle, array_flip($value));
				}
				if(sizeof($afterSQL)>0) {
					for($i=0;$i<sizeof($afterSQL);$i++) {
						db::doquery($afterSQL[$i], true);
					}
				}
				execEvent("creator_alter_section", $altTitle, $forUpdate);
			} else {
				$db = implode(",".PHP_EOL, array_map("self::createFieldsDB", $data));
				$db .= ",".PHP_EOL."primary key `id`(`".$dataFirst."`)";
				modules::create_table($altTitle, $db, true);
				execEvent("creator_new_section", $altTitle, $data, $db);
			}

			if(!is_writeable(ADMIN_MENU)) {
				@chmod(ADMIN_MENU, 077);
			}
			if(file_exists(ADMIN_MENU.$altTitle.".main.".ROOT_EX)) { unlink(ADMIN_MENU.$altTitle.".main.".ROOT_EX); }
			file_put_contents(ADMIN_MENU.$altTitle.".main.".ROOT_EX, $menu);
			if(!$install) {
				location("./?pages=Creator");
			}
			return true;
		}
		$file = "";
		if(!empty($name) && file_exists($pathForThisModule."file_".$name.".txt")) {
			$file = file_get_contents($pathForThisModule."file_".$name.".txt");
		}
		templates::assign_var("struct", $file);
		(new Core)->Prints("Creator/CreatorEdit");
	}

	private static function changeFieldByName($data) {
		$rename = array();
		for($i=1;$i<sizeof($data);$i++) {
			if(isset($data[$i]['beforeAltName']) && $data[$i]['beforeAltName']!=$data[$i]['altName']) {
				$rename[$data[$i]['altName']] = $data[$i]['beforeAltName'];
			}
		}
		return $rename;
	}

	private static function removePrefix($data, $prefix) {
		return str_replace($prefix, "", $data);
	}

	private static function createFields($struct) {
		return 'public $'.$struct['altName'].';';
	}

	private static function addSlash($data) {
		return "'".$data."'";
	}

	private static function createFieldsDB($struct, $isDB = true, $withName = true, $isAdd = false) {
		$type = $struct['type'];
		$event = execEvent("creator_create_fields_db", false, $type, $struct, $isDB, $withName, $isAdd);
		if($event!==false) {
			$type = $event;
		} else if($type=="linkToAdmin") {
			$type = "int".($isDB ? "(1)" : "");
		} else if($isDB && $type=="radio") {
			$type = "enum".($isDB ? "(".implode(",", array_map(("self::addSlash"), $struct['field'])).")" : "");
		} else if($type=="array" && $struct['selectedData']=="dataOnTable") {
			$name = $struct['loadDB']['name'];
			$key = $struct['loadDB']['key'];
			$datas = db::getTables(true, true, true);
			$type = false;
			if(isset($datas[$name])) {
				$datas = $datas[$name];
				$type = (isset($datas[$key]) ? $datas[$key] : false);
			}
		} else if($type=="multiple-array" && $struct['selectedData']=="dataOnTable") {
			$name = $struct['loadDB']['name'];
			$key = $struct['loadDB']['key'];
			$datas = db::getTables(true, true, true);
			$type = false;
			if(isset($datas[$name])) {
				$type = "longtext";
			}
		} else if($type=="multiple-array" && $struct['selectedData']=="dataOnInput") {
			$type = "longtext";
		} else if($type=="enum" || ($type=="array" && $struct['selectedData']=="dataOnInput")) {
			$type = "enum".($isDB ? "(".implode(",", array_map(("self::addSlash"), $struct['field'])).")" : "");
		} else if($type=="int" || $type=="createdTime" || $type=="editedTime") {
			$type = "int".($isDB ? "(11)" : "");
		} else if($type=="varchar") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="email") {
			$type =  "varchar".($isDB ? "(255)" : "");
		} else if($type=="password") {
			$type =  "varchar".($isDB ? "(255)" : "");
		} else if($type=="link") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="onlytextareatext") {
			$type = "longtext";
		} else if($type=="image") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="imageArray") {
			$type = "longtext";
		} else if($type=="imageAccess") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="imageArrayAccess") {
			$type = "longtext";
		} else if($type=="file") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="fileArray") {
			$type = "longtext";
		} else if($type=="fileArrayAccess") {
			$type = "longtext";
		} else if($type=="date") {
			$type = "int".($isDB ? "(11)" : "");
		} else if($type=="time") {
			$type = "int".($isDB ? "(11)" : "");
		} else if($type=="datetime") {
			$type = "int".($isDB ? "(11)" : "");
		} else if($type=="int") {
			$type = "int".($isDB ? "(11)" : "");
		} else if($type=="float") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="price") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="hidden") {
			$type = "varchar".($isDB ? "(255)" : "");
		}
		$auto_increment = "";
		if(isset($struct['auto_increment'])) {
			$auto_increment = " auto_increment";
		}
		if($isAdd === false && $withName===false) {
			$return = array($struct['altName'] => (isset($struct['orName']) ? array("orName" => $struct['orName'], "comment" => $struct['name'], $type) : $type));
		} else if($isAdd !== false && $withName===false) {
			$return = array($struct['altName'] => $type." not null".$auto_increment.(isset($struct['default']) && !empty($struct['default']) ? " DEFAULT ".db::escape($struct['default']) : "")." COMMENT ".db::escape($struct['name']));
		} else if($isDB) {
			$return = '`'.$struct['altName'].'` '.$type.' not null'.$auto_increment.(isset($struct['default']) && !empty($struct['default']) ? " DEFAULT ".db::escape($struct['default']) : "")." COMMENT ".db::escape($struct['name']);
		} elseif($withName===true) {
			$return = array($struct['altName'] => $type);
		} else {
			$arr = array("value" => $type);
			(isset($struct['name']) ? $arr['comment'] = $struct['name'] : "");
			$return = array($struct['altName'] => $arr);
		}
		return $return;
	}

	private static function getB($data, $type) {
		$true = array();
		for($i=0;$i<sizeof($data);$i++) {
			$true[] = $type;
		}
		return $true;
	}

}
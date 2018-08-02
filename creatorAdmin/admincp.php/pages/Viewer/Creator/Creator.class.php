<?php

class Creator extends Core {

	function __construct() {
		if(isset($_GET['list'])) {
			$file = file_get_contents(ROOT_PATH.ADMINCP_DIRECTORY.DS."assets".DS.config::Select("skins", "admincp").DS."css".DS."fonts".DS."fontawesome".DS."css".DS."font-awesome.css");
			preg_match_all("#\.fa-(.+?)\:before#", $file, $arr);
			$arr = $arr[1];
			$ret = "<input type=\"search\" class=\"form-control icon-find\" placeholder=\"input for quick search\">";
			$ret .= "<a href=\"#\" class=\"selectIcon pull-left\" data-icon=\"\"><i class=\"fa fa-stack fa-fw fa-2x\" style=\"font-size:2em!important;border:0.01em solid #333;width:1em;height:1em;margin:0px 0.5em;\"></i></a>";
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
				$this->Editor();
				$ret = "1";
			} else if($mod=="Edit" && ($name = $req->get->get("name", false)) !== false) {
				$this->Editor($name);
				$ret = "1";
			} else if($mod=="Delete" && ($name = $req->get->get("name", false)) !== false) {
				$this->Deletes($name);
				$ret = "1";
			} else if($mod=="MultiAction") {
				$this->MultiAction();
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
		$dbs = array_map(array($this, "removePrefix"), $dbs, $prefix);
		for($i=0;$i<sizeof($dbs);$i++) {
			if(file_exists($pathForThisModule."file_".$dbs[$i].".txt")) {
				templates::assign_vars(array("table" => $dbs[$i]), "creator", "c".$i);
			}
		}
		//vdebug($showMe);die();
		$this->Prints("Creator/CreatorMain");
	}

	function Deletes($name, $ret = true) {
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
		if(file_exists($pathForThisModule."file_".$name.".txt")) { unlink($pathForThisModule."file_".$name.".txt"); }
		if(file_exists(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX)) { unlink(PATH_MODULES.$altTitleUp."Archer.class.".ROOT_EX); }
		if(file_exists(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX)) { unlink(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX); }
		if(file_exists(ADMIN_MENU.$name.".main.".ROOT_EX)) { unlink(ADMIN_MENU.$name.".main.".ROOT_EX); }
		if($ret) {
			location("./?pages=Creator");
			return false;
		}
	}

	function MultiAction() {
		$pathForThisModule = PATH_CACHE_USERDATA."struct".DS;
		$req = new Request();
		if(($deletes = $req->post->get("delete", false))!==false) {
			for($i=0;$i<sizeof($deletes);$i++) {
				$this->Deletes($deletes[$i], false);
			}
		}
		location("./?pages=Creator");
		return false;
	}

	function combineFields($data1, $data2) {
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
		for($i=0;$i<sizeof($data2);$i++) {
			$res[] = $data2[$i];
		}
		$resC = array();
		for($i=0;$i<sizeof($res);$i++) {
			$resC[$res[$i]['altName']] = $res[$i];
		}
		$res = array_values($resC);
		return $res;
	}

	function workInField(&$data, &$listShild, &$universalAttributesTakeAdd, &$universalAttributesTakeEdit, &$universalAttributes, &$universalAttributesShow, &$createAutoField, &$exclude, &$fieldsForTranslate, $first, $i, $langSupport, $sufix = "", $ignoreNotFirst = true, $supportLang = false) {
		$altNameField = nucfirst(ToTranslit($data[$i]['name'], false, false, true));
		if(isset($data[$i]['supportLang'])) {
			$fieldsForTranslate[] = $first.$altNameField.$sufix;
			$upper = array_map("nucfirst", $langSupport);
			$supportLang = true;
			unset($data[$i]['supportLang']);
			for($z=0;$z<sizeof($upper);$z++) {
				$this->workInField($data, $listShild, $universalAttributesTakeAdd, $universalAttributesTakeEdit, $universalAttributes, $universalAttributesShow, $createAutoField, $exclude, $fieldsForTranslate, $first, $i, $langSupport, $upper[$z], ($z===0), $supportLang);
			}
			return;
		}
		$data[$i]['altName'] = $first.$altNameField.$sufix;
		if($data[$i]['type']=="image") {
			$listShild .= 'if(isset($row[\''.$first.$altNameField.$sufix.'\'])) { $row[\''.$first.$altNameField.'\'] = "<img src=\"{C_default_http_local}".$row[\''.$first.$altNameField.$sufix.'\']."\" width=\"200\">"; }';
		}
		$altName = "";
		if(isset($data[$i]['alttitle'])) {
			$altName = $data[$i]['alttitle'];
			$altTranslateField = nucfirst(ToTranslit($data[$i]['alttitle'], false, false, true));
		} else if(isset($data[$i]['name'])) {
			$altName = $data[$i]['name'];
			$altTranslateField = nucfirst(ToTranslit($data[$i]['name'], false, false, true));
		}
		if($supportLang === true) {
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Attr\', \'supportLang\');'.PHP_EOL;
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Lang\', \''.$sufix.'\');'.PHP_EOL;
		}
		if(isset($data[$i]['placeholder']) && !empty($data[$i]['placeholder'])) {
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'placeholder\', \''.$data[$i]['placeholder'].'\');'.PHP_EOL;
		}
		if($data[$i]['type']=="linkToAdmin") {
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \'linkToAdmin\');'.PHP_EOL;
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'linkLink\', \''.$data[$i]['field']['link'].'\');'.PHP_EOL;
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'titleLink\', \''.$data[$i]['field']['title'].'\');'.PHP_EOL;
		} else if($data[$i]['type']=="systime") {
			$universalAttributesTakeAdd .= 'if(isset($model->'.$first.$altNameField.$sufix.')) { $model->'.$first.$altNameField.$sufix.' = $model->Time(); }'.PHP_EOL;
			$universalAttributesShow .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \'datetime\');'.PHP_EOL;
			$data[$i]['type'] = "hidden";
		} else if($data[$i]['type']=="array" && $data[$i]['selectedData']=="dataOnTable") {
			$name = $data[$i]['loadDB']['name'];
			if(defined("PREFIX_DB") && PREFIX_DB!=="") {
				$len = strlen(PREFIX_DB);
				$name = substr($name, $len);
			}
			$universalAttributes .= 'if(isset($model->'.$first.$altTranslateField.$sufix.')) { $model->setAttribute("'.$first.$altTranslateField.$sufix.'", "Type", "array"); $category = array(); $db = self::init_db(); $db->doquery("SELECT * FROM {{'.$name.'}}", true); $default = ""; while($row = $db->fetch_assoc()) { if($model->'.$first.$altTranslateField.$sufix.' == $row[\''.$data[$i]['loadDB']['key'].'\']) { $default = $row[\''.$data[$i]['loadDB']['value'].'\']; } $category[] = $row[\''.$data[$i]['loadDB']['value'].'\']; } $category[\'default\'] = $default; $model->'.$first.$altTranslateField.$sufix.' = $category; }'.PHP_EOL;
			$d = 'if(isset($model->'.$first.$altTranslateField.$sufix.') && isset($_POST[\''.$first.$altTranslateField.$sufix.'\'])) { $model->'.$first.$altTranslateField.$sufix.' = $_POST[\''.$first.$altTranslateField.$sufix.'\']; $db = self::init_db(); $db->doquery("SELECT * FROM {{'.$name.'}}", true); while($row = $db->fetch_assoc()) { if($model->'.$first.$altTranslateField.$sufix.' == $row[\''.$data[$i]['loadDB']['value'].'\']) { $model->'.$first.$altTranslateField.$sufix.' = $row[\''.$data[$i]['loadDB']['key'].'\']; } } }';
			$universalAttributesTakeAdd .= $d.PHP_EOL;
			$universalAttributesTakeEdit .= $d.PHP_EOL;
		} else if($data[$i]['type']=="array" || $data[$i]['type']=="enum") {
			$universalAttributes .= '$model->setAttribute(\''.$first.$altTranslateField.'\', \'Type\', \'enum\');'.PHP_EOL;
			$universalAttributesTakeAdd .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \'enum\');'.PHP_EOL;
			$universalAttributesTakeEdit .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \'enum\');'.PHP_EOL;
		} else {
			$universalAttributesTakeAdd .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
			$universalAttributesTakeEdit .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
			$universalAttributesShow .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
		}
		if($data[$i]['type']!="array" && $data[$i]['type']!="enum") {
			$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.$sufix.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
		}
		if(isset($data[$i]['translate']) && $ignoreNotFirst===true) {
			$universalAttributes .= '$model->setAttribute(\''.$first.$altTranslateField.'\', \'Type\', \'hidden\');'.PHP_EOL;
			$universalAttributesTakeAdd .= 'if(isset($model->'.$first.$altTranslateField.')) { $model->'.$first.$altTranslateField.' = ToTranslit($model->'.$first.$altNameField.$sufix.'); }'.PHP_EOL;
			$universalAttributesTakeEdit .= 'if(isset($model->'.$first.$altTranslateField.') && empty($model->'.$first.$altTranslateField.')) { $model->'.$first.$altTranslateField.' = ToTranslit($model->'.$first.$altNameField.$sufix.'); }'.PHP_EOL;
			$createAutoField[$i] = array("altName" => $first.$altTranslateField, "name" => $altName, "type" => "hidden");//$data[$i]['type']
			$i++;
			$exclude[$first.$altTranslateField] = "\"".$first.$altTranslateField."\"";
		}
		if($sufix!=="" && $ignoreNotFirst===true) {
			$exclude[$first.$altNameField.$sufix] = "\"".$first.$altNameField.$sufix."\"";
			if(isset($data[$i]['loadDB'])) {
				$createAutoField[$i] = array("altName" => $first.$altNameField.$sufix, "name" => $altName.$sufix, "type" => $data[$i]['type'], "loadDB" => $data[$i]['loadDB'], "selectedData" => "dataOnTable");
			} else {
				$createAutoField[$i] = array("altName" => $first.$altNameField.$sufix, "name" => $altName.$sufix, "type" => $data[$i]['type']);
			}
		}
		for($l=0;$l<sizeof($langSupport);$l++) {
			lang::Update($langSupport[$l], $first.$altNameField.$sufix, $data[$i]['name']."&nbsp;".$sufix);
		}
		if(isset($data[$i]['hideOnMain'])) {
			$exclude[$first.$altNameField] = "\"".$first.$altNameField.$sufix."\"";
		}
	}

	function Editor($name = "") {
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
			$altTitle = (!empty($name) ? $name : ToTranslit($_POST['data']['title']));
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
				@chmod(PATH_MODULES, 077);
			}
			$title = $icon = "";
			if(isset($_POST['data']['title'])) {
				$title = $_POST['data']['title'];
				unset($_POST['data']['title']);
			}
			if(isset($_POST['data']['icon'])) {
				$icon = $_POST['data']['icon'];
				unset($_POST['data']['icon']);
			}
			$_POST['data'] = array_values($_POST['data']);
			$_POST['data'] = array_merge($_POST['data'], array("title" => $title, "icon" => $icon));
			if(!$dev) {
				if(file_exists($pathForThisModule."file_".$altTitle.".txt")) { unlink($pathForThisModule."file_".$altTitle.".txt"); }
				file_put_contents($pathForThisModule."file_".$altTitle.".txt", json_encode($_POST));
			}
			unset($_POST['data']['title']);
			unset($_POST['data']['icon']);

			// создание управляющего модуля для указанного раздела
			$data = $_POST['data'];
			$data = array_values($data);
			$listShild = $universalAttributes = $universalAttributesShow = $universalAttributesTakeAdd = $universalAttributesTakeEdit = "";
			$exclude = array();
			$createAutoField = array();
			$fieldsForTranslate = array();
			$data = array_values($data);
			for($i=0;$i<sizeof($data);$i++) {
				$this->workInField($data, $listShild, $universalAttributesTakeAdd, $universalAttributesTakeEdit, $universalAttributes, $universalAttributesShow, $createAutoField, $exclude, $fieldsForTranslate, $first, $i, $langSupport);
			}
			$exclude = array_unique($exclude);
			$exclude = array_values($exclude);
			$archer = str_replace("{universalAttributes}", trim($universalAttributes), $archer);
			$archer = str_replace("{universalAttributesShow}", trim($universalAttributesShow), $archer);
			$archer = str_replace("{universalAttributesTakeAdd}", trim($universalAttributesTakeAdd), $archer);
			$archer = str_replace("{universalAttributesTakeEdit}", trim($universalAttributesTakeEdit), $archer);
			$archer = str_replace("{listShild}", trim($listShild), $archer);
			$archer = str_replace("{exclude}", implode(", ", $exclude), $archer);
			$archer = str_replace("{altTitle}", $altTitleUp, $archer);
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
			sortByKey($data);
			$data = $this->combineFields($data, $createAutoField);
			//$data = array_merge($data, $createAutoField);
			$model = str_replace("{fields}", implode(PHP_EOL, array_map(array($this, "createFields"), $data)), $model);
			if(!is_writeable(PATH_MODELS)) {
				@chmod(PATH_MODELS, 077);
			}
			if(!$dev) {
				if(file_exists(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX)) { unlink(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX); }
				file_put_contents(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX, $model);
			}
			$prefix = "";
			if(defined("PREFIX_DB")) {
				$pr = PREFIX_DB;
				if(!empty($pr)) {
					$prefix = PREFIX_DB;
				}
			}
			$edit = db::getTables(true, true);
			$dbName = $prefix.$altTitle;
			if(isset($edit[$dbName])) {
				$fall = $this->getB($data, false);
				$struct = array_map(array($this, "createFieldsDB"), $data, $fall);
				$langSupport = array_map("nucfirst", $langSupport);
				$structClear = $structClearForRemove = array();
				for($i=0;$i<sizeof($struct);$i++) {
					$k = key($struct[$i]);
					$v = current($struct[$i]);
					$ks = str_replace($langSupport, "", $k);
					$structClear[$k] = array("v" => $v, "orName" => $ks, "name" => $data[$i]['name']);
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

				$groupFieldsForTranslate = array();
				foreach($structClear as $k => $v) {
					if(!isset($edit[$dbName][$k]) && isset($edit[$dbName][$v['orName']])) {
						if(strrpos($k, $firstLang)!==false) {
							if(!isset($groupFieldsForTranslate[$v['orName']])) {
								$groupFieldsForTranslate[$v['orName']] = array();
							}
							$groupFieldsForTranslate[$v['orName']]["first"] = $k;
							$forUpdate['edit'][$k] = array("altName" => $k, "orName" => $v['orName'], "name" => $v['name'], "type" => $v['v']);
							$structClearForRemove[$k] = true;
							$structClearForRemove[$v['orName']] = true;
						} else {
							$forUpdate['add'][$k] = array("altName" => $k, "orName" => $k, "name" => $v['name'], "type" => $v['v']);
							if(!isset($groupFieldsForTranslate[$v['orName']])) {
								$groupFieldsForTranslate[$v['orName']] = array();
							}
							if(!isset($groupFieldsForTranslate[$v['orName']]["children"])) {
								$groupFieldsForTranslate[$v['orName']]["children"] = array();
							}
							$groupFieldsForTranslate[$v['orName']]["children"][] = $k;
						}
					} else if(isset($edit[$dbName][$k]) && $edit[$dbName][$k]!=$v['v']) {
						$forUpdate['edit'][$k] = array("altName" => $k, "orName" => $v['orName'], "name" => $v['name'], "type" => $v['v']);
						if(isset($v['field'])) {
							$forUpdate['edit'][$k]['field'] = $v['field'];
						}
					} else if(!isset($edit[$dbName][$k])) {
						$forUpdate['add'][$k] = array("altName" => $k, "orName" => $k, "name" => $v['name'], "type" => $v['v']);
						if(isset($v['field'])) {
							$forUpdate['add'][$k]['field'] = $v['field'];
						}
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
							}
						} else {
							$forUpdate['remove'][$k] = array("altName" => $k, "orName" => $k, "type" => $v);
						}
					}
				}
				if(sizeof($forUpdate['add'])>0) {
					$fall = $this->getB($forUpdate['add'], false);
					$true = $this->getB($forUpdate['add'], true);
					$forUpdate['add'] = array_map(array($this, "createFieldsDB"), $forUpdate['add'], $true, $fall, $true);
				}
				if(sizeof($forUpdate['edit'])>0) {
					$fall = $this->getB($forUpdate['edit'], false);
					$true = $this->getB($forUpdate['edit'], true);
					$forUpdate['edit'] = array_map(array($this, "createFieldsDB"), $forUpdate['edit'], $true, $fall);
				}
				if(sizeof($forUpdate['remove'])>0) {
					$fall = $this->getB($forUpdate['remove'], false);
					$forUpdate['remove'] = array_map(array($this, "createFieldsDB"), $forUpdate['remove'], $fall);
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
				$db = implode(",".PHP_EOL, array_map(array($this, "createFieldsDB"), $data));
				$db .= ",".PHP_EOL."primary key `id`(`".$dataFirst."`)";
				modules::create_table($altTitle, $db, true);
				execEvent("creator_new_section", $altTitle, $data, $db);
			}

			if(!is_writeable(ADMIN_MENU)) {
				@chmod(ADMIN_MENU, 077);
			}
			if(file_exists(ADMIN_MENU.$altTitle.".main.".ROOT_EX)) { unlink(ADMIN_MENU.$altTitle.".main.".ROOT_EX); }
			file_put_contents(ADMIN_MENU.$altTitle.".main.".ROOT_EX, $menu);
			location("./?pages=Creator");
			return false;
		}
		$file = "";
		if(!empty($name) && file_exists($pathForThisModule."file_".$name.".txt")) {
			$file = file_get_contents($pathForThisModule."file_".$name.".txt");
		}
		templates::assign_var("struct", $file);
		$this->Prints("Creator/CreatorEdit");
	}

	function removePrefix($data, $prefix) {
		return str_replace($prefix, "", $data);
	}

	function createFields($struct) {
		return 'public $'.$struct['altName'].';';
	}

	function addSlash($data) {
		return "'".$data."'";
	}

	function createFieldsDB($struct, $isDB = true, $withName = true, $isAdd = false) {
		$type = $struct['type'];
		if($type=="linkToAdmin") {
			$type = "int".($isDB ? "(1)" : "");
		} else if($isDB && $type=="radio") {
			$type = "enum".($isDB ? "(".implode(",", array_map(array($this, "addSlash"), $struct['field'])).")" : "");
		} else if($type=="array" && $struct['selectedData']=="dataOnTable") {
			$name = $struct['loadDB']['name'];
			$key = $struct['loadDB']['key'];
			$datas = db::getTables(true, true, true);
			$type = false;
			if(isset($datas[$name])) {
				$datas = $datas[$name];
				$type = (isset($datas[$key]) ? $datas[$key] : false);
			}
		} else if($type=="enum" || ($type=="array" && $struct['selectedData']=="dataOnInput")) {
			$type = "enum".($isDB ? "(".implode(",", array_map(array($this, "addSlash"), $struct['field'])).")" : "");
		} else if($type=="int") {
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
		} else if($type=="file") {
			$type = "varchar".($isDB ? "(255)" : "");
		} else if($type=="fileArray") {
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
			return array($struct['altName'] => (isset($struct['orName']) ? array("orName" => $struct['orName'], $type) : $type));
		} else if($isAdd !== false && $withName===false) {
			return array($struct['altName'] => $type." COMMENT ".db::escape($struct['name']));
		} else if($isDB) {
			return '`'.$struct['altName'].'` '.$type.' not null'.$auto_increment." COMMENT ".db::escape($struct['name']);
		} elseif($withName===true) {
			return array($struct['altName'] => $type);
		} else {
			$arr = array("value" => $type);
			(isset($struct['name']) ? $arr['comment'] = $struct['name'] : "");
			return array($struct['altName'] => $arr);
		}
	}

	private function getB($data, $type) {
		$true = array();
		for($i=0;$i<sizeof($data);$i++) {
			$true[] = $type;
		}
		return $true;
	}

}
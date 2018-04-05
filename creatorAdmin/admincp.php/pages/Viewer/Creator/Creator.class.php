<?php

class Creator extends Core {

	function __construct() {
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

	function Editor($name = "") {
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
			if(file_exists($pathForThisModule."file_".$altTitle.".txt")) { unlink($pathForThisModule."file_".$altTitle.".txt"); }
			file_put_contents($pathForThisModule."file_".$altTitle.".txt", json_encode($_POST));
			unset($_POST['data']['title']);
			unset($_POST['data']['icon']);

			// создание управляющего модуля для указанного раздела
			$data = $_POST['data'];
			$data = array_values($data);
			$listShild = $universalAttributes = $universalAttributesTakeAdd = $universalAttributesTakeEdit = "";
			$exclude = array();
			$createAutoField = array();
			$data = array_values($data);
			for($i=0;$i<sizeof($data);$i++) {
				$altNameField = nucfirst(ToTranslit($data[$i]['name']));
				$data[$i]['altName'] = $first.$altNameField;
				if($data[$i]['type']=="image") {
					$listShild .= 'if(isset($row[\''.$first.$altNameField.'\'])) { $row[\''.$first.$altNameField.'\'] = "{C_default_http_local}".$row[\''.$first.$altNameField.'\']; }';
				}
				if($data[$i]['type']=="systime") {
					$universalAttributesTakeAdd .= 'if(isset($model->'.$first.$altNameField.')) { $model->'.$first.$altNameField.' = $model->Time(); }'.PHP_EOL;
					$data[$i]['type'] = "hidden";
				} else {
					$universalAttributesTakeAdd .= '$model->setAttribute(\''.$first.$altNameField.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
					$universalAttributesTakeEdit .= '$model->setAttribute(\''.$first.$altNameField.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
				}
				$universalAttributes .= '$model->setAttribute(\''.$first.$altNameField.'\', \'Type\', \''.$data[$i]['type'].'\');'.PHP_EOL;
				if(isset($data[$i]['translate'])) {
					$altTranslateField = nucfirst(ToTranslit($data[$i]['alttitle']));
					$universalAttributes .= '$model->setAttribute(\''.$first.$altTranslateField.'\', \'Type\', \'hidden\');'.PHP_EOL;
					$universalAttributesTakeAdd .= 'if(isset($model->'.$first.$altTranslateField.')) { $model->'.$first.$altTranslateField.' = ToTranslit($model->'.$first.$altTranslateField.'); }'.PHP_EOL;
					$createAutoField[] = array("altName" => $first.$altTranslateField, "type" => "hidden");//$data[$i]['type']
				}
				for($l=0;$l<sizeof($langSupport);$l++) {
					lang::Update($langSupport[$l], $first.$altNameField, $data[$i]['name']);
				}
				if(isset($data[$i]['hideOnMain'])) {
					$exclude[$first.$altNameField] = "\"".$first.$altNameField."\"";
				}
			}
			$exclude = array_unique($exclude);
			$exclude = array_values($exclude);
			$archer = str_replace("{universalAttributes}", trim($universalAttributes), $archer);
			$archer = str_replace("{universalAttributesTakeAdd}", trim($universalAttributesTakeAdd), $archer);
			$archer = str_replace("{universalAttributesTakeEdit}", trim($universalAttributesTakeEdit), $archer);
			$archer = str_replace("{listShild}", trim($listShild), $archer);
			$archer = str_replace("{exclude}", implode(", ", $exclude), $archer);
			$archer = str_replace("{altTitle}", $altTitleUp, $archer);
			if(!is_writeable(PATH_MODULES)) {
				@chmod(PATH_MODULES, 077);
			}
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


			// построение модели данных
			$model = str_replace("{altTitle}", $altTitleUp, $model);
			$dataFirst = $first."Id";
			$data[-1]['altName'] = $dataFirst;
			$data[-1]['type'] = "int";
			sortByKey($data);
			$data = array_merge($data, $createAutoField);
			$model = str_replace("{fields}", implode(PHP_EOL, array_map(array($this, "createFields"), $data)), $model);
			if(!is_writeable(PATH_MODELS)) {
				@chmod(PATH_MODELS, 077);
			}
			if(file_exists(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX)) { unlink(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX); }
			file_put_contents(PATH_MODELS."Model".$altTitleUp.".".ROOT_EX, $model);
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
				$struct = array_map(array($this, "createFieldsDB"), $data, array(false));
				$structClear = array();
				for($i=0;$i<sizeof($struct);$i++) {
					$k = key($struct[$i]);
					$v = current($struct[$i]);
					$structClear[$k] = $v;
				}
				$forUpdate = array("add" => array(), "edit" => array(), "remove" => array());
				foreach($structClear as $k => $v) {
					if(isset($edit[$dbName][$k]) && $edit[$dbName][$k]!=$v) {
						$forUpdate['edit'][$k] = array("altName" => $k, "type" => $v);
					} else if(!isset($edit[$dbName][$k])) {
						$forUpdate['add'][$k] = array("altName" => $k, "type" => $v);
					}
				}
				foreach($edit[$dbName] as $k => $v) {
					if(!isset($structClear[$k])) {
						$forUpdate['remove'][$k] = array("altName" => $k, "type" => $v);
					}
				}
				if(sizeof($forUpdate['add'])>0) {
					$forUpdate['add'] = array_map(array($this, "createFieldsDB"), $forUpdate['add'], array(true), array(false));
				}
				if(sizeof($forUpdate['edit'])>0) {
					$forUpdate['edit'] = array_map(array($this, "createFieldsDB"), $forUpdate['edit'], array(true), array(false));
				}
				if(sizeof($forUpdate['remove'])>0) {
					$forUpdate['remove'] = array_map(array($this, "createFieldsDB"), $forUpdate['remove'], array(false));
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
			} else {
				$db = implode(",".PHP_EOL, array_map(array($this, "createFieldsDB"), $data));
				$db .= ",".PHP_EOL."primary key `id`(`".$dataFirst."`)";
				modules::create_table($altTitle, $db, true);
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

	function createFieldsDB($struct, $isDB = true, $withName = true) {
		$type = $struct['type'];
		if($type=="int") {
			$type = "int".($isDB ? "(11)" : "");
		} else if($type=="varchar") {
			$type = "varchar".($isDB ? "(255)" : "");
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
		if($withName===false) {
			return array($struct['altName'] => $type);
		} else if($isDB) {
			return '`'.$struct['altName'].'` '.$type.' not null';
		} else {
			return array($struct['altName'] => $type);
		}
	}

}
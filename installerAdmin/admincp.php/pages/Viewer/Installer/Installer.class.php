<?php

class Installer extends Core {

	private function rebuild($arr) {
		$newArr = array();
		for($i=0;$i<sizeof($arr);$i++) {
			$arr[$i]['active'] = true;
			$res = $arr[$i];
			$res = array_merge($res, array("Name" => $arr[$i][0], "File" => $arr[$i][1]));
			$newArr[$arr[$i][0]] = $res;
		}
		return $newArr;
	}

	private function rcopyModules($src, $dst) {
        if(is_dir($src)) {
            @mkdir($dst, 0777);
            $files = @scandir($src);
            foreach($files as $file) {
                if($file != "." && $file != "..") {
                    $this->rcopyModules($src.DS.$file, $dst.DS.$file);
                    @rmdir($src);
                }
            }
        } else if(file_exists($src)) {
            @copy($src, $dst);
            @unlink($src);
        }
    }

	function get_file_data($file, $default_headers, $default = false) {
		$fp = fopen($file, 'r');
		$file_data = fread($fp, 8192);
		fclose($fp);
		$file_data = str_replace("\r", "\n", $file_data);
		$ret = array();
		foreach($default_headers as $field => $regex) {
			if(preg_match('/^[ \t\/*#@]*'.preg_quote($regex, '/').':(.*)$/mi', $file_data, $match) && isset($match[1])) {
				$ret[$field] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
			} else if($default!==false) {
				$ret[$field] = '';
			}
		}
		return $ret;
	}
	
	function __construct() {
	global $manifest;
		callAjax();
		$configs = array("https://raw.githubusercontent.com/killserver/modulesForCardinal/master/list.min.json");
		$paths = array();
		$listAll = array();
		for($i=0;$i<sizeof($configs);$i++) {
			$path = pathinfo($configs[$i]);
			$paths[] = $path['dirname']."/";
			$listMirror = new Parser($configs[$i]."?".time());
			$listMirror->timeout(3);
			$listMirror = $listMirror->get();
			$listMirror = json_decode($listMirror, true);
			if($listMirror!==null) {
				$listAll = array_merge($listAll, $listMirror);
			}
		}
		if(isset($_GET['download'])) {
			if(!isset($listAll[$_GET['download']]) || !isset($listAll[$_GET['download']]['download'])) {
				header("HTTP/1.1 406 Not Acceptable");
				echo "not found";
				return false;
			}
			$prs = new Parser($listAll[$_GET['download']]['download']."?".time());
			$prs->timeout(30);
			file_put_contents(PATH_CACHE_SYSTEM.$_GET['download'].".zip", $prs->get());
			HTTP::echos("1");
			return false;
		}
		if(isset($_GET['install'])) {
			if(!isset($listAll[$_GET['install']]) || !isset($listAll[$_GET['install']]['download'])) {
				header("HTTP/1.1 406 Not Acceptable");
				echo "not found";
				return false;
			}
			if(!file_exists(PATH_CACHE_SYSTEM.$_GET['install'].".zip")) {
				header("HTTP/1.0 404 Not Found");
				die();
			}
			$tar_object = new ZipArchive();
			$list = $tar_object->open(PATH_CACHE_SYSTEM.$_GET['install'].".zip");
			if($list!==true) {
				header("HTTP/1.0 404 Not Found");
				die();
			}
			$path = $listAll[$_GET['install']]['download'];
			$path = str_replace($paths, "", $path);
			$path = str_replace(".zip", "", $path);
			$listFiles = array("allList" => array(), "forDelete" => array());
			for($i=0;$i<$tar_object->numFiles;$i++) {
				$file = nsubstr($tar_object->getNameIndex($i), nstrlen($path."/"));
				if(empty($file)) { continue; }
				$fileInfo = pathinfo($file);
				$listFiles['allList'][$file] = $file;
				if(isset($fileInfo['extension'])) {
					$listFiles['forDelete'][$file] = $file;
				}
			}
			if(!file_exists(PATH_CACHE_USERDATA."Installer".DS)) {
				@mkdir(PATH_CACHE_USERDATA."Installer".DS, 0777);
			}
			if(!is_writeable(PATH_CACHE_USERDATA."Installer".DS)) {
				@chmod(PATH_CACHE_USERDATA."Installer".DS, 0777);
			}
			@file_put_contents(PATH_CACHE_USERDATA."Installer".DS.$path.".json", json_encode($listFiles));
			$tr = $tar_object->extractTo(ROOT_PATH);
			$this->rcopyModules(ROOT_PATH.$_GET['install'], ROOT_PATH);
			cardinal::RegAction("Установка модуля ".$_GET['install']);
			if($tr === true) {
				$tar_object->close();
				unlink(PATH_CACHE_SYSTEM.$_GET['install'].".zip");
				echo "1";
			} else {
				$tar_object->close();
				header("HTTP/1.1 406 Not Acceptable");
			}
			return false;
		}
		if(isset($_GET['active'])) {
			modules::actived($_GET['active'], (modules::actived($_GET['active'])===false ? true : false));
			return false;
		}
		if(isset($_GET['updateList'])) {
			config::Update("serverList", $_POST['serverList']);
			return false;
		}
		$default_headers = array(
			'Name' => 'Name',
			'Description' => 'Description',
			'Image' => 'Image',
			'Changelog' => 'Changelog',
			'Version' => 'Version',
			"OnlyUse" => "OnlyUse",
		);
		$lists = ($manifest['log']['init_modules']);
		$dt = read_dir(PATH_MODULES, ".class.".ROOT_EX);
		$dt = array_values($dt);
		$arr = array();
		foreach($dt as $v) {
			if("SEOBlock.class.php"!==$v && "ArcherExample.class.php"!==$v && "base.class.php"!==$v && "changelog.class.php"!==$v && "mobile.class.php"!==$v && "installerAdmin.class.php"!==$v) {
				$name = nsubstr($v, 0, -nstrlen(".class.".ROOT_EX));
				$arr[$name] = array($name, PATH_MODULES.$v);
				$arr[$name]['Name'] = $name;
				$arr[$name]['File'] = PATH_MODULES.$v;
				$info = $this->get_file_data(PATH_MODULES.$v, $default_headers);
				$arr[$name] = array_merge($arr[$name], $info);
			}
		}
		//$dt = array_values($dt);
		$lists = $this->rebuild($lists);
		$newList = array();
		foreach($lists as $k => $v) {
			if("SEOBlock"!==$k && "ArcherExample"!==$k && "base"!==$k && "changelog"!==$k && "mobile"!==$k && "installerAdmin"!==$k && strpos($v[1], PATH_MODULES)!==false) {
				$info = $this->get_file_data($v[1], $default_headers);
				$v['active'] = true;
				$v = array_merge($v, $info);
				$newList[$k] = $v;
			}
		}
		$lists = $newList;
		$lists = array_merge($arr, $lists);
		templates::assign_var("listServer", implode("\n", $configs));
		$list = array_values($lists);
		for($i=0;$i<sizeof($list);$i++) {
			$info = array("name" => $list[$i]['Name'], "path" => $list[$i][1], "altName" => $list[$i]['Name']);
			if(isset($list[$i]["active"]) && $list[$i]["active"]===true) {
				$info['active'] = "active";
			} else {
				$info['active'] = "unactive";
			}
			if(isset($listAll[$list[$i][0]])) {
				$info = array_merge($info, $listAll[$list[$i][0]]);
			}
			if(isset($info['description'])) {
				$info['description'] = str_replace("{", "&#123;", $info['description']);
			}
			if(isset($info['changelog'])) {
				$changelog = "";
				foreach($info['changelog'] as $b => $infoz) {
					$changelog .= "<b>".$b."</b><br>".$infoz."<br><br>";
				}
				$info['changelog'] = $changelog;
				$info['changelog'] = str_replace("{", "&#123;", $info['changelog']);
				$info['noChangelog'] = "false";
			} else {
				$info['noChangelog'] = "true";
			}
			if(!isset($info['description'])) {
				$info['description'] = "";
			}
			if(!isset($info['image'])) {
				$info['image'] = "https://png.icons8.com/color/540/app-symbol.png";
			}
			if(isset($info['version']) && class_exists($list[$i][0], false) && property_exists($list[$i][0], "version") && $list[$i][0]::$version<$info['version']) {
				$info['hasUpdate'] = "true";
			} else {
				$info['hasUpdate'] = "false";
			}
			if(isset($list[$i]['OnlyUse']) || (class_exists($list[$i][0], false) && property_exists($list[$i][0], "onlyAdmin") && $list[$i][0]::$onlyAdmin)) {
				continue;
			}
			templates::Assign_vars($info, "installed", "i".$i);
		}
		foreach($listAll as $k => $v) {
			if(isset($v['description'])) {
				$v['description'] = str_replace(array("{"), array("&#123;"), $v['description']);
			}
			$v['installed'] = "1";
			$v['subName'] = $k;
			if(isset($v['buy'])) {
				$v['installed'] = "4";
				$v['buyPrice'] = $v['buy'];
			} else if(isset($lists[$k]) && isset($v['version']) && class_exists($k, false) && property_exists($k, "version") && $k::$version<$v['version']) {
				$v['installed'] = "2";
			} else if(isset($lists[$k])) {
				$v['installed'] = "3";
			}
			$listAll[$k]['installed'] = $v['installed'];
			$listAll[$k]['description'] = $v['description'];
			templates::assign_vars($v, "listAll", $k);
		}
		$json = json_encode($listAll);
		$json = str_replace("'", "\\'", $json);
		templates::assign_var("infoAll", $json);
		$this->Prints("Installer");
	}
	
}
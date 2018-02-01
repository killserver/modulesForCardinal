<?php

class Installer extends Core {

	private function rebuild($arr) {
		$newArr = array();
		for($i=0;$i<sizeof($arr);$i++) {
			$arr[$i]['active'] = true;
			$newArr[$arr[$i][0]] = $arr[$i];
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
	
	function __construct() {
	global $manifest;
		callAjax();
		$config = array("https://raw.githubusercontent.com/killserver/modulesForCardinal/master/list.min.json");
		$listAll = array();
		for($i=0;$i<sizeof($config);$i++) {
			$listMirror = new Parser($config[$i]."?".time());
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
		$lists = ($manifest['log']['init_modules']);
		$dt = read_dir(PATH_MODULES, ".class.".ROOT_EX);
		$dt = array_values($dt);
		$arr = array();
		foreach($dt as $v) {
			if("SEOBlock.class.php"!==$v && "ArcherExample.class.php"!==$v && "base.class.php"!==$v && "changelog.class.php"!==$v && "mobile.class.php"!==$v && "installerAdmin.class.php"!==$v) {
				$name = nsubstr($v, 0, -nstrlen(".class.".ROOT_EX));
				$arr[$name] = array($name, PATH_MODULES.$v);
			}
		}
		//$dt = array_values($dt);
		$lists = $this->rebuild($lists);
		$newList = array();
		foreach($lists as $k => $v) {
			if("SEOBlock"!==$k && "ArcherExample"!==$k && "base"!==$k && "changelog"!==$k && "mobile"!==$k && "installerAdmin"!==$k && strpos($v[1], PATH_MODULES)!==false) {
				$v['active'] = true;
				$newList[$k] = $v;
			}
		}
		$lists = $newList;
		$lists = array_merge($arr, $lists);
		templates::assign_var("listServer", implode("\n", $config));
		$list = array_values($lists);
		for($i=0;$i<sizeof($list);$i++) {
			$info = array("name" => $list[$i][0], "path" => $list[$i][1], "altName" => $list[$i][0]);
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
			templates::Assign_vars($info, "installed", "i".$i);
		}
		foreach($listAll as $k => $v) {
			if(isset($v['changelog'])) {
				$changelog = "";
				foreach($v['changelog'] as $b => $vz) {
					$changelog .= "<b>".$b."</b><br>".$vz."<br><br>";
				}
				$v['changelog'] = $changelog;
				$v['changelog'] = str_replace(array("{", "'"), array("&#123;", "\'"), $v['changelog']);
			}
			if(isset($v['description'])) {
				$v['description'] = str_replace(array("{", "'"), array("&#123;", "\'"), $v['description']);
			}
			$v['installed'] = "1";
			$v['subName'] = $k;
			if(isset($lists[$k]) && isset($v['version']) && class_exists($k, false) && property_exists($k, "version") && $k::$version<$v['version']) {
				$v['installed'] = "2";
			} else if(isset($lists[$k])) {
				$v['installed'] = "3";
			}
			templates::assign_vars($v, "listAll", $k);
		}
		$json = json_encode($listAll);
		$json = str_replace("'", "\\'", $json);
		templates::assign_var("infoAll", $json);
		$this->Prints("Installer");
	}
	
}
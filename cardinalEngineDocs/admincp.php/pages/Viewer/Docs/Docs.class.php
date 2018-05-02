<?php

class Docs extends Core {
	
	function menu($data, $children = false, $notDocument = array()) {
		$menu = "<ul>";
		if(isset($data["childrenPage"])) {
			$menu .= "<li><a href=\"{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Docs&link=core-".$data["childrenPage"]."\" >Открыть</a></li>\n";
			unset($data["childrenPage"]);
		} else {
			$menu .= "<li><a>Документация</a></li>";
		}
		foreach($data as $key => $val) {
			$key = $val['name'];
			if(in_array($key, $notDocument)) {
				continue;
			}
			$children_arr = $children;
			if($children_arr==false) {
				$link = (!isset($val['link']) ? $key : $val['link']);
				$children_arr = array(!isset($val['link']) ? $key : $val['link']);
			} else {
				array_push($children_arr, (!isset($val['link']) ? $key : $val['link']));
				$link = implode("-", $children_arr);
			}
			$link = str_replace('{L_"Ядро"}-', "", $link);
			$active = false;
			$link = str_replace(".".ROOT_EX, "", $link);
			$altLink = $altLink2 = "";
			if(isset($children_arr[1]) && $children_arr[1]=="class") {
				$method = end($children_arr);
				$class = prev($children_arr);
				$altLink = $class."-".$method;
			}
			if(isset($children_arr[0]) && $children_arr[0]=="functions") {
				$altLink = $children_arr[2];
			}
			if(isset($children_arr[1]) && $children_arr[1]=="functions") {
				$altLink2 = $children_arr[3];
			}
			if(file_exists(dirname(__FILE__).DS."docs".DS.$altLink2.".json")) {
				$link = $altLink2;
				$active = true;
			} else if(file_exists(dirname(__FILE__).DS."docs".DS.$altLink.".json")) {
				$link = $altLink;
				$active = true;
			} else if(file_exists(dirname(__FILE__).DS."docs".DS."core-".$link.".json")) {
				$active = true;
			}
			if(isset($val['link'])) {
				$link = $val['link'];
			}
			if(isset($val["name"]) && !isset($val["children"])) {
				$menu .= "<li><a href=\"{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Docs&link=".$link."\"".(!$active ? " class=\"no-find\" title=\"{L_\"Не документировано\"}\" alt=\"{L_\"Не документировано\"}\"" : "")." >".$val["name"]."</a></li>\n";
			}
			if(isset($val["children"])) {
				$menu .= "<li><a class=\"cat\">".$val["name"]."</a>";
				$val["children"]["childrenPage"] = $link;
				$menu .= $this->menu($val["children"], $children_arr, $notDocument);
				$menu .= "</li>\n";
			}
		}
		$menu .= "</ul>";
		return $menu;
	}

	function scan($dir, $funcs) {
		$d = array();
		if(is_dir($dir)) {
			if($dh = dir($dir)) {
				while(($v = $dh->read()) !== false) {
					if($v == '.' or $v == '..' or $v == 'index.html' or $v == 'index.'.ROOT_EX) {
						continue;
					}
					if(!is_dir($dir.DS.$v)) {
						if(isset($funcs[$dir.DS.$v])) {
							if($funcs[$dir.DS.$v]['is_class']===false) {
								$keys = array_values($funcs[$dir.DS.$v]);
								$name = $v;
								$children = $keys;
							} else {
								$keys = array_values($funcs[$dir.DS.$v]);
								unset($keys[0]);
								$keys = current($keys);
								$name = $v;
								$children = $keys['children'];
							}
							$d[$v] = array("name" => $name, "children" => $children);
						} else {
							$d[] = array("name" => $v);
						}
					}
					if(is_dir($dir.DS.$v)) {
						$scan = $this->scan($dir.DS.$v, $funcs);
						if(sizeof($scan)>0) {
							//sortByValue($scan);
							$d[$v] = array("name" => $v, "children" => $scan);
						}
					}
				}
				$dh->close();
			}
		}
		return $d;
	}

	private function search($search) {
		$replaces = array(
			"core" => "{L_\"Ядро\"}",
			"cd-templates" => "{L_\"Шаблонизатор\"}",
			"cd-constants" => "{L_\"Константы\"}",
			"cd-hides" => "{L_\"Скрытые функции\"}",
			"cd-hides-admin" => "{L_\"Админ-панели\"}",
			"cd-hides-core" => "{L_\"Движка\"}",
		);

		templates::assign_var("documentation-page", "engine");
		$maxDate = 0;
		$all = array();
		$d = read_dir(dirname(__FILE__).DS."docs".DS, "json", true);
		for($i=0;$i<sizeof($d);$i++) {
			$maxDate = max($maxDate, fileatime($d[$i]));
			$read = file_get_contents($d[$i]);
			$all[$d[$i]] = json_decode($read, true);
		}
		templates::assign_var("doc_date", date("d-m-Y H:i:s", $maxDate));

		$find = $hightlight = array();
		$seachMaybe = "";
		foreach($all as $path => $json) {
			$descr = preg_replace("#[^a-zA-Zа-яА-ЯёЁ]#isu", " ", strip_tags($json[0]['descr']));
			$descr = str_replace("  ", " ", $descr);
			$words = explode(" ", $descr);
			// кратчайшее расстояние пока еще не найдено
			$closest = "";
			$shortest = -1;
			for($i=0;$i<sizeof($words);$i++) {
				$lev = levenshtein($search, $words[$i]);
				if($lev==0) {
					$closest = $words[$i];
					$shortest = 0;
					break;
				} else if($lev<=$shortest || $shortest<0) {
					$closest = $words[$i];
					$shortest = $lev;
				}
			}
			if($shortest == 0) {
				$find[$path] = $shortest;
				$hightlight[$path] = $closest;
			} elseif($shortest < 4) {
				if(empty($seachMaybe)) {
					$seachMaybe = $closest;
				}
				$find[$path] = $shortest;
				$hightlight[$path] = $closest;
			}
		}

		sortByValue($find);

		templates::assign_var("seachMaybe", (!empty($seachMaybe) ? $seachMaybe : ""));
		templates::assign_var("IS_seachMaybe", (!empty($seachMaybe) ? "1" : "0"));

		foreach($find as $path => $priory) {
			$name = $all[$path]['name'];
			$link = $all[$path]['path'];
			$descr = $all[$path][0]['descr'];
			$link = str_replace(".".ROOT_EX, "", $link);
			$link = str_replace("/", "-", $link);
			$name = str_replace("::", "-", $name);
			$name = str_replace("->", "-", $name);
			$name = str_replace(array_keys($replaces), array_values($replaces), $name);
			if(file_exists(dirname(__FILE__).DS."docs".DS.$name.".json")) {
				$link = $name;
			}
			$descr = str_Replace($hightlight[$path], "<span class=\"find\">".$hightlight[$path]."</span>", $descr);
			templates::assign_vars(array("name" => $name, "descr" => $descr, "link" => $link), "search", $path);
		}
		$this->Prints("Docs/Search");
	}

	function __construct() {
		if(Arr::get($_GET, "clearCache", false)!==false) {
			if(file_exists(dirname(__FILE__).DS."docs".DS."files.txt")) {
				unlink(dirname(__FILE__).DS."docs".DS."files.txt");
			}
			callAjax();
			HTTP::echos("done");
			return false;
		}
		templates::assign_var("documentation-page", "engine");
		templates::assign_var("copyright-documentation", "http://engine.socpro.pp.ua/");
		$search = Arr::get($_GET, "search", false);
		templates::assign_var("input", $search);
		if($search!==false) {
			$this->search($search);
			return false;
		}
		if(!file_exists(dirname(__FILE__).DS."docs".DS."maxTime.txt")) {
			$maxDate = 0;
			$d = read_dir(dirname(__FILE__).DS."docs".DS, "json", true);
			for($i=0;$i<sizeof($d);$i++) {
				$maxDate = max($maxDate, fileatime($d[$i]));
			}
			file_put_contents(dirname(__FILE__).DS."docs".DS."maxTime.txt", $maxDate);
		} else {
			$maxDate = file_get_contents(dirname(__FILE__).DS."docs".DS."maxTime.txt");
		}
		templates::assign_var("doc_date", date("d-m-Y H:i:s", $maxDate));

		if(!file_exists(dirname(__FILE__).DS."docs".DS."files.txt")) {
			$arr = $this->getMenu();
			file_get_contents(dirname(__FILE__).DS."docs".DS."files.txt", json_encode($arr));
		} else {
			$arr = file_get_contents(dirname(__FILE__).DS."docs".DS."files.txt");
			$arr = json_decode($arr, true);
		}
		//vdump($arr);die();
		$menu = $this->menu($arr, false, array("PHPMailer5.php", "PHPMailer7.php", "paths.default.php", "paths.php"));
		//vdump($menu);die();
		templates::assign_var("path_exists", "0");

		$replaces = array(
			"core" => "{L_\"Ядро\"}",
			"cd-templates" => "{L_\"Шаблонизатор\"}",
			"cd-constants" => "{L_\"Константы\"}",
			"cd-hides" => "{L_\"Скрытые функции\"}",
			"cd-hides-admin" => "{L_\"Админ-панели\"}",
			"cd-hides-core" => "{L_\"Движка\"}",
		);
		$content = "";
		
		($fileForRead = Arr::get($_GET, "link", false))===false ? $fileForRead = "welcome" : "";
		$fileForRead = ltrim($fileForRead, "./");
		if(file_exists(dirname(__FILE__).DS."docs".DS.$fileForRead.".json")) {
			$data = file_get_contents(dirname(__FILE__).DS."docs".DS.$fileForRead.".json");
			$data = json_decode($data, true);

			if(isset($data["path"])) {
				$data["path"] = ltrim($data["path"], "");
				$data["path"] = rtrim($data["path"], "/");
				$data['path'] = $data['path'];
				$exp = explode("/", $data["path"]);
				$path = "";
				$size = sizeof($exp);
				for($i=0;$i<$size;$i++) {
					$path .= $exp[$i]."/";
					templates::assign_vars(array("is_link" => ($i<($size-1) ? "1" : "0"), "path" => $path, "title" => str_replace(array_keys($replaces), array_values($replaces), $exp[$i])), "path", "i".$i);
				}
				templates::assign_var("path_exists", "1");
				unset($data["path"]);
			}
			if(isset($data["name"])) {
				$content .= "<div class=\"documentation-title-top\" >".str_replace(array_keys($replaces), array_values($replaces), $data["name"])."</div>";
				unset($data["name"]);
			}
			foreach($data as $key => $val) {
				$content .= "<span class=\"documentation-body\">";
				$ks = array_keys($val);
				for($t=0;$t<sizeof($ks);$t++) {
					$content .= $this->parseDocs($ks[$t], $val[$ks[$t]]);
				}
				$content .= "</span>";
			}
		} else {
			$content .= "<b class=\"no-find text-center\">Документация не реализована</b>";
		}
		if(isset($_GET["ajax"])) {
			HTTP::echos(templates::view($content));
			die();
		}
		templates::assign_var("menus", $menu);
		templates::assign_var("content", $content);
		$this->Prints("Docs/Show");
	}

	private function getMenu() {
		$funcs = array();

		$arr = read_dir(PATH_CLASS, ".".ROOT_EX, true, true, array("index.html", "index.php"));
		for($i=0;$i<sizeof($arr);$i++) {
			include_once($arr[$i]);
		}

		$exp = explode(",", DEFINED_CLASSES);
		$classes = array_diff(get_declared_classes(), $exp);
		$classes = array_values($classes);
		for($i=0;$i<sizeof($classes);$i++) {
			$reflFunc = new ReflectionClass($classes[$i]);
			$methods = $reflFunc->getMethods(ReflectionMethod::IS_PUBLIC);
			$methodMenu = array();
			for($z=0;$z<sizeof($methods);$z++) {
				$m = new ReflectionMethod($methods[$z]->class, $methods[$z]->name);
				$methodMenu[] = array("startLine" => $m->getStartLine(), "name" => $methods[$z]->name);
			}
			$funcs[$reflFunc->getFileName()]['is_class'] = true;
			$funcs[$reflFunc->getFileName()][$classes[$i]] = array("startLine" => $reflFunc->getStartLine(), "name" => $classes[$i], "children" => $methodMenu);
		}
		$func = get_defined_functions();
		$func = $func['user'];
		for($i=0;$i<sizeof($func);$i++) {
			$reflFunc = new ReflectionFunction($func[$i]);
			$funcs[$reflFunc->getFileName()]['is_class'] = false;
			$funcs[$reflFunc->getFileName()][$func[$i]] = array("startLine" => $reflFunc->getStartLine(), "name" => $func[$i]);
		}
		$arr = array();
		$core = ROOT_PATH."core";
		$arr = $this->scan($core, $funcs);
		unset($arr['cache']);
		unset($arr['media']);
		unset($arr['lang']);
		unset($arr['pages']);
		$arr = array(
			array(
				"name" => "{L_\"Ядро\"}",
				"link" => "core",
				"children" => $arr
			)
		);
		$arr[] = array("name" => "{L_\"Шаблонизатор\"}", "link" => "cd-templates");
		$arr[] = array("name" => "{L_\"Константы\"}", "link" => "cd-constants");
		$arr[] = array(
			"name" => "{L_\"Скрытые функции\"}",
			"link" => "cd-hides",
			"children" => array(
				array(
					"name" => "{L_\"Админ-панели\"}",
					"link" => "cd-hides-admin",
				),
				array(
					"name" => "{L_\"Движка\"}",
					"link" => "cd-hides-core",
				),
			)
		);
		return $arr;
	}

	private function parseDocs($type, $val) {
		$return = "";
		if($type=="title") {
			$return .= "<span class=\"documentation-title\" >".$val."</span>";
		}
		if($type=="version") {
			$return .= "<span class=\"documentation-version\" ><abbr title=\"Cardinal Engine\">CE</abbr>: <i>".($val<VERSION ? $val." - ".VERSION : VERSION)."</i></span>";
		}
		if($type=="params") {
			$params = "<table width=\"100%\"><thead><tr><th>Тип</th><th>Переменная</th><th>Описание</th></tr></thead><tbody>";
			foreach($val as $v) {
				$params .= "<tr><td width=\"15%\">".$v['type']."</td><td>$".$v['name']."</td><td>".$v['descr']."</td></tr>";
			}
			$params .= "</tbody></table>";
			$return .= "<div class=\"documentation-params\">".$params."</div>";
		}
		if($type=="descr") {
			$return .= "<div class=\"documentation-descr\" >".$val."</div>";
		}
		if($type=="demo") {
			$return .= "<div class=\"documentation-demo\" >".$val."</div>";
		}
		if($type=="code") {
			if(is_array($val)) {
				foreach($val as $type => $code) {
					if(is_array($code)) {
						$keys = array_keys($code);
						for($i=0;$i<sizeof($keys);$i++) {
							$type = $keys[$i];
							if($keys[$i]=="tpl") {
								$type = "html";
							}
							$return .= $this->printCode($code[$keys[$i]], $type);
						}
					} else {
						if($type=="tpl") {
							$type = "html";
						}
						$return .= $this->printCode($code, $type);
					}
				}
			} else if(is_string($val)) {
				$return .= "<pre><code>".$this->code($val)."</code></pre>";
			}
		}
		return $return;
	}

	private function printCode($code, $type = "") {
		if($type=="tpl") {
			$type = "html";
		}
		return "<pre><code".(!empty($type) && is_string($type) ? " class=\"".$type."\"" : "").">".$this->code($code)."</code></pre>";
	}

	private function code($code) {
		$code = htmlspecialchars($code);
		$code = str_Replace("/", "&sol;", $code);
		$code = str_Replace("[", "&#091;", $code);
		$code = str_Replace("]", "&#093;", $code);
		$code = str_Replace("{", "&#123;", $code);
		$code = str_Replace("}", "&#125;", $code);
		$code = str_Replace("|", "&#124;", $code);
		return $code;
	}

}

?>
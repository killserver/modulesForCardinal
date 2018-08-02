<?php
/*
Name: Нотификация в админ-панели(для разработчиков)
Version: 1.0
Author: killserver
OnlyUse: true
 */
if(!defined("IS_CORE")) {
die();
}

class badgeAdmin extends modules {

	private static $badge = array();

	public static $version = "1.0";

	function __construct() {
		addEventRef("admin_menu_loaded", array(&$this, "addBadgeAdmin"), 99999999);
		addEvent("print_after_admin", array(&$this, "changeAdminStyle"));
	}

	public static function addBadge($name, $badge, $color = "default") {
		$class = "";
		if(substr($color, 0, 4)==="#777"||$color==="default") {
			$class = "badge-default";
			$color = "";
		} else if($color==="red"||$color==="danger") {
			$class = "badge-danger";
			$color = "";
		} else if($color==="blue"||$color==="info") {
			$class = "badge-info";
			$color = "";
		} else if($color==="yellow"||$color==="warning") {
			$class = "badge-default";
			$color = "";
		} else if($color==="green"||$color==="success") {
			$class = "badge-success";
			$color = "";
		} else if($color==="primary"||$color==="#2c2e2f") {
			$class = "badge-primary";
			$color = "";
		} else if($color==="secondary"||$color==="#68b828") {
			$class = "badge-secondary";
			$color = "";
		} else if($color==="purple"||$color==="#7c38bc") {
			$class = "badge-purple";
			$color = "";
		} else if($color==="pink"||$color==="#ff6264") {
			$class = "badge-pink";
			$color = "";
		} else if($color==="yellow"||$color==="#fcd036") {
			$class = "badge-yellow";
			$color = "";
		} else if($color==="orange"||$color==="#f7aa47") {
			$class = "badge-orange";
			$color = "";
		} else if($color==="turquoise"||$color==="#00b19d") {
			$class = "badge-turquoise";
			$color = "";
		} else if($color==="red"||$color==="#d5080f") {
			$class = "badge-red";
			$color = "";
		} else if($color==="blue"||$color==="#0e62c7") {
			$class = "badge-blue";
			$color = "";
		} else if($color==="black"||$color==="#222222") {
			$class = "badge-black";
			$color = "";
		} else if($color==="white"||$color==="#fff") {
			$class = "badge-white";
			$color = "";
		}
		if(isset(self::$badge[$name]) && is_numeric(self::$badge[$name]['value'])) {
			$badge += self::$badge[$name]['value'];
		}
		self::$badge[$name] = array("value" => $badge, "color" => $color, "class" => $class);
	}

	function addBadgeAdmin(&$menu) {
		foreach(self::$badge as $k => $v) {
			$badge = "<span class=\"badge".(!empty($v['class']) ? " ".$v['class'] : "")." pull-right\"".(!empty($v['color']) ? " style=\"background-color:".$v['color']."\"" : "").">".$v['value']."</span>";
			if(isset($menu[$k]) && isset($menu[$k]['item'])) {
				if(sizeof($menu[$k]['item'])>1) {
					$menu[$k]['cat'][0]['title'] = $badge.$menu[$k]['item'][0]['title'];
				} else {
					$menu[$k]['item'][0]['title'] = $badge.$menu[$k]['cat'][0]['title'];
				}
			}
		}
		foreach($menu as $k => $v) {
			if(sizeof($menu[$k]['item'])>1) {
				$type = "cat";
			} else {
				$type = "item";
			}
			$title = $menu[$k][$type][0]['title'];
			$title = preg_replace("#\{L_([\"']|)(.+?)([\"']|)\}#is", "$2", $title);
			if(isset(self::$badge[$k])) {
				$menu[$k][$type][0]['title'] = "<span class=\"badge".(!empty(self::$badge[$k]['class']) ? " ".self::$badge[$k]['class'] : "")." pull-right\"".(!empty(self::$badge[$k]['color']) ? " style=\"background-color:".self::$badge[$k]['color']."\"" : "").">".self::$badge[$k]['value']."</span>".$menu[$k][$type][0]['title'];
			} else if(isset(self::$badge[$title])) {
				$menu[$k][$type][0]['title'] = "<span class=\"badge".(!empty(self::$badge[$title]['class']) ? " ".self::$badge[$title]['class'] : "")." pull-right\"".(!empty(self::$badge[$title]['color']) ? " style=\"background-color:".self::$badge[$title]['color']."\"" : "").">".self::$badge[$title]['value']."</span>".$menu[$k][$type][0]['title'];
			}
		}
	}

	function changeAdminStyle() {
		return '<style>'.
				'.sidebar-menu .main-menu a > i + span { padding-right: 0px; min-width: 73%; }'.
				'.badge.pull-right { margin-top: 0px; }'.
			'</style>';
	}

}
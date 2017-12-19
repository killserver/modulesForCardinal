<?php
/*
CREATE TABLE `menu` (
`mId` int not null auto_increment,
`mMenu` int(11) not null,
`mLevel` int(11) not null,
`mIcon` varchar(255) not null,
`mOpened` varchar(255) not null,
`mClass` varchar(255) not null,
`mContent` varchar(255) not null,
`mPage` varchar(255) not null,
`mParentId` int(11) not null,
primary key `id`(`mId`)
) ENGINE=MyISAM;
 */

class MenuAdmin extends Core {

	function childrenRebuild($id, $arr, $parent = 0) {
		foreach($arr as $v) {
			$child = false;
			if(isset($v['children'])) {
				$child = $v['children'];
				$parentNext = $v['uid']-1;
				//$parent = db::last_id("menu");
				unset($v['children']);
			}
			$v['parent_id'] = $parent;
			$arrs = array();
			$arrs['mMenu'] = $id;
			$arrs['mUId'] = $v['uid']-1;
			$arrs['mLevel'] = $v['level'];
			$arrs['mIcon'] = $v['icon'];
			$arrs['mOpened'] = $v['opened'];
			$arrs['mClass'] = $v['class'];
			$arrs['mContent'] = $v['content'];
			$arrs['mPage'] = $v['page'];
			$arrs['mParentId'] = $v['parent_id'];
			db::doquery("INSERT INTO {{menu}} SET ".implode(", ", array_map(array(&$this, "build"), array_keys($arrs), array_values($arrs))));
			if(!is_bool($child)) {
				$this->childrenRebuild($id, $child, ($parentNext));
			}
		}
	}

	function build($k, $v) {
		return "`".$k."` = ".db::escape($v);
	}

	function builder($arr, &$count, $i = 1) {
		$ret = "";
		foreach($arr as $v) {
			if(isset($v['mPage'])) {
				$ret .= '<li data-page="'.$v['mPage'].'" data-content="'.$v['mContent'].'" data-class="'.$v['mClass'].'" data-opened="'.$v['mOpened'].'" data-icon="'.$v['mIcon'].'" data-level="'.$v['mLevel'].'" data-uid="'.$i.'">
									<div class="uk-nestable-item" data-toggle="collapse" href="#collapseTwo-'.$i.'">
										<div class="uk-nestable-handle"></div>
										<div data-nestable-action="toggle"></div>
										<div class="list-label">'.($v['mIcon']!=="" ? '<i class="fa-'.$v['mIcon'].'" style="width:2.5em;text-align:center;font-size:1.35em;"></i>' : "").'<span>'.($v['mContent']!=="" ? $v['mContent'] : '{L_"Не заданно"}').'</span></div>
										<div class="btn btn-red btn-single pull-right remove">x</div>
									</div>
									<div id="collapseTwo-'.$i.'" class="panel panel-collapse collapse">
										<div class="panel-body">
										</div>
									</div>';
			}
			if(isset($v['children'])) {
				$ret .= '<ul>';
				$ret .= $this->builder($v['children'], $count, ($i+1));
				$ret .= '</ul>';
			}
			if(isset($v['mPage'])) {
				$ret .= '</li>';
			}
			$i++;
			$count++;
		}
		return $ret;
	}

	function __construct() {
		if(isset($_GET['list'])) {
			$file = new Parser("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css");
			$file = $file->get();
			preg_match_all("#\.fa-(.+?)\:before#", $file, $arr);
			$arr = $arr[1];
			$ret = "";
			for($i=0;$i<sizeof($arr);$i++) {
				$ret .= "<a href=\"#\" class=\"selectIcon pull-left\" data-icon=\"".$arr[$i]."\"><i class=\"fa fa-stack fa-fw fa-2x fa-".$arr[$i]."\"></i></a>";
			}
			callAjax();
			echo $ret;
			return false;
		}
		if(isset($_GET['delete']) && is_numeric($_GET['delete']) && $_GET['delete']>0) {
			db::doquery("DELETE FROM {{menu}} WHERE `mMenu` = ".intval($_GET['delete']));
			location("{C_default_http_local}{D_ADMINCP_DIRECTORY}/?pages=MenuAdmin");
			return false;
		}
		if(isset($_GET['add']) || (isset($_GET['edit']) && is_numeric($_GET['edit']) && $_GET['edit']>0)) {
			$additions = HTTP::getServer("REQUEST_URI");
			$additions = nsubstr($additions, nstrlen(config::Select("default_http_local")));
			$additions = str_replace((defined("ADMINCP_DIRECTORY") ? ADMINCP_DIRECTORY : "admincp.php"), "", $additions);
			$additions = str_replace("/?pages=MenuAdmin", "", $additions);
			templates::assign_var("additions", $additions);
			$post = file_get_contents("php://input");
			if(strlen($post)>0) {
				$post = json_decode($post, true);
				if(isset($_GET['add'])) {
					$id = db::doquery("SELECT DISTINCT MAX(`mMenu`) FROM {{menu}}");
					$id = $id[0];
					$id++;
				} else if(isset($_GET['edit']) && is_numeric($_GET['edit']) && $_GET['edit']>0) {
					$id = intval($_GET['edit']);
				}
				db::doquery("DELETE FROM {{menu}} WHERE `mMenu` = ".$id);
				$this->childrenRebuild($id, $post);
				callAjax();
				HTTP::echos("1");
				return false;
			}
			templates::assign_var("menuBuilder", "");
			templates::assign_var("countItems", "undefined");
			if(isset($_GET['edit']) && is_numeric($_GET['edit']) && $_GET['edit']>0) {
				$id = intval($_GET['edit']);
				db::doquery("SELECT * FROM {{menu}} WHERE `mMenu` = ".$id." ORDER BY `mParentId` ASC", true);
				$arr = array();
				while($row = db::fetch_assoc()) {
					if($row['mParentId']>0) {
						$arr[$row['mParentId']]['children'][] = $row;
					} else {
						$arr[$row['mUId']] = $row;
					}
				}
				ksort($arr);
				$count = 0;
				templates::assign_var("menuBuilder", $this->builder($arr, $count));
				templates::assign_var("countItems", ($count>0 ? $count : "undefined"));
			}
			$levels = userlevel::all();
			$levels = array_keys($levels);
			foreach($levels as $l) {
				templates::assign_vars(array("level" => $l), "levels", $l);
			}
			$this->Prints("MenuAdmin");
			return false;
		}
		db::doquery("SELECT *, (SELECT DISTINCT `mContent` FROM {{menu}} WHERE `mMenu` = {{menu}}.`mMenu` ORDER BY `mUId` ASC LIMIT 1) as `mContent` FROM {{menu}} WHERE `mParentId` = 0 GROUP BY `mMenu`", true);
		while($row = db::fetch_assoc()) {
			templates::assign_vars($row, "menuTmp", $row['mId']);
		}
		$this->Prints("MenuAdminMain");
	}

}
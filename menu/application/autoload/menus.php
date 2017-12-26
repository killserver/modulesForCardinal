<?php

class menus {

	function start($id) {
		$ret = "";
		if(db::getTable("menu")!==false) {
			db::doquery("SELECT * FROM {{menu}} WHERE `mMenu` = ".$id." ORDER BY `mId` ASC", true);
			$arr = array();
			while($row = db::fetch_assoc()) {
				if($row['mParentId']>0) {
					$arr[$row['mParentId']]['children'][] = $row;
				} else {
					$arr[$row['mId']] = $row;
				}
			}
			$arr = array_values($arr);
			$countClass = 0;
			$ret = $this->build($arr, $countClass);
			if($countClass>0) {
				regCssJs("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", "css");
			}
		}
		return $ret;
	}

	private function build($arr, &$countClass, $level = 0) {
		$ret = '<ul data-level="'.($level+1).'">';
		$lang = Route::param("lang");
		foreach($arr as $v) {
			if($v['mIcon']!=="") {
				$countClass++;
			}
			$ret .= (strpos($v['mPage'], "@")!==false ? "<!--email_off-->" : "").'<li'.($v['mClass']!=="" ? ' class="'.$v['mClass'].'"' : "").'>'.
						'<a href="'.(strpos($v['mPage'], "@")!==false ? $v['mPage'] : '{C_default_http_local}'.($lang!==false ? $lang."/" : "").($v['mPage']!=="" ? $v['mPage'] : "#")).'"'.($v['mClass']!=="" ? ' class="'.$v['mClass'].'"' : "").''.($v['mOpened']!=="" ? ' target="'.$v['mOpened'].'"' : "").'>'.
							($v['mIcon']!=="" ? '<i class="fa fa-'.$v['mIcon'].'"></i>' : "").
							'<span>'.$v['mContent'].'</span>'.
						'</a>';
			if(isset($v['children'])) {
				$ret .= $this->build($v['children'], $countClass, ($level+1));
			}
			$ret .= "</li>".(strpos($v['mPage'], "@")!==false ? "<!--/email_off-->" : "");
		}
		$ret .= "</ul>";
		return $ret;
	}

}
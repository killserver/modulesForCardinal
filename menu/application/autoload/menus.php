<?php

class menus {

	function start($id) {
		$data = func_get_args();
		array_shift($data);
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
			$ret = $this->build($arr, $countClass, $id, $data);
			if($countClass>0) {
				regCssJs("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", "css");
			}
		}
		return $ret;
	}

	function eae_encode_str( $string ) {
		$chars = str_split( $string );
		$seed = mt_rand( 0, (int) abs( crc32( $string ) / nstrlen( $string ) ) );
		foreach ( $chars as $key => $char ) {
			$ord = ord( $char );
			if ( $ord < 128 ) { // ignore non-ascii chars
				$r = ( $seed * ( 1 + $key ) ) % 100; // pseudo "random function"
				if ( $r > 60 && $char !== '@' && $char !== '.' ) ; // plain character (not encoded), except @-signs and dots
				else if ( $r < 45 ) $chars[ $key ] = '&#x' . dechex( $ord ) . ';'; // hexadecimal
				else $chars[ $key ] = '&#' . $ord . ';'; // decimal (ascii)
			}
		}
		return implode( '', $chars );
	}

	private function build($arr, &$countClass, $id, $data, $level = 0) {
		$tag = execEvent("startMenu", "ul", $id, $data);
		$start = execEvent("bothMenu", "span", $id, $data);
		$contain = execEvent("containMenu", "li", $id, $data);
		$ret = (!empty($tag) ? '<'.$tag.' data-level="'.($level+1).'">' : '');
		$lang = Route::param("lang");
		foreach($arr as $v) {
			if($v['mIcon']!=="") {
				$countClass++;
			}
			$tr = lang::get_lang($v['mContent']);
			if($tr==="") {
				$tr = $v['mContent'];
			}
			$ret .= (strpos($v['mPage'], "@")!==false ? "<!--email_off-->" : "").(!empty($contain) ? '<'.$contain.($v['mClass']!=="" ? ' class="'.$v['mClass'].'"' : "").'>' : "").
						'<a href="'.(strpos($v['mPage'], "tel:")!==false||strpos($v['mPage'], "@")!==false||strpos($v['mPage'], "http")!==false ? (strpos($v['mPage'], "@")!==false ? $this->eae_encode_str($v['mPage']) : $v['mPage']) : '{C_default_http_local}'.($lang!==false ? $lang."/" : "").($v['mPage']!=="" ? $v['mPage'] : "#")).'"'.($v['mClass']!=="" ? ' class="'.$v['mClass'].'"' : "").''.($v['mOpened']!=="" ? ' target="'.$v['mOpened'].'"' : "").'>'.
							($v['mIcon']!=="" ? '<i class="fa fa-'.$v['mIcon'].'"></i>' : "").
							(empty($start) ? '' : '<'.$start.'>').(strpos($v['mPage'], "@")!==false ? $this->eae_encode_str($tr) : $tr).(empty($start) ? '' : '</'.$start.'>').
						'</a>';
			if(isset($v['children'])) {
				$ret .= $this->build($v['children'], $countClass, $id, $data, ($level+1));
			}
			$ret .= (!empty($contain) ? "</".$contain.">" : "").(strpos($v['mPage'], "@")!==false ? "<!--/email_off-->" : "");
		}
		$ret .= execEvent("endMenu", "", $id, $data);
		$ret .= (empty($tag) ? '' : "</".$tag.">");
		return $ret;
	}

}
<?php

class Feedback_form extends Core {

	function save($altname = "") {
		$_POST = array_filter($_POST);
		if(sizeof($_POST)>0) {
			if(($title = Arr::get($_POST, "title", false))===false) {
				die();
			}
			if(($form = Arr::get($_POST, "form", false))===false) {
				die();
			}
			if(($send_mess = Arr::get($_POST, "send_mess", false))===false) {
				die();
			}
			$address = Arr::get($_POST, "address", "");
			if(!file_exists(PATH_CACHE_USERDATA."feedback".DS) || !is_dir(PATH_CACHE_USERDATA."feedback".DS)) {
				@mkdir(PATH_CACHE_USERDATA."feedback".DS, 0777);
			}
			if(!is_writable(PATH_CACHE_USERDATA."feedback".DS)) {
				@chmod(PATH_CACHE_USERDATA."feedback".DS, 0777);
			}
			$altname = (empty($altname) ? ToTranslit($title) : $altname);
			preg_match_all('#\[(.+?) name=[\'"](.+?)[\'"].*?\]#is', $form, $all);
			$find = array();
			for($i=0;$i<sizeof($all[2]);$i++) {
				$find[] = $all[2][$i];
			}
			$arr = array("title" => $title, "address" => $address, "form" => $form, "send_mess" => $send_mess, "fields" => $find);
			@file_put_contents(PATH_CACHE_USERDATA."feedback".DS.$altname.".".ROOT_EX, '<?php die(); ?>'.CardinalJSON::save($arr));
			location("./?pages=Feedback_form");
		}
	}
	
	function __construct() {
		$len = strlen('<?php die(); ?>');
		if(($t = Arr::get($_GET, 'pageType'))!==false && $t=="Add") {
			$this->save();
			templates::assign_var("show", "Add");
			templates::assign_var("type", "Add");
			$this->Prints("FeedbackAdd");
			return;
		} else if(($t = Arr::get($_GET, 'pageType'))!==false && $t=="MultiAction") {
			$list = Arr::get($_POST, "delete", array());
			for($i=0;$i<sizeof($list);$i++) {
				if(file_exists(PATH_CACHE_USERDATA."feedback".DS.$list[$i].".".ROOT_EX)) {
					@unlink(PATH_CACHE_USERDATA."feedback".DS.$list[$i].".".ROOT_EX);
				}
			}
			location("./?pages=Feedback_form");
			return;
		} else if(($t = Arr::get($_GET, 'pageType'))!==false && $t=="Copy" && ($t2 = Arr::get($_GET, 'viewId'))!==false && is_string($t2)) {
			if(file_exists(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX)) {
				$files1 = read_dir(PATH_CACHE_USERDATA."feedback".DS, $t2);
				$files2 = read_dir(PATH_CACHE_USERDATA."feedback".DS, preg_replace("/[0-9]+/", "", $t2));
				@copy(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX, PATH_CACHE_USERDATA."feedback".DS.$t2.(sizeof($files1)+sizeof($files2)-1).".".ROOT_EX);
			}
			location("./?pages=Feedback_form");
			return;
		} else if(($t = Arr::get($_GET, 'pageType'))!==false && $t=="Delete" && ($t2 = Arr::get($_GET, 'viewId'))!==false && is_string($t2)) {
			if(file_exists(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX)) {
				@unlink(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX);
			}
			location("./?pages=Feedback_form");
			return;
		} else if(($t = Arr::get($_GET, 'pageType'))!==false && $t=="Edit" && ($t2 = Arr::get($_GET, 'viewId'))!==false && is_string($t2)) {
			if(file_exists(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX)) {
				$this->save($t2);
				templates::assign_var("type", "Edit&viewId=".$t2);
				templates::assign_var("show", "Edit");
				$f = file_get_contents(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX);
				$f = substr($f, $len);
				$f = json_decode($f, true);
				templates::assign_vars($f);
				$this->Prints("FeedbackAdd");
			} else {
				$this->Prints("404");
			}
			return;
		} else if(($t = Arr::get($_GET, 'pageType'))!==false && $t=="Delete" && ($t2 = Arr::get($_GET, 'viewId'))!==false && is_string($t2)) {
			if(file_exists(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX)) {
				@unlink(PATH_CACHE_USERDATA."feedback".DS.$t2.".".ROOT_EX);
			}
			location("./?pages=Feedback_form");
			return;
		}
		$dir = read_dir(PATH_CACHE_USERDATA."feedback".DS, ".".ROOT_EX);
		for($i=0;$i<sizeof($dir);$i++) {
			$f = file_get_contents(PATH_CACHE_USERDATA."feedback".DS.$dir[$i]);
			$f = substr($f, $len);
			$f = json_decode($f, true);
			templates::assign_vars(array(
				"name" => Arr::get($f, "title"),
				"filename" => str_Replace(".".ROOT_EX, "", $dir[$i]),
				"address" => Arr::get($f, "address"),
			), "feedback");
		}
		$this->Prints("FeedbackMain");
	}

}

?>
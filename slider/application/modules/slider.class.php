<?php

class slider extends modules {

	function __construct() {
		$this->regCssJs("https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css", "css");
		$this->regCssJs(array("{C_default_http_local}js/jquery.bxslider.min.js", "{C_default_http_local}js/cardinalSlider.min.js"), "js");
		KernelArcher::excludeField("add", "Shield", array("slide_descr"));
		KernelArcher::callback("Shield", "TraceOn", array(&$this, "RebuildShields"));
		KernelArcher::callback("ShieldFunc", "slider::RebuildShield");
		KernelArcher::callback("AddModel", array(&$this, "RebuildAddModel"));
		KernelArcher::callback("Show", array(&$this, "RebuildShow"));
		KernelArcher::callback("EditModel", array(&$this, "RebuildEditModel"));
		KernelArcher::callback("TakeUpload", array(&$this, "RebuildTakeUpload"));
		KernelArcher::callback("TakeDelete", array(&$this, "RebuildTakeDelete"));
		KernelArcher::callback("TakeAddModel", array(&$this, "RebuildTakeAddModel"));
		KernelArcher::callback("TakeEditModel", array(&$this, "RebuildTakeEditModel"));
	}

	public static function installation() {
		self::create_table("slider", " `slide_id` int(11) NOT NULL AUTO_INCREMENT,".
										" `slide_title` varchar(255) NOT NULL DEFAULT '',".
										" `slide_img` varchar(255) NOT NULL DEFAULT '',".
										" `slide_descr` longtext NOT NULL DEFAULT '',".
										" PRIMARY KEY `id` (`slide_id`)");
	}

	public static $version = "1.4";

	public static function updater($version) {
		if($version=="1.0") {
			config::Update("templateSlider", "slider");
		}
	}

	public function RebuildTakeDelete($model, $models) {
		if(isset($model->slide_img)) {
			$model->pathForUpload = array("slide_img" => "uploads/slider/");
			$model->setAttribute("slide_img", "Type", "image");
			$model->setAttribute("slide_img", "allowUpload", "image");
		}
		return array("model" => $model, "models" => $models);
	}

	public function RebuildShields($table, $page, $model, $tpl) {
		defines::add("DisableSort", "0");
		return $tpl;
	}

	public function RebuildShow($table, $tpl, $model) {
		$model->SetTable($table);
		return array($table, $tpl, $model);
	}

	public static function RebuildShield($row) {
		if(isset($row['slide_img'])) {
			$row['slide_img'] = "<img src=\"{C_default_http_local}".$row['slide_img']."\" width=\"200\">";
		}
		return $row;
	}

	public function RebuildTakeUpload($model, $field, $id, $file, $path, $type = "", $i = -1) {
		return array($model, $field, $id, $file, $path, $type, "fileName" => $id.($i>=0 ? "_".$i : ""));
	}

	public static function RebuildEditModel($model, &$exc = array()) {
		if(isset($model->slide_img)) {
			$model->pathForUpload = array("slide_img" => "uploads/slider/");
			$model->setAttribute("slide_img", "Type", "image");
			$model->setAttribute("slide_img", "allowUpload", "image");
		}
		return $model;
	}

	function RebuildAddModel($model, &$exc = array()) {
		if(isset($model->slide_img)) {
			$model->pathForUpload = array("slide_img" => "uploads/slider/");
			$model->setAttribute("slide_img", "Type", "image");
			$model->setAttribute("slide_img", "allowUpload", "image");
		}
		return $model;
	}

	function RebuildTakeAddModel($model, $id, $countCall) {
		if(isset($model->slide_img)) {
			$model->pathForUpload = array("slide_img" => "uploads/slider/");
			$model->setAttribute("slide_img", "Type", "image");
			$model->setAttribute("slide_img", "allowUpload", "image");
		}
		return $model;
	}

	function RebuildTakeEditModel($model, $id, $countCall) {
		if(isset($model->slide_img)) {
			$model->pathForUpload = array("slide_img" => "uploads/slider/");
			$model->setAttribute("slide_img", "Type", "image");
			$model->setAttribute("slide_img", "allowUpload", "image");
		}
		return $model;
	}

}
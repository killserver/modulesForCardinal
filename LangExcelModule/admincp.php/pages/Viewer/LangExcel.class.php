<?php

class LangExcel extends Core {

	function rest($msg, $error = false) {
		$res = array("error" => false, "msg" => "");
		if(isset($msg)) {
			$res["msg"] = $msg;
		}
		if($error == true) {
			$res["error"] = $error;
		}
		ajax($res);
	}

	public $translate_DB = array(
		"original" => "№",
		"original" => "Оригинал",
		"translate" => "Перевод",
	);

	private function translateError($langs) {
    	$lang = modules::get_lang($langs);
    	if(!empty($isset) && $isset!='""') {
			$lang = $isset;
		} else {
			$lang = $langs;
		}
		return $lang;
	}

	function __construct() {
		$download = (isset($_GET["download"]) ? $_GET["download"] : false);
		if($download != false) {
			$this->excel(false, $download);
		}
		$support = lang::support(true);
		$supports = lang::translateSupport();
		sortByValue($supports);
		templates::assign_var("json", json_encode($supports));
		$supports = array_keys($supports);
		if(isset($_GET["save"])) {
			$langs = $_GET["save"];
			if(in_array($langs, $supports)) {
				if(!in_array($langs, $support)) {
					lang::Update($langs, 'lang_ini', $langs);
				}
				lang::set_lang($langs);
				lang::Update($langs, rawurldecode(Arr::get($_POST, 'original')), rawurldecode(Arr::get($_POST, 'translate')));
				$this->rest("comp");
			} else {
				$this->rest($this->translateError("Язык не поддерживается"), true);
			}
		}
		if(isset($_GET["get"])) {
			$this->excel($_GET['get'], false, (isset($_GET['templateOnly'])));
		}
		for($i=0;$i<sizeof($support);$i++) {
			$langer = nucfirst($support[$i]);
			templates::assign_vars(array("lang" => $support[$i], "langer" => $langer), "langList");
		}
		$this->Prints("excel/excel");
	}
	function excel($langs = false, $download = false, $templateOnly = false) {
		if($download !== false) {
			$file = ini_get('upload_tmp_dir').DS.$download.".xls";
			header('Content-Type: application/vnd.ms-excel; charset=utf-8');
			header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename=lang_'.$langs.'.xls');
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));

			readfile($file);
			exit;
		}

		lang::set_lang($langs);
		if(!$templateOnly) {
			$lang = lang::init_lang(true);
		} else {
			$lang = array();
		}
		$admin = PATH_SKINS;
		$dir = read_dir($admin);
		for($z=0;$z<sizeof($dir);$z++) {
			$file = file_get_contents($admin.$dir[$z]);
			preg_match_all("#\{L_(['\"]|)(.+?)(\[(.*?)\]|)\\1\}#", $file, $match);
			for($i=0;$i<sizeof($match[2]);$i++) {
				$translate = lang::get_lang($match[2][$i]);
				$lang[$match[2][$i]] = (!empty($translate) ? $translate : $match[2][$i]);
			}
		}
		$admin = PATH_SKINS.config::Select("skins", "skins").DS;
		$dir = read_dir($admin);
		for($z=0;$z<sizeof($dir);$z++) {
			$file = file_get_contents($admin.$dir[$z]);
			preg_match_all("#\{L_(['\"]|)(.+?)(\[(.*?)\]|)\\1\}#", $file, $match);
			for($i=0;$i<sizeof($match[2]);$i++) {
				$translate = lang::get_lang($match[2][$i]);
				$lang[$match[2][$i]] = (!empty($translate) ? $translate : $match[2][$i]);
			}
		}

		$xls = new PHPExcel();
		$xls->setActiveSheetIndex(0);
		$sheet = $xls->getActiveSheet();
		
		$sheet->setTitle($langs);
		
		$abc = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		foreach($abc as $k => $v) {
			$sheet->getColumnDimension($v)->setAutoSize(true);
		}

		$cells = array();
		$cells[] = "id";
		$cells[] = "original";
		$cells[] = "translate";

		$line = 1;
		$col = 0;
		$cell = "";
		foreach($cells as $k) {
			$sheet->setCellValueByColumnAndRow($col, $line, $this->translate($k));
			$cell = $abc[$col].$line;

			$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('607D8B');
			$sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
			
			$sheet->setCellValueByColumnAndRow($col, $line+1, $k);
			$cell = $abc[$col].($line+1);

			$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('4F81BD');
			$sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
			
			$sheet->setCellValueByColumnAndRow($col, $line+2, $langs);
			$cell = $abc[$col].($line+2);

			$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('4F81BD');
			$sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');

			$col++;
		}
		$line = 4;
		$id = 1;
		foreach($lang as $key => $value) {
			$col = 0;
			$sheet->setCellValueByColumnAndRow($col, $line, $id);
			$cell = $abc[$col].$line;

			//$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('EEECE1');
			$sheet->getStyle($cell)->getAlignment()->setWrapText(true);
			
			//$sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $line, $key);
			$cell = $abc[$col].$line;

			//$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('EEECE1');
			$sheet->getStyle($cell)->getAlignment()->setWrapText(true);
			
			//$sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $line, $value);
			$cell = $abc[$col].$line;

			//$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('EEECE1');
			$sheet->getStyle($cell)->getAlignment()->setWrapText(true);
			
			//$sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$col++;
			$line++;
			$id++;
		}
		
		require_once(PATH_AUTOLOADS.'PHPExcel/Writer/Excel5.php');
		 
		$objWriter = new PHPExcel_Writer_Excel5($xls);
		$filename = time();
		$objWriter->save(ini_get('upload_tmp_dir').DS.$filename.".xls");
		
		$this->excel($langs, $filename, $templateOnly);
	}
	
	function translate($n) {
		if(isset($this->translate_DB[$n])) return $this->translate_DB[$n];
		else return $n;
	}

}
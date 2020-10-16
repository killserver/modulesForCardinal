<?php

class ExcelModelAdmin extends Core {
	
	public $model = "";
	public $exclude = array();

	function rest($msg, $error = false) {
		$res = array("error" => false,"msg" => "");
		if(isset($msg)) {
			$res["msg"] = $msg;
		}
		if($error == true) {
			$res["error"] = $error;
		}
		ajax($res);
		/*
		echo json_encode($res,JSON_UNESCAPED_UNICODE);
		die();
		*/
	}
	
	function __construct() {
		$this->model = (isset($_GET["model"]) ? $_GET["model"] : "");
		$download = (isset($_GET["download"]) ? $_GET["download"] : false);
		if(empty($this->model)) {
			$this->rest("model is empty", true);
		}
		$this->exclude = execEvent("excel_model_exclude_".$this->model, execEvent("excel_model_exclude", array(), $this->model));
		
		if($download != false) {
			$this->excel(false, $download);
		}

		if(isset($_GET["update"])) {
			execEvent("excel_model_model_".$this->model."_admincp");
			execEvent("excel_model_model_admincp");
			return false;
		}
		if(isset($_GET["save"])) {
			if(empty($_POST["id"])) {
				$this->rest("id not set", true);
			}
			$id = $_POST["id"];
			//var_dump($_POST);die();
			$WHERE = execEvent("excel_model_".$this->model."_id", execEvent("excel_model_id", $this->model));
			if(empty($WHERE) || $WHERE == $this->model) {
				$this->rest("where is not set", true);
			}
			$row = db::doquery("SELECT * FROM {{".$this->model."}} WHERE `".$WHERE."` = ".$id);			
			if(!empty($row)) {
				foreach($_POST as $key => $val) {
					if($key == "id") {
						continue;
					}
					$row[$key] = $val;
				}
				db::doquery("UPDATE {{".$this->model."}} SET ".implode(",", array_map(function($key, $val) { return "`".$key."` = ".db::escape($val); }, array_keys($row), array_values($row)))." WHERE `".$WHERE."` = ".$row[$WHERE]);
			}
			$this->rest("comp");
		}
		if(isset($_GET["get"])) {
			$this->excel($this->model);
		}
		$name = execEvent("excel_model_name_".$this->model, execEvent("excel_model_name", $this->model));
		$this->title('{L_"Выгрузка данных"}&nbsp;"'.$name.'"', true);
		
		templates::assign_var("model_data", $this->model);
		$this->Prints("excel/excel_model");
	}

	function excel($table = false, $download = false) {
		if($download !== false) {
			$name = execEvent("excel_model_name_".$this->model, execEvent("excel_model_name", $this->model));
			
			if(empty($name)) {
				$this->rest("name is empty: ".$name, true);
			}

			$file = $this->get_temp_dir().DS.$download.".xls";
			header('Content-Type: application/vnd.ms-excel; charset=utf-8');
			header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.config::Select("default_http_hostname")."_".$name.'.xls');
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));

			readfile($file);
			exit;
		}

		$xls = new PHPExcel();
		$xls->setActiveSheetIndex(0);
		$sheet = $xls->getActiveSheet();
		
		$sheet->setTitle($table);
		$sheet->getDefaultStyle()->applyFromArray(array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		));
		$abc = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		foreach($abc as $k => $v) {
			$sheet->getColumnDimension($v)->setWidth(300);
		}

		$cells = array();
		try {
			$a = modules::loadModel($table);
			$fields = $a->getArray();
			$fields = array_keys($fields);
			for($i=0;$i<sizeof($fields);$i++) {
				if(in_array($fields[$i], $this->exclude)) {
					continue;
				}
				$type = $a->getAttribute($fields[$i], "type");
				if(empty($type)) {
					$type = $a->getAttribute($fields[$i], "Type");
				}
				$cells[$fields[$i]] = $type;
			}
		} catch(Exception $ex) {
			db::doquery("SHOW FULL COLUMNS FROM {{".$table."}}", true);
			while($row = db::fetch_assoc()) {
				if(in_array($row["Field"], $this->exclude)) {
					continue;
				}
				$cells[$row["Field"]] = $row["Type"];
			}
		}

		$line = 1;
		$col = 0;
		$cell = "";
		foreach($cells as $k => $v) {
			$sheet->setCellValueByColumnAndRow($col, $line, $this->translate($k));
			$cell = $abc[$col].$line;

			$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('607D8B');
			$sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
			
			$sheet->setCellValueByColumnAndRow($col,$line+1,$k);
			$cell = $abc[$col].($line+1);

			$sheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('4F81BD');
			$sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');

			$col++;
		}
		$line = 3;
		db::doquery("SELECT * FROM {{".$table."}} ", true);
		while($row = db::fetch_array()) {
			$col = 0;
			foreach($cells as $k => $v) {
				$value = $row[$k];
				// $sheet->setCellValueByColumnAndRow($col,$line,$value);
				$cell = $abc[$col].$line;
				$sheet->setCellValueExplicit($cell, $value, PHPExcel_Cell_DataType::TYPE_STRING);

				$sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('EEECE1');
				$sheet->getStyle($cell)->getAlignment()->setWrapText(true);
				
				$col++;
			}
			$line++;
		}
		
		require_once(PATH_AUTOLOADS.'PHPExcel/Writer/Excel5.php');
		 
		$objWriter = new PHPExcel_Writer_Excel5($xls);
		$filename = time();
		$objWriter->save($this->get_temp_dir().DS.$filename.".xls");
		
		$this->excel(false, $filename);
	}
	
	function translate($n) {
		$translate = lang::get_if_translated($n);
		if(!empty($translate)) {
			return html_entity_decode($translate);
		}
		return $n;
	}

	function get_temp_dir() {
	    static $temp = '';
	    if($temp) {
	        return $temp;
	    }
	    if(function_exists('sys_get_temp_dir')) {
	        $temp = sys_get_temp_dir();
	        if(@is_dir($temp) && $this->is_writable($temp)) {
	            return $temp;
	        }
	    }
	    $temp = ini_get('upload_tmp_dir');
	    if(@is_dir($temp) && $this->is_writable($temp)) {
	        return $temp;
	    }
	    $temp = WP_CONTENT_DIR . '/';
	    if(is_dir( $temp ) && $this->is_writable($temp)) {
	        return $temp;
	    }
	    return '/tmp/';
	}

	function is_writable($path) {
	    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	        return $this->win_is_writable($path);
	    } else {
	        return @is_writable($path);
	    }
	}

	function win_is_writable($path) {
	    if($path[strlen($path) - 1] === '/') {
	        // If it looks like a directory, check a random file within the directory.
	        return win_is_writable($path.uniqid(mt_rand()).'.tmp');
	    } else if(is_dir($path)) {
	        // If it's a directory (and not a file), check a random file within the directory.
	        return win_is_writable( $path . '/' . uniqid( mt_rand() ) . '.tmp' );
	    }
	    // Check tmp file for read/write capabilities.
	    $should_delete_tmp_file = !file_exists($path);
	    $f = @fopen($path, 'a');
	    if(false === $f) {
	        return false;
	    }
	    fclose($f);
	    if($should_delete_tmp_file) {
	        unlink($path);
	    }
	    return true;
	}
	
}

?>
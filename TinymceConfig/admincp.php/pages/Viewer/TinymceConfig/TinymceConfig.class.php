<?php

class TinymceConfig extends Core {

	function __construct() {
		$plugins = array(
			"advlist" => "Расширенный список",
			"anchor" => "Якорь",
			"autolink" => "Автоматическая ссылка",
			"autoresize" => "Автоматическое изменение размера",
			"autosave" => "Автосохранение",
			"bbcode" => "BBCode",
			"charmap" => "Карта символов",
			"code" => "Код",
			"codesample" => "Образец кода",
			"colorpicker" => "Выбор цвета",
			"contextmenu" => "Контекстное меню",
			"directionality" => "Направление ввода",
			"fullpage" => "Полная страница",
			"fullscreen" => "Полноэкранный режим",
			"help" => "Помощь",
			"hr" => "Горизонтальная линия",
			"image" => "Картинка",
			"imagetools" => "Инструменты изображения",
			"importcss" => "Импорт CSS",
			"insertdatetime" => "Вставить дату / время",
			"legacyoutput" => "Унаследованный вывод",
			"link" => "Ссылка",
			"lists" => "Списки",
			"localautosave" => "Автосохранение в браузере",
			"media" => "Медиа",
			"nonbreaking" => "Не переносить на новую линию",
			"noneditable" => "Нередактируемость",
			"pagebreak" => "Разрыв страницы",
			"paste" => "Вставка",
			"preview" => "Превью",
			"print" => "Печать",
			"save" => "Сохранение",
			"searchreplace" => "Поиск и замена",
			"spellchecker" => "Программа проверки орфографии",
			"tabfocus" => "Фокус вкладки",
			"table" => "Таблица",
			"textcolor" => "Цвет текста",
			"toc" => "Таблица по контенту",
			"visualblocks" => "Визуальные блоки",
			"visualchars" => "Визуальные символы",
			"wordcount" => "Количество слов",
		);
		if(function_exists("execEvent")) {
			$plugins = execEvent("editorTinymce", $plugins);
		}

		if(file_exists(PATH_CACHE_USERDATA."configTinymce.json")) {
			$file = file_get_contents(PATH_CACHE_USERDATA."configTinymce.json");
		} else {
			$file = file_get_contents(PATH_MEDIA."configTinymce.json");
		}
		$file = str_replace("\r\n", "\n", $file);
		$file = str_replace("selectLang", "\"".lang::get_lg()."\"", $file);
		$file = preg_Replace("#/\* remove this \*/(.*?)/\* remove this \*/#is", "", $file);
		$file = str_iReplace(",\n\n}", "\n}", $file);
		$file = json_decode($file, true);

		if(sizeof($_POST)>0) {
			$js = ",".PHP_EOL.'/* remove this */'.PHP_EOL;
			$post = $_POST;
			if(isset($post['manager'])) {
				$js .= ''.
					'las_callback: function() { var content = this.content; /* content saved */ var time = this.time; /* time on save action */ console.log(content); console.log(time); },'.PHP_EOL.
					'external_filemanager_path: default_admin_link+"/assets/tinymce/filemanager/",'.PHP_EOL.
					'filemanager_title: "{L_\'Загрузка файлов\'}",'.PHP_EOL.
					'external_plugins: { "filemanager" : default_admin_link+"/assets/tinymce/filemanager/plugin.min.js"},'.PHP_EOL;
					$post['plugins']['responsivefilemanager'] = "on";
					unset($post['manager']);
			}
			$js .= 'readonly: (typeof(readOnlyEditor)==\'undefined\' ? 0 : 1),'.PHP_EOL.'/* remove this */'.PHP_EOL."}";
			if(isset($post['plugins'])) {
				foreach($post['plugins'] as $k => $v) {
					if($v=="off") {
						unset($post['plugins'][$k]);
					}
				}
				$post['plugins'] = array_keys($post['plugins']);
			}
			if(isset($post['language'])) {
				$post['language'] = "selectLang";
			}
			foreach($post as $k => $v) {
				if($v=="true" || $v=="on") {
					$post[$k] = true;
				} else if($v=="false") {
					$post[$k] = false;
				} else if($v=="off") {
					unset($post[$k]);
				}
			}
			$post = array_merge($file, $post);
			$post = JSONHelper::normalizer(json_encode($post));
			$post = nsubstr($post, 0, -1);
			$post = trim($post);
			$post .= $js;
			$post = str_Replace("\"selectLang\"", "selectLang", $post);
			file_put_contents(PATH_CACHE_USERDATA."configTinymce.json", $post);
			location("./?pages=TinymceConfig");
			return false;
		}

		$plugins = array_flip($plugins);
		$count = sizeof($plugins);
		$perColumn = 4;
		$all = ceil($count/4);
		$keys = array_keys($plugins);
		for($i=0;$i<$perColumn;$i++) {
			$counter1 = $all*$i;
			$counter2 = $all*($i+1);
			$name = "block".($i+1);
			for($z=$counter1;$z<$counter2;$z++) {
				templates::assign_vars(array(
					"name" => $keys[$z],
					"val" => in_array($plugins[$keys[$z]], $file['plugins']),
					"title" => $plugins[$keys[$z]],
				), $name);
			}
		}
		$file['manager'] = false;
		if(isset($file['plugins'])) {
			if(in_array("responsivefilemanager", $file['plugins'])) {
				$file['manager'] = true;
			}
			unset($file['plugins']);
		}
		templates::assign_var("tinymceConfigPage", str_replace("'", "\'", json_encode($file)));
		$this->Prints("TinymceConfig");
	}

}
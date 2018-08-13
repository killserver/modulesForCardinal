<?php
/*
Name: Перевод при транслитерации для Cardinal Engine
Version: 1.0
Author: killserver
 */
class translator extends modules {

	function __construct() {
		addEvent("totranslit", array($this, "translate"));
	}

	public static $version = "1.0";

	function translate($var) {
		$urlParams = array(
			'sl'       => "ru",
			'tl'       => "en",
			'q'        => urlencode($var),
		);
		$url = "https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";
		$prs = new Parser($url);
		$prs->post($urlParams);
		$prs->agent("AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1");
		$data = $prs->get();
		$data = $this->getSentencesFromJSON($data);
		return $data;
	}

	function getSentencesFromJSON($json) {
		$sentencesArray = json_decode($json, true);
		$sentences = "";
		foreach($sentencesArray["sentences"] as $s) {
			$sentences .= isset($s["trans"]) ? $s["trans"] : '';
		}
		return $sentences;
	}

}
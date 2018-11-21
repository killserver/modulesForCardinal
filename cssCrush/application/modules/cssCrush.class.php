<?php
/*
Name: Компилятор SCSS
Version: 1.0
Author: killserver
 */
class cssCrush extends modules {

	function __construct() {
		Route::set("cssCrush", "skins/<theme>/<path>.crush.css")->defaults(array(
			"class" => __CLASS__,
			"method" => "dt",
		));
	}

	function dt() {
        callAjax();
        header("Content-type: text/css; charset=".config::Select("charset"));
		HTTP::echos(CssForce::dt(file_get_contents(PATH_SKINS.Route::param("theme").DS.Route::param("path").".css")));
	}

}

spl_autoload_register(function($class) {
	if(stripos($class, 'csscrush')!==0) {
		return;
	}
	$class = str_ireplace('csscrush', 'CssCrush', $class);
	$subpath = implode(DS, array_map('ucfirst', explode('\\', $class)));
	require_once(dirname(__FILE__).DS."CssCrush".DS.$subpath.".".ROOT_EX);
});

class CssForce {
	static function dt($d) {
		$GLOBALS['CSSCRUSH_PROPERTY_SORT_ORDER'] = array();
		CssCrush\Crush::$process = new CssCrush\Process(array("source_map" => true, 'boilerplate' => true, ), array('type' => 'filter', 'data' => $d));
    	return CssCrush\Crush::$process->compile()->__toString();
	}
}

/**
 * Process CSS file and return a new compiled file.
 *
 * @see docs/api/functions.md
 */
function csscrush_file($file, $options = []) {

    try {
        CssCrush\Crush::$process = new CssCrush\Process($options, array('type' => 'file', 'data' => $file));
    }
    catch (\Exception $e) {
        CssCrush\warning($e->getMessage());

        return '';
    }

    return new CssCrush\File(CssCrush\Crush::$process);
}


/**
 * Process CSS file and return an HTML link tag with populated href.
 *
 * @see docs/api/functions.md
 */
function csscrush_tag($file, $options = [], $tag_attributes = []) {

    $file = csscrush_file($file, $options);
    if ($file && $file->url) {
        $tag_attributes['href'] = $file->url;
        $tag_attributes += array(
            'rel' => 'stylesheet',
            'media' => 'all',
        );
        $attrs = CssCrush\Util::htmlAttributes($tag_attributes, array('rel', 'href', 'media'));

        return "<link".$attrs." />\n";
    }
}


/**
 * Process CSS file and return CSS as text wrapped in html style tags.
 *
 * @see docs/api/functions.md
 */
function csscrush_inline($file, $options = [], $tag_attributes = []) {

    if (! is_array($options)) {
        $options = [];
    }
    if (! isset($options['boilerplate'])) {
        $options['boilerplate'] = false;
    }

    $file = csscrush_file($file, $options);
    if ($file && $file->path) {
        $tagOpen = '';
        $tagClose = '';
        if (is_array($tag_attributes)) {
            $attrs = CssCrush\Util::htmlAttributes($tag_attributes);
            $tagOpen = "<style".$attrs.">";
            $tagClose = '</style>';
        }
        return $tagOpen . file_get_contents($file->path) . $tagClose . "\n";
    }
}


/**
 * Compile a raw string of CSS string and return it.
 *
 * @see docs/api/functions.md
 */
function csscrush_string($string, $options = []) {

    if (! isset($options['boilerplate'])) {
        $options['boilerplate'] = false;
    }

    CssCrush\Crush::$process = new CssCrush\Process($options, array('type' => 'filter', 'data' => $string));

    return CssCrush\Crush::$process->compile()->__toString();
}


/**
 * Set default options and config settings.
 *
 * @see docs/api/functions.md
 */
function csscrush_set($object_name, $modifier) {

    if (in_array($object_name, array('options', 'config'))) {

        $pointer = $object_name === 'options' ? CssCrush\Crush::$config->options : CssCrush\Crush::$config;

        if (is_callable($modifier)) {
            $modifier($pointer);
        } elseif (is_array($modifier)) {
            foreach ($modifier as $key => $value) {
                $pointer->{$key} = $value;
            }
        }
    }
}


/**
 * Get default options and config settings.
 *
 * @see docs/api/functions.md
 */
function csscrush_get($object_name, $property = null) {

    if (in_array($object_name, array('options', 'config'))) {

        $pointer = $object_name === 'options' ? CssCrush\Crush::$config->options : CssCrush\Crush::$config;

        if (! isset($property)) {
            return $pointer;
        }
        else {
            return isset($pointer->{$property}) ? $pointer->{$property} : null;
        }
    }
    return null;
}


/**
 * Add plugin.
 *
 * @see docs/api/functions.md
 */
function csscrush_plugin($name, callable $callback) {

    CssCrush\Crush::plugin($name, $callback);
}


/**
 * Get stats from most recent compile.
 *
 * @see docs/api/functions.md
 */
function csscrush_stat() {

    $process = CssCrush\Crush::$process;
    $stats = $process->stat;

    // Get logged errors as late as possible.
    $stats['errors'] = $process->errors;
    $stats['warnings'] = $process->warnings;
    $stats += array('compile_time' => 0);

    return $stats;
}

<?php
/**
 * Debug2
 * A pretty print and easy to locate debug function
 *
 * @author   Felideo Desitale Paravimnce <felideo@gmail.com>
 * @license  MIT
 * @version  1.0.0
 */

function debug2($var, $legenda = false, $limit = 0, $exit = false){
	$var = retificar_UTF8($var);
	echo "\n<pre style='position: relative; z-index: 99999;''>";
	echo "============================ DEBUG2 OFICIAL ============================\n";

	if(!empty($legenda)){
		$tabs = str_repeat('=', (70 - strlen($legenda)) / 2);
		echo strtoupper("{$tabs} {$legenda} {$tabs} \n\n");
	}

	if(is_array($var) || is_object($var)){
		echo html_entity_decode(print_r($var, true));
	}elseif(is_string($var)){
		echo "string(" . strlen($var) . ") \"" . htmlentities($var) . "\"\n";
	}else{
		var_dump($var);
	}

	echo "\n";
	debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit);
	echo "\n";

	echo "</pre>";

	if($exit){
		exit;
	}
}

function retificar_UTF8($text) {
	if (is_string($text) || is_numeric($text)) {
		if ($text == utf8_decode($text)) {
			return retificar_UTF8($text);
		}

		return $text;
	}

	if (is_array($text)) {
		foreach ($text as $k => $v) {
			$text[$k] = retificar_UTF8($text[$k]);
		}
		return $text;
	}

	if (is_object($text)) {
		$l = get_object_vars($text);
		foreach ($l as $k => $v) {
			$text->$k = retificar_UTF8($v);
		}
	}

	return $text;
}

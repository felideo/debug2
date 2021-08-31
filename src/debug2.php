<?php
/**
 * Debug2
 * A pretty print and easy to locate debug function
 *
 * @author   Felideo Desitale Paravimnce <felideo@gmail.com>
 * @license  MIT
 * @version  1.0.0
 */

function debug2($var, $legenda = false, $exit = false){
	echo "\n<pre style='position: relative; z-index: 99999;''>";
	echo "============================ DEBUG2 OFICIAL ==========================\n";

	if($legenda){
		$legenda = \strtoupper($legenda);
		$tamanho = \strlen ($legenda);
		$tabs    = \str_repeat('&nbsp;', (70 - $tamanho) / 2);
		echo $tabs . $legenda . "\n\n";
	}
	if(\is_array($var) || \is_object($var)){
		echo \htmlentities(\print_r($var, true));
	}elseif(\is_string($var)){
		echo "string(" . \strlen($var) . ") \"" . \htmlentities($var) . "\"\n";
	}else{
		\var_dump($var);
	}
	echo "\n";
	\debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	echo "\n";

	echo "</pre>";

	if($exit){
		exit;
	}
}
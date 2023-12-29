<?php
/**
 * Debug2
 * A pretty print and easy to locate debug function
 *
 * @author   Felideo Desitale Paravimnce <felideo@gmail.com>
 * @license  MIT
 * @version  2.0.0
 */

function debug1($debug, $title = false, $exit = false){
	debug2_header('DEBUG', $title);
	debug2_body($debug);
	debug2_footer($exit, 3);
}

function debug2($debug, $title = false, $exit = false){
	debug2_header('DEBUG 2', $title);
	debug2_body($debug);
	debug2_footer($exit, 0);
}

function reflect($object, $method = false, $exit = false){
	$class = new \ReflectionClass($object);

	$debug = [
		'class      '         => $class->getShortName(),
		'namespace  '         => $class->getNamespaceName(),
		'full_name  '         => $class->getName(),
		'file       '         => str_replace('/home/vagrant/code/', '', $class->getFileName()),
		'constructor'         => '',
		'size       '         => $class->getStartLine() . ' => ' .  $class->getEndLine(),
		// 'doc_comment'         => $class->getDocComment(),
		'method'              => [],
		'parents'             => [],
		'traits'              => $class->getTraitNames(),
		'properties'          => [],
		'methods'             => [],
	];

	if(!empty($method)){
		$debug['method'] = [
			'method     ' => $method,
			'parameters ' => array_column($class->getMethod($method)->getParameters(), 'name'),
			'doc_comment' => $class->getMethod($method)->getDocComment(),
			'toString   ' => "\n" . $class->getMethod($method)->__toString(),
		];
	}

	if(isset($class->getParentClass()->name)){
		$debug['parents'] = get_class_parents($class->getParentClass()->name, [$class->getParentClass()->name]);
	}

	if(isset($class->getConstructor()->name)){
		$debug['constructor'] = $class->getConstructor()->name;
	}

	if(isset($class->getConstructor()->class)){
		$constructor = new \ReflectionClass($class->getConstructor()->class);

		$debug['constructor']     .= ' => ' . $class->getConstructor()->class
			. ' => ' . str_replace('/home/vagrant/code/', '', $constructor->getFileName());
	}

	foreach ($class->getProperties() as $index => $property) {
		if(isset($property->name)){
			$debug['properties'][$index] = $property->name;
		}

		if(isset($property->class)){
			$debug['properties'][$index] .= ' => ' . $property->class;
		}
	}

	foreach ($class->getMethods() as $index => $method) {
		if(isset($method->name)){
			$debug['methods'][$index] = $method->name;
		}

		if(isset($property->class)){
			$debug['methods'][$index] .= ' => ' . $method->class;
		}
	}

	$debug['parents']    = empty($debug['parents'])    ? '' : $debug['parents'];
	$debug['properties'] = empty($debug['properties']) ? '' : $debug['properties'];
	$debug['methods']    = empty($debug['methods'])    ? '' : $debug['methods'];
	$debug['method']     = empty($debug['method'])     ? '' : $debug['method'];

	debug2_header('REFLECT', $class->getName());
	debug2_body($debug);
	debug2_footer($exit, 3);
}

function get_class_parents($constructor, $anterior = []){
	$class = new \ReflectionClass($constructor);

	if(!isset($class->getParentClass()->name)){
		return $anterior;
	}

	$anterior[] = $class->getParentClass()->name;

	return get_class_parents($class->getParentClass()->name, $anterior, true);
}

function debug2_header($function, $title){
	echo "\n<pre style='position: relative; z-index: 99999;'>";
	echo "============================ {$function} OFICIAL ==========================\n";

	if($title){
		$title   = \strtoupper($title);
		$tamanho = \strlen ($title);
		$tabs    = \str_repeat('&nbsp;', (70 - $tamanho) / 2);
		echo $tabs . $title . "\n\n";
	}
}

function debug2_body($debug){
	if(\is_array($debug) || \is_object($debug)){
		echo \htmlentities(\print_r($debug, true));
	}elseif(\is_string($debug)){
		echo "string(" . \strlen($debug) . ") \"" . \htmlentities($debug) . "\"\n";
	}else{
		\var_dump($debug);
	}
}

function debug2_footer($exit = false, $limit = 0){
	echo "\n";
	\debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit);
	echo "\n</pre>";

	if($exit){
		exit;
	}
}

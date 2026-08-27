<?php
/**
 * Debug2
 * A pretty print and easy to locate debug function
 *
 * @author   Felideo Desitale Paravimnce <felideo@gmail.com>
 * @license  MIT
 * @version  2.0.8
 */

function debug0($debug, $title = false, $exit = false){
	debug2_header('DEBUG', $title);
	debug2_body($debug);
	debug2_footer($exit, false);
}

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

function query_log_start(){
	\DB::enableQueryLog();
}

function query_log_stop(){
	$queryLog = \DB::getQueryLog();

	$sql = [];

	foreach ($queryLog as $index => $lastQuery) {
		$sql[$index]      = $lastQuery['query'];
		$bindings = $lastQuery['bindings'];

		foreach ($bindings as $binding) {
		    if ($binding instanceof \Carbon\Carbon) {
		        $value = "'" . $binding->format('Y-m-d H:i:s') . "'";
		    } elseif (is_numeric($binding)) {
		        $value = $binding;
		    } else {
		    	// vale esse!!!
		        $value = "'" . str_replace("'", "''", $binding) . "'";
		    }
		    $sql[$index] = preg_replace('/\?/', $value, $sql[$index], 1);

		}

	    $sql[$index] = str_replace('\\', '\\\\', $sql[$index]);
	}

	debug1($sql);
}

function reflect($object, $params = false, $exit = false){
	if(empty($object)){
		debug1('Reflect => Objeto vazio');
		return;
	}

	if (is_callable($object)) {
		$retorno = reflect_clousure($object);
	}

	$class = new \ReflectionClass($object);

	if($class->isSubclassOf('Illuminate\Support\Facades\Facade') && $class->hasMethod('getFacadeRoot')){
		$facade = reflect_laravel_facade($class, $params, $object);
	}

	$class = reflect_class($class, $params, $object);

	if(!empty($facade)){
		$retorno                    = $facade;
		$retorno['debug']['original_facade'] = $class;
	}else{
		$retorno                    = $class;
	}

	debug2_header('REFLECT', $retorno['header'], false);
	debug2_body($retorno['debug']);
	debug2_footer($exit, $retorno['backtrace']);
}

function reflect_clousure($object){
	$class = new \ReflectionFunction($object);

	$defined_at = $class->getClosureScopeClass();

	if(isset($defined_at->name)){
		$defined_at = $defined_at->name;
	}

	$used_at    = $class->getClosureCalledClass();

	if(isset($used_at->name)){
		$used_at = $used_at->name;
	}

	$debug = [
		'class      '         => $class->getShortName(),
		'namespace  '         => $class->getNamespaceName(),
		'full_name  '         => $class->getName(),
		'file       '         => str_replace('/home/vagrant/code/', '', $class->getFileName()),
		'constructor'         => '',
		// 'size       '         => $class->getStartLine() . ' => ' .  $class->getEndLine(),
		'defined_at '         => $defined_at,
		'start_line '         => $class->getStartLine(),
		'end_line   '         => $class->getEndLine(),
		'used_at    '         => $used_at,
		'doc_comment'         => $class->getDocComment(),
		'parameters '  		  => array_map(function ($param) {
								    return $param->getName();
								}, $class->getParameters()),
	];

	return [
		'header'    => $class->getName(),
		'debug'     => $debug,
		'backtrace' => 4
	];
}

function reflect_laravel_facade($class, $params, $original_object){
	$class      = $class->getMethod('getFacadeRoot');
	$facadeRoot = $class->invoke(null);
	$class      = new \ReflectionClass($facadeRoot);

	$debug = [
		'type       '         => $class->getShortName(),
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

	// if(!empty($method)){
	// 	$debug['method'] = [
	// 		'method     ' => $method,
	// 		'parameters ' => array_column($class->getMethod($method)->getParameters(), 'name'),
	// 		'doc_comment' => $class->getMethod($method)->getDocComment(),
	// 		'toString   ' => "\n" . $class->getMethod($method)->__toString(),
	// 	];
	// }

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
			$debug['properties'][$index] .= get_visibility($property);
		}

		if(isset($property->class)){
			$debug['properties'][$index] .= ' => ' . $property->class;
		}
	}

	$debug = get_methods($class, $debug, $params);

	if(!empty($debug['traits'])){
		foreach ($debug['traits'] as $index => $trait) {
			$traitClass = new \ReflectionClass($trait);
			$debug      = get_methods($traitClass, $debug, $params);
		}
	}

	$debug['method'] = array_keys($debug['method']);

	$debug['parents']    = empty($debug['parents'])    ? '' : $debug['parents'];
	$debug['properties'] = empty($debug['properties']) ? [] : $debug['properties'];
	$debug['methods']    = empty($debug['methods'])    ? [] : $debug['methods'];
	$debug['method']     = empty($debug['method'])     ? '' : $debug['method'];

	sort($debug['traits']);
	sort($debug['properties']);
	sort($debug['methods']);

	$debug['methods']    = arrows_align($debug['methods']);
	$debug['properties'] = arrows_align($debug['properties']);


	return [
		'header'    => $class->getName(),
		'debug'     => $debug,
		'backtrace' => 3
	];
}

function reflect_class($class, $params, $original_object){
	// $class = new \ReflectionClass($object, $method);

	$debug = [
		'type       '         => $class->getShortName(),
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
		'attributes'          => [],
		'methods'             => [],
	];

	// if(!empty($method)){
	// 	$debug['method'] = [
	// 		'method     ' => $method,
	// 		'parameters ' => array_column($class->getMethod($method)->getParameters(), 'name'),
	// 		'doc_comment' => $class->getMethod($method)->getDocComment(),
	// 		'toString   ' => "\n" . $class->getMethod($method)->__toString(),
	// 	];
	// }

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
		$property->setAccessible(true);

		if(isset($property->name)){
			$debug['properties'][$index] = $property->name;
			$debug['properties'][$index] .= get_visibility($property);

			// deixa comentado para encurtar, descomentar quando necessario
			// $debug['attributes'][$property->getName()] = valor_atributo($property->getValue($original_object));
		}

		if(isset($property->class)){
			$debug['properties'][$index] .= ' => ' . $property->class;
		}
	}

	$debug = get_methods($class, $debug, $params);

	if(!empty($debug['traits'])){
		foreach ($debug['traits'] as $index => $trait) {
			$traitClass = new \ReflectionClass($trait);
			$debug      = get_methods($traitClass, $debug, $params);
		}
	}

	$debug['method'] = array_keys($debug['method']);

	$debug['parents']    = empty($debug['parents'])    ? '' : $debug['parents'];
	$debug['properties'] = empty($debug['properties']) ? [] : $debug['properties'];
	$debug['attributes'] = empty($debug['attributes']) ? [] : $debug['attributes'];

	if(empty($debug['attributes'])){
		unset($debug['attributes']);
	}

	$debug['methods']    = empty($debug['methods'])    ? [] : $debug['methods'];
	$debug['method']     = empty($debug['method'])     ? '' : $debug['method'];

	sort($debug['traits']);
	sort($debug['properties']);
	sort($debug['methods']);

	$debug['methods']    = arrows_align($debug['methods']);
	$debug['properties'] = arrows_align($debug['properties']);

	return [
		'header'    => $class->getName(),
		'debug'     => $debug,
		'backtrace' => 3
	];
}

function valor_atributo($valor){
	if(!is_object($valor)){
		return $valor;
	}

	$class = new ReflectionClass($valor);

	return $class->getName();
}

function reflectm($object, $method, $exit = false){
	$class = new \ReflectionClass($object);

	$debug = [
		'type       '         => $class->getShortName(),
		'class      '         => $class->getShortName(),
		'namespace  '         => $class->getNamespaceName(),
		'full_name  '         => $class->getName(),
		'file       '         => str_replace('/home/vagrant/code/', '', $class->getFileName()),
		'constructor'         => '',
		'size       '         => $class->getStartLine() . ' => ' .  $class->getEndLine(),
		'method'              => [],
	];

	$debug['method'] = [
		'method     ' => $method,
		'parameters ' => array_column($class->getMethod($method)->getParameters(), 'name'),
		'doc_comment' => $class->getMethod($method)->getDocComment(),
		'toString   ' => "\n" . $class->getMethod($method)->__toString(),
	];

	$retorno = [
		'header'    => $class->getName(),
		'debug'     => $debug,
		'backtrace' => 3
	];

	debug2_header('REFLECT METHOD', $retorno['header'], false);
	debug2_body($retorno['debug']);
	debug2_footer($exit, $retorno['backtrace']);
}

function get_methods($class, $debug, $params = false){
	foreach ($class->getMethods() as $index => $method) {
		$parametros            = [];
		$parametros_formatados = '';

		if(!empty($params)){
			$parametros_formatados = '()';

			foreach ($method->getParameters() as $parametro) {
			    $tipo = $parametro->hasType() ? $parametro->getType() . ' ' : '';
			    $parametros[] = $tipo . '$' . $parametro->getName();
			}

			if(!empty($parametros)){
				$parametros_formatados = '(' . implode(', ', $parametros) . ')';
			}
		}

		if(isset($method->name)){
			$debug['methods'][md5($method->name)] = $method->name . $parametros_formatados;
			$debug['methods'][md5($method->name)] .= get_visibility($method);
		}

		if(isset($method->class)){
			$debug['methods'][md5($method->name)] .= ' => ' . $method->class;
		}
	}



	// debug1($debug);
	// exit;

	return $debug;
}

function get_visibility($object){
	$visibility = '';

	if($object->isPublic()){
		$visibility .= ' => public';
	}

	if($object->isPrivate()){
		$visibility .= ' => private';
	}

	if($object->isProtected()){
		$visibility .= ' => protected';
	}

	return $visibility;
}

function arrows_align($array){
	// 1. Separar os elementos corretamente
	$parsedData = array();
	foreach ($array as $line) {
	    $parts = preg_split('/\s*=>\s*/', $line); // Divide pelo "=>" ignorando espaços extras
	    if (count($parts) === 3) {
	        $parsedData[] = array(
	            'method'     => trim($parts[0]),
	            'visibility' => trim($parts[1]),
	            'class'      => trim($parts[2]),
	        );
	    }
	}

	// 2. Calcular os comprimentos máximos para alinhamento
	$maxMethodLength = 0;
	$maxClassLength = 0;

	foreach ($parsedData as $item) {
	    $maxMethodLength = max($maxMethodLength, strlen($item['method']));
	    $maxClassLength = max($maxClassLength, strlen($item['visibility']));
	}

	// 3. Modificar o array original com os valores formatados
	foreach ($parsedData as $key => $item) {
	    $maxMethodLengthFix = $maxMethodLength;

	    if($key < 10){
	    	$maxMethodLengthFix +=3;
	    }

	    if($key >= 10 && $key < 100){
	    	$maxMethodLengthFix +=2;
	    }

	    if($key >= 100){
	    	$maxMethodLengthFix +=1;
	    }

	    $array[$key] = sprintf(
	        "%-{$maxMethodLengthFix}s => %-{$maxClassLength}s => %s",
	        $item['method'],
	        $item['visibility'],
	        $item['class']
	    );
	}

	return $array;
}

function get_class_parents($constructor, $anterior = []){
	$class = new \ReflectionClass($constructor);

	if(!isset($class->getParentClass()->name)){
		return $anterior;
	}

	$anterior[] = $class->getParentClass()->name;

	return get_class_parents($class->getParentClass()->name, $anterior, true);
}

function debug2_header($function, $title, $upper = true){
	echo "\n<pre style='position: relative; z-index: 99999;'>";
	echo "============================ {$function} OFICIAL ==========================\n";

	if($title){
		$title   = $title;

		if($upper){
			$title   = \strtoupper($title);
		}

		$tamanho = \strlen ($title);
		$times   = (70 - $tamanho) / 2 > 0
			? (70 - $tamanho) / 2
			: 0;
		$tabs    = \str_repeat('&nbsp;', $times);
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

	if($limit !== false){
		echo "\n";
		\debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit);
	}
	echo "\n</pre>";

	if($exit){
		exit;
	}
}

function get_backtrace(){
	return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
}
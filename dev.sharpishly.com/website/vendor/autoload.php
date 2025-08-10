<?php

$Directory = new RecursiveDirectoryIterator(__DIR__);

$it = new RecursiveIteratorIterator($Directory);

$it = new RegexIterator($it, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($it as $p) {
			
		$class = str_replace('\\', '/', $it->key());
						
		require_once $class;
	
	
}
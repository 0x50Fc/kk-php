<?php

namespace kk;

/**
 * 获取值
 * @param any $object
 * @param string|int $key
 * @param any $defaultValue
 * @return any
 */
function v(&$object,&$key,&$defaultValue=null) {
	if(isset($object->$key)) {
		return $object->$key;
	}
	if(isset($object[$key])) {
		return $object[$key];
	}
	return $defaultValue;
}

/**
 * 获取值
 * @param any $object
 * @param string|array $keyPath
 * @param any $defaultValue
 * @return any
 */
function vv(&$object,&$keyPath,&$defaultValue=null) {
	
	if(! is_array($keyPath)) {
		$keyPath = preg_split("/[\\.]/",$keyPath);
	}
	
	$key = array_shift($keyPath);
	
	$r = v($object,$key);
	
	if($r === null) {
		return $defaultValue;
	}
	
	if(count($keyPath) == 0) {
		return $r;
	}
	
	return vv($r,$keyPath,$defaultValue);
}

/**
 * 设置值
 * @param any $object
 * @param string|int $key
 * @param any $value
 */
function s(&$object,&$key,&$value) {
	if(is_array($object)) {
		$object[$key] = $value;
	}
	else if(is_object($object)) {
		$object->$key = $value;
	}
}

<?php

namespace kk;

/**
 * 获取值
 * @param any $object
 * @param string|int $key
 * @param any $defaultValue
 * @return any
 */
function v($object,$key,$defaultValue=null) {
	if(isset($object->$key)) {
		return $object->$key;
	}
	if(isset($object[$key])) {
		return $object[$key];
	}
	return $defaultValue;
}

/**
 * 设置值
 * @param any $object
 * @param string|int $key
 * @param any $value
 */
function s($object,$key,$value) {
	if(is_array($object)) {
		$object[$key] = $value;
	}
	else if(is_object($object)) {
		$object->$key = $value;
	}
}

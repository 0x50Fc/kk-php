<?php

namespace kk;

/**
 * 对象
 * @author zhanghailong
 *
 */
class Object implements IObject {
	
	public function get($key) {
		return isset($this->$key) ? $this->$key : null;
	}
	
	public function set($key,$value) {
		$this->$key = $value;
	}
	
}

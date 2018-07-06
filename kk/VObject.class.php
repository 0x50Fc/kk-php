<?php

namespace kk;

/**
 * 对象
 * @author zhanghailong
 *
 */
class VObject implements IVObject {
	
	public function get($key) {
		return isset($this->$key) ? $this->$key : null;
	}
	
	public function set($key,$value) {
		$this->$key = $value;
	}
	
}

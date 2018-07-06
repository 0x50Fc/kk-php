<?php
namespace kk\story\plot;

/**
 * 情节
 * @author zhanghailong
 *
 */
class Plot {
	
	public $object = new \stdClass();
	
	public function set($name,$value) {
		$object->$name = $value;
		return $this;
	}
	
 
	public function on($name,$plot) {
		
	}
	
}

<?php
namespace kk\story;

/**
 * 故事
 * @author zhanghailong
 *
 */
class Story {
	
	private $_id;
	private $_plotClasss;
	
	public function __construct() {
		$this->_id = 0;
		$this->_plotClasss = new \stdClass();
	}
	
	/**
	 * 映射情节类
	 * @param string $name
	 * @param string|class $plotClass
	 */
	public function resolve($name,$plotClass) {
		$this->_plotClasss->$name = $plotClass;
		return $this;
	}
	
	/**
	 * 创建情节
	 * @param string|class $name
	 */
	public function plot($name) {
		
		if(isset($this->_plotClasss->$name)) {
			$name = $this->_plotClasss->$name;
		}
		
		if(class_exists($name)) {
			return new $name();
		}
		else{
			throw new \Exception("Not Found class " . $name);
		}
	}
}

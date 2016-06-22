<?php
namespace kk;

/**
 * 服务
 * @author zhanghailong
 *
 */
class Service extends Object implements ITask {

	private $_plugin;
	
	public function plugin() {
		return $this->_plugin;
	}
	
	public function setPlugin($plugin) {
		$this->plugin = $plugin;
	}
	
	public function handle($app,$task) {
		
		$name = get_class($task);
		
		$i = strrpos($name, "\\");
		
		if($i !== false) {
			$name = substr($name,$i + 1);
		}
		
		$name = 'handle'. $name;
		
		if(method_exists($this, $name)) {
			return $this->$name($app,$task);
		}
	}
	
}

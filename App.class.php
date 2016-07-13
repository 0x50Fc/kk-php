<?php
namespace kk;

/**
 * 应用
 * @author zhanghailong
 *
 */
class App extends Object implements IApp {
	
	private $_plugins;
	private $_services;
	private $_taskTypes;
	
	public function __construct($path=false){
		$this->_plugins = array();
		$this->_services = array();
		$this->_taskTypes = new \stdClass();
		
		if($path !== false) {
			
			$f = realpath($path.'/app.json');
			
			if(file_exists($f)) {
				$cfg = json_decode(file_get_contents($f));
				foreach($cfg as $key=>$value) {
					if($key == 'libs') {
						foreach($value as $p) {
							$this->load($path . '/' . $p);
						}
					}
					else {
						$this->set($key, $value);
					}
				}
			}
			else {
				throw new \Exception("Not Found " . $f);
			}
		}
		
	}
	
	/**
	 * 加载类
	 * @param string $path
	 * @param string $namespace
	 * @param string $name
	 */
	public function loadClass($path,$namespace,$name) {
		
		if(strpos($name, "\\") === 0) {
			if(class_exists($name)) {
				return $name;
			}
			else {
				throw new \Exception("Not Found Class " . $name);
			}
		}
		
		$className = $namespace .'\\'. $name;
		
		if(class_exists($className)) {
			return $className;
		}
		
		$fpath = realpath($path . '/' . str_replace("\\", "//", $name).".class.php");
		
		if(file_exists($fpath)) {
			
			require_once($fpath);
			
			if(class_exists($className)) {
				return $className;
			}
			else {
				throw new \Exception("Not Found Class " . $className);
			}
		}
		else {
			throw new \Exception("Not Found Class " . $fpath);
		}
	}
	
	/**
	 * 加载插件
	 * @param string $path
	 */
	public function load($path) {
		
		$f = realpath($path .'/kk.json');
		
		if(file_exists($f)) {
			
			$cfg = json_decode(file_get_contents($f));
			
			$namespace = \kk\v($cfg, 'namespace','');
			
			$plugin = false;
			
			if(($v = \kk\v($cfg,'class'))) {
				$plugin = new $this->loadClass($path, $namespace, $v);
			}
			else {
				$plugin = new \kk\Plugin();
			}
			
			if(($v = \kk\v($cfg,'object'))) {
				foreach($v as $key=>$value) {
					$plugin->set($key,$value);
				}
			}
			
			$this->addPlugin($plugin);
			
			if(($v = \kk\v($cfg,'services'))) {
				
				foreach($v as $srv) {
					
					if(($name = \kk\v($srv,'class'))) {
						
						$service = new $this->loadClass($path, $namespace, $name);
						
						if(($vv = \kk\v($cfg,'object'))) {
							foreach($vv as $key=>$value) {
								$service->set($key,$value);
							}
						}
						
						$this->addService($service);
						
						if(($vv = \kk\v($cfg,'taskTypes'))) {
							foreach($vv as $taskType) {
								$this->addTaskType($this->loadClass($path, $namespace, $taskType));
							}
						}
					}
					
				}
			}
			
		}
		else {
			throw new \Exception("Not Found " . $f);
		}
	}
	
	/**
	 * 添加插件
	 * @param \kk\IPlugin $plugin
	 */
	public function addPlugin($plugin) {
		$this->_plugins[] = $plugin;
	}
	
	/**
	 * 获取插件
	 * @param class $class	插件类
	 */
	public function getPlugin($class) {
		foreach($this->_plugins as $plugin) {
			if($plugin instanceof $class) {
				return $plugin;
			}
		}
	}
	
	/**
	 * 添加服务，关联最后一个插件
	 * @param \kk\IService $service
	 */
	public function addService($service) {
		
		$c = count($this->_plugins);
		
		if($c > 0) {
			$service->setPlugin($this->_plugins[$c -1]);
		}
		
		$this->_services[] = $service;
		
	}
	
	/**
	 * 添加任务, 关联最后一个服务
	 * @param class $taskType 任务类
	 */
	public function addTaskType($taskType) {
		
		$c = count($this->_services);
		
		if($c > 0) {
			
			$service = $this->_services[$c - 1];
			$srvs;
			
			if(isset($this->_taskTypes->$taskType)) {
				$srvs = $this->_taskTypes->$taskType;
			}
			else {
				$srvs = array();
				$this->_taskTypes->$taskType = $srvs;
			}
			
			$srvs[] = $service;
			
		}
		else {
			throw new \Exception("Not Found Service");
		}
	}
	
	/**
	 * 处理任务
	 * @param \kk\ITask $task
	 */
	public function handle($task) {
		
		$taskType = get_class($task);
		
		if(isset($this->_taskTypes->$taskType)) {
			
			foreach ($this->_taskTypes->$taskType as $service) {
				$r = $service->handle($this,$task);
				if($r !== null || $r !== false) {
					return $r;
				}
			}
		}
		
	}

	private static $_apps = new \stdClass();
	
	public static function load($path) {
	
		if(isset(App::$_apps->$path)) {
			return App::$_apps->$path;
		}
	
		$app = new App($path);
	
		App::$_apps->$path = $app;
	
		return $app;
	}
	
}

<?php
namespace kk;

/**
 * 应用
 * @author zhanghailong
 *
 */
class App extends VObject implements IApp {
	
	private $_plugins;
	private $_services;
	private $_taskTypes;
	private $_tasks;
	private $_tasksWithName;
	
	public function __construct($path=false){
		$this->_plugins = array();
		$this->_services = array();
		$this->_taskTypes = new \stdClass();
		$this->_tasks = array();
		$this->_tasksWithName = new \kk\VObject();
		
		if($path !== false) {
			
			$f = realpath($path.'/app.json');
			
			if(file_exists($f)) {
				
				$v = file_get_contents($f);
				$cfg = json_decode($v);
					
				if($cfg === null) {
					echo "config fail : {$f}";
					echo $v;
				}
					
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
			
			$v = file_get_contents($f);
			$cfg = json_decode($v);

			if($cfg === null) {
				echo "config fail : {$f}";
				echo $v;
			}
			
			$namespace = \kk\v($cfg, 'namespace','');
			
			$plugin = false;
			
			if(($v = \kk\v($cfg,'class'))) {
				$plugin = new $this->loadClass($path, $namespace, $v);
			}
			else {
				$plugin = new \kk\Plugin();
			}

			$name = basename($path);
			$name = \kk\v($cfg, 'name',$name);

			$plugin->set('name',$name);
			$plugin->set('namespace',$namespace);
			
			if(($v = \kk\v($cfg,'object'))) {
				foreach($v as $key=>$value) {
					$plugin->set($key,$value);
				}
			}
			
			$this->addPlugin($plugin);
			
			if(($v = \kk\v($cfg,'services'))) {
				
				foreach($v as $srv) {
					
					if(($name = \kk\v($srv,'class'))) {
						
						$cls = $this->loadClass($path, $namespace, $name);
						
						$service = new $cls();
						
						if(($vv = \kk\v($srv,'object'))) {
							foreach($vv as $key=>$value) {
								$service->set($key,$value);
							}
						}
						
						$this->addService($service);
						
						if(($vv = \kk\v($srv,'taskTypes'))) {
							foreach($vv as $taskType) {
								$this->addTaskType($this->loadClass($path, $namespace, $taskType));
							}
						}
					}
					
				}
			}

			if(($v = \kk\v($cfg,'tasks'))) {
				
				foreach($v as $task) {
					
					$this->addTask($plugin,$task);
					
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
	 * 获取公开任务
	 */
	public function tasks() {
		return $this->_tasks;
	}

	public function newTask($name,&$inputData) {
		$task = $this->_tasksWithName->get($name);
		if($task) {
			$class = $task->get("class");
			if($class) {
				$task = new $class();
				if($inputData) {
					foreach($inputData as $key=>$value) {
						$task->set($key,$value);
					}
				}
				return $task;
			}
		}
		return false;
	}

	public function addTask($plugin,$item) {

		$task = new \kk\VObject();

		foreach($item as $key=>$value){
			$task->set($key,$value);
		}

		$class = $plugin->get("namespace")."\\".\kk\v($item,'class');

		$task->set("class",$class);

		$name = $plugin->get("name")."/".\kk\v($item,'name');
		$task->set("name",$name);

		$this->_tasks[] = $task;

		$this->_tasksWithName->set($name,$task);

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
			
			if(isset($this->_taskTypes->$taskType)) {
				array_push($this->_taskTypes->$taskType,$service);
			}
			else {
				$this->_taskTypes->$taskType = array($service);
			}
			
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
		
		$taskType = '\\'.get_class($task);
		
		if(isset($this->_taskTypes->$taskType)) {
			
			foreach ($this->_taskTypes->$taskType as $service) {
				$r = $service->handle($this,$task);
				if($r !== null || $r !== false) {
					return $r;
				}
			}
		}
		
	}

	/**
	 * 处理开放接口
	 */
	public function open() {

		$app = $this;
		
		$output = new \stdClass();

		if(isset($_GET["path"])) {
			
			$path = $_GET["path"];

			if($path == '_raml') {

				$items = array();
				
				foreach($app->tasks() as $task) {

					$items[] = $task;

				}
				
				$output->items = $items;

			} else if($path) {

				$inputData = array();

				if($_GET) {
					foreach($_GET as $key=>$value) {
						$inputData[$key] = $value;
					}
				}

				if($_POST) {
					foreach($_POST as $key=>$value) {
						$inputData[$key] = $value;
					}
				}

				$task = $app->newTask($path,$inputData);

				if($task) {

					try {
						$app->handle($task);
						$output->data = $task->getResult();
						$output->errno = 200;
					}
					catch(\Exception $ex) {
						$output->errno = $ex->getCode();
						$output->errmsg = $ex->getMessage();
					}
					

				} else {
					$output->errno = '404';
					$output->errmsg = "Not Found Task";
				}

			} else {
				$output->errno = '404';
				$output->errmsg = "Not Found Task";
			}

		} else {
			$output->errno = '404';
			$output->errmsg = "Not Found Path";
		}

		header("Content-Type: application/json; charset=utf-8");

		echo json_encode($output);
	}

}

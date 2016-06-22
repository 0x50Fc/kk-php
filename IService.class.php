<?php

namespace kk;

/**
 * 服务
 * @author zhanghailong
 *
 */
interface IService extends IObject {

	/**
	 * 所属插件
	 */
	public function plugin();
	
	/**
	 * 设置所属插件
	 * @param \kk\IPlugin $plugin
	 */
	public function setPlugin($plugin);
	
	/**
	 * 处理任务
	 * @param \kk\IApp $app		应用
	 * @param \kk\ITask $task	任务
	 */
	public function handle($app,$task);
	
}

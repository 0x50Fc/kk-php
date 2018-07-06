<?php

namespace kk;

/**
 * 服务
 * @author zhanghailong
 *
 */
interface IService extends IVObject {

	/**
	 * 处理任务
	 * @param \kk\IApp $app		应用
	 * @param \kk\ITask $task	任务
	 * @throws \Exception
	 */
	public function handle($app,$task);
	
}

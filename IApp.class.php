<?php

namespace kk;

/**
 * 应用
 * @author zhanghailong
 *
 */
interface IApp extends IObject{
	
	/**
	 * 处理任务
	 * @param \kk\ITask $task
	 */
	public function handle($task);
	
}

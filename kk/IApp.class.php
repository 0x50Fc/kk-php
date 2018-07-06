<?php

namespace kk;

/**
 * 应用
 * @author zhanghailong
 *
 */
interface IApp extends IVObject{
	
	/**
	 * 处理任务
	 * @param \kk\ITask $task
	 */
	public function handle($task);
	
	/**
	 * 获取公开任务
	 */
	public function tasks();

	/**
	 * 创建任务
	 */
	public function newTask($name,&$inputData);

	/**
	 * 处理开放接口
	 */
	public function open();
}

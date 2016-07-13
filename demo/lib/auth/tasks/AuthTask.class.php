<?php
namespace kk\auth\tasks;

/**
 * 验证当前登录状态, 未登录抛出异常
 * @author zhanghailong
 *
 */
class AuthTask extends \kk\Task {
	
	/**
	 * 输出 当前用户ID
	 * @var long
	 */
	public $uid;
	
}

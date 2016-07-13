<?php

namespace kk\auth;

/**
 * 授权服务
 * @author zhanghailong
 *
 */
class AuthService extends \kk\Service {
	
	/**
	 * 验证登录凭证
	 * @param \kk\IApp $app
	 * @param \kk\blazer\auth\tasks\AuthTask $task
	 * @throws \Exception
	 */
	public function handleAuthTask($app,$task) {
		
		var_dump($task);
		
		return true;
	}

	/**
	 * 设置登录凭证
	 * @param \kk\IApp $app
	 * @param \kk\blazer\auth\tasks\AuthSetTask $task
	 * @throws \Exception
	 */
	public function handleAuthSetTask($app,$task) {
		
		return true;
	}
	
	/**
	 * 删除登录凭证
	 * @param \kk\IApp $app
	 * @param \kk\blazer\auth\tasks\AuthRemoveTask $task
	 * @throws \Exception
	 */
	public function handleAuthRemoveTask($app,$task) {
	

		return true;
	}
	
}

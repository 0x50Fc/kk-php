<?php

namespace kk;

/**
 * 对象
 * @author zhanghailong
 *
 */
interface IObject {
	
	/**
	 * 获取值
	 * @param string $key
	 */
	public function get($key);
	
	/**
	 * 设置值
	 * @param string $key
	 * @param any $value
	 */
	public function set($key,&$value);
	
}

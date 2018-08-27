<?php

/**
 * 计划任务插件
 *
 * 计划任务插件, 有可能触发写操作,需要判断master, 另外,从效率考虑,根据情况,加入rand随机检测操作
 *
 * @category   Framework
 * @package    Cron
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 *
 */
class Plugin_Cron implements Yf_Plugin_Interface
{
	function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('end', $this, 'checkTask');
	}

	public static function desc()
	{
		return '计划任务功能, 用法参考Linux crontab.';
	}


	public function checkTask()
	{
		//需要设计规则,随机触发.

		//db需要为master
		$Base_CronModel = new Base_CronModel();
		$rows           = $Base_CronModel->checkTask();

		fb($rows);
	}
}

?>
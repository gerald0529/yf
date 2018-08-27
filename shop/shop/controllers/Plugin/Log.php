<?php

/**
 * 日志插件
 *
 * 记录操作日志，同步或者异步日志系统
 *
 * @category   Framework
 * @package    Log
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 *
 */
class Plugin_Log implements Yf_Plugin_Interface
{
	function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('log', $this, 'addLog');
	}

	public static function desc()
	{
		return 'this is a log plugin! 开启日志,则会记录登录用户的操作记录,但会轻微加重系统负担.';
	}

	public function addLog()
	{
		//同步，直接操作日志数据库

		if (true || isset($ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]['log']))
		{

			$Yf_Registry = Yf_Registry::getInstance();
			$ccmd_rows   = $Yf_Registry['ccmd_rows'];

			$rights_id = @$ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]['rid'];

			$data = array();

			if (Perm::$login)
			{
				$data['user_id']      = Perm::$row['user_id']; // 玩家Id
				$data['user_account'] = Perm::$row['user_account']; // 角色账户
				//$data['user_name']              = Perm::$row['user_realname']      ; // 角色名称
			}
			else
			{

			}

			$data['action_id'] = $rights_id; // 行为id == protocal_id -> rights_id
			$data['log_param'] = $_REQUEST; // 请求的参数，|| 详细数据，可以通过controller结束赋值全部变量来或获取
			$data['log_ip']    = get_ip(); //

			$logActionModel = new Log_ActionModel();
			$log_id         = $logActionModel->addAction($data, true);
		}

		//异步，队列操作
	}
}

?>
<?php
//服务器端命令行运行模式配置文件

if ('cli' == SAPI)
{
	Yf_Db::setConnectMode(false);
}
else
{
	die('该程序只能运行在CLI模式下!');
}
?>
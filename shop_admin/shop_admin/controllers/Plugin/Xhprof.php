<?php
/**
 * Xhprof插件的实现
 *
 * @category   Framework
 * @package    Plugin
 */

/**
 *需要注意的几个默认规则：
 *    1. 本插件类的文件名必须是action
 *    2. 插件类的名称必须是{插件名_actions}
 */
class Plugin_Xhprof implements Yf_Plugin_Interface
{
	private $xhprof_on = false;

	//解析函数的参数是pluginManager的引用
	function __construct()
	{
		/*
		$out_path = LOG_PATH . '/xhprof';
		make_dir_path($out_path);

		ini_set("xhprof.output_dir", $out_path);
		*/

		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('init', $this, 'startXhprof');
		Yf_Plugin_Manager::getInstance()->register('end', $this, 'endXhprof');
	}

	public static function desc($argv = null)
	{
		return "XHProf是facebook 开发的一个测试php性能的扩展，本文记录了在PHP应用中使用XHProf对PHP进行性能优化，查找性能瓶颈的方法。";
	}

	function startXhprof($data = null)
	{
		if (function_exists('xhprof_enable'))
		{
			xhprof_enable(XHPROF_FLAGS_MEMORY);

			$this->xhprof_on = true;
		}
		else
		{
			$this->xhprof_on = false;
		}
	}

	function endXhprof($data = null)
	{
		if ($this->xhprof_on)
		{
			// stop profiler
			$xhprof_data = xhprof_disable();

			// save $xhprof_data somewhere (say a central DB)
			include_once CTL_PATH . '/Plugin/Xhprof/xhprof_lib/utils/xhprof_lib.php';
			include_once CTL_PATH . '/Plugin/Xhprof/xhprof_lib/utils/xhprof_runs.php';

			// save raw data for this profiler run using default
			// implementation of iXHProfRuns.
			$xhprof_runs = new XHProfRuns_Default();

			// save the run under a namespace "xhprof_foo"
			if (isset($_REQUEST['ctl']))
			{
				$ctl = $_REQUEST['ctl'];
			}
			else
			{
				$ctl = 'Index';

			}

			if (isset($_REQUEST['met']))
			{
				$met = $_REQUEST['met'];
			}
			else
			{
				$met = 'index';

			}

			$run_id = $xhprof_runs->save_run($xhprof_data, $ctl . '_' . $met);

			fb(Yf_Registry::get('base_url') . '/erp/controllers/Plugin/Xhprof/xhprof_html/index.php?run=' . $run_id . '&source=' . $ctl . '_' . $met);
		}
	}
}

?>
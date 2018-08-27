<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Web_ConfigModel extends Web_Config
{

	private static $_instance;

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getConfigList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	/*
	 * 获取config
	 */
	public static function value($key, $default = false)
	{
		if (!@(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}

		return self::$_instance->getConfigValue($key, $default);
	}

	/*
	 * 获取config
	 */
	public function getConfigValue($key, $default = false)
	{
		if (Yf_Registry::isRegistered($key))
		{
			return Yf_Registry::get($key);
		}
		else
		{
			$config_row = array();

			$config_rows = $this->getConfig($key);

			if ($config_rows)
			{
				$config_row = array_pop($config_rows);

				if ('json' == $config_row['config_datatype'])
				{
					$config_row['config_value'] = decode_json($config_row['config_value']);
				}

				Yf_Registry::set($key, $config_row['config_value']);
				$val = $config_row['config_value'];
			}
			else
			{
				$val = $default;
			}

			return $val;
		}
	}
}

?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_ChannelModel extends Payment_Channel
{
	const ALIPAY = 1;
	const TENPAY = 2;
	const ALIPAY_WAP  = 3;
	const WECHAT_PAY = 4;
	const MONEY = 5;
	const CARDS = 6;
    const BAITIAO = 9;

	const UNIONPAY = 10;
    
    
	const ENABLE_NO = 0;
	const ENABLE_YES = 1;

	public static $configRows = array();
    private static $_instance;
	/**
	 * 读取分页列表
	 *
	 * @param  int $payment_channel_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getChannelList($payment_channel_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$payment_channel_id_row = array();
		$payment_channel_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($payment_channel_id_row)
		{
			$data_rows = $this->getChannel($payment_channel_id_row);

			foreach ($data_rows as $k=>$data_row)
			{
				$data_row['id'] = $data_row['payment_channel_id'];
				$data_rows[$k] = $data_row;
			}
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	/*
	 * 获取config
	 */
	public function getPaymentChannelValue($key)
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

			}

			return $config_row['config_value'];
		}
	}



	/**
	 * 此处可以先这样, 可以考虑生成PHP配置文件或者Cache
	 *
	 * @param  int $payment_channel_code 支付渠道code
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getChannelConfig($payment_channel_code)
	{
		$config_row = array();

		if (isset(self::$configRows[$payment_channel_code]))
		{
			$config_row = self::$configRows[$payment_channel_code];
		}
		else
		{
			$Payment_ChannelModel = new Payment_ChannelModel();
			$data                 = $Payment_ChannelModel->getByWhere(array('payment_channel_code' => $payment_channel_code));

			if ($data)
			{
				$data_row   = array_pop($data);
				$config_row = $data_row['payment_channel_config'];


				self::$configRows[$payment_channel_code] = $config_row;
			}
		}

		return $config_row;
	}
	public function getWayByKey($payment_channel_id=null)
	{
		$this->sql->setWhere('payment_channel_id',$payment_channel_id);
		$config_id_row = $this->selectKeyLimit();
		return $config_id_row;
	}
	public function getPayWaysByCode($payment_channel_code=null)
	{
		if($payment_channel_code)
		{
			$this->sql->setWhere('payment_channel_code',$payment_channel_code);
		}
		
		$code_ways_row = $this->getPaymentChannel('*');
		return $code_ways_row;
	}
    
    /**
     *  获取状态
     * @param type $key
     * @return type
     */
    public static function status($key)
	{
		if (!@(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance->getConfigStatus($key);
	}

	/*
	 * 获取状态
	 */
	public function getConfigStatus($key)
	{
		if (Yf_Registry::isRegistered($key))
		{
			return Yf_Registry::get($key);
		}
		else
		{
			$data = array();
            $data = $this->getByWhere(array('payment_channel_code' => $key));
        
			if ($data)
			{
				$config_row = array_pop($data);
				Yf_Registry::set($key.'_payment_channel_enable', $config_row['payment_channel_enable']);
				$val = $config_row['payment_channel_enable'];
			}
			else
			{
				$val = self::ENABLE_NO;
			}
			return $val;
		}
	}

}
?>
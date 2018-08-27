<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BaseModel extends User_Base
{
	const PAYMENT_PASSWORD_MISTAKE_BOUT_LIMIT = 3; //一回合支付密码错误上限
    const PAYMENT_PASSWORD_MISTAKE_DAY_LIMIT = 9; //当天支付密码错误上限
	const PAYMENT_PASSWORD_MISTAKE_BOUT_COUNT_CACHE_KEY = 'paymentPassWordMistakeBoutCount';//cache
    const PAYMENT_PASSWORD_MISTAKE_DAY_COUNT_CACHE_KEY = 'paymentPassWordMistakeDayCount';//cache
	const PAYMENT_PASSWORD_MISTAKE_INTERVAL = 600; //冷却时间

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($user_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		$user_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_id_row)
		{
			$data_rows = $this->getBase($user_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getBaseIdByAccount($user_account = null)
	{
		$this->sql->setWhere('user_account',$user_account);
		$data = $this->selectKeyLimit();

		return $data;
	}
	
	/**
	 * 读取一个会员信息
	 *
	 * @param  array $order 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserBase($order_row = array())
	{
		return $this->getOneByWhere($order_row);
	}
        
	public function  getPayBaseList($cond_row = array(),$order_row = array(), $page=1, $rows=20, $sort='asc')
	{
		$getBaseList = $this->listByWhere($cond_row ,$order_row, $page, $rows, $sort);
		$user_resource = new User_ResourceModel();
		foreach ($getBaseList['items'] as $key => $value)
		{
				$user_resource_list =$user_resource->getone($value['user_id']);
				$getBaseList['items'][$key] = array_merge($getBaseList['items'][$key], $user_resource_list);
		}

		return $getBaseList;
	}

	public function checkPaymentPassWord ($user_id, $password)
    {
        $user_data = $this->getOne($user_id);

        if ($user_data === false) {
            return false;
        }

        if ($user_data['user_pay_passwd'] !== md5($password)) {
			$this->recordPaymentPasswordMistake($user_id);
            return false;
        }
        return true;
    }

	/**
     * 此方法判断当前回合是否达到上限
	 * 判断支付密码错误次数是否达到上限，达到上限禁止当前请求
	 * @param int $user_id
	 * @return boolean
     *
     * 判断次数，如果达到上限，在判断时间，如果在限制之间内，拒绝请求。只有同时满足次数和时间
	 */
	public static function isPaymentPasswordMistakeBoutLimit($user_id)
	{
		$cache_key = self::PAYMENT_PASSWORD_MISTAKE_BOUT_COUNT_CACHE_KEY.$user_id;
		$cache = Yf_Cache::create('default');
        $cache_val = $cache->get($cache_key);

        if ($cache_val === false) { //没有该条记录，直接返回FALSE
            return false;
        }
        list($count, $before_time) = explode('-', $cache_val);

        if ($count >= self::PAYMENT_PASSWORD_MISTAKE_BOUT_LIMIT) {
            if (time() - $before_time <= self::PAYMENT_PASSWORD_MISTAKE_INTERVAL) {
                return true;
            } else {
                self::clearPaymentPasswordBoutMistake($user_id);
                return false;
            }
        }
        return false;
	}

    /**
     * @param int $user_id
     * @return boolean
     * 此方法判断当天总次数是否达到上限
     */
    public static function isPaymentPasswordMistakeDayLimit($user_id)
    {
        $cache_key = self::PAYMENT_PASSWORD_MISTAKE_DAY_COUNT_CACHE_KEY.$user_id;
        $cache = Yf_Cache::create('default');
        $cache_val = $cache->get($cache_key);

        if ($cache_val === false) { //没有该条记录，直接返回FALSE
            return false;
        }

        if ($cache_val >= self::PAYMENT_PASSWORD_MISTAKE_DAY_LIMIT) {
            return true;
        }
        return false;
    }

	/**
	 * @param $user_id
	 * @throws Exception
	 * 记录用户密码错误次数
     *
     * cache=> $count-time()
	 */
	private function recordPaymentPasswordMistake($user_id)
	{
		$bout_cache_key = self::PAYMENT_PASSWORD_MISTAKE_BOUT_COUNT_CACHE_KEY.$user_id;
        $day_cache_key = self::PAYMENT_PASSWORD_MISTAKE_DAY_COUNT_CACHE_KEY.$user_id;

		$config_cache = Yf_Registry::get('config_cache');
		if (!file_exists($config_cache['default']['cacheDir'])){
			mkdir($config_cache['default']['cacheDir'], 0777);
		}

		$cache = Yf_Cache::create('default');
		$bout_cache_val = $cache->get($bout_cache_key);
        $day_cache_val = $cache->get($day_cache_key);

        //单次回合
        $now_time = time();
        if ($bout_cache_val === false) { //没有记录，第一次访问
            $update_bout_val = "1-$now_time";
        } else {
            list($count) = explode('-', $bout_cache_val);
            $count += 1;
            $update_bout_val = "$count-$now_time";
        }
		$cache->save($update_bout_val, $bout_cache_key);

        //当天
        $update_day_val = $day_cache_val ? $day_cache_val + 1 : 1;
        $cache->save($update_day_val, $day_cache_key);
	}

    /**
     * @param $user_id
     * @return bool
     * 支付密码输入正确后，清除之前错误次数
     */
    public function clearPaymentPasswordBoutMistake ($user_id)
    {
        $cache = Yf_Cache::create('default');
        $bout_cache_key = self::PAYMENT_PASSWORD_MISTAKE_BOUT_COUNT_CACHE_KEY.$user_id;
        return $cache->save(0, $bout_cache_key);
    }
}
?>
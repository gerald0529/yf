<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Number_SeqModel extends Number_Seq
{

	/**
	 * 根据主键值，从数据库读取当前Number
	 *
	 * @param int $prefix
	 * @param int $pad_length
	 * @return string $number_str 下一个序列号
	 * @access public
	 */
	public function getSeq($prefix, $pad_length = 4, $next_flag = false, $pre_flag = true)
	{
		$rows = array();
		$rows = $this->get($prefix);

		$number = 0;

		if (!$rows)
		{
			$number = 1;

			//set index = 1
			$data['prefix'] = $prefix; // 前缀
			$data['number'] = $number;

			$add_flag = $this->addSeq($data);

			if (!$add_flag)
			{
				$number = 0;
			}
		}
		else
		{
			if ($next_flag)
			{
				$number = $rows[$prefix]['number'] + 1;
			}
			else
			{
				$number = $rows[$prefix]['number'];
			}
		}


		$number_str = '';

		if ($number && $pre_flag)
		{
			$number_str = $prefix . str_pad($number, $pad_length, 0, STR_PAD_LEFT);
		}
		elseif ($number)
		{
			$number_str = $number;
		}
		else
		{
			$number_str = false;
		}

		return $number_str;
	}


	/**
	 * 得到下一个Id
	 * @param int $prefix
	 * @param int $pad_length
	 * @return string $number_str 下一个序列号
	 * @access public
	 */
	public function getNextSeq($prefix, $pad_length = 4, $pre_flag = true)
	{
		/*
		if ($number)
		{
			$flag = $this->editSeqSingleField($prefix, 'number', $number+1, $number);

			if ($flag)
			{
				$number += 1;
			}
			else
			{
				$number = false;
			}
		}
		*/
		{
			$rows = $this->get($prefix);

			$number = $this->getSeq($prefix, $pad_length, true, $pre_flag);
		}

		return $number;
	}


	/**
	 * 得到下一个Id
	 * @param int $prefix
	 * @param int $pad_length
	 * @return string $number_str 下一个序列号
	 * @access public
	 */
	public function createSeq($prefix, $pad_length = 4, $pre_flag = true)
	{
		$rows = $this->get($prefix);

		if ($rows)
		{
			$number = $rows[$prefix]['number'];

			$flag = $this->editSeqSingleField($prefix, 'number', $number + 1, $number);
		}

		$number = $this->getSeq($prefix, $pad_length, false, $pre_flag);

		return $number;
	}
}

?>
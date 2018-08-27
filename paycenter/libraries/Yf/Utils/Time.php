<?php

class Yf_Utils_Time
{
	public static function getDiffTime($begin_time, $time_format='m/d H:i', $diff_time_max=86400,  $end_time=null)
	{
		if (null == $end_time)
		{
			$end_time = time();
		}

		if ($begin_time < $end_time)
		{
			$starttime = $begin_time;
			$endtime   = $end_time;
		}
		else
		{
			$starttime = $end_time;
			$endtime   = $begin_time;
		}

		$timediff = $endtime - $starttime;


		if ($timediff >= $diff_time_max)
		{
			$res = date($time_format, $begin_time);
		}
		else
		{
			$days     = intval($timediff / 86400);
			$remain   = $timediff % 86400;
			$hours    = intval($remain / 3600);
			$remain   = $remain % 3600;
			$mins     = intval($remain / 60);
			$secs     = $remain % 60;


			if ($days)
			{
				$res      =  sprintf('%d 天前', $days);
			}
			else if ($hours)
			{
				$res      =  sprintf('%d 小时前', $hours);
			}
			else if ($mins)
			{
				$res      =  sprintf('%d 分钟前', $mins);
			}
			else if ($secs)
			{
				$res      =  sprintf('%d 秒前', $secs);
			}

		}

		return $res;
	}
}

?>
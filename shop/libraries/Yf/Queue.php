<?php

/**
 * 队列 管理者类
 *
 * 负责初始化并存放所有的队列类。
 *
 * @category   Framework
 * @package    队列
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Yf_Queue
{
	public static $keyPre = '_msg_|';
	public static $type   = 'file';
	
	private static $fileQueueInstances = array();
	
	static public function getKey($queue)
	{
		if ('WIN' == substr(PHP_OS, 0, 3) && 'msg-queue' == self::$type)
		{
			self::$type = 'file';
		}
		else
		{
		}
		
		self::$keyPre = Zero_Registry::get('queue_key_prefix');
		$queue = self::$keyPre . $queue;
		
		if ('file' == self::$type && !array_key_exists($queue, self::$fileQueueInstances))
		{
			$queue_dir = DATA_PATH . DS . 'file-queue';
			
			$FileQueue = new Zero\Queue\File(array(
												 'role' => 'customer',
												 'queueNamespace' => 'demo',
												 'queueDir' => $queue_dir,
												 'queueFileName' => $queue
											 ));
			
			self::$fileQueueInstances[$queue] = $FileQueue;
		}
		
		return $queue;
	}
	
	static public function send($queue, $data)
	{
		$queue        = self::getKey($queue);
		
		if ('redis' == self::$type)
		{
			$res = Zero_Queue_Redis::send($queue, $data);
		}
		elseif ('msg-queue' == self::$type)
		{
			$res = Zero_Queue_MsgQueue::send($queue, $data);
		}
		else
		{
			$res = self::$fileQueueInstances[$queue]->push($data);
		}
		
		return $res;
	}
	
	static public function receive($queue)
	{
		$queue        = self::getKey($queue);
		
		if ('redis' == self::$type)
		{
			$data = Zero_Queue_Redis::receive($queue);
		}
		elseif ('msg-queue' == self::$type)
		{
			$data = Zero_Queue_MsgQueue::receive($queue);
		}
		else
		{
			$data = self::$fileQueueInstances[$queue]->pop();
		}
		
		return $data;
	}
	
	//不会改动内容
	public static function all($queue)
	{
		$queue        = self::getKey($queue);
		
		if ('redis' == self::$type)
		{
			$data = Zero_Queue_Redis::all($queue);
		}
		elseif ('msg-queue' == self::$type)
		{
			$data = Zero_Queue_MsgQueue::all($queue);
		}
		else
		{
			$rs = self::$fileQueueInstances[$queue]->all();
		}
		
		return $data;
	}
	
	public static function remove($queue)
	{
		$queue        = self::getKey($queue);
		
		if ('redis' == self::$type)
		{
			$rs = Zero_Queue_Redis::remove($queue);
		}
		elseif ('msg-queue' == self::$type)
		{
			$rs = Zero_Queue_MsgQueue::remove($queue);
		}
		else
		{
			$rs = self::$fileQueueInstances[$queue]->unmount();
		}
		
		
		return $rs;
	}
	
	public static function msgStat($queue)
	{
		$queue        = self::getKey($queue);
		
		if ('redis' == self::$type)
		{
			$queue_status['msg_qnum'] = Zero_Queue_Redis::size($queue);
		}
		elseif ('msg-queue' == self::$type)
		{
			$queue_status = Zero_Queue_MsgQueue::msgStat($queue);
		}
		else
		{
		}
		
		return $queue_status;
	}
	
	public static function msgStatQueueNum($queue)
	{
		$queue        = self::getKey($queue);
		
		if ('redis' == self::$type)
		{
		}
		elseif ('msg-queue' == self::$type)
		{
			$queue_status = self::msgStat($queue);
		}
		else
		{
			$pos_row = self::$fileQueueInstances[$queue]->position();
			$all_num = self::$fileQueueInstances[$queue]->length();
			$pos_num = $pos_row[1];
			
			$queue_num = 0;
			
			if ($pos_num > $all_num)
			{
				
			}
			else
			{
				$queue_num = $all_num - $pos_num + 1;
			}
			
			$queue_status['msg_qnum'] = $queue_num;
		}
		
		return $queue_status['msg_qnum'];
	}
	
	private function __construct()
	{
	}
}

?>
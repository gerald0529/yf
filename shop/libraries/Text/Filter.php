<?php
class Text_Filter
{
	private static $words = null;  //文字

	public static function filterWords(&$message=null, &$matche_row=array())
	{
		if (!self::$words)
		{
			include_once(INI_PATH . '/filter.ini.php');
			self::$words =& $_CACHE['word_filter'];
		}
		else
		{
			
		}

		$message = empty(self::$words['filter']) ? $message : @preg_replace(self::$words['filter']['find'], self::$words['filter']['replace'], $message);
		
		return $message;
	}


	public static function checkBanned(&$message=null, &$matche_row=array())
	{
		if (!self::$words)
		{
			include_once(INI_PATH . '/filter.ini.php');
			self::$words =& $_CACHE['word_filter'];
		}
		else
		{

		}

		if(self::$words['banned'] && preg_match(self::$words['banned'], $message, $matche_row))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>
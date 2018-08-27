<?php
class Text_Filter
{
	private static $words = null;  //文字

	public static function wordsFilter(&$message=null, &$matche_row=array())
	{
		if (!self::$words)
		{
			include_once(INI_PATH . '/filter.ini.php');
			self::$words =& $_CACHE['word_filter'];
		}
		else
		{
			
		}




        foreach(self::$words['filter']['find'] as $key => $value)
        {
            if(preg_match($value, $message))
            {
                return -1;
            }
        }


        if(self::$words['banned'] && preg_match(self::$words['banned'], $message, $matche_row))
        {
            return -1;
        }
        else
        {
            return $message;
        }





        /*
		$message = empty(self::$words['filter']) ? $message : @preg_replace(self::$words['filter']['find'], self::$words['filter']['replace'], $message);

		if(self::$words['banned'] && preg_match(self::$words['banned'], $message, $matche_row))
		{
			return -1;
		}
		else
		{
			return $message;
		}
        */
	}
}
?>
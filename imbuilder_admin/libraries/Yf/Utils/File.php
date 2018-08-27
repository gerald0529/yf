<?php

class Yf_Utils_File
{
	public static function getByteSize($size)
	{
		$base = array(array('KB','K'),array('MB','M'),array('GB','G'),array('TB','T'));
		$sum  = 1;

		for ($i = 0; $i < 4; $i++)
		{
			if (stripos($size, $base[$i][0]) || stripos($size, $base[$i][1]))
			{
				$size = $sum * str_ireplace($base[$i], '', $size) * 1024;
				break;
			}
			$sum *= 1024;
		}

		return $size;
	}

	/**
	 * 生成PHP文件
	 *
	 * @param string $file    文件名称
	 * @param array  $row     内容 array('key', value) =>  $key = val  | 如果key 为数字, 则直接加入内容
	 *
	 * @return self::dbHandle   Db Object
	 *
	 * @access public
	 */
	public static function generatePhpFile($file, $row = array())
	{
		$php_start = '<?php' . PHP_EOL;
		$php_end   = '?>' . PHP_EOL;
		$str = $php_start;

		foreach ($row as $key=>$val)
		{
			$val_str = '';

			if (is_array($val))
			{
				$val_str = var_export($val, true);
			}
			elseif (is_string($val))
			{
				if (!is_numeric($key))
				{
					$val_str = untrim($val);
				}
				else
				{
					$val_str = $val;
				}
			}
			else
			{
				$val_str = $val;
			}

			if (!is_numeric($key))
			{
				$str  = $str . sprintf('$%s = %s; %s', $key, $val_str, PHP_EOL);
			}
			else
			{
				$str  = $str . sprintf('%s; %s', $val_str, PHP_EOL);
			}
		}

		$php_code  = $str . $php_end;
		
		return file_put_contents($file, $php_code);
	}


	function getPhpFile($dir)
	{
		$files = array();

		if (is_dir($dir))
		{
			if ($handle = opendir($dir))
			{
				while (($file = readdir($handle)) !== false)
				{
					if ($file != "." && $file != "..")
					{
						if (is_dir($dir . "/" . $file))
						{
							$files = array_merge($files, find_php_file($dir . "/" . $file));
						}
						else
						{
							$tmp_file = $dir . "/" . $file;

							$ext_row = pathinfo($tmp_file);

							if ('php' == @$ext_row['extension'])
							{
								$files[] = $dir . "/" . $file;
							}
						}
					}
				}

				closedir($handle);

				return $files;
			}
		}
	}
}
?>
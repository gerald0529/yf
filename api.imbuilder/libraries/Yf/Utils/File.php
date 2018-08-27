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

		return file_put_contents($file, $php_code);;
	}

	public static function getPhpFile($dir)
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
							$files = array_merge($files, Yf_Utils_File::getPhpFile($dir . "/" . $file));
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
	
	
	
	/**
	 * Determine a writable directory for temporary files.
	 *
	 * Function's preference is the return value of sys_get_temp_dir(),
	 * followed by your PHP temporary upload directory, followed by WP_CONTENT_DIR,
	 * before finally defaulting to /tmp/
	 *
	 * In the event that this function does not find a writable location,
	 * It may be overridden by the WP_TEMP_DIR constant in your wp-config.php file.
	 *
	 * @since 2.5.0
	 *
	 * @staticvar string $temp
	 *
	 * @return string Writable temporary directory.
	 */
	public static function getTempDir()
	{
		static $temp = '';
		if (defined('SELF_TEMP_DIR'))
		{
			return SELF_TEMP_DIR;
		}
		
		if ($temp)
		{
			return $temp;
		}
		
		if (function_exists('sys_get_temp_dir'))
		{
			$temp = sys_get_temp_dir();
			if (@is_dir($temp) && is_writable($temp))
			{
				return $temp;
			}
		}
		
		$temp = ini_get('upload_tmp_dir');
		if (@is_dir($temp) && is_writable($temp))
		{
			return $temp;
		}
		
		$temp = LOG_PATH;
		if (is_dir($temp) && is_writable($temp))
		{
			return $temp;
		}
		
		return '/tmp';
	}
	
	
	/**
	 * Returns a filename of a Temporary unique file.
	 * Please note that the calling function must unlink() this itself.
	 *
	 * The filename is based off the passed parameter or defaults to the current unix timestamp,
	 * while the directory can either be passed as well, or by leaving it blank, default to a writable temporary directory.
	 *
	 * @since 2.6.0
	 *
	 * @param string $filename Optional. Filename to base the Unique file off. Default empty.
	 * @param string $dir Optional. Directory to store the file in. Default empty.
	 * @return string a writable filename
	 */
	public static function tempnam($filename = '', $dir = '')
	{
		if (empty($dir))
		{
			$dir = self::getTempDir();
		}
		
		if (empty($filename) || '.' == $filename || '/' == $filename || '\\' == $filename)
		{
			$filename = time();
		}
		
		// Use the basename of the given file without the extension as the name for the temporary directory
		$temp_filename = basename($filename);
		$temp_filename = preg_replace('|\.[^.]*$|', '', $temp_filename);
		
		// If the folder is falsey, use its parent directory name instead.
		if (!$temp_filename)
		{
			return self::tempnam(dirname($filename), $dir);
		}
		
		// Suffix some random data to avoid filename conflicts
		$temp_filename .= '-' . @Text_Password::create(6, 'numeric');
		$temp_filename .= '.tmp';
		$temp_filename = $dir . DIRECTORY_SEPARATOR . @Text_Password::create(10) . '-' . $temp_filename;
		
		$fp = @fopen($temp_filename, 'x');
		if (!$fp && is_writable($dir) && file_exists($temp_filename))
		{
			return self::tempnam($filename, $dir);
		}
		if ($fp)
		{
			fclose($fp);
		}
		
		return $temp_filename;
	}
	
	public static function cleanDir($dir = null, $del_dir = null)
	{
		if (is_dir($dir))
		{
			$dh = opendir($dir);
			
			while (false !== ($f = readdir($dh)))
			{
				if ($f == '.' || $f == '..')
				{
					continue;
				}
				elseif (is_dir($dir . '/' . $f))
				{
					clean_cache($dir . '/' . $f, true);
				}
				else
				{
					unlink($dir . '/' . $f);
				}
			}
			
			closedir($dh);
			
			if ($del_dir && 'cache' != $dir)
			{
				rmdir($dir);
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function copyDir($source, $destination, $skip=array())
	{
		if (is_dir($source) && !is_dir($destination))
		{
			mkdir($destination, 0777, true);
		}
		
		$handle = opendir($source);
		
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != ".." && $file != ".svn" && !in_array($destination. DIRECTORY_SEPARATOR . $file, $skip))
			{
				is_dir("$source/$file") ? self::copyDir("$source/$file", "$destination/$file", $skip) : copy("$source/$file", "$destination/$file");
			}
		}
		
		closedir($handle);
		
		return true;
	}
	
	public static function copy($source, $destination)
	{
		return copy($source, $destination);
	}
	
	public static function exists($file)
	{
		return @file_exists($file);
	}
	
	public static function mkdir($pathname, $mode = 0777, $recursive = true, $context = null)
	{
		return mkdir($pathname, $mode, $recursive , $context);
	}
	
	public static function delete($dir = null, $del_dir = null)
	{
		if (is_dir($dir))
		{
			return self::cleanDir($dir, $del_dir);
		}
		else
		{
			return @unlink($dir);
		}
	}
}
?>
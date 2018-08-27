<?php
class File_Php
{
	public $filePath  = '';

	public $phpPrefix  = "<?php\n";
	public $phpComment = "";
	public $phpText    = array();
	public $phpSuffix  = "\n?>";

	public function __construct($path)
	{
		$this->filePath = $path;
	}

	/**
	 * 写PHP文件
	 *
	 * @param str		$file_name		
	 *
	 * @return bool		$flag		是否成功
	 * @access	public	
	 */
	function writePhpFile($file_name)
	{
		$flag = false;
		$str = '';

		if ($file_name)
		{
			$str .= $this->phpPrefix;
			$str .= $this->phpComment . "\n";

			$text = implode("\n\n", $this->phpText);
			$str .= $text . "\n";
			$str .= $this->phpSuffix;

			$flag = file_put_contents($this->filePath . $file_name, $str);
		}

		return $flag;
	}

	/**
	 * 增加文件内容
	 *
	 * @param str		$text		
	 *
	 * @return bool		$flag		是否成功
	 * @access	public	
	 */
	function addPhpText($text)
	{
		array_push($this->phpText, $text);

		return $flag;
	}
}

?>
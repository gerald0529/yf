<?php
 

//默认语言);
 
//打开翻译，如果关闭，多语言功能将不能使用
$open_translate  = true;
//开启自动翻译，直接返回原来的值。需要用户自己到对应的包中进行翻译
$i18nDebug = false;

/**
 * 从后台取得当前语言设置
 * 
 */
$row = (new Web_Config)->getConfig('language_id');
 
$language_id = "zh_CN";
if($row['language_id']['config_value']){
	$language_id = trim($row['language_id']['config_value']);
}
 
/**
 * 如果是其他系统传入参数  _system_language_ 返回当前语言。
 * 目的使paycenter ucenter shop三系统语言统一。界面一致
 */
if(isset($_GET['_system_language_'])){
	echo $language_id;
	exit;
} 
global $i18n;

$i18n = new I18N; 
$i18n->open = $open_translate; //打开语言翻译
$i18n->language  = $language_id; //当前语言
$i18n->debug = $i18nDebug;
$i18n->load();

/**
 * tanslate 
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function __($str)
{ 
	global $i18n;
	return $i18n->__($str); 
}  

class I18N{

	public $language = 'en';
	public $open = false;
	public $debug = false;
	static $arr = [];
	public $lang_file;
	public function __($str){ 
		if($this->open !== true || preg_match('/^(\d|\s)+$/', $str)) return $str;
		if($this->debug == true && !static::$arr[$str]){
		 	 static::$arr[$str] =  $this->auto_translate($str)?:$str; 
			 file_put_contents( $this->lang_file , "<?php return ".var_export(static::$arr, true).";");
		} 
		return static::$arr[$str]?static::$arr[$str]:$str; 
	}

	protected function auto_translate($str){
		return $str;
	}

	public function load(){ 
		global $allowLanguage;
		if(static::$arr) return;
		$dir = dirname(__FILE__).'/';
		if(!is_dir($dir.$this->language)){ 
			return;
		} 
		 
		$this->lang_file = $dir.$this->language."/app.php";   
		if(file_exists($this->lang_file)){
			static::$arr = include $this->lang_file;
			static::$arr['lang'] = $this->language;
		} 
	}

}

  

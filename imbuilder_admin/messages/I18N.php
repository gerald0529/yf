<?php
/**
 * 多语言翻译，请
 */

//默认语言);
//读SHOP的
$shop_url = Yf_Registry::get('shop_api_url');
if(!$shop_url){
	exit('Please set shop_api_url at config.ini.php');
}
$language_id = trim(file_get_contents($shop_url."?_system_language_=1"));
if(!$language_id){
	exit('Language not setting.');
}
//打开翻译，如果关闭，多语言功能将不能使用
$open_translate  = true;

global $i18n;

$i18n = new I18N; 
$i18n->open = $open_translate; //打开语言翻译
$i18n->language  = $language_id; //当前语言
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
if(!function_exists('_')){

	return __($str);
} 
class I18N{

	public $language = 'en';
	public $open = false;
	static $arr = [];
	public $lang_file;
	public function __($str){ 
		if($this->open !== true || preg_match('/^(\d|\s)+$/', $str)) return $str;  
		return static::$arr[$str]?static::$arr[$str]:$str; 
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

  

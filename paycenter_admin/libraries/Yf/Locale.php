<?php
/**
 * Yf_Locale 语言包类
 *
 * 系统语言包采用的是php-gettext模块.
 *
 * @category   Framework
 * @package    Locale
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Yf_Locale
{
    /**
     * _options 设置语言包的选项
     *
     * $this->_options['lang'] 应用程序使用什么语言包.php-gettext支持的所有语言都可以.
     * 在ubuntu下使用sudo vim /usr/share/i18n/SUPPORTED 主要是utf8编码
     * $this->_options['domain'] 生成的.mo文件的名字.一般是应用程序名
     *
     * @var array
     * @access protected
     */
    protected $_options;

    /**
     * __construct 构造函数 对象初始化时设置语言包的参数
     *
     * @access public
     * @return void
     */
    public function __construct($path=null, $domain='HelloWorld', $codeset='UTF-8', $lang='zh_CN.UTF-8') 
    {
        $this->_options = array(
            'lang' => $lang,
            'path' => $path,
            'domain' => $domain,
            'codeset' => $codeset
        );

        $this->setApplicationLocale();
    }

    /**
     * setOptions 设置应用程序语言包的参数 放在在数组$this->_options中
     *
     * @param mixed $options
     * @access public
     * @return void
     */
    public function setOptions($options) 
    {
        
        if (!empty($options)) 
        {
            
            foreach ($options as $key => $option) 
            {
                $this->_options[$key] = $option;
            }
        }
    }

    /**
     * setApplicationLocale  设置应用程序语言包
     *
     * @access public
     * @return void
     */
    public function setApplicationLocale() 
    {
        putenv('LANG=' . $this->_options['lang']);
        setlocale(LC_ALL, $this->_options['lang']);  // bsd use zh_CN.UTF-8
        bindtextdomain($this->_options['domain'], $this->_options['path']); //设置某个域的mo文件路径 
        textdomain($this->_options['domain']); //设置gettext()函数从哪个域去找mo文件 
        bind_textdomain_codeset($this->_options['domain'], $this->_options['codeset']); //设置mo文件的编码为UTF-8 
    }
}
?>

<?php 
use Schnittstabil\Csrf\TokenService;
/**
 * composer require volnix/csrf
 * token表单验证
 * @weichat sunkangchina
 * @date    2017
 */
class CSRF{
    static $obj;
    /**
     * 取得token 名
     * @return  [type]
     * @weichat sunkangchina
     * @date    2017
     */
    static function name(){
        return "_csrf_token_";
    }
    static function ins(){
            if(self::$obj)
            {
                return self::$obj;
            }
            $ttl = 1440;  
            $key = md5('token');
            self::$obj = new TokenService($key, $ttl); 
            return self::$obj;
    }
    /**
     * 生成token
     * @param   boolean      $new
     * @return  
     * @weichat sunkangchina
     * @date    2017
     */
    static function create( ){ 
            return self::ins()->generate();
    }
    /**
     * 判断token是否正确
     * @param   [type]       $value
     * @return  bool
     * @weichat sunkangchina
     * @date    2017
     */
    static function check($token){
        if (self::ins()->validate($token)) {
                return true;
        }
        return false;
    }

}
<?php
/**
 * 用来加密解密数据。
 * 
 * @category   Framework
 * @package    Hash
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Hash 
{
    private static $_key = 'fjsakjlkg&*^%)(89042432';

    /**
     * Passport 加密函数
     *
     * @param    string        等待加密的原字串
     * @param    string        私有密匙(用于解密和加密)
     *
     * @return    string        原字串经过私有密匙加密后的结果
     */
    public static function encrypt($txt) 
    {
        //使用随机数发生器产生 0~32000 的值并 MD5()
        srand((double)microtime() *1000000);
        $encrypt_key = md5(rand(0, 32000));

        //变量初始化
        $ctr = 0;
        $tmp = '';

        //for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
        $txt_len = strlen($txt);
        $encrypt_key_len = strlen($encrypt_key);

        for ($i = 0; $i<$txt_len; $i++) 
        {
            //如果 $ctr = $encrypt_key 的长度，则 $ctr 清零
            $ctr = $ctr == $encrypt_key_len ? 0 : $ctr;

            //$tmp 字串在末尾增加两位，其第一位内容为 $encrypt_key 的第 $ctr 位，
            //第二位内容为 $txt 的第 $i 位与 $encrypt_key 的 $ctr 位取异或。然后 $ctr = $ctr + 1
            $tmp.= $encrypt_key[$ctr] . ($txt[$i]^$encrypt_key[$ctr++]);
        }

        //返回结果，结果为 passportKey() 函数返回值的 base65 编码结果
        return base64_encode(self::passportKey($tmp));
    }

    /**
     * Passport 解密函数
     *
     * @param        string        加密后的字串
     * @param        string        私有密匙(用于解密和加密)
     *
     * @return    string        字串经过私有密匙解密后的结果
     */
    public static function decrypt($txt) 
    {
        //$txt 的结果为加密后的字串经过 base64 解码，然后与私有密匙一起，
        //经过 passportKey() 函数处理后的返回值
        $txt = self::passportKey(base64_decode($txt));

        //变量初始化
        $tmp = '';

        $txt_len = strlen($txt);

        //for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
        for ($i = 0; $i <$txt_len; $i++) 
        {
            //$tmp 字串在末尾增加一位，其内容为 $txt 的第 $i 位，
            //与 $txt 的第 $i + 1 位取异或。然后 $i = $i + 1
            $tmp.= $txt[$i]^$txt[++$i];
        }

        //返回 $tmp 的值作为结果
        
        return $tmp;
    }

    /**
     * Passport 密匙处理函数
     *
     * @param        string        待加密或待解密的字串
     * @param        string        私有密匙(用于解密和加密)
     *
     * @return    string        处理后的密匙
     */
    public static function passportKey($txt) 
    {
        //将 $encrypt_key 赋为 $encrypt_key 经 md5() 后的值
        $encrypt_key = md5(self::$_key);
        //变量初始化
        $ctr = 0;
        $tmp = '';

        $txt_len = strlen($txt);
        $encrypt_key_len = strlen($encrypt_key);


        //for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
        
        for ($i = 0; $i <$txt_len; $i++) 
        {
            //如果 $ctr = $encrypt_key 的长度，则 $ctr 清零
            $ctr = $ctr == $encrypt_key_len ? 0 : $ctr;
            //$tmp 字串在末尾增加一位，其内容为 $txt 的第 $i 位，
            //与 $encrypt_key 的第 $ctr + 1 位取异或。然后 $ctr = $ctr + 1
            $tmp.= $txt[$i]^$encrypt_key[$ctr++];
        }

        //返回 $tmp 的值作为结果
        return $tmp;
    }

    /**
     * 设置key
     *
     * @param        array        待编码的数组
     *
     * @return    string        数组经编码后的字串
     */
    public static function setKey($key=null) 
    {
        if ($key)
        {
            self::$_key = $key;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 设置key
     *
     * @param        array        待编码的数组
     *
     * @return    string        数组经编码后的字串
     */
    public static function getKey() 
    {
        return self::$_key;
    }

    /**
     * 用户散列hash
     *
     * @param        $str        用于散列的字符串
     * @param        $level        散列的深度
     *
     * @return    string        数组经编码后的字串
     */
    public static function hashNum($str, $level=1, $dir=false)
    {
        if ($str)
        {
            $md5_user = md5(strtolower($str));

            if (1 == $level)
            {
                return hexdec($md5_user[0]);
            }
            else
            {
                $level_row = array();

                if ($dir)
                {
                    for ($i=0; $i<$level; $i++)
                    {
                        $level_row[$i] = hexdec($md5_user[$i]);
                    }
                }
                else
                {
                    for ($i=0; $i<$level; $i++)
                    {
                        $level_row[$i] = hexdec($md5_user[$i]);
                    }

                }

                return $level_row;
            }
        }
        else
        {
            return false;
        }
    }
}

/*
Yf_Hash::setKey(1111);

$data = array(
    'time'        => time(),
    'user_account'    => "xinze",
    'user_nickname'    => $user_nickname,
    'prize_count'    => 123,
    'da'    => array(1, 3, 4)
);

$str = http_build_query($data);

$encrypt_str = Yf_Hash::encrypt($str);

echo $encrypt_str;
echo '<br />';

$decrypt_str = Yf_Hash::decrypt($encrypt_str);
echo '<pre>';
parse_str($decrypt_str, $now_data);
print_r($now_data);
*/

?>
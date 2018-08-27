<?php   
/**
ios andriod php通用加密解密
$cr = new ECrypt(); 
echo $cr->decode("UTVYz6PgS6sCztmVhLAV6g==");
如果string是 a=1&b=1可通过以下变为数组 
parse_str($s,$arr); 
 */
class ECrypt {
 	public $iv = '0000000000000000';
    public $key = 'U1MjU1M0FDOUZ.1T';

    public function decode($string){ 
			$encryptedData = base64_decode($string);  
			return json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $encryptedData, MCRYPT_MODE_CBC, $this->iv)),true);   
    }

    public function encode($arr)
    {
        $json_str = json_encode($arr);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $json_str, MCRYPT_MODE_CBC, $this->iv));
    }
}
<?php
    use Qcloud\Sms\SmsSingleSender;
    class Sms
    {
        public static function send1($mob, $content, $tple_id = null,$data=[])
        {
            if (is_array($content)) {
                $content = encode_json($content);
            }
            $sms_config = Yf_Registry::get('sms_config');
            $name = $sms_config['sms_account'];
            $password = md5($sms_config['sms_pass']);
            $mob = $mob;
            $content = urlencode($content);
            $content = iconv("utf-8", "gb2312//IGNORE", $content);
            
            
            $url = "http://sms.b2b-builder.com/sms.php?name=" . $name . "&password=" . $password . "&mob=" . $mob . "&content=" . $content.'&data='. json_encode($data);
            
            if ($tple_id) {
                $url = $url . '&tpl_id=' . $tple_id;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
        
        /** 国际
         * 发送短信统一接口
         *
         */
        public static function sends($mob, $content, $tple_id = null)
        {
            try {
                $cmd = "curl -X 'POST' 'https://rest.nexmo.com/sms/json' -d 'from=Acme Inc' -d 'text=" . $content . "' -d 'to=39" . $mob . "' -d 'api_key=5ce8aae6  ' -d 'api_secret=4YCe5Lymz4Ds8lTh'";
                exec($cmd, $request);
                $a = implode(" ", $request);
                $data = json_decode($a, true);
                if ($data['messages'][0][status] == 0) {
                    return ture;
                }
                return false;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * 腾讯云短信接口
         * 国内外添加国家区号即可
         */
        public static function send2()
        {
            // 短信应用SDK AppID
            $appid = 1400046726; // 1400开头
            // 短信应用SDK AppKey
            $appkey = "37b6171fb455bd487b4ddafd6598a38a";
            // 需要发送短信的手机号码
            $phoneNumbers = ["18801963698","88751629","88751629","99185728","99188013","18356066378",''];
            // 短信模板ID，需要在短信应用中申请
            // $templateId = 148038;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
            $smsSign = "感谢您注册{1}，欢迎您。"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
    
            try {
                $ssender = new SmsSingleSender($appid, $appkey);
                $result = $ssender->send(0, "86", $phoneNumbers[5], "感谢您注册{1}，欢迎您。", "", "");
                $rsp = json_decode($result);
                echo $result;
            } catch(\Exception $e) {
                echo var_dump($e);
            }
            

        }
    
		public static function send($mob, $content, $tple_id=null,$data=[])
		{
			if (is_array($content))
			{
				$content = encode_json($content);
			}

			$sms_config = Yf_Registry::get('sms_config');

			$name     = $sms_config['sms_account'];
			$password = md5($sms_config['sms_pass']);

			$mob      = $mob;
			$content  = urlencode($content);
			$content  = iconv("utf-8", "gb2312//IGNORE", $content);

			$url = "http://sms.b2b-builder.com/sms.php?name=" . $name . "&password=" . $password . "&mob=" . $mob . "&content=" . $content.'&data='. json_encode($data);
			
			if ($tple_id)
			{
				$url = $url . '&tpl_id=' .  $tple_id;
			}
            
			$ch  = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

	}